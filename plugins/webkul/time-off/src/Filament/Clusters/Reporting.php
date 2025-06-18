<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Panel;

final class Reporting extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?int $navigationSort = 4;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'time-off/reporting';
    }

    public static function getNavigationLabel(): string
    {
        return __('time-off::filament/clusters/reporting.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('time-off::filament/clusters/reporting.navigation.group');
    }
}
