<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource;
use Webkul\Inventory\Models\Route;

final class ListRoutes extends ListRecords
{
    protected static string $resource = RouteResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('inventories::filament/clusters/configurations/resources/route/pages/list-routes.tabs.all'))
                ->badge(Route::count()),
            'archived' => Tab::make(__('inventories::filament/clusters/configurations/resources/route/pages/list-routes.tabs.archived'))
                ->badge(Route::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('inventories::filament/clusters/configurations/resources/route/pages/list-routes.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateDataUsing(function (array $data) {
                    $user = Auth::user();

                    $data['creator_id'] = $user->id;

                    $data['company_id'] = $user->default_company_id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/route/pages/list-routes.header-actions.create.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/route/pages/list-routes.header-actions.create.notification.body')),
                ),
        ];
    }
}
