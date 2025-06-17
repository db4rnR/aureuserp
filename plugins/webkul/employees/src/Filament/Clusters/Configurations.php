<?php

namespace Webkul\Employee\Filament\Clusters;

use Filament\Panel;
use Filament\Clusters\Cluster;

class Configurations extends Cluster
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 4;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'employees/configurations';
    }

    public static function getNavigationLabel(): string
    {
        return __('employees::filament/clusters/configurations.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('employees::filament/clusters/configurations.navigation.group');
    }
}
