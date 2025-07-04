<?php

declare(strict_types=1);

namespace Dotswan\FilamentLaravelPulse\Widgets;

use Filament\Widgets\Widget;

class PulseServers extends Widget
{
    protected string $view = 'filament-laravel-pulse::widgets.pulse-servers';

    protected string|int|array $cols;

    protected string $ignoreAfter;

    public function __construct()
    {
        $config = config('filament-laravel-pulse.components.servers');
        $this->columnSpan = $config['columnSpan'] ?? [
            'md' => 5,
            'xl' => 5,
        ];
        $this->cols = $config['cols'] ?? 'full';
        $this->ignoreAfter = $config['ignoreAfter'] ?? '1 hour';
        self::$isDiscovered = $config['isDiscovered'] ?? true;
        self::$isLazy = $config['isLazy'] ?? true;
        self::$sort = $config['sort'] ?? null;
    }
}
