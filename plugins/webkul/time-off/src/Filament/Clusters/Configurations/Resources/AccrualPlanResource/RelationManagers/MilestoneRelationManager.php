<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Webkul\TimeOff\Traits\LeaveAccrualPlan;

final class MilestoneRelationManager extends RelationManager
{
    use LeaveAccrualPlan;

    protected static string $relationship = 'leaveAccrualLevels';
}
