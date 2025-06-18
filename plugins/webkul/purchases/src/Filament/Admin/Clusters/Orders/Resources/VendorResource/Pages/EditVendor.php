<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\VendorResource\Pages;

use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\Pages\EditVendor as BaseEditVendor;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\VendorResource;

final class EditVendor extends BaseEditVendor
{
    protected static string $resource = VendorResource::class;
}
