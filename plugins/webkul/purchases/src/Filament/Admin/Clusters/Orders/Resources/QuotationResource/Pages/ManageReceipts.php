<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\QuotationResource\Pages;

use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\OrderResource\Pages\ManageReceipts as BaseManageReceipts;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\QuotationResource;

final class ManageReceipts extends BaseManageReceipts
{
    protected static string $resource = QuotationResource::class;
}
