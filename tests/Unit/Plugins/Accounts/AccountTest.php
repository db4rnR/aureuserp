<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Group;
use Webkul\Account\Enums\AccountType;
use Webkul\Account\Models\Account;
use Webkul\Account\Models\Journal;
use Webkul\Account\Models\Tag;
use Webkul\Account\Models\Tax;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('unit')]
#[Group('accounts')]
#[Description('Test Account model attributes and relationships')]
function account_model_attributes_and_relationships(): void
{
    // Create a test account
    $account = Account::factory()->create([
        'name' => 'Test Account',
        'code' => '101001',
        'account_type' => AccountType::ASSET_CURRENT,
        'deprecated' => false,
        'reconcile' => true,
        'non_trade' => false,
    ]);

    // Test attributes
    expect($account->name)->toBe('Test Account');
    expect($account->code)->toBe('101001');
    expect($account->account_type)->toBe(AccountType::ASSET_CURRENT);
    expect($account->deprecated)->toBeFalse();
    expect($account->reconcile)->toBeTrue();
    expect($account->non_trade)->toBeFalse();

    // Test relationships
    expect($account->currency())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($account->createdBy())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($account->taxes())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    expect($account->tags())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    expect($account->journals())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
}

#[Test]
#[Group('unit')]
#[Group('accounts')]
#[Description('Test Account model relationships with other models')]
function account_model_relationships_with_other_models(): void
{
    // Create a currency
    $currency = Currency::factory()->create();

    // Create a user
    $user = User::factory()->create();

    // Create an account with relationships
    $account = Account::factory()->create([
        'currency_id' => $currency->id,
        'creator_id' => $user->id,
    ]);

    // Create related models
    $tax = Tax::factory()->create();
    $tag = Tag::factory()->create();
    $journal = Journal::factory()->create();

    // Attach related models
    $account->taxes()->attach($tax);
    $account->tags()->attach($tag);
    $account->journals()->attach($journal);

    // Test relationships
    expect($account->currency->id)->toBe($currency->id);
    expect($account->createdBy->id)->toBe($user->id);
    expect($account->taxes->first()->id)->toBe($tax->id);
    expect($account->tags->first()->id)->toBe($tag->id);
    expect($account->journals->first()->id)->toBe($journal->id);
}
