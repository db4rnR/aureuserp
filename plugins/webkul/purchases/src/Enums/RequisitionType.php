<?php

namespace Webkul\Purchase\Enums;

use Filament\Support\Contracts\HasLabel;

enum RequisitionType: string implements HasLabel
{
    case BLANKET_ORDER = 'blanket_order';

    case PURCHASE_TEMPLATE = 'purchase_template';

    public static function options(): array
    {
        return [
            self::BLANKET_ORDER->value     => __('purchases::enums/requisition-type.blanket-order'),
            self::PURCHASE_TEMPLATE->value => __('purchases::enums/requisition-type.purchase-template'),
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::BLANKET_ORDER     => __('purchases::enums/requisition-type.blanket-order'),
            self::PURCHASE_TEMPLATE => __('purchases::enums/requisition-type.purchase-template'),
        };
    }
}
