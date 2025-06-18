<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource;

final class CreateLot extends CreateRecord
{
    protected static string $resource = LotResource::class;
}
