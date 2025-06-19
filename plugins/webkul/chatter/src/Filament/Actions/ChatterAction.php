<?php

declare(strict_types=1);

namespace Webkul\Chatter\Filament\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ChatterAction extends Action
{
    private mixed $activityPlans;

    private string $resource = '';

    private string $followerViewMail = '';

    private string $messageViewMail = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->icon('heroicon-s-chat-bubble-left-right')
            ->modalIcon('heroicon-s-chat-bubble-left-right')
            ->slideOver()
            ->modalContentFooter(fn (Model $record): View => tap(view('chatter::filament.widgets.chatter', [
                'record' => $record,
                'activityPlans' => $this->getActivityPlans(),
                'resource' => $this->resource,
                'followerViewMail' => $this->followerViewMail,
                'messageViewMail' => $this->messageViewMail,
            ]), fn () => $record->markAsRead()))
            ->modalHeading(__('chatter::filament/resources/actions/chatter-action.title'))
            ->modalDescription(__('chatter::filament/resources/actions/chatter-action.description'))
            ->badge(fn (Model $record): int => $record->unRead()->count())
            ->modalWidth(Width::TwoExtraLarge)
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }

    public static function getDefaultName(): ?string
    {
        return 'chatter.action';
    }

    public function setActivityPlans(mixed $activityPlans): static
    {
        $this->activityPlans = $activityPlans;

        return $this;
    }

    public function setResource(string $resource): static
    {
        if ($resource === '' || $resource === '0') {
            throw new InvalidArgumentException('The resource parameter must be provided and cannot be empty.');
        }

        if (! class_exists($resource)) {
            throw new InvalidArgumentException("The resource class [{$resource}] does not exist.");
        }

        $this->resource = $resource;

        return $this;
    }

    public function setFollowerMailView(string|Closure|null $followerViewMail): static
    {
        $this->followerViewMail = $followerViewMail;

        return $this;
    }

    public function setMessageMailView(string|Closure|null $followerViewMail): static
    {
        $this->followerViewMail = $followerViewMail;

        return $this;
    }

    public function getActivityPlans(): mixed
    {
        return $this->activityPlans ?? collect();
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getFollowerMailView(): string|Closure|null
    {
        return $this->followerViewMail;
    }

    public function getMessageMailView(): string|Closure|null
    {
        return $this->messageViewMail;
    }
}
