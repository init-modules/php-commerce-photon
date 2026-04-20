<?php

namespace Init\CommerceWebsiteBuilder\Pages;

use Illuminate\Database\Eloquent\Builder;
use Init\Commerce\Catalog\Models\CatalogItem;
use Init\WebsiteBuilder\Data\WebsiteBuilderDocumentData;
use Init\WebsiteBuilder\Pages\Contracts\WebsiteBuilderSearchablePageDefinition;
use Init\WebsiteBuilder\Search\Support\WebsiteBuilderSearchPageCandidate;

class ProductPageDefinition extends AbstractCommerceWebsiteBuilderPageDefinition implements WebsiteBuilderSearchablePageDefinition
{
    public function key(): string
    {
        return 'commerce:product';
    }

    public function name(): string
    {
        return $this->copy('Product Template', 'Шаблон товара');
    }

    public function description(): ?string
    {
        return $this->copy(
            'Reusable product detail template bound to the current catalog item.',
            'Переиспользуемый шаблон товара, связанный с текущей позицией каталога.',
        );
    }

    public function kind(): string
    {
        return 'template';
    }

    public function routePattern(): string
    {
        return $this->routeConfig('product', '/catalog/{slug}');
    }

    public function navigationRoute(): ?string
    {
        $item = CatalogItem::query()
            ->publiclyVisible()
            ->orderBy('name')
            ->first();

        if (! $item instanceof CatalogItem) {
            return null;
        }

        return $this->buildRoute($this->routePattern(), [
            'slug' => $item->slug,
        ]);
    }

    public function canOpen(): bool
    {
        return filled($this->navigationRoute());
    }

    public function matchesPath(string $path): ?array
    {
        $matches = $this->matchRoutePattern($this->routePattern(), $this->normalizePath($path));

        if ($matches === null || blank($matches['slug'] ?? null)) {
            return null;
        }

        return [
            'slug' => (string) $matches['slug'],
        ];
    }

    public function searchCandidates(string $query, int $limit = 20): iterable
    {
        if (trim($query) === '') {
            return [];
        }

        $like = '%' . $query . '%';

        return CatalogItem::query()
            ->publiclyVisible()
            ->where(function (Builder $builder) use ($like): void {
                $builder
                    ->where('name', 'like', $like)
                    ->orWhere('sku', 'like', $like)
                    ->orWhere('description', 'like', $like);
            })
            ->orderBy('name')
            ->limit(max(1, min($limit, 50)))
            ->get()
            ->map(fn (CatalogItem $item): WebsiteBuilderSearchPageCandidate => new WebsiteBuilderSearchPageCandidate(
                route: $this->buildRoute($this->routePattern(), [
                    'slug' => $item->slug,
                ]),
                name: $item->name,
                context: [
                    'slug' => $item->slug,
                ],
            ))
            ->all();
    }

    public function fallbackDocument(): WebsiteBuilderDocumentData
    {
        return $this->makeDocument('product', [
            [
                'id' => 'commerce-product-detail',
                'module' => 'commerce-website-builder',
                'type' => 'commerce-product-detail',
                'props' => [
                    'eyebrow' => $this->copy('Product', 'Товар'),
                    'backLabel' => $this->copy('Back to catalog', 'Назад в каталог'),
                    'showSku' => true,
                    'showDescription' => true,
                    'showImage' => true,
                ],
                'bindings' => [
                    'product' => [
                        'source' => 'commerceProduct',
                        'path' => 'product',
                        'mode' => 'write',
                    ],
                ],
            ],
            [
                'id' => 'commerce-add-to-cart',
                'module' => 'commerce-website-builder',
                'type' => 'commerce-add-to-cart',
                'props' => [
                    'quantityLabel' => $this->copy('Quantity', 'Количество'),
                    'buttonLabel' => $this->copy('Add to cart', 'Добавить в корзину'),
                    'successLabel' => $this->copy('Added to cart', 'Добавлено в корзину'),
                    'cartHref' => $this->routeConfig('cart', '/cart'),
                ],
                'bindings' => [
                    'product' => [
                        'source' => 'commerceProduct',
                        'path' => 'product',
                        'mode' => 'read',
                    ],
                ],
            ],
        ]);
    }

    public function resolveResources(array $context): array
    {
        $item = CatalogItem::query()
            ->publiclyVisible()
            ->where('slug', (string) ($context['slug'] ?? ''))
            ->firstOrFail();

        return [
            'commerceProduct' => [
                'product' => $this->productPayload($item),
            ],
        ];
    }

    public function persistResources(array $context, array $resources): void
    {
        $payload = $resources['commerceProduct']['product'] ?? null;

        if (! is_array($payload)) {
            return;
        }

        $item = CatalogItem::query()
            ->where('slug', (string) ($context['slug'] ?? ''))
            ->first();

        if (! $item instanceof CatalogItem) {
            return;
        }

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

    protected function productPayload(CatalogItem $item): array
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
            'catalogHref' => $this->routeConfig('catalog', '/catalog'),
            'coverImage' => $item->getFirstMediaUrl('cover') ?: null,
        ];
    }
}
