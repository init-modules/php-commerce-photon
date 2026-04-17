<?php

namespace Init\CommerceWebsiteBuilder;

use Init\WebsiteBuilder\Contracts\WebsiteBuilderIntegration;

class CommerceWebsiteBuilderIntegration implements WebsiteBuilderIntegration
{
    public function module(): string
    {
        return 'commerce-website-builder';
    }

    public function label(): string
    {
        return 'Commerce Website Builder';
    }

    public function blocks(): array
    {
        return [
            [
                'type' => 'commerce-product-grid',
                'label' => 'Commerce Product Grid',
                'category' => 'Commerce',
                'description' => 'Catalog section with template-owned intro copy and live product cards.',
            ],
            [
                'type' => 'commerce-product-detail',
                'label' => 'Commerce Product Detail',
                'category' => 'Commerce',
                'description' => 'Product detail surface bound to the current catalog item.',
            ],
            [
                'type' => 'commerce-add-to-cart',
                'label' => 'Commerce Add To Cart',
                'category' => 'Commerce',
                'description' => 'Quantity selector and add-to-cart action for the current catalog item.',
            ],
            [
                'type' => 'commerce-cart-summary',
                'label' => 'Commerce Cart Summary',
                'category' => 'Commerce',
                'description' => 'Current cart totals, line count and checkout call to action.',
            ],
            [
                'type' => 'commerce-checkout-form',
                'label' => 'Commerce Checkout Form',
                'category' => 'Commerce',
                'description' => 'Lightweight checkout form that places an order from the active cart.',
            ],
        ];
    }
}
