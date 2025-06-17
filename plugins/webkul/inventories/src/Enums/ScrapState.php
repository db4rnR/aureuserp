<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ScrapState: string implements HasColor, HasLabel
{
    case DRAFT = 'draft';

    case DONE = 'done';

    public static function options(): array
    {
        return [
            self::DRAFT->value    => __('inventories::enums/scrap-state.draft'),
            self::DONE->value     => __('inventories::enums/scrap-state.done'),
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT    => __('inventories::enums/scrap-state.draft'),
            self::DONE     => __('inventories::enums/scrap-state.done'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::DONE  => 'success',
        };
    }
}
