<?php

namespace Init\CommercePhoton\Pages;

use Init\Photon\Data\PhotonDocumentData;

class CheckoutPageDefinition extends AbstractCommercePhotonPageDefinition
{
    public function key(): string
    {
        return 'commerce:checkout';
    }

    public function name(): string
    {
        return $this->copy('Checkout', 'Оформление заказа');
    }

    public function description(): ?string
    {
        return $this->copy(
            'Checkout page powered by init/commerce-order.',
            'Страница оформления заказа на базе init/commerce-order.',
        );
    }

    public function kind(): string
    {
        return 'page';
    }

    public function routePattern(): string
    {
        return $this->routeConfig('checkout', '/checkout');
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
        return $this->makeDocument('checkout', [
            [
                'id' => 'commerce-checkout-form',
                'module' => 'commerce-photon',
                'type' => 'commerce-checkout-form',
                'props' => [
                    'eyebrow' => $this->copy('Checkout', 'Оформление'),
                    'title' => $this->copy('Place your order', 'Оформить заказ'),
                    'body' => $this->copy(
                        'Review your active cart and leave contact details for the order snapshot.',
                        'Проверьте активную корзину и оставьте контактные данные для снимка заказа.',
                    ),
                    'nameLabel' => $this->copy('Name', 'Имя'),
                    'emailLabel' => $this->copy('Email', 'Email'),
                    'phoneLabel' => $this->copy('Phone', 'Телефон'),
                    'submitLabel' => $this->copy('Place order', 'Разместить заказ'),
                    'successTitle' => $this->copy('Order placed', 'Заказ создан'),
                    'cartHref' => $this->routeConfig('cart', '/cart'),
                ],
            ],
        ]);
    }
}
