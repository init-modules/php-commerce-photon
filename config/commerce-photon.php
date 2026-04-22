<?php

return [
    'routes' => [
        'catalog' => env('COMMERCE_PHOTON_CATALOG_ROUTE', '/catalog'),
        'product' => env('COMMERCE_PHOTON_PRODUCT_ROUTE', '/catalog/{slug}'),
        'cart' => env('COMMERCE_PHOTON_CART_ROUTE', '/cart'),
        'checkout' => env('COMMERCE_PHOTON_CHECKOUT_ROUTE', '/checkout'),
        'account_orders' => env('COMMERCE_PHOTON_ACCOUNT_ORDERS_ROUTE', '/account/orders'),
    ],
    'catalog_limit' => (int) env('COMMERCE_PHOTON_CATALOG_LIMIT', 24),
    'localization_manifest' => [
        'commerce-photon::commerce-product-grid' => [
            'localized' => [
                'addToCartLabel',
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
        'commerce-photon::commerce-product-detail' => [
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
        'commerce-photon::commerce-add-to-cart' => [
            'localized' => [
                'buttonLabel',
                'quantityLabel',
                'successLabel',
            ],
            'shared' => [
                'cartHref',
            ],
        ],
        'commerce-photon::commerce-cart-summary' => [
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
        'commerce-photon::commerce-checkout-form' => [
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
        'commerce-photon::commerce-order-list' => [
            'localized' => [
                'authLabel',
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
