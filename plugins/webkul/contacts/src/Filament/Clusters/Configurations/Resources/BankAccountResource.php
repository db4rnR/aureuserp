<?php

declare(strict_types=1);

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources;

use BackedEnum;
use Webkul\Contact\Filament\Clusters\Configurations;
use Webkul\Contact\Filament\Clusters\Configurations\Resources\BankAccountResource\Pages\ManageBankAccounts;
use Webkul\Partner\Filament\Resources\BankAccountResource as BaseBankAccountResource;

final class BankAccountResource extends BaseBankAccountResource
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 5;

    protected static ?string $cluster = Configurations::class;

    public static function getNavigationGroup(): string
    {
        return __('contacts::filament/clusters/configurations/resources/bank-account.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('contacts::filament/clusters/configurations/resources/bank-account.navigation.title');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBankAccounts::route('/'),
        ];
    }
}
