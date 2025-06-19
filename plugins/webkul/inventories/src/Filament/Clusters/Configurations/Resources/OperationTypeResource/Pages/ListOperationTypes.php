<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\OperationTypeResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\OperationTypeResource;
use Webkul\Inventory\Models\OperationType;

class ListOperationTypes extends ListRecords
{
    protected static string $resource = OperationTypeResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('inventories::filament/clusters/configurations/resources/operation-type/pages/list-operation-types.tabs.all'))
                ->badge(OperationType::count()),
            'archived' => Tab::make(__('inventories::filament/clusters/configurations/resources/operation-type/pages/list-operation-types.tabs.archived'))
                ->badge(OperationType::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label(__('inventories::filament/clusters/configurations/resources/operation-type/pages/list-operation-types.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateDataUsing(function (array $data) {
                    $user = Auth::user();

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->default_company_id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/operation-type/pages/list-operation-types.header-actions.create.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/operation-type/pages/list-operation-types.header-actions.create.notification.body')),
                ),
        ];
    }
}
