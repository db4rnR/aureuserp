<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityPlanResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityPlanResource;

final class ViewActivityPlan extends ViewRecord
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('sales::filament/clusters/configurations/resources/activity-plan/pages/view-activity-plan.header-actions.delete.notification.title'))
                        ->body(__('sales::filament/clusters/configurations/resources/activity-plan/pages/view-activity-plan.header-actions.delete.notification.body')),
                ),
        ];
    }
}
