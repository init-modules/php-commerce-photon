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
