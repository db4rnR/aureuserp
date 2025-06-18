<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityTypeResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityTypeResource;
use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\CreateActivityType as BaseCreateActivityType;

final class CreateActivityType extends BaseCreateActivityType
{
    protected static string $resource = ActivityTypeResource::class;
}
