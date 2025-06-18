<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\DeliveryResource\Pages;

use Webkul\Inventory\Filament\Clusters\Operations\Resources\DeliveryResource;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource\Pages\ManageMoves as OperationManageMoves;

final class ManageMoves extends OperationManageMoves
{
    protected static string $resource = DeliveryResource::class;
}
