# PHPStan Static Analysis
# PHPStan Static Analysis

## 1. Overview

PHPStan is a powerful static analysis tool that identifies potential errors in PHP code without executing it. This guide covers how we use PHPStan in our Laravel 12 project with PHP 8.4 to achieve the highest level of code quality and reliability.

## 2. Documentation Sections

- [2.1. Configuration](010-configuration.md) - Core PHPStan setup and configuration files
- [2.2. Baseline Management](020-baseline-management.md) - Working with error baselines effectively
- [2.3. Workflow](030-workflow.md) - Day-to-day usage and integration into development process
- [2.4. Command Reference](040-commands.md) - All available PHPStan commands in this project
- [2.5. Custom Rules](050-custom-rules.md) - Creating project-specific static analysis rules
- [2.6. Troubleshooting](060-troubleshooting.md) - Resolving common PHPStan issues

## 3. Why PHPStan?

PHPStan helps our project by:

- Finding bugs before they reach production
- Enforcing consistent code standards
- Improving code maintainability
- Reducing technical debt
- Providing confidence during refactoring

## 4. Key Features in Our Implementation

- **Level 10 Analysis**: We aim for the strictest level of static analysis
- **Parallelized Execution**: Analysis runs in parallel for faster feedback
- **Laravel Integration**: Larastan provides Laravel-specific rule sets
- **Incremental Analysis**: Only changed files are analyzed during local development
- **CI/CD Integration**: Automated analysis runs on every pull request

## 5. Quick Start

To run PHPStan locally:
PHPStan is a powerful static analysis tool that identifies potential errors in PHP code without executing it. This guide covers how we use PHPStan in our Laravel 12 project with PHP 8.4.

## Documentation Sections

* [Configuration](010-configuration.md) - Core PHPStan setup and configuration files
* [Baseline Management](020-baseline-management.md) - Working with error baselines effectively
* [Workflow](030-workflow.md) - Day-to-day usage and integration into development process
* [Command Reference](040-commands.md) - All available PHPStan commands in this project
* [Troubleshooting](050-troubleshooting.md) - Common issues and their solutions

## Key Features

Our PHPStan implementation includes:

* Level 10 (maximum strictness) static analysis
* Baseline approach for incremental improvement
* Integration with Laravel-specific type rules via Larastan
* Automated workflows for continuous quality improvement
* Parallelized execution for performance

## Quick Start

```bash
# Run PHPStan analysis on your code
composer analyze

# Generate a fresh baseline (recommended for new setups)
composer analyze:fresh-baseline

# See detailed error table
composer analyze:show-errors
```

For more detailed instructions, see the [Workflow](030-workflow.md) documentation.
