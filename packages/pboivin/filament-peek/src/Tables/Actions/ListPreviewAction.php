<?php

namespace Pboivin\FilamentPeek\Tables\Actions;

use Filament\Actions\Action;
use Pboivin\FilamentPeek\Support\Concerns\SetsInitialPreviewModalData;
use Pboivin\FilamentPeek\Support\Panel;
use Pboivin\FilamentPeek\Support\Page;
use Pboivin\FilamentPeek\Support\View;
use Pboivin\FilamentPeek\Support;

class ListPreviewAction extends Action
{
    use SetsInitialPreviewModalData;

    public static function getDefaultName(): ?string
    {
        return 'listPreview';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-peek::ui.preview-action-label'))
            ->icon('heroicon-s-eye')
            ->action(function ($livewire, $record) {
                Panel::ensurePluginIsLoaded();

                Page::ensurePreviewModalSupport($livewire);

                $livewire->initialPreviewModalData(
                    $this->evaluate($this->previewModalData)
                );

                $livewire->setPreviewableRecord($record);

                $livewire->openPreviewModal();
            });

        View::setupPreviewModal();
    }
}
