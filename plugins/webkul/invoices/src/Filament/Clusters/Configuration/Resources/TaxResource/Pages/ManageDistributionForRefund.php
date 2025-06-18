<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;

use Webkul\Account\Filament\Resources\TaxResource\Pages\ManageDistributionForRefund as BaseManageDistributionForRefund;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource;

final class ManageDistributionForRefund extends BaseManageDistributionForRefund
{
    protected static string $resource = TaxResource::class;
}
