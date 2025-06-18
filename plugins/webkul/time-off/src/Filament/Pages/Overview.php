<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Pages;

use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Webkul\TimeOff\Filament\Widgets\OverviewCalendarWidget;

final class Overview extends BaseDashboard
{
    protected static string $routePath = 'time-off';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('time-off::filament/pages/overview.navigation.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('time-off::filament/pages/overview.navigation.group');
    }

    public function getTitle(): string
    {
        return __('time-off::filament/pages/overview.navigation.title');
    }

    public function getWidgets(): array
    {
        return [
            OverviewCalendarWidget::class,
        ];
    }
}
