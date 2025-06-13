<?php

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages\EditQuotation as BaseEditQuotation;
use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource;

class EditOrderToInvoice extends BaseEditQuotation
{
    protected static string $resource = OrderToInvoiceResource::class;
}
