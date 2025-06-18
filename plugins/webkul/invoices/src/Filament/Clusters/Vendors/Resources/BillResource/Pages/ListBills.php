<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources\BillResource\Pages;

use Webkul\Account\Filament\Resources\BillResource\Pages\ListBills as BaseListBills;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\BillResource;

final class ListBills extends BaseListBills
{
    protected static string $resource = BillResource::class;
}
