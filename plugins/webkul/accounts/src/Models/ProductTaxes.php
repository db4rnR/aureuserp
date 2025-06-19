<?php

declare(strict_types=1);

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;

class ProductTaxes extends Model
{
    public $timestamps = false;

    protected $table = 'accounts_product_taxes';

    protected $fillable = [
        'product_id',
        'tax_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }
}
