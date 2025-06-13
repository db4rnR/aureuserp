<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums\AllowNewProduct;
use Webkul\Inventory\Filament\Clusters\Configurations;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource\Pages;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource\RelationManagers;
use Webkul\Inventory\Models\StorageCategory;
use Webkul\Inventory\Settings\WarehouseSettings;

class StorageCategoryResource extends Resource
{
    protected static ?string $model = StorageCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?int $navigationSort = 4;

    protected static ?string $cluster = Configurations::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $isGloballySearchable = false;

    public static function isDiscovered(): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return app(WarehouseSettings::class)->enable_locations;
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/configurations/resources/storage-category.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/storage-category.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/storage-category.form.sections.general.title'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('inventories::filament/clusters/configurations/resources/storage-category.form.sections.general.fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('max_weight')
                            ->label(__('inventories::filament/clusters/configurations/resources/storage-category.form.sections.general.fields.max-weight'))
                            ->numeric()
                            ->default(0.0000)
                            ->minValue(0)
                            ->maxValue(99999999),
                        Forms\Components\Select::make('allow_new_products')
                            ->label(__('inventories::filament/clusters/configurations/resources/storage-category.form.sections.general.fields.allow-new-products'))
                            ->options(AllowNewProduct::class)
                            ->required()
                            ->default(AllowNewProduct::MIXED),
                        Forms\Components\Select::make('company_id')
                            ->label(__('inventories::filament/clusters/configurations/resources/storage-category.form.sections.general.fields.company'))
                            ->relationship(name: 'company', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->default(Auth::user()->default_company_id),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('allow_new_products')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.allow-new-products'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_weight')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.max-weight'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.company'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('allow_new_products')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.groups.allow-new-products'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/storage-category.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/storage-category.table.actions.delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/storage-category.table.bulk-actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/storage-category.table.bulk-actions.delete.notification.body')),
                    ),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('inventories::filament/clusters/configurations/resources/storage-category.infolist.sections.general.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.infolist.sections.general.entries.name'))
                                    ->icon('heroicon-o-tag'), // Example icon for name
                                Infolists\Components\TextEntry::make('max_weight')
                                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.infolist.sections.general.entries.max-weight'))
                                    ->numeric()
                                    ->icon('heroicon-o-scale'), // Example icon for max weight
                                Infolists\Components\TextEntry::make('allow_new_products')
                                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.infolist.sections.general.entries.allow-new-products'))
                                    ->icon('heroicon-o-plus-circle'), // Example icon for allow new products
                                Infolists\Components\TextEntry::make('company.name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.infolist.sections.general.entries.company'))
                                    ->icon('heroicon-o-building-office'), // Example icon for company
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('inventories::filament/clusters/configurations/resources/storage-category.infolist.sections.record-information.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.infolist.sections.record-information.entries.created-at'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar'),

                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.infolist.sections.record-information.entries.created-by'))
                                    ->icon('heroicon-m-user'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category.infolist.sections.record-information.entries.last-updated'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar-days'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        $route = request()->route()?->getName() ?? session('current_route');

        if ($route && $route != 'livewire.update') {
            session(['current_route' => $route]);
        } else {
            $route = session('current_route');
        }

        if ($route === self::getRouteBaseName().'.index') {
            return SubNavigationPosition::Start;
        }

        return SubNavigationPosition::Top;
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewStorageCategory::class,
            Pages\EditStorageCategory::class,
            Pages\ManageCapacityByPackages::class,
            Pages\ManageCapacityByProducts::class,
            Pages\ManageLocations::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Capacity By Packages', [
                RelationManagers\CapacityByPackagesRelationManager::class,
            ])
                ->icon('heroicon-o-cube'),

            RelationGroup::make('Capacity By Products', [
                RelationManagers\CapacityByProductsRelationManager::class,
            ])
                ->icon('heroicon-o-clipboard-document-check'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListStorageCategories::route('/'),
            'create'     => Pages\CreateStorageCategory::route('/create'),
            'view'       => Pages\ViewStorageCategory::route('/{record}'),
            'edit'       => Pages\EditStorageCategory::route('/{record}/edit'),
            'packages'   => Pages\ManageCapacityByPackages::route('/{record}/packages'),
            'products'   => Pages\ManageCapacityByProducts::route('/{record}/products'),
            'locations'  => Pages\ManageLocations::route('/{record}/locations'),
        ];
    }
}
