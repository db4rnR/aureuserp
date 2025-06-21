<?php

declare(strict_types=1);

namespace Webkul\TableViews\Filament\Components;

use Closure;
use Filament\Forms\Components\Tabs\Tab;
use Webkul\TableViews\Models\TableViewFavorite;

class PresetView extends Tab
{
    protected string|Closure|null $id = null;

    private string|Closure|null $color = null;

    private bool|Closure $isDefault = false;

    private bool|Closure $isFavorite = false;

    private bool|Closure $isEditable = false;

    private bool|Closure $isReplaceable = false;

    private bool|Closure $isDeletable = false;

    private static mixed $cachedFavoriteTableViews;

    /**
     * @return array<string|int, Tab>
     */
    public function getFavoriteTableViews(): mixed
    {
        return TableViewFavorite::query()
            ->where('user_id', auth()->id())
            ->get();
    }

    public function getCachedFavoriteTableViews(): mixed
    {
        return self::$cachedFavoriteTableViews ??= $this->getFavoriteTableViews();
    }

    public function color(string|Closure|null $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getModel(): ?string
    {
        return null;
    }

    public function favorite(bool|Closure $condition = true): static
    {
        $this->isFavorite = $condition;

        return $this;
    }

    public function default(bool|Closure $condition = true): static
    {
        $this->isDefault = $condition;

        return $this;
    }

    public function isDefault(): bool
    {
        return (bool) $this->evaluate($this->isDefault);
    }

    public function isFavorite(string|int|null $id = null): bool
    {
        $tableViewFavorite = $this->getCachedFavoriteTableViews()
            ->where('view_type', 'preset')
            ->where('view_key', $id)
            ->first();

        return (bool) ($tableViewFavorite?->is_favorite ?? $this->evaluate($this->isFavorite));
    }

    public function isEditable(): bool
    {
        return $this->isEditable;
    }

    public function isReplaceable(): bool
    {
        return $this->isReplaceable;
    }

    public function isDeletable(): bool
    {
        return $this->isDeletable;
    }

    public function getVisibilityIcon(): string
    {
        return 'heroicon-o-lock-closed';
    }

    /**
     * @return string | array{50: string, 100: string, 200: string, 300: string, 400: string, 500: string, 600: string, 700: string, 800: string, 900: string, 950: string} | null
     */
    public function getColor(): string|array|null
    {
        return $this->evaluate($this->color);
    }
}
