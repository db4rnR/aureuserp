<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Customer\Clusters\Account\Resources\QuotationResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Webkul\Purchase\Filament\Customer\Clusters\Account\Resources\QuotationResource;

final class ViewQuotation extends ViewRecord
{
    protected static string $resource = QuotationResource::class;
}
