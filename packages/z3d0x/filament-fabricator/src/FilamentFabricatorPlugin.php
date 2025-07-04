<?php

declare(strict_types=1);

namespace Z3d0X\FilamentFabricator;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Pboivin\FilamentPeek\FilamentPeekPlugin;
use Z3d0X\FilamentFabricator\Enums\BlockPickerStyle;

class FilamentFabricatorPlugin implements Plugin
{
    public const ID = 'filament-fabricator';

    private BlockPickerStyle|Closure|null $blockPickerStyle = null;

    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return static::ID;
    }

    public function register(Panel $panel): void
    {
        $panel->resources(array_filter([
            config('filament-fabricator.page-resource'),
        ]));

        if (! $panel->hasPlugin(FilamentPeekPlugin::ID)) {
            // Automatically register FilamentPeekPlugin if it is not already registered
            $panel->plugin(FilamentPeekPlugin::make());
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function blockPickerStyle(?BlockPickerStyle $style): static
    {
        $this->blockPickerStyle = $style;

        return $this;
    }

    public function getBlockPickerStyle(): ?BlockPickerStyle
    {
        return $this->blockPickerStyle;
    }
}
