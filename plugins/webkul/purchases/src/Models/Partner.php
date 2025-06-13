<?php

namespace Webkul\Purchase\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Account\Models\Move;
use Webkul\Account\Models\Partner as BasePartner;

class Partner extends BasePartner
{
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function accountMoves(): HasMany
    {
        return $this->hasMany(Move::class);
    }
}
