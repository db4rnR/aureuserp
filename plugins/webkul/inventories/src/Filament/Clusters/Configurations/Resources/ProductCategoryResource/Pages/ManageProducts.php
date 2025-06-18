<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductCategoryResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductCategoryResource;
use Webkul\Product\Filament\Resources\CategoryResource\Pages\ManageProducts as BaseManageProducts;

final class ManageProducts extends BaseManageProducts
{
    protected static string $resource = ProductCategoryResource::class;
}
