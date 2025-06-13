# Phase 1.3: GitHub Workflows Configuration

**Version:** 1.0.2 **Date:** 2023-11-13 **Author:** AI Assistant **Status:** Updated **Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
  - [Required Prior Steps](#required-prior-steps)
  - [Required Knowledge](#required-knowledge)
  - [Required Environment](#required-environment)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Workflow Files](#workflow-files)
  - [Code Quality](#code-quality)
  - [Tests](#tests)
  - [Static Analysis](#static-analysis)
  - [Linting](#linting)
  - [Authentication Tests](#authentication-tests)
  - [Code Quality PR Comments](#code-quality-pr-comments)
- [Common Components](#common-components)
  - [PHP Setup](#php-setup)
  - [Dependency Caching](#dependency-caching)
  - [Environment Configuration](#environment-configuration)
- [Integration with Code Quality Tools](#integration-with-code-quality-tools)
- [Continuous Integration Strategy](#continuous-integration-strategy)
- [Deployment Considerations](#deployment-considerations)
- [Maintenance and Updates](#maintenance-and-updates)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides a comprehensive reference for the GitHub Workflows configured in the Enhanced Laravel Application
(ELA). These workflows automate testing, code quality checks, and other CI/CD processes to ensure code reliability and
maintainability.

## Prerequisites

Before working with these GitHub Workflows, ensure you have:

### Required Prior Steps

- [Phase 0 Summary](070-phase-summaries/010-phase0-summary.md) reviewed
- [Configuration Files](070-phase-summaries/020-configuration-files.md) reviewed
- All Phase 0 implementation steps completed

### Required Knowledge

- Basic understanding of GitHub Actions and workflows
- Familiarity with YAML syntax
- Understanding of CI/CD concepts
- Knowledge of the code quality tools being used

### Required Environment

- GitHub repository with Actions enabled
- Access to GitHub repository settings
- Local Git installation for testing workflows

## Estimated Time Requirements

| Task                                       | Estimated Time |
| ------------------------------------------ | -------------- |
| Review Workflow Files                      | 20 minutes     |
| Understand Common Components               | 15 minutes     |
| Review Integration with Code Quality Tools | 15 minutes     |
| Understand CI Strategy                     | 10 minutes     |
| Review Deployment Considerations           | 10 minutes     |
| **Total**                                  | **70 minutes** |

> **Note:** These time estimates assume familiarity with GitHub Actions. Actual time may vary based on experience level
> and the complexity of your CI/CD requirements.

## Workflow Files

### Code Quality

**File**: `.github/workflows/code-quality.yml`

**Purpose**: Runs comprehensive code quality checks on the codebase.

**Trigger**:

- Push to main, master, or develop branches
- Pull requests to main, master, or develop branches

**Key Features**:

- Matrix testing with PHP 8.3 and 8.4
- Comprehensive quality checks including:
  - Syntax validation
  - Dependency validation
  - Static analysis
  - Style enforcement
  - Metrics collection
  - Architecture testing
  - Unit and integration testing
  - Mutation testing (PHP 8.3 only)
  - Accessibility testing (PHP 8.3 only)
- Performance monitoring
- Report generation and artifact upload

**Example Job**:

````yaml
quality:
  name: Quality Checks (PHP ${{ matrix.php }})
  runs-on: ubuntu-latest
  strategy:
    fail-fast: false
    matrix:
      php: ['8.3', '8.4']
      include:
        - php: '8.3'
          coverage: true
          mutation: true
          accessibility: true
        - php: '8.4'
          coverage: true
          mutation: false
          accessibility: false
```text

### Tests

**File**: `.github/workflows/tests.yml`

**Purpose**: Runs the application's test suite.

**Trigger**:
- Push to develop or main branches
- Pull requests to develop or main branches

**Key Features**:
- PHP 8.4 environment
- Node.js 22 setup for frontend assets
- Flux credentials configuration
- Environment setup with .env.example
- Asset building
- Pest test execution

**Example Job**:
```yaml
ci:
  runs-on: ubuntu-latest
  environment: Testing
  steps:
    # Setup steps...
    - name: Run Tests
      run: ./vendor/bin/pest
````php
### Static Analysis

**File**: `.github/workflows/static-analysis.yml`

**Purpose**: Runs PHPStan static analysis on the codebase.

**Trigger**:

- Push to main or develop branches
- Pull requests to main or develop branches
- Manual trigger (workflow_dispatch)

**Key Features**:

- PHP 8.4 environment
- Caching of composer dependencies
- Caching of PHPStan results
- Memory limit configuration (1GB)
- Artifact upload of analysis results

**Example Job**:

````yaml
phpstan:
  name: PHPStan Analysis
  runs-on: ubuntu-latest
  steps:
    # Setup steps...
    - name: Run PHPStan
      run: ./vendor/bin/phpstan analyse --memory-limit=1G --no-progress
```text

### Linting

**File**: `.github/workflows/lint.yml`

**Purpose**: Runs Laravel Pint to enforce code style.

**Trigger**:
- Push to develop or main branches
- Pull requests to develop or main branches

**Key Features**:
- PHP 8.4 environment
- Flux credentials configuration
- Laravel Pint execution
- (Commented out) Auto-commit functionality for style fixes

**Example Job**:
```yaml
quality:
  runs-on: ubuntu-latest
  environment: Testing
  steps:
    # Setup steps...
    - name: Run Pint
      run: vendor/bin/pint
````php
### Authentication Tests

**File**: `.github/workflows/auth.yml`

**Purpose**: Tests the authentication system using DevDojo Auth.

**Trigger**:

- Push to main branch
- Pull requests to main branch

**Key Features**:

- PHP 8.3 environment
- Symlink to DevDojo Auth tests
- SQLite database configuration
- Environment configuration for testing
- Migration execution
- Pest and Laravel Dusk installation
- Test execution

**Example Job**:

````yaml
test:
  runs-on: ubuntu-latest
  steps:
    # Setup steps...
    - name: Remove current tests and symlink to DevDojo Auth
      run: |
        rm -rf tests
        ln -s vendor/devdojo/auth/tests tests
    # Database and test setup...
    - name: Run Tests
      run: ./vendor/bin/pest
```text

### Code Quality PR Comments

**File**: `.github/workflows/code-quality-pr.yml`

**Purpose**: Generates code quality reports and posts them as comments on pull requests.

**Trigger**:
- Pull requests opened, synchronized, or reopened to main, master, or develop branches

**Key Features**:
- PHP 8.4 environment
- PHPStan analysis with JSON output
- PHP Insights execution with JSON output
- Quality dashboard generation
- Summary extraction and formatting
- PR comment creation with quality report
- Report artifact upload

**Example Job**:
```yaml
quality-report:
  name: Generate Quality Report
  runs-on: ubuntu-latest
  steps:
    # Setup steps...
    - name: Comment PR
      uses: actions/github-script@v6
      with:
        github-token: ${{ secrets.GITHUB_TOKEN }}
        script: |
          # Script to post comment...
````php
## Common Components

### PHP Setup

All workflows use the `shivammathur/setup-php@v2` action to configure PHP:

````yaml
- name: Setup PHP
  uses: shivammathur/setup-php@v2
  with:
    php-version: '8.4'  # or '8.3' depending on workflow
    extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite, bcmath, intl, ...
    coverage: pcov  # or xdebug or none depending on workflow
    tools: composer:v2
```text

### Dependency Caching

Most workflows implement caching for Composer dependencies:

```yaml
- name: Get composer cache directory
  id: composer-cache
  run: echo "dir=$(composer config cache-files-dir)" >> $GITHUB_OUTPUT

- name: Cache composer dependencies
  uses: actions/cache@v3  # or v4 depending on workflow
  with:
    path: ${{ steps.composer-cache.outputs.dir }}
    key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
    restore-keys: ${{ runner.os }}-composer-
````php
### Environment Configuration

Workflows that run tests configure the environment:

````yaml
- name: Copy Environment File
  run: cp .env.example .env

- name: Generate Application Key
  run: php artisan key:generate
```text

## Integration with Code Quality Tools

The workflows integrate with various code quality tools:

1. **PHPStan**:
   ```yaml
   - name: Run PHPStan
     run: ./vendor/bin/phpstan analyse --memory-limit=1G --no-progress
````

2. **Laravel Pint**:

   ```yaml
   - name: Run Pint
     run: vendor/bin/pint
   ```

3. **Pest PHP**:

   ```yaml
   - name: Run Tests
     run: ./vendor/bin/pest
   ```

4. **PHP Insights**:

   ```yaml
   - name: Run PHP Insights
     run: php artisan insights --format=json > reports/phpinsights/report.json || true
   ```

5. **Accessibility Testing**:
   ```yaml
   - name: Run accessibility tests
     run: |
       axe http://localhost:8000 --exit --tags wcag2aa > reports/accessibility/axe-report.json
       lhci autorun --collect.url=http://localhost:8000 --collect.settings.preset=desktop --collect.settings.onlyCategories=accessibility || true
   ```

## Continuous Integration Strategy

The ELA implements a comprehensive CI strategy:

1. **Branch Protection**:

   - Main, master, and develop branches are protected
   - Pull requests require passing checks before merging

2. **Quality Gates**:

   - Code style must pass (Pint)
   - Static analysis must pass (PHPStan)
   - Tests must pass (Pest)
   - Code quality metrics must meet thresholds

3. **Feedback Mechanisms**:

   - PR comments with quality reports
   - Artifact uploads for detailed inspection
   - Test and analysis results in GitHub UI

4. **Performance Optimization**:
   - Dependency caching
   - Parallel testing
   - Selective testing based on changes

## Deployment Considerations

While the current workflows focus on testing and quality, deployment could be added:

1. **Staging Deployment**:

   ```yaml
   deploy-staging:
     needs: [tests, quality]
     if: github.ref == 'refs/heads/develop'
     runs-on: ubuntu-latest
     steps:
       # Deployment steps...
   ```

2. **Production Deployment**:
   ```yaml
   deploy-production:
     needs: [tests, quality]
     if: github.ref == 'refs/heads/main'
     runs-on: ubuntu-latest
     steps:
       # Deployment steps...
   ```

## Maintenance and Updates

To maintain and update these workflows:

1. **Version Updates**:

   - When updating PHP versions, update all workflow files
   - Keep action versions consistent (e.g., actions/checkout@v4)

2. **Tool Updates**:

   - When updating code quality tools, update the corresponding workflow steps
   - Ensure memory limits and other settings are adjusted as needed

3. **Performance Optimization**:

   - Regularly review and optimize caching strategies
   - Adjust parallel execution settings based on repository size

4. **Security Considerations**:
   - Regularly update GitHub Actions to latest versions
   - Review and update secrets management
   - Consider adding security scanning workflows

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: Workflow fails with dependency installation errors

**Symptoms:**

- Workflow fails during the dependency installation step
- Error messages about missing packages or version conflicts

**Possible Causes:**

- Composer or npm cache issues
- Incompatible package versions
- Missing PHP extensions

**Solutions:**

1. Clear the dependency cache in the workflow
2. Update the dependency lock files locally and commit them
3. Ensure all required PHP extensions are installed in the workflow

### Issue: Tests pass locally but fail in CI

**Symptoms:**

- Tests pass on local development environment
- Same tests fail in GitHub Actions

**Possible Causes:**

- Different PHP or Node.js versions
- Missing environment variables
- Different database configuration

**Solutions:**

1. Ensure the CI environment matches the local environment
2. Add all required environment variables to GitHub secrets
3. Check for environment-specific code that might behave differently in CI

### Issue: Code quality checks are too strict

**Symptoms:**

- Workflows fail due to code quality issues
- Many false positives or minor issues reported

**Possible Causes:**

- Overly strict code quality tool configuration
- Missing baseline files
- Incompatible rules for the codebase

**Solutions:**

1. Adjust the code quality tool configuration to be more lenient
2. Create baseline files to ignore existing issues
3. Exclude specific files or directories from analysis

### Issue: Workflows take too long to run

**Symptoms:**

- Workflows take a long time to complete
- CI/CD pipeline becomes a bottleneck

**Possible Causes:**

- Inefficient caching
- Running too many tests or checks
- Sequential execution of tasks that could be parallel

**Solutions:**

1. Optimize caching strategies for dependencies
2. Split large workflows into smaller, focused workflows
3. Use matrix builds to run tests in parallel

</details>

## Related Documents

- [Configuration Files](070-phase-summaries/020-configuration-files.md) - For configuration file details
- [Code Quality Tools](060-configuration/040-code-quality-tools.md) - For code quality tools configuration
- [Testing Configuration](060-configuration/030-testing-configuration.md) - For testing configuration details
- [SoftDeletes and UserTracking Implementation](090-model-features/010-softdeletes-usertracking.md) - For next
  implementation step

## Version History

| Version | Date       | Changes                                                                                             | Author       |
| ------- | ---------- | --------------------------------------------------------------------------------------------------- | ------------ |
| 1.0.0   | 2025-05-15 | Initial version                                                                                     | AI Assistant |
| 1.0.1   | 2025-05-17 | Updated file references and links                                                                   | AI Assistant |
| 1.0.2   | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [Configuration Files](070-phase-summaries/020-configuration-files.md) | **Next Step:**
[SoftDeletes and UserTracking Implementation](090-model-features/010-softdeletes-usertracking.md)
