<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantApplicantCategory extends Model
{
    public $timestamps = false;

    protected $table = 'recruitments_applicant_applicant_categories';

    protected $fillable = ['applicant_id', 'applicant_category_id'];
}
