<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Webkul\Employee\Filament\Resources\DepartmentResource\Pages\EditDepartment as BaseEditDepartment;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;

final class EditDepartment extends BaseEditDepartment
{
    protected static string $resource = DepartmentResource::class;
}
