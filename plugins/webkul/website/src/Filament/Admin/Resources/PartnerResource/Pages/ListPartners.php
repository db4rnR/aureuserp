<?php

declare(strict_types=1);

namespace Webkul\Website\Filament\Admin\Resources\PartnerResource\Pages;

use Webkul\Partner\Filament\Resources\PartnerResource\Pages\ListPartners as BaseListPartners;
use Webkul\Website\Filament\Admin\Resources\PartnerResource;

final class ListPartners extends BaseListPartners
{
    protected static string $resource = PartnerResource::class;
}
