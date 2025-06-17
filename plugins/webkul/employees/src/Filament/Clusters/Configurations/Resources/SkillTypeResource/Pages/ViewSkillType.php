<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource;

class ViewSkillType extends ViewRecord
{
    protected static string $resource = SkillTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('employees::filament/clusters/configurations/resources/skill-type/pages/view-skill-type.header-actions.delete.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/skill-type/pages/view-skill-type.header-actions.delete.notification.body')),
                ),
        ];
    }
}
