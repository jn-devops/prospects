<?php

namespace Homeful\Prospects;

use Homeful\Prospects\Commands\ProspectsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ProspectsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('prospects')
            ->hasConfigFile(['data', 'prospects', 'media-library'])
            ->hasViews()
            ->hasMigration('create_prospects_table')
            ->hasCommand(ProspectsCommand::class);
    }
}
