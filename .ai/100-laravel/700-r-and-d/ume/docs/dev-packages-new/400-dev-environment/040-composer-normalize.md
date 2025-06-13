# Composer Normalize

## 1. Overview

Composer Normalize is a plugin for Composer that normalizes `composer.json` files, ensuring they follow a consistent format and structure. This helps maintain a clean and standardized `composer.json` file across projects and teams.

### 1.1. Package Information

- **Package Name**: ergebnis/composer-normalize
- **Version**: ^2.45.0
- **GitHub**: [https://github.com/ergebnis/composer-normalize](https://github.com/ergebnis/composer-normalize)
- **Documentation**: [https://github.com/ergebnis/composer-normalize#readme](https://github.com/ergebnis/composer-normalize#readme)

## 2. Key Features

- Normalizes `composer.json` files
- Ensures consistent ordering of elements
- Validates JSON structure
- Formats JSON consistently
- Integrates with Composer
- Can be used in CI/CD pipelines
- Supports custom normalization rules
- Provides dry-run mode
- Supports JSON schema validation
- Integrates with Laravel projects

## 3. Installation

```bash
composer require --dev ergebnis/composer-normalize
```

## 4. Usage

### 4.1. Basic Usage

Normalize your `composer.json` file:

```bash
composer normalize
```

### 4.2. Dry Run

Check if your `composer.json` file needs normalization without making changes:

```bash
composer normalize --dry-run
```

### 4.3. Formatting Options

Format JSON with specific indentation:

```bash
composer normalize --indent-size=2 --indent-style=space
```

### 4.4. Schema Validation

Validate against the Composer schema:

```bash
composer normalize --no-check-lock --no-update-lock
```

### 4.5. Normalizing a Specific File

Normalize a specific `composer.json` file:

```bash
composer normalize /path/to/composer.json
```

## 5. Integration with Laravel 12 and PHP 8.4

Composer Normalize is fully compatible with Laravel 12 and PHP 8.4. It helps maintain a clean and standardized `composer.json` file in your Laravel projects.

## 6. Composer Scripts

Add Composer Normalize to your `composer.json` scripts:

```json
"scripts": {
    "normalize": [
        "composer normalize"
    ],
    "validate:deps": [
        "@composer validate",
        "@composer normalize --dry-run || exit 0",
        "@composer audit --no-dev || echo 'Found abandoned packages'"
    ]
}
```

Usage:

```bash
composer normalize
composer validate:deps
```

## 7. CI/CD Integration

### 7.1. GitHub Actions

```yaml
name: Normalize Composer

on:
  push:
    paths:
      - 'composer.json'
  pull_request:
    paths:
      - 'composer.json'

jobs:
  normalize:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
      - name: Install Dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress
      - name: Normalize Composer
        run: composer normalize --dry-run
```

### 7.2. GitLab CI

```yaml
normalize:
  stage: test
  script:
    - composer install --no-progress --no-scripts --no-interaction
    - composer normalize --dry-run
```

## 8. Normalization Rules

### 8.1. Default Rules

Composer Normalize applies these default rules:

1. Sort packages alphabetically
2. Sort package requirements by type and then alphabetically
3. Sort autoload and autoload-dev sections
4. Sort scripts alphabetically
5. Format JSON with consistent indentation
6. Remove redundant information

### 8.2. Custom Rules

Create a `.composer-normalize.json` file in your project root to customize normalization:

```json
{
    "indent-size": 2,
    "indent-style": "space",
    "no-update-lock": true,
    "sort-packages": true
}
```

## 9. Best Practices

1. **Run Before Committing**: Normalize your `composer.json` file before committing changes
2. **Include in CI/CD**: Add Composer Normalize to your CI/CD pipeline
3. **Use with Validation**: Combine with `composer validate` for comprehensive checks
4. **Consistent Team Standards**: Ensure all team members use the same normalization rules
5. **Document Exceptions**: If you need to deviate from standard normalization, document why

## 10. Troubleshooting

### 10.1. Lock File Conflicts

If you encounter lock file conflicts:

1. Use the `--no-update-lock` option:
   ```bash
   composer normalize --no-update-lock
   ```

2. Or update the lock file after normalizing:
   ```bash
   composer normalize && composer update --lock
   ```

### 10.2. Schema Validation Errors

If you encounter schema validation errors:

1. Check your `composer.json` for invalid entries
2. Fix any validation errors manually
3. Run normalize again:
   ```bash
   composer normalize
   ```

### 10.3. Custom Ordering Conflicts

If you need custom ordering that conflicts with normalization:

1. Document why you need custom ordering
2. Consider creating a custom normalization configuration
3. In some cases, you may need to accept that some parts of your `composer.json` will be normalized differently than you prefer
