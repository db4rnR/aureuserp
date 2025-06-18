<?php

declare(strict_types=1);

namespace Webkul\Sale\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Invoice\Models\Category as BaseCategory;

final class Category extends BaseCategory
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
