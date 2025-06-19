<?php

declare(strict_types=1);

namespace Webkul\Recruitment;

use Filament\Contracts\Plugin;
use Filament\Panel;
use ReflectionClass;
use Webkul\Support\Package;

class RecruitmentPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'recruitments';
    }

    public function register(Panel $panel): void
    {
        if (! Package::isPluginInstalled($this->getId())) {
            return;
        }

        $panel
            ->when($panel->getId() === 'admin', function (Panel $panel): void {
                $panel->discoverResources(in: $this->getPluginBasePath('/Filament/Resources'), for: 'Webkul\\Recruitment\\Filament\\Resources')
                    ->discoverPages(in: $this->getPluginBasePath('/Filament/Pages'), for: 'Webkul\\Recruitment\\Filament\\Pages')
                    ->discoverClusters(in: $this->getPluginBasePath('/Filament/Clusters'), for: 'Webkul\\Recruitment\\Filament\\Clusters')
                    ->discoverWidgets(in: $this->getPluginBasePath('/Filament/Widgets'), for: 'Webkul\\Recruitment\\Filament\\Widgets');
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
