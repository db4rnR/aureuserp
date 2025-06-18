<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Webkul\Employee\Filament\Resources\DepartmentResource\Pages\CreateDepartment as BaseCreateDepartment;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;

final class CreateDepartment extends BaseCreateDepartment
{
    protected static string $resource = DepartmentResource::class;
}
