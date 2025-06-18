<?php

declare(strict_types=1);

namespace Webkul\Project\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

final class Configurations extends Cluster
{
    protected static ?string $slug = 'project/configurations';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 0;

    public static function getNavigationLabel(): string
    {
        return __('projects::filament/clusters/configurations.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('projects::filament/clusters/configurations.navigation.group');
    }
}
