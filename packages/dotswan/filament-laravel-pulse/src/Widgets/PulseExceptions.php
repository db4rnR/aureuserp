<?php

declare(strict_types=1);

namespace Dotswan\FilamentLaravelPulse\Widgets;

use Filament\Widgets\Widget;

class PulseExceptions extends Widget
{
    protected string $view = 'filament-laravel-pulse::widgets.pulse-exceptions';

    protected string|int|array $cols;

    protected string $ignoreAfter;

    protected int $rows;

    public function __construct()
    {
        $config = config('filament-laravel-pulse.components.exceptions');
        $this->columnSpan = $config['columnSpan'] ?? [
            'md' => 5,
            'xl' => 5,
        ];
        $this->rows = $config['rows'] ?? 2;
        $this->cols = $config['cols'] ?? 'full';
        $this->ignoreAfter = $config['ignoreAfter'] ?? '1 hour';
        self::$isDiscovered = $config['isDiscovered'] ?? true;
        self::$isLazy = $config['isLazy'] ?? true;
        self::$sort = $config['sort'] ?? null;
    }
}
