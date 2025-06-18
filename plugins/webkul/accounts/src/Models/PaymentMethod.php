<?php

declare(strict_types=1);

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;

final class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'accounts_payment_methods';

    protected $fillable = ['code', 'payment_type', 'name', 'created_by'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accountMovePayment()
    {
        return $this->hasMany(Move::class, 'payment_id');
    }
}
