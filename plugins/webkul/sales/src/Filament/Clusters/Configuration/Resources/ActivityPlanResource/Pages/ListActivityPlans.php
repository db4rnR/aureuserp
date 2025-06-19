<?php

declare(strict_types=1);

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityPlanResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\Auth;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ActivityPlanResource;
use Webkul\Support\Models\ActivityPlan;

class ListActivityPlans extends ListRecords
{
    protected static string $resource = ActivityPlanResource::class;

    private static ?string $pluginName = 'sales';

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('sales::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.tabs.all'))
                ->badge(ActivityPlan::where('plugin', $this->getPluginName())->count()),
            'archived' => Tab::make(__('sales::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.tabs.archived'))
                ->badge(ActivityPlan::where('plugin', $this->getPluginName())->onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->where('plugin', $this->getPluginName())->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->icon('heroicon-o-plus-circle')
                ->label(__('sales::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.header-actions.create.label'))
                ->mutateDataUsing(function (array $data) {
                    $user = Auth::user();

                    $data['plugin'] = $this->getPluginName();

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->defaultCompany?->id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()->success()
                        ->title(__('sales::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.header-actions.create.notification.title'))
                        ->body(__('sales::filament/clusters/configurations/resources/activity-plan/pages/list-activity-plan.header-actions.create.notification.body')),
                ),
        ];
    }

    private function getPluginName(): ?string
    {
        return self::$pluginName;
    }
}
