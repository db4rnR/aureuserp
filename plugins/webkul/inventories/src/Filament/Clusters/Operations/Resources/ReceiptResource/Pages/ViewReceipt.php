<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\ReceiptResource\Pages;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\QueryException;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Inventory\Enums\OperationState;
use Webkul\Inventory\Filament\Clusters\Operations\Actions as OperationActions;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\ReceiptResource;
use Webkul\Inventory\Models\Receipt;

final class ViewReceipt extends ViewRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()
                ->setResource(self::$resource),
            ActionGroup::make([
                OperationActions\Print\PickingOperationAction::make(),
                OperationActions\Print\DeliverySlipAction::make(),
                OperationActions\Print\PackageAction::make(),
                OperationActions\Print\LabelsAction::make(),
            ])
                ->label(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.print.label'))
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->button(),
            DeleteAction::make()
                ->hidden(fn (): bool => $this->getRecord()->state === OperationState::DONE)
                ->action(function (DeleteAction $action, Receipt $record): void {
                    try {
                        $record->delete();

                        $action->success();
                    } catch (QueryException) {
                        Notification::make()
                            ->danger()
                            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/view-receipt.header-actions.delete.notification.error.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/view-receipt.header-actions.delete.notification.error.body'))
                            ->send();

                        $action->failure();
                    }
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/view-receipt.header-actions.delete.notification.success.title'))
                        ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/view-receipt.header-actions.delete.notification.success.body')),
                ),
        ];
    }
}
