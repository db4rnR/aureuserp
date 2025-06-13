<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Operations;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource\Pages;
use Webkul\Inventory\Models\InternalTransfer;
use Webkul\Inventory\Settings\WarehouseSettings;

class InternalResource extends Resource
{
    protected static ?string $model = InternalTransfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = Operations::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function isDiscovered(): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return app(WarehouseSettings::class)->enable_locations;
    }

    public static function getModelLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/internal.navigation.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/internal.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/operations/resources/internal.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return OperationResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return OperationResource::table($table)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->hidden(fn (InternalTransfer $record): bool => $record->state == Enums\OperationState::DONE)
                        ->action(function (InternalTransfer $record) {
                            try {
                                $record->delete();
                            } catch (QueryException $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('inventories::filament/clusters/operations/resources/internal.table.actions.delete.notification.error.title'))
                                    ->body(__('inventories::filament/clusters/operations/resources/internal.table.actions.delete.notification.error.body'))
                                    ->send();
                            }
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/operations/resources/internal.table.actions.delete.notification.success.title'))
                                ->body(__('inventories::filament/clusters/operations/resources/internal.table.actions.delete.notification.success.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function (Collection $records) {
                        try {
                            $records->each(fn (Model $record) => $record->delete());
                        } catch (QueryException $e) {
                            Notification::make()
                                ->danger()
                                ->title(__('inventories::filament/clusters/operations/resources/internal.table.bulk-actions.delete.notification.error.title'))
                                ->body(__('inventories::filament/clusters/operations/resources/internal.table.bulk-actions.delete.notification.error.body'))
                                ->send();
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/internal.table.bulk-actions.delete.notification.success.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/internal.table.bulk-actions.delete.notification.success.body')),
                    ),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->whereHas('operationType', function (Builder $query) {
                    $query->where('type', Enums\OperationType::INTERNAL);
                });
            });
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return OperationResource::infolist($infolist);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewInternal::class,
            Pages\EditInternal::class,
            Pages\ManageMoves::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInternals::route('/'),
            'create' => Pages\CreateInternal::route('/create'),
            'view'   => Pages\ViewInternal::route('/{record}/view'),
            'edit'   => Pages\EditInternal::route('/{record}/edit'),
            'moves'  => Pages\ManageMoves::route('/{record}/moves'),
        ];
    }
}
