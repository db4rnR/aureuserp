<?php

declare(strict_types=1);

namespace Webkul\Website\Filament\Admin\Resources\PartnerResource\Pages;

use Webkul\Partner\Filament\Resources\PartnerResource\Pages\ViewPartner as BaseViewPartner;
use Webkul\Website\Filament\Admin\Resources\PartnerResource;

class ViewPartner extends BaseViewPartner
{
    protected static string $resource = PartnerResource::class;
}
