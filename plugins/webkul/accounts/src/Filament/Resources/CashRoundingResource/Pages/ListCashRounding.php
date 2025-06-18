<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\CashRoundingResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Webkul\Account\Filament\Resources\CashRoundingResource;

final class ListCashRounding extends ListRecords
{
    protected static string $resource = CashRoundingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
