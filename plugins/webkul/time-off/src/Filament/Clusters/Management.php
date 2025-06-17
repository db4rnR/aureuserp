<?php

namespace Webkul\TimeOff\Filament\Clusters;

use Filament\Panel;
use Filament\Clusters\Cluster;

class Management extends Cluster
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 3;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'time-off/management';
    }

    public static function getNavigationLabel(): string
    {
        return __('time-off::filament/clusters/management.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('time-off::filament/clusters/management.navigation.group');
    }
}
