<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Webkul\TimeOff\Filament\Clusters\Reporting;
use Webkul\TimeOff\Filament\Widgets\LeaveTypeWidget;

final class ByType extends BaseDashboard
{
    protected static string $routePath = 'reporting/by-type';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Reporting::class;

    public static function getNavigationLabel(): string
    {
        return __('time-off::filament/pages/by-type.navigation.title');
    }

    public function getTitle(): string
    {
        return __('time-off::filament/pages/by-type.navigation.title');
    }

    public function getWidgets(): array
    {
        return [
            LeaveTypeWidget::class,
        ];
    }
}
