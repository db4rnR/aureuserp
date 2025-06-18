<?php

declare(strict_types=1);

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources\TagResource\Pages;

use Webkul\Contact\Filament\Clusters\Configurations\Resources\TagResource;
use Webkul\Partner\Filament\Resources\TagResource\Pages\ManageTags as BaseManageTags;

final class ManageTags extends BaseManageTags
{
    protected static string $resource = TagResource::class;
}
