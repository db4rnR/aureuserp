<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Configurations\Resources\ProductAttributeResource\Pages;

use Webkul\Product\Filament\Resources\AttributeResource\Pages\EditAttribute;
use Webkul\Purchase\Filament\Admin\Clusters\Configurations\Resources\ProductAttributeResource;

final class EditProductAttribute extends EditAttribute
{
    protected static string $resource = ProductAttributeResource::class;
}
