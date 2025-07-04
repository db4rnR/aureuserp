<?php

declare(strict_types=1);

namespace Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Employee\Models\Employee;
use Webkul\TimeOff\Enums\State;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\TimeOffResource;

class EditTimeOff extends EditRecord
{
    protected static string $resource = TimeOffResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()->success()
            ->title(__('time-off::filament/clusters/management/resources/time-off/pages/edit-time-off.notification.title'))
            ->body(__('time-off::filament/clusters/management/resources/time-off/pages/edit-time-off.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make()->setResource(self::$resource),
            ViewAction::make(),
            DeleteAction::make()->successNotification(
                    Notification::make()->success()
                        ->title(__('time-off::filament/clusters/management/resources/time-off/pages/edit-time-off.header-actions.delete.notification.title'))
                        ->body(__('time-off::filament/clusters/management/resources/time-off/pages/edit-time-off.header-actions.delete.notification.body'))
                ),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['employee_id'])) {
            $employee = Employee::find($data['employee_id']);

            $data['department_id'] = $employee->department ? $employee->department?->id : null;

            if ($employee->calendar) {
                $data['calendar_id'] = $employee->calendar->id;
                $data['number_of_hours'] = $employee->calendar->hours_per_day;
            }

            $user = $employee?->user;

            if ($user) {
                $data['user_id'] = $user->id;

                $data['company_id'] = $user->default_company_id;

                $data['employee_company_id'] = $user->default_company_id;
            }
        }

        if (isset($data['request_unit_half'])) {
            $data['duration_display'] = '0.5 day';

            $data['number_of_days'] = 0.5;
        } else {
            $startDate = Carbon::parse($data['request_date_from']);
            $endDate = $data['request_date_to'] ? Carbon::parse($data['request_date_to']) : $startDate;

            $data['duration_display'] = $startDate->diffInDays($endDate) + 1 .' day(s)';

            $data['number_of_days'] = $startDate->diffInDays($endDate) + 1;
        }

        $data['creator_id'] = Auth::user()->id;

        $data['state'] = State::CONFIRM->value;

        $data['date_from'] = $data['request_date_from'];
        $data['date_to'] = $data['request_date_to'];

        return $data;
    }
}
