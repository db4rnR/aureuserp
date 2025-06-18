<?php

declare(strict_types=1);

namespace Saade\FilamentFullCalendar\Actions;

use Filament\Actions\EditAction as BaseEditAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

final class EditAction extends BaseEditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->model(
            fn (FullCalendarWidget $livewire) => $livewire->getModel()
        );

        $this->record(
            fn (FullCalendarWidget $livewire) => $livewire->getRecord()
        );

        $this->schema(
            fn (FullCalendarWidget $livewire) => $livewire->getFormSchema()
        );

        $this->after(
            fn (FullCalendarWidget $livewire) => $livewire->refreshRecords()
        );

        $this->cancelParentActions();
    }
}
