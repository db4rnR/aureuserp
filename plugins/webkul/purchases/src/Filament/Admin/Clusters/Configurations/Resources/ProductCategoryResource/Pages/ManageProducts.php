<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Configurations\Resources\ProductCategoryResource\Pages;

use Webkul\Product\Filament\Resources\CategoryResource\Pages\ManageProducts as BaseManageProducts;
use Webkul\Purchase\Filament\Admin\Clusters\Configurations\Resources\ProductCategoryResource;

final class ManageProducts extends BaseManageProducts
{
    protected static string $resource = ProductCategoryResource::class;
}
