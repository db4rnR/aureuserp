<?php

use Pest\Attributes\Test;
use Pest\Attributes\Group;
use Pest\Attributes\Description;
use Pest\Attributes\DataProvider;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\delete;
use function Pest\Laravel\actingAs;

/**
 * Feature Test Template for AureusERP
 *
 * This template demonstrates the recommended structure and style for feature tests.
 * Replace the example code with your actual test code.
 */

/**
 * Test for a resource listing page
 */
#[Test]
#[Group('feature')]
#[Group('plugin-name')] // Replace with your plugin name
#[Group('ui')] // Example of a specific test category
#[Description('Test that the resource listing page loads successfully')]
function resource_listing_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the resource listing page
    $response = get(route('filament.admin.resources.plugin-name.resource-name.index'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

/**
 * Test for a resource creation page
 */
#[Test]
#[Group('feature')]
#[Group('plugin-name')] // Replace with your plugin name
#[Description('Test that the resource creation page loads successfully')]
function resource_creation_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the resource creation page
    $response = get(route('filament.admin.resources.plugin-name.resource-name.create'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

/**
 * Test for resource creation
 */
#[Test]
#[Group('feature')]
#[Group('plugin-name')] // Replace with your plugin name
#[Group('api')] // Example of a specific test category
#[Group('database')] // Example of combining multiple specific categories
#[Description('Test that a resource can be created successfully')]
function resource_can_be_created_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Prepare resource data
    $resourceData = [
        'name' => 'Test Resource',
        'description' => 'This is a test resource',
        // Add other required fields
    ];

    // Submit the resource creation form
    $response = post(route('filament.admin.resources.plugin-name.resource-name.store'), $resourceData);

    // Assert the resource was created successfully
    $response->assertRedirect(route('filament.admin.resources.plugin-name.resource-name.index'));

    // Assert the resource exists in the database
    expect(YourModel::where('name', 'Test Resource')->exists())->toBeTrue();
}

/**
 * Test for resource update
 */
#[Test]
#[Group('feature')]
#[Group('plugin-name')] // Replace with your plugin name
#[Description('Test that a resource can be updated successfully')]
function resource_can_be_updated_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a resource
    $resource = YourModel::factory()->create([
        'name' => 'Original Resource Name',
        'description' => 'Original description',
    ]);

    // Act as the user
    actingAs($user);

    // Prepare updated resource data
    $updatedData = [
        'name' => 'Updated Resource Name',
        'description' => 'Updated description',
        // Add other fields to update
    ];

    // Submit the resource update form
    $response = put(route('filament.admin.resources.plugin-name.resource-name.update', $resource), $updatedData);

    // Assert the resource was updated successfully
    $response->assertRedirect(route('filament.admin.resources.plugin-name.resource-name.index'));

    // Assert the resource was updated in the database
    $updatedResource = YourModel::find($resource->id);
    expect($updatedResource->name)->toBe('Updated Resource Name');
    expect($updatedResource->description)->toBe('Updated description');
}

/**
 * Test for resource deletion
 */
#[Test]
#[Group('feature')]
#[Group('plugin-name')] // Replace with your plugin name
#[Description('Test that a resource can be deleted successfully')]
function resource_can_be_deleted_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a resource
    $resource = YourModel::factory()->create();

    // Act as the user
    actingAs($user);

    // Delete the resource
    $response = delete(route('filament.admin.resources.plugin-name.resource-name.destroy', $resource));

    // Assert the resource was deleted successfully
    $response->assertRedirect(route('filament.admin.resources.plugin-name.resource-name.index'));

    // Assert the resource was deleted from the database
    expect(YourModel::find($resource->id))->toBeNull();
}

/**
 * Test for authorization
 */
#[Test]
#[Group('feature')]
#[Group('plugin-name')] // Replace with your plugin name
#[Group('security')] // Example of a specific test category
#[Description('Test that unauthorized users cannot access protected resources')]
function unauthorized_users_cannot_access_protected_resources()
{
    // Create a user without appropriate permissions
    $user = User::factory()->create();
    // Don't assign the required permissions/roles

    // Act as the user
    actingAs($user);

    // Try to access a protected resource
    $response = get(route('filament.admin.resources.plugin-name.resource-name.index'));

    // Assert the access is denied
    $response->assertForbidden();
}

/**
 * Test for validation errors
 */
#[Test]
#[Group('feature')]
#[Group('plugin-name')] // Replace with your plugin name
#[Group('validation')] // Example of a specific test category
#[Group('error-handling')] // Example of combining multiple specific categories
#[Description('Test that validation errors are returned for invalid input')]
function validation_errors_are_returned_for_invalid_input()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Prepare invalid resource data (missing required fields)
    $invalidData = [
        // Missing required fields
    ];

    // Submit the resource creation form with invalid data
    $response = post(route('filament.admin.resources.plugin-name.resource-name.store'), $invalidData);

    // Assert validation errors are returned
    $response->assertSessionHasErrors(['name', 'description']);
}
