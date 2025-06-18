<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductCategoryResource;
use Webkul\Product\Filament\Resources\CategoryResource\Pages\CreateCategory;

final class CreateProductCategory extends CreateCategory
{
    protected static string $resource = ProductCategoryResource::class;
}
