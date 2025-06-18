<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\ViewActivityType as BaseViewActivityType;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\ActivityTypeResource;

final class ViewActivityType extends BaseViewActivityType
{
    protected static string $resource = ActivityTypeResource::class;
}
