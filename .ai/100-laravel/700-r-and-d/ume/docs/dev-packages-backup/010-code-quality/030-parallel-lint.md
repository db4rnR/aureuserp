# PHP Parallel Lint

## 1. Overview

PHP Parallel Lint is a tool that checks syntax of PHP files in parallel, making it significantly faster than serial syntax checking for large projects. It doesn't do any code analysis beyond syntax checking, but it's fast and efficient.

### 1.1. Package Information

- **Package Name**: php-parallel-lint/php-parallel-lint
- **Version**: 1.4.0
- **GitHub**: [https://github.com/php-parallel-lint/PHP-Parallel-Lint](https://github.com/php-parallel-lint/PHP-Parallel-Lint)
- **Documentation**: [https://github.com/php-parallel-lint/PHP-Parallel-Lint#readme](https://github.com/php-parallel-lint/PHP-Parallel-Lint#readme)

## 2. Key Features

- Parallel syntax checking of PHP files
- Much faster than serial checking
- Easy integration with CI/CD systems
- Simple configuration
- Support for excluding files/directories
- Output in various formats

## 3. Usage Examples

### 3.1. Basic Usage

```sh
## Check syntax of all PHP files in the current directory
./vendor/bin/parallel-lint .

## Check specific directories
./vendor/bin/parallel-lint app config routes

## Exclude vendor directory
./vendor/bin/parallel-lint --exclude vendor .
```

### 3.2. Advanced Options

```sh
## Set number of parallel processes
./vendor/bin/parallel-lint -j 10 .

## Show progress
./vendor/bin/parallel-lint --progress .

## Output as JSON
./vendor/bin/parallel-lint --json .

## Colors in output
./vendor/bin/parallel-lint --colors .
```

## 4. Configuration

PHP Parallel Lint doesn't use a configuration file, but can be configured with command-line options:

```php
<?php

declare(strict_types=1);

// Example options that could be used in a CI script
$options = [
    '--exclude' => 'vendor',
    '--exclude' => 'node_modules',
    '--colors' => true,
    '--show-deprecated' => true,
    '-j' => 10,
];

// Would translate to the command:
// ./vendor/bin/parallel-lint --exclude vendor --exclude node_modules --colors --show-deprecated -j 10 .
```

## 5. Best Practices

### 5.1. Pre-commit Hook

Use PHP Parallel Lint in a pre-commit hook:

```sh
#!/bin/sh
# .git/hooks/pre-commit

# Lint PHP files that are staged for commit
STAGED_PHP_FILES=$(git diff --cached --name-only --diff-filter=ACM | grep '\.php$')

if [ -n "$STAGED_PHP_FILES" ]; then
    echo "Checking PHP syntax..."
    echo "$STAGED_PHP_FILES" | xargs ./vendor/bin/parallel-lint
    
    if [ $? -ne 0 ]; then
        echo "PHP syntax errors found. Commit aborted."
        exit 1
    fi
fi

exit 0
```

### 5.2. CI/CD Integration

Integrate with CI/CD workflows:

```yaml
# In GitHub workflow
php-lint:
  runs-on: ubuntu-latest
  steps:
    - uses: actions/checkout@v3
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
    - name: Install dependencies
      run: composer install --prefer-dist
    - name: Check PHP syntax
      run: vendor/bin/parallel-lint app config database routes tests
```

### 5.3. Laravel Integration

Include in Laravel's test workflow:

```php
<?php

declare(strict_types=1);

// In composer.json scripts
"scripts": {
    "test:lint": [
        "./vendor/bin/parallel-lint app config database routes tests"
    ],
    // Run syntax check before other tests
    "test": [
        "@test:lint",
        "@php artisan test"
    ]
}
```
