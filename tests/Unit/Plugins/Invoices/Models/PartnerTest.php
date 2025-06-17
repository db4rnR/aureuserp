<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Invoice\Models\Partner;
use Webkul\Account\Models\Partner as AccountPartner;
use Webkul\Partner\Models\Partner as BasePartner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Country;
use Webkul\Support\Models\State;
use Webkul\Partner\Models\Title;
use Webkul\Partner\Models\Industry;
use Webkul\Partner\Models\BankAccount;
use Webkul\Partner\Models\Tag;
use Webkul\Partner\Enums\AccountType;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Partner model inheritance and properties')]
function partner_model_inheritance_and_properties()
{
    // Create a test partner
    $partner = Partner::factory()->create([
        'name' => 'Test Partner',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'account_type' => AccountType::COMPANY,
    ]);

    // Test that it's an instance of the Invoice Partner, Account Partner, and the base Partner
    expect($partner)->toBeInstanceOf(Partner::class);
    expect($partner)->toBeInstanceOf(AccountPartner::class);
    expect($partner)->toBeInstanceOf(BasePartner::class);

    // Test attributes
    expect($partner->name)->toBe('Test Partner');
    expect($partner->email)->toBe('test@example.com');
    expect($partner->phone)->toBe('1234567890');
    expect($partner->account_type)->toBe(AccountType::COMPANY);

    // Test relationships inherited from the base class
    expect($partner->country())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->state())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->parent())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->creator())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->user())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->title())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->company())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->industry())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->addresses())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($partner->contacts())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($partner->bankAccounts())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($partner->tags())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);

    // Test relationships inherited from the Account Partner class
    expect($partner->propertyAccountPayable())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->propertyAccountReceivable())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->propertyAccountPosition())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->propertyPaymentTerm())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->propertySupplierPaymentTerm())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->propertyOutboundPaymentMethodLine())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($partner->propertyInboundPaymentMethodLine())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Partner model relationships with other models')]
function partner_model_relationships_with_other_models()
{
    // Create related models
    $user = User::factory()->create();
    $company = Company::factory()->create();
    $country = Country::factory()->create();
    $state = State::factory()->create();
    $title = Title::factory()->create();
    $industry = Industry::factory()->create();
    $parentPartner = Partner::factory()->create(['name' => 'Parent Partner']);

    // Create a partner with relationships
    $partner = Partner::factory()->create([
        'creator_id' => $user->id,
        'company_id' => $company->id,
        'country_id' => $country->id,
        'state_id' => $state->id,
        'title_id' => $title->id,
        'industry_id' => $industry->id,
        'parent_id' => $parentPartner->id,
        'user_id' => $user->id,
    ]);

    // Create a bank account for this partner
    $bankAccount = BankAccount::factory()->create([
        'partner_id' => $partner->id,
    ]);

    // Create a tag and attach it to the partner
    $tag = Tag::factory()->create();
    $partner->tags()->attach($tag);

    // Test relationships
    expect($partner->creator->id)->toBe($user->id);
    expect($partner->company->id)->toBe($company->id);
    expect($partner->country->id)->toBe($country->id);
    expect($partner->state->id)->toBe($state->id);
    expect($partner->title->id)->toBe($title->id);
    expect($partner->industry->id)->toBe($industry->id);
    expect($partner->parent->id)->toBe($parentPartner->id);
    expect($partner->user->id)->toBe($user->id);
    expect($partner->bankAccounts->count())->toBe(1);
    expect($partner->bankAccounts->first()->id)->toBe($bankAccount->id);
    expect($partner->tags->count())->toBe(1);
    expect($partner->tags->first()->id)->toBe($tag->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Partner model additional fillable fields from Account Partner')]
function partner_model_additional_fillable_fields()
{
    // Create a reflection of the Partner class to access protected properties
    $reflection = new ReflectionClass(Partner::class);
    $fillable = $reflection->getProperty('fillable');
    $fillable->setAccessible(true);
    $fillableFields = $fillable->getValue(new Partner());

    // Test that the additional fillable fields from Account Partner are present
    expect($fillableFields)->toContain('message_bounce');
    expect($fillableFields)->toContain('supplier_rank');
    expect($fillableFields)->toContain('customer_rank');
    expect($fillableFields)->toContain('invoice_warning');
    expect($fillableFields)->toContain('autopost_bills');
    expect($fillableFields)->toContain('credit_limit');
    expect($fillableFields)->toContain('ignore_abnormal_invoice_date');
    expect($fillableFields)->toContain('ignore_abnormal_invoice_amount');
    expect($fillableFields)->toContain('invoice_sending_method');
    expect($fillableFields)->toContain('invoice_edi_format_store');
    expect($fillableFields)->toContain('trust');
    expect($fillableFields)->toContain('invoice_warn_msg');
    expect($fillableFields)->toContain('debit_limit');
    expect($fillableFields)->toContain('peppol_endpoint');
    expect($fillableFields)->toContain('peppol_eas');
    expect($fillableFields)->toContain('sale_warn');
    expect($fillableFields)->toContain('comment');
    expect($fillableFields)->toContain('sale_warn_msg');
    expect($fillableFields)->toContain('property_account_payable_id');
    expect($fillableFields)->toContain('property_account_receivable_id');
    expect($fillableFields)->toContain('property_account_position_id');
    expect($fillableFields)->toContain('property_payment_term_id');
    expect($fillableFields)->toContain('property_supplier_payment_term_id');
    expect($fillableFields)->toContain('property_outbound_payment_method_line_id');
    expect($fillableFields)->toContain('property_inbound_payment_method_line_id');
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Partner model methods')]
function partner_model_methods()
{
    // Create a test partner with an avatar
    $partner = Partner::factory()->create([
        'avatar' => 'partners/avatar.jpg',
    ]);

    // Test the getAvatarUrlAttribute method
    expect($partner->avatar_url)->not->toBeNull();

    // Test the canAccessPanel method
    expect($partner->canAccessPanel(new \Filament\Panel()))->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Partner model traits')]
function partner_model_traits()
{
    // Create a test partner
    $partner = Partner::factory()->create();

    // Test that the model uses the expected traits
    expect($partner)->toBeInstanceOf(\Webkul\Chatter\Traits\HasChatter::class);
    expect($partner)->toBeInstanceOf(\Webkul\Chatter\Traits\HasLogActivity::class);
    expect($partner)->toBeInstanceOf(\Illuminate\Database\Eloquent\SoftDeletes::class);
    expect($partner)->toBeInstanceOf(\Illuminate\Notifications\Notifiable::class);
}
