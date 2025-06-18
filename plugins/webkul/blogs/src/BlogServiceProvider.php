<?php

declare(strict_types=1);

namespace Webkul\Blog;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Webkul\Support\Console\Commands\InstallCommand;
use Webkul\Support\Console\Commands\UninstallCommand;
use Webkul\Support\Package;
use Webkul\Support\PackageServiceProvider;

final class BlogServiceProvider extends PackageServiceProvider
{
    public static string $name = 'blogs';

    public static string $viewNamespace = 'blogs';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(self::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2025_03_06_093011_create_blogs_categories_table',
                '2025_03_06_094011_create_blogs_posts_table',
                '2025_03_07_065635_create_blogs_tags_table',
                '2025_03_07_065715_create_blogs_post_tags_table',
            ])
            ->runsMigrations()
            ->hasSettings([
            ])
            ->runsSettings()
            ->hasDependencies([
                'website',
            ])
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->installDependencies()
                    ->runsMigrations();
            })
            ->hasUninstallCommand(function (UninstallCommand $command): void {});
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('blogs', __DIR__.'/../resources/dist/blogs.css'),
        ], 'blogs');
    }
}
