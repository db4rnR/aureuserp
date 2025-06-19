<?php

declare(strict_types=1);

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource;
use Webkul\TableViews\Filament\Components\PresetView;
use Webkul\TableViews\Filament\Concerns\HasTableViews;

class ListJobPositions extends ListRecords
{
    use HasTableViews;

    protected static string $resource = JobPositionResource::class;

    public function getPresetTableViews(): array
    {
        return [
            'my_department' => PresetView::make(__('employees::filament/clusters/configurations/resources/job-position/pages/list-job-position.tabs.my-department'))
                ->icon('heroicon-m-user-group')
                ->favorite()
                ->modifyQueryUsing(function ($query) {
                    $user = Auth::user();

                    return $query->whereIn('department_id', $user->departments->pluck('id'));
                }),
            'archived_projects' => PresetView::make(__('employees::filament/clusters/configurations/resources/job-position/pages/list-job-position.tabs.archived'))
                ->icon('heroicon-s-archive-box')
                ->favorite()
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label(__('employees::filament/clusters/configurations/resources/job-position/pages/list-job-position.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateDataUsing(fn ($data) => $data),
        ];
    }
}
