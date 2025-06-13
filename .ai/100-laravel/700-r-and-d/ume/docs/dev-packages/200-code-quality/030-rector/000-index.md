# Rector

## 1. Overview

Rector is an automated refactoring tool for PHP that helps upgrade and refactor code. It can automatically upgrade your code to newer PHP versions, refactor to better coding standards, and apply custom transformations.

### 1.1. Package Information

- **Package Name**: rector/rector
- **Version**: ^2.0.11
- **GitHub**: [https://github.com/rectorphp/rector](https://github.com/rectorphp/rector)
- **Documentation**: [https://getrector.com/documentation](https://getrector.com/documentation)

### 1.2. Related Packages

- **driftingly/rector-laravel**: Laravel-specific rules for Rector
- **rector/type-perfect**: Additional type-related rules

## 2. Key Features

- Automated PHP version upgrades
- Framework-specific refactoring rules
- Custom rule creation
- Dry-run mode to preview changes
- Integration with CI/CD pipelines
- Parallel processing for performance

## 3. Installation and Setup

See [010-installation.md](010-installation.md) for detailed installation instructions.

## 4. Configuration

See [020-configuration.md](020-configuration.md) for information on configuring Rector.

## 5. Laravel Integration

See [030-laravel-rules.md](030-laravel-rules.md) for information on Laravel-specific rules.

## 6. Type Perfect

See [040-type-perfect.md](040-type-perfect.md) for information on the Type Perfect extension for Rector.

## 7. Usage in This Project

In this project, Rector is configured to:

- Target PHP 8.4 features
- Apply Laravel-specific rules
- Run in parallel for performance
- Apply custom rules for project-specific patterns

## 8. Composer Commands

```bash
# Apply automated refactoring
composer refactor

# Preview changes without modifying files
composer refactor:dry

# Run as part of quality checks
composer test:refactor
```

## 9. Configuration

Rector is configured through:

- `rector.php` - Main configuration file
- Custom rule sets defined in the project

## 10. Best Practices

- Always run in dry-run mode first to preview changes
- Review automated changes carefully
- Apply changes in small, manageable batches
- Add custom rules for project-specific patterns
- Run Rector regularly to keep code up-to-date
