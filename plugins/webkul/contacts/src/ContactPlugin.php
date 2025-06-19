<?php

declare(strict_types=1);

namespace Webkul\Contact;

use Filament\Contracts\Plugin;
use Filament\Panel;
use ReflectionClass;
use Webkul\Support\Package;

class ContactPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'contacts';
    }

    public function register(Panel $panel): void
    {
        if (! Package::isPluginInstalled('contacts')) {
            return;
        }

        $panel
            ->when($panel->getId() === 'admin', function (Panel $panel): void {
                $panel->discoverResources(in: $this->getPluginBasePath('/Filament/Resources'), for: 'Webkul\\Contact\\Filament\\Resources')
                    ->discoverPages(in: $this->getPluginBasePath('/Filament/Pages'), for: 'Webkul\\Contact\\Filament\\Pages')
                    ->discoverClusters(in: $this->getPluginBasePath('/Filament/Clusters'), for: 'Webkul\\Contact\\Filament\\Clusters')
                    ->discoverWidgets(in: $this->getPluginBasePath('/Filament/Widgets'), for: 'Webkul\\Contact\\Filament\\Widgets');
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
