<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources\ProductResource\Pages;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\ProductResource;
use Webkul\Product\Filament\Resources\ProductResource\Pages\ManageAttributes as BaseManageAttributes;

final class ManageAttributes extends BaseManageAttributes
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'attributes';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }
}
