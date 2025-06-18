<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Configurations\Resources\ProductCategoryResource\Pages;

use Webkul\Product\Filament\Resources\CategoryResource\Pages\CreateCategory;
use Webkul\Purchase\Filament\Admin\Clusters\Configurations\Resources\ProductCategoryResource;

final class CreateProductCategory extends CreateCategory
{
    protected static string $resource = ProductCategoryResource::class;
}
