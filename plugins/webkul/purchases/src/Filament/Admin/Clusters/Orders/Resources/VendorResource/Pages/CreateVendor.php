<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\VendorResource\Pages;

use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\Pages\CreateVendor as BaseCreateVendor;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\VendorResource;

final class CreateVendor extends BaseCreateVendor
{
    protected static string $resource = VendorResource::class;
}
