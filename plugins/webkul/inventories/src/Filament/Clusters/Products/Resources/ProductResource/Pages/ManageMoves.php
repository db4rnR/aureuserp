<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource;
use Webkul\Inventory\Settings\OperationSettings;
use Webkul\Inventory\Settings\TraceabilitySettings;
use Webkul\Inventory\Settings\WarehouseSettings;
use Webkul\TableViews\Filament\Components\PresetView;
use Webkul\TableViews\Filament\Concerns\HasTableViews;

class ManageMoves extends ManageRelatedRecords
{
    use HasTableViews;

    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'moveLines';

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/products/resources/product/pages/manage-moves.title');
    }

    public function getPresetTableViews(): array
    {
        return [
            'todo_moves' => PresetView::make(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.tabs.todo'))
                ->favorite()
                ->icon('heroicon-o-clipboard-document-list')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotIn('state', [Enums\MoveState::DRAFT, Enums\MoveState::DONE, Enums\MoveState::CANCELED])),
            'done_moves' => PresetView::make(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.tabs.done'))
                ->favorite()
                ->default()
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', Enums\MoveState::DONE)),
            'incoming_moves' => PresetView::make(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.tabs.incoming'))
                ->favorite()
                ->icon('heroicon-o-arrow-down-tray')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('operation.operationType', function (Builder $query) {
                        $query->where('type', Enums\OperationType::INCOMING);
                    });
                }),
            'outgoing_moves' => PresetView::make(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.tabs.outgoing'))
                ->favorite()
                ->icon('heroicon-o-arrow-up-tray')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('operation.operationType', function (Builder $query) {
                        $query->where('type', Enums\OperationType::OUTGOING);
                    });
                }),
            'internal_moves' => PresetView::make(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.tabs.internal'))
                ->favorite()
                ->icon('heroicon-o-arrows-right-left')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('operation.operationType', function (Builder $query) {
                        $query->where('type', Enums\OperationType::INTERNAL);
                    });
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.columns.product'))
                    ->sortable()
                    ->placeholder('—')
                    ->visible((bool) $this->getOwnerRecord()->is_configurable),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.columns.date'))
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('reference')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.columns.reference'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lot.name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.columns.lot'))
                    ->sortable()
                    ->placeholder('—')
                    ->visible(fn (TraceabilitySettings $settings) => $settings->enable_lots_serial_numbers && $this->getOwnerRecord()->tracking != Enums\ProductTracking::QTY),
                Tables\Columns\TextColumn::make('resultPackage.name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.columns.package'))
                    ->sortable()
                    ->placeholder('—')
                    ->visible(fn (OperationSettings $settings) => $settings->enable_packages),
                Tables\Columns\TextColumn::make('sourceLocation.full_name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.columns.source-location'))
                    ->visible(fn (WarehouseSettings $settings) => $settings->enable_locations),
                Tables\Columns\TextColumn::make('destinationLocation.full_name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.columns.destination-location'))
                    ->visible(fn (WarehouseSettings $settings) => $settings->enable_locations),
                Tables\Columns\TextColumn::make('uom_qty')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.columns.quantity'))
                    ->sortable()
                    ->color(fn ($record) => $record->destinationLocation->type == Enums\LocationType::INTERNAL ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('state')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.columns.state'))
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.columns.done-by'))
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/products/resources/product/pages/manage-moves.table.actions.delete.notification.body')),
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('state', Enums\MoveState::DONE);
            });
    }
}
