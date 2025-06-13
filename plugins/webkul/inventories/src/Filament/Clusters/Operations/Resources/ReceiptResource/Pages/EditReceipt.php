<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\ReceiptResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\QueryException;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Operations\Actions as OperationActions;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\ReceiptResource;
use Webkul\Inventory\Models\Receipt;

class EditReceipt extends EditRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.notification.title'))
            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()
                ->setResource(static::$resource),
            OperationActions\TodoAction::make(),
            OperationActions\ValidateAction::make(),
            OperationActions\CancelAction::make(),
            OperationActions\ReturnAction::make(),
            Actions\ActionGroup::make([
                OperationActions\Print\PickingOperationAction::make(),
                OperationActions\Print\DeliverySlipAction::make(),
                OperationActions\Print\PackageAction::make(),
                OperationActions\Print\LabelsAction::make(),
            ])
                ->label(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.print.label'))
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->button(),
            Actions\DeleteAction::make()
                ->hidden(fn () => $this->getRecord()->state == Enums\OperationState::DONE)
                ->action(function (Actions\DeleteAction $action, Receipt $record) {
                    try {
                        $record->delete();

                        $action->success();
                    } catch (QueryException $e) {
                        Notification::make()
                            ->danger()
                            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.delete.notification.error.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.delete.notification.error.body'))
                            ->send();

                        $action->failure();
                    }
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.delete.notification.success.title'))
                        ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.delete.notification.success.body')),
                ),
        ];
    }

    public function updateForm(): void
    {
        $this->fillForm();
    }
}
