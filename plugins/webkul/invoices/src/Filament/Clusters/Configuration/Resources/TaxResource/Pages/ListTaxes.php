<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;

use Webkul\Account\Filament\Resources\TaxResource\Pages\ListTaxes as BaseListTaxes;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource;

final class ListTaxes extends BaseListTaxes
{
    protected static string $resource = TaxResource::class;
}
