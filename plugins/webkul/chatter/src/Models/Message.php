<?php

declare(strict_types=1);

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;
use Webkul\Support\Models\ActivityType;
use Webkul\Support\Models\Company;

final class Message extends Model
{
    protected $table = 'chatter_messages';

    protected $fillable = [
        'company_id',
        'activity_type_id',
        'messageable_type',
        'messageable_id',
        'type',
        'name',
        'subject',
        'body',
        'summary',
        'is_internal',
        'date_deadline',
        'pinned_at',
        'log_name',
        'event',
        'assigned_to',
        'causer_type',
        'causer_id',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
        'date_deadline' => 'date',
    ];

    public static function boot(): void
    {
        parent::boot();

        $user = filament()->auth()->user();

        if ($user) {
            self::creating(function ($data) use ($user): void {
                DB::transaction(function () use ($data, $user): void {
                    $data->causer_type = $user->getMorphClass();
                    $data->causer_id = $user->id;
                });
            });

            self::updating(function ($data) use ($user): void {
                $data->causer_type = $user->getMorphClass();
                $data->causer_id = $user->id;
            });
        }
    }

    public function messageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function causer()
    {
        return $this->morphTo();
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function setPropertiesAttribute($value): void
    {
        $this->attributes['properties'] = json_encode($value);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'message_id');
    }
}
