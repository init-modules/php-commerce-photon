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
];
