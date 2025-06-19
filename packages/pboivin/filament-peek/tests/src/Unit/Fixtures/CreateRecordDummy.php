<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests\Unit\Fixtures;

use Filament\Resources\Pages\CreateRecord;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class CreateRecordDummy extends CreateRecord
{
    use HasBuilderPreview;
    use HasPreviewModal;

    public ?array $data = [];

    protected static string $resource = ResourceDummy::class;
}
