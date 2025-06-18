<?php

declare(strict_types=1);

namespace Webkul\Project\Filament\Resources\TaskResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Enums\TaskState;
use Webkul\Project\Filament\Resources\TaskResource;
use Webkul\Project\Filament\Widgets\StatsOverviewWidget;
use Webkul\TableViews\Filament\Components\PresetView;
use Webkul\TableViews\Filament\Concerns\HasTableViews;

final class ListTasks extends ListRecords
{
    use HasTableViews;

    protected static string $resource = TaskResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            StatsOverviewWidget::make(),
        ];
    }

    public function table(Table $table): Table
    {
        $table = parent::table($table)
            ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('parent_id'));

        return $table;
    }

    public function getPresetTableViews(): array
    {
        return [
            'open_tasks' => PresetView::make(__('projects::filament/resources/task/pages/list-tasks.tabs.open-tasks'))
                ->icon('heroicon-s-bolt')
                ->favorite()
                ->default()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotIn('state', [
                    TaskState::CANCELLED,
                    TaskState::DONE,
                ])),

            'my_tasks' => PresetView::make(__('projects::filament/resources/task/pages/list-tasks.tabs.my-tasks'))
                ->icon('heroicon-s-user')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereHas('users', function ($q): void {
                        $q->where('user_id', Auth::id());
                    })),

            'unassigned_tasks' => PresetView::make(__('projects::filament/resources/task/pages/list-tasks.tabs.unassigned-tasks'))
                ->icon('heroicon-s-user-minus')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('users')),

            'private_tasks' => PresetView::make(__('projects::filament/resources/task/pages/list-tasks.tabs.private-tasks'))
                ->icon('heroicon-s-lock-closed')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('project_id')),

            'followed_tasks' => PresetView::make(__('projects::filament/resources/task/pages/list-tasks.tabs.followed-tasks'))
                ->icon('heroicon-s-bell')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('followers', function ($q): void {
                    $q->where('chatter_followers.partner_id', Auth::user()->partner_id);
                })),

            'closed_tasks' => PresetView::make(__('projects::filament/resources/task/pages/list-tasks.tabs.closed-tasks'))
                ->icon('heroicon-s-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('state', [
                    TaskState::CANCELLED,
                    TaskState::DONE,
                ])),

            'starred_tasks' => PresetView::make(__('projects::filament/resources/task/pages/list-tasks.tabs.starred-tasks'))
                ->icon('heroicon-s-star')
                ->favorite()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('priority', true)),

            'archived_tasks' => PresetView::make(__('projects::filament/resources/task/pages/list-tasks.tabs.archived-tasks'))
                ->icon('heroicon-s-archive-box')
                ->favorite()
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('projects::filament/resources/task/pages/list-tasks.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
