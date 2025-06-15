# Security Advisories

## 1. Overview

The `roave/security-advisories` package helps prevent installing dependencies with known security vulnerabilities. It acts as a virtual package that conflicts with insecure package versions, preventing Composer from installing them.

### 1.1. Package Information

- **Package Name**: roave/security-advisories
- **Version**: dev-latest
- **GitHub**: [https://github.com/Roave/SecurityAdvisories](https://github.com/Roave/SecurityAdvisories)
- **Documentation**: [https://github.com/Roave/SecurityAdvisories#readme](https://github.com/Roave/SecurityAdvisories#readme)

## 2. Key Features

- Prevents installation of packages with known security vulnerabilities
- Automatically updated with new security advisories
- Zero runtime impact
- Works with Composer's dependency resolution
- Provides detailed information about conflicts
- Covers a wide range of PHP packages

## 3. Installation

```bash
composer require --dev roave/security-advisories:dev-latest
```

## 4. How It Works

The package defines conflicts with specific versions of packages that have known security vulnerabilities. When you try to install a vulnerable package, Composer will refuse to install it and show an error message.

### 4.1. Example Conflict

```json
"conflict": {
    "laravel/framework": ">=5.5.0,<5.5.22|>=5.6.0,<5.6.2",
}
```

This prevents installing Laravel Framework versions 5.5.0 to 5.5.21 and 5.6.0 to 5.6.1, which have known vulnerabilities.

## 5. Usage

### 5.1. Basic Usage

Once installed, the package works automatically. When you run `composer update` or `composer install`, it will prevent installing vulnerable packages.

### 5.2. Checking Existing Dependencies

Check your existing dependencies for vulnerabilities:

```bash
composer update --dry-run
```

### 5.3. Composer Audit

In addition to this package, you can use Composer's built-in audit command:

```bash
composer audit
```

## 6. Integration with Laravel 12 and PHP 8.4

The package is fully compatible with Laravel 12 and PHP 8.4. It helps ensure that your Laravel 12 project doesn't use dependencies with known security vulnerabilities.

## 7. CI/CD Integration

### 7.1. GitHub Actions

```yaml
name: Security Check

on:
  push:
    branches: [ 010-ddl ]
  pull_request:
    branches: [ 010-ddl ]
  schedule:
    - cron: '0 0 * * *'  # Run daily

jobs:
  security:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
      - name: Install Dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress
      - name: Security Check
        run: composer audit
```

### 7.2. GitLab CI

```yaml
security:
  stage: test
  script:
    - composer install --no-progress --no-scripts --no-interaction
    - composer audit
  allow_failure: false
```

## 8. Best Practices

### 8.1. Regular Updates

Keep the package updated to get the latest security advisories:

```bash
composer update roave/security-advisories --no-interaction
```

### 8.2. Scheduled Checks

Set up scheduled checks to regularly verify your dependencies:

```bash
# Add to your crontab
0 0 * * * cd /path/to/project && composer audit
```

### 8.3. Handling Conflicts

When you encounter a conflict:

1. Check the specific vulnerability
2. Update the conflicting package if a fix is available
3. If no fix is available, consider alternatives
4. If you must use the vulnerable version, assess the risk and implement mitigations

## 9. Additional Security Tools

In addition to `roave/security-advisories`, consider these tools:

### 9.1. Composer Audit

Composer has a built-in audit command:

```bash
composer audit
```

### 9.2. Enlightn Security Checker

A lightweight security checker:

```bash
composer require --dev enlightn/security-checker
./vendor/bin/security-checker security:check
```

### 9.3. Symfony Security Checker

Symfony's security checker:

```bash
composer require --dev symfony/security-checker
./vendor/bin/security-checker security:check
```

## 10. Composer Scripts

Add security checks to your Composer scripts:

```json
"scripts": {
    "test:security": [
        "./vendor/bin/security-checker security:check",
        "composer audit"
    ]
}
```

Usage:

```bash
composer test:security
```
