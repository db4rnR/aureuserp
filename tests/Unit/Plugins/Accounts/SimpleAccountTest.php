<?php

declare(strict_types=1);

/**
 * Simple test to validate testing environment for accounts plugin.
 */
it('can validate basic accounts plugin testing', function (): void {
    expect(true)->toBeTrue();
})->group('unit', 'accounts');
