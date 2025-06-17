<?php

namespace Z3d0X\FilamentFabricator\Resources\PageResource\Pages;

use Z3d0X\FilamentFabricator\Resources\PageResource\Pages\Concerns\HasPreviewModal;
use Filament\Resources\Pages\CreateRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Z3d0X\FilamentFabricator\Resources\PageResource;

class CreatePage extends CreateRecord
{
    use HasPreviewModal;

    protected static string $resource = PageResource::class;

    public static function getResource(): string
    {
        return config('filament-fabricator.page-resource') ?? static::$resource;
    }

    protected function getActions(): array
    {
        return [
            PreviewAction::make(),
        ];
    }
}
