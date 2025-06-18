<?php

declare(strict_types=1);

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class AccountPaymentRegisterMoveLine extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'accounts_account_payment_register_move_lines';

    protected $fillable = [
        'payment_register_id',
        'move_line_id',
    ];

    public function paymentRegister()
    {
        return $this->belongsTo(PaymentRegister::class, 'payment_register_id');
    }

    public function moveLine()
    {
        return $this->belongsTo(MoveLine::class, 'move_line_id');
    }
}
