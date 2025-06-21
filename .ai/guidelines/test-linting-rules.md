# Test Linting Rules

This document describes the linting rules used to enforce test style standards in the project.

## Overview

The project uses custom PHPStan rules to enforce test style standards. These rules check that test functions have the required PHP attributes and PHPDoc blocks.

## Rules

### TestClassAttributesRule

This rule checks that test functions have the following PHP attributes:

- `#[Group]` - To categorize the test
- `#[CoversClass]` - To indicate which class is being tested
- `#[PluginTest]` - To indicate which plugin is being tested

Example of a compliant test function:

```php
/**
 * Test Invoice model attributes and relationships
 *
 * This test verifies that the Invoice model's attributes are correctly set
 * and that its relationships with other models are properly defined.
 */
#[Test]
#[Group('unit')]
#[Group('invoices')]
#[PluginTest('Invoices')]
#[CoversClass(Invoice::class)]
#[Description('Test Invoice model attributes and relationships')]
function invoice_model_attributes_and_relationships()
{
    // Test implementation
}
```

### TestFunctionDocBlockRule

This rule checks that test functions have a PHPDoc block that describes:

- What is being tested
- The expected outcome
- Any special setup or conditions

The PHPDoc block should be comprehensive (at least 50 characters long).

Example of a compliant PHPDoc block:

```php
/**
 * Test Invoice model attributes and relationships
 *
 * This test verifies that the Invoice model's attributes are correctly set
 * and that its relationships with other models are properly defined.
 */
```

## Usage

To run the linting rules on the test files, use the `lint-tests.sh` script:

```bash
./lint-tests.sh
```

This script runs PHPStan with the custom rules defined in `phpstan-tests.neon`.

## Integration with CI/CD

The linting rules can be integrated into the CI/CD pipeline to ensure that all test files follow the style standards. Add the following step to your CI/CD configuration:

```yaml
- name: Lint Tests
  run: ./lint-tests.sh
```

## Fixing Issues

If the linting rules report issues with your test files, you can fix them by:

1. Adding the required PHP attributes to the test functions
2. Adding or improving the PHPDoc blocks for the test functions

## References

- [Testing Standards](testing-standards.md)
- [PHP Attributes Standards](test-templates/php-attributes-standards.md)
