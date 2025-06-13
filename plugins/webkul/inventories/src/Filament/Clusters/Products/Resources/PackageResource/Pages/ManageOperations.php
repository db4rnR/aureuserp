<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\OperationResource;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource;
use Webkul\Inventory\Models\Operation;
use Webkul\TableViews\Filament\Concerns\HasTableViews;

class ManageOperations extends ManageRelatedRecords
{
    use HasTableViews;

    protected static string $resource = PackageResource::class;

    protected static string $relationship = 'operations';

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/products/resources/package/pages/manage-operations.title');
    }

    public function getPresetTableViews(): array
    {
        return OperationResource::getPresetTableViews();
    }

    public function table(Table $table): Table
    {
        return OperationResource::table($table)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->url(fn ($record): string => OperationResource::getUrl('view', ['record' => $record])),
                    Tables\Actions\EditAction::make()
                        ->url(fn ($record): string => OperationResource::getUrl('edit', ['record' => $record])),
                    Tables\Actions\DeleteAction::make()
                        ->hidden(fn (Operation $record): bool => $record->state == Enums\OperationState::DONE)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/operations/resources/receipt.table.actions.delete.notification.title'))
                                ->body(__('inventories::filament/clusters/operations/resources/receipt.table.actions.delete.notification.body')),
                        ),
                ]),
            ]);
    }
}
