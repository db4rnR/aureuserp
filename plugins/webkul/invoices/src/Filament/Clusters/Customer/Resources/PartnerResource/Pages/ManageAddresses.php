<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\PartnerResource\Pages;

use Webkul\Invoice\Filament\Clusters\Customer\Resources\PartnerResource;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\Pages\ManageAddresses as BaseManageAddresses;

final class ManageAddresses extends BaseManageAddresses
{
    protected static string $resource = PartnerResource::class;
}
