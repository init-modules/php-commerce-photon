<?php

return [
    'routes' => [
        'catalog' => env('COMMERCE_WEBSITE_BUILDER_CATALOG_ROUTE', '/catalog'),
        'product' => env('COMMERCE_WEBSITE_BUILDER_PRODUCT_ROUTE', '/catalog/{slug}'),
        'cart' => env('COMMERCE_WEBSITE_BUILDER_CART_ROUTE', '/cart'),
        'checkout' => env('COMMERCE_WEBSITE_BUILDER_CHECKOUT_ROUTE', '/checkout'),
    ],
    'catalog_limit' => (int) env('COMMERCE_WEBSITE_BUILDER_CATALOG_LIMIT', 24),
];
