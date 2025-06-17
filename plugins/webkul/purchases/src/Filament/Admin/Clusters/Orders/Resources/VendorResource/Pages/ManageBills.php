<?php

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\VendorResource\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\BillResource;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\VendorResource;

class ManageBills extends ManageRelatedRecords
{
    protected static string $resource = VendorResource::class;

    protected static string $relationship = 'accountMoves';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-check';

    public static function getNavigationLabel(): string
    {
        return __('purchases::filament/admin/clusters/orders/resources/vendor/pages/manage-bills.navigation.title');
    }

    public function table(Table $table): Table
    {
        return BillResource::table($table)
            ->modifyQueryUsing(fn ($query) => $query->where('partner_id', $this->record->getKey()))
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => BillResource::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(false),

                EditAction::make()
                    ->url(fn ($record) => BillResource::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab(false),
            ]);
    }
}
