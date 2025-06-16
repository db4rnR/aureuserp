<?php

use Webkul\Invoice\Models\Partner;
use Webkul\Partner\Enums\AccountType;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Country;
use Webkul\Support\Models\State;
use Webkul\Partner\Models\Title;
use Webkul\Partner\Models\Industry;

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test partners listing page loads successfully')]
function partners_listing_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the partners listing page
    $response = get(route('filament.admin.customer.resources.partner.index'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test partner creation page loads successfully')]
function partner_creation_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the partner creation page
    $response = get(route('filament.admin.customer.resources.partner.create'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test partner can be created successfully')]
function partner_can_be_created_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create dependencies
    $company = Company::factory()->create();
    $country = Country::factory()->create();
    $state = State::factory()->create(['country_id' => $country->id]);
    $title = Title::factory()->create();
    $industry = Industry::factory()->create();

    // Act as the user
    actingAs($user);

    // Prepare partner data
    $partnerData = [
        'account_type' => AccountType::COMPANY->value,
        'name' => 'Test Partner',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'mobile' => '0987654321',
        'website' => 'https://example.com',
        'tax_id' => 'TAX123456',
        'company_registry' => 'REG123456',
        'street1' => '123 Main St',
        'street2' => 'Suite 100',
        'city' => 'Test City',
        'zip' => '12345',
        'state_id' => $state->id,
        'country_id' => $country->id,
        'company_id' => $company->id,
        'title_id' => $title->id,
        'industry_id' => $industry->id,
    ];

    // Submit the partner creation form
    $response = post(route('filament.admin.customer.resources.partner.store'), $partnerData);

    // Assert the partner was created successfully
    $response->assertRedirect(route('filament.admin.customer.resources.partner.index'));

    // Assert the partner exists in the database
    expect(Partner::where('name', 'Test Partner')
        ->where('email', 'test@example.com')
        ->exists())->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test partner can be viewed successfully')]
function partner_can_be_viewed_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a partner
    $partner = Partner::factory()->create([
        'name' => 'Test Partner',
        'email' => 'test@example.com',
    ]);

    // Act as the user
    actingAs($user);

    // Visit the partner view page
    $response = get(route('filament.admin.customer.resources.partner.view', $partner));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test partner can be edited successfully')]
function partner_can_be_edited_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a partner
    $partner = Partner::factory()->create([
        'name' => 'Test Partner',
        'email' => 'test@example.com',
    ]);

    // Act as the user
    actingAs($user);

    // Visit the partner edit page
    $response = get(route('filament.admin.customer.resources.partner.edit', $partner));

    // Assert the page loads successfully
    $response->assertSuccessful();

    // Prepare updated partner data
    $updatedData = [
        'name' => 'Updated Partner',
        'email' => 'updated@example.com',
    ];

    // Submit the partner edit form
    $response = patch(route('filament.admin.customer.resources.partner.update', $partner), $updatedData);

    // Assert the partner was updated successfully
    $response->assertRedirect(route('filament.admin.customer.resources.partner.view', $partner));

    // Assert the partner was updated in the database
    $updatedPartner = Partner::find($partner->id);
    expect($updatedPartner->name)->toBe('Updated Partner');
    expect($updatedPartner->email)->toBe('updated@example.com');
}
