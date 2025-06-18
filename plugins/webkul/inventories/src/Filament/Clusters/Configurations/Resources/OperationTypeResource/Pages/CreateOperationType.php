<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\OperationTypeResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums\ReservationMethod;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\OperationTypeResource;

final class CreateOperationType extends CreateRecord
{
    protected static string $resource = OperationTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/configurations/resources/operation-type/pages/create-operation-type.notification.title'))
            ->body(__('inventories::filament/clusters/configurations/resources/operation-type/pages/create-operation-type.notification.body'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['reservation_method'] = ReservationMethod::AT_CONFIRM;

        $data['creator_id'] = Auth::id();

        return $data;
    }
}
