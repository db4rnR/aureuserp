<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\LeaveTypeResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\LeaveTypeResource;

final class ViewLeaveType extends ViewRecord
{
    protected static string $resource = LeaveTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('time-off::filament/clusters/configurations/resources/leave-type/pages/view-leave-type.header-actions.delete.notification.title'))
                        ->body(__('time-off::filament/clusters/configurations/resources/leave-type/pages/view-leave-type.header-actions.delete.notification.body'))
                ),
        ];
    }
}
