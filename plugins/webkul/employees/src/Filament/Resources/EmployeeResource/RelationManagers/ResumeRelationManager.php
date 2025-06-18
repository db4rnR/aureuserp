<?php

declare(strict_types=1);

namespace Webkul\Employee\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Webkul\Employee\Traits\Resources\Employee\EmployeeResumeRelation;

final class ResumeRelationManager extends RelationManager
{
    use EmployeeResumeRelation;

    protected static string $relationship = 'resumes';

    protected static ?string $title = 'Resumes';
}
