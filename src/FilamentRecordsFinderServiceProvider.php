<?php

namespace Yeganehha\FilamentRecordsFinder;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Yeganehha\FilamentRecordsFinder\Livewire\RecordsModalContent;

class FilamentRecordsFinderServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-records-finder';

    public static string $viewNamespace = 'filament-records-finder';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('yeganehha/filament-records-finder');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function bootingPackage(): void
    {
        Livewire::component('records-modal-content', RecordsModalContent::class);
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            AlpineComponent::make('table', __DIR__ . '/../dist/components/table.js'),
        ], 'yeganehha/filament-records-finder');
    }
}
