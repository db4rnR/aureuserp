<?php

namespace Webkul\Sale\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Invoice\Models\Category as BaseCategory;

class Category extends BaseCategory
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
