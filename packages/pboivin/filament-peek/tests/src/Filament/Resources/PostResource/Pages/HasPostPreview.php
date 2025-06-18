<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests\Filament\Resources\PostResource\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

trait HasPostPreview
{
    use HasBuilderPreview;
    use HasPreviewModal;

    public static function getBuilderEditorSchema(string $builderName): Component|array
    {
        return [TextInput::make('test')];
    }

    protected function getActions(): array
    {
        return [
            PreviewAction::make()
                ->label('Test_Preview_Action'),
        ];
    }

    protected function getPreviewModalView(): ?string
    {
        return 'preview-post';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'post';
    }
}
