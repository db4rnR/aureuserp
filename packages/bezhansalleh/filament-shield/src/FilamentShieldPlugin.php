<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentShield;

use BezhanSalleh\FilamentShield\Concerns\CanBeCentralApp;
use BezhanSalleh\FilamentShield\Concerns\CanCustomizeColumns;
use BezhanSalleh\FilamentShield\Concerns\CanLocalizePermissionLabels;
use BezhanSalleh\FilamentShield\Concerns\HasSimpleResourcePermissionView;
use BezhanSalleh\FilamentShield\Resources\RoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;

final class FilamentShieldPlugin implements Plugin
{
    use CanBeCentralApp;
    use CanCustomizeColumns;
    use CanLocalizePermissionLabels;
    use EvaluatesClosures;
    use HasSimpleResourcePermissionView;

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
        return 'filament-shield';
    }

    public function register(Panel $panel): void
    {

        if (! Utils::isResourcePublished($panel)) {
            $panel->resources([
                RoleResource::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
