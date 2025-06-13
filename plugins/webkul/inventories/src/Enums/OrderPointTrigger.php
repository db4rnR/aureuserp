<?php

namespace Webkul\Inventory\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderPointTrigger: string implements HasLabel
{
    case MANUAL = 'manual';

    case AUTOMATIC = 'automatic';

    public function getLabel(): string
    {
        return match ($this) {
            self::MANUAL    => __('inventories::enums/order-point-trigger.manual'),
            self::AUTOMATIC => __('inventories::enums/order-point-trigger.automatic'),
        };
    }
}
