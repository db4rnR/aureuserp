<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource\Pages;

use Webkul\Account\Filament\Resources\TaxGroupResource\Pages\EditTaxGroup as BaseEditTaxGroup;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource;

final class EditTaxGroup extends BaseEditTaxGroup
{
    protected static string $resource = TaxGroupResource::class;
}
