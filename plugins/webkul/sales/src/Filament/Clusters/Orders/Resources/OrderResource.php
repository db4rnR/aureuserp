<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Orders\Resources;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Webkul\Sale\Enums\OrderState;
use Webkul\Sale\Filament\Clusters\Orders;
use Webkul\Sale\Filament\Clusters\Orders\Resources\OrderResource\Pages\CreateOrder;
use Webkul\Sale\Filament\Clusters\Orders\Resources\OrderResource\Pages\EditOrder;
use Webkul\Sale\Filament\Clusters\Orders\Resources\OrderResource\Pages\ListOrders;
use Webkul\Sale\Filament\Clusters\Orders\Resources\OrderResource\Pages\ManageDeliveries;
use Webkul\Sale\Filament\Clusters\Orders\Resources\OrderResource\Pages\ManageInvoices;
use Webkul\Sale\Filament\Clusters\Orders\Resources\OrderResource\Pages\ViewOrder;
use Webkul\Sale\Models\Order;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $cluster = Orders::class;

    protected static ?int $navigationSort = 2;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getModelLabel(): string
    {
        return __('sales::filament/clusters/orders/resources/order.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('sales::filament/clusters/orders/resources/order.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return QuotationResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return QuotationResource::table($table)
            ->modifyQueryUsing(function ($query): void {
                $query->where('state', OrderState::SALE);
            });
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return QuotationResource::infolist($infolist);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewOrder::class,
            EditOrder::class,
            ManageInvoices::class,
            ManageDeliveries::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'view' => ViewOrder::route('/{record}'),
            'edit' => EditOrder::route('/{record}/edit'),
            'invoices' => ManageInvoices::route('/{record}/invoices'),
            'deliveries' => ManageDeliveries::route('/{record}/deliveries'),
        ];
    }
}
