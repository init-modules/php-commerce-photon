<?php

namespace Init\CommercePhoton;

use Init\CommercePhoton\Pages\AccountOrdersPageDefinition;
use Init\CommercePhoton\Pages\CartPageDefinition;
use Init\CommercePhoton\Pages\CatalogPageDefinition;
use Init\CommercePhoton\Pages\CheckoutPageDefinition;
use Init\CommercePhoton\Pages\ProductPageDefinition;
use Init\Photon\Pages\Registry\PhotonPageRegistry;
use Init\Photon\Registry\PhotonIntegrationRegistry;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RootServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('commerce-photon')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $manifest = config('commerce-photon.localization_manifest', []);

        if (! is_array($manifest) || $manifest === []) {
            return;
        }

        config()->set(
            'photon.localization_manifest',
            array_replace_recursive((array) config('photon.localization_manifest', []), $manifest),
        );
    }

    public function packageBooted(): void
    {
        $this->app->make(PhotonIntegrationRegistry::class)
            ->register($this->app->make(CommercePhotonIntegration::class));

        $pageRegistry = $this->app->make(PhotonPageRegistry::class);

        foreach ([CatalogPageDefinition::class, ProductPageDefinition::class, CartPageDefinition::class, CheckoutPageDefinition::class, AccountOrdersPageDefinition::class] as $definition) {
            $pageRegistry->register($this->app->make($definition));
        }
    }
}
