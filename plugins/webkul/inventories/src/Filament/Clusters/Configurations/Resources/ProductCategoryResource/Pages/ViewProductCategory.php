<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductCategoryResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductCategoryResource;
use Webkul\Product\Filament\Resources\CategoryResource\Pages\ViewCategory;

final class ViewProductCategory extends ViewCategory
{
    protected static string $resource = ProductCategoryResource::class;
}
