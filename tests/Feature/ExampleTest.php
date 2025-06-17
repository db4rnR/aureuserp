<?php

use function Pest\Laravel\get;

/**
 * A basic test example.
 */
it('returns a successful response', function () {
    $response = get('/');

    $response->assertStatus(200);
})->group('feature');
