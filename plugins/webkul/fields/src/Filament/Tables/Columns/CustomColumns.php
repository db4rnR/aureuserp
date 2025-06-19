<?php

declare(strict_types=1);

namespace Webkul\Field\Filament\Tables\Columns;

use Filament\Support\Components\Component;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;
use Webkul\Field\Models\Field;

class CustomColumns extends Component
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

    public function getColumns(): array
    {
        $fields = $this->getFields();

        return $fields->map(fn ($field): Column => $this->createColumn($field))->toArray();
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

        return $query->orderBy('sort')->get();
    }

    private function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    private function createColumn(Field $field): Column
    {
        $columnClass = match ($field->type) {
            'text', 'textarea', 'select', 'radio' => TextColumn::class,
            'checkbox', 'toggle' => IconColumn::class,
            'checkbox_list' => TextColumn::class,
            'datetime' => TextColumn::class,
            'editor', 'markdown' => TextColumn::class,
            'color' => ColorColumn::class,
            default => TextColumn::class,
        };

        $column = $columnClass::make($field->code)->label($field->name);

        if (! empty($field->table_settings)) {
            foreach ($field->table_settings as $setting) {
                $this->applySetting($column, $setting);
            }
        }

        return $column;
    }

    private function applySetting(Column $column, array $setting): void
    {
        $name = $setting['setting'];
        $value = $setting['value'] ?? null;

        if (method_exists($column, $name)) {
            if ($value !== null) {
                if ($name === 'weight') {
                    $column->{$name}(constant(FontWeight::class."::$value"));
                } elseif ($name === 'size') {
                    $column->{$name}(constant(TextSize::class."::$value"));
                } else {
                    $column->{$name}($value);
                }
            } else {
                $column->{$name}();
            }
        }
    }
}
