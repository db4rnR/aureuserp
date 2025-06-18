<?php

declare(strict_types=1);

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Model;

final class AccountTax extends Model
{
    public $timestamps = false;

    protected $table = 'accounts_account_taxes';

    protected $fillable = [
        'account_id',
        'tax_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
