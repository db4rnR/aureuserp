<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\OrderResource\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Livewire\Component;
use Webkul\Purchase\Enums\OrderState;
use Webkul\Purchase\Facades\PurchaseOrder;
use Webkul\Purchase\Models\Order;

class CreateBillAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('purchases::filament/admin/clusters/orders/resources/order/actions/create-bill.label'))
            ->color(function (Order $record): string {
                if ($record->qty_to_invoice === 0) {
                    return 'gray';
                }

                return 'primary';
            })
            ->action(function (Order $record, Component $livewire): void {
                if ($record->qty_to_invoice === 0) {
                    Notification::make()->title(__('purchases::filament/admin/clusters/orders/resources/order/actions/create-bill.action.notification.warning.title'))
                        ->body(__('purchases::filament/admin/clusters/orders/resources/order/actions/create-bill.action.notification.warning.body'))
                        ->warning()
                        ->send();

                    return;
                }

                $record = PurchaseOrder::createPurchaseOrderBill($record);

                $livewire->updateForm();

                Notification::make()->title(__('purchases::filament/admin/clusters/orders/resources/order/actions/create-bill.action.notification.success.title'))
                    ->body(__('purchases::filament/admin/clusters/orders/resources/order/actions/create-bill.action.notification.success.body'))
                    ->success()
                    ->send();
            })
            ->visible(fn (): bool => in_array($this->getRecord()->state, [
                OrderState::PURCHASE,
                OrderState::DONE,
            ], true));
    }

    public static function getDefaultName(): ?string
    {
        return 'purchases.orders.create-bill';
    }
}
