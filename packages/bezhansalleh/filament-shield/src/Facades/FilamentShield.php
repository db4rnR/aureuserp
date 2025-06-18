<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentShield\Facades;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \BezhanSalleh\FilamentShield\FilamentShield configurePermissionIdentifierUsing(Closure $callback)
 * @method static string getPermissionIdentifier(string $resource)
 * @method static void generateForResource(array $entity)
 * @method static void generateForPage(string $page)
 * @method static void generateForWidget(string $widget)
 * @method static void createRole(string|null $name = null)
 * @method static array|null getResources()
 * @method static string getLocalizedResourceLabel(string $entity)
 * @method static string getLocalizedResourcePermissionLabel(string $permission)
 * @method static array|null getPages()
 * @method static string getLocalizedPageLabel(string $page)
 * @method static array|null getWidgets()
 * @method static string getLocalizedWidgetLabel(string $widget)
 * @method static array getAllResourcePermissions()
 * @method static Collection|null getCustomPermissions()
 * @method static mixed evaluate(mixed $value, array $namedInjections = [], array $typedInjections = [])
 *
 * @see \BezhanSalleh\FilamentShield\FilamentShield
 */
final class FilamentShield extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-shield';
    }
}
