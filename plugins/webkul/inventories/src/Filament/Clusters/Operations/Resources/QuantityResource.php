<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Operations;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\QuantityResource\Pages;
use Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource;
use Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource;
use Webkul\Inventory\Models\Location;
use Webkul\Inventory\Models\Product;
use Webkul\Inventory\Models\ProductQuantity;
use Webkul\Inventory\Models\Warehouse;
use Webkul\Inventory\Settings\OperationSettings;
use Webkul\Inventory\Settings\ProductSettings;
use Webkul\Inventory\Settings\TraceabilitySettings;
use Webkul\Inventory\Settings\WarehouseSettings;

class QuantityResource extends Resource
{
    protected static ?string $model = ProductQuantity::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-up-down';

    protected static ?int $navigationSort = 4;

    protected static ?string $cluster = Operations::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/quantity.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/operations/resources/quantity.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('location_id')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.form.fields.location'))
                    ->relationship(
                        name: 'location',
                        titleAttribute: 'full_name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('type', Enums\LocationType::INTERNAL),
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->visible(fn (WarehouseSettings $settings) => $settings->enable_locations),
                Forms\Components\Select::make('product_id')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.form.fields.product'))
                    ->relationship(
                        name: 'product',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('is_storable', true)->whereNull('is_configurable'),
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                        $set('lot_id', null);
                    }),
                Forms\Components\Select::make('lot_id')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.form.fields.lot'))
                    ->relationship(
                        name: 'lot',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query, Forms\Get $get) => $query->where('product_id', $get('product_id')),
                    )
                    ->searchable()
                    ->preload()
                    ->createOptionForm(fn (Form $form): Form => LotResource::form($form))
                    ->createOptionAction(function (Action $action, Forms\Get $get) {
                        $action
                            ->mutateFormDataUsing(function (array $data) use ($get): array {
                                $data['product_id'] = $get('product_id');

                                return $data;
                            });
                    })
                    ->visible(function (TraceabilitySettings $settings, Forms\Get $get): bool {
                        if (! $settings->enable_lots_serial_numbers) {
                            return false;
                        }

                        $product = Product::find($get('product_id'));

                        if (! $product) {
                            return false;
                        }

                        return $product->tracking === Enums\ProductTracking::LOT;
                    }),
                Forms\Components\Select::make('package_id')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.form.fields.package'))
                    ->relationship('package', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm(fn (Form $form): Form => PackageResource::form($form))
                    ->visible(fn (OperationSettings $settings) => $settings->enable_packages),
                Forms\Components\TextInput::make('counted_quantity')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.form.fields.counted-qty'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(99999999999)
                    ->default(0)
                    ->required(),
                Forms\Components\DatePicker::make('scheduled_at')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.form.fields.scheduled-at'))
                    ->native(false)
                    ->default(now()->setDay(app(OperationSettings::class)->annual_inventory_day)->setMonth(app(OperationSettings::class)->annual_inventory_month)),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('location.full_name')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.location'))
                    ->searchable()
                    ->sortable()
                    ->visible(fn (WarehouseSettings $settings) => $settings->enable_locations),
                Tables\Columns\TextColumn::make('storageCategory.name')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.storage-category'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn (WarehouseSettings $settings) => $settings->enable_locations),
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.product'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.category.name')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.product-category'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lot.name')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.lot'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('—')
                    ->visible(fn (TraceabilitySettings $settings) => $settings->enable_lots_serial_numbers),
                Tables\Columns\TextColumn::make('package.name')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.package'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('—')
                    ->visible(fn (OperationSettings $settings) => $settings->enable_packages),
                Tables\Columns\TextColumn::make('available_quantity')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.available-quantity'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.on-hand'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextInputColumn::make('counted_quantity')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.counted'))
                    ->sortable()
                    ->rules(['numeric', 'min:0'])
                    ->beforeStateUpdated(function ($record, $state) {
                        $record->update([
                            'inventory_quantity_set'  => true,
                            'inventory_diff_quantity' => $state - $record->quantity,
                        ]);
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/quantity.table.columns.on-hand-before-state-updated.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/quantity.table.columns.on-hand-before-state-updated.notification.body'))
                            ->success()
                            ->send();
                    }),
                Tables\Columns\TextColumn::make('inventory_diff_quantity')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.difference'))
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->inventory_quantity_set ? $record->inventory_diff_quantity : '')
                    ->color(fn ($record) => $record->inventory_diff_quantity > 0 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.scheduled-at'))
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.user'))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.columns.company'))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups(
                collect([
                    Tables\Grouping\Group::make('product.name')
                        ->label(__('inventories::filament/clusters/operations/resources/quantity.table.groups.product')),
                    Tables\Grouping\Group::make('product.category.full_name')
                        ->label(__('inventories::filament/clusters/operations/resources/quantity.table.groups.product-category')),
                    Tables\Grouping\Group::make('location.full_name')
                        ->label(__('inventories::filament/clusters/operations/resources/quantity.table.groups.location')),
                    Tables\Grouping\Group::make('storageCategory.name')
                        ->label(__('inventories::filament/clusters/operations/resources/quantity.table.groups.storage-category')),
                    Tables\Grouping\Group::make('lot.name')
                        ->label(__('inventories::filament/clusters/operations/resources/quantity.table.groups.lot')),
                    Tables\Grouping\Group::make('package.name')
                        ->label(__('inventories::filament/clusters/operations/resources/quantity.table.groups.package')),
                    Tables\Grouping\Group::make('company.name')
                        ->label(__('inventories::filament/clusters/operations/resources/quantity.table.groups.company')),
                ])->filter(function ($group) {
                    return match ($group->getId()) {
                        'location.full_name', 'storageCategory.name' => app(WarehouseSettings::class)->enable_locations,
                        'lot.name'     => app(TraceabilitySettings::class)->enable_lots_serial_numbers,
                        'package.name' => app(OperationSettings::class)->enable_packages,
                        default        => true
                    };
                })->all()
            )
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints(collect([
                        app(WarehouseSettings::class)->enable_locations
                            ? Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('location')
                                ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.location'))
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
                            ? Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('storageCategory')
                                ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.storage-category'))
                                ->multiple()
                                ->selectable(
                                    IsRelatedToOperator::make()
                                        ->titleAttribute('full_name')
                                        ->searchable()
                                        ->multiple()
                                        ->preload(),
                                )
                                ->icon('heroicon-o-folder')
                                : null,
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('product')
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.product'))
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
                                ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.uom'))
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
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.product-category'))
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
                                ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.lot'))
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
                                ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.package'))
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
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('quantity')
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.on-hand-quantity'))
                            ->icon('heroicon-o-scale'),
                        Tables\Filters\QueryBuilder\Constraints\NumberConstraint::make('difference')
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.difference-quantity'))
                            ->icon('heroicon-o-scale'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('user')
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.user'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('incoming_at')
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.incoming-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('scheduled_at')
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.scheduled-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.created-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.updated-at')),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.company'))
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
                            ->label(__('inventories::filament/clusters/operations/resources/quantity.table.filters.creator'))
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $product = Product::find($data['product_id']);

                        $data['location_id'] = $data['location_id'] ?? Warehouse::first()->lot_stock_location_id;

                        $data['creator_id'] = Auth::id();

                        $data['company_id'] = $product->company_id;

                        $data['inventory_quantity_set'] = true;

                        $data['inventory_diff_quantity'] = $data['counted_quantity'];

                        $data['incoming_at'] = now();

                        $data['scheduled_at'] = now()->setDay(app(OperationSettings::class)->annual_inventory_day)->setMonth(app(OperationSettings::class)->annual_inventory_month);

                        return $data;
                    })
                    ->before(function (Tables\Actions\CreateAction $action, array $data) {
                        $existingQuantity = ProductQuantity::where('location_id', $data['location_id'] ?? Warehouse::first()->lot_stock_location_id)
                            ->where('product_id', $data['product_id'])
                            ->where('package_id', $data['package_id'] ?? null)
                            ->where('lot_id', $data['lot_id'] ?? null)
                            ->exists();

                        if ($existingQuantity) {
                            Notification::make()
                                ->title(__('inventories::filament/clusters/operations/resources/quantity.table.header-actions.create.before.notification.title'))
                                ->body(__('inventories::filament/clusters/operations/resources/quantity.table.header-actions.create.before.notification.body'))
                                ->warning()
                                ->send();

                            $action->halt();
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/quantity.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/quantity.table.header-actions.create.notification.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('apply')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.actions.apply.label'))
                    ->icon('heroicon-o-check')
                    ->visible(fn (ProductQuantity $record) => $record->inventory_quantity_set)
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/quantity.table.actions.apply.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/quantity.table.actions.apply.notification.body')),
                    )
                    ->action(function (ProductQuantity $record) {
                        $adjustmentLocation = Location::where('type', Enums\LocationType::INVENTORY)
                            ->where('is_scrap', false)
                            ->first();

                        $countedQuantity = $record->counted_quantity;

                        $diffQuantity = $record->inventory_diff_quantity;

                        $record->update([
                            'quantity'                => $countedQuantity,
                            'counted_quantity'        => 0,
                            'inventory_diff_quantity' => 0,
                            'inventory_quantity_set'  => false,
                        ]);

                        ProductQuantity::updateOrCreate(
                            [
                                'location_id' => $adjustmentLocation->id,
                                'product_id'  => $record->product_id,
                                'lot_id'      => $record->lot_id,
                            ], [
                                'quantity'               => -$record->product->on_hand_quantity,
                                'company_id'             => $record->company_id,
                                'creator_id'             => Auth::id(),
                                'incoming_at'            => now(),
                                'inventory_quantity_set' => false,
                            ]
                        );

                        if ($diffQuantity < 0) {
                            $sourceLocationId = $record->location_id;

                            $destinationLocationId = $adjustmentLocation->id;
                        } else {
                            $sourceLocationId = $adjustmentLocation->id;

                            $destinationLocationId = $record->location_id;
                        }

                        ProductResource::createMove($record, abs($diffQuantity), $sourceLocationId, $destinationLocationId);
                    }),
                Tables\Actions\Action::make('clear')
                    ->label(__('inventories::filament/clusters/operations/resources/quantity.table.actions.clear.label'))
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn (ProductQuantity $record) => $record->inventory_quantity_set)
                    ->color('gray')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/quantity.table.actions.clear.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/quantity.table.actions.clear.notification.body')),
                    )
                    ->action(function (ProductQuantity $record) {
                        $record->update([
                            'inventory_quantity_set'  => false,
                            'counted_quantity'        => 0,
                            'inventory_diff_quantity' => 0,
                        ]);
                    }),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereHas('location', function (Builder $query) {
                    $query->whereIn('type', [Enums\LocationType::INTERNAL, Enums\LocationType::TRANSIT]);
                });

                $query->whereHas('product', function (Builder $query) {
                    $query->whereNull('deleted_at');
                });
            });
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ManageQuantities::route('/'),
        ];
    }
}
