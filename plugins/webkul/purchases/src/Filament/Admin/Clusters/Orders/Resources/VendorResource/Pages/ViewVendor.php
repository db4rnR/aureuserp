<?php

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\VendorResource\Pages;

use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\Pages\ViewVendor as BaseViewVendor;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\VendorResource;

class ViewVendor extends BaseViewVendor
{
    protected static string $resource = VendorResource::class;
}
