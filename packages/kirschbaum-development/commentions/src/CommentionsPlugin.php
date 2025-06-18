<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class CommentionsPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return CommentionsServiceProvider::$name;
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
