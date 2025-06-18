<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests\Unit\Fixtures;

use Filament\Resources\Pages\ListRecords;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

final class ListRecordsDummy extends ListRecords
{
    use HasPreviewModal;

    protected static string $resource = ResourceDummy::class;
}
