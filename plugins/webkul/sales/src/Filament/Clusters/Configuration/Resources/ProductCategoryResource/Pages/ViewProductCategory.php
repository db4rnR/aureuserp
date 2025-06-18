<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages\ViewProductCategory as BaseViewProductCategory;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource;

final class ViewProductCategory extends BaseViewProductCategory
{
    protected static string $resource = ProductCategoryResource::class;
}
