<?php

declare(strict_types=1);

namespace Webkul\Support\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

final class Settings extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench';

    protected static ?int $navigationSort = 1000;

    public static function getNavigationLabel(): string
    {
        return __('support::filament/clusters/settings/pages/settings.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('support::filament/clusters/settings/pages/settings.navigation.group');
    }
}
