<?php

return [
    'routes' => [
        'catalog' => env('COMMERCE_WEBSITE_BUILDER_CATALOG_ROUTE', '/catalog'),
        'product' => env('COMMERCE_WEBSITE_BUILDER_PRODUCT_ROUTE', '/catalog/{slug}'),
        'cart' => env('COMMERCE_WEBSITE_BUILDER_CART_ROUTE', '/cart'),
        'checkout' => env('COMMERCE_WEBSITE_BUILDER_CHECKOUT_ROUTE', '/checkout'),
        'account_orders' => env('COMMERCE_WEBSITE_BUILDER_ACCOUNT_ORDERS_ROUTE', '/account/orders'),
    ],
    'catalog_limit' => (int) env('COMMERCE_WEBSITE_BUILDER_CATALOG_LIMIT', 24),
    'localization_manifest' => [
        'commerce-website-builder::commerce-product-grid' => [
            'localized' => [
                'body',
                'cardCtaLabel',
                'emptyBody',
                'emptyTitle',
                'eyebrow',
                'title',
            ],
            'shared' => [
                'columns',
                'showDescription',
            ],
        ],
        'commerce-website-builder::commerce-product-detail' => [
            'localized' => [
                'backLabel',
                'eyebrow',
            ],
            'shared' => [
                'showDescription',
                'showImage',
                'showSku',
            ],
        ],
        'commerce-website-builder::commerce-add-to-cart' => [
            'localized' => [
                'buttonLabel',
                'quantityLabel',
                'successLabel',
            ],
            'shared' => [
                'cartHref',
            ],
        ],
        'commerce-website-builder::commerce-cart-summary' => [
            'localized' => [
                'catalogLabel',
                'checkoutLabel',
                'emptyBody',
                'emptyTitle',
                'eyebrow',
                'title',
            ],
            'shared' => [
                'catalogHref',
                'checkoutHref',
            ],
        ],
        'commerce-website-builder::commerce-checkout-form' => [
            'localized' => [
                'body',
                'emailLabel',
                'eyebrow',
                'nameLabel',
                'phoneLabel',
                'submitLabel',
                'successTitle',
                'title',
            ],
            'shared' => [
                'cartHref',
            ],
        ],
        'commerce-website-builder::commerce-order-list' => [
            'localized' => [
                'catalogLabel',
                'emptyBody',
                'emptyTitle',
                'eyebrow',
                'itemCountLabel',
                'orderLabel',
                'title',
                'totalLabel',
            ],
            'shared' => [
                'catalogHref',
                'limit',
            ],
        ],
    ],
];
