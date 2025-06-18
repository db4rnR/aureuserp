<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\FiscalPositionResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Webkul\Account\Filament\Resources\FiscalPositionResource;

final class ListFiscalPositions extends ListRecords
{
    protected static string $resource = FiscalPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
