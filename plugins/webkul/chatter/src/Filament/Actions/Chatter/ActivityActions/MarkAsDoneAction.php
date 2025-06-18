<?php

declare(strict_types=1);

namespace Webkul\Chatter\Filament\Actions\Chatter\ActivityActions;

use Filament\Actions\Action;

final class MarkAsDoneAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->color('gray')
            ->outlined()
            ->slideOver(false);
    }

    public static function getDefaultName(): ?string
    {
        return 'activity.mark_as_done.action';
    }
}
