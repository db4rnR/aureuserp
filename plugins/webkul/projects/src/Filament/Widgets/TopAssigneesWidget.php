<?php

declare(strict_types=1);

namespace Webkul\Project\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Webkul\Project\Models\Timesheet;

class TopAssigneesWidget extends BaseWidget
{
    use HasWidgetShield, InteractsWithPageFilters;

    private static ?string $pollingInterval = '15s';

    public function getHeading(): string|Htmlable|null
    {
        return __('projects::filament/widgets/top-assignees.heading');
    }

    public function getTableRecordKey(Model|array $record): string
    {
        return (string) $record->project_id;
    }

    protected function getTableQuery(): Builder
    {
        $query = Timesheet::query();

        if (! empty($this->pageFilters['selectedProjects'])) {
            $query->whereIn('project_id', $this->pageFilters['selectedProjects']);
        }

        if (! empty($this->pageFilters['selectedAssignees'])) {
            $query->whereIn('user_id', $this->pageFilters['selectedAssignees']);
        }

        if (! empty($this->pageFilters['selectedPartners'])) {
            $query->whereIn('analytic_records.partner_id', $this->pageFilters['selectedPartners']);
        }

        $startDate = is_null($this->pageFilters['startDate'] ?? null) ?
            null :
            Carbon::parse($this->pageFilters['startDate']);

        $endDate = is_null($this->pageFilters['endDate'] ?? null) ?
            now() :
            Carbon::parse($this->pageFilters['endDate']);

        return $query
            ->join('users', 'users.id', '=', 'analytic_records.user_id')
            ->selectRaw('
                user_id,
                users.name as user_name,
                SUM(unit_amount) as total_hours,
                COUNT(DISTINCT task_id) as total_tasks
            ')
            ->whereBetween('analytic_records.created_at', [$startDate, $endDate])
            ->groupBy('user_id')
            ->orderByDesc('total_hours')
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('user_name')->label(__('projects::filament/widgets/top-assignees.table-columns.user'))
                ->sortable(),
            TextColumn::make('total_hours')->label(__('projects::filament/widgets/top-assignees.table-columns.hours-spent'))
                ->sortable(),
            TextColumn::make('total_tasks')->label(__('projects::filament/widgets/top-assignees.table-columns.tasks'))
                ->sortable(),
        ];
    }
}
