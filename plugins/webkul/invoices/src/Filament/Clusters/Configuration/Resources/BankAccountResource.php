<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Account\Filament\Resources\BankAccountResource as BaseBankAccountResource;
use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\BankAccountResource\Pages\ListBankAccounts;
use Webkul\Invoice\Models\BankAccount;

class BankAccountResource extends BaseBankAccountResource
{
    protected static ?string $model = BankAccount::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $cluster = Configuration::class;

    public static function getPages(): array
    {
        return [
            'index' => ListBankAccounts::route('/'),
        ];
    }
}
