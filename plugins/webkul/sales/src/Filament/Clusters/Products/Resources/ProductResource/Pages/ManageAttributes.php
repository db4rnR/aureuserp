<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Products\Resources\ProductResource\Pages;

use Webkul\Invoice\Filament\Clusters\Vendors\Resources\ProductResource\Pages\ManageAttributes as BaseManageAttributes;
use Webkul\Sale\Filament\Clusters\Products\Resources\ProductResource;

final class ManageAttributes extends BaseManageAttributes
{
    protected static string $resource = ProductResource::class;
}
