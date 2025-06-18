<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Customer\Clusters\Account\Resources\PurchaseOrderResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Webkul\Purchase\Filament\Customer\Clusters\Account\Resources\PurchaseOrderResource;

final class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;
}
