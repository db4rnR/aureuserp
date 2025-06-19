<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;

class JobPositionInterviewer extends Model
{
    public $timestamps = false;

    protected $table = 'recruitments_job_position_interviewers';

    protected $fillable = ['job_position_id', 'user_id'];

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class, 'job_position_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
