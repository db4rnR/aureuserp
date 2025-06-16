# Tasks for Testing Framework Improvement

## Relevant Files

### Created/Modified Files:
- `.junie/testing-improvement/prioritised-plugins.md` - Document identifying and prioritizing all plugins for test implementation
- `tests/Unit/Plugins/Invoices/Models/InvoiceTest.php` - Unit tests for the Invoice model
- `tests/Unit/Plugins/Invoices/Models/PaymentTermTest.php` - Unit tests for the PaymentTerm model
- `tests/Unit/Plugins/Invoices/Models/ProductTest.php` - Unit tests for the Product model
- `tests/Unit/Plugins/Invoices/Models/BillTest.php` - Unit tests for the Bill model
- `tests/Unit/Plugins/Invoices/Models/AttributeTest.php` - Unit tests for the Attribute model
- `tests/Unit/Plugins/Invoices/Models/BankAccountTest.php` - Unit tests for the BankAccount model
- `tests/Unit/Plugins/Invoices/Models/CategoryTest.php` - Unit tests for the Category model
- `tests/Unit/Plugins/Invoices/Models/CreditNoteTest.php` - Unit tests for the CreditNote model
- `tests/Unit/Plugins/Invoices/Models/IncotermTest.php` - Unit tests for the Incoterm model
- `tests/Unit/Plugins/Invoices/Models/PartnerTest.php` - Unit tests for the Partner model
- `tests/Unit/Plugins/Invoices/Models/PaymentTest.php` - Unit tests for the Payment model
- `tests/Unit/Plugins/Invoices/Models/RefundTest.php` - Unit tests for the Refund model
- `tests/Unit/Plugins/Invoices/Models/TaxTest.php` - Unit tests for the Tax model
- `tests/Unit/Plugins/Invoices/Models/TaxGroupTest.php` - Unit tests for the TaxGroup model

### All models in the Invoices plugin now have tests!

### For PHP/Laravel Projects:
- `tests/TestCase.php` - Base test class that needs enhancement with utilities and helpers
- `tests/Unit/Plugins/Accounts/` - Directory containing existing tests to use as reference
- `tests/Feature/Plugins/Accounts/` - Directory containing existing feature tests to use as reference
- `tests/Integration/Plugins/Accounts/` - Directory containing existing integration tests to use as reference
- `tests/Traits/` - Directory for new test traits (API testing, authentication, etc.)
- `tests/Factories/` - Directory for test data factories
- `tests/Helpers/` - Directory for test helper functions
- `phpunit.xml` - PHPUnit configuration file that needs updates for coverage reporting and parallel testing
- `pest.config.php` - Pest configuration file that may need updates for test categories
- `composer.json` - For adding new testing dependencies (Larastan, Mockery, Infection PHP, Laravel Dusk)
- `.github/workflows/` - CI/CD pipeline files for integrating test coverage reporting
- `.junie/guidelines/testing-standards.md` - New file for comprehensive testing standards documentation
- `.junie/guidelines/test-templates/` - Directory for test templates
- `app/Plugins/*/Tests/` - Potential location for plugin-specific test utilities
- `database/factories/` - For model factories used in tests

#### Files to be created for each plugin:
- `tests/Unit/Plugins/[PluginName]/Models/` - Unit tests for plugin models
- `tests/Unit/Plugins/[PluginName]/Services/` - Unit tests for plugin services
- `tests/Feature/Plugins/[PluginName]/Controllers/` - Feature tests for plugin controllers
- `tests/Feature/Plugins/[PluginName]/API/` - Feature tests for plugin API endpoints
- `tests/Integration/Plugins/[PluginName]/` - Integration tests for plugin components

### Notes

#### For PHP/Laravel Projects:
- Unit tests should be placed in the `tests/Unit` directory, mirroring the structure of the `app` directory.
- Feature tests should be placed in the `tests/Feature` directory.
- Integration tests should be placed in the `tests/Integration` directory.
- Use `php artisan test` or `./vendor/bin/pest` to run tests. Add `--filter=TestClassName` to run specific tests.
- For Pest tests, use `./vendor/bin/pest --coverage` to generate coverage reports.

#### Additional Testing Tools:
- **Larastan**: Install via Composer (`composer require -Wo --dev larastan/larastan`). Run with `./vendor/bin/phpstan 
analyse`.
- **Mockery**: Install via Composer (`composer require -Wo --dev mockery/mockery`). Use in tests with `$mock = Mockery::mock(ClassToMock::class)`.
- **Infection PHP**: Install via Composer (`composer require -Wo --dev infection/infection`). Run with `./vendor/bin/infection`.
- **Laravel Dusk**: Install via Composer (`composer require -Wo --dev laravel/dusk`). Set up with `php artisan dusk:install`.

#### Test Categories:
- Use PHP attributes to categorize tests: `#[Group('api')]`, `#[Group('database')]`, etc.
- Run specific test categories with `./vendor/bin/pest --group=api`.

#### Test Performance:
- Configure parallel testing in phpunit.xml with `<testsuite name="Parallel">...</testsuite>`.
- Use database transactions for tests to avoid expensive database resets: `use RefreshDatabase;`.
- Consider using in-memory SQLite database for faster test execution.

## Tasks

- [ ] 1.0 Expand Plugin Test Coverage
  - [✅] 1.1 Identify and prioritize **all** plugins for test implementation
  - [ ] 1.2 Create Unit tests for models in each selected plugin (Started with Invoices plugin)
  - [ ] 1.3 Implement Feature tests for HTTP endpoints in each selected plugin
  - [ ] 1.4 Develop Integration tests for service classes in each selected plugin
  - [ ] 1.5 Ensure test coverage for critical business logic in each plugin
  - [ ] 1.6 Verify all tests pass and provide meaningful feedback on failures

- [ ] 2.0 Standardize Test Style and Approach
  - [ ] 2.1 Create a comprehensive style guide document for test creation
  - [ ] 2.2 Define standards for PHP attributes usage in tests
  - [ ] 2.3 Develop templates for Unit, Feature, and Integration tests
  - [ ] 2.4 Create generators for new tests to ensure consistency
  - [ ] 2.5 Audit existing tests and update them to follow the standard style
  - [ ] 2.6 Implement linting rules to enforce test style standards

- [ ] 3.0 Enhance TestCase Class with Utilities and Helpers
  - [ ] 3.1 Analyze common test patterns in existing tests
  - [ ] 3.2 Add utility methods to the base TestCase class
  - [ ] 3.3 Create traits for API testing scenarios
  - [ ] 3.4 Create traits for authentication testing
  - [ ] 3.5 Implement test data factories for all models
  - [ ] 3.6 Add methods for common assertions
  - [ ] 3.7 Develop helpers for test setup and teardown

- [ ] 4.0 Implement Test Coverage Reporting
  - [ ] 4.1 Research and select appropriate coverage reporting tools compatible with Pest
  - [ ] 4.2 Configure tools to generate test coverage reports
  - [ ] 4.3 Set minimum coverage targets for each plugin (70%)
  - [ ] 4.4 Integrate coverage reporting into CI/CD pipeline
  - [ ] 4.5 Create a dashboard for visualizing test coverage metrics
  - [ ] 4.6 Implement alerts for coverage drops below targets

- [ ] 5.0 Add Specific Test Categories
  - [ ] 5.1 Define a comprehensive test categorization scheme
  - [ ] 5.2 Implement technical categories (database, API, UI, performance)
  - [ ] 5.3 Create domain-specific categories for business logic areas
  - [ ] 5.4 Add categories for security, validation, and error handling tests
  - [ ] 5.5 Update existing tests with appropriate categories
  - [ ] 5.6 Document how to run tests by category

- [ ] 6.0 Improve Test Documentation
  - [ ] 6.1 Add inline documentation to existing tests
  - [ ] 6.2 Create plugin-specific testing guidelines
  - [ ] 6.3 Document test data requirements and assumptions
  - [ ] 6.4 Provide examples for each test type and category
  - [ ] 6.5 Create a comprehensive testing guide in `.junie/guidelines`
  - [ ] 6.6 Add documentation for test helpers and utilities

- [ ] 7.0 Optimize Test Performance
  - [ ] 7.1 Analyze current test execution times to identify bottlenecks
  - [ ] 7.2 Increase parallel test processes from 4 to 8
  - [ ] 7.3 Implement database seeding optimizations
  - [ ] 7.4 Add selective test running capabilities
  - [ ] 7.5 Optimize test database configuration
  - [ ] 7.6 Implement test caching where appropriate
  - [ ] 7.7 Measure and document performance improvements
