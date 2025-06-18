<?php

declare(strict_types=1);

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Webkul\Product\Database\Factories\PackagingFactory;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

final class Packaging extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'products_packagings';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'barcode',
        'qty',
        'sort',
        'product_id',
        'company_id',
        'creator_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): PackagingFactory
    {
        return PackagingFactory::new();
    }
}
