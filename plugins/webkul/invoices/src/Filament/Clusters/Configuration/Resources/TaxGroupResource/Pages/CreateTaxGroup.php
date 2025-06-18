<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource\Pages;

use Webkul\Account\Filament\Resources\TaxGroupResource\Pages\CreateTaxGroup as BaseCreateTaxGroup;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource;

final class CreateTaxGroup extends BaseCreateTaxGroup
{
    protected static string $resource = TaxGroupResource::class;
}
