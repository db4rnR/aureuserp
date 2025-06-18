<?php

declare(strict_types=1);

namespace Webkul\Product\Filament\Resources\PriceListResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Product\Filament\Resources\PriceListResource;

final class CreatePriceList extends CreateRecord
{
    protected static string $resource = PriceListResource::class;
}
