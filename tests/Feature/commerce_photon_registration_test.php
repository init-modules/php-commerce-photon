<?php

use Init\Photon\Pages\Registry\PhotonPageRegistry;
use Init\Photon\Registry\PhotonIntegrationRegistry;

it('registers the commerce photon integration', function () {
    $integrations = app(PhotonIntegrationRegistry::class)->toArray();

    expect($integrations)->toHaveCount(1);
    expect($integrations[0]['module'])->toBe('commerce-photon');
    expect($integrations[0]['blocks'])->toHaveCount(6);
});

it('registers commerce storefront page definitions', function () {
    $pageRegistry = app(PhotonPageRegistry::class);
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
    $manifest = config('photon.localization_manifest');

    expect($manifest)->toHaveKey('commerce-photon::commerce-product-grid');
    expect($manifest)->toHaveKey('commerce-photon::commerce-product-detail');
    expect($manifest)->toHaveKey('commerce-photon::commerce-add-to-cart');
    expect($manifest)->toHaveKey('commerce-photon::commerce-cart-summary');
    expect($manifest)->toHaveKey('commerce-photon::commerce-checkout-form');
    expect($manifest)->toHaveKey('commerce-photon::commerce-order-list');
    expect($manifest['commerce-photon::commerce-product-grid']['localized'])->toContain('addToCartLabel');
    expect($manifest['commerce-photon::commerce-product-grid']['shared'])->toContain('columns');
    expect($manifest['commerce-photon::commerce-order-list']['localized'])->toContain('authLabel');
    expect($manifest['commerce-photon::commerce-order-list']['localized'])->toContain('eyebrow');
    expect($manifest['commerce-photon::commerce-order-list']['shared'])->toContain('limit');
});
