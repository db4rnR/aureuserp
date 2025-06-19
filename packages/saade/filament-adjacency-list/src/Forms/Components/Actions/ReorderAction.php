<?php

declare(strict_types=1);

namespace Saade\FilamentAdjacencyList\Forms\Components\Actions;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

class ReorderAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->iconButton()->icon('heroicon-o-arrows-up-down')->color('gray');

        $this->label(fn (): string => __('filament-adjacency-list::adjacency-list.actions.reorder.label'));

        $this->livewireClickHandlerEnabled(false);

        $this->extraAttributes(['data-sortable-handle' => 'true']);

        $this->size(Size::Small);

        $this->visible(
            fn (AdjacencyList $component): bool => $component->isReorderable()
        );
    }

    public static function getDefaultName(): ?string
    {
        return 'reorder';
    }
}
