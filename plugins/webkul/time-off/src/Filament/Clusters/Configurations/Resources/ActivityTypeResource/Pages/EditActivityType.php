<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\EditActivityType as BaseEditActivityType;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\ActivityTypeResource;

final class EditActivityType extends BaseEditActivityType
{
    protected static string $resource = ActivityTypeResource::class;
}
