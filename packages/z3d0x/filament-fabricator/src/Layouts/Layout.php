<?php

declare(strict_types=1);

namespace Z3d0X\FilamentFabricator\Layouts;

use Illuminate\Support\Str;

abstract class Layout
{
    protected static ?string $component;

    protected static ?string $name;

    final public static function getName(): string
    {
        return static::$name;
    }

    final public static function getLabel(): string
    {
        return Str::headline(static::$name);
    }

    final public static function getComponent(): string
    {
        if (isset(static::$component)) {
            return static::$component;
        }

        return 'filament-fabricator.layouts.'.static::getName();
    }
}
