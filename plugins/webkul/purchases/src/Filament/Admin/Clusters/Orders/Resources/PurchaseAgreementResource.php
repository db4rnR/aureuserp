<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Product\Enums\ProductType;
use Webkul\Product\Models\Product;
use Webkul\Purchase\Enums\RequisitionState;
use Webkul\Purchase\Enums\RequisitionType;
use Webkul\Purchase\Filament\Admin\Clusters\Orders;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\PurchaseAgreementResource\Pages\CreatePurchaseAgreement;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\PurchaseAgreementResource\Pages\EditPurchaseAgreement;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\PurchaseAgreementResource\Pages\ListPurchaseAgreements;
use Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources\PurchaseAgreementResource\Pages\ViewPurchaseAgreement;
use Webkul\Purchase\Models\Requisition;
use Webkul\Purchase\Settings\OrderSettings;
use Webkul\Purchase\Settings\ProductSettings;

final class PurchaseAgreementResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Requisition::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-check';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $cluster = Orders::class;

    protected static ?int $navigationSort = 3;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('purchases::filament/admin/clusters/orders/resources/purchase-agreement.navigation.title');
    }

    public static function isDiscovered(): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return app(OrderSettings::class)->enable_purchase_agreements;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ProgressStepper::make('state')
                    ->hiddenLabel()
                    ->inline()
                    ->options(RequisitionState::options())
                    ->default(RequisitionState::DRAFT)
                    ->disabled(),
                Section::make(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.sections.general.title'))
                    ->schema([
                        Group::make()
                            ->schema([
                                Select::make('partner_id')
                                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.sections.general.fields.vendor'))
                                    ->relationship(
                                        'partner',
                                        'name',
                                        fn ($query) => $query->where('sub_type', 'supplier')
                                    )
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->disabled(fn ($record): bool => $record && $record?->state !== RequisitionState::DRAFT),
                                Select::make('user_id')
                                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.sections.general.fields.buyer'))
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->disabled(fn ($record): bool => $record && $record?->state !== RequisitionState::DRAFT),
                                Select::make('type')
                                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.sections.general.fields.agreement-type'))
                                    ->options(RequisitionType::class)
                                    ->required()
                                    ->default(RequisitionType::BLANKET_ORDER)
                                    ->disabled(fn ($record): bool => $record && $record?->state !== RequisitionState::DRAFT)
                                    ->live(),
                                Select::make('currency_id')
                                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.sections.general.fields.currency'))
                                    ->relationship('currency', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                            ]),

                        Group::make()
                            ->schema([
                                Group::make()
                                    ->schema([
                                        DatePicker::make('starts_at')
                                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.sections.general.fields.valid-from'))
                                            ->native(false)
                                            ->suffixIcon('heroicon-o-calendar'),
                                        DatePicker::make('ends_at')
                                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.sections.general.fields.valid-to'))
                                            ->native(false)
                                            ->suffixIcon('heroicon-o-calendar'),
                                    ])
                                    ->columns(2)
                                    ->hidden(fn (Get $get): bool => $get('type') !== RequisitionType::BLANKET_ORDER),
                                TextInput::make('reference')
                                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.sections.general.fields.reference'))
                                    ->maxLength(255)
                                    ->placeholder(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.sections.general.fields.reference-placeholder')),
                                Select::make('company_id')
                                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.sections.general.fields.company'))
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->default(auth()->user()->default_company_id)
                                    ->disabled(fn ($record): bool => $record && $record?->state !== RequisitionState::DRAFT),
                            ]),
                    ])
                    ->columns(2),

                Tabs::make()
                    ->schema([
                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.tabs.products.title'))
                            ->schema([
                                self::getProductsRepeater(),
                            ]),

                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.tabs.additional.title'))
                            ->visible(($customFormFields = self::getCustomFormFields()) !== [])
                            ->schema($customFormFields),

                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.tabs.terms.title'))
                            ->schema([
                                RichEditor::make('description')
                                    ->hiddenLabel(),
                            ]),
                    ]),
            ])
            ->columns(1);
    }

    public static function getProductsRepeater(): Repeater
    {
        $columns = 3;

        if (app(ProductSettings::class)->enable_uom) {
            $columns++;
        }

        return Repeater::make('lines')
            ->hiddenLabel()
            ->relationship()
            ->schema([
                Select::make('product_id')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.tabs.products.fields.product'))
                    ->relationship('product', 'name')
                    ->relationship(
                        'product',
                        'name',
                        fn ($query) => $query->where('type', ProductType::GOODS),
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get): void {
                        if ($product = Product::find($get('product_id'))) {
                            $set('uom_id', $product->uom_id);
                        }
                    })
                    ->disabled(fn ($record): bool => in_array($record?->requisition->state, [RequisitionState::CLOSED, RequisitionState::CANCELED], true)),
                TextInput::make('qty')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.tabs.products.fields.quantity'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(99999999999)
                    ->default(0)
                    ->required()
                    ->disabled(fn ($record): bool => in_array($record?->requisition->state, [RequisitionState::CLOSED, RequisitionState::CANCELED], true)),
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
                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_uom)
                    ->disabled(fn ($record): bool => in_array($record?->requisition->state, [RequisitionState::CLOSED, RequisitionState::CANCELED], true)),
                TextInput::make('price_unit')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.form.tabs.products.fields.unit-price'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(99999999999)
                    ->default(0)
                    ->required()
                    ->disabled(fn ($record): bool => in_array($record?->requisition->state, [RequisitionState::CLOSED, RequisitionState::CANCELED], true)),
            ])
            ->columns($columns);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::mergeCustomTableColumns([
                TextColumn::make('name')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.columns.agreement'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('partner.name')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.columns.vendor'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('type')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.columns.agreement-type'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.columns.buyer'))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('company.name')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.columns.company'))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('starts_at')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.columns.valid-from'))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('ends_at')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.columns.valid-to'))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('reference')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.columns.reference'))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('state')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.columns.status'))
                    ->sortable()
                    ->badge()
                    ->toggleable(),
            ]))
            ->groups([
                Tables\Grouping\Group::make('partner.name')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.groups.vendor')),
                Tables\Grouping\Group::make('state')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.groups.state')),
                Tables\Grouping\Group::make('type')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.groups.agreement-type')),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints(collect(self::mergeCustomTableQueryBuilderConstraints([
                        TextConstraint::make('name')
                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.filters.agreement')),
                        SelectConstraint::make('state')
                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.filters.status'))
                            ->multiple()
                            ->options(RequisitionState::class)
                            ->icon('heroicon-o-bars-2'),
                        RelationshipConstraint::make('partner')
                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.filters.vendor'))
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
                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.filters.buyer'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        RelationshipConstraint::make('company')
                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.filters.company'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-building-office'),
                        DateConstraint::make('starts_at')
                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.filters.valid-from')),
                        DateConstraint::make('ends_at')
                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.filters.valid-to')),
                        TextConstraint::make('reference')
                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.filters.reference'))
                            ->icon('heroicon-o-identification'),
                        DateConstraint::make('created_at')
                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.filters.created-at')),
                        DateConstraint::make('updated_at')
                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.filters.updated-at')),
                    ]))->filter()->values()->all()),
            ], layout: FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Action $action): Action => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    EditAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.actions.restore.notification.title'))
                                ->body(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.actions.restore.notification.body')),
                        ),
                    DeleteAction::make()
                        ->hidden(fn (Model $record): bool => $record->state === RequisitionState::CLOSED)
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.actions.delete.notification.title'))
                                ->body(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.actions.delete.notification.body')),
                        ),
                    ForceDeleteAction::make()
                        ->action(function (Requisition $record): void {
                            try {
                                $record->forceDelete();
                            } catch (QueryException) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.actions.force-delete.notification.error.title'))
                                    ->body(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.actions.force-delete.notification.error.body'))
                                    ->send();
                            }
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.actions.force-delete.notification.success.title'))
                                ->body(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.actions.force-delete.notification.success.body')),
                        ),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.bulk-actions.restore.notification.title'))
                                ->body(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.bulk-actions.restore.notification.body')),
                        ),
                    DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.bulk-actions.delete.notification.title'))
                                ->body(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.bulk-actions.delete.notification.body')),
                        ),
                    ForceDeleteBulkAction::make()
                        ->action(function (Collection $records): void {
                            try {
                                $records->each(fn (Model $record) => $record->forceDelete());
                            } catch (QueryException) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.bulk-actions.force-delete.notification.error.title'))
                                    ->body(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.bulk-actions.force-delete.notification.error.body'))
                                    ->send();
                            }
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.bulk-actions.force-delete.notification.success.title'))
                                ->body(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.table.bulk-actions.force-delete.notification.success.body')),
                        ),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (Model $record): bool => self::can('delete', $record) && $record->state !== RequisitionState::CLOSED,
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

                Section::make(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.general.title'))
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Group::make([
                                    TextEntry::make('partner.name')
                                        ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.general.entries.vendor'))
                                        ->icon('heroicon-o-building-storefront'),

                                    TextEntry::make('user.name')
                                        ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.general.entries.buyer'))
                                        ->icon('heroicon-o-user'),

                                    TextEntry::make('type')
                                        ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.general.entries.agreement-type'))
                                        ->icon('heroicon-o-document')
                                        ->badge(),

                                    TextEntry::make('currency.name')
                                        ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.general.entries.currency'))
                                        ->icon('heroicon-o-currency-dollar'),
                                ]),

                                Group::make([
                                    Grid::make(2)
                                        ->schema([
                                            TextEntry::make('starts_at')
                                                ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.general.entries.valid-from'))
                                                ->icon('heroicon-o-calendar')
                                                ->date(),

                                            TextEntry::make('ends_at')
                                                ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.general.entries.valid-to'))
                                                ->icon('heroicon-o-calendar')
                                                ->date(),
                                        ])
                                        ->visible(fn ($record): bool => $record->type === RequisitionType::BLANKET_ORDER),

                                    TextEntry::make('reference')
                                        ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.general.entries.reference'))
                                        ->icon('heroicon-o-identification'),

                                    TextEntry::make('company.name')
                                        ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.general.entries.company'))
                                        ->icon('heroicon-o-building-office'),
                                ]),
                            ]),
                    ]),

                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.tabs.products.title'))
                            ->icon('heroicon-o-cube')
                            ->schema([
                                RepeatableEntry::make('lines')
                                    ->schema([
                                        TextEntry::make('product.name')
                                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.tabs.products.entries.product')),

                                        TextEntry::make('qty')
                                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.tabs.products.entries.quantity')),

                                        TextEntry::make('uom.name')
                                            ->label(__('inventories::filament/clusters/operations/resources/operation.form.tabs.operations.entries.unit'))
                                            ->visible(fn (ProductSettings $settings): bool => $settings->enable_uom),

                                        TextEntry::make('price_unit')
                                            ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.tabs.products.entries.unit-price'))
                                            ->money(fn ($record) => $record->requisition->currency->code ?? 'USD'),
                                    ])
                                    ->columns([
                                        'sm' => 2,
                                        'xl' => 4,
                                    ]),
                            ]),

                        Section::make(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.tabs.additional.title'))
                            ->visible(($customInfolistEntries = self::getCustomInfolistEntries()) !== [])
                            ->schema($customInfolistEntries),

                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.tabs.terms.title'))
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextEntry::make('description')
                                    ->hiddenLabel()
                                    ->markdown()
                                    ->prose(),
                            ]),
                    ]),

                Section::make(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.metadata.title'))
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.metadata.entries.created-at'))
                                    ->dateTime()
                                    ->icon('heroicon-o-clock'),

                                TextEntry::make('creator.name')
                                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.metadata.entries.created-by'))
                                    ->icon('heroicon-o-user'),

                                TextEntry::make('updated_at')
                                    ->label(__('purchases::filament/admin/clusters/orders/resources/purchase-agreement.infolist.sections.metadata.entries.updated-at'))
                                    ->dateTime()
                                    ->icon('heroicon-o-arrow-path'),
                            ]),
                    ]),
            ])
            ->columns(1);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewPurchaseAgreement::class,
            EditPurchaseAgreement::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseAgreements::route('/'),
            'create' => CreatePurchaseAgreement::route('/create'),
            'edit' => EditPurchaseAgreement::route('/{record}/edit'),
            'view' => ViewPurchaseAgreement::route('/{record}/view'),
        ];
    }
}
