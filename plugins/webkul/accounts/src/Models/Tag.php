<?php

declare(strict_types=1);

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Country;

final class Tag extends Model
{
    use HasFactory;

    protected $table = 'accounts_account_tags';

    protected $fillable = [
        'color',
        'country_id',
        'creator_id',
        'applicability',
        'name',
        'tax_negate',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
