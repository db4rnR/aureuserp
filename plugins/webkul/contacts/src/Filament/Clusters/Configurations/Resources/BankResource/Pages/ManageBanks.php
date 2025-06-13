<?php

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources\BankResource\Pages;

use Webkul\Contact\Filament\Clusters\Configurations\Resources\BankResource;
use Webkul\Partner\Filament\Resources\BankResource\Pages\ManageBanks as BaseManageBanks;

class ManageBanks extends BaseManageBanks
{
    protected static string $resource = BankResource::class;
}
