<?php

declare(strict_types=1);

namespace Webkul\Support\Filament\Resources\ActivityTypeResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Tabs\Tab;
use Webkul\Support\Filament\Resources\ActivityTypeResource;
use Webkul\Support\Models\ActivityType;

class ListActivityTypes extends ListRecords
{
    protected static string $resource = ActivityTypeResource::class;

    private static ?string $pluginName = 'employees';

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('support::filament/resources/activity-type/pages/list-activity-type.tabs.all'))
                ->badge(ActivityType::where('plugin', $this->getPluginName())->count()),
            'archived' => Tab::make(__('support::filament/resources/activity-type/pages/list-activity-type.tabs.archived'))
                ->badge(ActivityType::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->where('plugin', $this->getPluginName())->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label(__('support::filament/resources/activity-type/pages/list-activity-type.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    private function getPluginName(): ?string
    {
        return self::$pluginName;
    }
}
