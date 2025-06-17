<?php

namespace Saade\FilamentFullCalendar\Widgets;

use Saade\FilamentFullCalendar\Widgets\Concerns\InteractsWithEvents;
use Saade\FilamentFullCalendar\Widgets\Concerns\InteractsWithRecords;
use Saade\FilamentFullCalendar\Widgets\Concerns\InteractsWithHeaderActions;
use Saade\FilamentFullCalendar\Widgets\Concerns\InteractsWithModalActions;
use Saade\FilamentFullCalendar\Widgets\Concerns\InteractsWithRawJS;
use Saade\FilamentFullCalendar\Widgets\Concerns\CanBeConfigured;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Actions;

class FullCalendarWidget extends Widget implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
    use InteractsWithEvents;
    use InteractsWithRecords;
    use InteractsWithHeaderActions;
    use InteractsWithModalActions;
    use InteractsWithRawJS;
    use CanBeConfigured;

    protected string $view = 'filament-fullcalendar::fullcalendar';

    protected int | string | array $columnSpan = 'full';

    protected function headerActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function modalActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function viewAction(): Action
    {
        return ViewAction::make();
    }

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views.
     * @param array{start: string, end: string, timezone: string} $info
     */
    public function fetchEvents(array $info): array
    {
        return [];
    }

    public function getFormSchema(): array
    {
        return [];
    }
}
