<?php

declare(strict_types=1);

namespace Webkul\Account\Enums;

use Filament\Support\Contracts\HasLabel;

enum CommunicationStandard: string implements HasLabel
{
    case AUREUS = 'aureus';

    case EUROPEAN = 'european';

    public static function options(): array
    {
        return [
            self::AUREUS->value => __('accounts::enums/communication-standard.aureus'),
            self::EUROPEAN->value => __('accounts::enums/communication-standard.european'),
        ];
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AUREUS => __('accounts::enums/communication-standard.aureus'),
            self::EUROPEAN => __('accounts::enums/communication-standard.european'),
        };
    }
}
