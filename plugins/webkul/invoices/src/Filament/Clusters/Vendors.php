<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Vendors extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/vendors.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('invoices::filament/clusters/vendors.navigation.group');
    }
}
