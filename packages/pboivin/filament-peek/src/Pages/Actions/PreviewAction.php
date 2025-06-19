<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Pages\Actions;

use Filament\Actions\Action;
use Pboivin\FilamentPeek\Support\Concerns\CanPreviewInNewTab;
use Pboivin\FilamentPeek\Support\Concerns\SetsInitialPreviewModalData;
use Pboivin\FilamentPeek\Support\Page;
use Pboivin\FilamentPeek\Support\Panel;
use Pboivin\FilamentPeek\Support\View;

class PreviewAction extends Action
{
    use CanPreviewInNewTab;
    use SetsInitialPreviewModalData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-peek::ui.preview-action-label'))
            ->color('gray')
            ->action(function ($livewire): void {
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

    public static function getDefaultName(): ?string
    {
        return 'preview';
    }
}
