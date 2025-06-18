<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\QuotationResource\Pages;

use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\OrderResource\Pages\EditOrder;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\QuotationResource;

final class EditQuotation extends EditOrder
{
    protected static string $resource = QuotationResource::class;
}
