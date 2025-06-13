<?php

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources\ProductResource\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Settings\OperationSettings;
use Webkul\Inventory\Settings\TraceabilitySettings;
use Webkul\Inventory\Settings\WarehouseSettings;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\ProductResource;

class ManageMoves extends ManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'moveLines';

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.columns.date'))
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('reference')
                    ->label(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.columns.reference'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lot.name')
                    ->label(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.columns.lot'))
                    ->sortable()
                    ->visible(fn (TraceabilitySettings $traceabilitySettings) => $traceabilitySettings->enable_lots_serial_numbers && $this->getOwnerRecord()->tracking != Enums\ProductTracking::QTY),
                Tables\Columns\TextColumn::make('resultPackage.name')
                    ->label(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.columns.package'))
                    ->sortable()
                    ->visible(fn (OperationSettings $operationSettings) => $operationSettings->enable_packages),
                Tables\Columns\TextColumn::make('sourceLocation.full_name')
                    ->label(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.columns.source-location'))
                    ->visible(fn (WarehouseSettings $warehouseSettings) => $warehouseSettings->enable_locations),
                Tables\Columns\TextColumn::make('destinationLocation.full_name')
                    ->label(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.columns.destination-location'))
                    ->visible(fn (WarehouseSettings $warehouseSettings) => $warehouseSettings->enable_locations),
                Tables\Columns\TextColumn::make('qty')
                    ->label(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.columns.quantity'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->label(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.columns.state'))
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.columns.done-by'))
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.actions.delete.notification.title'))
                            ->body(__('invoices::filament/clusters/vendors/resources/product/pages/manage-moves.table.actions.delete.notification.body')),
                    ),
            ]);
    }
}
