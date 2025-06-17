<?php

namespace Pboivin\FilamentPeek\Forms\Actions;

use Filament\Actions\Action;
use Pboivin\FilamentPeek\Support\Concerns\SetsInitialPreviewModalData;
use Pboivin\FilamentPeek\Support\Panel;
use Pboivin\FilamentPeek\Support\Page;
use Pboivin\FilamentPeek\Support\View;
use Pboivin\FilamentPeek\Support;

class InlinePreviewAction extends Action
{
    use SetsInitialPreviewModalData;

    public static int $count = 1;

    protected ?string $builderField = null;

    public static function getDefaultName(): ?string
    {
        return 'inlinePreview'.static::$count++;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-peek::ui.preview-action-label'))
            ->link()
            ->action(function ($livewire) {
                Panel::ensurePluginIsLoaded();

                Page::ensurePreviewModalSupport($livewire);

                if ($this->builderField) {
                    Page::ensureBuilderPreviewSupport($livewire);

                    $livewire->openPreviewModalForBuidler($this->builderField);
                } else {
                    $livewire->initialPreviewModalData(
                        $this->evaluate($this->previewModalData)
                    );

                    $livewire->openPreviewModal();
                }
            });

        View::setupPreviewModal();
    }

    public function builderPreview(string $builderField = 'blocks'): static
    {
        View::setupBuilderEditor();

        $this->builderField = $builderField;

        return $this;
    }

    /** Alias for builderPreview */
    public function builderName(string $builderField = 'blocks'): static
    {
        $this->builderPreview($builderField);

        return $this;
    }
}
