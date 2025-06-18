<?php

declare(strict_types=1);

namespace Webkul\Contact\Filament\Resources\PartnerResource\Pages;

use Webkul\Contact\Filament\Resources\PartnerResource;
use Webkul\Partner\Filament\Resources\PartnerResource\Pages\EditPartner as BaseEditPartner;

final class EditPartner extends BaseEditPartner
{
    protected static string $resource = PartnerResource::class;
}
