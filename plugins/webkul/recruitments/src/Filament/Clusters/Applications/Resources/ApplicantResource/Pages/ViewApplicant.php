<?php

declare(strict_types=1);

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Employee\Filament\Resources\EmployeeResource;
use Webkul\Recruitment\Enums\ApplicationStatus;
use Webkul\Recruitment\Enums\RecruitmentState;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource;
use Webkul\Recruitment\Mail\ApplicantRefuseMail;
use Webkul\Recruitment\Models\Applicant;
use Webkul\Recruitment\Models\RefuseReason;
use Webkul\Support\Services\EmailService;

class ViewApplicant extends ViewRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('state')->hiddenLabel()
                ->icon(function ($record) {
                    if ($record->state === RecruitmentState::DONE->value) {
                        return RecruitmentState::DONE->getIcon();
                    }
                    if ($record->state === RecruitmentState::BLOCKED->value) {
                        return RecruitmentState::BLOCKED->getIcon();
                    }
                    if ($record->state === RecruitmentState::NORMAL->value) {
                        return RecruitmentState::NORMAL->getIcon();
                    }
                })
                ->iconButton()
                ->color(function ($record) {
                    if ($record->state === RecruitmentState::DONE->value) {
                        return RecruitmentState::DONE->getColor();
                    }
                    if ($record->state === RecruitmentState::BLOCKED->value) {
                        return RecruitmentState::BLOCKED->getColor();
                    }
                    if ($record->state === RecruitmentState::NORMAL->value) {
                        return RecruitmentState::NORMAL->getColor();
                    }
                })
                ->schema([
                    ToggleButtons::make('state')->inline()
                        ->options(RecruitmentState::class),
                ])
                ->fillForm(fn ($record): array => [
                    'state' => $record->state,
                ])
                ->tooltip(function ($record) {
                    if ($record->state === RecruitmentState::DONE->value) {
                        return RecruitmentState::DONE->getLabel();
                    }
                    if ($record->state === RecruitmentState::BLOCKED->value) {
                        return RecruitmentState::BLOCKED->getLabel();
                    }
                    if ($record->state === RecruitmentState::NORMAL->value) {
                        return RecruitmentState::NORMAL->getLabel();
                    }
                })
                ->action(function (Applicant $record, $data): void {
                    $record->update($data);

                    Notification::make()->success()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.state.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.state.notification.body'))
                        ->send();
                }),
            Action::make('gotoEmployee')->tooltip(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.goto-employee'))
                ->visible(fn ($record): bool => $record->application_status->value === ApplicationStatus::HIRED->value || $record->candidate->employee_id)
                ->icon('heroicon-s-arrow-top-right-on-square')
                ->iconButton()
                ->action(function (Applicant $record) {
                    $employee = $record->createEmployee();

                    return redirect(EmployeeResource::getUrl('view', ['record' => $employee]));
                }),
            ChatterActions\ChatterAction::make()->setResource(self::$resource),
            Action::make('createEmployee')->label(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.create-employee'))
                ->hidden(fn ($record): bool => $record->application_status->value === ApplicationStatus::HIRED->value || $record->candidate->employee_id)
                ->action(function (Applicant $record) {
                    $employee = $record->createEmployee();

                    return redirect(EmployeeResource::getUrl('edit', ['record' => $employee]));
                }),
            DeleteAction::make()->successNotification(
                    Notification::make()->success()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.delete.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.delete.notification.body'))
                ),
            Action::make('Refuse')->modalIcon('heroicon-s-bug-ant')
                ->hidden(fn ($record): bool => $record->refuse_reason_id || $record->application_status->value === ApplicationStatus::ARCHIVED->value)
                ->modalHeading('Refuse Reason')
                ->schema(fn (Form $form, $record): Form => $form->schema([
                    ToggleButtons::make('refuse_reason_id')->hiddenLabel()
                        ->inline()
                        ->live()
                        ->options(RefuseReason::all()->pluck('name', 'id')),
                    Toggle::make('notify')->inline()
                        ->live()
                        ->default(true)
                        ->visible(fn (Get $get): mixed => $get('refuse_reason_id'))
                        ->label('Notify'),
                    TextInput::make('email')->visible(fn (Get $get): bool => $get('notify') && $get('refuse_reason_id'))
                        ->default($record->candidate->email_from)
                        ->label('Email To'),
                ]))
                ->action(function (array $data, Applicant $record) {
                    $refuseReason = RefuseReason::find($data['refuse_reason_id']);

                    if (! $refuseReason) {
                        return null;
                    }

                    $record->setAsRefused($refuseReason?->id);

                    if (isset($data['notify']) && $data['notify']) {
                        $data = $this->prepareApplicantRefuseNotificationPayload($data);

                        app(EmailService::class)->send(
                            mailClass: ApplicantRefuseMail::class,
                            view: "recruitments::mails.{$refuseReason?->template}",
                            payload: $data
                        );
                    }

                    Notification::make()->info()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.refuse.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.refuse.notification.body'))
                        ->send();
                }),
            Action::make('Restore')->hidden(fn ($record): bool => ! $record->refuse_reason_id)
                ->modalHeading('Restore Applicant from refuse')
                ->requiresConfirmation()
                ->color('gray')
                ->action(function (Applicant $record): void {
                    $record->reopen();

                    Notification::make()->info()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.reopen.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.reopen.notification.body'))
                        ->send();
                }),

        ];
    }

    private function prepareApplicantRefuseNotificationPayload(array $data): array
    {
        return [
            'applicant_name' => $this->record->candidate->name,
            'subject' => __('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.mail.application-refused.subject', [
                'application' => $this->record->job?->name,
            ]),
            'to' => [
                'address' => $data['email'] ?? $this->record?->candidate?->email_from,
                'name' => $this->record?->candidate?->name,
            ],
        ];
    }
}
