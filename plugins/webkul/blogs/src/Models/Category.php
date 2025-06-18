<?php

declare(strict_types=1);

namespace Webkul\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Webkul\Blog\Database\Factories\CategoryFactory;
use Webkul\Security\Models\User;

final class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'blogs_categories';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sub_title',
        'slug',
        'image',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'creator_id',
    ];

    /**
     * Get image url for the product image.
     */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return null;
        }

        return Storage::url($this->image);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
