<?php

declare(strict_types=1);

namespace Saade\FilamentAdjacencyList\Forms\Components\Actions;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

class DeleteAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->iconButton()->icon('heroicon-o-trash')->color('danger');

        $this->label(fn (): string => __('filament-adjacency-list::adjacency-list.actions.delete.label'));

        $this->modalIcon('heroicon-o-trash');

        $this->modalHeading(fn (): string => __('filament-adjacency-list::adjacency-list.actions.delete.modal.heading'));

        $this->modalSubmitActionLabel(fn (): string => __('filament-adjacency-list::adjacency-list.actions.delete.modal.actions.confirm'));

        $this->action(
            function (array $arguments, AdjacencyList $component): void {
                $statePath = $component->getRelativeStatePath($arguments['statePath']);
                $items = $component->getState();

                data_forget($items, $statePath);

                $component->state($items);
            }
        );

        $this->size(Size::ExtraSmall);

        $this->visible(
            fn (AdjacencyList $component): bool => $component->isDeletable()
        );
    }

    public static function getDefaultName(): ?string
    {
        return 'delete';
    }
}
