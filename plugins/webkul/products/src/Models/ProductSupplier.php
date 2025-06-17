<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

class ProductSupplier extends Model implements Sortable
{
    use SortableTrait;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'products_product_suppliers';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'sort',
        'delay',
        'product_name',
        'product_code',
        'starts_at',
        'ends_at',
        'min_qty',
        'price',
        'discount',
        'product_id',
        'partner_id',
        'currency_id',
        'company_id',
        'creator_id',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
    ];

    public $sortable = [
        'order_column_name'  => 'sort',
        'sort_when_creating' => true,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
