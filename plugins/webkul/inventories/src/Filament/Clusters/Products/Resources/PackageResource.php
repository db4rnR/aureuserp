<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\PackageTypeResource;
use Webkul\Inventory\Filament\Clusters\Products;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource\Pages;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource\RelationManagers;
use Webkul\Inventory\Models\Package;
use Webkul\Inventory\Settings\OperationSettings;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $cluster = Products::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function isDiscovered(): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return app(OperationSettings::class)->enable_packages;
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/products/resources/package.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('inventories::filament/clusters/products/resources/package.form.sections.general.title'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('inventories::filament/clusters/products/resources/package.form.sections.general.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->placeholder(__('inventories::filament/clusters/products/resources/package.form.sections.general.fields.name-placeholder'))
                            ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('package_type_id')
                                    ->label(__('inventories::filament/clusters/products/resources/package.form.sections.general.fields.package-type'))
                                    ->relationship('packageType', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(fn (Form $form): Form => PackageTypeResource::form($form)),
                                Forms\Components\DatePicker::make('pack_date')
                                    ->label(__('inventories::filament/clusters/products/resources/package.form.sections.general.fields.pack-date'))
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->default(today()),
                                Forms\Components\Select::make('location_id')
                                    ->label(__('inventories::filament/clusters/products/resources/package.form.sections.general.fields.location'))
                                    ->relationship('location', 'full_name')
                                    ->searchable()
                                    ->preload(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('packageType.name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.columns.package-type'))
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.full_name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.columns.location'))
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.columns.company'))
                    ->sortable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('packageType.name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.groups.package-type')),
                Tables\Grouping\Group::make('location.full_name')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.groups.location')),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.groups.created-at'))
                    ->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('package_type_id')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.filters.package-type'))
                    ->relationship('packageType', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('location_id')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.filters.location'))
                    ->relationship('location', 'full_name')
                    ->searchable()
                    ->multiple()
                    ->preload(),
                Tables\Filters\SelectFilter::make('creator_id')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.filters.creator'))
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('company_id')
                    ->label(__('inventories::filament/clusters/products/resources/package.table.filters.company'))
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->action(function (Package $record) {
                            try {
                                $record->delete();
                            } catch (QueryException $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('inventories::filament/clusters/products/resources/package.table.actions.delete.notification.error.title'))
                                    ->body(__('inventories::filament/clusters/products/resources/package.table.actions.delete.notification.error.body'))
                                    ->send();
                            }
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/products/resources/package.table.actions.delete.notification.success.title'))
                                ->body(__('inventories::filament/clusters/products/resources/package.table.actions.delete.notification.success.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('print-without-content')
                        ->label(__('inventories::filament/clusters/products/resources/package.table.bulk-actions.print-without-content.label'))
                        ->icon('heroicon-o-printer')
                        ->action(function ($records) {
                            $pdf = PDF::loadView('inventories::filament.clusters.products.packages.actions.print-without-content', [
                                'records' => $records,
                            ]);

                            $pdf->setPaper('a4', 'portrait');

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'Package-Barcode.pdf');
                        }),
                    Tables\Actions\BulkAction::make('print-with-content')
                        ->label(__('inventories::filament/clusters/products/resources/package.table.bulk-actions.print-with-content.label'))
                        ->icon('heroicon-o-printer')
                        ->action(function ($records) {
                            $pdf = PDF::loadView('inventories::filament.clusters.products.packages.actions.print-with-content', [
                                'records' => $records,
                            ]);

                            $pdf->setPaper('a4', 'portrait');

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'Package-Barcode.pdf');
                        }),
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            try {
                                $records->each(fn (Model $record) => $record->delete());
                            } catch (QueryException $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('inventories::filament/clusters/products/resources/package.table.bulk-actions.delete.notification.error.title'))
                                    ->body(__('inventories::filament/clusters/products/resources/package.table.bulk-actions.delete.notification.error.body'))
                                    ->send();
                            }
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/products/resources/package.table.bulk-actions.delete.notification.success.title'))
                                ->body(__('inventories::filament/clusters/products/resources/package.table.bulk-actions.delete.notification.success.body')),
                        ),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('inventories::filament/clusters/products/resources/package.infolist.sections.general.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label(__('inventories::filament/clusters/products/resources/package.infolist.sections.general.entries.name'))
                                    ->icon('heroicon-o-cube')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(FontWeight::Bold),

                                Infolists\Components\Grid::make(2)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('packageType.name')
                                            ->label(__('inventories::filament/clusters/products/resources/package.infolist.sections.general.entries.package-type'))
                                            ->icon('heroicon-o-rectangle-stack')
                                            ->placeholder('—'),

                                        Infolists\Components\TextEntry::make('pack_date')
                                            ->label(__('inventories::filament/clusters/products/resources/package.infolist.sections.general.entries.pack-date'))
                                            ->icon('heroicon-o-calendar')
                                            ->date(),
                                    ]),

                                Infolists\Components\Grid::make(2)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('location.full_name')
                                            ->label(__('inventories::filament/clusters/products/resources/package.infolist.sections.general.entries.location'))
                                            ->icon('heroicon-o-map-pin')
                                            ->placeholder('—'),

                                        Infolists\Components\TextEntry::make('company.name')
                                            ->label(__('inventories::filament/clusters/products/resources/package.infolist.sections.general.entries.company'))
                                            ->icon('heroicon-o-building-office'),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('inventories::filament/clusters/products/resources/package.infolist.sections.record-information.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('inventories::filament/clusters/products/resources/package.infolist.sections.record-information.entries.created-at'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar'),

                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label(__('inventories::filament/clusters/products/resources/package.infolist.sections.record-information.entries.created-by'))
                                    ->icon('heroicon-m-user'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label(__('inventories::filament/clusters/products/resources/package.infolist.sections.record-information.entries.last-updated'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar-days'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewPackage::class,
            Pages\EditPackage::class,
            Pages\ManageProducts::class,
            Pages\ManageOperations::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'      => Pages\ListPackages::route('/'),
            'create'     => Pages\CreatePackage::route('/create'),
            'edit'       => Pages\EditPackage::route('/{record}/edit'),
            'view'       => Pages\ViewPackage::route('/{record}/view'),
            'products'   => Pages\ManageProducts::route('/{record}/products'),
            'operations' => Pages\ManageOperations::route('/{record}/operations'),
        ];
    }
}
