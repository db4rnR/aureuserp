<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Panel;

final class Applications extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'recruitments/applications';
    }

    public static function getNavigationLabel(): string
    {
        return __('recruitments::filament/clusters/applications.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('recruitments::filament/clusters/applications.navigation.group');
    }
}
