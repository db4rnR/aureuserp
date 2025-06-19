<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Configurations extends Cluster
{
    protected static ?string $slug = 'inventory/configurations';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/configurations.navigation.group');
    }
}
