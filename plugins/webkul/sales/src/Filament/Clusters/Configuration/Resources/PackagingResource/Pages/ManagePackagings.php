<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\PackagingResource\Pages;

use Webkul\Product\Filament\Resources\PackagingResource\Pages\ManagePackagings as BaseManagePackagings;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\PackagingResource;

class ManagePackagings extends BaseManagePackagings
{
    protected static string $resource = PackagingResource::class;
}
