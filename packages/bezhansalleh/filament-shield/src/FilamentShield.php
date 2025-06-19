<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentShield;

use BezhanSalleh\FilamentShield\Commands\GenerateCommand;
use BezhanSalleh\FilamentShield\Commands\InstallCommand;
use BezhanSalleh\FilamentShield\Commands\PublishCommand;
use BezhanSalleh\FilamentShield\Commands\SetupCommand;
use BezhanSalleh\FilamentShield\Support\Utils;
use Closure;
use Filament\Facades\Filament;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Widgets\TableWidget;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class FilamentShield
{
    use EvaluatesClosures;

    public ?Collection $customPermissions = null;

    protected ?Closure $configurePermissionIdentifierUsing = null;

    public static function generateForPage(string $page): void
    {
        if (Utils::isPageEntityEnabled()) {
            $permission = Utils::getPermissionModel()::firstOrCreate(
                ['name' => $page],
                ['guard_name' => Utils::getFilamentAuthGuard()]
            )->name;

            self::giveSuperAdminPermission($permission);
        }
    }

    public static function generateForWidget(string $widget): void
    {
        if (Utils::isWidgetEntityEnabled()) {
            $permission = Utils::getPermissionModel()::firstOrCreate(
                ['name' => $widget],
                ['guard_name' => Utils::getFilamentAuthGuard()]
            )->name;

            self::giveSuperAdminPermission($permission);
        }
    }

    public static function createRole(?string $name = null, int|string|null $tenantId = null): Role
    {
        if (Utils::isTenancyEnabled()) {
            return Utils::getRoleModel()::firstOrCreate(
                [
                    'name' => $name ?? Utils::getSuperAdminName(),
                    Utils::getTenantModelForeignKey() => $tenantId,
                ],
                ['guard_name' => Utils::getFilamentAuthGuard()]
            );
        }

        return Utils::getRoleModel()::firstOrCreate(
            ['name' => $name ?? Utils::getSuperAdminName()],
            ['guard_name' => Utils::getFilamentAuthGuard()]
        );
    }

    /**
     * Get the localized resource label
     */
    public static function getLocalizedResourceLabel(string $entity): string
    {
        $resources = Filament::getResources();
        if (Utils::discoverAllResources()) {
            $resources = [];
            foreach (Filament::getPanels() as $panel) {
                $resources = array_merge($resources, $panel->getResources());
            }
            $resources = array_unique($resources);
        }
        $label = collect($resources)->filter(function ($resource) use ($entity) {
            return $resource === $entity;
        })->first()::getModelLabel();

        return str($label)->headline()->toString();
    }

    /**
     * Get the localized resource permission label
     */
    public static function getLocalizedResourcePermissionLabel(string $permission): string
    {
        return Lang::has("filament-shield::filament-shield.resource_permission_prefixes_labels.$permission", app()->getLocale())
            ? __("filament-shield::filament-shield.resource_permission_prefixes_labels.$permission")
            : Str::of($permission)->headline();
    }

    /**
     * Transform filament pages to key value pair for shield
     */
    public static function getPages(): ?array
    {
        $pages = Filament::getPages();

        if (Utils::discoverAllPages()) {
            $pages = [];
            foreach (Filament::getPanels() as $panel) {
                $pages = array_merge($pages, $panel->getPages());
            }
            $pages = array_unique($pages);
        }

        $clusters = collect($pages)
            ->map(fn ($page) => $page::getCluster())
            ->reject(fn ($cluster) => is_null($cluster))
            ->unique()
            ->values()
            ->toArray();

        return collect($pages)
            ->reject(function ($page) use ($clusters) {
                if (in_array($page, $clusters, true)) {
                    return true;
                }

                if (Utils::isGeneralExcludeEnabled()) {
                    return in_array(Str::afterLast($page, '\\'), Utils::getExcludedPages(), true);
                }
            })
            ->mapWithKeys(function ($page) {
                $permission = Str::of(class_basename($page))
                    ->prepend(
                        Str::of(Utils::getPagePermissionPrefix())
                            ->append('_')
                            ->toString()
                    )
                    ->toString();

                return [
                    $permission => [
                        'class' => $page,
                        'permission' => $permission,
                    ],
                ];
            })
            ->toArray();
    }

    /**
     * Get localized page label
     */
    public static function getLocalizedPageLabel(string $page): string
    {
        $pageInstance = app()->make($page);

        return $pageInstance->getTitle()
                ?? $pageInstance->getHeading()
                ?? $pageInstance->getNavigationLabel()
                ?? '';
    }

    /**
     * Transform filament widgets to key value pair for shield
     */
    public static function getWidgets(): ?array
    {
        $widgets = Filament::getWidgets();
        if (Utils::discoverAllWidgets()) {
            $widgets = [];
            foreach (Filament::getPanels() as $panel) {
                $widgets = array_merge($widgets, $panel->getWidgets());
            }
            $widgets = array_unique($widgets);
        }

        return collect($widgets)
            ->reject(function ($widget) {
                if (Utils::isGeneralExcludeEnabled()) {
                    return in_array(
                        needle: str(
                            static::getWidgetInstanceFromWidgetConfiguration($widget)
                        )
                            ->afterLast('\\')
                            ->toString(),
                        haystack: Utils::getExcludedWidgets(), true
                    );
                }
            })
            ->mapWithKeys(function ($widget) {
                $permission = Str::of(class_basename(static::getWidgetInstanceFromWidgetConfiguration($widget)))
                    ->prepend(
                        Str::of(Utils::getWidgetPermissionPrefix())
                            ->append('_')
                            ->toString()
                    )
                    ->toString();

                return [
                    $permission => [
                        'class' => static::getWidgetInstanceFromWidgetConfiguration($widget),
                        'permission' => $permission,
                    ],
                ];
            })
            ->toArray();
    }

    /**
     * Get localized widget label
     */
    public static function getLocalizedWidgetLabel(string $widget): string
    {
        $widgetInstance = app()->make($widget);

        return match (true) {
            $widgetInstance instanceof TableWidget => (string) invade($widgetInstance)->makeTable()->getHeading(),
            self::hasValidHeading($widgetInstance) => (string) invade($widgetInstance)->getHeading(),
            default => str($widget)
                ->afterLast('\\')
                ->headline()
                ->toString(),
        };
    }

    /**
     * Indicate if destructive Shield commands should be prohibited.
     *
     * Prohibits: shield:setup, shield:install, and shield:generate
     */
    public static function prohibitDestructiveCommands(bool $prohibit = true): void
    {
        GenerateCommand::prohibit($prohibit);
        InstallCommand::prohibit($prohibit);
        PublishCommand::prohibit($prohibit);
        SetupCommand::prohibit($prohibit);
    }

    public function configurePermissionIdentifierUsing(Closure $callback): static
    {
        $this->configurePermissionIdentifierUsing = $callback;

        return $this;
    }

    public function getPermissionIdentifier(string $resource): string
    {
        if ($this->configurePermissionIdentifierUsing) {

            $identifier = $this->evaluate(
                value: $this->configurePermissionIdentifierUsing,
                namedInjections: [
                    'resource' => $resource,
                ]
            );

            if (Str::contains($identifier, '_')) {
                throw new InvalidArgumentException("Permission identifier `$identifier` for `$resource` cannot contain underscores.");
            }

            return $identifier;
        }

        return $this->getDefaultPermissionIdentifier($resource);
    }

    public function generateForResource(array $entity): void
    {
        $resourceByFQCN = $entity['fqcn'];
        $permissionPrefixes = Utils::getResourcePermissionPrefixes($resourceByFQCN);

        if (Utils::isResourceEntityEnabled()) {
            $permissions = collect();
            collect($permissionPrefixes)
                ->each(function ($prefix) use ($entity, $permissions): void {
                    $permissions->push(Utils::getPermissionModel()::firstOrCreate(
                        ['name' => $prefix.'_'.$entity['resource']],
                        ['guard_name' => Utils::getFilamentAuthGuard()]
                    ));
                });

            static::giveSuperAdminPermission($permissions);
        }
    }

    /**
     * Transform filament resources to key value pair for shield
     */
    public function getResources(): ?array
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
                if (Utils::isGeneralExcludeEnabled()) {
                    return in_array(
                        Str::of($resource)->afterLast('\\'),
                        Utils::getExcludedResouces(), true
                    );
                }
            })
            ->mapWithKeys(function ($resource) {
                $name = $this->getPermissionIdentifier($resource);

                return [
                    $name => [
                        'resource' => "{$name}",
                        'model' => str($resource::getModel())->afterLast('\\')->toString(),
                        'fqcn' => $resource,
                    ],
                ];
            })
            ->sortKeys()
            ->toArray();
    }

    public function getAllResourcePermissions(): array
    {
        return collect($this->getResources())
            ->map(function ($resourceEntity) {
                return collect(
                    Utils::getResourcePermissionPrefixes($resourceEntity['fqcn'])
                )
                    ->flatMap(function ($permission) use ($resourceEntity) {
                        $name = $permission.'_'.$resourceEntity['resource'];
                        $permissionLabel = FilamentShieldPlugin::get()->hasLocalizedPermissionLabels()
                            ? str(static::getLocalizedResourcePermissionLabel($permission))
                                ->prepend(
                                    str($resourceEntity['fqcn']::getPluralModelLabel())
                                        ->title()
                                        ->append(' - ')
                                        ->toString()
                                )
                                ->toString()
                            : $name;
                        $resourceLabel = FilamentShieldPlugin::get()->hasLocalizedPermissionLabels()
                            ? static::getLocalizedResourceLabel($resourceEntity['fqcn'])
                            : $resourceEntity['model'];

                        return [
                            $name => $permissionLabel,
                        ];
                    })
                    ->toArray();
            })
            ->sortKeys()
            ->collapse()
            ->toArray();
    }

    public function getCustomPermissions(): ?Collection
    {

        if (blank($this->customPermissions)) {
            $query = Utils::getPermissionModel()::query();
            $this->customPermissions = $query
                ->select('name')
                ->whereNotIn(DB::raw('lower(name)'), $this->getEntitiesPermissions())
                ->pluck('name');
        }

        return $this->customPermissions;
    }

    protected static function giveSuperAdminPermission(string|array|Collection $permissions): void
    {
        if (! Utils::isSuperAdminDefinedViaGate() && Utils::isSuperAdminEnabled()) {
            $superAdmin = static::createRole();

            $superAdmin->givePermissionTo($permissions);

            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    protected static function getWidgetInstanceFromWidgetConfiguration(string|WidgetConfiguration $widget): string
    {
        return $widget instanceof WidgetConfiguration
            ? $widget->widget
            : $widget;
    }

    protected function getDefaultPermissionIdentifier(string $resource): string
    {
        return Str::of($resource)
            ->afterLast('Resources\\')
            ->beforeLast('Resource')
            ->replace('\\', '')
            ->snake()
            ->replace('_', '::');
    }

    protected function getEntitiesPermissions(): ?array
    {
        return collect($this->getAllResourcePermissions())->keys()
            ->merge(collect($this->getPages())->map->permission->keys())
            ->merge(collect($this->getWidgets())->map->permission->keys())
            ->map(fn ($permission) => str($permission)->lower()->toString())
            ->values()
            ->unique()
            ->toArray();
    }

    private static function hasValidHeading($widgetInstance): bool
    {
        return $widgetInstance instanceof Widget
            && method_exists($widgetInstance, 'getHeading')
            && filled(invade($widgetInstance)->getHeading());
    }
}