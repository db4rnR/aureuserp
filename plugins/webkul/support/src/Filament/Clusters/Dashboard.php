<?php

declare(strict_types=1);

namespace Webkul\Support\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Widgets\Widget;

class Dashboard extends Cluster
{
    protected static ?string $slug = '/';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 0;

    /**
     * @var view-string
     */
    protected string $view = 'filament-panels::pages.dashboard';

    private static string $routePath = '/';

    public static function getRoutePath(Panel $panel): string
    {
        return self::$routePath;
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return Filament::getWidgets();
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    /**
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int|string|array
    {
        return 2;
    }
}
