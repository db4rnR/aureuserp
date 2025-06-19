<?php

declare(strict_types=1);

namespace Webkul\Chatter\Filament\Infolists\Components\Messages;

use Filament\Infolists\Components\RepeatableEntry;

class MessageRepeatableEntry extends RepeatableEntry
{
    protected string $view = 'chatter::filament.infolists.components.messages.repeatable-entry';

    protected function setup(): void
    {
        parent::setup();

        $this->configureRepeatableEntry();
    }

    private function configureRepeatableEntry(): void
    {
        $this
            ->contained(false)
            ->hiddenLabel();
    }
}
