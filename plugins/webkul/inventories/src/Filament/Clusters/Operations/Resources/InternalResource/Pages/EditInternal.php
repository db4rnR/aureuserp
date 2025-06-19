<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource\Pages;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\QueryException;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Inventory\Enums\OperationState;
use Webkul\Inventory\Filament\Clusters\Operations\Actions as OperationActions;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource;
use Webkul\Inventory\Models\InternalTransfer;

class EditInternal extends EditRecord
{
    protected static string $resource = InternalResource::class;

    public function updateForm(): void
    {
        $this->fillForm();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()->success()
            ->title(__('inventories::filament/clusters/operations/resources/internal/pages/edit-internal.notification.title'))
            ->body(__('inventories::filament/clusters/operations/resources/internal/pages/edit-internal.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()->setResource(self::$resource),
            OperationActions\TodoAction::make(),
            OperationActions\CheckAvailabilityAction::make(),
            OperationActions\ValidateAction::make(),
            OperationActions\CancelAction::make(),
            OperationActions\ReturnAction::make(),
            ActionGroup::make([
                OperationActions\Print\PickingOperationAction::make(),
                OperationActions\Print\DeliverySlipAction::make(),
                OperationActions\Print\PackageAction::make(),
                OperationActions\Print\LabelsAction::make(),
            ])
                ->label(__('inventories::filament/clusters/operations/resources/internal/pages/edit-internal.header-actions.print.label'))
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->button(),
            DeleteAction::make()->hidden(fn (): bool => $this->getRecord()->state === OperationState::DONE)
                ->action(function (DeleteAction $action, InternalTransfer $record): void {
                    try {
                        $record->delete();

                        $action->success();
                    } catch (QueryException) {
                        Notification::make()->danger()
                            ->title(__('inventories::filament/clusters/operations/resources/internal/pages/edit-internal.header-actions.delete.notification.error.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/internal/pages/edit-internal.header-actions.delete.notification.error.body'))
                            ->send();

                        $action->failure();
                    }
                })
                ->successNotification(
                    Notification::make()->success()
                        ->title(__('inventories::filament/clusters/operations/resources/internal/pages/edit-internal.header-actions.delete.notification.success.title'))
                        ->body(__('inventories::filament/clusters/operations/resources/internal/pages/edit-internal.header-actions.delete.notification.success.body')),
                ),
        ];
    }
}
