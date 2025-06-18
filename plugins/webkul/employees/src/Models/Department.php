<?php

declare(strict_types=1);

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;
use Webkul\Employee\Database\Factories\DepartmentFactory;
use Webkul\Field\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

final class Department extends Model
{
    use HasChatter, HasCustomFields, HasFactory, HasLogActivity, SoftDeletes;

    protected $table = 'employees_departments';

    protected $fillable = [
        'name',
        'manager_id',
        'company_id',
        'parent_id',
        'master_department_id',
        'complete_name',
        'parent_path',
        'creator_id',
        'color',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function masterDepartment(): BelongsTo
    {
        return $this->belongsTo(self::class, 'master_department_id');
    }

    public function jobPositions(): HasMany
    {
        return $this->hasMany(EmployeeJobPosition::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    protected static function newFactory(): DepartmentFactory
    {
        return DepartmentFactory::new();
    }

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($department): void {
            if (! self::validateNoRecursion($department)) {
                throw new InvalidArgumentException('Circular reference detected in department hierarchy');
            }
            self::handleDepartmentData($department);
        });

        self::updating(function ($department): void {
            if (! self::validateNoRecursion($department)) {
                throw new InvalidArgumentException('Circular reference detected in department hierarchy');
            }
            self::handleDepartmentData($department);
        });
    }

    private static function validateNoRecursion($department): bool
    {
        if (! $department->parent_id) {
            return true;
        }

        if ($department->exists && $department->id === $department->parent_id) {
            return false;
        }

        $visitedIds = [$department->exists ? $department->id : -1];
        $currentParentId = $department->parent_id;

        while ($currentParentId) {
            if (in_array($currentParentId, $visitedIds, true)) {
                return false;
            }

            $visitedIds[] = $currentParentId;
            $parent = self::find($currentParentId);

            if (! $parent) {
                break;
            }

            $currentParentId = $parent->parent_id;
        }

        return true;
    }

    private static function handleDepartmentData($department): void
    {
        if ($department->parent_id) {
            $parent = self::find($department->parent_id);
            $department->parent_path = $parent?->parent_path.$parent?->id.'/';

            $department->master_department_id = self::findTopLevelParentId($parent);
        } else {
            $department->parent_path = '/';
            $department->master_department_id = null;
        }

        $department->complete_name = self::getCompleteName($department);
    }

    private static function findTopLevelParentId($department)
    {
        $currentDepartment = $department;

        while ($currentDepartment->parent_id) {
            $currentDepartment = self::find($currentDepartment->parent_id);
        }

        return $currentDepartment->id;
    }

    private static function getCompleteName($department): string
    {
        $names = [];
        $names[] = $department->name;

        $currentDepartment = $department;
        while ($currentDepartment->parent_id) {
            $currentDepartment = self::find($currentDepartment->parent_id);
            array_unshift($names, $currentDepartment->name);
        }

        return implode(' / ', $names);
    }
}
