<?php

declare(strict_types=1);

namespace Webkul\TableViews\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;

final class TableViewFavorite extends Model
{
    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'is_favorite',
        'view_type',
        'view_key',
        'filterable_type',
        'user_id',
    ];

    /**
     * Get the user that owns the saved filter.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
