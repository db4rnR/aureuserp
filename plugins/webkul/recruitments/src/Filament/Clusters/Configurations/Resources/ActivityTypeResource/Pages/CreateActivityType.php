<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityTypeResource;
use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\CreateActivityType as BaseCreateActivityType;

final class CreateActivityType extends BaseCreateActivityType
{
    protected static string $resource = ActivityTypeResource::class;

    protected static ?string $pluginName = 'recruitments';
}
