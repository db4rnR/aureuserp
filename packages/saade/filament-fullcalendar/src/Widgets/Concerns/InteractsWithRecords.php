<?php

declare(strict_types=1);

namespace Saade\FilamentFullCalendar\Widgets\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Locked;

use function Filament\Support\get_model_label;

trait InteractsWithRecords
{
    #[Locked]
    public Model|string|null $model = null;

    #[Locked]
    public Model|int|string|null $record;

    protected ?string $modelLabel = null;

    protected static ?string $recordRouteKeyName = null;

    public function getModel(): ?string
    {
        $model = $this->model;

        if ($model instanceof Model) {
            return $model::class;
        }

        if (filled($model)) {
            return $model;
        }

        return null;
    }

    public function getRecord(): ?Model
    {
        $record = $this->record;

        if ($record instanceof Model) {
            return $record;
        }

        if (is_string($record)) {
            return null;
        }

        return null;
    }

    public function resolveRecordRouteBinding(int|string $key): ?Model
    {
        return app($this->getModel())
            ->resolveRouteBindingQuery($this->getEloquentQuery(), $key, $this->getRecordRouteKeyName())
            ->first();
    }

    protected function resolveRecord(int|string $key): Model
    {
        $record = $this->resolveRecordRouteBinding($key);

        if ($record === null) {
            throw (new ModelNotFoundException())->setModel($this->getModel(), [$key]);
        }

        return $record;
    }

    protected function getEloquentQuery(): Builder
    {
        $query = app($this->getModel())::query();

        // TODO: Scope query to tenant.

        return $query;
    }

    protected function getRecordRouteKeyName(): ?string
    {
        return static::$recordRouteKeyName;
    }

    protected function getModelLabel(): string
    {
        return $this->modelLabel ?? get_model_label($this->getModel());
    }
}
