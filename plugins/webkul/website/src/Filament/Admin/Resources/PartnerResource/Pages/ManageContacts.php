<?php

declare(strict_types=1);

namespace Webkul\Website\Filament\Admin\Resources\PartnerResource\Pages;

use Webkul\Partner\Filament\Resources\PartnerResource\Pages\ManageContacts as BaseManageContacts;
use Webkul\Website\Filament\Admin\Resources\PartnerResource;

class ManageContacts extends BaseManageContacts
{
    protected static string $resource = PartnerResource::class;
}
