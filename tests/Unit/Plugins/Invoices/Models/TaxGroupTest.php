<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Invoice\Models\TaxGroup;
use Webkul\Account\Models\TaxGroup as BaseTaxGroup;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Country;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test TaxGroup model inheritance and properties')]
function tax_group_model_inheritance_and_properties()
{
    // Create a test tax group
    $taxGroup = TaxGroup::factory()->create([
        'name' => 'Test Tax Group',
        'preceding_subtotal' => true,
    ]);

    // Test that it's an instance of both the Invoice TaxGroup and the base Account TaxGroup
    expect($taxGroup)->toBeInstanceOf(TaxGroup::class);
    expect($taxGroup)->toBeInstanceOf(BaseTaxGroup::class);

    // Test attributes
    expect($taxGroup->name)->toBe('Test Tax Group');
    expect($taxGroup->preceding_subtotal)->toBeTrue();

    // Test relationships inherited from the base class
    expect($taxGroup->company())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($taxGroup->country())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($taxGroup->createdBy())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test TaxGroup model relationships with other models')]
function tax_group_model_relationships_with_other_models()
{
    // Create related models
    $company = Company::factory()->create();
    $country = Country::factory()->create();
    $user = User::factory()->create();

    // Create a tax group with relationships
    $taxGroup = TaxGroup::factory()->create([
        'company_id' => $company->id,
        'country_id' => $country->id,
        'creator_id' => $user->id,
    ]);

    // Test relationships
    expect($taxGroup->company->id)->toBe($company->id);
    expect($taxGroup->country->id)->toBe($country->id);
    expect($taxGroup->createdBy->id)->toBe($user->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test TaxGroup model traits and interfaces')]
function tax_group_model_traits_and_interfaces()
{
    // Create a test tax group
    $taxGroup = TaxGroup::factory()->create();

    // Test that the model uses the expected traits and implements interfaces
    expect($taxGroup)->toBeInstanceOf(\Spatie\EloquentSortable\Sortable::class);

    // Test sortable configuration
    $sortable = (new \ReflectionClass($taxGroup))->getProperty('sortable')->getValue($taxGroup);
    expect($sortable)->toBeArray();
    expect($sortable)->toHaveKey('order_column_name');
    expect($sortable['order_column_name'])->toBe('sort');
    expect($sortable)->toHaveKey('sort_when_creating');
    expect($sortable['sort_when_creating'])->toBeTrue();
}
