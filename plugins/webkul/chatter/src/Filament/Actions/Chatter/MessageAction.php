<?php

declare(strict_types=1);

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Group;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Webkul\Chatter\Mail\MessageMail;
use Webkul\Support\Services\EmailService;

class MessageAction extends Action
{
    private string $mailView = 'chatter::mail.message-mail';

    private string $resource = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->color('gray')
            ->outlined()
            ->visible(false)
            ->schema([
                Group::make([
                    Actions::make([
                        Action::make('add_subject')->label(fn ($get) => $get('showSubject') ? __('chatter::filament/resources/actions/chatter/message-action.setup.form.fields.hide-subject') : __('chatter::filament/resources/actions/chatter/message-action.setup.form.fields.add-subject'))
                            ->action(function ($set, $get): void {
                                if ($get('showSubject')) {
                                    $set('showSubject', false);

                                    return;
                                }

                                $set('showSubject', true);
                            })
                            ->link()
                            ->size('sm')
                            ->icon(fn ($get): string => $get('showSubject') ? 'heroicon-s-minus' : 'heroicon-s-plus'),
                    ])
                        ->columnSpan('full')
                        ->alignRight(),
                ]),
                TextInput::make('subject')->placeholder(__('chatter::filament/resources/actions/chatter/message-action.setup.form.fields.subject'))
                    ->live()
                    ->visible(fn ($get) => $get('showSubject')),
                RichEditor::make('body')->hiddenLabel()
                    ->placeholder(__('chatter::filament/resources/actions/chatter/message-action.setup.form.fields.write-message-here'))
                    ->fileAttachmentsDirectory('log-attachments')
                    ->disableGrammarly()
                    ->required(),
                FileUpload::make('attachments')->hiddenLabel()
                    ->multiple()
                    ->directory('messages-attachments')
                    ->disableGrammarly()
                    ->previewable(true)
                    ->panelLayout('grid')
                    ->imagePreviewHeight('100')
                    ->acceptedFileTypes([
                        'image/*',
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'text/plain',
                    ])
                    ->maxSize(10240)
                    ->helperText(__('chatter::filament/resources/actions/chatter/message-action.setup.form.fields.attachments-helper-text'))
                    ->columnSpanFull(),
                Hidden::make('type')->default('comment'),
            ])
            ->action(function (array $data, ?Model $record = null): void {
                try {
                    $data['name'] = $record->name;

                    $message = $record->addMessage($data, filament()->auth()->id());

                    if (! empty($data['attachments'])) {
                        $record->addAttachments(
                            $data['attachments'],
                            ['message_id' => $message->id],
                        );
                    }

                    $this->notifyFollower($record, $message);

                    Notification::make()->success()
                        ->title(__('chatter::filament/resources/actions/chatter/message-action.setup.actions.notification.success.title'))
                        ->body(__('chatter::filament/resources/actions/chatter/message-action.setup.actions.notification.success.body'))
                        ->send();
                } catch (Exception $e) {
                    report($e);
                    Notification::make()->danger()
                        ->title(__('chatter::filament/resources/actions/chatter/message-action.setup.actions.notification.error.title'))
                        ->body(__('chatter::filament/resources/actions/chatter/message-action.setup.actions.notification.error.body'))
                        ->send();
                }
            })
            ->label(__('chatter::filament/resources/actions/chatter/message-action.setup.title'))
            ->icon('heroicon-o-chat-bubble-left-right')
            ->modalIcon('heroicon-o-chat-bubble-left-right')
            ->modalSubmitAction(function ($action): void {
                $action->label(__('chatter::filament/resources/actions/chatter/message-action.setup.submit-title'));
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }

    public static function getDefaultName(): ?string
    {
        return 'message.action';
    }

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function setMessageMailView(?string $mailView): self
    {
        $mailView = $this->evaluate($mailView);

        if (empty($mailView)) {
            return $this;
        }

        $this->mailView = $mailView;

        return $this;
    }

    public function getMessageMailView(): string
    {
        return $this->mailView;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    private function notifyFollower(mixed $record, mixed $message): void
    {
        foreach ($record->followers as $follower) {
            if ($follower?->partner) {
                app(EmailService::class)->send(
                    mailClass: MessageMail::class,
                    view: $this->mailView,
                    attachments: $this->prepareAttachments($message->attachments),
                    payload: $this->preparePayload($record, $follower->partner, $message),
                );
            }
        }
    }

    private function prepareResourceUrl(mixed $record): string
    {
        return $this->resource::getUrl('view', ['record' => $record]);
    }

    private function preparePayload(Model $record, mixed $partner, mixed $message): array
    {
        return [
            'record_url' => $this->prepareResourceUrl($record) ?? '',
            'record_name' => $recordName = $record->{$record->recordTitleAttribute} ?? $record->name,
            'model_name' => class_basename($record),
            'subject' => __('chatter::filament/resources/actions/chatter/message-action.setup.actions.mail.subject', [
                'record_name' => $recordName,
            ]),
            'content' => $message->body ?? '',
            'to' => [
                'address' => $partner->email,
                'name' => $partner->name,
            ],
        ];
    }

    private function prepareAttachments(Collection $attachments): array
    {
        return $attachments?->map(fn ($attachment): array => [
            'path' => asset($attachment->url),
            'name' => $attachment->name,
            'mime' => $attachment->mime,
        ])->toArray();
    }
}
