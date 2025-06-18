<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\LeaveTypeResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\LeaveTypeResource;
use Webkul\TimeOff\Models\LeaveType;

final class ListLeaveTypes extends ListRecords
{
    protected static string $resource = LeaveTypeResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('time-off::filament/clusters/configurations/resources/leave-type/pages/list-leave-type.tabs.all'))
                ->badge(LeaveType::whereNull('deleted_at')->count()),
            'archived' => Tab::make(__('time-off::filament/clusters/configurations/resources/leave-type/pages/list-leave-type.tabs.archived'))
                ->badge(LeaveType::onlyTrashed()->count())
                ->modifyQueryUsing(fn ($query) => $query->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('time-off::filament/clusters/configurations/resources/leave-type/pages/list-leave-type.header-actions.new-leave-type'))
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
