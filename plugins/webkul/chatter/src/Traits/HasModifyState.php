<?php

declare(strict_types=1);

namespace Webkul\Chatter\Traits;

use Closure;
use Illuminate\Support\HtmlString;

trait HasModifyState
{
    protected $state;

    public function modifyState(Closure $callback): static
    {
        $this->state = $callback;

        return $this;
    }

    public function getModifiedState(): null|string|HtmlString
    {
        return $this->evaluate($this->state);
    }

    private function getCauserName($causer): string
    {
        return $causer->name ?? $causer->first_name ?? $causer->last_name ?? $causer->username ?? 'Unknown';
    }
}
