<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\Pages;

use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource;
use Webkul\Partner\Filament\Resources\BankAccountResource;

class ManageBankAccounts extends ManageRelatedRecords
{
    protected static string $resource = VendorResource::class;

    protected static string $relationship = 'bankAccounts';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/vendors/resources/vendor/pages/manage-bank-account.title');
    }

    public function form(Form $form): Form
    {
        return BankAccountResource::form($form);
    }

    public function table(Table $table): Table
    {
        return BankAccountResource::table($table)
            ->headerActions([
                CreateAction::make()->label(__('invoices::filament/clusters/vendors/resources/vendor/pages/manage-bank-account.table.header-actions.create.title'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateDataUsing(fn (array $data): array => $data),
            ]);
    }
}
