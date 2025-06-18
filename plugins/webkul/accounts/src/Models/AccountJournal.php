<?php

declare(strict_types=1);

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Model;

final class AccountJournal extends Model
{
    public $timestamps = false;

    protected $table = 'accounts_account_journals';

    protected $fillable = [
        'account_id',
        'journal_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
