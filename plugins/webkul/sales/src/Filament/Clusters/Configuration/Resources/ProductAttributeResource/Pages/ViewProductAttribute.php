<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductAttributeResource\Pages\ViewProductAttribute as BaseViewProductAttribute;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductAttributeResource;

final class ViewProductAttribute extends BaseViewProductAttribute
{
    protected static string $resource = ProductAttributeResource::class;
}
