<?php

declare(strict_types=1);

namespace Webkul\TimeOff;

use Filament\Contracts\Plugin;
use Filament\Panel;
use ReflectionClass;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Webkul\Support\Package;

class TimeOffPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'time-off';
    }

    public function register(Panel $panel): void
    {
        if (! Package::isPluginInstalled($this->getId())) {
            return;
        }

        $panel
            ->when($panel->getId() === 'admin', function (Panel $panel): void {
                $panel->discoverResources(in: $this->getPluginBasePath('/Filament/Resources'), for: 'Webkul\\TimeOff\\Filament\\Resources')
                    ->discoverPages(in: $this->getPluginBasePath('/Filament/Pages'), for: 'Webkul\\TimeOff\\Filament\\Pages')
                    ->discoverClusters(in: $this->getPluginBasePath('/Filament/Clusters'), for: 'Webkul\\TimeOff\\Filament\\Clusters')
                    ->discoverWidgets(in: $this->getPluginBasePath('/Filament/Widgets'), for: 'Webkul\\TimeOff\\Filament\\Widgets');
            })
            ->plugin(
                FilamentFullCalendarPlugin::make()->selectable()
                    ->editable(true)
                    ->plugins(['multiMonth'])
            );
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
