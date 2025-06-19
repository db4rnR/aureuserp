<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Pages;

use Filament\Pages\Enums\SubNavigationPosition;
use Webkul\Account\Filament\Resources\InvoiceResource\Pages\ViewInvoice as BaseViewInvoice;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource;

class ViewInvoice extends BaseViewInvoice
{
    protected static string $resource = InvoiceResource::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }
}
