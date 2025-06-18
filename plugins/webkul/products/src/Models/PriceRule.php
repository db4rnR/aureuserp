<?php

declare(strict_types=1);

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Webkul\Product\Database\Factories\PriceRuleFactory;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

final class PriceRule extends Model implements Sortable
{
    use HasFactory, SoftDeletes, SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'products_price_rules';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sort',
        'currency_id',
        'company_id',
        'creator_id',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PriceRuleItem::class);
    }

    protected static function newFactory(): PriceRuleFactory
    {
        return PriceRuleFactory::new();
    }
}
