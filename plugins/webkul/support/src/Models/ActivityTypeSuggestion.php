<?php

declare(strict_types=1);

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;

final class ActivityTypeSuggestion extends Model
{
    public $timestamps = false;

    protected $table = 'activity_type_suggestions';

    protected $fillable = [
        'activity_type_id',
        'suggested_activity_type_id',
    ];

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function suggestedActivityType()
    {
        return $this->belongsTo(ActivityType::class, 'suggested_activity_type_id');
    }
}
