<?php

declare(strict_types=1);

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Model;

class TaxTaxes extends Model
{
    public $timestamps = false;

    protected $table = 'accounts_tax_taxes';

    protected $fillable = ['parent_tax_id', 'child_tax_id'];

    public function parentTax()
    {
        return $this->belongsTo(Tax::class, 'parent_tax_id');
    }

    public function childTax()
    {
        return $this->belongsTo(Tax::class, 'child_tax_id');
    }
}
