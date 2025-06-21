<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\RuleResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RuleResource;
use Webkul\Inventory\Models\Rule;

class ListRules extends ListRecords
{
    protected static string $resource = RuleResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('inventories::filament/clusters/configurations/resources/rule/pages/list-rules.tabs.all'))
                ->badge(Rule::count()),
            'archived' => Tab::make(__('inventories::filament/clusters/configurations/resources/rule/pages/list-rules.tabs.archived'))
                ->badge(Rule::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label(__('inventories::filament/clusters/configurations/resources/rule/pages/list-rules.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateDataUsing(function (array $data) {
                    $user = Auth::user();

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->default_company_id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/rule/pages/list-rules.header-actions.create.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/rule/pages/list-rules.header-actions.create.notification.body')),
                ),
        ];
    }
}
