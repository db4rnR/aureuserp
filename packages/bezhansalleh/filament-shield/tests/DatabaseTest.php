<?php

declare(strict_types=1);

use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use BezhanSalleh\FilamentShield\Resources\RoleResource;

it('can check if package testing is configured', function (): void {
    expect(true)->toBeTrue();
});

it('can check if the permission name can be configured using the closure', function (): void {
    $resource = RoleResource::class;

    FilamentShield::configurePermissionIdentifierUsing(
        fn ($resource) => str('Spatie\\Permission\\Models\\Role')
            ->afterLast('\\')
            ->lower()
            ->toString()
    );

    expect(FilamentShield::getPermissionIdentifier($resource))->toBe('role');
});
