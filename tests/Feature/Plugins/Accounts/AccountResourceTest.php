<?php

declare(strict_types=1);

use Webkul\Account\Enums\AccountType;
use Webkul\Account\Models\Account;
use Webkul\Security\Models\User;

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test account listing page loads successfully')]
function account_listing_page_loads_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the accounts listing page
    $response = get(route('filament.admin.resources.accounts.accounts.index'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test account creation page loads successfully')]
function account_creation_page_loads_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the account creation page
    $response = get(route('filament.admin.resources.accounts.accounts.create'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test account can be created successfully')]
function account_can_be_created_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Prepare account data
    $accountData = [
        'name' => 'Test Account',
        'code' => '101001',
        'account_type' => AccountType::ASSET_CURRENT->value,
        'deprecated' => false,
        'reconcile' => true,
        'non_trade' => false,
    ];

    // Submit the account creation form
    $response = post(route('filament.admin.resources.accounts.accounts.store'), $accountData);

    // Assert the account was created successfully
    $response->assertRedirect(route('filament.admin.resources.accounts.accounts.index'));

    // Assert the account exists in the database
    expect(Account::where('name', 'Test Account')
        ->where('code', '101001')
        ->where('account_type', AccountType::ASSET_CURRENT)
        ->exists())->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test account can be updated successfully')]
function account_can_be_updated_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create an account
    $account = Account::factory()->create([
        'name' => 'Original Account Name',
        'code' => '101001',
        'account_type' => AccountType::ASSET_CURRENT->value,
    ]);

    // Act as the user
    actingAs($user);

    // Prepare updated account data
    $updatedData = [
        'name' => 'Updated Account Name',
        'code' => '101002',
        'account_type' => AccountType::ASSET_CASH->value,
        'deprecated' => true,
        'reconcile' => false,
        'non_trade' => true,
    ];

    // Submit the account update form
    $response = put(route('filament.admin.resources.accounts.accounts.update', $account), $updatedData);

    // Assert the account was updated successfully
    $response->assertRedirect(route('filament.admin.resources.accounts.accounts.index'));

    // Assert the account was updated in the database
    $updatedAccount = Account::find($account->id);
    expect($updatedAccount->name)->toBe('Updated Account Name');
    expect($updatedAccount->code)->toBe('101002');
    expect($updatedAccount->account_type)->toBe(AccountType::ASSET_CASH);
    expect($updatedAccount->deprecated)->toBeTrue();
    expect($updatedAccount->reconcile)->toBeFalse();
    expect($updatedAccount->non_trade)->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test account can be deleted successfully')]
function account_can_be_deleted_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create an account
    $account = Account::factory()->create();

    // Act as the user
    actingAs($user);

    // Delete the account
    $response = delete(route('filament.admin.resources.accounts.accounts.destroy', $account));

    // Assert the account was deleted successfully
    $response->assertRedirect(route('filament.admin.resources.accounts.accounts.index'));

    // Assert the account was deleted from the database
    expect(Account::find($account->id))->toBeNull();
}
