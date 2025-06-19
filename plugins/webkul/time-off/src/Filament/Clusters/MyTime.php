<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Panel;

class MyTime extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?int $navigationSort = 1;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'time-off/dashboard';
    }

    public static function getNavigationLabel(): string
    {
        return __('time-off::filament/clusters/my-time.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('time-off::filament/clusters/my-time.navigation.group');
    }
}
