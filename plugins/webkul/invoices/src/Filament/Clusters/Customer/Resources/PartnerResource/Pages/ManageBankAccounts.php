<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\PartnerResource\Pages;

use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\PartnerResource;
use Webkul\Partner\Filament\Resources\BankAccountResource;

class ManageBankAccounts extends ManageRelatedRecords
{
    protected static string $resource = PartnerResource::class;

    protected static string $relationship = 'bankAccounts';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationLabel(): string
    {
        return __('Bank Accounts');
    }

    public function form(Form $form): Form
    {
        return BankAccountResource::form($form);
    }

    public function table(Table $table): Table
    {
        return BankAccountResource::table($table)
            ->headerActions([
                CreateAction::make()->label(__('New Bank Account'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateDataUsing(fn (array $data): array => $data),
            ]);
    }
}
