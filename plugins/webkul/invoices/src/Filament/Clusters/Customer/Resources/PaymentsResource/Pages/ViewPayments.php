<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\PaymentsResource\Pages;

use Webkul\Account\Filament\Resources\PaymentsResource\Pages\ViewPayments as BaseViewPayments;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\PaymentsResource;

final class ViewPayments extends BaseViewPayments
{
    protected static string $resource = PaymentsResource::class;
}
