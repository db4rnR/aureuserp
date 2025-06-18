<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Employee\Models\Employee;
use Webkul\Employee\Models\EmployeeJobPosition as BaseJobPosition;
use Webkul\Employee\Models\Skill;
use Webkul\Partner\Models\Industry;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;

final class JobPosition extends BaseJobPosition
{
    /**
     * Create a new Eloquent model instance.
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->mergeFillable([
            'address_id',
            'manager_id',
            'industry_id',
            'recruiter_id',
            'no_of_hired_employee',
            'date_from',
            'date_to',
        ]);

        $this->mergeCasts([
            'date_from' => 'datetime',
            'date_to' => 'datetime',
        ]);

        parent::__construct($attributes);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'address_id')->where('sub_type', 'company');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_position_skills', 'job_position_id', 'skill_id');
    }

    public function interviewers()
    {
        return $this->belongsToMany(User::class, 'recruitments_job_position_interviewers', 'job_position_id', 'user_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function applications()
    {
        return $this->hasMany(Applicant::class, 'job_id');
    }

    protected static function boot(): void
    {
        parent::boot();

        self::updated(function ($jobPosition): void {
            cache()->forget("job_position_{$jobPosition->id}_employee_count");
            cache()->forget("job_position_{$jobPosition->id}_hired_count");
        });
    }

    private function noOfEmployee(): Attribute
    {
        return Attribute::make(
            get: fn () => once(fn () => $this->employees()
                ->where('is_active', true)
                ->count())
        );
    }

    private function noOfHiredEmployee(): Attribute
    {
        return Attribute::make(
            get: fn () => once(fn () => $this->applications()
                ->where(function ($query): void {
                    $query->whereNotNull('date_closed')
                        ->where('is_active', true);
                })
                ->count())
        );
    }

    private function expectedEmployees(): Attribute
    {
        return Attribute::make(
            get: function (): float|int|array {
                $currentEmployees = $this->getAttributeValue('no_of_employee');

                return $currentEmployees + ($this->no_of_recruitment ?? 0);
            }
        );
    }
}
