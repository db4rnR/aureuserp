<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Actions;

use Filament\Actions\Action;
use Webkul\Sale\Facades\SaleOrder;
use Webkul\Sale\Models\Order;
use Webkul\Sale\Settings\QuotationAndOrderSettings;

class LockAndUnlockAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(fn ($record) => $record->locked ? __('sales::filament/clusters/orders/resources/quotation/actions/lock-and-unlock.unlock') : __('sales::filament/clusters/orders/resources/quotation/actions/lock-and-unlock.lock'))
            ->color(fn ($record): string => $record->locked ? 'primary' : 'gray')
            ->icon(fn ($record): string => $record->locked ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
            ->action(function (Order $record): void {
                SaleOrder::lockAndUnlock($record);
            })
            ->visible(fn (QuotationAndOrderSettings $quotationAndOrderSettings): bool => $quotationAndOrderSettings?->enable_lock_confirm_sales);
    }

    public static function getDefaultName(): ?string
    {
        return 'purchases.orders.lock';
    }
}
