<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Actions\Print;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Livewire\Component;
use Webkul\Inventory\Facades\Inventory;
use Webkul\Inventory\Models\Operation;

final class ReturnSlipAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('inventories::filament/clusters/operations/actions/todo.label'))
            ->action(function (Operation $record, Component $livewire): void {
                if (! $record->moves->count()) {
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/operations/actions/todo.notification.warning.title'))
                        ->body(__('inventories::filament/clusters/operations/actions/todo.notification.warning.body'))
                        ->warning()
                        ->send();

                    return;
                }

                Inventory::computeTransfer($record);

                $livewire->updateForm();

                Notification::make()
                    ->success()
                    ->title(__('inventories::filament/clusters/operations/actions/todo.notification.success.title'))
                    ->body(__('inventories::filament/clusters/operations/actions/todo.notification.success.body'))
                    ->success()
                    ->send();
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'inventories.operations.print.return-slip';
    }
}
