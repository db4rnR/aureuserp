<?php

declare(strict_types=1);

namespace Webkul\Employee\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Panel;

class Reportings extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?int $navigationSort = 3;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'employees/reportings';
    }

    public static function getNavigationLabel(): string
    {
        return __('employees::filament/clusters/reportings.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('employees::filament/clusters/reportings.navigation.group');
    }
}
