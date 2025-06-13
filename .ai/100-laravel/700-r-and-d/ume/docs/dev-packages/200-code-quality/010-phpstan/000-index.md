# PHPStan and Larastan

## 1. Overview

PHPStan is a static analysis tool that finds errors in your code without running it. Larastan is a PHPStan extension specifically designed for Laravel applications, providing framework-specific rules and type inference.

### 1.1. Package Information

- **PHPStan Package**: phpstan/phpstan
- **Larastan Package**: larastan/larastan
- **Version**: ^3.2.0
- **GitHub**: [https://github.com/larastan/larastan](https://github.com/larastan/larastan)
- **Documentation**: [https://phpstan.org/user-guide/getting-started](https://phpstan.org/user-guide/getting-started)

## 2. Key Features

- Static code analysis without running the code
- Configurable analysis levels (0-10)
- Type inference for Laravel-specific patterns
- Custom rule creation
- Baseline management for gradual adoption
- Integration with CI/CD pipelines

## 3. Installation and Setup

See [010-installation.md](010-installation.md) for detailed installation instructions.

## 4. Configuration

See [020-configuration.md](020-configuration.md) for information on configuring PHPStan and Larastan.

## 5. Laravel Integration

See [030-larastan.md](030-larastan.md) for information on Laravel-specific features and rules.

## 6. Baseline Management

See [040-baseline-management.md](040-baseline-management.md) for information on managing baseline files.

## 7. Workflow Integration

See [050-workflow.md](050-workflow.md) for information on integrating PHPStan into your development workflow.

## 8. Troubleshooting

See [060-troubleshooting.md](060-troubleshooting.md) for common issues and solutions.

## 9. Usage in This Project

In this project, PHPStan/Larastan is configured to:

- Run at level 8 (out of 10)
- Use Laravel-specific rules
- Include custom rules for project-specific patterns
- Manage existing issues through baseline files
- Run as part of CI/CD pipelines

## 10. Composer Commands

```bash
# Run PHPStan analysis
composer analyze

# Generate baseline file
composer analyze:baseline

# Generate fresh baseline
composer analyze:fresh-baseline

# Clear PHPStan cache
composer analyze:clear-cache

# Show detailed errors
composer analyze:show-errors
```

## 11. Best Practices

- Gradually increase PHPStan level as code quality improves
- Use baseline files to manage existing issues
- Add PHPDoc blocks to clarify types
- Run PHPStan before committing code
- Address issues in small, manageable batches
