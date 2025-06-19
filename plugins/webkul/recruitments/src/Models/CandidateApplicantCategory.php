<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateApplicantCategory extends Model
{
    public $timestamps = false;

    protected $table = 'recruitments_candidate_applicant_categories';

    protected $fillable = ['candidate_id', 'applicant_category_id'];
}
