<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

final class Operations extends Cluster
{
    protected static ?string $slug = 'inventory/operations';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/operations.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/operations.navigation.group');
    }
}
