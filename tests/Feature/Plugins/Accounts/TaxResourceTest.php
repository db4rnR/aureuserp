<?php

use Webkul\Account\Models\Tax;
use Webkul\Account\Models\TaxGroup;
use Webkul\Security\Models\User;

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test tax listing page loads successfully')]
function tax_listing_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the taxes listing page
    $response = get(route('filament.admin.resources.accounts.taxes.index'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test tax creation page loads successfully')]
function tax_creation_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the tax creation page
    $response = get(route('filament.admin.resources.accounts.taxes.create'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test tax can be created successfully')]
function tax_can_be_created_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Prepare tax data
    $taxData = [
        'name' => 'VAT 20%',
        'amount' => 20,
        'amount_type' => 'percent',
        'type_tax_use' => 'sale',
        'active' => true,
        'description' => 'Value Added Tax at 20%',
    ];

    // Submit the tax creation form
    $response = post(route('filament.admin.resources.accounts.taxes.store'), $taxData);

    // Assert the tax was created successfully
    $response->assertRedirect(route('filament.admin.resources.accounts.taxes.index'));

    // Assert the tax exists in the database
    expect(Tax::where('name', 'VAT 20%')
        ->where('amount', 20)
        ->where('type_tax_use', 'sale')
        ->exists())->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test tax can be updated successfully')]
function tax_can_be_updated_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a tax
    $tax = Tax::factory()->create([
        'name' => 'VAT 20%',
        'amount' => 20,
        'amount_type' => 'percent',
        'type_tax_use' => 'sale',
    ]);

    // Act as the user
    actingAs($user);

    // Prepare updated tax data
    $updatedData = [
        'name' => 'VAT 25%',
        'amount' => 25,
        'amount_type' => 'percent',
        'type_tax_use' => 'sale',
        'active' => true,
        'description' => 'Updated Value Added Tax at 25%',
    ];

    // Submit the tax update form
    $response = put(route('filament.admin.resources.accounts.taxes.update', $tax), $updatedData);

    // Assert the tax was updated successfully
    $response->assertRedirect(route('filament.admin.resources.accounts.taxes.index'));

    // Assert the tax was updated in the database
    $updatedTax = Tax::find($tax->id);
    expect($updatedTax->name)->toBe('VAT 25%');
    expect($updatedTax->amount)->toBe(25);
    expect($updatedTax->description)->toBe('Updated Value Added Tax at 25%');
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test tax can be added to a tax group')]
function tax_can_be_added_to_tax_group()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a tax
    $tax = Tax::factory()->create([
        'name' => 'VAT 20%',
        'amount' => 20,
    ]);

    // Create a tax group
    $taxGroup = TaxGroup::factory()->create([
        'name' => 'Standard Rate',
    ]);

    // Act as the user
    actingAs($user);

    // Prepare tax group update data to add the tax
    $updateData = [
        'name' => 'Standard Rate',
        'tax_ids' => [$tax->id],
    ];

    // Submit the tax group update form
    $response = put(route('filament.admin.resources.accounts.tax-groups.update', $taxGroup), $updateData);

    // Assert the tax group was updated successfully
    $response->assertRedirect(route('filament.admin.resources.accounts.tax-groups.index'));

    // Assert the tax was added to the tax group
    $updatedTaxGroup = TaxGroup::with('taxes')->find($taxGroup->id);
    expect($updatedTaxGroup->taxes->contains($tax))->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test tax can be deleted successfully')]
function tax_can_be_deleted_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a tax
    $tax = Tax::factory()->create();

    // Act as the user
    actingAs($user);

    // Delete the tax
    $response = delete(route('filament.admin.resources.accounts.taxes.destroy', $tax));

    // Assert the tax was deleted successfully
    $response->assertRedirect(route('filament.admin.resources.accounts.taxes.index'));

    // Assert the tax was deleted from the database
    expect(Tax::find($tax->id))->toBeNull();
}
