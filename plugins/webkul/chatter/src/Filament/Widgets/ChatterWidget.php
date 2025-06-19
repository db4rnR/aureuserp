<?php

declare(strict_types=1);

namespace Webkul\Chatter\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class ChatterWidget extends Widget
{
    public $record;

    public mixed $activityPlans;

    public string $resource = '';

    public mixed $followerViewMail;

    public mixed $messageViewMail;

    protected string $view = 'chatter::filament.widgets.chatter';

    protected int|string|array $columnSpan = 'full';

    private static string $type = 'footer';

    public static function canView(): bool
    {
        return true;
    }

    public function mount($record = null, mixed $followerViewMail = null, mixed $messageViewMail = null, string $resource = '', $activityPlans = []): void
    {
        $this->record = $record;

        if ($activityPlans instanceof Collection) {
            $this->activityPlans = $activityPlans;
        } else {
            $this->activityPlans = collect($activityPlans);
        }

        $this->followerViewMail = $followerViewMail;

        $this->messageViewMail = $messageViewMail;

        $this->resource = $resource;
    }

    public function getRecord()
    {
        return $this->record;
    }
}
