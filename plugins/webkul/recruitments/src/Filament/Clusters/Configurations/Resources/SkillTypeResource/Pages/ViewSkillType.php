<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages;

use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages\ViewSkillType as ViewSkillTypeBase;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\SkillTypeResource;

final class ViewSkillType extends ViewSkillTypeBase
{
    protected static string $resource = SkillTypeResource::class;
}
