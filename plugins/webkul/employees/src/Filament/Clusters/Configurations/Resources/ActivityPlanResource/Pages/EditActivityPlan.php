<?php

declare(strict_types=1);

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource;

class EditActivityPlan extends EditRecord
{
    protected static string $resource = ActivityPlanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()->success()
            ->title(__('employees::filament/clusters/configurations/resources/activity-plan/pages/edit-activity-plan.notification.title'))
            ->body(__('employees::filament/clusters/configurations/resources/activity-plan/pages/edit-activity-plan.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->successNotification(
                    Notification::make()->success()
                        ->title(__('employees::filament/clusters/configurations/resources/activity-plan/pages/edit-activity-plan.header-actions.delete.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/activity-plan/pages/edit-activity-plan.header-actions.delete.notification.body')),
                ),
        ];
    }
}
