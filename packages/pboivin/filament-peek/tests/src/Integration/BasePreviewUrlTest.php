<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests\Integration;

use Pboivin\FilamentPeek\CachedPreview;
use Pboivin\FilamentPeek\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('cannot access preview url', function (): void {
    actingAs(User::factory()->create());

    CachedPreview::make(EditPost::class, 'preview-data', ['KEY' => 'VALUE'])
        ->put('test');

    get('/filament-peek/preview/?token=test')
        ->assertNotFound();
});
