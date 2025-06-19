<?php

declare(strict_types=1);

namespace Z3d0X\FilamentFabricator\Listeners;

use Illuminate\Console\Command;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;
use Z3d0X\FilamentFabricator\Commands\ClearRoutesCacheCommand;

class OptimizeWithLaravel
{
    public const COMMANDS = [
        'cache:clear',
        'config:cache',
        'config:clear',
        'optimize',
        'optimize:clear',
        'route:clear',
    ];

    public const REFRESH_COMMANDS = [
        'config:cache',
        'optimize',
    ];

    public function handle(CommandFinished $event): void
    {
        if (! $this->shouldHandleEvent($event)) {
            return;
        }

        if ($this->shouldRefresh($event)) {
            $this->refresh();
        } else {
            $this->clear();
        }
    }

    public function shouldHandleEvent(CommandFinished $event)
    {
        return $event->exitCode === Command::SUCCESS
            && in_array($event->command, self::COMMANDS, true);
    }

    public function shouldRefresh(CommandFinished $event)
    {
        return in_array($event->command, self::REFRESH_COMMANDS, true);
    }

    public function refresh(): void
    {
        $this->callCommand([
            '--refresh' => true,
        ]);
    }

    public function clear(): void
    {
        $this->callCommand();
    }

    public function callCommand(array $params = []): void
    {
        Artisan::call(ClearRoutesCacheCommand::class, $params);
    }
}
