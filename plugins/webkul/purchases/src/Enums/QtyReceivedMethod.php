<?php

namespace Webkul\Purchase\Enums;

use Filament\Support\Contracts\HasLabel;

enum QtyReceivedMethod: string implements HasLabel
{
    case MANUAL = 'manual';

    case STOCK_MOVE = 'stock_move';

    public function getLabel(): string
    {
        return match ($this) {
            self::MANUAL     => __('purchases::enums/qty-received-method.manual'),
            self::STOCK_MOVE => __('purchases::enums/qty-received-method.stock-move'),
        };
    }
}
