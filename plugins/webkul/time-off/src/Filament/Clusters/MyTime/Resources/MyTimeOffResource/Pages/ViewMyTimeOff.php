<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyTimeOffResource;

class ViewMyTimeOff extends ViewRecord
{
    protected static string $resource = MyTimeOffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make()->setResource(self::$resource),
            EditAction::make(),
            DeleteAction::make()->successNotification(
                    Notification::make()->success()
                        ->title(__('time-off::filament/clusters/my-time/resources/my-time-off/pages/view-time-off.header-actions.delete.notification.title'))
                        ->body(__('time-off::filament/clusters/my-time/resources/my-time-off/pages/view-time-off.header-actions.delete.notification.body'))
                ),
        ];
    }
}
