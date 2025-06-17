<?php

namespace Kirschbaum\Commentions\Filament\Concerns;

trait HasPolling
{
    protected ?int $pollingInterval = null;

    public function pollingInterval(int $interval = 60): static
    {
        $this->pollingInterval = $interval;

        return $this;
    }

    public function getPollingInterval(): ?int
    {
        return $this->pollingInterval;
    }
}
