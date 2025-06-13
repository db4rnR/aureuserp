<?php

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Pages;

use Filament\Pages\SubNavigationPosition;
use Webkul\Account\Filament\Resources\InvoiceResource\Pages\EditInvoice as BaseEditInvoice;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource;

class EditInvoice extends BaseEditInvoice
{
    protected static string $resource = InvoiceResource::class;

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }
}
