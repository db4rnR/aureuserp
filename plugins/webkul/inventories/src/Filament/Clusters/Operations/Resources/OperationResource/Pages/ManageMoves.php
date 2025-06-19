<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource\Pages;

use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Webkul\Inventory\Enums\LocationType;
use Webkul\Inventory\Enums\MoveState;
use Webkul\Inventory\Enums\ProductTracking;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource;
use Webkul\Inventory\Models\MoveLine;
use Webkul\Inventory\Settings\OperationSettings;
use Webkul\Inventory\Settings\TraceabilitySettings;
use Webkul\Inventory\Settings\WarehouseSettings;

class ManageMoves extends ManageRelatedRecords
{
    protected static string $resource = OperationResource::class;

    protected static string $relationship = 'moveLines';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('scheduled_at')->label(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.columns.date'))
                    ->sortable()
                    ->dateTime(),
                TextColumn::make('reference')->label(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.columns.reference'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lot.name')->label(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.columns.lot'))
                    ->sortable()
                    ->placeholder('—')
                    ->visible(fn (TraceabilitySettings $settings): bool => $settings->enable_lots_serial_numbers && $this->getOwnerRecord()->tracking !== ProductTracking::QTY),
                TextColumn::make('package.name')->label(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.columns.package'))
                    ->sortable()
                    ->placeholder('—')
                    ->visible(fn (OperationSettings $settings): bool => $settings->enable_packages),
                TextColumn::make('sourceLocation.full_name')->label(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.columns.source-location'))
                    ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations),
                TextColumn::make('destinationLocation.full_name')->label(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.columns.destination-location'))
                    ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations),
                TextColumn::make('uom_qty')->label(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.columns.quantity'))
                    ->sortable()
                    ->color(fn ($record): string => $record->destinationLocation->type === LocationType::INTERNAL ? 'success' : 'danger'),
                TextColumn::make('state')->label(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.columns.state'))
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('creator.name')->label(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.columns.done-by'))
                    ->sortable(),
            ])
            ->recordActions([
                DeleteAction::make()->hidden(fn (MoveLine $record): bool => $record->state === MoveState::DONE)
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/operation/pages/manage-moves.table.actions.delete.notification.body')),
                    ),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
