<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityPlanResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityPlanResource;

class ViewActivityPlan extends ViewRecord
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('sales::filament/clusters/configurations/resources/activity-plan/pages/view-activity-plan.header-actions.delete.notification.title'))
                        ->body(__('sales::filament/clusters/configurations/resources/activity-plan/pages/view-activity-plan.header-actions.delete.notification.body')),
                ),
        ];
    }
}
