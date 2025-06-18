<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource\Pages;

use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource\Pages\ListTimeOffs;
use Webkul\TimeOff\Filament\Clusters\Reporting\Resources\ByEmployeeResource;

final class ListByEmployees extends ListTimeOffs
{
    protected static string $resource = ByEmployeeResource::class;
}
