<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\CustomerResource\Pages;

use Filament\Pages\SubNavigationPosition;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\PartnerResource\Pages\ManageContacts as BaseManageContacts;
use Webkul\Sale\Filament\Clusters\Orders\Resources\CustomerResource;

class ManageContacts extends BaseManageContacts
{
    protected static string $resource = CustomerResource::class;

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }
}
