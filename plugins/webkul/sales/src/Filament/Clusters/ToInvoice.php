<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

final class ToInvoice extends Cluster
{
    protected static ?string $slug = 'sale/invoice';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    public static function getNavigationLabel(): string
    {
        return __('sales::filament/clusters/to-invoice.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('sales::filament/clusters/to-invoice.navigation.group');
    }
}
