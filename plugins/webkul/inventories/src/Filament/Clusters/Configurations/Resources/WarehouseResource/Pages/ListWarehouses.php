<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource;
use Webkul\Inventory\Models\Warehouse;
use Webkul\Inventory\Settings\WarehouseSettings;

final class ListWarehouses extends ListRecords
{
    protected static string $resource = WarehouseResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('inventories::filament/clusters/configurations/resources/warehouse/pages/list-warehouses.tabs.all'))
                ->badge(Warehouse::count()),
            'archived' => Tab::make(__('inventories::filament/clusters/configurations/resources/warehouse/pages/list-warehouses.tabs.archived'))
                ->badge(Warehouse::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('inventories::filament/clusters/configurations/resources/warehouse/pages/list-warehouses.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations)
                ->mutateDataUsing(function (array $data) {
                    $user = Auth::user();

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->defaultCompany?->id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/warehouse/pages/list-warehouses.header-actions.create.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/warehouse/pages/list-warehouses.header-actions.create.notification.body')),
                ),
        ];
    }
}
