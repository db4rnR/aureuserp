<?php

declare(strict_types=1);

namespace Pboivin\FilamentPeek\Tests\Integration;

use Pboivin\FilamentPeek\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('does not see preview modal assets when disabled', function (): void {
    actingAs(User::factory()->create());

    get('/admin')
        ->assertSuccessful()
        ->assertDontSee('filament-peek.css')
        ->assertDontSee('filament-peek.js');
});
