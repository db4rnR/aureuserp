<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Configurations extends Cluster
{
    protected static ?string $slug = 'purchase/configurations';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('purchases::filament/admin/clusters/configurations.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('purchases::filament/admin/clusters/configurations.navigation.group');
    }
}
