<?php

declare(strict_types=1);

namespace Webkul\Field\Filament\Forms\Components;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Collection;
use Webkul\Field\Models\Field;

final class CustomFields extends Component
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

        return $fields->map(fn ($field): Component => $this->createField($field))->toArray();
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

    private function createField(Field $field): Component
    {
        $componentClass = match ($field->type) {
            'text' => TextInput::class,
            'textarea' => Textarea::class,
            'select' => Select::class,
            'checkbox' => Checkbox::class,
            'radio' => Radio::class,
            'toggle' => Toggle::class,
            'checkbox_list' => CheckboxList::class,
            'datetime' => DateTimePicker::class,
            'editor' => RichEditor::class,
            'markdown' => MarkdownEditor::class,
            'color' => ColorPicker::class,
            default => TextInput::class,
        };

        $component = $componentClass::make($field->code)
            ->label($field->name);

        if (! empty($field->form_settings['validations'])) {
            foreach ($field->form_settings['validations'] as $validation) {
                $this->applyValidation($component, $validation);
            }
        }

        if (! empty($field->form_settings['settings'])) {
            foreach ($field->form_settings['settings'] as $setting) {
                $this->applySetting($component, $setting);
            }
        }

        if ($field->type === 'text' && $field->input_type !== 'text') {
            $component->{$field->input_type}();
        }

        if (in_array($field->type, ['select', 'radio', 'checkbox_list'], true) && ! empty($field->options)) {
            $component->options(fn () => collect($field->options)
                ->mapWithKeys(fn ($option) => [$option => $option])
                ->toArray());

            if ($field->is_multiselect) {
                $component->multiple();
            }
        }

        if (in_array($field->type, ['select', 'datetime'], true)) {
            $component->native(false);
        }

        return $component;
    }

    private function applyValidation(Component $component, array $validation): void
    {
        $rule = $validation['validation'];

        $field = $validation['field'] ?? null;

        $value = $validation['value'] ?? null;

        if (method_exists($component, $rule)) {
            if ($field) {
                $component->{$rule}($field, $value);
            } elseif ($value) {
                $component->{$rule}($value);
            } else {
                $component->{$rule}();
            }
        }
    }

    private function applySetting(Component $component, array $setting): void
    {
        $name = $setting['setting'];
        $value = $setting['value'] ?? null;

        if (method_exists($component, $name)) {
            if ($value !== null) {
                $component->{$name}($value);
            } else {
                $component->{$name}();
            }
        }
    }
}
