<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Inventory\Filament\Clusters\Configurations;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\PackageTypeResource\Pages;
use Webkul\Inventory\Models\PackageType;
use Webkul\Inventory\Settings\OperationSettings;

class PackageTypeResource extends Resource
{
    protected static ?string $model = PackageType::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 10;

    protected static ?string $cluster = Configurations::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $isGloballySearchable = false;

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/configurations/resources/package-type.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/package-type.navigation.title');
    }

    public static function isDiscovered(): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return app(OperationSettings::class)->enable_packages;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.title'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),

                        Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.fieldsets.size.title'))
                            ->schema([
                                Forms\Components\TextInput::make('length')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.fieldsets.size.fields.length'))
                                    ->required()
                                    ->numeric()
                                    ->default(0.0000)
                                    ->minValue(0)
                                    ->maxValue(99999999999),
                                Forms\Components\TextInput::make('width')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.fieldsets.size.fields.width'))
                                    ->required()
                                    ->numeric()
                                    ->default(0.0000)
                                    ->minValue(0)
                                    ->maxValue(99999999999),
                                Forms\Components\TextInput::make('height')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.fieldsets.size.fields.height'))
                                    ->required()
                                    ->numeric()
                                    ->default(0.0000)
                                    ->minValue(0)
                                    ->maxValue(99999999999),
                            ])
                            ->columns(3),
                        Forms\Components\TextInput::make('base_weight')
                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.weight'))
                            ->required()
                            ->numeric()
                            ->default(0.0000)
                            ->minValue(0)
                            ->maxValue(99999999999),
                        Forms\Components\TextInput::make('max_weight')
                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.max-weight'))
                            ->required()
                            ->numeric()
                            ->default(0.0000)
                            ->minValue(0)
                            ->maxValue(99999999999),
                        Forms\Components\TextInput::make('barcode')
                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.barcode'))
                            ->maxLength(255),
                        Forms\Components\Select::make('company_id')
                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.form.sections.general.fields.company'))
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('height')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.height'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('width')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.width'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('length')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.length'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.barcode'))
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/package-type.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/package-type.table.actions.delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/package-type.table.bulk-actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/package-type.table.bulk-actions.delete.notification.body')),
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
                        Infolists\Components\Section::make(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.general.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.general.entries.name'))
                                    ->icon('heroicon-o-tag')
                                    ->weight(FontWeight::Bold)
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                                Infolists\Components\Group::make([
                                    Infolists\Components\Section::make(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.general.entries.fieldsets.size.title'))
                                        ->schema([
                                            Infolists\Components\Grid::make(3)
                                                ->schema([
                                                    Infolists\Components\TextEntry::make('length')
                                                        ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.general.entries.fieldsets.size.entries.length'))
                                                        ->icon('heroicon-o-arrows-right-left')
                                                        ->numeric()
                                                        ->suffix(' cm'),

                                                    Infolists\Components\TextEntry::make('width')
                                                        ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.general.entries.fieldsets.size.entries.width'))
                                                        ->icon('heroicon-o-arrows-up-down')
                                                        ->numeric()
                                                        ->suffix(' cm'),

                                                    Infolists\Components\TextEntry::make('height')
                                                        ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.general.entries.fieldsets.size.entries.height'))
                                                        ->icon('heroicon-o-arrows-up-down')
                                                        ->numeric()
                                                        ->suffix(' cm'),
                                                ]),
                                        ])
                                        ->icon('heroicon-o-cube'),
                                ]),

                                Infolists\Components\Grid::make(2)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('base_weight')
                                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.general.entries.weight'))
                                            ->icon('heroicon-o-scale')
                                            ->numeric()
                                            ->suffix(' kg'),

                                        Infolists\Components\TextEntry::make('max_weight')
                                            ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.general.entries.max-weight'))
                                            ->icon('heroicon-o-scale')
                                            ->numeric()
                                            ->suffix(' kg'),
                                    ]),

                                Infolists\Components\TextEntry::make('barcode')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.general.entries.barcode'))
                                    ->icon('heroicon-o-bars-4')
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('company.name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.general.entries.company'))
                                    ->icon('heroicon-o-building-office'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.record-information.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.record-information.entries.created-at'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar'),

                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.record-information.entries.created-by'))
                                    ->icon('heroicon-m-user'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label(__('inventories::filament/clusters/configurations/resources/package-type.infolist.sections.record-information.entries.last-updated'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar-days'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPackageTypes::route('/'),
            'create' => Pages\CreatePackageType::route('/create'),
            'view'   => Pages\ViewPackageType::route('/{record}'),
            'edit'   => Pages\EditPackageType::route('/{record}/edit'),
        ];
    }
}
