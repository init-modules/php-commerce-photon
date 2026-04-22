<?php

namespace Init\CommercePhoton\Pages;

use Init\Photon\Data\PhotonDocumentData;

class CartPageDefinition extends AbstractCommercePhotonPageDefinition
{
    public function key(): string
    {
        return 'commerce:cart';
    }

    public function name(): string
    {
        return $this->copy('Cart', 'Корзина');
    }

    public function description(): ?string
    {
        return $this->copy(
            'Cart page powered by init/commerce-cart.',
            'Страница корзины на базе init/commerce-cart.',
        );
    }

    public function kind(): string
    {
        return 'page';
    }

    public function routePattern(): string
    {
        return $this->routeConfig('cart', '/cart');
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

    public function fallbackDocument(): PhotonDocumentData
    {
        return $this->makeDocument('cart', [
            [
                'id' => 'commerce-cart-summary',
                'module' => 'commerce-photon',
                'type' => 'commerce-cart-summary',
                'props' => [
                    'eyebrow' => $this->copy('Cart', 'Корзина'),
                    'title' => $this->copy('Your cart', 'Ваша корзина'),
                    'emptyTitle' => $this->copy('Your cart is empty', 'Корзина пуста'),
                    'emptyBody' => $this->copy(
                        'Add a catalog item to start checkout.',
                        'Добавьте товар из каталога, чтобы перейти к оформлению.',
                    ),
                    'checkoutLabel' => $this->copy('Checkout', 'Оформить заказ'),
                    'catalogLabel' => $this->copy('Continue shopping', 'Продолжить покупки'),
                    'catalogHref' => $this->routeConfig('catalog', '/catalog'),
                    'checkoutHref' => $this->routeConfig('checkout', '/checkout'),
                ],
            ],
        ]);
    }
}
