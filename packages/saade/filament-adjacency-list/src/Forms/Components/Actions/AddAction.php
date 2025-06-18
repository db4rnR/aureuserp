<?php

declare(strict_types=1);

namespace Saade\FilamentAdjacencyList\Forms\Components\Actions;

use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Size;
use Illuminate\Support\Str;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

final class AddAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->button()->color('gray');

        $this->label(fn (): string => __('filament-adjacency-list::adjacency-list.actions.add.label'));

        $this->modalHeading(fn (): string => __('filament-adjacency-list::adjacency-list.actions.add.modal.heading'));

        $this->modalSubmitActionLabel(fn (): string => __('filament-adjacency-list::adjacency-list.actions.add.modal.actions.create'));

        $this->action(
            function (AdjacencyList $component, array $data): void {
                $items = $component->getState();

                $items[(string) Str::uuid()] = [
                    $component->getLabelKey() => __('filament-adjacency-list::adjacency-list.items.untitled'),
                    $component->getChildrenKey() => [],
                    ...$data,
                ];

                $component->state($items);
            }
        );

        $this->size(Size::Small);

        $this->form(
            fn (AdjacencyList $component, Schema $schema) => $component->getForm($schema)
        );

        $this->visible(
            fn (AdjacencyList $component): bool => $component->isAddable()
        );
    }

    public static function getDefaultName(): ?string
    {
        return 'add';
    }
}
