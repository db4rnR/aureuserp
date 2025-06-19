<?php

declare(strict_types=1);

namespace Hugomyb\FilamentMediaAction\Actions;

use Filament\Actions\Action;
use Hugomyb\FilamentMediaAction\Concerns\HasMedia;

class MediaAction extends Action
{
    use HasMedia;
}
