<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Webkul\Sale\Enums\InvoiceStatus;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource;
use Webkul\Sale\Filament\Clusters\ToInvoice;
use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToUpsellResource\Pages\ListOrderToUpsells;
use Webkul\Sale\Models\Order;

class OrderToUpsellResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static ?string $cluster = ToInvoice::class;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getModelLabel(): string
    {
        return __('sales::filament/clusters/to-invoice/resources/order-to-upsell.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('sales::filament/clusters/to-invoice/resources/order-to-upsell.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return QuotationResource::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return QuotationResource::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return QuotationResource::table($table)
            ->modifyQueryUsing(function ($query): void {
                $query->where('invoice_status', InvoiceStatus::UP_SELLING);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrderToUpsells::route('/'),
        ];
    }
}
