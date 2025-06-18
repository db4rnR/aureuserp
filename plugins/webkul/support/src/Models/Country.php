<?php

declare(strict_types=1);

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_id',
        'phone_code',
        'code',
        'name',
        'state_required',
        'zip_required',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'state_required' => 'boolean',
        'zip_required' => 'boolean',
    ];

    /**
     * Get the currency associated with the country.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Get all states for the country.
     */
    public function states(): HasMany
    {
        return $this->hasMany(State::class, 'country_id');
    }
}
