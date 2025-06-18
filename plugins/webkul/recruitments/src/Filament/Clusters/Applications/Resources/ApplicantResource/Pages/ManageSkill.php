<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\CandidateResource\Pages\ManageSkill as BaseManageSkill;

final class ManageSkill extends BaseManageSkill
{
    protected static string $resource = ApplicantResource::class;
}
