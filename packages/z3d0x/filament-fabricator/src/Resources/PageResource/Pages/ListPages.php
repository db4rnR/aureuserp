<?php

declare(strict_types=1);

namespace Z3d0X\FilamentFabricator\Resources\PageResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Z3d0X\FilamentFabricator\Resources\PageResource;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    public static function getResource(): string
    {
        return config('filament-fabricator.page-resource') ?? self::$resource;
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
