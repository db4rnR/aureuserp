<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\OrderResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\QueryException;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Purchase\Enums\OrderState;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\OrderResource;
use Webkul\Purchase\Models\Order;

final class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function configureAction(Action $action): void
    {
        if ($action instanceof ChatterAction) {
            $order = Order::find($this->getRecord()->id);

            $action
                ->record($order)
                ->recordTitle($this->getRecordTitle());

            return;
        }

        parent::configureAction($action);
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()
                ->setResource(self::$resource),
            DeleteAction::make()
                ->hidden(fn (): bool => $this->getRecord()->state === OrderState::DONE)
                ->action(function (DeleteAction $action, Order $record): void {
                    try {
                        $record->delete();

                        $action->success();
                    } catch (QueryException) {
                        Notification::make()
                            ->danger()
                            ->title(__('inventories::filament/clusters/orders/resources/order/pages/view-order.header-actions.delete.notification.error.title'))
                            ->body(__('inventories::filament/clusters/orders/resources/order/pages/view-order.header-actions.delete.notification.error.body'))
                            ->send();

                        $action->failure();
                    }
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/orders/resources/order/pages/view-order.header-actions.delete.notification.success.title'))
                        ->body(__('inventories::filament/clusters/orders/resources/order/pages/view-order.header-actions.delete.notification.success.body')),
                ),
        ];
    }
}
