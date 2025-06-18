<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Pages;

use Filament\Pages\Enums\SubNavigationPosition;
use Webkul\Account\Filament\Resources\InvoiceResource\Pages\EditInvoice as BaseEditInvoice;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource;

final class EditInvoice extends BaseEditInvoice
{
    protected static string $resource = InvoiceResource::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }
}
