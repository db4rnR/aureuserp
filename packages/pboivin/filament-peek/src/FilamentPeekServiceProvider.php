<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class FilamentPeekServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name(FilamentPeekPlugin::ID)
            ->hasTranslations()
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('preview');
    }
}
