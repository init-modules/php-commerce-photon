<?php

namespace Init\CommercePhoton\Pages;

use Illuminate\Database\Eloquent\Builder;
use Init\Commerce\Catalog\Models\CatalogItem;
use Init\Photon\Data\PhotonDocumentData;
use Init\Photon\Pages\Contracts\PhotonSearchablePageDefinition;
use Init\Photon\Search\Support\PhotonSearchPageCandidate;

class CatalogPageDefinition extends AbstractCommercePhotonPageDefinition implements PhotonSearchablePageDefinition
{
    public function key(): string
    {
        return 'commerce:catalog';
    }

    public function name(): string
    {
        return $this->copy('Commerce Catalog', 'Каталог');
    }

    public function description(): ?string
    {
        return $this->copy(
            'Template-owned catalog page with live product cards from init/commerce-catalog.',
            'Страница каталога из шаблона с живыми карточками товаров из init/commerce-catalog.',
        );
    }

    public function kind(): string
    {
        return 'page';
    }

    public function routePattern(): string
    {
        return $this->routeConfig('catalog', '/catalog');
    }

    public function navigationRoute(): ?string
    {
        return $this->routePattern();
    }

    public function canOpen(): bool
    {
        return true;
    }

    public function matchesPath(string $path): ?array
    {
        return $this->normalizePath($path) === $this->routePattern() ? [] : null;
    }

    public function searchCandidates(string $query, int $limit = 20): iterable
    {
        if (trim($query) !== '') {
            return [];
        }

        return [
            new PhotonSearchPageCandidate(
                route: $this->routePattern(),
                name: $this->name(),
            ),
        ];
    }

    public function fallbackDocument(): PhotonDocumentData
    {
        return $this->makeDocument('catalog', [
            [
                'id' => 'commerce-product-grid',
                'module' => 'commerce-photon',
                'type' => 'commerce-product-grid',
                'props' => [
                    'eyebrow' => $this->copy('Catalog', 'Каталог'),
                    'title' => $this->copy('Products and services', 'Товары и услуги'),
                    'body' => $this->copy(
                        'Browse live catalog items managed by the commerce packages.',
                        'Просматривайте живые позиции каталога из commerce-пакетов.',
                    ),
                    'emptyTitle' => $this->copy('No products yet', 'Товаров пока нет'),
                    'emptyBody' => $this->copy(
                        'Add active catalog items to unlock this storefront section.',
                        'Добавьте активные позиции каталога, чтобы открыть этот раздел витрины.',
                    ),
                    'cardCtaLabel' => $this->copy('View product', 'Открыть товар'),
                    'addToCartLabel' => $this->copy('Add to cart', 'В корзину'),
                    'columns' => 3,
                    'showDescription' => true,
                ],
                'bindings' => [
                    'items' => [
                        'source' => 'commerceCatalog',
                        'path' => 'items',
                        'mode' => 'write',
                    ],
                ],
            ],
        ]);
    }

    public function resolveResources(array $context): array
    {
        $limit = max(1, min((int) config('commerce-photon.catalog_limit', 24), 60));

        $items = CatalogItem::query()
            ->publiclyVisible()
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(fn (CatalogItem $item): array => $this->catalogItemPayload($item))
            ->all();

        return [
            'commerceCatalog' => [
                'items' => $items,
            ],
        ];
    }

    public function persistResources(array $context, array $resources): void
    {
        $catalogResource = $resources['commerceCatalog'] ?? null;

        if (! is_array($catalogResource)) {
            return;
        }

        foreach (['items', 'products', 'services'] as $collectionKey) {
            $items = $catalogResource[$collectionKey] ?? null;

            if (! is_array($items)) {
                continue;
            }

            foreach ($items as $payload) {
                if (! is_array($payload)) {
                    continue;
                }

                $id = $payload['id'] ?? null;

                if (! is_string($id) || trim($id) === '') {
                    continue;
                }

                $item = CatalogItem::query()->whereKey($id)->first();

                if (! $item instanceof CatalogItem) {
                    continue;
                }

                $this->persistCatalogItemPayload($item, $payload);
            }
        }
    }

    protected function catalogItemPayload(CatalogItem $item): array
    {
        return [
            'id' => (string) $item->getKey(),
            'type' => $item->type?->value,
            'status' => $item->status?->value,
            'sku' => $item->sku,
            'name' => $item->name,
            'slug' => $item->slug,
            'description' => $item->description,
            'publicPriceAmount' => (int) $item->effective_price_amount,
            'currency' => $item->currency,
            'inventoryMode' => $item->inventory_mode?->value,
            'tracked' => $item->isTracked(),
            'href' => $this->buildRoute($this->routeConfig('product', '/catalog/{slug}'), [
                'slug' => $item->slug,
            ]),
            'coverImage' => $item->getFirstMediaUrl('cover') ?: null,
        ];
    }

    protected function persistCatalogItemPayload(CatalogItem $item, array $payload): void
    {
        $updates = [];

        foreach (['name', 'sku', 'description'] as $field) {
            if (array_key_exists($field, $payload)) {
                $updates[$field] = is_string($payload[$field])
                    ? trim($payload[$field])
                    : null;
            }
        }

        if (isset($updates['name']) && $updates['name'] === '') {
            unset($updates['name']);
        }

        if ($updates !== []) {
            $item->fill($updates)->save();
        }
    }
}
