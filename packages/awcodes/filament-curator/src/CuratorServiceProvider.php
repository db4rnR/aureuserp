<?php

declare(strict_types=1);

namespace Awcodes\Curator;

use Awcodes\Curator\Commands\UpgradeCommand;
use Awcodes\Curator\Components\Modals\CuratorCuration;
use Awcodes\Curator\Components\Modals\CuratorPanel;
use Awcodes\Curator\Models\Media;
use Awcodes\Curator\Observers\MediaObserver;
use Awcodes\Curator\Resources\MediaResource;
use Awcodes\Curator\View\Components\Curation;
use Awcodes\Curator\View\Components\Glider;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CuratorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('curator')
            ->hasConfigFile()
            ->hasRoute('web')
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations(['create_media_table', 'add_tenant_aware_column_to_media_table'])
            ->hasCommands([
                UpgradeCommand::class,
            ])
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->publishMigrations()
                    ->askToRunMigrations();
            });
    }

    public function packageBooted(): void
    {
        app()->bind(Media::class, config('curator.model'));
        app()->bind(MediaResource::class, config('curator.resources.resource'));

        app(Media::class)::observe(MediaObserver::class);

        Livewire::component('curator-panel', CuratorPanel::class);
        Livewire::component('curator-curation', CuratorCuration::class);

        Blade::component('curator-glider', Glider::class);
        Blade::component('curator-curation', Curation::class);

        FilamentAsset::register([
            AlpineComponent::make('curation', __DIR__.'/../resources/dist/curation.js'),
            Css::make('curator', __DIR__.'/../resources/dist/curator.css')->loadedOnRequest(),
        ], 'awcodes/curator');
    }
}
