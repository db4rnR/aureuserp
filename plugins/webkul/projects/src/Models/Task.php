<?php

declare(strict_types=1);

namespace Webkul\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kirschbaum\Commentions\Contracts\Commentable;
use Kirschbaum\Commentions\HasComments;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;
use Webkul\Field\Traits\HasCustomFields;
use Webkul\Partner\Models\Partner;
use Webkul\Project\Database\Factories\TaskFactory;
use Webkul\Security\Models\Scopes\UserPermissionScope;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class Task extends Model implements Commentable, Sortable
{
    use HasChatter, HasComments, HasCustomFields, HasFactory, HasLogActivity, SoftDeletes, SortableTrait;

    public string $recordTitleAttribute = 'title';

    public array $sortable = [
        'order_column_name' => 'sort',
        'sort_when_creating' => true,
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'projects_tasks';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'color',
        'priority',
        'state',
        'tags',
        'sort',
        'is_active',
        'is_recurring',
        'deadline',
        'working_hours_open',
        'working_hours_close',
        'allocated_hours',
        'remaining_hours',
        'effective_hours',
        'total_hours_spent',
        'subtask_effective_hours',
        'overtime',
        'progress',
        'stage_id',
        'project_id',
        'partner_id',
        'parent_id',
        'company_id',
        'creator_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'deadline' => 'datetime',
        'is_active' => 'boolean',
        'tags' => 'array',
        'priority' => 'boolean',
        'is_recurring' => 'boolean',
        'working_hours_open' => 'float',
        'working_hours_close' => 'float',
        'allocated_hours' => 'float',
        'remaining_hours' => 'float',
        'effective_hours' => 'float',
        'total_hours_spent' => 'float',
        'overtime' => 'float',
    ];

    protected array $logAttributes = [
        'title',
        'description',
        'color',
        'priority',
        'state',
        'tags',
        'sort',
        'is_active',
        'is_recurring',
        'deadline',
        'allocated_hours',
        'stage.name' => 'Stage',
        'project.name' => 'Project',
        'partner.name' => 'Partner',
        'parent.title' => 'Parent',
        'company.name' => 'Company',
        'creator.name' => 'Creator',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function subTasks(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(TaskStage::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'projects_task_users');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'projects_task_tag', 'task_id', 'tag_id');
    }

    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }

    protected static function booted(): void
    {
        self::addGlobalScope(new UserPermissionScope('users'));
    }

    /**
     * Bootstrap any application services.
     */
    protected static function boot(): void
    {
        parent::boot();

        self::updated(function ($task): void {
            $task->timesheets()->update([
                'project_id' => $task->project_id,
                'partner_id' => $task->partner_id ?? $task->project?->partner_id,
                'company_id' => $task->company_id ?? $task->project?->company_id,
            ]);
        });
    }

    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }
}
