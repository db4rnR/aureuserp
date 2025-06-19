<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource;

class ViewWarehouse extends ViewRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->successNotification(
                    Notification::make()->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/warehouse/pages/view-warehouse.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/warehouse/pages/view-warehouse.header-actions.delete.notification.body')),
                ),
        ];
    }
}
