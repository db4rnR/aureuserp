<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource;

final class ViewTimeOff extends ViewRecord
{
    protected static string $resource = TimeOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make()
                ->setResource(self::$resource),
            EditAction::make(),
            DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('time-off::filament/clusters/management/resources/time-off/pages/view-time-off.header-actions.delete.notification.title'))
                        ->body(__('time-off::filament/clusters/management/resources/time-off/pages/view-time-off.header-actions.delete.notification.body'))
                ),
        ];
    }
}
