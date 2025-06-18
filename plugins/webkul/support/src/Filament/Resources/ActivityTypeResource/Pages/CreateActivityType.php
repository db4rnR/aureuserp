<?php

declare(strict_types=1);

namespace Webkul\Support\Filament\Resources\ActivityTypeResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Webkul\Support\Filament\Resources\ActivityTypeResource;

final class CreateActivityType extends CreateRecord
{
    protected static string $resource = ActivityTypeResource::class;

    private static ?string $pluginName = 'support';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('support::filament/resources/activity-type/pages/create-activity-type.notification.title'))
            ->body(__('support::filament/resources/activity-type/pages/create-activity-type.notification.body'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['plugin'] = $this->getPluginName();

        return $data;
    }

    private function getPluginName(): ?string
    {
        return self::$pluginName;
    }
}
