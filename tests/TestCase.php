<?php

namespace Tests;

use Init\CommercePhoton\RootServiceProvider;
use Init\Photon\RootServiceProvider as PhotonRootServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            PhotonRootServiceProvider::class,
            RootServiceProvider::class,
        ];
    }
}
