<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\OperationTypeResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\OperationTypeResource;

class EditOperationType extends EditRecord
{
    protected static string $resource = OperationTypeResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()->success()
            ->title(__('inventories::filament/clusters/configurations/resources/operation-type/pages/edit-operation-type.notification.title'))
            ->body(__('inventories::filament/clusters/configurations/resources/operation-type/pages/edit-operation-type.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->successNotification(
                    Notification::make()->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/operation-type/pages/edit-operation-type.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/operation-type/pages/edit-operation-type.header-actions.delete.notification.body')),
                ),
        ];
    }
}
