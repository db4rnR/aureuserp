<?php

declare(strict_types=1);

namespace Webkul\Website;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Route;
use Webkul\Support\Console\Commands\InstallCommand;
use Webkul\Support\Console\Commands\UninstallCommand;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;
use Webkul\Website\Http\Responses\LogoutResponse;

class WebsiteServiceProvider extends PackageServiceProvider
{
    public static string $name = 'website';

    public static string $viewNamespace = 'website';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2025_03_10_094011_create_website_pages_table',
                '2025_03_10_064655_alter_partners_partners_table',
            ])
            ->runsMigrations()
            ->hasSeeder(Database\Seeders\DatabaseSeeder::class)
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->installDependencies()
                    ->runsMigrations()
                    ->runsSeeders();
            })
            ->hasSettings([
                '2025_03_10_094021_create_website_contact_settings',
            ])
            ->runsSettings()
            ->hasUninstallCommand(function (UninstallCommand $command): void {});
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('website', __DIR__.'/../resources/dist/website.css'),
        ], 'website');

        if (! Package::isPluginInstalled(self::$name)) {
            Route::get('/', fn () => redirect()->route('filament.admin..'));
        }
    }

    public function packageRegistered(): void
    {
        $this->app->bind(\Filament\Auth\Http\Responses\Contracts\LogoutResponse::class, LogoutResponse::class);
    }
}
