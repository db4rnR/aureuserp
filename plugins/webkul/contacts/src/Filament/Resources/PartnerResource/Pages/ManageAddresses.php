<?php

declare(strict_types=1);

namespace Webkul\Contact\Filament\Resources\PartnerResource\Pages;

use Webkul\Contact\Filament\Resources\PartnerResource;
use Webkul\Partner\Filament\Resources\PartnerResource\Pages\ManageAddresses as BaseManageAddresses;

class ManageAddresses extends BaseManageAddresses
{
    protected static string $resource = PartnerResource::class;
}
