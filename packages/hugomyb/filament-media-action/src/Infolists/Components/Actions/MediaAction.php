<?php

declare(strict_types=1);

namespace Hugomyb\FilamentMediaAction\Infolists\Components\Actions;

use Filament\Actions\Action;
use Hugomyb\FilamentMediaAction\Concerns\HasMedia;

final class MediaAction extends Action
{
    use HasMedia;
}
