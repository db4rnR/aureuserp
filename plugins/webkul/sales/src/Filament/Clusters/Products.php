<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

final class Products extends Cluster
{
    protected static ?string $slug = 'sale/products';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 0;

    public static function getNavigationLabel(): string
    {
        return __('sales::filament/clusters/products.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('sales::filament/clusters/products.navigation.group');
    }
}
