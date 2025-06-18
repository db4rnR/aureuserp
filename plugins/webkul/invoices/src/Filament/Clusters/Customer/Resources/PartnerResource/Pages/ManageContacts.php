<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\PartnerResource\Pages;

use Webkul\Invoice\Filament\Clusters\Customer\Resources\PartnerResource;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\Pages\ManageContacts as BaseManageContacts;

final class ManageContacts extends BaseManageContacts
{
    protected static string $resource = PartnerResource::class;
}
