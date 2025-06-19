<?php

declare(strict_types=1);

namespace Webkul\Employee\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Webkul\Employee\Traits\Resources\Employee\EmployeeSkillRelation;

class SkillsRelationManager extends RelationManager
{
    use EmployeeSkillRelation;

    protected static string $relationship = 'skills';

    protected static ?string $title = 'Skills';
}
