<?php

declare(strict_types=1);

namespace Webkul\Contact\Filament\Resources\PartnerResource\Pages;

use Webkul\Contact\Filament\Resources\PartnerResource;
use Webkul\Partner\Filament\Resources\PartnerResource\Pages\CreatePartner as BaseCreatePartner;

class CreatePartner extends BaseCreatePartner
{
    protected static string $resource = PartnerResource::class;
}
