<?php

declare(strict_types=1);

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\CalendarResource;
use Webkul\Employee\Models\Calendar;

final class ListCalendars extends ListRecords
{
    protected static string $resource = CalendarResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('employees::filament/clusters/configurations/resources/calendar/pages/list-calendar.tabs.all'))
                ->badge(Calendar::count()),
            'archived' => Tab::make(__('employees::filament/clusters/configurations/resources/calendar/pages/list-calendar.tabs.archived'))
                ->badge(Calendar::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('employees::filament/clusters/configurations/resources/calendar/pages/list-calendar.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('employees::filament/clusters/configurations/resources/calendar/pages/list-calendar.header-actions.create.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/calendar/pages/list-calendar.header-actions.create.notification.body')),
                ),
        ];
    }
}
