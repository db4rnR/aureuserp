<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Forms\Actions;

use Filament\Actions\Action;
use Pboivin\FilamentPeek\Support\Concerns\SetsInitialPreviewModalData;
use Pboivin\FilamentPeek\Support\Page;
use Pboivin\FilamentPeek\Support\Panel;
use Pboivin\FilamentPeek\Support\View;

class InlinePreviewAction extends Action
{
    use SetsInitialPreviewModalData;

    public static int $count = 1;

    protected ?string $builderField = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-peek::ui.preview-action-label'))
            ->link()
            ->action(function ($livewire): void {
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

    public static function getDefaultName(): ?string
    {
        return 'inlinePreview'.self::$count++;
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
