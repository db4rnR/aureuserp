<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource\Pages;

use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource;

class ManageRoutes extends ManageRelatedRecords
{
    protected static string $resource = WarehouseResource::class;

    protected static string $relationship = 'routes';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/warehouse/pages/manage-routes.title');
    }

    public function form(Form $form): Form
    {
        return RouteResource::form($form);
    }

    public function table(Table $table): Table
    {
        return RouteResource::table($table)
            ->headerActions([
                CreateAction::make()->label(__('inventories::filament/clusters/configurations/resources/warehouse/pages/manage-routes.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->fillForm(fn (array $arguments): array => [
                        'warehouse_selectable' => true,
                        'warehouses' => [$this->getOwnerRecord()->id],
                    ])
                    ->mutateDataUsing(function (array $data): array {
                        $data['warehouse_selectable'] = true;

                        $data['creator_id'] = Auth::id();

                        $data['company_id'] ??= Auth::user()->default_company_id;

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/warehouse/pages/manage-routes.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/warehouse/pages/manage-routes.table.header-actions.create.notification.body')),
                    ),
            ]);
    }
}
