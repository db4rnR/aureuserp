<?php

declare(strict_types=1);

namespace Webkul\Product\Filament\Resources\PriceListResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Webkul\Product\Filament\Resources\PriceListResource;

final class ListPriceLists extends ListRecords
{
    protected static string $resource = PriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
