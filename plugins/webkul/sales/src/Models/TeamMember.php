<?php

declare(strict_types=1);

namespace Webkul\Sale\Models;

use Illuminate\Database\Eloquent\Model;

final class TeamMember extends Model
{
    public $timestamps = false;

    protected $table = 'sales_team_members';

    protected $fillable = [
        'team_id',
        'user_id',
    ];
}
