<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Account\Enums\DocumentType;
use Webkul\Account\Enums\RepartitionType;
use Webkul\Account\Models\Account;
use Webkul\Account\Models\Tax as BaseTax;
use Webkul\Account\Models\TaxGroup;
use Webkul\Account\Models\TaxPartition;
use Webkul\Invoice\Models\Tax;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Country;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Tax model inheritance and properties')]
function tax_model_inheritance_and_properties(): void
{
    // Create a test tax
    $tax = Tax::factory()->create([
        'name' => 'Test Tax',
        'description' => 'Test tax description',
        'amount' => 10.0,
        'is_active' => true,
        'include_base_amount' => false,
        'is_base_affected' => true,
        'analytic' => false,
    ]);

    // Test that it's an instance of both the Invoice Tax and the base Account Tax
    expect($tax)->toBeInstanceOf(Tax::class);
    expect($tax)->toBeInstanceOf(BaseTax::class);

    // Test attributes
    expect($tax->name)->toBe('Test Tax');
    expect($tax->description)->toBe('Test tax description');
    expect($tax->amount)->toBe(10.0);
    expect($tax->is_active)->toBeTrue();
    expect($tax->include_base_amount)->toBeFalse();
    expect($tax->is_base_affected)->toBeTrue();
    expect($tax->analytic)->toBeFalse();

    // Test relationships inherited from the base class
    expect($tax->company())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($tax->taxGroup())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($tax->cashBasisTransitionAccount())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($tax->country())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($tax->createdBy())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($tax->distributionForInvoice())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($tax->distributionForRefund())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($tax->parentTaxes())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Tax model relationships with other models')]
function tax_model_relationships_with_other_models(): void
{
    // Create related models
    $company = Company::factory()->create();
    $taxGroup = TaxGroup::factory()->create();
    $account = Account::factory()->create();
    $country = Country::factory()->create();
    $user = User::factory()->create();

    // Create a tax with relationships
    $tax = Tax::factory()->create([
        'company_id' => $company->id,
        'tax_group_id' => $taxGroup->id,
        'cash_basis_transition_account_id' => $account->id,
        'country_id' => $country->id,
        'creator_id' => $user->id,
    ]);

    // Test relationships
    expect($tax->company->id)->toBe($company->id);
    expect($tax->taxGroup->id)->toBe($taxGroup->id);
    expect($tax->cashBasisTransitionAccount->id)->toBe($account->id);
    expect($tax->country->id)->toBe($country->id);
    expect($tax->createdBy->id)->toBe($user->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Tax model boot method for distribution creation')]
function tax_model_boot_method_for_distribution_creation(): void
{
    // Create a test tax
    $tax = Tax::factory()->create();

    // Test that distributions were created
    $invoiceDistributions = TaxPartition::where('tax_id', $tax->id)
        ->where('document_type', DocumentType::INVOICE->value)
        ->get();

    $refundDistributions = TaxPartition::where('tax_id', $tax->id)
        ->where('document_type', DocumentType::REFUND->value)
        ->get();

    // Check that we have 2 distributions for invoice (base and tax)
    expect($invoiceDistributions->count())->toBe(2);
    expect($invoiceDistributions->where('repartition_type', RepartitionType::BASE->value)->count())->toBe(1);
    expect($invoiceDistributions->where('repartition_type', RepartitionType::TAX->value)->count())->toBe(1);

    // Check that we have 2 distributions for refund (base and tax)
    expect($refundDistributions->count())->toBe(2);
    expect($refundDistributions->where('repartition_type', RepartitionType::BASE->value)->count())->toBe(1);
    expect($refundDistributions->where('repartition_type', RepartitionType::TAX->value)->count())->toBe(1);

    // Check the tax distribution properties
    $taxDistribution = $invoiceDistributions->where('repartition_type', RepartitionType::TAX->value)->first();
    expect($taxDistribution->factor_percent)->toBe(100);
    expect($taxDistribution->factor)->toBe(1);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Tax model parent-child relationships')]
function tax_model_parent_child_relationships(): void
{
    // Create parent and child taxes
    $parentTax = Tax::factory()->create(['name' => 'Parent Tax']);
    $childTax = Tax::factory()->create(['name' => 'Child Tax']);

    // Attach child to parent
    $parentTax->parentTaxes()->attach($childTax);

    // Test the relationship
    expect($childTax->parentTaxes->contains($parentTax))->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Tax model traits and interfaces')]
function tax_model_traits_and_interfaces(): void
{
    // Create a test tax
    $tax = Tax::factory()->create();

    // Test that the model uses the expected traits and implements interfaces
    expect($tax)->toBeInstanceOf(Spatie\EloquentSortable\Sortable::class);

    // Test sortable configuration
    $sortable = new ReflectionClass($tax)->getProperty('sortable')->getValue($tax);
    expect($sortable)->toBeArray();
    expect($sortable)->toHaveKey('order_column_name');
    expect($sortable['order_column_name'])->toBe('sort');
    expect($sortable)->toHaveKey('sort_when_creating');
    expect($sortable['sort_when_creating'])->toBeTrue();
}
