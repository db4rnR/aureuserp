<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Webkul\Inventory\Enums\OperationState;
use Webkul\Inventory\Enums\OperationType;
use Webkul\Inventory\Filament\Clusters\Operations;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\DropshipResource\Pages\CreateDropship;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\DropshipResource\Pages\EditDropship;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\DropshipResource\Pages\ListDropships;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\DropshipResource\Pages\ManageMoves;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\DropshipResource\Pages\ViewDropship;
use Webkul\Inventory\Models\Dropship;
use Webkul\Inventory\Settings\LogisticSettings;

final class DropshipResource extends Resource
{
    protected static ?string $model = Dropship::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $cluster = Operations::class;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function isDiscovered(): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return app(LogisticSettings::class)->enable_dropshipping;
    }

    public static function getModelLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/dropship.navigation.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/dropship.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/operations/resources/dropship.navigation.group');
    }

    public static function form(Schema $schema): Schema
    {
        return OperationResource::form($schema);
    }

    public static function table(Table $table): Table
    {
        return OperationResource::table($table)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->hidden(fn (Dropship $record): bool => $record->state === OperationState::DONE)
                        ->action(function (Dropship $record): void {
                            try {
                                $record->delete();
                            } catch (QueryException) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('inventories::filament/clusters/operations/resources/dropship.table.actions.delete.notification.error.title'))
                                    ->body(__('inventories::filament/clusters/operations/resources/dropship.table.actions.delete.notification.error.body'))
                                    ->send();
                            }
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/operations/resources/dropship.table.actions.delete.notification.success.title'))
                                ->body(__('inventories::filament/clusters/operations/resources/dropship.table.actions.delete.notification.success.body')),
                        ),
                ]),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->action(function (Collection $records): void {
                        try {
                            $records->each(fn (Model $record) => $record->delete());
                        } catch (QueryException) {
                            Notification::make()
                                ->danger()
                                ->title(__('inventories::filament/clusters/operations/resources/dropship.table.bulk-actions.delete.notification.error.title'))
                                ->body(__('inventories::filament/clusters/operations/resources/dropship.table.bulk-actions.delete.notification.error.body'))
                                ->send();
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/dropship.table.bulk-actions.delete.notification.success.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/dropship.table.bulk-actions.delete.notification.success.body')),
                    ),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('operationType', function (Builder $query): void {
                $query->where('type', OperationType::DROPSHIP);
            }));
    }

    public static function infolist(Schema $schema): Schema
    {
        return OperationResource::infolist($schema);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewDropship::class,
            EditDropship::class,
            ManageMoves::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDropships::route('/'),
            'create' => CreateDropship::route('/create'),
            'edit' => EditDropship::route('/{record}/edit'),
            'view' => ViewDropship::route('/{record}/view'),
            'moves' => ManageMoves::route('/{record}/moves'),
        ];
    }
}
