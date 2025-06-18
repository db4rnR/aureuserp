<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\CustomerResource\Pages;

use Filament\Pages\Enums\SubNavigationPosition;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\PartnerResource\Pages\ManageBankAccounts as BaseManageBankAccounts;
use Webkul\Sale\Filament\Clusters\Orders\Resources\CustomerResource;

final class ManageBankAccounts extends BaseManageBankAccounts
{
    protected static string $resource = CustomerResource::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }
}
