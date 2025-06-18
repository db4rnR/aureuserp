<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductAttributeResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\ProductAttributeResource;
use Webkul\Product\Filament\Resources\AttributeResource\Pages\CreateAttribute;

final class CreateProductAttribute extends CreateAttribute
{
    protected static string $resource = ProductAttributeResource::class;
}
