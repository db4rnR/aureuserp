<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\ProductResource\Pages;

use Webkul\Invoice\Filament\Clusters\Customer\Resources\ProductResource;
use Webkul\Product\Filament\Resources\ProductResource\Pages\ListProducts as BaseListProducts;

final class ListProducts extends BaseListProducts
{
    protected static string $resource = ProductResource::class;
}
