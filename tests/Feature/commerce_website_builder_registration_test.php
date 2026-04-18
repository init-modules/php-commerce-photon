<?php

use Init\WebsiteBuilder\Pages\Registry\WebsiteBuilderPageRegistry;
use Init\WebsiteBuilder\Registry\WebsiteBuilderIntegrationRegistry;

it('registers the commerce website builder integration', function () {
    $integrations = app(WebsiteBuilderIntegrationRegistry::class)->toArray();

    expect($integrations)->toHaveCount(1);
    expect($integrations[0]['module'])->toBe('commerce-website-builder');
    expect($integrations[0]['blocks'])->toHaveCount(6);
});

it('registers commerce storefront page definitions', function () {
    $pageRegistry = app(WebsiteBuilderPageRegistry::class);
    $keys = collect($pageRegistry->all())
        ->map(fn ($page): string => $page->key())
        ->values()
        ->all();

    expect($keys)->toContain(
        'commerce:catalog',
        'commerce:product',
        'commerce:cart',
        'commerce:checkout',
        'commerce:account-orders',
    );
});

it('registers commerce block localization manifest entries', function () {
    $manifest = config('website-builder.localization_manifest');

    expect($manifest)->toHaveKey('commerce-website-builder::commerce-product-grid');
    expect($manifest)->toHaveKey('commerce-website-builder::commerce-product-detail');
    expect($manifest)->toHaveKey('commerce-website-builder::commerce-add-to-cart');
    expect($manifest)->toHaveKey('commerce-website-builder::commerce-cart-summary');
    expect($manifest)->toHaveKey('commerce-website-builder::commerce-checkout-form');
    expect($manifest)->toHaveKey('commerce-website-builder::commerce-order-list');
    expect($manifest['commerce-website-builder::commerce-order-list']['localized'])->toContain('eyebrow');
    expect($manifest['commerce-website-builder::commerce-order-list']['shared'])->toContain('limit');
});
