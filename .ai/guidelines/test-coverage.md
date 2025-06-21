# Test Coverage Guidelines

This document outlines the test coverage requirements, tools, and processes for the project.

## Coverage Requirements

We aim to maintain a minimum of 70% code coverage across the codebase, with a focus on critical business logic. This threshold applies to:

- Overall project coverage
- Individual plugin coverage
- New code contributions

Coverage is measured in terms of:
- Line coverage (percentage of code lines executed during tests)
- Method coverage (percentage of methods/functions called during tests)
- Statement coverage (percentage of statements executed during tests)

## Coverage Tools

### Local Development

For local development, we have several tools available:

1. **Running Tests with Coverage**

   ```bash
   # Run all tests with coverage
   composer test:coverage
   
   # Generate HTML coverage report
   composer test:coverage-html
   ```

2. **Coverage Dashboard**

   We provide a custom dashboard script that displays coverage metrics for the entire project and individual plugins:

   ```bash
   php scripts/coverage-dashboard.php
   ```

   This script:
   - Runs tests with coverage
   - Parses the coverage reports
   - Displays a formatted dashboard in the console
   - Provides a link to the HTML coverage report

3. **HTML Coverage Reports**

   After running tests with coverage, you can view detailed HTML reports at:
   
   ```
   file://<project-path>/reports/coverage/index.html
   ```

### CI/CD Integration

Coverage is automatically checked during CI/CD pipelines:

1. **GitHub Actions**

   Our GitHub workflow runs tests with coverage on every push and pull request.

2. **Codecov Integration**

   We use [Codecov](https://codecov.io) to track coverage metrics over time. Codecov:
   
   - Provides a dashboard for visualizing coverage
   - Tracks coverage changes between commits
   - Enforces minimum coverage thresholds
   - Adds coverage information to pull requests

## Coverage Configuration

The coverage configuration is defined in the following files:

1. **phpunit.xml**

   Contains the basic configuration for PHPUnit/Pest coverage reporting, including:
   - Which directories to include/exclude from coverage analysis
   - Output formats for coverage reports

2. **codecov.yml**

   Configures the Codecov integration, including:
   - Minimum coverage thresholds (70%)
   - Coverage display settings
   - Failure conditions for CI/CD

## Coverage Alerts

We have several mechanisms to alert about coverage issues:

1. **CI/CD Pipeline Failures**

   If coverage drops below the 70% threshold, the CI/CD pipeline will fail.

2. **Codecov PR Comments**

   Codecov adds comments to pull requests with coverage information, highlighting any coverage decreases.

3. **Coverage Dashboard**

   The coverage dashboard script uses color coding to highlight plugins with insufficient coverage:
   - Green: ≥70% coverage (meets requirements)
   - Yellow: 50-69% coverage (needs improvement)
   - Red: <50% coverage (critical, needs immediate attention)

## Best Practices

1. **Write Tests First**

   Follow a test-driven development approach when possible, writing tests before implementing features.

2. **Focus on Critical Code**

   Prioritize testing business logic and critical code paths over simple getters/setters.

3. **Check Coverage Locally**

   Before submitting a pull request, run the coverage dashboard to ensure your changes maintain or improve coverage.

4. **Address Coverage Gaps**

   When you identify areas with low coverage, add tests to cover those areas, especially for critical functionality.

5. **Don't Game the System**

   The goal is not just to achieve 70% coverage, but to have meaningful tests that verify the code works correctly. Focus on test quality, not just quantity.

## Exemptions

In some cases, certain files or code blocks may be exempted from coverage requirements:

1. **Configuration Files**

   Simple configuration files may not need extensive testing.

2. **Generated Code**

   Automatically generated code (e.g., from migrations) may be exempted.

3. **Third-Party Integrations**

   Code that primarily interacts with third-party services may have lower coverage requirements if those services are difficult to mock.

To exempt specific lines or blocks from coverage analysis, use the appropriate PHPDoc annotations:

```php
// @codeCoverageIgnore
// @codeCoverageIgnoreStart
// @codeCoverageIgnoreEnd
```

## Troubleshooting

### Common Issues

1. **Xdebug Not Enabled**

   Coverage analysis requires Xdebug. If you see an error about coverage not being available, ensure Xdebug is installed and enabled.

2. **Slow Test Execution**

   Coverage analysis can slow down test execution. For faster development cycles, run tests without coverage during development and only check coverage before committing.

3. **Memory Limits**

   Coverage analysis requires more memory. If you encounter memory limit errors, increase the PHP memory limit in your php.ini file.
