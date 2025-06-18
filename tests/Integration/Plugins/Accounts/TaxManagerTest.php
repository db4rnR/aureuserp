<?php

declare(strict_types=1);

use Webkul\Account\Models\Tax;
use Webkul\Account\Models\TaxGroup;
use Webkul\Account\TaxManager;

#[Test]
#[Group('integration')]
#[Group('accounts')]
#[Description('Test tax calculation with percentage tax')]
function tax_calculation_with_percentage_tax(): void
{
    // Create a tax with percentage amount type
    $tax = Tax::factory()->create([
        'name' => 'VAT 20%',
        'amount' => 20,
        'amount_type' => 'percent',
    ]);

    // Create tax manager instance
    $taxManager = app(TaxManager::class);

    // Calculate tax for a price of 100
    $price = 100;
    $taxAmount = $taxManager->calculateTax($price, $tax);

    // Assert the tax amount is calculated correctly (20% of 100 = 20)
    expect($taxAmount)->toBe(20);

    // Calculate tax for a price of 50
    $price = 50;
    $taxAmount = $taxManager->calculateTax($price, $tax);

    // Assert the tax amount is calculated correctly (20% of 50 = 10)
    expect($taxAmount)->toBe(10);
}

#[Test]
#[Group('integration')]
#[Group('accounts')]
#[Description('Test tax calculation with fixed tax')]
function tax_calculation_with_fixed_tax(): void
{
    // Create a tax with fixed amount type
    $tax = Tax::factory()->create([
        'name' => 'Fixed Tax',
        'amount' => 10,
        'amount_type' => 'fixed',
    ]);

    // Create tax manager instance
    $taxManager = app(TaxManager::class);

    // Calculate tax for a price of 100
    $price = 100;
    $taxAmount = $taxManager->calculateTax($price, $tax);

    // Assert the tax amount is calculated correctly (fixed 10)
    expect($taxAmount)->toBe(10);

    // Calculate tax for a price of 50
    $price = 50;
    $taxAmount = $taxManager->calculateTax($price, $tax);

    // Assert the tax amount is calculated correctly (still fixed 10)
    expect($taxAmount)->toBe(10);
}

#[Test]
#[Group('integration')]
#[Group('accounts')]
#[Description('Test tax calculation with tax group')]
function tax_calculation_with_tax_group(): void
{
    // Create taxes
    $tax1 = Tax::factory()->create([
        'name' => 'VAT 10%',
        'amount' => 10,
        'amount_type' => 'percent',
    ]);

    $tax2 = Tax::factory()->create([
        'name' => 'VAT 5%',
        'amount' => 5,
        'amount_type' => 'percent',
    ]);

    // Create a tax group with both taxes
    $taxGroup = TaxGroup::factory()->create([
        'name' => 'Combined Tax',
    ]);

    // Attach taxes to the tax group
    $taxGroup->taxes()->attach([$tax1->id, $tax2->id]);

    // Create tax manager instance
    $taxManager = app(TaxManager::class);

    // Calculate tax for a price of 100 using the tax group
    $price = 100;
    $taxAmount = $taxManager->calculateTaxGroup($price, $taxGroup);

    // Assert the tax amount is calculated correctly (10% + 5% of 100 = 15)
    expect($taxAmount)->toBe(15);
}

#[Test]
#[Group('integration')]
#[Group('accounts')]
#[Description('Test tax calculation with inclusive price')]
function tax_calculation_with_inclusive_price(): void
{
    // Create a tax with percentage amount type
    $tax = Tax::factory()->create([
        'name' => 'VAT 20%',
        'amount' => 20,
        'amount_type' => 'percent',
    ]);

    // Create tax manager instance
    $taxManager = app(TaxManager::class);

    // Calculate tax for an inclusive price of 120 (price including 20% tax)
    $inclusivePrice = 120;
    $taxAmount = $taxManager->calculateInclusiveTax($inclusivePrice, $tax);

    // Assert the tax amount is calculated correctly (20% of 100 = 20)
    expect($taxAmount)->toBe(20);

    // Calculate the base price (price without tax)
    $basePrice = $inclusivePrice - $taxAmount;

    // Assert the base price is calculated correctly (120 - 20 = 100)
    expect($basePrice)->toBe(100);
}

#[Test]
#[Group('integration')]
#[Group('accounts')]
#[Description('Test tax calculation with discount')]
function tax_calculation_with_discount(): void
{
    // Create a tax with percentage amount type
    $tax = Tax::factory()->create([
        'name' => 'VAT 20%',
        'amount' => 20,
        'amount_type' => 'percent',
    ]);

    // Create tax manager instance
    $taxManager = app(TaxManager::class);

    // Calculate tax for a price of 100 with a 10% discount
    $price = 100;
    $discount = 10; // 10%
    $discountedPrice = $price * (1 - $discount / 100); // 90
    $taxAmount = $taxManager->calculateTax($discountedPrice, $tax);

    // Assert the tax amount is calculated correctly (20% of 90 = 18)
    expect($taxAmount)->toBe(18);
}
