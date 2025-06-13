<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources;

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
use Illuminate\Support\Facades\Auth;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Inventory\Enums\DeliveryStep;
use Webkul\Inventory\Enums\ReceptionStep;
use Webkul\Inventory\Filament\Clusters\Configurations;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource\Pages;
use Webkul\Inventory\Models\Warehouse;
use Webkul\Inventory\Settings\WarehouseSettings;
use Webkul\Partner\Filament\Resources\PartnerResource;

class WarehouseResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Configurations::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static bool $isGloballySearchable = false;

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/configurations/resources/warehouse.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/warehouse.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.title'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->autofocus()
                                    ->placeholder(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.name-placeholder'))
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;'])
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('code')
                                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.code'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.code-placeholder'))
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.code-hint-tooltip'))
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('company_id')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.company'))
                                            ->relationship('company', 'name')
                                            ->required()
                                            ->disabled(fn () => Auth::user()->default_company_id)
                                            ->default(Auth::user()->default_company_id),
                                        Forms\Components\Select::make('partner_address_id')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.address'))
                                            ->relationship('partnerAddress', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm(fn (Form $form): Form => PartnerResource::form($form)),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.additional.title'))
                            ->visible(! empty($customFormFields = static::getCustomFormFields()))
                            ->schema($customFormFields),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.title'))
                            ->schema([
                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.shipment-management'))
                                    ->schema([
                                        Forms\Components\Radio::make('reception_steps')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.incoming-shipments'))
                                            ->options(ReceptionStep::class)
                                            ->default(ReceptionStep::ONE_STEP)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.incoming-shipments-hint-tooltip')),

                                        Forms\Components\Radio::make('delivery_steps')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.outgoing-shipments'))
                                            ->options(DeliveryStep::class)
                                            ->default(DeliveryStep::ONE_STEP)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.outgoing-shipments-hint-tooltip')),
                                    ])
                                    ->columns(1)
                                    ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_multi_steps_routes),

                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.resupply-management'))
                                    ->schema([
                                        Forms\Components\CheckboxList::make('supplierWarehouses')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.resupply-from'))
                                            ->relationship('supplierWarehouses', 'name'),
                                    ])
                                    ->visible(Warehouse::count() > 1),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->visible(fn (WarehouseSettings $settings): bool => $settings->enable_multi_steps_routes),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.company'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partnerAddress.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.address'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.deleted-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company_id')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.filters.company'))
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->groups([
                Tables\Grouping\Group::make('company.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.groups.company'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.restore.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.restore.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.delete.notification.body')),
                    ),
                Tables\Actions\ForceDeleteAction::make()
                    ->action(function (Warehouse $record) {
                        try {
                            $record->forceDelete();
                        } catch (QueryException $e) {
                            Notification::make()
                                ->danger()
                                ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.force-delete.notification.error.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.force-delete.notification.error.body'))
                                ->send();
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.force-delete.notification.success.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.force-delete.notification.success.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.restore.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.delete.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            try {
                                $records->each(fn (Model $record) => $record->forceDelete());
                            } catch (QueryException $e) {
                                Notification::make()
                                    ->danger()
                                    ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.force-delete.notification.error.title'))
                                    ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.force-delete.notification.error.body'))
                                    ->send();
                            }
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.force-delete.notification.success.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.force-delete.notification.success.body')),
                        ),
                ]),
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
                        Infolists\Components\Section::make(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.general.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.general.entries.name'))
                                    ->icon('heroicon-o-building-storefront')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(FontWeight::Bold),

                                Infolists\Components\TextEntry::make('code')
                                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.general.entries.code'))
                                    ->icon('heroicon-m-hashtag'),

                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('company.name')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.general.entries.company'))
                                            ->icon('heroicon-o-building-office'),
                                        Infolists\Components\TextEntry::make('partnerAddress.name')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.general.entries.address'))
                                            ->icon('heroicon-o-map'),
                                    ])
                                    ->columns(2),
                            ]),

                        Infolists\Components\Section::make(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.settings.title'))
                            ->schema([
                                Infolists\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.settings.entries.shipment-management'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('reception_steps')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.settings.entries.incoming-shipments'))
                                            ->icon('heroicon-o-truck'),

                                        Infolists\Components\TextEntry::make('delivery_steps')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.settings.entries.outgoing-shipments'))
                                            ->icon('heroicon-o-paper-airplane'),
                                    ]),

                                Infolists\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.settings.entries.resupply-management'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('supplierWarehouses.name')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.settings.entries.resupply-from'))
                                            ->icon('heroicon-o-refresh')
                                            ->placeholder('—'),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.record-information.title'))
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.record-information.entries.created-at'))
                                    ->dateTime()
                                    ->icon('heroicon-m-calendar'),

                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.record-information.entries.created-by'))
                                    ->icon('heroicon-m-user'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.infolist.sections.record-information.entries.last-updated'))
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
            Pages\ViewWarehouse::class,
            Pages\EditWarehouse::class,
            Pages\ManageRoutes::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'view'   => Pages\ViewWarehouse::route('/{record}'),
            'edit'   => Pages\EditWarehouse::route('/{record}/edit'),
            'routes' => Pages\ManageRoutes::route('/{record}/routes'),
        ];
    }
}
