<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\ScrapResource\Pages;

use Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource\Pages\ManageMoves as OperationManageMoves;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\ScrapResource;

final class ManageMoves extends OperationManageMoves
{
    protected static string $resource = ScrapResource::class;
}
