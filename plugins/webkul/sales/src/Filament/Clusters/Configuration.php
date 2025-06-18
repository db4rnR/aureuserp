<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

final class Configuration extends Cluster
{
    protected static ?string $slug = 'sale/configurations';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('sales::filament/clusters/configurations.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('sales::filament/clusters/configurations.navigation.group');
    }
}
