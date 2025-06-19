<?php

declare(strict_types=1);

namespace FilamentTiptapEditor\Tests\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use FilamentTiptapEditor\Tests\Resources\PageResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;
}
