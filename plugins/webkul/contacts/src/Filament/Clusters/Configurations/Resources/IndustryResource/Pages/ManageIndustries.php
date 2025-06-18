<?php

declare(strict_types=1);

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources\IndustryResource\Pages;

use Webkul\Contact\Filament\Clusters\Configurations\Resources\IndustryResource;
use Webkul\Partner\Filament\Resources\IndustryResource\Pages\ManageIndustries as BaseManageIndustries;

final class ManageIndustries extends BaseManageIndustries
{
    protected static string $resource = IndustryResource::class;
}
