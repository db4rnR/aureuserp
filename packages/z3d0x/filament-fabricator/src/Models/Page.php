<?php

declare(strict_types=1);

namespace Z3d0X\FilamentFabricator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Z3d0X\FilamentFabricator\Models\Concerns\HandlesPageUrls;
use Z3d0X\FilamentFabricator\Models\Contracts\Page as Contract;

class Page extends Model implements Contract
{
    use HandlesPageUrls;

    protected $guarded = [];

    protected $casts = [
        'blocks' => 'array',
        'parent_id' => 'integer',
    ];

    public function __construct(array $attributes = [])
    {
        if (blank($this->table)) {
            $this->setTable(config('filament-fabricator.table_name', 'pages'));
        }

        parent::__construct($attributes);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function allChildren(): HasMany
    {
        return $this->children()
            ->select('id', 'slug', 'title', 'parent_id')
            ->with('allChildren:id,slug,title,parent_id');
    }
}
