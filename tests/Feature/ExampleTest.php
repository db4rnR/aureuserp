<?php

declare(strict_types=1);

use function Pest\Laravel\get;

/**
 * A basic test example.
 */
it('returns a successful response', function (): void {
    $response = get('/');

    $response->assertStatus(200);
})->group('feature');
