<?php

declare(strict_types=1);

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Forms\Get;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Model;
use Throwable;
use Webkul\Chatter\Mail\FollowerMail;
use Webkul\Partner\Models\Partner;
use Webkul\Support\Services\EmailService;

class FollowerAction extends Action
{
    private string $mailView = 'chatter::mail.follower-mail';

    private string $resource = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->icon('heroicon-s-user')
            ->color('gray')
            ->modal()
            ->tooltip(__('chatter::filament/resources/actions/chatter/follower-action.setup.tooltip'))
            ->modalIcon('heroicon-s-user-plus')
            ->badge(fn (Model $record): int => $record->followers->count())
            ->modalWidth(Width::TwoExtraLarge)
            ->slideOver(false)
            ->schema([
                    Select::make('partners')->label(__('chatter::filament/resources/actions/chatter/follower-action.setup.form.fields.recipients'))
                        ->preload()
                        ->searchable()
                        ->multiple()
                        ->live()
                        ->relationship('followable', 'name')
                        ->required(),
                    Toggle::make('notify')->live()
                        ->label(__('chatter::filament/resources/actions/chatter/follower-action.setup.form.fields.notify-user')),
                    RichEditor::make('note')->disableGrammarly()
                        ->toolbarButtons([
                            'attachFiles',
                            'blockquote',
                            'bold',
                            'bulletList',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->visible(fn ($get): mixed => $get('notify'))
                        ->hiddenLabel()
                        ->placeholder(__('chatter::filament/resources/actions/chatter/follower-action.setup.form.fields.add-a-note')),
                ])
            ->modalContentFooter(fn (Model $record) => view('chatter::filament.actions.follower-action', [
                'record' => $record,
            ]))
            ->action(function (Model $record, $livewire): void {
                [$data] = $livewire->mountedActionsData;

                try {
                    collect($data['partners'])->each(function ($partnerId) use ($record, $data): void {
                        $partner = Partner::findOrFail($partnerId);

                        $record->addFollower($partner);

                        if (
                            ! empty($data['notify'])
                            && $data['notify']
                        ) {
                            $this->notifyFollower($record, $partner, $data);
                        }

                        Notification::make()->success()
                            ->title(__('chatter::filament/resources/actions/chatter/follower-action.setup.actions.notification.success.title'))
                            ->body(__('chatter::filament/resources/actions/chatter/follower-action.setup.actions.notification.success.body', ['partner' => $partner->name]))
                            ->send();
                    });
                } catch (Throwable $e) {
                    info('Error adding followers', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    Notification::make()->danger()
                        ->title(__('chatter::filament/resources/actions/chatter/follower-action.setup.actions.notification.error.title'))
                        ->body(__('chatter::filament/resources/actions/chatter/follower-action.setup.actions.notification.error.body'))
                        ->send();
                }
            })
            ->hiddenLabel()
            ->modalHeading(__('chatter::filament/resources/actions/chatter/follower-action.setup.title'))
            ->modalSubmitAction(
                fn ($action) => $action
                    ->label(__('chatter::filament/resources/actions/chatter/follower-action.setup.submit-action-title'))
                    ->icon('heroicon-m-user-plus')
            );
    }

    public static function getDefaultName(): ?string
    {
        return 'add.followers.action';
    }

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function setFollowerMailView(?string $mailView): self
    {
        $mailView = $this->evaluate($mailView);

        if (empty($mailView)) {
            return $this;
        }

        $this->mailView = $mailView;

        return $this;
    }

    public function getFollowerMailView(): string
    {
        return $this->mailView;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function preparePayload(Model $record, Partner $partner, $data): array
    {
        return [
            'record_url' => $this->prepareResourceUrl($record) ?? '',
            'record_name' => $recordName = $record->{$record->recordTitleAttribute} ?? $record->name,
            'model_name' => $modelName = class_basename($record),
            'subject' => __('chatter::filament/resources/actions/chatter/follower-action.setup.actions.mail.subject', [
                'model' => $modelName,
                'department' => $recordName,
            ]),
            'note' => $data['note'] ?? '',
            'to' => [
                'address' => $partner->email,
                'name' => $partner->name,
            ],
        ];
    }

    private function notifyFollower(Model $record, Partner $partner, array $data): void
    {
        app(EmailService::class)->send(
            mailClass: FollowerMail::class,
            view: $this->mailView,
            payload: $this->preparePayload($record, $partner, $data),
        );
    }

    private function prepareResourceUrl(mixed $record): string
    {
        return $this->resource::getUrl('view', ['record' => $record]);
    }
}
