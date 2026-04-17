<?php

namespace Tests;

use Init\CommerceWebsiteBuilder\RootServiceProvider;
use Init\WebsiteBuilder\RootServiceProvider as WebsiteBuilderRootServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            WebsiteBuilderRootServiceProvider::class,
            RootServiceProvider::class,
        ];
    }
}
