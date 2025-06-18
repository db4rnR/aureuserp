<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductAttributeResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductAttributeResource;
use Webkul\Product\Filament\Resources\AttributeResource\Pages\ViewAttribute;

final class ViewProductAttribute extends ViewAttribute
{
    protected static string $resource = ProductAttributeResource::class;
}
