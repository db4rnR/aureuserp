<?php

declare(strict_types=1);

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages;

use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductCategoryResource;
use Webkul\Product\Filament\Resources\CategoryResource\Pages\EditCategory;

class EditProductCategory extends EditCategory
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [

            ChatterActions\ChatterAction::make()->setResource(self::$resource),
            ...parent::getHeaderActions(),
        ];
    }
}
