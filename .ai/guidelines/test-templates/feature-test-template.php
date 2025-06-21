<?php

/**
 * Feature Test Template for Resource Tests
 *
 * This template demonstrates how to structure feature tests for resources in the project.
 * Replace placeholders with actual values for your specific test case.
 */

// Import necessary classes
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use App\Tests\Attributes\PluginTest;
use App\Tests\Attributes\RequiresDatabase;
// Import the model being tested
use Webkul\YourPlugin\Models\YourModel;
// Import any related models or dependencies
use Webkul\Security\Models\User;
use Webkul\YourPlugin\Models\RelatedModel;

/**
 * Test resource listing page
 */
#[Test]
#[Group('feature')]
#[Group('your-plugin')]
#[PluginTest('YourPlugin')]
#[RequiresDatabase]
#[Description('Test YourModel listing page loads successfully')]
function your_model_listing_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the model listing page
    $response = get(route('filament.admin.resources.your-models.index'));

    // Assert the page loads successfully
    $response->assertSuccessful();

    // Optional: Assert specific content is present
    $response->assertSee('Your Models');
}

/**
 * Test resource creation page
 */
#[Test]
#[Group('feature')]
#[Group('your-plugin')]
#[PluginTest('YourPlugin')]
#[RequiresDatabase]
#[Description('Test YourModel creation page loads successfully')]
function your_model_creation_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the model creation page
    $response = get(route('filament.admin.resources.your-models.create'));

    // Assert the page loads successfully
    $response->assertSuccessful();

    // Optional: Assert specific form elements are present
    $response->assertSee('Create Your Model');
}

/**
 * Test resource creation
 */
#[Test]
#[Group('feature')]
#[Group('your-plugin')]
#[PluginTest('YourPlugin')]
#[RequiresDatabase]
#[Description('Test YourModel can be created successfully')]
function your_model_can_be_created_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create dependencies
    $relatedModel = RelatedModel::factory()->create();

    // Act as the user
    actingAs($user);

    // Prepare model data
    $modelData = [
        'name' => 'Test Model',
        'related_model_id' => $relatedModel->id,
        'attribute1' => 'value1',
        'attribute2' => 'value2',
        // Add other attributes as needed
    ];

    // Submit the model creation form
    $response = post(route('filament.admin.resources.your-models.store'), $modelData);

    // Assert the model was created successfully
    $response->assertRedirect(route('filament.admin.resources.your-models.index'));

    // Assert the model exists in the database
    expect(YourModel::where('name', 'Test Model')
        ->where('related_model_id', $relatedModel->id)
        ->exists())->toBeTrue();
}

/**
 * Test resource viewing
 */
#[Test]
#[Group('feature')]
#[Group('your-plugin')]
#[PluginTest('YourPlugin')]
#[RequiresDatabase]
#[Description('Test YourModel can be viewed successfully')]
function your_model_can_be_viewed_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a model
    $model = YourModel::factory()->create([
        'name' => 'Test Model',
        'attribute1' => 'value1',
    ]);

    // Act as the user
    actingAs($user);

    // Visit the model view page
    $response = get(route('filament.admin.resources.your-models.view', $model));

    // Assert the page loads successfully
    $response->assertSuccessful();

    // Assert the model details are displayed
    $response->assertSee('Test Model');
    $response->assertSee('value1');
}

/**
 * Test resource editing
 */
#[Test]
#[Group('feature')]
#[Group('your-plugin')]
#[PluginTest('YourPlugin')]
#[RequiresDatabase]
#[Description('Test YourModel can be edited successfully')]
function your_model_can_be_edited_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a model
    $model = YourModel::factory()->create([
        'name' => 'Original Name',
        'attribute1' => 'original value',
    ]);

    // Act as the user
    actingAs($user);

    // Visit the model edit page
    $response = get(route('filament.admin.resources.your-models.edit', $model));

    // Assert the page loads successfully
    $response->assertSuccessful();

    // Prepare updated model data
    $updatedData = [
        'name' => 'Updated Name',
        'attribute1' => 'updated value',
    ];

    // Submit the model edit form
    $response = patch(route('filament.admin.resources.your-models.update', $model), $updatedData);

    // Assert the model was updated successfully
    $response->assertRedirect(route('filament.admin.resources.your-models.index'));

    // Assert the model was updated in the database
    $model->refresh();
    expect($model->name)->toBe('Updated Name');
    expect($model->attribute1)->toBe('updated value');
}

/**
 * Test resource deletion
 */
#[Test]
#[Group('feature')]
#[Group('your-plugin')]
#[PluginTest('YourPlugin')]
#[RequiresDatabase]
#[Description('Test YourModel can be deleted successfully')]
function your_model_can_be_deleted_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a model
    $model = YourModel::factory()->create();

    // Act as the user
    actingAs($user);

    // Delete the model
    $response = delete(route('filament.admin.resources.your-models.destroy', $model));

    // Assert the model was deleted successfully
    $response->assertRedirect(route('filament.admin.resources.your-models.index'));

    // Assert the model was deleted from the database
    expect(YourModel::find($model->id))->toBeNull();
}

/**
 * Test resource validation
 */
#[Test]
#[Group('feature')]
#[Group('your-plugin')]
#[Group('validation')]
#[PluginTest('YourPlugin')]
#[RequiresDatabase]
#[Description('Test YourModel validation works correctly')]
function your_model_validation_works_correctly()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Prepare invalid model data (missing required fields)
    $invalidData = [
        // Missing required fields
        'name' => '',
        'attribute1' => '',
    ];

    // Submit the model creation form with invalid data
    $response = post(route('filament.admin.resources.your-models.store'), $invalidData);

    // Assert validation fails
    $response->assertSessionHasErrors(['name', 'attribute1']);

    // Prepare valid model data
    $validData = [
        'name' => 'Valid Name',
        'attribute1' => 'valid value',
        // Add other required attributes
    ];

    // Submit the model creation form with valid data
    $response = post(route('filament.admin.resources.your-models.store'), $validData);

    // Assert the model was created successfully
    $response->assertRedirect(route('filament.admin.resources.your-models.index'));
}

/**
 * Test resource filtering
 */
#[Test]
#[Group('feature')]
#[Group('your-plugin')]
#[PluginTest('YourPlugin')]
#[RequiresDatabase]
#[Description('Test YourModel listing can be filtered')]
function your_model_listing_can_be_filtered()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create models with different attributes
    YourModel::factory()->create(['name' => 'Model A', 'status' => 'active']);
    YourModel::factory()->create(['name' => 'Model B', 'status' => 'inactive']);
    YourModel::factory()->create(['name' => 'Model C', 'status' => 'active']);

    // Act as the user
    actingAs($user);

    // Visit the model listing page with filter
    $response = get(route('filament.admin.resources.your-models.index', ['filter' => ['status' => 'active']]));

    // Assert the page loads successfully
    $response->assertSuccessful();

    // Assert filtered results are displayed
    $response->assertSee('Model A');
    $response->assertSee('Model C');
    $response->assertDontSee('Model B');
}
