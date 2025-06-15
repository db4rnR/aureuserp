<?php

use Pest\Attributes\Test;
use Pest\Attributes\Group;
use Pest\Attributes\Description;
use Pest\Attributes\DataProvider;

/**
 * Unit Test Template for AureusERP
 *
 * This template demonstrates the recommended structure and style for unit tests.
 * Replace the example code with your actual test code.
 */

/**
 * Test for a model's attributes and basic functionality
 */
#[Test]
#[Group('unit')]
#[Group('plugin-name')] // Replace with your plugin name
#[Group('validation')] // Example of a specific test category
#[Description('Test that the model attributes and methods work correctly')]
function model_attributes_and_methods()
{
    // Create a model instance using a factory
    $model = YourModel::factory()->create([
        'attribute1' => 'value1',
        'attribute2' => 'value2',
    ]);

    // Test attributes
    expect($model->attribute1)->toBe('value1');
    expect($model->attribute2)->toBe('value2');

    // Test methods
    expect($model->someMethod())->toBe('expected result');
}

/**
 * Test for model relationships
 */
#[Test]
#[Group('unit')]
#[Group('plugin-name')] // Replace with your plugin name
#[Group('database')] // Example of a specific test category
#[Description('Test model relationships with other models')]
function model_relationships()
{
    // Create related models
    $relatedModel1 = RelatedModel1::factory()->create();
    $relatedModel2 = RelatedModel2::factory()->create();

    // Create the main model with relationships
    $model = YourModel::factory()->create([
        'related_model1_id' => $relatedModel1->id,
    ]);

    // Attach many-to-many relationships
    $model->relatedModels2()->attach($relatedModel2);

    // Test relationships
    expect($model->relatedModel1->id)->toBe($relatedModel1->id);
    expect($model->relatedModels2->first()->id)->toBe($relatedModel2->id);

    // Test relationship types
    expect($model->relatedModel1())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($model->relatedModels2())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
}

/**
 * Test with data provider for multiple scenarios
 */
#[Test]
#[Group('unit')]
#[Group('plugin-name')] // Replace with your plugin name
#[Group('performance')] // Example of a specific test category
#[DataProvider('calculation_data_provider')]
#[Description('Test calculations with different inputs')]
function calculation_with_different_inputs($input, $expected)
{
    // Create a model or service that performs calculations
    $calculator = new YourCalculator();

    // Perform the calculation
    $result = $calculator->calculate($input);

    // Verify the result
    expect($result)->toBe($expected);
}

/**
 * Data provider for the calculation test
 */
function calculation_data_provider()
{
    return [
        'scenario1' => [10, 20],
        'scenario2' => [20, 40],
        'scenario3' => [30, 60],
    ];
}

/**
 * Test for validation rules
 */
#[Test]
#[Group('unit')]
#[Group('plugin-name')] // Replace with your plugin name
#[Group('validation')] // Example of a specific test category
#[Group('error-handling')] // Example of combining multiple specific categories
#[Description('Test model validation rules')]
function model_validation_rules()
{
    // Create a validator with invalid data
    $validator = Validator::make(
        ['attribute1' => null], // Invalid data
        ['attribute1' => 'required'] // Validation rules
    );

    // Verify validation fails
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('attribute1'))->toBeTrue();

    // Create a validator with valid data
    $validator = Validator::make(
        ['attribute1' => 'value1'], // Valid data
        ['attribute1' => 'required'] // Validation rules
    );

    // Verify validation passes
    expect($validator->fails())->toBeFalse();
}
