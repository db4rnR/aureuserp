<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\OrderResource\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Livewire\Component;
use Webkul\Purchase\Enums\OrderState;
use Webkul\Purchase\Facades\PurchaseOrder;
use Webkul\Purchase\Models\Order;

final class DraftAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('purchases::filament/admin/clusters/orders/resources/order/actions/draft.label'))
            ->color('gray')
            ->action(function (Order $record, Component $livewire): void {
                $record = PurchaseOrder::draftPurchaseOrder($record);

                $livewire->updateForm();

                Notification::make()
                    ->title(__('purchases::filament/admin/clusters/orders/resources/order/actions/draft.action.notification.success.title'))
                    ->body(__('purchases::filament/admin/clusters/orders/resources/order/actions/draft.action.notification.success.body'))
                    ->success()
                    ->send();
            })
            ->visible(fn (): bool => $this->getRecord()->state === OrderState::CANCELED);
    }

    public static function getDefaultName(): ?string
    {
        return 'purchases.orders.draft';
    }
}
