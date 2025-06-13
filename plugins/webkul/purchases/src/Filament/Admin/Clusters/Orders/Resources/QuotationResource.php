<?php

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources;

use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Webkul\Purchase\Filament\Admin\Clusters\Orders;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\QuotationResource\Pages;
use Webkul\Purchase\Models\Quotation;

class QuotationResource extends OrderResource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $recordTitleAttribute = 'name';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Orders::class;

    public static function getNavigationLabel(): string
    {
        return __('purchases::filament/admin/clusters/orders/resources/quotation.navigation.title');
    }

    public static function getModelLabel(): string
    {
        return __('purchases::filament/admin/clusters/orders/resources/quotation.navigation.title');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewQuotation::class,
            Pages\EditQuotation::class,
            Pages\ManageBills::class,
            Pages\ManageReceipts::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'    => Pages\ListQuotations::route('/'),
            'create'   => Pages\CreateQuotation::route('/create'),
            'view'     => Pages\ViewQuotation::route('/{record}'),
            'edit'     => Pages\EditQuotation::route('/{record}/edit'),
            'bills'    => Pages\ManageBills::route('/{record}/bills'),
            'receipts' => Pages\ManageReceipts::route('/{record}/receipts'),
        ];
    }
}
