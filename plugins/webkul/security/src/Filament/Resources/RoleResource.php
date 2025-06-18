<?php

declare(strict_types=1);

namespace Webkul\Security\Filament\Resources;

use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Forms\ShieldSelectAllToggle;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Webkul\Security\Filament\Resources\RoleResource\Pages\CreateRole;
use Webkul\Security\Filament\Resources\RoleResource\Pages\EditRole;
use Webkul\Security\Filament\Resources\RoleResource\Pages\ListRoles;
use Webkul\Security\Filament\Resources\RoleResource\Pages\ViewRole;

final class RoleResource extends Resource implements HasShieldPermissions
{
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    private static $permissionsCollection;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('filament-shield::filament-shield.field.name'))
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('guard_name')
                                    ->label(__('filament-shield::filament-shield.field.guard_name'))
                                    ->default(Utils::getFilamentAuthGuard())
                                    ->nullable()
                                    ->maxLength(255),

                                ShieldSelectAllToggle::make('select_all')
                                    ->onIcon('heroicon-s-shield-check')
                                    ->offIcon('heroicon-s-shield-exclamation')
                                    ->label(__('filament-shield::filament-shield.field.select_all.name'))
                                    ->helperText(fn (): HtmlString => new HtmlString(__('filament-shield::filament-shield.field.select_all.message')))
                                    ->dehydrated(fn ($state): bool => $state),

                            ])
                            ->columns([
                                'sm' => 2,
                                'lg' => 3,
                            ]),
                    ]),
                Tabs::make('Permissions')
                    ->contained()
                    ->tabs([
                        self::getTabFormComponentForResources(),
                        self::getTabFormComponentForPage(),
                        self::getTabFormComponentForWidget(),
                        self::getTabFormComponentForCustomPermissions(),
                    ])
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->badge()
                    ->label(__('filament-shield::filament-shield.column.name'))
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->colors(['primary'])
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->badge()
                    ->label(__('filament-shield::filament-shield.column.guard_name')),
                TextColumn::make('permissions_count')
                    ->badge()
                    ->label(__('filament-shield::filament-shield.column.permissions'))
                    ->counts('permissions')
                    ->colors(['success']),
                TextColumn::make('updated_at')
                    ->label(__('filament-shield::filament-shield.column.updated_at'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (Model $record): bool => $record->name === config('filament-shield.panel_user.name')),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'view' => ViewRole::route('/{record}'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }

    public static function getCluster(): ?string
    {
        return Utils::getResourceCluster() ?? self::$cluster;
    }

    public static function getModel(): string
    {
        return Utils::getRoleModel();
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.roles');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Utils::isResourceNavigationRegistered();
    }

    public static function getNavigationGroup(): ?string
    {
        return Utils::isResourceNavigationGroupEnabled()
            ? __('filament-shield::filament-shield.nav.group')
            : '';
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-shield::filament-shield.nav.role.label');
    }

    public static function getNavigationIcon(): string
    {
        return __('filament-shield::filament-shield.nav.role.icon');
    }

    public static function getNavigationSort(): ?int
    {
        return Utils::getResourceNavigationSort();
    }

    public static function getSlug(?Panel $panel = null): string
    {
        return Utils::getResourceSlug();
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? (string) (self::getEloquentQuery()->count())
            : null;
    }

    public static function isScopedToTenant(): bool
    {
        return Utils::isScopedToTenant();
    }

    public static function canGloballySearch(): bool
    {
        return Utils::isResourceGloballySearchable() && count(self::getGloballySearchableAttributes()) && self::canViewAny();
    }

    public static function getPluginResourceEntitiesSchema(): ?array
    {
        return collect(self::getPluginResources())
            ->sortKeys()
            ->map(fn ($plugin, $key): Section => Section::make($key)
                ->collapsible()
                ->schema([
                    Grid::make()
                        ->schema(fn () => collect($plugin)
                            ->map(function (array $entity): Fieldset {
                                $fieldsetLabel = (string) (
                                    self::shield()->hasLocalizedPermissionLabels()
                                        ? FilamentShield::getLocalizedResourceLabel($entity['fqcn'])
                                        : $entity['model']
                                );

                                return Fieldset::make($fieldsetLabel)
                                    ->schema([
                                        Placeholder::make('model')
                                            ->inlineLabel()
                                            ->content(fn (): HtmlString => new HtmlString('<span style="word-break: break-word;">'.$entity['fqcn'].'</span>'))
                                            ->columnSpan(2)
                                            ->visible(config('filament-shield.shield_resource.show_model_path', false)),
                                        self::getCheckBoxListComponentForResource($entity),
                                    ])
                                    ->columnSpan(self::shield()->getSectionColumnSpan());
                            })
                            ->toArray())
                        ->columns(self::shield()->getGridColumns()),
                ]))
            ->toArray();
    }

    /**
     * Transform filament resources to key value pair for shield
     */
    public static function getPluginResources(): ?array
    {
        return collect(self::getResources())
            ->groupBy(fn ($value, $key): string => explode('\\', (string) $key)[1] ?? 'Unknown')
            ->toArray();
    }

    /**
     * Transform filament resources to key value pair for shield
     */
    public static function getResources(): ?array
    {
        $resources = Filament::getResources();
        if (Utils::discoverAllResources()) {
            $resources = [];
            foreach (Filament::getPanels() as $panel) {
                $resources = array_merge($resources, $panel->getResources());
            }
            $resources = array_unique($resources);
        }

        return collect($resources)
            ->reject(function ($resource) {
                if (! $resource::shouldRegisterNavigation()) {
                    return true;
                }

                if ($resource === \BezhanSalleh\FilamentShield\Resources\RoleResource::class) {
                    return true;
                }

                if (Utils::isGeneralExcludeEnabled()) {
                    return in_array(
                        Str::of($resource)->afterLast('\\'),
                        Utils::getExcludedResouces(), true
                    );
                }
            })
            ->mapWithKeys(function ($resource) {
                $name = FilamentShield::getPermissionIdentifier($resource);

                return [
                    $resource => [
                        'resource' => "{$name}",
                        'model' => str($resource::getModel())->afterLast('\\')->toString(),
                        'fqcn' => $resource,
                    ],
                ];
            })
            ->sortKeys()
            ->toArray();
    }

    public static function getResourceTabBadgeCount(): ?int
    {
        return collect(FilamentShield::getResources())
            ->map(fn ($resource): int => count(self::getResourcePermissionOptions($resource)))
            ->sum();
    }

    public static function getResourcePermissionOptions(array $entity): array
    {
        return collect(Utils::getResourcePermissionPrefixes($entity['fqcn']))
            ->flatMap(function (string $permission) use ($entity) {
                $name = $permission.'_'.$entity['resource'];
                $label = self::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedResourcePermissionLabel($permission)
                    : $name;

                return [
                    $name => $label,
                ];
            })
            ->toArray();
    }

    public static function setPermissionStateForRecordPermissions(Component $component, string $operation, array $permissions, ?Model $record): void
    {

        if (in_array($operation, ['edit', 'view'], true)) {

            if (blank($record)) {
                return;
            }
            if ($component->isVisible() && count($permissions) > 0) {
                $component->state(
                    collect($permissions)
                        /** @phpstan-ignore-next-line */
                        ->filter(fn ($value, $key) => $record->checkPermissionTo($key))
                        ->keys()
                        ->toArray()
                );
            }
        }
    }

    public static function getPageOptions(): array
    {
        return collect(FilamentShield::getPages())
            ->flatMap(fn ($page) => [
                $page['permission'] => self::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedPageLabel($page['class'])
                    : $page['permission'],
            ])
            ->toArray();
    }

    public static function getWidgetOptions(): array
    {
        return collect(FilamentShield::getWidgets())
            ->flatMap(fn ($widget) => [
                $widget['permission'] => self::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedWidgetLabel($widget['class'])
                    : $widget['permission'],
            ])
            ->toArray();
    }

    public static function getCustomPermissionOptions(): ?array
    {
        return FilamentShield::getCustomPermissions()
            ->mapWithKeys(fn ($customPermission) => [
                $customPermission => self::shield()->hasLocalizedPermissionLabels() ? str($customPermission)->headline()->toString() : $customPermission,
            ])
            ->toArray();
    }

    public static function getTabFormComponentForResources(): Component
    {
        return self::shield()->hasSimpleResourcePermissionView()
            ? self::getTabFormComponentForSimpleResourcePermissionsView()
            : Tab::make('resources')
                ->label(__('filament-shield::filament-shield.resources'))
                ->visible(fn (): bool => Utils::isResourceEntityEnabled())
                ->badge(self::getResourceTabBadgeCount())
                ->schema(self::getPluginResourceEntitiesSchema());
    }

    public static function getCheckBoxListComponentForResource(array $entity): Component
    {
        $permissionsArray = self::getResourcePermissionOptions($entity);

        return self::getCheckboxListFormComponent($entity['resource'], $permissionsArray, false);
    }

    public static function getTabFormComponentForPage(): Component
    {
        $options = self::getPageOptions();
        $count = count($options);

        return Tab::make('pages')
            ->label(__('filament-shield::filament-shield.pages'))
            ->visible(fn (): bool => Utils::isPageEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                self::getCheckboxListFormComponent('pages_tab', $options),
            ]);
    }

    public static function getTabFormComponentForWidget(): Component
    {
        $options = self::getWidgetOptions();
        $count = count($options);

        return Tab::make('widgets')
            ->label(__('filament-shield::filament-shield.widgets'))
            ->visible(fn (): bool => Utils::isWidgetEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                self::getCheckboxListFormComponent('widgets_tab', $options),
            ]);
    }

    public static function getTabFormComponentForCustomPermissions(): Component
    {
        $options = self::getCustomPermissionOptions();
        $count = count($options);

        return Tab::make('custom')
            ->label(__('vendor.filament-shield.custom'))
            ->visible(fn (): bool => Utils::isCustomPermissionEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                self::getCheckboxListFormComponent('custom_permissions', $options),
            ]);
    }

    public static function getTabFormComponentForSimpleResourcePermissionsView(): Component
    {
        $options = FilamentShield::getAllResourcePermissions();
        $count = count($options);

        return Tab::make('resources')
            ->label(__('filament-shield::filament-shield.resources'))
            ->visible(fn (): bool => Utils::isResourceEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                self::getCheckboxListFormComponent('resources_tab', $options),
            ]);
    }

    public static function getCheckboxListFormComponent(string $name, array $options, bool $searchable = true): Component
    {
        return CheckboxList::make($name)
            ->label('')
            ->options(fn (): array => $options)
            ->searchable($searchable)
            ->afterStateHydrated(
                fn (Component $component, string $operation, ?Model $record) => self::setPermissionStateForRecordPermissions(
                    component: $component,
                    operation: $operation,
                    permissions: $options,
                    record: $record
                )
            )
            ->dehydrated(fn ($state): bool => ! blank($state))
            ->bulkToggleable()
            ->gridDirection('row')
            ->columns(self::shield()->getCheckboxListColumns())
            ->columnSpan(self::shield()->getCheckboxListColumnSpan());
    }

    public static function shield(): FilamentShieldPlugin
    {
        return FilamentShieldPlugin::get();
    }
}
