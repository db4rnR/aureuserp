<?php

declare(strict_types=1);

namespace Webkul\Chatter\Filament\Infolists\Components\Messages;

use Filament\Forms\Components\Concerns\CanAllowHtml;
use Filament\Infolists\Components\Entry;
use Filament\Support\Concerns\HasExtraAttributes;

final class TitleTextEntry extends Entry
{
    use CanAllowHtml;
    use HasExtraAttributes;

    protected string $view = 'chatter::filament.infolists.components.messages.title-text-entry';

    protected function setUp(): void
    {
        parent::setUp();
    }
}
