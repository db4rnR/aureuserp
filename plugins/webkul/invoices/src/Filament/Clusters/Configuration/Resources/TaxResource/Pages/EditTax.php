<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;

use Webkul\Account\Filament\Resources\TaxResource\Pages\EditTax as BaseEditTax;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource;

final class EditTax extends BaseEditTax
{
    protected static string $resource = TaxResource::class;
}
