<?php

declare(strict_types=1);

namespace Webkul\Chatter\Filament\Pages\Concerns;

use Webkul\Chatter\Filament\Widgets\ChatterWidget;

trait HasChatter
{
    protected function getFooterWidgets(): array
    {
        return [
            ChatterWidget::class,
        ];
    }
}
