<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource\Pages;

use Webkul\Invoice\Filament\Clusters\Vendors\Resources\VendorResource;
use Webkul\Partner\Filament\Resources\PartnerResource\Pages\ManageContacts as BaseManageContacts;

final class ManageContacts extends BaseManageContacts
{
    protected static string $resource = VendorResource::class;
}
