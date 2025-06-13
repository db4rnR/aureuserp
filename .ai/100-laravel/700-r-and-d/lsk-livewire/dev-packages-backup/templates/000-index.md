# Configuration Templates Index

## 1. Overview

This directory contains template files that can be used as starting points for configuring development packages. Unlike the examples in the `configs` directory, these templates are minimal and designed to be customized for specific project needs.

## 2. Templates By Category

### 2.1. Static Analysis Templates

- [PHPStan Base Template](phpstan-base.neon.dist)
- [Custom Rule Template](phpstan-rule-template.php)
- [Baseline Generation Template](phpstan-baseline.neon)

### 2.2. Code Style Templates

- [Laravel Pint Base Template](pint-base.json)
- [Custom Laravel Pint Preset](pint-custom-preset.json)
- [Team Standard Template](team-standards.json)

### 2.3. Testing Templates

- [PHPUnit Base Template](phpunit-base.xml.dist)
- [Pest Base Template](pest-base.php)
- [Feature Test Template](feature-test-template.php)
- [Unit Test Template](unit-test-template.php)

### 2.4. CI/CD Templates

- [GitHub Actions Base Workflow](github-actions-base.yml)
- [Deployment Workflow Template](deployment-workflow.yml)
- [Quality Gate Template](quality-gate.yml)

### 2.5. Documentation Templates

- [Package Documentation Template](package-doc-template.md)
- [API Documentation Template](api-doc-template.md)
- [Code Example Template](code-example-template.md)

## 3. Using These Templates

To use these templates:

1. Copy the relevant template file to the appropriate location in your project
2. Rename the file according to the package requirements
3. Customize the template with project-specific settings
4. Reference the corresponding package documentation for implementation details

Each template includes comments explaining the purpose of each section and options for customization.
