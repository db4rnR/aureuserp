<?php

namespace Webkul\Partner\Enums;

use Filament\Support\Contracts\HasLabel;

enum AccountType: string implements HasLabel
{
    case INDIVIDUAL = 'individual';

    case COMPANY = 'company';

    case ADDRESS = 'address';

    public static function options(): array
    {
        return [
            self::INDIVIDUAL->value => __('partners::enums/account-type.individual'),
            self::COMPANY->value    => __('partners::enums/account-type.company'),
            self::ADDRESS->value    => __('partners::enums/account-type.address'),
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::INDIVIDUAL => __('partners::enums/account-type.individual'),
            self::COMPANY    => __('partners::enums/account-type.company'),
            self::ADDRESS    => __('partners::enums/account-type.address'),
        };
    }
}
