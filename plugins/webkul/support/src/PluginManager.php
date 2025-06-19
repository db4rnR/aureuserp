<?php

declare(strict_types=1);

namespace Webkul\Support;

use Filament\Contracts\Plugin;
use Filament\Panel;

use function Illuminate\Filesystem\join_paths;

class PluginManager implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(self::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'plugin-manager';
    }

    public function register(Panel $panel): void
    {
        $plugins = $this->getPlugins();

        foreach ($plugins as $modulePlugin) {
            $panel->plugin($modulePlugin::make());
        }
    }

    public function boot(Panel $panel): void {}

    private function getPlugins(): array
    {
        $plugins = require join_paths(base_path().'/bootstrap', 'plugins.php');

        return collect($plugins)
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }
}
