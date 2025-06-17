<?php

namespace Webkul\Purchase\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RequisitionState: string implements HasColor, HasLabel
{
    case DRAFT = 'draft';

    case CONFIRMED = 'confirmed';

    case CLOSED = 'closed';

    case CANCELED = 'canceled';

    public static function options(): array
    {
        return [
            self::DRAFT->value     => __('purchases::enums/requisition-state.draft'),
            self::CONFIRMED->value => __('purchases::enums/requisition-state.confirmed'),
            self::CLOSED->value    => __('purchases::enums/requisition-state.closed'),
            self::CANCELED->value  => __('purchases::enums/requisition-state.canceled'),
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT     => __('purchases::enums/requisition-state.draft'),
            self::CONFIRMED => __('purchases::enums/requisition-state.confirmed'),
            self::CLOSED    => __('purchases::enums/requisition-state.closed'),
            self::CANCELED  => __('purchases::enums/requisition-state.canceled'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DRAFT     => 'gray',
            self::CONFIRMED => 'blue',
            self::CLOSED    => 'success',
            self::CANCELED  => 'danger',
        };
    }
}
