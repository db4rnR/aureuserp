<?php

declare(strict_types=1);

namespace Webkul\Purchase\Filament\Admin\Clusters\Orders\Resources;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Webkul\Account\Enums\TypeTaxUse;
use Webkul\Account\Facades\Tax as TaxFacade;
use Webkul\Account\Filament\Resources\IncoTermResource;
use Webkul\Account\Models\Partner;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Product\Enums\ProductType;
use Webkul\Product\Models\Packaging;
use Webkul\Purchase\Enums\OrderState;
use Webkul\Purchase\Enums\QtyReceivedMethod;
use Webkul\Purchase\Livewire\Summary;
use Webkul\Purchase\Models\Order;
use Webkul\Purchase\Models\Product;
use Webkul\Purchase\Settings\OrderSettings;
use Webkul\Purchase\Settings\ProductSettings;
use Webkul\Support\Models\Currency;
use Webkul\Support\Models\UOM;
use Webkul\Support\Package;

class OrderResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Order::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->components([
                ProgressStepper::make('state')->hiddenLabel()
                    ->inline()
                    ->options(function ($record): array {
                        $options = OrderState::options();

                        if ($record && $record->state !== OrderState::CANCELED) {
                            unset($options[OrderState::CANCELED->value]);
                        }

                        if ($record && $record->state !== OrderState::DONE) {
                            unset($options[OrderState::DONE->value]);
                        }

                        return $options;
                    })
                    ->default(OrderState::DRAFT)
                    ->disabled(),
                Section::make(__('purchases::filament/admin/clusters/orders/resources/order.form.sections.general.title'))
                    ->schema([
                        Group::make()->schema([
                                Select::make('partner_id')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.sections.general.fields.vendor'))
                                    ->relationship(
                                        'partner',
                                        'name',
                                    )
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->createOptionForm(fn (Schema $form): Schema => VendorResource::form($form))
                                    ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                                        if ($state) {
                                            $vendor = Partner::find($state);

                                            $set('payment_term_id', $vendor->property_supplier_payment_term_id);

                                            $products = $get('products');
                                            if (is_array($products)) {
                                                foreach ($products as $key => $product) {
                                                    if (isset($product['product_id'])) {
                                                        $productModel = Product::find($product['product_id']);
                                                        if ($productModel) {
                                                            $vendorPrices = $productModel->supplierInformation
                                                                ->where('partner_id', $state)
                                                                ->where('currency_id', $get('currency_id'))
                                                                ->where('min_qty', '<=', $product['product_qty'] ?? 1)
                                                                ->sortByDesc('sort');

                                                            $vendorPrice = $vendorPrices->isNotEmpty() ? $vendorPrices->first()->price : $productModel->cost ?? $productModel->price;

                                                            $set("products.$key.price_unit", round($vendorPrice, 2));

                                                            self::calculateLineTotals($set, $get, "products.$key.");
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    })
                                    ->live()
                                    ->disabled(fn ($record): bool => $record && ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT], true)),
                                TextInput::make('partner_reference')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.sections.general.fields.vendor-reference'))
                                    ->maxLength(255)
                                    ->hintIcon('heroicon-o-question-mark-circle', tooltip: __('purchases::filament/admin/clusters/orders/resources/order.form.sections.general.fields.vendor-reference-tooltip')),
                                Select::make('requisition_id')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.sections.general.fields.agreement'))
                                    ->relationship('requisition', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn (OrderSettings $setting): bool => $setting->enable_purchase_agreements),
                                Select::make('currency_id')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.sections.general.fields.currency'))
                                    ->relationship('currency', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->default(Auth::user()->defaultCompany?->currency_id)
                                    ->disabled(fn ($record): bool => $record && ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT], true)),
                            ]),

                        Group::make()->schema([
                                DateTimePicker::make('approved_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.sections.general.fields.confirmation-date'))
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->default(now())
                                    ->disabled()
                                    ->visible(fn ($record): bool => $record && ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT], true)),
                                DateTimePicker::make('ordered_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.sections.general.fields.order-deadline'))
                                    ->native(false)
                                    ->required()
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->default(now())
                                    ->hidden(fn ($record): bool => $record && ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT], true)),
                                DateTimePicker::make('planned_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.sections.general.fields.expected-arrival'))
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->hint('Test')
                                    ->hint(fn ($record): string => $record && $record->mail_reminder_confirmed ? __('purchases::filament/admin/clusters/orders/resources/order.form.sections.general.fields.confirmed-by-vendor') : '')
                                    ->disabled(fn ($record): bool => $record && ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT, OrderState::PURCHASE], true)),
                            ]),
                    ])
                    ->columns(2),

                Tabs::make()->schema([
                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.title'))
                            ->schema([
                                self::getProductRepeater(),
                                Livewire::make(Summary::class, fn (Get $get): array => [
                                    'currency' => Currency::find($get('currency_id')),
                                    'products' => $get('products'),
                                ])
                                    ->live()
                                    ->reactive(),
                            ]),

                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.additional.title'))
                            ->schema(self::mergeCustomFormFields([
                                Group::make()->schema([
                                        Select::make('user_id')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.additional.fields.buyer'))
                                            ->relationship('user', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->default(Auth::id())
                                            ->disabled(fn ($record): bool => $record && ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT, OrderState::PURCHASE], true)),
                                        Select::make('company_id')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.additional.fields.company'))
                                            ->relationship('company', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->default(Auth::user()->default_company_id)
                                            ->disabled(fn ($record): bool => $record && ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT], true)),
                                        TextInput::make('reference')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.additional.fields.source-document'))
                                            ->maxLength(255),
                                        Select::make('incoterm_id')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.additional.fields.incoterm'))
                                            ->relationship('incoterm', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm(fn (Schema $form): Schema => IncoTermResource::form($form))
                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: __('purchases::filament/admin/clusters/orders/resources/order.form.tabs.additional.fields.incoterm-tooltip'))
                                            ->disabled(fn ($record): bool => $record && ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT, OrderState::PURCHASE], true)),
                                        TextInput::make('reference')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.additional.fields.incoterm-location'))
                                            ->maxLength(255)
                                            ->disabled(fn ($record): bool => $record && ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT, OrderState::PURCHASE], true)),
                                    ]),

                                Group::make()->schema([
                                        Select::make('payment_term_id')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.additional.fields.payment-term'))
                                            ->relationship('paymentTerm', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->disabled(fn ($record): bool => $record && ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT, OrderState::PURCHASE], true)),
                                    ]),
                            ]))
                            ->columns(2),

                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.terms.title'))
                            ->schema([
                                RichEditor::make('description')->hiddenLabel(),
                            ]),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::mergeCustomTableColumns([
                IconColumn::make('priority')->label('')
                    ->icon(fn (Order $record): string => $record->priority ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color(fn (Order $record): string => $record->priority ? 'warning' : 'gray')
                    ->action(function (Order $record): void {
                        $record->update([
                            'priority' => ! $record->priority,
                        ]);
                    }),
                TextColumn::make('partner_reference')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.vendor-reference'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.reference'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('partner.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.vendor'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('company.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.company'))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('user.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.buyer'))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('ordered_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.order-deadline'))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('origin')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.source-document'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('untaxed_amount')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.untaxed-amount'))
                    ->sortable()
                    ->money(fn (Order $record) => $record->currency->code)
                    ->toggleable(),
                TextColumn::make('total_amount')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.total-amount'))
                    ->sortable()
                    ->money(fn (Order $record) => $record->currency->code)
                    ->toggleable(),
                TextColumn::make('invoice_status')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.billing-status'))
                    ->sortable()
                    ->badge()
                    ->toggleable(),
                TextColumn::make('state')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.status'))
                    ->sortable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]))
            ->groups([
                Tables\Grouping\Group::make('partner.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.groups.vendor')),
                Tables\Grouping\Group::make('user.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.groups.buyer')),
                Tables\Grouping\Group::make('state')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.groups.state')),
                Tables\Grouping\Group::make('created_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->filters([
                QueryBuilder::make()->constraints(collect(self::mergeCustomTableQueryBuilderConstraints([
                        SelectConstraint::make('state')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.status'))
                            ->multiple()
                            ->options(OrderState::class)
                            ->icon('heroicon-o-bars-2'),
                        TextConstraint::make('partner_reference')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.vendor-reference'))
                            ->icon('heroicon-o-identification'),
                        TextConstraint::make('name')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.reference'))
                            ->icon('heroicon-o-identification'),
                        NumberConstraint::make('untaxed_amount')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.untaxed-amount')),
                        NumberConstraint::make('total_amount')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.total-amount')),
                        RelationshipConstraint::make('partner')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.vendor'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        RelationshipConstraint::make('user')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.buyer'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        RelationshipConstraint::make('company')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.company'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-building-office'),
                        RelationshipConstraint::make('paymentTerm')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.payment-term'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-currency-dollar'),
                        RelationshipConstraint::make('incoterm')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.incoterm'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-globe-alt'),
                        DateConstraint::make('ordered_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.order-deadline')),
                        DateConstraint::make('created_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.created-at')),
                        DateConstraint::make('updated_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.filters.updated-at')),
                    ]))->filter()->values()->all()),
            ], layout: FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn (Action $action): Action => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()->hidden(fn (Model $record): bool => $record->state === OrderState::DONE)
                        ->action(function (Model $record): void {
                            try {
                                $record->delete();
                            } catch (QueryException) {
                                Notification::make()->danger()
                                    ->title(__('purchases::filament/admin/clusters/orders/resources/order.table.actions.delete.notification.error.title'))
                                    ->body(__('purchases::filament/admin/clusters/orders/resources/order.table.actions.delete.notification.error.body'))
                                    ->send();
                            }
                        })
                        ->successNotification(
                            Notification::make()->success()
                                ->title(__('purchases::filament/admin/clusters/orders/resources/order.table.actions.delete.notification.success.title'))
                                ->body(__('purchases::filament/admin/clusters/orders/resources/order.table.actions.delete.notification.success.body')),
                        ),
                ]),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()->action(function (Collection $records): void {
                        try {
                            $records->each(fn (Model $record) => $record->delete());
                        } catch (QueryException) {
                            Notification::make()->danger()
                                ->title(__('purchases::filament/admin/clusters/orders/resources/order.table.bulk-actions.delete.notification.error.title'))
                                ->body(__('purchases::filament/admin/clusters/orders/resources/order.table.bulk-actions.delete.notification.error.body'))
                                ->send();
                        }
                    })
                    ->successNotification(
                        Notification::make()->success()
                            ->title(__('purchases::filament/admin/clusters/orders/resources/order.table.bulk-actions.delete.notification.success.title'))
                            ->body(__('purchases::filament/admin/clusters/orders/resources/order.table.bulk-actions.delete.notification.success.body')),
                    ),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (Model $record): bool => self::can('delete', $record) && $record->state !== OrderState::DONE,
            );
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->components([
                Section::make()->schema([
                        TextEntry::make('state')->badge(),
                    ])
                    ->compact(),

                Section::make(__('purchases::filament/admin/clusters/orders/resources/order.infolist.sections.general.title'))
                    ->schema([
                        Grid::make(2)->schema([
                                Group::make([
                                    TextEntry::make('partner.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.sections.general.entries.vendor'))
                                        ->icon('heroicon-o-user-group'),
                                    TextEntry::make('partner_reference')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.sections.general.entries.vendor-reference'))
                                        ->icon('heroicon-o-document-text')
                                        ->placeholder('—'),
                                    TextEntry::make('requisition.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.sections.general.entries.agreement'))
                                        ->placeholder('—')
                                        ->icon('heroicon-o-document-check')
                                        ->visible(fn (OrderSettings $setting): bool => $setting->enable_purchase_agreements),
                                    TextEntry::make('currency.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.sections.general.entries.currency'))
                                        ->icon('heroicon-o-currency-dollar'),
                                ]),

                                Group::make([
                                    TextEntry::make('approved_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.sections.general.entries.confirmation-date'))
                                        ->dateTime()
                                        ->icon('heroicon-o-calendar')
                                        ->visible(fn ($record): bool => ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT], true)),
                                    TextEntry::make('ordered_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.sections.general.entries.order-deadline'))
                                        ->dateTime()
                                        ->icon('heroicon-o-calendar')
                                        ->hidden(fn ($record): bool => ! in_array($record?->state, [OrderState::DRAFT, OrderState::SENT], true)),
                                    TextEntry::make('planned_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.sections.general.entries.expected-arrival'))
                                        ->dateTime()
                                        ->icon('heroicon-o-calendar')
                                        ->hintColor('success')
                                        ->hint(fn ($record): string => $record->mail_reminder_confirmed ? __('purchases::filament/admin/clusters/orders/resources/order.infolist.sections.general.entries.confirmed-by-vendor') : ''),
                                ]),
                            ]),
                    ]),

                Tabs::make('Tabs')->tabs([
                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.title'))
                            ->schema([
                                RepeatableEntry::make('lines')->hiddenLabel()
                                    ->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.title'))
                                    ->schema([
                                        Grid::make(4)->schema([
                                                TextEntry::make('product.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.product'))
                                                    ->icon('heroicon-o-cube'),
                                                TextEntry::make('planned_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.expected-arrival'))
                                                    ->dateTime()
                                                    ->icon('heroicon-o-calendar'),
                                                TextEntry::make('product_qty')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.quantity'))
                                                    ->icon('heroicon-o-calculator'),
                                                TextEntry::make('qty_received')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.received'))
                                                    ->visible(fn ($record): bool => in_array($record?->order->state, [OrderState::PURCHASE, OrderState::DONE], true))
                                                    ->icon('heroicon-o-calculator'),
                                                TextEntry::make('qty_invoiced')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.billed'))
                                                    ->visible(fn ($record): bool => in_array($record?->order->state, [OrderState::PURCHASE, OrderState::DONE], true))
                                                    ->icon('heroicon-o-calculator'),
                                                TextEntry::make('uom.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.unit'))
                                                    ->icon('heroicon-o-beaker')
                                                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_uom),
                                                TextEntry::make('product_packaging_qty')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.packaging-qty'))
                                                    ->icon('heroicon-o-calculator')
                                                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_packagings),
                                                TextEntry::make('productPackaging.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.packaging'))
                                                    ->icon('heroicon-o-gift')
                                                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_packagings),
                                                TextEntry::make('price_unit')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.unit-price'))
                                                    ->money(fn ($record) => $record->order->currency->code),
                                                TextEntry::make('taxes.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.taxes'))
                                                    ->badge()
                                                    ->state(fn ($record): array => $record->taxes->map(fn ($tax): array => [
                                                        'name' => $tax->name,
                                                    ])->toArray())
                                                    ->icon('heroicon-o-receipt-percent')
                                                    ->formatStateUsing(fn ($state) => $state['name'])
                                                    ->placeholder('-'),
                                                TextEntry::make('discount')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.discount-percentage'))
                                                    ->suffix('%'),
                                                TextEntry::make('price_subtotal')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.products.repeater.products.entries.amount'))
                                                    ->money(fn ($record) => $record->order->currency->code),
                                            ]),
                                    ])
                                    ->columnSpanFull(),

                                Group::make([
                                    TextEntry::make('untaxed_amount')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.untaxed-amount'))
                                        ->money(fn (Order $record) => $record->currency->code),
                                    TextEntry::make('tax_amount')->label('Tax Amount')
                                        ->money(fn (Order $record) => $record->currency->code),
                                    TextEntry::make('total_amount')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.total-amount'))
                                        ->money(fn (Order $record) => $record->currency->code),
                                    TextEntry::make('invoice_status')->label(__('purchases::filament/admin/clusters/orders/resources/order.table.columns.billing-status'))
                                        ->badge(),
                                ])
                                    ->columnSpanFull()
                                    ->columns(4),
                            ]),

                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.additional.title'))
                            ->schema([
                                Grid::make(2)->schema([
                                        Group::make([
                                            TextEntry::make('user.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.additional.entries.buyer'))
                                                ->placeholder('—'),
                                            TextEntry::make('company.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.additional.entries.company'))
                                                ->placeholder('—'),
                                            TextEntry::make('reference')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.additional.entries.source-document'))
                                                ->placeholder('—'),
                                            TextEntry::make('incoterm.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.additional.entries.incoterm'))
                                                ->icon('heroicon-o-question-mark-circle')
                                                ->placeholder('—')
                                                ->tooltip(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.additional.entries.incoterm-tooltip')),
                                        ]),

                                        Group::make([
                                            TextEntry::make('paymentTerm.name')->label(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.additional.entries.payment-term'))
                                                ->placeholder('—'),
                                        ]),
                                    ]),
                            ]),

                        Tab::make(__('purchases::filament/admin/clusters/orders/resources/order.infolist.tabs.terms.title'))
                            ->schema([
                                TextEntry::make('description')->hiddenLabel()
                                    ->markdown()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }

    public static function getProductRepeater(): Repeater
    {
        return Repeater::make('products')->relationship('lines')
            ->hiddenLabel()
            ->live()
            ->reactive()
            ->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.title'))
            ->addActionLabel(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.add-product-line'))
            ->collapsible()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
            ->deleteAction(fn (Action $action): Action => $action->requiresConfirmation())
            ->deletable(fn ($record): bool => ! in_array($record?->state, [OrderState::DONE, OrderState::CANCELED], true))
            ->addable(fn ($record): bool => ! in_array($record?->state, [OrderState::DONE, OrderState::CANCELED], true))
            ->schema([
                Group::make()->schema([
                        Grid::make(4)->schema([
                                Select::make('product_id')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.product'))
                                    ->relationship(
                                        'product',
                                        'name',
                                        fn ($query) => $query->where('type', ProductType::GOODS)->whereNull('is_configurable'),
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get): void {
                                        self::afterProductUpdated($set, $get);
                                    })
                                    ->required()
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::SENT, OrderState::PURCHASE, OrderState::DONE, OrderState::CANCELED], true)),
                                DateTimePicker::make('planned_at')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.expected-arrival'))
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->required()
                                    ->default(now())
                                    ->default(function (Get $get, Set $set) {
                                        if (empty($get('../../planned_at'))) {
                                            $set('../../planned_at', now());
                                        }

                                        return now();
                                    })
                                    ->afterStateUpdated(function (?string $state, Set $set): void {
                                        $set('../../planned_at', $state);
                                    })
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::DONE, OrderState::CANCELED], true)),
                                TextInput::make('product_qty')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.quantity'))
                                    ->required()
                                    ->default(1)
                                    ->numeric()
                                    ->maxValue(99999999999)
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get): void {
                                        self::afterProductQtyUpdated($set, $get);
                                    })
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::DONE, OrderState::CANCELED], true)),
                                TextInput::make('qty_received')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.received'))
                                    ->required()
                                    ->default(0)
                                    ->numeric()
                                    ->maxValue(99999999999)
                                    ->visible(fn ($record): bool => in_array($record?->order->state, [OrderState::PURCHASE, OrderState::DONE], true))
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::DONE, OrderState::CANCELED], true) || $record?->qty_received_method === QtyReceivedMethod::STOCK_MOVE),
                                TextInput::make('qty_invoiced')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.billed'))
                                    ->default(0)
                                    ->numeric()
                                    ->maxValue(99999999999)
                                    ->visible(fn ($record): bool => in_array($record?->order->state, [OrderState::PURCHASE, OrderState::DONE], true))
                                    ->disabled(),
                                Select::make('uom_id')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.unit'))
                                    ->relationship(
                                        'uom',
                                        'name',
                                        fn ($query) => $query->where('category_id', 1)->orderBy('id'),
                                    )
                                    ->required()
                                    ->live()
                                    ->selectablePlaceholder(false)
                                    ->afterStateUpdated(function (Set $set, Get $get): void {
                                        self::afterUOMUpdated($set, $get);
                                    })
                                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_uom)
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::PURCHASE, OrderState::DONE, OrderState::CANCELED], true)),
                                TextInput::make('product_packaging_qty')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.packaging-qty'))
                                    ->live()
                                    ->numeric()
                                    ->maxValue(99999999999)
                                    ->afterStateUpdated(function (Set $set, Get $get): void {
                                        self::afterProductPackagingQtyUpdated($set, $get);
                                    })
                                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_packagings)
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::DONE, OrderState::CANCELED], true)),
                                Select::make('product_packaging_id')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.packaging'))
                                    ->relationship(
                                        'productPackaging',
                                        'name',
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get): void {
                                        self::afterProductPackagingUpdated($set, $get);
                                    })
                                    ->visible(fn (ProductSettings $settings): bool => $settings->enable_packagings)
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::DONE, OrderState::CANCELED], true)),
                                TextInput::make('price_unit')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.unit-price'))
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->maxValue(99999999999)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get): void {
                                        self::calculateLineTotals($set, $get);
                                    })
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::DONE, OrderState::CANCELED], true)),
                                Select::make('taxes')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.taxes'))
                                    ->relationship(
                                        'taxes',
                                        'name',
                                        fn (Builder $query) => $query->where('type_tax_use', TypeTaxUse::PURCHASE->value),
                                    )
                                    ->searchable()
                                    ->multiple()
                                    ->preload()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state): void {
                                        self::calculateLineTotals($set, $get);
                                    })
                                    ->live()
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::DONE, OrderState::CANCELED], true)),
                                TextInput::make('discount')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.discount-percentage'))
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get): void {
                                        self::calculateLineTotals($set, $get);
                                    })
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::DONE, OrderState::CANCELED], true)),
                                TextInput::make('price_subtotal')->label(__('purchases::filament/admin/clusters/orders/resources/order.form.tabs.products.repeater.products.fields.amount'))
                                    ->default(0)
                                    ->readOnly()
                                    ->disabled(fn ($record): bool => in_array($record?->order->state, [OrderState::DONE, OrderState::CANCELED], true)),
                                Hidden::make('product_uom_qty')->default(0),
                                Hidden::make('price_tax')->default(0),
                                Hidden::make('price_total')->default(0),
                            ]),
                    ])
                    ->columns(2),
            ])
            ->mutateRelationshipDataBeforeCreateUsing(function (array $data, $record) {
                $product = Product::find($data['product_id']);

                $qtyReceivedMethod = QtyReceivedMethod::MANUAL;

                if (Package::isPluginInstalled('inventories')) {
                    $qtyReceivedMethod = QtyReceivedMethod::STOCK_MOVE;
                }

                return array_merge($data, [
                    'name' => $product->name,
                    'state' => $record->state->value,
                    'qty_received_method' => $qtyReceivedMethod,
                    'uom_id' => $data['uom_id'] ?? $product->uom_id,
                    'currency_id' => $record->currency_id,
                    'partner_id' => $record->partner_id,
                    'creator_id' => Auth::id(),
                    'company_id' => Auth::user()->default_company_id,
                ]);
            });
    }

    private static function afterProductUpdated(Set $set, Get $get): void
    {
        if (! $get('product_id')) {
            return;
        }

        $product = Product::find($get('product_id'));

        $set('uom_id', $product->uom_id);

        $uomQuantity = self::calculateUnitQuantity($get('uom_id'), $get('product_qty'));

        $set('product_uom_qty', round($uomQuantity, 2));

        $priceUnit = self::calculateUnitPrice($get);

        $set('price_unit', round($priceUnit, 2));

        $set('taxes', $product->productTaxes->pluck('id')->toArray());

        $packaging = self::getBestPackaging($get('product_id'), round($uomQuantity, 2));

        $set('product_packaging_id', $packaging['packaging_id'] ?? null);

        $set('product_packaging_qty', $packaging['packaging_qty'] ?? null);

        self::calculateLineTotals($set, $get);
    }

    private static function afterProductQtyUpdated(Set $set, Get $get): void
    {
        if (! $get('product_id')) {
            return;
        }

        $uomQuantity = self::calculateUnitQuantity($get('uom_id'), $get('product_qty'));

        $set('product_uom_qty', round($uomQuantity, 2));

        $packaging = self::getBestPackaging($get('product_id'), $uomQuantity);

        $set('product_packaging_id', $packaging['packaging_id'] ?? null);

        $set('product_packaging_qty', $packaging['packaging_qty'] ?? null);

        self::calculateLineTotals($set, $get);
    }

    private static function afterUOMUpdated(Set $set, Get $get): void
    {
        if (! $get('product_id')) {
            return;
        }

        $uomQuantity = self::calculateUnitQuantity($get('uom_id'), $get('product_qty'));

        $set('product_uom_qty', round($uomQuantity, 2));

        $packaging = self::getBestPackaging($get('product_id'), $uomQuantity);

        $set('product_packaging_id', $packaging['packaging_id'] ?? null);

        $set('product_packaging_qty', $packaging['packaging_qty'] ?? null);

        $priceUnit = self::calculateUnitPrice($get);

        $set('price_unit', round($priceUnit, 2));

        self::calculateLineTotals($set, $get);
    }

    private static function afterProductPackagingQtyUpdated(Set $set, Get $get): void
    {
        if (! $get('product_id')) {
            return;
        }

        if ($get('product_packaging_id')) {
            $packaging = Packaging::find($get('product_packaging_id'));

            $packagingQty = (float) ($get('product_packaging_qty') ?? 0);

            $productUOMQty = $packagingQty * $packaging->qty;

            $set('product_uom_qty', round($productUOMQty, 2));

            $uom = UOM::find($get('uom_id'));

            $productQty = $uom ? $productUOMQty * $uom->factor : $productUOMQty;

            $set('product_qty', round($productQty, 2));
        }

        self::calculateLineTotals($set, $get);
    }

    private static function afterProductPackagingUpdated(Set $set, Get $get): void
    {
        if (! $get('product_id')) {
            return;
        }

        if ($get('product_packaging_id')) {
            $packaging = Packaging::find($get('product_packaging_id'));

            $productUOMQty = $get('product_uom_qty') ?: 1;

            if ($packaging) {
                $packagingQty = $productUOMQty / $packaging->qty;

                $set('product_packaging_qty', $packagingQty);
            }
        } else {
            $set('product_packaging_qty', null);
        }

        self::calculateLineTotals($set, $get);
    }

    private static function calculateUnitQuantity($uomId, $quantity)
    {
        if (! $uomId) {
            return $quantity;
        }

        $uom = UOM::find($uomId);

        return (float) ($quantity ?? 0) / $uom->factor;
    }

    private static function calculateUnitPrice(Get $get)
    {
        $product = Product::find($get('product_id'));

        $vendorPrices = $product->supplierInformation->sortByDesc('sort');

        if ($get('../../partner_id')) {
            $vendorPrices = $vendorPrices->where('partner_id', $get('../../partner_id'));
        }

        $vendorPrices = $vendorPrices->where('min_qty', '<=', $get('product_qty') ?? 1)->where('currency_id', $get('../../currency_id'));

        $vendorPrice = $vendorPrices->isEmpty() ? $product->cost ?? $product->price : $vendorPrices->first()->price;

        if (! $get('uom_id')) {
            return $vendorPrice;
        }

        $uom = UOM::find($get('uom_id'));

        return (float) ($vendorPrice / $uom->factor);
    }

    private static function getBestPackaging($productId, $quantity): ?array
    {
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

    private static function calculateLineTotals(Set $set, Get $get, ?string $prefix = ''): void
    {
        if (! $get($prefix.'product_id')) {
            $set($prefix.'price_unit', 0);

            $set($prefix.'discount', 0);

            $set($prefix.'price_tax', 0);

            $set($prefix.'price_subtotal', 0);

            $set($prefix.'price_total', 0);

            return;
        }

        $priceUnit = (float) ($get($prefix.'price_unit'));

        $quantity = (float) ($get($prefix.'product_qty') ?? 1);

        $subTotal = $priceUnit * $quantity;

        $discountValue = (float) ($get($prefix.'discount') ?? 0);

        if ($discountValue > 0) {
            $discountAmount = $subTotal * ($discountValue / 100);

            $subTotal -= $discountAmount;
        }

        $taxIds = $get($prefix.'taxes') ?? [];

        [$subTotal, $taxAmount] = TaxFacade::collect($taxIds, $subTotal, $quantity);

        $set($prefix.'price_subtotal', round($subTotal, 4));

        $set($prefix.'price_tax', $taxAmount);

        $set($prefix.'price_total', $subTotal + $taxAmount);
    }
}
