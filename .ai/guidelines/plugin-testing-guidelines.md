# Plugin-Specific Testing Guidelines

This document provides guidelines for testing specific plugins in the AureusERP system. Each plugin has unique functionality and requirements that need to be considered when writing tests.

## General Plugin Testing Principles

1. **Test All Layers**: Each plugin should have tests for all layers:
   - Models (Unit tests)
   - Services (Unit tests)
   - Controllers (Feature tests)
   - API endpoints (Feature tests)
   - Integration between components (Integration tests)

2. **Test Critical Paths**: Focus on testing the critical business paths for each plugin.

3. **Use Appropriate Categories**: Apply the appropriate test categories as defined in [Test Categories Guidelines](test-categories.md).

4. **Maintain Test Independence**: Tests for one plugin should not depend on the functionality of another plugin unless explicitly testing integration points.

## Invoices Plugin Testing Guidelines

### Overview

The Invoices plugin manages customer and vendor invoices, credit notes, payments, and related financial documents. It's a critical component of the financial system and requires thorough testing.

### Key Models to Test

- `Invoice`: Represents customer and vendor invoices
- `CreditNote`: Represents credit notes issued to customers or received from vendors
- `Payment`: Represents payments made to vendors or received from customers
- `PaymentTerm`: Represents payment terms that can be applied to invoices
- `Partner`: Represents customers and vendors
- `Product`: Represents products that can be invoiced

### Critical Business Paths

1. **Invoice Creation and Validation**
   - Test that invoices can be created with valid data
   - Test validation rules for required fields
   - Test validation rules for numeric fields (amounts, quantities)
   - Test validation rules for date fields

2. **Invoice Workflow**
   - Test state transitions (draft → posted → paid)
   - Test that appropriate accounting entries are created
   - Test that invoice amounts are calculated correctly

3. **Payment Processing**
   - Test payment creation and validation
   - Test payment matching with invoices
   - Test partial payments and multiple payments for a single invoice

4. **Credit Note Processing**
   - Test credit note creation and validation
   - Test credit note application to invoices
   - Test refund generation from credit notes

### Test Data Requirements

1. **Basic Test Data**
   - At least one customer and one vendor
   - At least one product with pricing information
   - At least one payment term
   - At least one journal for sales and one for purchases
   - At least one currency

2. **Edge Case Test Data**
   - Invoices with multiple tax rates
   - Invoices with discounts
   - Invoices in foreign currencies
   - Invoices with payment terms that include multiple installments

### Example Tests

```php
/**
 * Test invoice creation with valid data
 */
#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Group('database')]
#[Group('validation')]
#[Group('billing')]
function invoice_creation_with_valid_data()
{
    // Test code
}

/**
 * Test invoice state transition from draft to posted
 */
#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Group('database')]
#[Group('workflow')]
#[Group('billing')]
function invoice_state_transition_from_draft_to_posted()
{
    // Test code
}

/**
 * Test payment matching with invoice
 */
#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Group('database')]
#[Group('billing')]
function payment_matching_with_invoice()
{
    // Test code
}
```

## Products Plugin Testing Guidelines

### Overview

The Products plugin manages product information, including attributes, categories, pricing, and inventory. It's a foundational component used by many other plugins.

### Key Models to Test

- `Product`: Represents products that can be sold or purchased
- `Attribute`: Represents product attributes (color, size, etc.)
- `Category`: Represents product categories
- `PriceList`: Represents price lists for products
- `PriceRule`: Represents pricing rules for products

### Critical Business Paths

1. **Product Creation and Validation**
   - Test that products can be created with valid data
   - Test validation rules for required fields
   - Test that products can be assigned to categories
   - Test that products can have attributes

2. **Product Pricing**
   - Test that product prices can be set
   - Test that price lists can be created and applied
   - Test that price rules can be created and applied
   - Test price calculation with various rules

3. **Product Variants**
   - Test that product variants can be created based on attributes
   - Test that variants have correct attribute values
   - Test that variants can have different prices

### Test Data Requirements

1. **Basic Test Data**
   - At least one product category
   - At least one product attribute with options
   - At least one price list
   - At least one product

2. **Edge Case Test Data**
   - Products with multiple attributes
   - Products with many variants
   - Products with complex pricing rules
   - Products in multiple categories

## Payments Plugin Testing Guidelines

### Overview

The Payments plugin manages payment methods, payment transactions, and payment processing. It integrates with various payment gateways and provides a unified interface for payment processing.

### Key Models to Test

- `Payment`: Represents a payment
- `PaymentMethod`: Represents a payment method (credit card, bank transfer, etc.)
- `PaymentTransaction`: Represents a payment transaction with a payment gateway
- `PaymentGateway`: Represents a payment gateway configuration

### Critical Business Paths

1. **Payment Method Configuration**
   - Test that payment methods can be configured
   - Test validation of payment method configuration

2. **Payment Processing**
   - Test payment creation and validation
   - Test payment processing with different payment methods
   - Test payment status updates

3. **Payment Gateway Integration**
   - Test integration with payment gateways
   - Test handling of gateway responses
   - Test error handling for gateway failures

### Test Data Requirements

1. **Basic Test Data**
   - At least one payment method
   - At least one payment gateway configuration
   - At least one customer

2. **Edge Case Test Data**
   - Payments with different currencies
   - Payments with different statuses
   - Payments with different payment methods

## Adding Guidelines for New Plugins

When adding a new plugin to the system, create a new section in this document with the following information:

1. **Overview**: A brief description of the plugin and its purpose
2. **Key Models to Test**: A list of the key models in the plugin that should be tested
3. **Critical Business Paths**: A list of the critical business paths that should be tested
4. **Test Data Requirements**: A description of the test data required for testing the plugin
5. **Example Tests**: Examples of tests for the plugin

This will help ensure that all plugins are tested consistently and thoroughly.
