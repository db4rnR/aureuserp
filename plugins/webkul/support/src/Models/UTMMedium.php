<?php

declare(strict_types=1);

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;

final class UTMMedium extends Model
{
    protected $table = 'utm_mediums';

    protected $fillable = ['name', 'creator_id'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
