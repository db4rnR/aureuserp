<?php

declare(strict_types=1);

namespace Guava\FilamentIconPicker;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class FilamentIconPickerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-icon-picker')
            ->hasViews()
            ->hasConfigFile('icon-picker');
    }

    public function bootingPackage(): void
    {
        parent::bootingPackage();

        FilamentAsset::register([
            Css::make('filament-icon-picker-stylesheet', __DIR__.'/../dist/plugin.css'),
        ], package: 'guava/filament-icon-picker');
    }
}
