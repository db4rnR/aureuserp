<?php

declare(strict_types=1);

namespace Saade\FilamentAdjacencyList\Forms\Components\Actions;

use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

final class EditAction extends Action
{
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

    public static function getDefaultName(): ?string
    {
        return 'edit';
    }
}
