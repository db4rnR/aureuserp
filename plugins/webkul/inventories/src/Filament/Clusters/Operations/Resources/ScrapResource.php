<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Operations;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\ScrapResource\Pages;
use Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource;
use Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource;
use Webkul\Inventory\Models\Location;
use Webkul\Inventory\Models\Product;
use Webkul\Inventory\Models\Scrap;
use Webkul\Inventory\Settings\OperationSettings;
use Webkul\Inventory\Settings\ProductSettings;
use Webkul\Inventory\Settings\TraceabilitySettings;
use Webkul\Inventory\Settings\WarehouseSettings;
use Webkul\Partner\Filament\Resources\PartnerResource;
use Webkul\Product\Enums\ProductType;

class ScrapResource extends Resource
{
    protected static ?string $model = Scrap::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';

    protected static ?int $navigationSort = 5;

    protected static ?string $cluster = Operations::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/scrap.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/operations/resources/scrap.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ProgressStepper::make('state')
                    ->hiddenLabel()
                    ->inline()
                    ->options(Enums\ScrapState::options())
                    ->default(Enums\ScrapState::DRAFT)
                    ->disabled(),
                Forms\Components\Section::make(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.title'))
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.product'))
                                            ->relationship(name: 'product', titleAttribute: 'name')
                                            ->relationship(
                                                'product',
                                                'name',
                                                fn ($query) => $query->where('type', ProductType::GOODS)->whereNull('is_configurable'),
                                            )
                                            ->getOptionLabelFromRecordUsing(function ($record): string {
                                                return $record->name.($record->trashed() ? ' (Deleted)' : '');
                                            })
                                            ->disableOptionWhen(function ($label) {
                                                return str_contains($label, ' (Deleted)');
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                                $set('lot_id', null);

                                                if ($product = Product::find($get('product_id'))) {
                                                    $set('uom_id', $product->uom_id);
                                                }
                                            })
                                            ->createOptionForm(fn (Form $form): Form => ProductResource::form($form))
                                            ->createOptionAction(fn ($action) => $action->modalWidth('6xl'))
                                            ->disabled(fn ($record): bool => $record?->state == Enums\ScrapState::DONE),
                                        Forms\Components\TextInput::make('qty')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.quantity'))
                                            ->required()
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(99999999999)
                                            ->default(0)
                                            ->disabled(fn ($record): bool => $record?->state == Enums\ScrapState::DONE),
                                        Forms\Components\Select::make('uom_id')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.unit'))
                                            ->relationship(
                                                'uom',
                                                'name',
                                                fn ($query) => $query->where('category_id', 1),
                                            )
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->visible(fn (ProductSettings $settings) => $settings->enable_uom)
                                            ->disabled(fn ($record): bool => $record?->state == Enums\ScrapState::DONE),
                                        Forms\Components\Select::make('lot_id')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.lot'))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->relationship(
                                                name: 'lot',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn (Builder $query, Forms\Get $get) => $query->where('product_id', $get('product_id')),
                                            )
                                            ->disabled(fn ($record): bool => $record?->state == Enums\ScrapState::DONE)
                                            ->visible(function (TraceabilitySettings $settings, Forms\Get $get): bool {
                                                if (! $settings->enable_lots_serial_numbers) {
                                                    return false;
                                                }

                                                $product = Product::find($get('product_id'));

                                                if (! $product) {
                                                    return false;
                                                }

                                                return $product->tracking === Enums\ProductTracking::LOT;
                                            })
                                            ->createOptionForm(fn (Form $form): Form => LotResource::form($form))
                                            ->createOptionAction(function (Action $action, Forms\Get $get) {
                                                $action
                                                    ->mutateFormDataUsing(function (array $data) use ($get): array {
                                                        $data['product_id'] = $get('product_id');

                                                        return $data;
                                                    });
                                            }),
                                        Forms\Components\Select::make('tags')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.tags'))
                                            ->relationship(name: 'tags', titleAttribute: 'name')
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.name'))
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique('inventories_tags'),
                                            ]),
                                    ]),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('package_id')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.package'))
                                            ->relationship('package', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm(fn (Form $form): Form => PackageResource::form($form))
                                            ->visible(fn (OperationSettings $settings) => $settings->enable_packages)
                                            ->disabled(fn ($record): bool => $record?->state == Enums\ScrapState::DONE),
                                        Forms\Components\Select::make('partner_id')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.owner'))
                                            ->relationship('partner', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm(fn (Form $form): Form => PartnerResource::form($form))
                                            ->disabled(fn ($record): bool => $record?->state == Enums\ScrapState::DONE),
                                        Forms\Components\Select::make('source_location_id')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.source-location'))
                                            ->relationship('sourceLocation', 'full_name')
                                            ->relationship(
                                                'sourceLocation',
                                                'full_name',
                                                fn ($query) => $query->where('type', Enums\LocationType::INTERNAL)->where('is_scrap', false),
                                            )
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->default(function () {
                                                $scrapLocation = Location::where('type', Enums\LocationType::INTERNAL)
                                                    ->where('is_scrap', false)
                                                    ->first();

                                                return $scrapLocation?->id;
                                            })
                                            ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations)
                                            ->disabled(fn ($record): bool => $record?->state == Enums\ScrapState::DONE),
                                        Forms\Components\Select::make('destination_location_id')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.destination-location'))
                                            ->relationship('destinationLocation', 'full_name')
                                            ->relationship(
                                                'destinationLocation',
                                                'full_name',
                                                fn ($query) => $query->where('is_scrap', true),
                                            )
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->default(function () {
                                                $scrapLocation = Location::where('is_scrap', true)
                                                    ->first();

                                                return $scrapLocation?->id;
                                            })
                                            ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations)
                                            ->disabled(fn ($record): bool => $record?->state == Enums\ScrapState::DONE),
                                        Forms\Components\TextInput::make('origin')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.source-document'))
                                            ->maxLength(255),
                                        Forms\Components\Select::make('company_id')
                                            ->label(__('inventories::filament/clusters/operations/resources/scrap.form.sections.general.fields.company'))
                                            ->relationship('company', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->default(Auth::user()->default_company_id)
                                            ->disabled(fn ($record): bool => $record?->state == Enums\ScrapState::DONE),
                                    ]),
                            ])
                            ->columns(2),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('closed_at')
                    ->label(__('inventories::filament/clusters/operations/resources/scrap.table.columns.date'))
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/operations/resources/scrap.table.columns.reference'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('inventories::filament/clusters/operations/resources/scrap.table.columns.product'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lot.name')
                    ->label(__('inventories::filament/clusters/operations/resources/scrap.table.columns.lot'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('—')
                    ->visible(fn (TraceabilitySettings $settings) => $settings->enable_lots_serial_numbers),
                Tables\Columns\TextColumn::make('package.name')
                    ->label(__('inventories::filament/clusters/operations/resources/scrap.table.columns.package'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('—')
                    ->visible(fn (OperationSettings $settings) => $settings->enable_packages),
                Tables\Columns\TextColumn::make('sourceLocation.full_name')
                    ->label(__('inventories::filament/clusters/operations/resources/scrap.table.columns.source-location'))
                    ->sortable()
                    ->visible(fn (WarehouseSettings $settings) => $settings->enable_locations),
                Tables\Columns\TextColumn::make('destinationLocation.full_name')
                    ->label(__('inventories::filament/clusters/operations/resources/scrap.table.columns.scrap-location'))
                    ->sortable()
                    ->visible(fn (WarehouseSettings $settings) => $settings->enable_locations),
                Tables\Columns\TextColumn::make('qty')
                    ->label(__('inventories::filament/clusters/operations/resources/scrap.table.columns.quantity'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('uom.name')
                    ->label(__('inventories::filament/clusters/operations/resources/scrap.table.columns.uom'))
                    ->sortable()
                    ->visible(fn (WarehouseSettings $settings) => $settings->enable_locations),
                Tables\Columns\TextColumn::make('state')
                    ->label(__('inventories::filament/clusters/operations/resources/scrap.table.columns.state'))
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups(
                collect([
                    Tables\Grouping\Group::make('product.name')
                        ->label(__('inventories::filament/clusters/operations/resources/scrap.table.groups.product')),
                    Tables\Grouping\Group::make('sourceLocation.full_name')
                        ->label(__('inventories::filament/clusters/operations/resources/scrap.table.groups.source-location')),
                    Tables\Grouping\Group::make('destinationLocation.full_name')
                        ->label(__('inventories::filament/clusters/operations/resources/scrap.table.groups.destination-location')),
                ])->filter(function ($group) {
                    return match ($group->getId()) {
                        'sourceLocation.full_name', 'destinationLocation.full_name' => app(WarehouseSettings::class)->enable_locations,
                        default => true
                    };
                })->all()
            )
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints(collect([
                        app(WarehouseSettings::class)->enable_locations
                            ? Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('sourceLocation')
                                ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.source-location'))
                                ->multiple()
                                ->selectable(
                                    IsRelatedToOperator::make()
                                        ->titleAttribute('full_name')
                                        ->searchable()
                                        ->multiple()
                                        ->preload(),
                                )
                                ->icon('heroicon-o-map-pin')
                            : null,
                        app(WarehouseSettings::class)->enable_locations
                            ? Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('destinationLocation')
                                ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.destination-location'))
                                ->multiple()
                                ->selectable(
                                    IsRelatedToOperator::make()
                                        ->titleAttribute('full_name')
                                        ->searchable()
                                        ->multiple()
                                        ->preload(),
                                )
                                ->icon('heroicon-o-map-pin')
                            : null,
                        Tables\Filters\QueryBuilder\Constraints\SelectConstraint::make('state')
                            ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.state'))
                            ->multiple()
                            ->options(Enums\OperationState::class)
                            ->icon('heroicon-o-bars-2'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('product')
                            ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.product'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-shopping-bag'),
                        app(ProductSettings::class)->enable_uom
                            ? Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('uom')
                                ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.uom'))
                                ->multiple()
                                ->selectable(
                                    IsRelatedToOperator::make()
                                        ->titleAttribute('name')
                                        ->searchable()
                                        ->multiple()
                                        ->preload(),
                                )
                                ->icon('heroicon-o-shopping-bag')
                            : null,
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('product.category')
                            ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.product-category'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('full_name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-folder'),
                        app(TraceabilitySettings::class)->enable_lots_serial_numbers
                            ? Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('lot')
                                ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.lot'))
                                ->multiple()
                                ->selectable(
                                    IsRelatedToOperator::make()
                                        ->titleAttribute('name')
                                        ->searchable()
                                        ->multiple()
                                        ->preload(),
                                )
                                ->icon('heroicon-o-rectangle-stack')
                            : null,
                        app(OperationSettings::class)->enable_packages
                            ? Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('package')
                                ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.package'))
                                ->multiple()
                                ->selectable(
                                    IsRelatedToOperator::make()
                                        ->titleAttribute('name')
                                        ->searchable()
                                        ->multiple()
                                        ->preload(),
                                )
                                ->icon('heroicon-o-cube')
                            : null,
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('qty')
                            ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.quantity'))
                            ->icon('heroicon-o-scale'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('closed_at')
                            ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.closed-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.created-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.updated-at')),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.company'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-building-office'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('creator')
                            ->label(__('inventories::filament/clusters/operations/resources/scrap.table.filters.creator'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                    ])->filter()->values()->all()),
            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->hidden(fn (Scrap $record): bool => $record->state == Enums\ScrapState::DONE)
                        ->action(function (Scrap $record) {
                            try {
                                $record->delete();
                            } catch (QueryException $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('inventories::filament/clusters/operations/resources/scrap.table.actions.delete.notification.error.title'))
                                    ->body(__('inventories::filament/clusters/operations/resources/scrap.table.actions.delete.notification.error.body'))
                                    ->send();
                            }
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/operations/resources/scrap.table.actions.delete.notification.success.title'))
                                ->body(__('inventories::filament/clusters/operations/resources/scrap.table.actions.delete.notification.success.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            try {
                                $records->each(fn (Model $record) => $record->delete());
                            } catch (QueryException $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('inventories::filament/clusters/operations/resources/scrap.table.bulk-actions.delete.notification.error.title'))
                                    ->body(__('inventories::filament/clusters/operations/resources/scrap.table.bulk-actions.delete.notification.error.body'))
                                    ->send();
                            }
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/operations/resources/scrap.table.bulk-actions.delete.notification.success.title'))
                                ->body(__('inventories::filament/clusters/operations/resources/scrap.table.bulk-actions.delete.notification.success.body')),
                        ),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (Model $record): bool => static::can('delete', $record) && $record->state !== Enums\ScrapState::DONE,
            );
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.title'))
                            ->schema([
                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\Group::make()
                                            ->schema([
                                                Infolists\Components\TextEntry::make('product.name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.entries.product'))
                                                    ->icon('heroicon-o-shopping-bag'),

                                                Infolists\Components\TextEntry::make('qty')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.entries.quantity'))
                                                    ->icon('heroicon-o-calculator')
                                                    ->suffix(fn (Scrap $record) => ' '.$record->uom?->name),

                                                Infolists\Components\TextEntry::make('lot.name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.entries.lot'))
                                                    ->icon('heroicon-o-rectangle-stack')
                                                    ->placeholder('—')
                                                    ->visible(fn (TraceabilitySettings $settings) => $settings->enable_lots_serial_numbers),

                                                Infolists\Components\TextEntry::make('tags.name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.entries.tags'))
                                                    ->icon('heroicon-o-tag')
                                                    ->badge()
                                                    ->separator(','),
                                            ]),

                                        Infolists\Components\Group::make()
                                            ->schema([
                                                Infolists\Components\TextEntry::make('package.name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.entries.package'))
                                                    ->icon('heroicon-o-cube')
                                                    ->placeholder('—')
                                                    ->visible(fn (OperationSettings $settings) => $settings->enable_packages),

                                                Infolists\Components\TextEntry::make('partner.name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.entries.owner'))
                                                    ->icon('heroicon-o-user-circle'),

                                                Infolists\Components\TextEntry::make('sourceLocation.full_name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.entries.source-location'))
                                                    ->icon('heroicon-o-map-pin'),

                                                Infolists\Components\TextEntry::make('destinationLocation.full_name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.entries.destination-location'))
                                                    ->icon('heroicon-o-map-pin'),

                                                Infolists\Components\TextEntry::make('origin')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.entries.source-document'))
                                                    ->icon('heroicon-o-document-text')
                                                    ->placeholder('—'),

                                                Infolists\Components\TextEntry::make('company.name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.general.entries.company'))
                                                    ->icon('heroicon-o-building-office'),
                                            ]),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.record-information.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.record-information.entries.created-at'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar'),

                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.record-information.entries.created-by'))
                                    ->icon('heroicon-m-user'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label(__('inventories::filament/clusters/operations/resources/scrap.infolist.sections.record-information.entries.last-updated'))
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
            Pages\ViewScrap::class,
            Pages\EditScrap::class,
            Pages\ManageMoves::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListScraps::route('/'),
            'create' => Pages\CreateScrap::route('/create'),
            'view'   => Pages\ViewScrap::route('/{record}/view'),
            'edit'   => Pages\EditScrap::route('/{record}/edit'),
            'moves'  => Pages\ManageMoves::route('/{record}/moves'),
        ];
    }
}
