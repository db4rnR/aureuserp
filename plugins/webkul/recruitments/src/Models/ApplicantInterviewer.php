<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;

final class ApplicantInterviewer extends Model
{
    public $timestamps = false;

    protected $table = 'recruitments_applicant_interviewers';

    protected $fillable = [
        'applicant_id',
        'interviewer_id',
    ];
}
