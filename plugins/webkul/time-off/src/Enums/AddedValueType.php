<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Enums;

use Filament\Support\Contracts\HasLabel;

enum AddedValueType: string implements HasLabel
{
    case DAYS = 'days';

    case HOURS = 'hours';

    public static function options(): array
    {
        return [
            self::DAYS->value => __('time-off::enums/added-value-type.days'),
            self::HOURS->value => __('time-off::enums/added-value-type.hours'),
        ];
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DAYS => __('time-off::enums/added-value-type.days'),
            self::HOURS => __('time-off::enums/added-value-type.hours'),
        };
    }
}
