# PHPStan Workflow Integration

## 1. Overview

This document explains how to integrate PHPStan into your daily development workflow for our Laravel 12 project. Effective integration ensures code quality without disrupting productivity.

## 2. Development Workflow

### 2.1. Local Development

Integrate PHPStan into your local development workflow:

1. **Before committing code**: Run PHPStan to check for errors
2. **After making significant changes**: Run PHPStan on affected files
3. **When fixing type issues**: Update the baseline after fixes

### 2.2. Recommended Workflow

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│  Write Code     │────▶│  Run PHPStan    │────▶│  Fix Errors     │
└─────────────────┘     └─────────────────┘     └─────────────────┘
         ▲                                               │
         │                                               │
         └───────────────────────────────────────────────┘
```

## 3. Command Reference

### 3.1. Daily Commands

```bash
# Run PHPStan analysis
composer analyze

# Show detailed error table
composer analyze:show-errors

# Clear PHPStan cache (if you encounter strange behavior)
composer analyze:clear-cache
```

### 3.2. Occasional Commands

```bash
# Update baseline after fixing errors
composer analyze:baseline

# Generate fresh baseline (rarely needed)
composer analyze:fresh-baseline
```

## 4. IDE Integration

### 4.1. PHPStorm

1. Install the PHPStan plugin
2. Configure in Settings → PHP → Quality Tools → PHPStan
3. Set path to PHPStan executable: `vendor/bin/phpstan`
4. Enable "Run on Save" for immediate feedback

### 4.2. VS Code

1. Install the PHPStan extension
2. Configure in settings.json:
   ```json
   "phpstan.enabled": true,
   "phpstan.executablePath": "vendor/bin/phpstan"
   ```

## 5. Git Hooks

### 5.1. Pre-Commit Hook

Add a pre-commit hook to run PHPStan automatically:

```bash
#!/bin/sh
# .git/hooks/pre-commit
FILES=$(git diff --cached --name-only --diff-filter=ACMR "*.php" | sed 's| |\\ |g')
[ -z "$FILES" ] && exit 0

# Run PHPStan on changed files only
vendor/bin/phpstan analyse $FILES
```

### 5.2. Using Husky

If you're using Husky for Git hooks:

```json
// package.json
{
  "husky": {
    "hooks": {
      "pre-commit": "composer analyze"
    }
  }
}
```

## 6. CI/CD Integration

### 6.1. GitHub Actions

Our GitHub Actions workflow runs PHPStan on every pull request:

```yaml
# .github/workflows/phpstan.yml
name: PHPStan

on:
  pull_request:
    paths:
      - '**.php'
      - 'phpstan.neon'
      - 'composer.json'
      - 'composer.lock'

jobs:
  phpstan:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
      - name: Install Dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress
      - name: Run PHPStan
        run: composer analyze
```

### 6.2. GitLab CI

For GitLab CI:

```yaml
# .gitlab-ci.yml
phpstan:
  stage: test
  script:
    - composer install --no-progress --no-scripts --no-interaction
    - composer analyze
  cache:
    paths:
      - vendor/
      - var/cache/phpstan/
```

## 7. Team Workflow

### 7.1. Code Review Process

During code review:

1. Check that PHPStan passes without new errors
2. Verify that baseline changes only remove errors, not add them
3. Ensure complex types are properly documented with PHPDoc

### 7.2. Handling Legacy Code

For legacy code:

1. Use baseline to ignore existing errors
2. Fix errors gradually in dedicated refactoring PRs
3. Focus on high-impact areas first

## 8. Best Practices

1. **Run Early, Run Often**: Run PHPStan frequently during development
2. **Fix Issues Immediately**: Address type issues as soon as they're detected
3. **Document Complex Types**: Use PHPDoc for complex types
4. **Incremental Adoption**: Gradually increase PHPStan level
5. **Share Knowledge**: Document common issues and solutions
