<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Group;
use Filament\Forms\Get;
use Illuminate\Support\Arr;
use Webkul\Sale\Enums\AdvancedPayment;
use Webkul\Sale\Enums\InvoiceStatus;
use Webkul\Sale\Facades\SaleOrder as SalesFacade;
use Webkul\Sale\Models\Order;

class CreateInvoiceAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->color(function (Order $record): string {
                if ($record->qty_to_invoice === 0) {
                    return 'gray';
                }

                return 'primary';
            })
            ->label(__('sales::filament/clusters/orders/resources/quotation/actions/create-invoice.title'))
            ->schema([
                Radio::make('advance_payment_method')->inline(false)
                    ->label(__('sales::filament/clusters/orders/resources/quotation/actions/create-invoice.form.fields.create-invoice'))
                    ->options(function () {
                        $options = AdvancedPayment::options();

                        return Arr::only($options, [
                            AdvancedPayment::DELIVERED->value,
                        ]);
                    })
                    ->default(AdvancedPayment::DELIVERED->value)
                    ->live(),
                Group::make()->columns(2)
                    ->schema([
                        TextInput::make('amount')->visible(fn (Get $get): bool => $get('advance_payment_method') === AdvancedPayment::PERCENTAGE->value)
                            ->rules(['required', 'numeric'])
                            ->default(0.00)
                            ->suffix('%'),
                        TextInput::make('amount')->visible(fn (Get $get): bool => $get('advance_payment_method') === AdvancedPayment::FIXED->value)
                            ->rules(['required', 'numeric'])
                            ->default(0.00)
                            ->prefix(fn ($record) => $record->currency->symbol),
                    ]),
            ])
            ->hidden(fn ($record): bool => $record->invoice_status !== InvoiceStatus::TO_INVOICE)
            ->action(function (Order $record, $data): void {
                if ($record->qty_to_invoice === 0) {
                    Notification::make()->title(__('sales::filament/clusters/orders/resources/quotation/actions/create-invoice.notification.no-invoiceable-lines.title'))
                        ->body(__('sales::filament/clusters/orders/resources/quotation/actions/create-invoice.notification.no-invoiceable-lines.body'))
                        ->warning()
                        ->send();

                    return;
                }

                SalesFacade::createInvoice($record, $data);

                Notification::make()->title(__('sales::filament/clusters/orders/resources/quotation/actions/create-invoice.notification.invoice-created.title'))
                    ->body(__('sales::filament/clusters/orders/resources/quotation/actions/create-invoice.notification.invoice-created.body'))
                    ->success()
                    ->send();
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'orders.sales.create-invoice';
    }
}
