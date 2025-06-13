# PHPStan Documentation

## 1. Overview

This section provides comprehensive documentation for integrating and using PHPStan with the Laravel project. PHPStan is a static analysis tool that finds errors in your code without running it.

## 2. Installation and Setup

- [2.1. Basic Installation](010-installation.md) - Setting up PHPStan in the project
- [2.2. Larastan Integration](020-larastan.md) - Laravel-specific PHPStan extension
- [2.3. Configuration Options](030-configuration.md) - Understanding phpstan.neon

## 3. Rules and Levels

- [3.1. Rule Levels Explained](040-rule-levels.md) - Understanding the 10 rule levels
- [3.2. Custom Rules](050-custom-rules.md) - Creating project-specific rules
- [3.3. Ignoring Errors](060-ignoring-errors.md) - When and how to ignore errors

## 4. Advanced Usage

- [4.1. Baseline Generation](070-baseline.md) - Creating a baseline for legacy code
- [4.2. CI/CD Integration](080-ci-integration.md) - Running PHPStan in pipelines
- [4.3. IDE Integration](090-ide-integration.md) - PHPStan in your development environment

## 5. Best Practices

- [5.1. Incremental Adoption](100-incremental-adoption.md) - Gradually increasing strictness
- [5.2. Team Workflow](110-team-workflow.md) - Integrating PHPStan into development process
- [5.3. Performance Optimization](120-performance.md) - Running PHPStan efficiently

## 6. Reference Materials

- [6.1. Configuration Reference](../configs/phpstan.neon.dist) - Example configuration file
- [6.2. Common Issues](130-common-issues.md) - Troubleshooting and solutions
- [6.3. Additional Resources](140-resources.md) - External documentation and tutorials
