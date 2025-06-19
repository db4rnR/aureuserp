<?php

declare(strict_types=1);

namespace Z3d0X\FilamentFabricator\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Z3d0X\FilamentFabricator\Resources\PageResource;
use Z3d0X\FilamentFabricator\Resources\PageResource\Pages\Concerns\HasPreviewModal;

class CreatePage extends CreateRecord
{
    use HasPreviewModal;

    protected static string $resource = PageResource::class;

    public static function getResource(): string
    {
        return config('filament-fabricator.page-resource') ?? self::$resource;
    }

    protected function getActions(): array
    {
        return [
            PreviewAction::make(),
        ];
    }
}
