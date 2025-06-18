<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Products\Resources\ProductResource\Pages;

use Webkul\Invoice\Filament\Clusters\Vendors\Resources\ProductResource\Pages\ManageVariants as BaseManageVariants;
use Webkul\Sale\Filament\Clusters\Products\Resources\ProductResource;
use Webkul\Sale\Settings\ProductSettings;

final class ManageVariants extends BaseManageVariants
{
    protected static string $resource = ProductResource::class;

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function canAccess(array $parameters = []): bool
    {
        $canAccess = parent::canAccess($parameters);

        if (! $canAccess) {
            return false;
        }

        return app(ProductSettings::class)->enable_variants;
    }
}
