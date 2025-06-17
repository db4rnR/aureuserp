<?php

namespace Webkul\TimeOff\Filament\Clusters;

use Filament\Panel;
use Filament\Clusters\Cluster;

class Configurations extends Cluster
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 5;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'time-off/configurations';
    }

    public static function getNavigationLabel(): string
    {
        return __('time-off::filament/clusters/configuration.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('time-off::filament/clusters/configuration.navigation.group');
    }
}
