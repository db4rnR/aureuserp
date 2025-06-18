<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages\ListProductCategories as BaseListProductCategories;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource;

final class ListProductCategories extends BaseListProductCategories
{
    protected static string $resource = ProductCategoryResource::class;
}
