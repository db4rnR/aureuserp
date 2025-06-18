<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages\EditQuotation as BaseEditQuotation;
use Webkul\Sale\Filament\Clusters\ToInvoice\Resources\OrderToInvoiceResource;

final class EditOrderToInvoice extends BaseEditQuotation
{
    protected static string $resource = OrderToInvoiceResource::class;
}
