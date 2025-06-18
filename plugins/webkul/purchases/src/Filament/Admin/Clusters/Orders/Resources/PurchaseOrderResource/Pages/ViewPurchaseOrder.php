<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\PurchaseOrderResource\Pages;

use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\OrderResource\Pages\ViewOrder;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\PurchaseOrderResource;

final class ViewPurchaseOrder extends ViewOrder
{
    protected static string $resource = PurchaseOrderResource::class;
}
