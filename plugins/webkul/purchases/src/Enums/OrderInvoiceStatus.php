<?php

namespace Webkul\Purchase\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderInvoiceStatus: string implements HasColor, HasLabel
{
    case NO = 'no';

    case TO_INVOICED = 'to_invoiced';

    case INVOICED = 'invoiced';

    public static function options(): array
    {
        return [
            self::NO->value          => __('purchases::enums/order-invoice-status.no'),
            self::TO_INVOICED->value => __('purchases::enums/order-invoice-status.to-invoiced'),
            self::INVOICED->value    => __('purchases::enums/order-invoice-status.invoiced'),
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::NO          => __('purchases::enums/order-invoice-status.no'),
            self::TO_INVOICED => __('purchases::enums/order-invoice-status.to-invoiced'),
            self::INVOICED    => __('purchases::enums/order-invoice-status.invoiced'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::NO          => 'gray',
            self::TO_INVOICED => 'warning',
            self::INVOICED    => 'success',
        };
    }
}
