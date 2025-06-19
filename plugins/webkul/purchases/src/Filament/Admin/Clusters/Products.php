<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Products extends Cluster
{
    protected static ?string $slug = 'purchase/products';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('purchases::filament/admin/clusters/products.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('purchases::filament/admin/clusters/products.navigation.group');
    }
}
