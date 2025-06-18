<?php

declare(strict_types=1);

namespace Webkul\Product\Filament\Resources\PriceListResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Product\Filament\Resources\PriceListResource;

final class ViewPriceList extends ViewRecord
{
    protected static string $resource = PriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
