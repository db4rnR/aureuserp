<?php

declare(strict_types=1);

namespace Webkul\Chatter;

use Filament\Contracts\Plugin;
use Filament\Panel;
use ReflectionClass;

class ChatterPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'chatter';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(in: $this->getPluginBasePath('/Filament/Resources'), for: 'Webkul\\Chatter\\Filament\\Resources')
            ->discoverPages(in: $this->getPluginBasePath('/Filament/Pages'), for: 'Webkul\\Chatter\\Filament\\Pages')
            ->discoverClusters(in: $this->getPluginBasePath('/Filament/Clusters'), for: 'Webkul\\Chatter\\Filament\\Clusters')
            ->discoverClusters(in: $this->getPluginBasePath('/Filament/Widgets'), for: 'Webkul\\Chatter\\Filament\\Widgets');
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
