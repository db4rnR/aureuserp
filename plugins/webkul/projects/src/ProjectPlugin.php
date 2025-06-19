<?php

declare(strict_types=1);

namespace Webkul\Project;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use ReflectionClass;
use Webkul\Project\Filament\Clusters\Settings\Pages\ManageTasks;
use Webkul\Support\Package;

class ProjectPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'projects';
    }

    public function register(Panel $panel): void
    {
        if (! Package::isPluginInstalled($this->getId())) {
            return;
        }

        $panel
            ->when($panel->getId() === 'admin', function (Panel $panel): void {
                $panel
                    ->discoverResources(in: $this->getPluginBasePath('/Filament/Resources'), for: 'Webkul\\Project\\Filament\\Resources')
                    ->discoverPages(in: $this->getPluginBasePath('/Filament/Pages'), for: 'Webkul\\Project\\Filament\\Pages')
                    ->discoverClusters(in: $this->getPluginBasePath('/Filament/Clusters'), for: 'Webkul\\Project\\Filament\\Clusters')
                    ->discoverWidgets(in: $this->getPluginBasePath('/Filament/Widgets'), for: 'Webkul\\Project\\Filament\\Widgets')
                    ->navigationItems([
                        NavigationItem::make('Settings')->url(fn (): string => ManageTasks::getUrl())
                            ->icon('heroicon-o-wrench')
                            ->group('Project')
                            ->sort(3),
                    ]);
            });
    }

    public function boot(Panel $panel): void
    {
        //
    }

    private function getPluginBasePath($path = null): string
    {
        $reflector = new ReflectionClass(self::class);

        return dirname($reflector->getFileName()).($path ?? '');
    }
}
