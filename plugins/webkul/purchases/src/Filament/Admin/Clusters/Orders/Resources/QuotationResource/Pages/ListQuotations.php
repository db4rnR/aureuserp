<?php

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\QuotationResource\Pages;

use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\OrderResource\Pages\ListOrders;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\QuotationResource;

class ListQuotations extends ListOrders
{
    protected static string $resource = QuotationResource::class;
}
