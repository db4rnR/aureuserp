<?php

declare(strict_types=1);

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource;
use Webkul\Employee\Models\SkillType;

final class ListSkillTypes extends ListRecords
{
    protected static string $resource = SkillTypeResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('employees::filament/clusters/configurations/resources/skill-type/pages/list-skill-type.tabs.all'))
                ->badge(SkillType::count()),
            'archived' => Tab::make(__('employees::filament/clusters/configurations/resources/skill-type/pages/list-skill-type.tabs.archived'))
                ->badge(SkillType::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus-circle')
                ->label(__('employees::filament/clusters/configurations/resources/skill-type/pages/list-skill-type.header-actions.create.label'))
                ->createAnother(false)
                ->after(fn ($record) => redirect(
                    self::$resource::getUrl('edit', ['record' => $record]),
                ))
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('employees::filament/clusters/configurations/resources/skill-type/pages/list-skill-type.header-actions.create.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/skill-type/pages/list-skill-type.header-actions.create.notification.body')),
                ),
        ];
    }
}
