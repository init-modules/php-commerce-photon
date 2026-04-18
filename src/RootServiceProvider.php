<?php

namespace Init\CommerceWebsiteBuilder;

use Init\CommerceWebsiteBuilder\Pages\AccountOrdersPageDefinition;
use Init\CommerceWebsiteBuilder\Pages\CartPageDefinition;
use Init\CommerceWebsiteBuilder\Pages\CatalogPageDefinition;
use Init\CommerceWebsiteBuilder\Pages\CheckoutPageDefinition;
use Init\CommerceWebsiteBuilder\Pages\ProductPageDefinition;
use Init\WebsiteBuilder\Pages\Registry\WebsiteBuilderPageRegistry;
use Init\WebsiteBuilder\Registry\WebsiteBuilderIntegrationRegistry;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RootServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('commerce-website-builder')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $manifest = config('commerce-website-builder.localization_manifest', []);

        if (! is_array($manifest) || $manifest === []) {
            return;
        }

        config()->set(
            'website-builder.localization_manifest',
            array_replace_recursive((array) config('website-builder.localization_manifest', []), $manifest),
        );
    }

    public function packageBooted(): void
    {
        $this->app->make(WebsiteBuilderIntegrationRegistry::class)
            ->register($this->app->make(CommerceWebsiteBuilderIntegration::class));

        $pageRegistry = $this->app->make(WebsiteBuilderPageRegistry::class);

        foreach ([CatalogPageDefinition::class, ProductPageDefinition::class, CartPageDefinition::class, CheckoutPageDefinition::class, AccountOrdersPageDefinition::class] as $definition) {
            $pageRegistry->register($this->app->make($definition));
        }
    }
}
