<?php

namespace Webkul\Sale\Enums;

use Filament\Support\Contracts\HasLabel;

enum AdvancedPayment: string implements HasLabel
{
    case DELIVERED = 'delivered';

    case PERCENTAGE = 'percentage';

    case FIXED = 'fixed';

    public function getLabel(): string
    {
        return match ($this) {
            self::DELIVERED  => __('sales::enums/advanced-payment.delivered'),
            self::PERCENTAGE => __('sales::enums/advanced-payment.percentage'),
            self::FIXED      => __('sales::enums/advanced-payment.fixed'),
        };
    }

    public static function options(): array
    {
        return [
            self::DELIVERED->value  => __('sales::enums/advanced-payment.delivered'),
            self::PERCENTAGE->value => __('sales::enums/advanced-payment.percentage'),
            self::FIXED->value      => __('sales::enums/advanced-payment.fixed'),
        ];
    }
}
