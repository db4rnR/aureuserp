<?php

declare(strict_types=1);

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

final class Plugin extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];

    protected $fillable = [
        'name',
        'author',
        'summary',
        'description',
        'latest_version',
        'license',
        'is_active',
        'is_installed',
        'sort',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'plugin_dependencies',
            'plugin_id',
            'dependency_id'
        );
    }

    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'plugin_dependencies',
            'dependency_id',
            'plugin_id'
        );
    }
}
