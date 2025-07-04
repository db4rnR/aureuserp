<?php

declare(strict_types=1);

namespace Kirschbaum\Commentions\Filament\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Kirschbaum\Commentions\Filament\Concerns\HasMentionables;
use Kirschbaum\Commentions\Filament\Concerns\HasPolling;

class CommentsEntry extends Entry
{
    use HasMentionables;
    use HasPolling;

    protected string $view = 'commentions::filament.infolists.components.comments-entry';
}
