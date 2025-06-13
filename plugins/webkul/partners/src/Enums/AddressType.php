<?php

namespace Webkul\Partner\Enums;

use Filament\Support\Contracts\HasLabel;

enum AddressType: string implements HasLabel
{
    case PERMANENT = 'permanent';

    case PRESENT = 'present';

    case INVOICE = 'invoice';

    case DELIVERY = 'delivery';

    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::PERMANENT  => __('partners::enums/address-type.permanent'),
            self::PRESENT    => __('partners::enums/address-type.present'),
            self::INVOICE    => __('partners::enums/address-type.invoice'),
            self::DELIVERY   => __('partners::enums/address-type.delivery'),
            self::OTHER      => __('partners::enums/address-type.other'),
        };
    }
}
