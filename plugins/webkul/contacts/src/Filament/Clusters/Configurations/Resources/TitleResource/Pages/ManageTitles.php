<?php

declare(strict_types=1);

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources\TitleResource\Pages;

use Webkul\Contact\Filament\Clusters\Configurations\Resources\TitleResource;
use Webkul\Partner\Filament\Resources\TitleResource\Pages\ManageTitles as BaseManageTitles;

class ManageTitles extends BaseManageTitles
{
    protected static string $resource = TitleResource::class;
}
