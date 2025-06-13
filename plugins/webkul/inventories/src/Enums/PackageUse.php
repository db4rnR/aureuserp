<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum PackageUse: string implements HasLabel
{
    case DISPOSABLE = 'disposable';

    case REUSABLE = 'reusable';

    public function getLabel(): string
    {
        return match ($this) {
            self::DISPOSABLE => __('inventories::enums/package-use.disposable'),
            self::REUSABLE   => __('inventories::enums/package-use.reusable'),
        };
    }
}
