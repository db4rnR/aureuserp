<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityTypeResource\Pages;

use Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityTypeResource;
use Webkul\Support\Filament\Resources\ActivityTypeResource\Pages\ListActivityTypes as BaseListActivityTypes;

final class ListActivityTypes extends BaseListActivityTypes
{
    protected static string $resource = ActivityTypeResource::class;
}
