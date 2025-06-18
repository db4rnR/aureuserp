<?php

declare(strict_types=1);

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;

final class JobPositionSkill extends Model
{
    public $timestamps = false;

    protected $table = 'job_position_skills';

    protected $fillable = ['job_position_id', 'skill_id'];

    public function jobPosition()
    {
        return $this->belongsTo(EmployeeJobPosition::class, 'job_position_id');
    }

    public function skill()
    {
        return $this->belongsTo(EmployeeSkill::class, 'skill_id');
    }
}
