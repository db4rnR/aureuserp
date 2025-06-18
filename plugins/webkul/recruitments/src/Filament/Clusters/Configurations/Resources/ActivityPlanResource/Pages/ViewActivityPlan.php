<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages\ViewActivityPlan as BaseViewActivityPlan;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\ActivityPlanResource;

final class ViewActivityPlan extends BaseViewActivityPlan
{
    protected static string $resource = ActivityPlanResource::class;
}
