<?php

declare(strict_types=1);

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\DepartureReasonResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\DepartureReasonResource;

class ListDepartureReasons extends ListRecords
{
    protected static string $resource = DepartureReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->icon('heroicon-o-plus-circle')
                ->label(__('employees::filament/clusters/configurations/resources/departure-reason/pages/list-departure.header-actions.create.label'))
                ->successNotification(
                    Notification::make()->success()
                        ->title(__('employees::filament/clusters/configurations/resources/departure-reason/pages/list-departure.header-actions.create.notification.title'))
                        ->body(__('employees::filament/clusters/configurations/resources/departure-reason/pages/list-departure.header-actions.create.notification.body')),
                )
                ->mutateDataUsing(function (array $data): array {
                    $data['reason_code'] = crc32((string) $data['name']) % 100000;

                    return $data;
                }),
        ];
    }
}
