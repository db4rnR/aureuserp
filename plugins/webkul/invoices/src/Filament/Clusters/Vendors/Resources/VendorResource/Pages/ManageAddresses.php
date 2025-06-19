<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\Pages;

use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource;
use Webkul\Partner\Filament\Resources\PartnerResource\Pages\ManageAddresses as BaseManageAddresses;

class ManageAddresses extends BaseManageAddresses
{
    protected static string $resource = VendorResource::class;
}
