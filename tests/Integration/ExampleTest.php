<?php

declare(strict_types=1);

use App\Models\User;

/**
 * Example integration test that demonstrates testing database interactions.
 */
it('can create and retrieve a user', function (): void {
    // This is just an example. Adjust according to your actual User model structure.
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $retrievedUser = User::where('email', 'test@example.com')->first();

    expect($retrievedUser)->not->toBeNull()
        ->and($retrievedUser->name)->toBe('Test User')
        ->and($retrievedUser->email)->toBe('test@example.com');
})->group('integration');
