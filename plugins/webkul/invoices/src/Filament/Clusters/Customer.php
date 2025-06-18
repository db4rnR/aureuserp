<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

final class Customer extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/customers.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('invoices::filament/clusters/customers.navigation.group');
    }
}
