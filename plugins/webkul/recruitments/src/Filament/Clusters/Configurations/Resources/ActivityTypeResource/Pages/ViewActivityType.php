<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource;
use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\ViewActivityType as BaseViewActivityType;

final class ViewActivityType extends BaseViewActivityType
{
    protected static string $resource = ActivityTypeResource::class;
}
