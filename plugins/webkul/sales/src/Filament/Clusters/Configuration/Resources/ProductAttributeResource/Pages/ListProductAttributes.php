<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductAttributeResource\Pages\ListProductAttributes as BaseListProductAttributes;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource;

class ListProductAttributes extends BaseListProductAttributes
{
    protected static string $resource = ProductAttributeResource::class;
}
