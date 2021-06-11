<?php

namespace DIJ\ApiFactories;

use DIJ\ApiFactories\Commands\ApiFactoryMakeCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ApiFactoriesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-api-factories')
            ->hasCommand(ApiFactoryMakeCommand::class);
    }
}
