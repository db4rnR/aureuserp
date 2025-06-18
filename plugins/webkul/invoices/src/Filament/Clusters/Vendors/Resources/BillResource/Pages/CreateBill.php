<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources\BillResource\Pages;

use Webkul\Account\Filament\Resources\BillResource\Pages\CreateBill as BaseCreateBill;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\BillResource;

final class CreateBill extends BaseCreateBill
{
    protected static string $resource = BillResource::class;
}
