<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

final class Orders extends Cluster
{
    protected static ?string $slug = 'purchase/orders';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('purchases::filament/admin/clusters/orders.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('purchases::filament/admin/clusters/orders.navigation.group');
    }
}
