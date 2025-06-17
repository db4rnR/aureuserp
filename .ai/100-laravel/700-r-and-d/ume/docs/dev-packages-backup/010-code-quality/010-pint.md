# Laravel Pint

## 1. Overview

Laravel Pint is an opinionated PHP code style fixer based on PHP-CS-Fixer with a pre-defined set of rules that align with Laravel's coding style.

### 1.1. Package Information

- **Package Name**: laravel/pint
- **Version**: ^1.18
- **GitHub**: [https://github.com/laravel/pint](https://github.com/laravel/pint)
- **Documentation**: [https://laravel.com/docs/pint](https://laravel.com/docs/pint)

## 2. Key Features

- Opinionated code style fixing
- Pre-configured rule sets
- Simple configuration
- Laravel-focused
- Fast execution

## 3. Usage Examples

### 3.1. Running Pint

```sh
// Via composer script
composer lint

// Check but don't fix (via composer)
composer test:lint

// Directly
./vendor/bin/pint
```

### 3.2. Targeting Specific Files

```sh
// Format a specific file
./vendor/bin/pint app/Models/User.php

// Format a directory
./vendor/bin/pint app/Http/Controllers
```

## 4. Configuration

Our Pint configuration is located in `pint.json`:

```json
{
    "preset": "laravel",
    "rules": {
        "align_multiline_comment": true,
        "array_indentation": true,
        "array_syntax": {
            "syntax": "short"
        },
        "blank_line_after_namespace": true,
        "blank_line_after_opening_tag": true,
        "combine_consecutive_issets": true,
        "combine_consecutive_unsets": true,
        "concat_space": {
            "spacing": "one"
        },
        "declare_strict_types": true,
        "explicit_string_variable": true,
        "fully_qualified_strict_types": true,
        "global_namespace_import": {
            "import_classes": true,
            "import_constants": true,
            "import_functions": true
        },
        "is_null": true,
        "lambda_not_used_import": true,
        "method_chaining_indentation": true,
        "modernize_types_casting": true,
        "nullable_type_declaration_for_default_null_value": true,
        "ordered_imports": {
            "sort_algorithm": "alpha"
        },
        "ordered_interfaces": true,
        "protected_to_private": true,
        "single_quote": true
    }
}
```

## 5. Integration with IDE

### 5.1. PhpStorm Integration

Configure PhpStorm to run Pint before saving:

1. Go to Settings > Tools > External Tools
2. Add a new tool:
    - Name: Laravel Pint
    - Program: $ProjectFileDir$/vendor/bin/pint
    - Arguments: $FilePath$
    - Working directory: $ProjectFileDir$

### 5.2. VS Code Integration

Install the Laravel Pint VS Code extension for automatic formatting.

## 6. CI/CD Integration

Pint is integrated into our CI/CD pipeline:

```yaml
# In GitHub Actions workflow
lint:
  runs-on: ubuntu-latest
  steps:
    - uses: actions/checkout@v3
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
    - name: Install dependencies
      run: composer install --prefer-dist
    - name: Check code style
      run: ./vendor/bin/pint --test
```
