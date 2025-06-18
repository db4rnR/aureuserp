<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources\RefundResource\Pages;

use Webkul\Account\Filament\Resources\RefundResource\Pages\CreateRefund as BaseCreateRefund;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\RefundResource;

final class CreateRefund extends BaseCreateRefund
{
    protected static string $resource = RefundResource::class;
}
