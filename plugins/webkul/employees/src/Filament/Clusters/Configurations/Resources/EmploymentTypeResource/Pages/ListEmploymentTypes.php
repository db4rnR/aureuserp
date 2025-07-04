<?php

declare(strict_types=1);

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\EmploymentTypeResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmploymentTypeResource;

class ListEmploymentTypes extends ListRecords
{
    protected static string $resource = EmploymentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->icon('heroicon-o-plus-circle')
                ->label(__('employees::filament/clusters/configurations/resources/employment-type/pages/list-employment-type.header-actions.create.label'))
                ->mutateDataUsing(function (array $data): array {
                    $data['code'] ??= $data['name'];

                    $data['user_id'] = Auth::user()->id;

                    return $data;
                })
                ->successNotification(
                    Notification::make()->success()
                        ->title(__('employees::filament/clusters/configurations/resources/employment-type/pages/list-employment-type.header-actions.create.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/employment-type/pages/list-employment-type.header-actions.create.notification.body'))
                ),
        ];
    }
}
