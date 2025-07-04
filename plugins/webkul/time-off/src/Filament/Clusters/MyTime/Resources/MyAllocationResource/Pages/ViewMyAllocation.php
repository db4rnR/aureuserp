<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\TimeOff\Filament\Clusters\MyTime\Resources\MyAllocationResource;

class ViewMyAllocation extends ViewRecord
{
    protected static string $resource = MyAllocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make()->setResource(self::$resource),
            EditAction::make(),
            DeleteAction::make()->successNotification(
                    Notification::make()->success()
                        ->title(__('time-off::filament/clusters/my-time/resources/my-allocation/pages/view-allocation.header-actions.delete.notification.title'))
                        ->body(__('time-off::filament/clusters/my-time/resources/my-allocation/pages/view-allocation.header-actions.delete.notification.body'))
                ),
        ];
    }
}
