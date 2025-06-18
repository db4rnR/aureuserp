<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\OrderResource\Pages;

use Webkul\Sale\Filament\Clusters\Orders\Resources\OrderResource;
use Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Pages\ManageInvoices as BaseManageInvoices;

final class ManageInvoices extends BaseManageInvoices
{
    protected static string $resource = OrderResource::class;
}
