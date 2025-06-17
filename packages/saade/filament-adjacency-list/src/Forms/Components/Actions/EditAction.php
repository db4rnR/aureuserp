<?php

namespace Saade\FilamentAdjacencyList\Forms\Components\Actions;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Schemas\Schema;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

class EditAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'edit';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->iconButton()->icon('heroicon-o-pencil-square')->color('gray');

        $this->label(fn (): string => __('filament-adjacency-list::adjacency-list.actions.edit.label'));

        $this->modalHeading(fn (): string => __('filament-adjacency-list::adjacency-list.actions.edit.modal.heading'));

        $this->modalSubmitActionLabel(fn (): string => __('filament-adjacency-list::adjacency-list.actions.edit.modal.actions.save'));

        $this->action(
            function (AdjacencyList $component, array $arguments, array $data): void {
                $statePath = $component->getRelativeStatePath($arguments['statePath']);
                $state = $component->getState();

                $item = array_merge(data_get($state, $statePath), $data);

                data_set($state, $statePath, $item);

                $component->state($state);
            }
        );

        $this->size(Size::Small);

        $this->form(
            fn (AdjacencyList $component, Schema $schema) => $component->getForm($schema)
        );

        $this->mountUsing(
            fn (AdjacencyList $component, Schema $schema, array $arguments) => $schema->fill(
                data_get($component->getState(), $component->getRelativeStatePath($arguments['statePath']), [])
            )
        );

        $this->visible(
            fn (AdjacencyList $component): bool => $component->isEditable()
        );
    }
}
