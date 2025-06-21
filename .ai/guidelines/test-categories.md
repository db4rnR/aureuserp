# Test Categories Guidelines

This document defines the comprehensive test categorization scheme for the project, including how to use categories and what categories are available.

## Using Test Categories

Test categories in the project are implemented using PHP attributes with the `#[Group]` attribute. Multiple categories can be applied to a single test.

### Basic Usage

```php
#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Group('database')]
function test_invoice_creation_stores_in_database()
{
    // Test code
}
```

### Running Tests by Category

Tests can be run by category using the `--group` option with Pest:

```bash
# Run all tests in the 'api' group
./vendor/bin/pest --group=api

# Run all tests in the 'database' group
./vendor/bin/pest --group=database

# Run all tests in both 'api' and 'database' groups
./vendor/bin/pest --group=api,database
```

We've also added convenient Composer scripts for common categories:

```bash
# Run all unit tests
composer test:unit

# Run all feature tests
composer test:feature

# Run all integration tests
composer test:integration

# Run all database tests
composer test:database

# Run all API tests
composer test:api

# Run all performance tests
composer test:performance

# Run all security tests
composer test:security
```

## Category Types

Our test categorization scheme includes four types of categories:

1. **Test Type Categories**: Define the type of test (unit, feature, integration)
2. **Technical Categories**: Define the technical area being tested (database, API, UI, etc.)
3. **Domain Categories**: Define the business domain or functionality being tested
4. **Cross-Cutting Categories**: Define aspects that cut across different domains (security, validation, etc.)

## Available Categories

### Test Type Categories (Required)

Every test must have exactly one of these categories:

- `unit`: Tests for individual components in isolation
- `feature`: Tests for features from an HTTP perspective
- `integration`: Tests for interactions between components
- `arch`: Tests for architectural constraints and rules
- `stress`: Tests for performance under load

### Plugin Categories (Required for Plugin Tests)

Every test for plugin code must have exactly one of these categories:

- `accounts`: Tests for the Accounts plugin
- `invoices`: Tests for the Invoices plugin
- `payments`: Tests for the Payments plugin
- `products`: Tests for the Products plugin
- `inventory`: Tests for the Inventory plugin
- `manufacturing`: Tests for the Manufacturing plugin
- `purchasing`: Tests for the Purchasing plugin
- `sales`: Tests for the Sales plugin
- `crm`: Tests for the CRM plugin
- `hr`: Tests for the HR plugin
- `projects`: Tests for the Projects plugin
- `website`: Tests for the Website plugin

### Technical Categories (Optional)

These categories define the technical area being tested:

- `database`: Tests involving database operations
- `api`: Tests for API endpoints
- `ui`: Tests for user interface components
- `cli`: Tests for command-line interfaces
- `events`: Tests for event handling
- `cache`: Tests for caching functionality
- `queue`: Tests for queue functionality
- `mail`: Tests for email functionality
- `notification`: Tests for notification functionality
- `storage`: Tests for file storage functionality
- `auth`: Tests for authentication functionality

### Domain Categories (Optional)

These categories define the business domain or functionality being tested:

- `billing`: Tests for billing functionality
- `reporting`: Tests for reporting functionality
- `tax`: Tests for tax calculation functionality
- `shipping`: Tests for shipping functionality
- `discount`: Tests for discount functionality
- `pricing`: Tests for pricing functionality
- `inventory-management`: Tests for inventory management functionality
- `user-management`: Tests for user management functionality
- `workflow`: Tests for workflow functionality
- `import-export`: Tests for import/export functionality
- `integration-external`: Tests for integration with external systems

### Cross-Cutting Categories (Optional)

These categories define aspects that cut across different domains:

- `security`: Tests for security features and vulnerabilities
- `validation`: Tests for input validation
- `error-handling`: Tests for error handling
- `performance`: Tests for performance characteristics
- `accessibility`: Tests for accessibility features
- `localization`: Tests for localization and internationalization
- `compatibility`: Tests for compatibility with different environments
- `regression`: Tests for regression issues
- `edge-case`: Tests for edge cases and boundary conditions
- `smoke`: Basic smoke tests for critical functionality
- `critical-path`: Tests for critical business paths

## Category Combinations

Categories can be combined to provide more specific categorization. For example:

```php
#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Group('database')]
#[Group('validation')]
function test_invoice_validation_rejects_invalid_data()
{
    // Test code
}
```

This test is categorized as:
- A unit test
- For the Invoices plugin
- Testing database operations
- Focusing on validation

## Best Practices

1. **Be Consistent**: Use the same categories for similar tests.
2. **Don't Overuse**: Apply only the categories that are relevant to the test.
3. **Required Categories**: Always include a test type category and, for plugin tests, a plugin category.
4. **Descriptive Names**: Use descriptive test names that complement the categories.
5. **Documentation**: Document the categories used in the test file's header comment.
6. **Maintenance**: Periodically review and update categories as the application evolves.

## Adding New Categories

If you need to add a new category:

1. Update this document with the new category and its description.
2. Add a new Composer script in `composer.json` if appropriate.
3. Update the CI/CD pipeline to include the new category if needed.
4. Communicate the new category to the team.
