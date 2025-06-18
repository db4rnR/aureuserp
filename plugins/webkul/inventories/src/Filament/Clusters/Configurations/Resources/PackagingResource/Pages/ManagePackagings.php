<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\PackagingResource\Pages;

use Webkul\Inventory\Filament\Clusters\Configurations\Resources\PackagingResource;
use Webkul\Product\Filament\Resources\PackagingResource\Pages\ManagePackagings as BaseManagePackagings;

final class ManagePackagings extends BaseManagePackagings
{
    protected static string $resource = PackagingResource::class;
}
