<?php

declare(strict_types=1);

namespace Webkul\Website\Filament\Admin\Resources\PartnerResource\Pages;

use Webkul\Partner\Filament\Resources\PartnerResource\Pages\ManageAddresses as BaseManageAddresses;
use Webkul\Website\Filament\Admin\Resources\PartnerResource;

final class ManageAddresses extends BaseManageAddresses
{
    protected static string $resource = PartnerResource::class;
}
