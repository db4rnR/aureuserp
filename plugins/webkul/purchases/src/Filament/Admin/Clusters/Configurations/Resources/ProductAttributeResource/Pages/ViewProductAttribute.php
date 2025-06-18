<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Configurations\Resources\ProductAttributeResource\Pages;

use Webkul\Product\Filament\Resources\AttributeResource\Pages\ViewAttribute;
use Webkul\Purchase\Filament\Admin\Clusters\Configurations\Resources\ProductAttributeResource;

final class ViewProductAttribute extends ViewAttribute
{
    protected static string $resource = ProductAttributeResource::class;
}
