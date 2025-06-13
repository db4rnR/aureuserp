<?php

namespace Webkul\Sale\Filament\Clusters\Products\Resources\ProductResource\Pages;

use Webkul\Product\Filament\Resources\ProductResource\Pages\EditProduct as BaseEditProduct;
use Webkul\Sale\Filament\Clusters\Products\Resources\ProductResource;

class EditProduct extends BaseEditProduct
{
    protected static string $resource = ProductResource::class;
}
