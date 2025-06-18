<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\JournalResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Webkul\Account\Filament\Resources\JournalResource;

final class ListJournals extends ListRecords
{
    protected static string $resource = JournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
