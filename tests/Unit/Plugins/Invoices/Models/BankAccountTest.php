<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Invoice\Models\BankAccount;
use Webkul\Partner\Models\BankAccount as BaseBankAccount;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Bank;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test BankAccount model inheritance and properties')]
function bank_account_model_inheritance_and_properties()
{
    // Create a test bank account
    $bankAccount = BankAccount::factory()->create([
        'account_number' => '123456789',
        'is_active' => true,
        'can_send_money' => true,
    ]);

    // Test that it's an instance of both the Invoice BankAccount and the base Partner BankAccount
    expect($bankAccount)->toBeInstanceOf(BankAccount::class);
    expect($bankAccount)->toBeInstanceOf(BaseBankAccount::class);

    // Test attributes
    expect($bankAccount->account_number)->toBe('123456789');
    expect($bankAccount->is_active)->toBeTrue();
    expect($bankAccount->can_send_money)->toBeTrue();

    // Test relationships inherited from the base class
    expect($bankAccount->bank())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($bankAccount->partner())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($bankAccount->creator())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test BankAccount model relationships with other models')]
function bank_account_model_relationships_with_other_models()
{
    // Create related models
    $bank = Bank::factory()->create();
    $partner = Partner::factory()->create(['name' => 'Test Partner']);
    $user = User::factory()->create();

    // Create a bank account with relationships
    $bankAccount = BankAccount::factory()->create([
        'bank_id' => $bank->id,
        'partner_id' => $partner->id,
        'creator_id' => $user->id,
    ]);

    // Test relationships
    expect($bankAccount->bank->id)->toBe($bank->id);
    expect($bankAccount->partner->id)->toBe($partner->id);
    expect($bankAccount->creator->id)->toBe($user->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test BankAccount model boot methods')]
function bank_account_model_boot_methods()
{
    // Create a partner
    $partner = Partner::factory()->create(['name' => 'Test Partner']);

    // Create a bank account with the partner
    $bankAccount = BankAccount::factory()->create([
        'partner_id' => $partner->id,
    ]);

    // Test that the account_holder_name was set to the partner's name
    expect($bankAccount->account_holder_name)->toBe('Test Partner');

    // Update the partner's name
    $partner->update(['name' => 'Updated Partner Name']);

    // Update the bank account to trigger the updating event
    $bankAccount->update(['is_active' => false]);

    // Refresh the bank account from the database
    $bankAccount->refresh();

    // Test that the account_holder_name was updated to the partner's new name
    expect($bankAccount->account_holder_name)->toBe('Updated Partner Name');
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test BankAccount model traits')]
function bank_account_model_traits()
{
    // Create a test bank account
    $bankAccount = BankAccount::factory()->create();

    // Test that the model uses the expected traits
    expect($bankAccount)->toBeInstanceOf(\Illuminate\Database\Eloquent\SoftDeletes::class);
}
