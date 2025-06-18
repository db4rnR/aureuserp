<?php

declare(strict_types=1);

namespace Webkul\Account\Filament\Resources\PaymentTermResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Webkul\Account\Traits\PaymentDueTerm;

final class PaymentDueTermRelationManager extends RelationManager
{
    use PaymentDueTerm;

    protected static string $relationship = 'dueTerm';

    protected static ?string $title = 'Due Terms';
}
