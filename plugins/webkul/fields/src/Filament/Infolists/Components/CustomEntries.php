<?php

declare(strict_types=1);

namespace Webkul\Field\Filament\Infolists\Components;

use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Component;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Collection;
use Webkul\Field\Models\Field;

class CustomEntries extends Component
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

    public function getSchema(): array
    {
        $fields = $this->getFields();

        return $fields->map(fn ($field): Component => $this->createEntry($field))->toArray();
    }

    private function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    private function getFields(): Collection
    {
        $query = Field::query()
            ->where('customizable_type', $this->resourceClass::getModel());

        if ($this->include !== []) {
            $query->whereIn('code', $this->include);
        }

        if ($this->exclude !== []) {
            $query->whereNotIn('code', $this->exclude);
        }

        return $query->orderBy('sort')->get();
    }

    private function createEntry(Field $field): Component
    {
        $entryClass = match ($field->type) {
            'text', 'textarea', 'select', 'radio' => TextEntry::class,
            'checkbox', 'toggle' => IconEntry::class,
            'checkbox_list' => TextEntry::class,
            'datetime' => TextEntry::class,
            'editor', 'markdown' => TextEntry::class,
            'color' => ColorEntry::class,
            default => TextEntry::class,
        };

        $entry = $entryClass::make($field->code)->label($field->name);

        if (! empty($field->infolist_settings)) {
            foreach ($field->infolist_settings as $setting) {
                $this->applySetting($entry, $setting);
            }
        }

        return $entry;
    }

    private function applySetting(Entry $column, array $setting): void
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
