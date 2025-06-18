<?php

declare(strict_types=1);

namespace FilamentTiptapEditor\Tests\Resources\PageResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use FilamentTiptapEditor\Tests\Resources\PageResource;

final class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
