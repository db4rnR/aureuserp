<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Webkul\Partner\Filament\Resources\BankAccountResource;

class BankAccountsRelationManager extends RelationManager
{
    protected static string $relationship = 'bankAccounts';

    public function form(Form $form): Form
    {
        return BankAccountResource::form($form);
    }

    public function table(Table $table): Table
    {
        return BankAccountResource::table($table)
            ->headerActions([
                CreateAction::make()->label(__('invoices::filament/clusters/vendors/resources/vendor/relation-manager/bank-account-relation-manager.create-bank-account'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateDataUsing(fn (array $data): array => $data),
            ]);
    }
}
