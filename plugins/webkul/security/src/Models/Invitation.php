<?php

declare(strict_types=1);

namespace Webkul\Security\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Invitation extends Model
{
    use HasFactory;

    protected $table = 'user_invitations';

    protected $guarded = [];
}
