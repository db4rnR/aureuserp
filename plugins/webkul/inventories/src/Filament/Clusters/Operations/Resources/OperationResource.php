<?php

declare(strict_types=1);

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Enums\LocationType;
use Webkul\Inventory\Enums\MoveState;
use Webkul\Inventory\Enums\MoveType;
use Webkul\Inventory\Enums\OperationState;
use Webkul\Inventory\Enums\ProcureMethod;
use Webkul\Inventory\Enums\ProductTracking;
use Webkul\Inventory\Facades\Inventory;
use Webkul\Inventory\Filament\Clusters\Products\Resources\LotResource;
use Webkul\Inventory\Filament\Clusters\Products\Resources\PackageResource;
use Webkul\Inventory\Models\Move;
use Webkul\Inventory\Models\Operation;
use Webkul\Inventory\Models\OperationType;
use Webkul\Inventory\Models\Packaging;
use Webkul\Inventory\Models\Product;
use Webkul\Inventory\Models\ProductQuantity;
use Webkul\Inventory\Settings\OperationSettings;
use Webkul\Inventory\Settings\ProductSettings;
use Webkul\Inventory\Settings\TraceabilitySettings;
use Webkul\Inventory\Settings\WarehouseSettings;
use Webkul\Partner\Filament\Resources\PartnerResource;
use Webkul\Product\Enums\ProductType;
use Webkul\Support\Models\UOM;
use Webkul\TableViews\Filament\Components\PresetView;

final class OperationResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Operation::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ProgressStepper::make('state')
                    ->hiddenLabel()
                    ->inline()
                    ->options(OperationState::options())
                    ->options(function ($record): array {
                        $options = OperationState::options();

                        if ($record && $record->state !== OperationState::CANCELED) {
                            unset($options[OperationState::CANCELED->value]);
                        }

                        return $options;
                    })
                    ->default(OperationState::DRAFT)
                    ->disabled(),
                Section::make(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.title'))
                    ->schema([
                        Select::make('partner_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.receive-from'))
                            ->relationship('partner', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm(fn (Schema $schema): Schema => PartnerResource::form($schema))
                            ->visible(fn (Get $get): bool => OperationType::withTrashed()->find($get('operation_type_id'))?->type === Enums\OperationType::INCOMING)
                            ->disabled(fn ($record): bool => in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true)),
                        Select::make('partner_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.contact'))
                            ->relationship('partner', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm(fn (Schema $schema): Schema => PartnerResource::form($schema))
                            ->visible(fn (Get $get): bool => OperationType::withTrashed()->find($get('operation_type_id'))?->type === Enums\OperationType::INTERNAL)
                            ->disabled(fn ($record): bool => in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true)),
                        Select::make('partner_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.delivery-address'))
                            ->relationship('partner', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm(fn (Schema $schema): Schema => PartnerResource::form($schema))
                            ->visible(fn (Get $get): bool => OperationType::withTrashed()->find($get('operation_type_id'))?->type === Enums\OperationType::OUTGOING)
                            ->disabled(fn ($record): bool => in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true)),
                        Select::make('operation_type_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.operation-type'))
                            ->relationship(
                                name: 'operationType',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->withTrashed()
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->getOptionLabelFromRecordUsing(function (OperationType $record) {
                                if (! $record->warehouse) {
                                    return $record->name;
                                }

                                return $record->warehouse->name.': '.$record->name.($record->trashed() ? ' (Deleted)' : '');
                            })
                            ->disableOptionWhen(fn ($label): bool => str_contains((string) $label, ' (Deleted)'))
                            ->afterStateUpdated(function (Set $set, Get $get): void {
                                $operationType = OperationType::withTrashed()->find($get('operation_type_id'));

                                $set('source_location_id', $operationType?->source_location_id);
                                $set('destination_location_id', $operationType?->destination_location_id);
                            })
                            ->disabled(fn ($record): bool => in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true)),
                        Select::make('source_location_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.source-location'))
                            ->relationship(
                                'sourceLocation',
                                'full_name',
                                modifyQueryUsing: fn (Builder $query) => $query->withTrashed(),
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->full_name.($record->trashed() ? ' (Deleted)' : ''))
                            ->disableOptionWhen(fn ($label): bool => str_contains((string) $label, ' (Deleted)'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(fn (WarehouseSettings $settings, Get $get): bool => $settings->enable_locations && OperationType::withTrashed()->find($get('operation_type_id'))?->type !== Enums\OperationType::INCOMING)
                            ->disabled(fn ($record): bool => in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true)),
                        Select::make('destination_location_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.sections.general.fields.destination-location'))
                            ->relationship(
                                'destinationLocation',
                                'full_name',
                                modifyQueryUsing: fn (Builder $query) => $query->withTrashed(),
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->full_name.($record->trashed() ? ' (Deleted)' : ''))
                            ->disableOptionWhen(fn ($label): bool => str_contains((string) $label, ' (Deleted)'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(fn (WarehouseSettings $settings, Get $get): bool => $settings->enable_locations && OperationType::withTrashed()->find($get('operation_type_id'))?->type !== Enums\OperationType::OUTGOING)
                            ->disabled(fn ($record): bool => in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true)),
                    ])
                    ->columns(2),

                Tabs::make()
                    ->schema([
                        Tab::make(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.title'))
                            ->schema([
                                self::getMovesRepeater(),
                            ]),

                        Tab::make(__('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.title'))
                            ->schema([
                                Select::make('user_id')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.responsible'))
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->default(Auth::id())
                                    ->disabled(fn ($record): bool => in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true)),
                                Select::make('move_type')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.shipping-policy'))
                                    ->options(MoveType::class)
                                    ->default(MoveType::DIRECT)
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.shipping-policy-hint-tooltip'))
                                    ->visible(fn (Get $get): bool => OperationType::withTrashed()->find($get('operation_type_id'))?->type !== Enums\OperationType::INCOMING)
                                    ->disabled(fn ($record): bool => in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true)),
                                DateTimePicker::make('scheduled_at')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.scheduled-at'))
                                    ->native(false)
                                    ->default(now()->format('Y-m-d H:i:s'))
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.scheduled-at-hint-tooltip'))
                                    ->disabled(fn ($record): bool => in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true)),
                                TextInput::make('origin')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.source-document'))
                                    ->maxLength(255)
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/operations/resources/operation.form.tabs.additional.fields.source-document-hint-tooltip'))
                                    ->disabled(fn ($record): bool => in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true)),
                            ])
                            ->columns(2),

                        Tab::make(__('inventories::filament/clusters/operations/resources/operation.form.tabs.note.title'))
                            ->schema([
                                RichEditor::make('description')
                                    ->hiddenLabel(),
                            ]),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_favorite')
                    ->label('')
                    ->icon(fn (Operation $record): string => $record->is_favorite ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color(fn (Operation $record): string => $record->is_favorite ? 'warning' : 'gray')
                    ->action(function (Operation $record): void {
                        $record->update([
                            'is_favorite' => ! $record->is_favorite,
                        ]);
                    }),
                TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.reference'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sourceLocation.full_name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.from'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations),
                TextColumn::make('destinationLocation.full_name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.to'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations),
                TextColumn::make('partner.name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.contact'))
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.responsible'))
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('scheduled_at')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.scheduled-at'))
                    ->placeholder('—')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('deadline')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.deadline'))
                    ->placeholder('—')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('closed_at')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.closed-at'))
                    ->placeholder('—')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('origin')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.source-document'))
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('operationType.name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.operation-type'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.company'))
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('state')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.columns.state'))
                    ->sortable()
                    ->badge(),
            ])
            ->groups([
                Group::make('state')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.groups.state')),
                Group::make('origin')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.groups.source-document')),
                Group::make('operationType.name')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.groups.operation-type')),
                Group::make('schedule_at')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.groups.schedule-at'))
                    ->date(),
                Group::make('created_at')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.table.groups.created-at'))
                    ->date(),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints(collect(self::mergeCustomTableQueryBuilderConstraints([
                        TextConstraint::make('name')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.name')),
                        SelectConstraint::make('state')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.state'))
                            ->multiple()
                            ->options(OperationState::class)
                            ->icon('heroicon-o-bars-2'),
                        RelationshipConstraint::make('partner')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.partner'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        RelationshipConstraint::make('user')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.responsible'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        RelationshipConstraint::make('owner')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.owner'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        app(WarehouseSettings::class)->enable_locations
                            ? RelationshipConstraint::make('sourceLocation')
                                ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.source-location'))
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
                            ? RelationshipConstraint::make('destinationLocation')
                                ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.destination-location'))
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
                        DateConstraint::make('deadline')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.deadline'))
                            ->icon('heroicon-o-calendar'),
                        DateConstraint::make('scheduled_at')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.scheduled-at')),
                        DateConstraint::make('closed_at')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.closed-at')),
                        DateConstraint::make('created_at')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.created-at')),
                        DateConstraint::make('updated_at')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.updated-at')),
                        RelationshipConstraint::make('company')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.company'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-building-office'),
                        RelationshipConstraint::make('creator')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.table.filters.creator'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                    ]))->filter()->values()->all()),
            ], layout: FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Action $action): Action => $action
                    ->slideOver(),
            )
            ->filtersTriggerAction(
                fn (Action $action): Action => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->checkIfRecordIsSelectableUsing(
                fn (Model $record): bool => self::can('delete', $record) && $record->state !== OperationState::DONE,
            );
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('state')
                            ->badge(),
                    ])
                    ->compact(),

                Section::make(__('inventories::filament/clusters/operations/resources/operation.infolist.sections.general.title'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('partner.name')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.sections.general.entries.contact'))
                                    ->icon('heroicon-o-user-group')
                                    ->placeholder('—'),

                                TextEntry::make('operationType.name')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.sections.general.entries.operation-type'))
                                    ->icon('heroicon-o-clipboard-document-list'),

                                TextEntry::make('sourceLocation.full_name')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.sections.general.entries.source-location'))
                                    ->icon('heroicon-o-arrow-up-tray')
                                    ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations),

                                TextEntry::make('destinationLocation.full_name')
                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.sections.general.entries.destination-location'))
                                    ->icon('heroicon-o-arrow-down-tray')
                                    ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations),
                            ]),
                    ]),

                // Tabs Section
                Tabs::make('Details')
                    ->tabs([
                        // Operations Tab
                        Tab::make(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.title'))
                            ->schema([
                                RepeatableEntry::make('moves')
                                    ->schema([
                                        Grid::make(5)
                                            ->schema([
                                                TextEntry::make('product.name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.entries.product'))
                                                    ->icon('heroicon-o-cube'),

                                                TextEntry::make('finalLocation.full_name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.entries.final-location'))
                                                    ->icon('heroicon-o-map-pin')
                                                    ->placeholder('—')
                                                    ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations),

                                                TextEntry::make('description_picking')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.entries.description'))
                                                    ->icon('heroicon-o-document-text')
                                                    ->placeholder('—'),

                                                TextEntry::make('scheduled_at')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.entries.scheduled-at'))
                                                    ->dateTime()
                                                    ->icon('heroicon-o-calendar')
                                                    ->placeholder('—'),

                                                TextEntry::make('deadline')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.entries.deadline'))
                                                    ->dateTime()
                                                    ->icon('heroicon-o-clock')
                                                    ->placeholder('—'),

                                                TextEntry::make('productPackaging.name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.entries.packaging'))
                                                    ->icon('heroicon-o-gift')
                                                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_packagings)
                                                    ->placeholder('—'),

                                                TextEntry::make('product_qty')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.entries.demand'))
                                                    ->icon('heroicon-o-calculator'),

                                                TextEntry::make('quantity')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.entries.quantity'))
                                                    ->icon('heroicon-o-scale')
                                                    ->placeholder('—'),

                                                TextEntry::make('uom.name')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.entries.unit'))
                                                    ->icon('heroicon-o-beaker')
                                                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_uom),

                                                IconEntry::make('is_picked')
                                                    ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.operations.entries.picked'))
                                                    ->icon(fn (bool $state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                                                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                                            ]),
                                    ]),
                            ]),

                        Tab::make(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.additional.title'))
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('user.name')
                                            ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.additional.entries.responsible'))
                                            ->icon('heroicon-o-user')
                                            ->placeholder('—'),

                                        TextEntry::make('move_type')
                                            ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.additional.entries.shipping-policy'))
                                            ->icon('heroicon-o-truck')
                                            ->placeholder('—'),

                                        TextEntry::make('scheduled_at')
                                            ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.additional.entries.scheduled-at'))
                                            ->dateTime()
                                            ->icon('heroicon-o-calendar')
                                            ->placeholder('—'),

                                        TextEntry::make('origin')
                                            ->label(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.additional.entries.source-document'))
                                            ->icon('heroicon-o-document-text')
                                            ->placeholder('—'),
                                    ]),
                            ]),

                        Tab::make(__('inventories::filament/clusters/operations/resources/operation.infolist.tabs.note.title'))
                            ->schema([
                                TextEntry::make('description')
                                    ->markdown()
                                    ->hiddenLabel()
                                    ->placeholder('—'),
                            ]),
                    ]),
            ])
            ->columns(1);
    }

    /**
     * @param  array<mixed>  $parameters
     */
    public static function getUrl(?string $name = null, array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null, bool $shouldGuessMissingParameters = false): string
    {
        return match ($parameters['record']?->operationType->type) {
            Enums\OperationType::INCOMING => ReceiptResource::getUrl('view', $parameters, $isAbsolute, $panel, $tenant, $shouldGuessMissingParameters),
            Enums\OperationType::INTERNAL => InternalResource::getUrl('view', $parameters, $isAbsolute, $panel, $tenant, $shouldGuessMissingParameters),
            Enums\OperationType::OUTGOING => DeliveryResource::getUrl('view', $parameters, $isAbsolute, $panel, $tenant, $shouldGuessMissingParameters),
            default => parent::getUrl('view', $parameters, $isAbsolute, $panel, $tenant, $shouldGuessMissingParameters),
        };
    }

    public static function getMovesRepeater(): Repeater
    {
        return Repeater::make('moves')
            ->hiddenLabel()
            ->relationship()
            ->schema([
                Select::make('product_id')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.product'))
                    ->relationship('product', 'name')
                    ->relationship(
                        'product',
                        'name',
                        fn ($query) => $query->where('type', ProductType::GOODS)->whereNull('is_configurable'),
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get): void {
                        self::afterProductUpdated($set, $get);
                    })
                    ->disabled(fn (Move $move): bool => $move->id && $move->state !== MoveState::DRAFT),
                Select::make('final_location_id')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.final-location'))
                    ->relationship(
                        'finalLocation',
                        'full_name',
                        modifyQueryUsing: fn (Builder $query) => $query->withTrashed(),
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record): string => $record->full_name.($record->trashed() ? ' (Deleted)' : ''))
                    ->disableOptionWhen(fn ($label): bool => str_contains((string) $label, ' (Deleted)'))
                    ->searchable()
                    ->preload()
                    ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_locations)
                    ->disabled(fn ($record): bool => in_array($record?->state, [MoveState::DONE, MoveState::CANCELED], true)),
                TextInput::make('description_picking')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.description'))
                    ->maxLength(255)
                    ->disabled(fn ($record): bool => in_array($record?->state, [MoveState::DONE, MoveState::CANCELED], true)),
                DateTimePicker::make('scheduled_at')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.scheduled-at'))
                    ->default(now())
                    ->native(false)
                    ->disabled(fn ($record): bool => in_array($record?->state, [MoveState::DONE, MoveState::CANCELED], true)),
                DateTimePicker::make('deadline')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.deadline'))
                    ->native(false)
                    ->disabled(fn ($record): bool => in_array($record?->state, [MoveState::DONE, MoveState::CANCELED], true)),
                Select::make('product_packaging_id')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.packaging'))
                    ->relationship('productPackaging', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_packagings)
                    ->disabled(fn ($record): bool => in_array($record?->state, [MoveState::DONE, MoveState::CANCELED], true)),
                TextInput::make('product_uom_qty')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.demand'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(99999999999)
                    ->default(0)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get): void {
                        self::afterProductUOMQtyUpdated($set, $get);
                    })
                    ->disabled(fn (Move $move): bool => $move->id && $move->state !== MoveState::DRAFT),
                TextInput::make('quantity')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.quantity'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(99999999999)
                    ->default(0)
                    ->required()
                    ->visible(fn (Move $move): bool => $move->id && $move->state !== MoveState::DRAFT)
                    ->disabled(fn ($record): bool => in_array($record?->state, [MoveState::DONE, MoveState::CANCELED], true))
                    ->suffixAction(fn ($record): Action => self::getMoveLinesAction($record)),
                Select::make('uom_id')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.unit'))
                    ->relationship(
                        'uom',
                        'name',
                        fn ($query) => $query->where('category_id', 1),
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get): void {
                        self::afterUOMUpdated($set, $get);
                    })
                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_uom)
                    ->disabled(fn ($record): bool => in_array($record?->state, [MoveState::DONE, MoveState::CANCELED], true)),
                Toggle::make('is_picked')
                    ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.picked'))
                    ->default(0)
                    ->inline(false)
                    ->disabled(fn ($record): bool => in_array($record?->state, [MoveState::DONE, MoveState::CANCELED], true)),
                Hidden::make('product_qty')
                    ->default(0),
            ])
            ->columns(4)
            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, $record) {
                $product = Product::find($data['product_id']);

                return array_merge($data, [
                    'creator_id' => Auth::id(),
                    'company_id' => Auth::user()->default_company_id,
                    'warehouse_id' => $record->destinationLocation->warehouse_id,
                    'state' => $record->state->value,
                    'name' => $product->name,
                    'procure_method' => ProcureMethod::MAKE_TO_STOCK,
                    'uom_id' => $data['uom_id'] ?? $product->uom_id,
                    'operation_type_id' => $record->operation_type_id,
                    'quantity' => null,
                    'source_location_id' => $record->source_location_id,
                    'destination_location_id' => $record->destination_location_id,
                    'scheduled_at' => $record->scheduled_at ?? now(),
                    'reference' => $record->name,
                ]);
            })
            ->mutateRelationshipDataBeforeSaveUsing(function (array $data, $record): array {
                if (isset($data['quantity'])) {
                    $record->fill([
                        'quantity' => $data['quantity'] ?? null,
                    ]);

                    Inventory::computeTransferMove($record);

                    Inventory::computeTransferState($record->operation);
                }

                return $data;
            })
            ->deletable(fn ($record): bool => ! in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true))
            ->addable(fn ($record): bool => ! in_array($record?->state, [OperationState::DONE, OperationState::CANCELED], true));
    }

    public static function getMoveLinesAction($move): Action
    {
        $columns = 2;

        if (
            app(TraceabilitySettings::class)->enable_lots_serial_numbers
            && (
                $move->product->tracking === ProductTracking::LOT
                || $move->product->tracking === ProductTracking::SERIAL
            )
            && $move->sourceLocation->type === LocationType::SUPPLIER
        ) {
            $columns++;
        }

        if ($move->sourceLocation->type === LocationType::INTERNAL) {
            $columns++;
        }

        if ($move->destinationLocation->type !== LocationType::INTERNAL) {
            $columns--;
        }

        if (app(OperationSettings::class)->enable_packages) {
            $columns++;
        }

        return Action::make('manageLines')
            ->icon('heroicon-m-bars-4')
            ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.modal-heading'))
            ->modalSubmitActionLabel('Save')
            ->visible(app(WarehouseSettings::class)->enable_locations)
            ->schema([
                Repeater::make('lines')
                    ->hiddenLabel()
                    ->relationship('lines')
                    ->schema([
                        Select::make('quantity_id')
                            ->label(__(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.fields.pick-from')))
                            ->options(function ($record) use ($move) {
                                if (in_array($record?->state, [MoveState::DONE, MoveState::CANCELED], true)) {
                                    $nameParts = array_filter([
                                        $record->sourceLocation->full_name,
                                        $record->lot?->name,
                                        $record->package?->name,
                                    ]);

                                    return [
                                        $record->id => implode(' - ', $nameParts),
                                    ];
                                }

                                return ProductQuantity::with(['location', 'lot', 'package'])
                                    ->where('product_id', $move->product_id)
                                    ->whereHas('location', function (Builder $query) use ($move): void {
                                        $query->where('id', $move->source_location_id)
                                            ->orWhere('parent_id', $move->source_location_id);
                                    })
                                    ->get()
                                    ->mapWithKeys(function ($quantity) {
                                        $nameParts = array_filter([
                                            $quantity->location->full_name,
                                            $quantity->lot?->name,
                                            $quantity->package?->name,
                                        ]);

                                        return [$quantity->id => implode(' - ', $nameParts)];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateHydrated(function (Select $component, $record): void {
                                if (in_array($record?->state, [MoveState::DONE, MoveState::CANCELED], true)) {
                                    $component->state($record->id);

                                    return;
                                }

                                $productQuantity = ProductQuantity::with(['location', 'lot', 'package'])
                                    ->where('product_id', $record?->product_id)
                                    ->where('location_id', $record?->source_location_id)
                                    ->where('lot_id', $record?->lot_id ?? null)
                                    ->where('package_id', $record?->package_id ?? null)
                                    ->first();

                                $component->state($productQuantity?->id);
                            })
                            ->afterStateUpdated(function (Set $set, Get $get) use ($move): void {
                                $productQuantity = ProductQuantity::find($get('quantity_id'));

                                $set('lot_id', $productQuantity?->lot_id);

                                $set('result_package_id', $productQuantity?->package_id);

                                if ($productQuantity?->quantity) {
                                    if (! $move->uom_id) {
                                        $set('qty', $productQuantity->quantity);
                                    } else {
                                        $set('qty', (float) ($productQuantity->quantity ?? 0) * $move->uom->factor);
                                    }
                                }
                            })
                            ->visible($move->sourceLocation->type === LocationType::INTERNAL)
                            ->disabled(fn (): bool => in_array($move->state, [MoveState::DONE, MoveState::CANCELED], true)),
                        Select::make('lot_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.fields.lot'))
                            ->relationship(
                                name: 'lot',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('product_id', $move->product_id),
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (): bool => in_array($move->state, [MoveState::DONE, MoveState::CANCELED], true))
                            ->disableOptionWhen(fn (): bool => ! $move->operationType->use_existing_lots)
                            ->createOptionForm(fn (Schema $schema): Schema => LotResource::form($schema))
                            ->createOptionAction(function (Action $action) use ($move): void {
                                $action->visible($move->operationType->use_create_lots)
                                    ->mutateDataUsing(function (array $data) use ($move) {
                                        $data['product_id'] = $move->product_id;

                                        return $data;
                                    });
                            })
                            ->visible(fn (TraceabilitySettings $settings): bool => $settings->enable_lots_serial_numbers
                                && (
                                    $move->product->tracking === ProductTracking::LOT
                                    || $move->product->tracking === ProductTracking::SERIAL
                                )
                                && $move->sourceLocation->type === LocationType::SUPPLIER
                            ),
                        Select::make('destination_location_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.fields.location'))
                            ->relationship(
                                name: 'destinationLocation',
                                titleAttribute: 'full_name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->withTrashed()
                                    ->where('type', '<>', LocationType::VIEW)
                                    ->where(function ($query) use ($move): void {
                                        $query->where('id', $move->destination_location_id)
                                            ->orWhere('parent_id', $move->destination_location_id);
                                    })
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record): string => $record->full_name.($record->trashed() ? ' (Deleted)' : ''))
                            ->disableOptionWhen(fn ($label): bool => str_contains((string) $label, ' (Deleted)'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->default($move->destination_location_id)
                            ->afterStateUpdated(function (Set $set): void {
                                $set('result_package_id', null);
                            })
                            ->visible($move->destinationLocation->type === LocationType::INTERNAL)
                            ->disabled(fn (): bool => in_array($move->state, [MoveState::DONE, MoveState::CANCELED], true)),
                        Select::make('result_package_id')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.fields.package'))
                            ->relationship(
                                name: 'resultPackage',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query, Get $get, $record) => $query
                                    ->where(function ($query) use ($get, $record): void {
                                        $query->where('location_id', $get('destination_location_id'))
                                            ->orWhere('id', $record?->result_package_id ?? $get('result_package_id'))
                                            ->orWhereNull('location_id');
                                    }),
                            )
                            ->searchable()
                            ->preload()
                            ->createOptionForm(fn (Schema $schema): Schema => PackageResource::form($schema))
                            ->createOptionAction(function (Action $action) use ($move): void {
                                $action->mutateDataUsing(function (array $data) use ($move) {
                                    $data['company_id'] = $move->company_id;

                                    return $data;
                                });
                            })
                            ->disabled(fn (): bool => in_array($move->state, [MoveState::DONE, MoveState::CANCELED], true))
                            ->visible(fn (OperationSettings $settings): bool => $settings->enable_packages),
                        TextInput::make('qty')
                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.fields.quantity'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(99999999999)
                            ->maxValue(fn (): int => $move->product->tracking === ProductTracking::SERIAL ? 1 : 999999999)
                            ->required()
                            ->suffix(function () use ($move) {
                                if (! app(ProductSettings::class)->enable_uom) {
                                    return false;
                                }

                                return $move->uom->name;
                            })
                            ->disabled(fn (): bool => in_array($move->state, [MoveState::DONE, MoveState::CANCELED], true)),
                    ])
                    ->defaultItems(0)
                    ->addActionLabel(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.fields.lines.add-line'))
                    ->columns($columns)
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Move $move): array {
                        if (isset($data['quantity_id'])) {
                            $productQuantity = ProductQuantity::find($data['quantity_id']);

                            $data['lot_id'] = $productQuantity?->lot_id;

                            $data['package_id'] = $productQuantity?->package_id;
                        }

                        $data['reference'] = $move->reference;
                        $data['state'] = $move->state;
                        $data['uom_qty'] = self::calculateProductQuantity($data['uom_id'] ?? $move->uom_id, $data['qty']);
                        $data['scheduled_at'] = $move->scheduled_at;
                        $data['operation_id'] = $move->operation_id;
                        $data['move_id'] = $move->id;
                        $data['source_location_id'] = $move->source_location_id;
                        $data['uom_id'] ??= $move->uom_id;
                        $data['creator_id'] = Auth::id();
                        $data['product_id'] = $move->product_id;
                        $data['company_id'] = $move->company_id;

                        return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        if (isset($data['quantity_id'])) {
                            $productQuantity = ProductQuantity::find($data['quantity_id']);

                            $data['lot_id'] = $productQuantity?->lot_id;

                            $data['package_id'] = $productQuantity?->package_id;
                        }

                        return $data;
                    })
                    ->deletable(fn (): bool => ! in_array($move->state, [MoveState::DONE, MoveState::CANCELED], true))
                    ->addable(fn (): bool => ! in_array($move->state, [MoveState::DONE, MoveState::CANCELED], true)),
            ])
            ->modalWidth('6xl')
            ->mountUsing(function (Schema $schema, Move $record): void {
                $schema->fill([]);
            })
            ->modalSubmitAction(
                fn ($action, Move $record) => $action
                    ->visible(! in_array($move->state, [MoveState::DONE, MoveState::CANCELED], true))
            )
            ->action(function (Set $set, array $data, Move $record): void {
                $totalQty = $record->lines()->sum('qty');

                $record->fill([
                    'quantity' => $totalQty,
                ]);

                Inventory::computeTransferMove($record);

                $set('quantity', $totalQty);
            });
    }

    public static function getPresetTableViews(): array
    {
        return [
            'todo_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/operation.tabs.todo'))
                ->favorite()
                ->icon('heroicon-s-clipboard-document-list')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotIn('state', [OperationState::DONE, OperationState::CANCELED])),
            'my_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/operation.tabs.my'))
                ->favorite()
                ->icon('heroicon-s-user')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::id())),
            'favorite_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/operation.tabs.starred'))
                ->favorite()
                ->icon('heroicon-s-star')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_favorite', true)),
            'draft_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/operation.tabs.draft'))
                ->favorite()
                ->icon('heroicon-s-pencil-square')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', OperationState::DRAFT)),
            'waiting_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/operation.tabs.waiting'))
                ->favorite()
                ->icon('heroicon-s-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', OperationState::CONFIRMED)),
            'ready_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/operation.tabs.ready'))
                ->favorite()
                ->icon('heroicon-s-play-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', OperationState::ASSIGNED)),
            'done_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/operation.tabs.done'))
                ->favorite()
                ->icon('heroicon-s-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', OperationState::DONE)),
            'canceled_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/operation.tabs.canceled'))
                ->icon('heroicon-s-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', OperationState::CANCELED)),
        ];
    }

    public static function calculateProductQuantity($uomId, $uomQuantity): float|int
    {
        if (! $uomId) {
            return self::normalizeZero((float) ($uomQuantity ?? 0));
        }

        $uom = UOM::find($uomId);

        if (! $uom || ! is_numeric($uom->factor) || $uom->factor === 0) {
            return 0;
        }

        $quantity = (float) ($uomQuantity ?? 0) / $uom->factor;

        return self::normalizeZero($quantity);
    }

    private static function normalizeZero(float $value): float
    {
        return $value === 0 ? 0.0 : $value; // convert -0.0 to 0.0
    }

    private static function afterProductUpdated(Set $set, Get $get): void
    {
        if (! $get('product_id')) {
            return;
        }

        $product = Product::find($get('product_id'));

        $set('uom_id', $product->uom_id);

        $productQuantity = self::calculateProductQuantity($get('uom_id'), $get('product_uom_qty'));

        $set('product_qty', round($productQuantity, 2));

        $packaging = self::getBestPackaging($get('product_id'), round($productQuantity, 2));

        $set('product_packaging_id', $packaging['packaging_id'] ?? null);
    }

    private static function afterProductUOMQtyUpdated(Set $set, Get $get): void
    {
        if (! $get('product_id')) {
            return;
        }

        $productQuantity = self::calculateProductQuantity($get('uom_id'), $get('product_uom_qty'));

        $set('product_qty', round($productQuantity, 2));

        $packaging = self::getBestPackaging($get('product_id'), $productQuantity);

        $set('product_packaging_id', $packaging['packaging_id'] ?? null);
    }

    private static function afterUOMUpdated(Set $set, Get $get): void
    {
        if (! $get('product_id')) {
            return;
        }

        $productQuantity = self::calculateProductQuantity($get('uom_id'), $get('product_uom_qty'));

        $set('product_qty', round($productQuantity, 2));

        $packaging = self::getBestPackaging($get('product_id'), $productQuantity);

        $set('product_packaging_id', $packaging['packaging_id'] ?? null);
    }

    private static function getBestPackaging($productId, float|int $quantity): ?array
    {
        Product::find($productId);

        $packagings = Packaging::where('product_id', $productId)
            ->orderByDesc('qty')
            ->get();

        foreach ($packagings as $packaging) {
            if ($quantity && $quantity % $packaging->qty === 0) {
                return [
                    'packaging_id' => $packaging->id,
                    'packaging_qty' => round($quantity / $packaging->qty, 2),
                ];
            }
        }

        return null;
    }
}
