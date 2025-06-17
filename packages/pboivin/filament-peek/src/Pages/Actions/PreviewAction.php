<?php

namespace Pboivin\FilamentPeek\Pages\Actions;

use Pboivin\FilamentPeek\Support\Concerns\CanPreviewInNewTab;
use Pboivin\FilamentPeek\Support\Concerns\SetsInitialPreviewModalData;
use Pboivin\FilamentPeek\Support\Panel;
use Pboivin\FilamentPeek\Support\Page;
use Pboivin\FilamentPeek\Support\View;
use Filament\Actions\Action;
use Pboivin\FilamentPeek\Support;

class PreviewAction extends Action
{
    use CanPreviewInNewTab;
    use SetsInitialPreviewModalData;

    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-peek::ui.preview-action-label'))
            ->color('gray')
            ->action(function ($livewire) {
                Panel::ensurePluginIsLoaded();

                Page::ensurePreviewModalSupport($livewire);

                $livewire->initialPreviewModalData(
                    $this->evaluate($this->previewModalData)
                );

                if ($this->shouldPreviewInNewTab()) {
                    $livewire->openPreviewTab();
                } else {
                    $livewire->openPreviewModal();
                }
            });

        View::setupPreviewModal();
    }
}
