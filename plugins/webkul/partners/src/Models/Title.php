<?php

declare(strict_types=1);

namespace Webkul\Partner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Partner\Database\Factories\TitleFactory;
use Webkul\Security\Models\User;

final class Title extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'partners_titles';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'short_name',
        'creator_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): TitleFactory
    {
        return TitleFactory::new();
    }
}
