<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests\Unit\Fixtures;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditRecordDummy extends EditRecord
{
    use HasBuilderPreview;
    use HasPreviewModal;

    public ?array $data = [];

    protected static string $resource = ResourceDummy::class;

    public function getRecord(): Model
    {
        return new ModelDummy;
    }
}
