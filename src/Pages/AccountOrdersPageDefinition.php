<?php

namespace Init\CommerceWebsiteBuilder\Pages;

use Init\WebsiteBuilder\Data\WebsiteBuilderDocumentData;

class AccountOrdersPageDefinition extends AbstractCommerceWebsiteBuilderPageDefinition
{
    public function key(): string
    {
        return 'commerce:account-orders';
    }

    public function name(): string
    {
        return $this->copy('Account Orders', 'Заказы в личном кабинете');
    }

    public function description(): ?string
    {
        return $this->copy(
            'Customer order history page powered by init/commerce-order.',
            'Страница истории заказов клиента на базе init/commerce-order.',
        );
    }

    public function kind(): string
    {
        return 'page';
    }

    public function routePattern(): string
    {
        return $this->routeConfig('account_orders', '/account/orders');
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

    public function fallbackDocument(): WebsiteBuilderDocumentData
    {
        return $this->makeDocument('account-orders', [
            [
                'id' => 'commerce-order-list',
                'module' => 'commerce-website-builder',
                'type' => 'commerce-order-list',
                'props' => [
                    'eyebrow' => $this->copy('Account', 'Личный кабинет'),
                    'title' => $this->copy('Your orders', 'Ваши заказы'),
                    'emptyTitle' => $this->copy('No orders yet', 'Заказов пока нет'),
                    'emptyBody' => $this->copy(
                        'Checkout your first cart to see order history here.',
                        'Оформите первую корзину, чтобы увидеть историю заказов.',
                    ),
                    'orderLabel' => $this->copy('Order', 'Заказ'),
                    'totalLabel' => $this->copy('Total', 'Итого'),
                    'itemCountLabel' => $this->copy('items', 'позиций'),
                    'catalogLabel' => $this->copy('Open catalog', 'Открыть каталог'),
                    'catalogHref' => $this->routeConfig('catalog', '/catalog'),
                    'limit' => 20,
                ],
            ],
        ]);
    }
}
