<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\Management\Resources\AllocationResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\TimeOff\Enums\State;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\AllocationResource;

class EditAllocation extends EditRecord
{
    protected static string $resource = AllocationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()->success()
            ->title(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.notification.title'))
            ->body(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make()->setResource(self::$resource),
            Action::make('approved')->label(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.approved.title'))
                ->color('gray')
                ->hidden(fn ($record): bool => $record->state !== State::CONFIRM->value)
                ->action(function ($record): void {
                    $record->update(['state' => State::VALIDATE_TWO->value]);

                    $this->refreshFormData(['state']);

                    Notification::make()->success()
                        ->title(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.approved.notification.title'))
                        ->body(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.approved.notification.body'))
                        ->send();
                }),
            Action::make('refuse')->label(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.refuse.title'))
                ->color('gray')
                ->hidden(fn ($record): bool => $record->state === State::REFUSE->value)
                ->action(function ($record): void {
                    $record->update(['state' => State::REFUSE->value]);

                    $this->refreshFormData(['state']);

                    Notification::make()->success()
                        ->title(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.refuse.notification.title'))
                        ->body(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.refuse.notification.body'))
                        ->send();
                }),
            Action::make('mark_as_ready_to_confirm')->label(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.mark-as-ready-to-confirm.title'))
                ->color('gray')
                ->visible(fn ($record): bool => $record->state === State::REFUSE->value)
                ->action(function ($record): void {
                    $record->update(['state' => State::CONFIRM->value]);

                    $this->refreshFormData(['state']);

                    Notification::make()->success()
                        ->title(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.mark-as-ready-to-confirm.notification.title'))
                        ->body(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.mark-as-ready-to-confirm.notification.body'))
                        ->send();
                }),
            DeleteAction::make()->successNotification(
                    Notification::make()->success()
                        ->title(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.delete.notification.title'))
                        ->body(__('time-off::filament/clusters/management/resources/allocation/pages/edit-allocation.header-actions.delete.notification.body'))
                ),
        ];
    }
}
