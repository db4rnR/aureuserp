<?php

declare(strict_types=1);

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource;
use Webkul\Support\Models\ActivityPlan;

class ListActivityPlans extends ListRecords
{
    protected static string $resource = ActivityPlanResource::class;

    private static ?string $pluginName = 'employees';

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('employees::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.tabs.all'))
                ->badge(ActivityPlan::where('plugin', $this->getPluginName())->count()),
            'archived' => Tab::make(__('employees::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.tabs.archived'))
                ->badge(ActivityPlan::where('plugin', $this->getPluginName())->onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->where('plugin', $this->getPluginName())->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->icon('heroicon-o-plus-circle')
                ->label(__('employees::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.header-actions.create.label'))
                ->mutateDataUsing(function ($data) {
                    $user = Auth::user();

                    $data['plugin'] = $this->getPluginName();

                    $data['creator_id'] = $user->id;

                    $data['company_id'] ??= $user->defaultCompany?->id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()->success()
                        ->title(__('employees::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.header-actions.create.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.header-actions.create.notification.body')),
                ),
        ];
    }

    private function getPluginName(): ?string
    {
        return self::$pluginName;
    }
}
