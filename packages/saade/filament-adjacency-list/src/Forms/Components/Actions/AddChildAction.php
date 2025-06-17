<?php

namespace Saade\FilamentAdjacencyList\Forms\Components\Actions;

use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

class AddChildAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'addChild';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->iconButton()->icon('heroicon-o-plus')->color('gray');

        $this->label(fn (): string => __('filament-adjacency-list::adjacency-list.actions.add-child.label'));

        $this->modalHeading(fn (): string => __('filament-adjacency-list::adjacency-list.actions.add-child.modal.heading'));

        $this->modalSubmitActionLabel(fn (): string => __('filament-adjacency-list::adjacency-list.actions.add-child.modal.actions.create'));

        $this->action(
            function (AdjacencyList $component, array $arguments, array $data): void {
                $statePath = $component->getRelativeStatePath($arguments['statePath']);
                $uuid = (string) Str::uuid();

                $items = $component->getState();

                data_set($items, ("$statePath." . $component->getChildrenKey() . ".$uuid"), [
                    $component->getLabelKey() => __('filament-adjacency-list::adjacency-list.items.untitled'),
                    $component->getChildrenKey() => [],
                    ...$data,
                ]);

                $component->state($items);
            }
        );

        $this->size(Size::ExtraSmall);

        $this->form(
            fn (AdjacencyList $component, Schema $schema) => $component->getForm($schema)
        );

        $this->visible(
            fn (AdjacencyList $component): bool => $component->isAddable()
        );
    }
}
