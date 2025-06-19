<?php

declare(strict_types=1);

namespace Webkul\Field\Filament\Tables\Filters;

use Filament\Support\Components\Component;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\Constraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Webkul\Field\Models\Field;

class CustomFilters extends Component
{
    private array $include = [];

    private array $exclude = [];

    final public function __construct(private readonly ?string $resourceClass) {}

    public static function make(string $resource): static
    {
        $static = app(self::class, ['resource' => $resource]);

        $static->configure();

        return $static;
    }

    public function include(array $fields): static
    {
        $this->include = $fields;

        return $this;
    }

    public function exclude(array $fields): static
    {
        $this->exclude = $fields;

        return $this;
    }

    public function getFilters(): array
    {
        $fields = $this->getFields();

        return $fields->map(fn ($field): BaseFilter => $this->createFilter($field))->toArray();
    }

    public function getQueryBuilderConstraints(): array
    {
        $fields = $this->getFields();

        return $fields->map(fn ($field): Constraint => $this->createConstraint($field))->toArray();
    }

    private function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    private function getFields(): Collection
    {
        $query = Field::query()
            ->where('customizable_type', $this->resourceClass::getModel())
            ->where('use_in_table', true);

        if ($this->include !== []) {
            $query->whereIn('code', $this->include);
        }

        if ($this->exclude !== []) {
            $query->whereNotIn('code', $this->exclude);
        }

        return $query
            ->orderBy('sort')
            ->whereJsonContains('table_settings', ['setting' => 'filterable'])
            ->get();
    }

    private function createFilter(Field $field): BaseFilter
    {
        $filter = match ($field->type) {
            'checkbox' => Filter::make($field->code)->query(fn (Builder $query): Builder => $query->where($field->code, true)),

            'toggle' => Filter::make($field->code)->toggle()
                ->query(fn (Builder $query): Builder => $query->where($field->code, true)),

            'radio' => SelectFilter::make($field->code)->options(fn () => collect($field->options)
                    ->mapWithKeys(fn ($option) => [$option => $option])
                    ->toArray()),

            'select' => $field->is_multiselect
                ? SelectFilter::make($field->code)->options(fn () => collect($field->options)
                        ->mapWithKeys(fn ($option) => [$option => $option])
                        ->toArray())
                    ->query(function (Builder $query, $state) use ($field): Builder {
                        if (empty($state['values'])) {
                            return $query;
                        }

                        return $query->where(function (Builder $query) use ($state, $field): void {
                            foreach ((array) $state as $value) {
                                $query->orWhereJsonContains($field->code, $value);
                            }
                        });
                    })
                    ->multiple()
                : SelectFilter::make($field->code)->options(fn () => collect($field->options)
                        ->mapWithKeys(fn ($option) => [$option => $option])
                        ->toArray()),

            'checkbox_list' => SelectFilter::make($field->code)->options(fn () => collect($field->options)
                    ->mapWithKeys(fn ($option) => [$option => $option])
                    ->toArray())
                ->query(function (Builder $query, $state) use ($field): Builder {
                    if (empty($state['values'])) {
                        return $query;
                    }

                    return $query->where(function (Builder $query) use ($state, $field): void {
                        foreach ((array) $state as $value) {
                            $query->orWhereJsonContains($field->code, $value);
                        }
                    });
                })
                ->multiple(),

            default => Filter::make($field->code),
        };

        return $filter->label($field->name);
    }

    private function createConstraint(Field $field): Constraint
    {
        $filter = match ($field->type) {
            'text' => match ($field->input_type) {
                'integer' => NumberConstraint::make($field->code)->integer(),
                'numeric' => NumberConstraint::make($field->code),
                default => TextConstraint::make($field->code),
            },

            'datetime' => DateConstraint::make($field->code),

            'checkbox', 'toggle' => BooleanConstraint::make($field->code),

            'select' => $field->is_multiselect
                ? SelectConstraint::make($field->code)->options(fn () => collect($field->options)
                        ->mapWithKeys(fn ($option) => [$option => $option])
                        ->toArray())
                    ->multiple()
                : SelectConstraint::make($field->code)->options(fn () => collect($field->options)
                        ->mapWithKeys(fn ($option) => [$option => $option])
                        ->toArray()),

            'checkbox_list' => SelectConstraint::make($field->code)->options(fn () => collect($field->options)
                    ->mapWithKeys(fn ($option) => [$option => $option])
                    ->toArray())
                ->multiple(),

            default => TextConstraint::make($field->code),
        };

        return $filter->label($field->name);
    }
}
