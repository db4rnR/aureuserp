<?php

declare(strict_types=1);

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Model;

final class AccountAccountTag extends Model
{
    public $timestamps = false;

    protected $table = 'accounts_account_account_tags';

    protected $fillable = [
        'account_id',
        'account_tag_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function accountTag()
    {
        return $this->belongsTo(Tag::class);
    }
}
