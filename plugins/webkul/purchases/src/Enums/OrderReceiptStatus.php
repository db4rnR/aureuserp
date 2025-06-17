<?php

namespace Webkul\Purchase\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderReceiptStatus: string implements HasColor, HasLabel
{
    case NO = 'no';

    case PENDING = 'pending';

    case PARTIAL = 'partial';

    case FULL = 'full';

    public static function options(): array
    {
        return [
            self::NO->value      => __('purchases::enums/order-receipt-status.no'),
            self::PENDING->value => __('purchases::enums/order-receipt-status.pending'),
            self::PARTIAL->value => __('purchases::enums/order-receipt-status.partial'),
            self::FULL->value    => __('purchases::enums/order-receipt-status.full'),
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::NO      => __('purchases::enums/order-receipt-status.no'),
            self::PENDING => __('purchases::enums/order-receipt-status.pending'),
            self::PARTIAL => __('purchases::enums/order-receipt-status.partial'),
            self::FULL    => __('purchases::enums/order-receipt-status.full'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::NO      => 'gray',
            self::PENDING => 'info',
            self::PARTIAL => 'warning',
            self::FULL    => 'success',
        };
    }
}
