<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;

use Webkul\Account\Filament\Resources\TaxResource\Pages\ManageDistributionForInvoice as BaseManageDistributionForInvoice;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource;

final class ManageDistributionForInvoice extends BaseManageDistributionForInvoice
{
    protected static string $resource = TaxResource::class;
}
