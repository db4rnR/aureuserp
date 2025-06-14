<?php

namespace Webkul\TimeOff\Filament\Clusters;

use Filament\Panel;
use Filament\Clusters\Cluster;

class Overview extends Cluster
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-view-columns';

    protected static ?int $navigationSort = 2;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'time-off/overview';
    }

    public static function getNavigationLabel(): string
    {
        return __('time-off::filament/clusters/overview.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('time-off::filament/clusters/overview.navigation.group');
    }
}
