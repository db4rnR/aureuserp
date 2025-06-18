<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

final class Products extends Cluster
{
    protected static ?string $slug = 'inventory/products';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/products.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/products.navigation.group');
    }
}
