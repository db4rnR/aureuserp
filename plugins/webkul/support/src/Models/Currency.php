<?php

declare(strict_types=1);

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;

final class Currency extends Model
{
    protected $fillable = [
        'name',
        'symbol',
        'iso_numeric',
        'decimal_places',
        'full_name',
        'rounding',
        'active',
    ];
}
