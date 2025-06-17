# 1. Code Quality

This document provides comprehensive instructions for setting up and using code quality tools in the AureusERP project.

## 1.1. Overview

AureusERP maintains high code quality standards through a combination of tools, configurations, and processes. This document outlines how to set up and use these tools effectively.

## 1.2. Code Style Tools

### 1.2.1. EditorConfig

EditorConfig helps maintain consistent coding styles across various editors and IDEs.

#### Configuration

The `.editorconfig` file at the project root defines basic formatting rules:

```
root = true

[*]
charset = utf-8
end_of_line = lf
indent_size = 4
indent_style = space
insert_final_newline = true
trim_trailing_whitespace = true

[*.md]
trim_trailing_whitespace = false

[*.{yml,yaml}]
indent_size = 2

[docker-compose.yml]
indent_size = 4
```

#### Usage

Most modern IDEs and editors support EditorConfig natively or through plugins. No additional setup is required once the plugin is installed.

### 1.2.2. Laravel Pint

Laravel Pint is an opinionated PHP code style fixer based on PHP-CS-Fixer.

#### Configuration

The `pint.json` file at the project root configures Laravel Pint with project-specific rules:

```json
{
    "preset": "laravel",
    "notPath": [
        "tests/TestCase.php"
    ],
    "rules": {
        "array_push": true,
        "backtick_to_shell_exec": true,
        "date_time_immutable": true,
        "declare_strict_types": true,
        "lowercase_keywords": true,
        "lowercase_static_reference": true,
        "final_class": true,
        "final_internal_class": true,
        "final_public_method_for_abstract_class": true,
        "fully_qualified_strict_types": true,
        "global_namespace_import": {
            "import_classes": true,
            "import_constants": true,
            "import_functions": true
        },
        "mb_str_functions": true,
        "modernize_types_casting": true,
        "new_with_parentheses": false,
        "no_superfluous_elseif": true,
        "no_useless_else": true,
        "no_multiple_statements_per_line": true,
        "ordered_class_elements": {
            "order": [
                "use_trait",
                "case",
                "constant",
                "constant_public",
                "constant_protected",
                "constant_private",
                "property_public",
                "property_protected",
                "property_private",
                "construct",
                "destruct",
                "magic",
                "phpunit",
                "method_abstract",
                "method_public_static",
                "method_public",
                "method_protected_static",
                "method_protected",
                "method_private_static",
                "method_private"
            ],
            "sort_algorithm": "none"
        },
        "ordered_interfaces": true,
        "ordered_traits": true,
        "protected_to_private": true,
        "self_accessor": true,
        "self_static_accessor": true,
        "strict_comparison": true,
        "visibility_required": true
    }
}
```

#### Usage

Run Laravel Pint using the following composer script:

```bash
composer pint
```

To check for style issues without fixing them:

```bash
composer pint:test
```

## 1.3. Static Analysis Tools

### 1.3.1. PHPStan/Larastan

PHPStan with Larastan extension provides static analysis for Laravel projects.

#### Configuration

Create a `phpstan.neon` file at the project root:

```yaml
includes:
    - ./vendor/larastan/larastan/extension.neon

parameters:
    level: 8
    paths:
        - app
        - database
        - tests
    excludePaths:
        - tests/Fixtures
    checkMissingIterableValueType: false
    checkGenericClassInNonGenericObjectType: false
```

#### Usage

Run PHPStan using the following composer script:

```bash
composer phpstan
```

### 1.3.2. Rector

Rector is an automated refactoring tool that helps upgrade and improve PHP code.

#### Configuration

The `rector.php` file at the project root configures Rector:

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap/app.php',
        __DIR__.'/database',
        __DIR__.'/public',
    ])
    ->withSkip([
        AddOverrideAttributeToOverriddenMethodsRector::class,
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
        strictBooleans: true,
    )
    ->withPhpSets();
```

#### Usage

Run Rector using the following composer script:

```bash
composer rector
```

To check for issues without fixing them:

```bash
composer rector:dry-run
```

### 1.3.3. PHP Insights

PHP Insights provides metrics and insights about code quality.

#### Configuration

Create a `phpinsights.php` file at the project root:

```php
<?php

declare(strict_types=1);

return [
    'preset' => 'laravel',
    'exclude' => [
        'tests/Fixtures',
    ],
    'add' => [
        // Additional rules
    ],
    'remove' => [
        // Rules to remove
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses::class,
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\DisallowArrayTypeHintSyntaxSniff::class,
    ],
    'config' => [
        // Configuration options
    ],
];
```

#### Usage

Run PHP Insights using the following composer script:

```bash
composer insights
```

## 1.4. Composer Scripts

Add the following scripts to your `composer.json` file:

```json
{
    "scripts": {
        "pint": "pint",
        "pint:test": "pint --test",
        "phpstan": "phpstan analyse",
        "rector": "rector process",
        "rector:dry-run": "rector process --dry-run",
        "insights": "phpinsights",
        "analyze": [
            "@pint:test",
            "@phpstan",
            "@rector:dry-run",
            "@insights"
        ],
        "fix": [
            "@pint",
            "@rector"
        ]
    }
}
```

## 1.5. Pre-commit Hooks

### 1.5.1. Setup

Install the `husky` package to manage Git hooks:

```bash
composer require --dev tysonandre/husky
```

### 1.5.2. Configuration

Create a `.husky/pre-commit` file:

```bash
#!/bin/sh
. "$(dirname "$0")/_/husky.sh"

# Run Laravel Pint on staged files
STAGED_FILES=$(git diff --cached --name-only --diff-filter=ACM | grep -E '\.(php)$')
if [ "$STAGED_FILES" != "" ]; then
    echo "Running Laravel Pint on staged files..."
    ./vendor/bin/pint $STAGED_FILES
    git add $STAGED_FILES
fi

# Run PHPStan
echo "Running PHPStan..."
composer phpstan || exit 1
```

Make the hook executable:

```bash
chmod +x .husky/pre-commit
```

## 1.6. Integration with IDEs

### 1.6.1. PhpStorm

1. Install the "Laravel Pint" plugin
2. Install the "PHPStan" plugin
3. Configure the plugins to use the project's configuration files

### 1.6.2. Visual Studio Code

1. Install the "PHP Intelephense" extension
2. Install the "PHP CS Fixer" extension
3. Configure the extensions to use the project's configuration files

## 1.7. Best Practices

1. Run code quality tools before committing changes
2. Address all issues reported by static analysis tools
3. Follow PSR-12 coding standards
4. Use type declarations and PHP 8.4+ features appropriately
5. Maintain consistent naming conventions across plugins
6. Ensure all files have proper docblocks
7. Keep cyclomatic complexity low
8. Avoid duplicate code
9. Follow Laravel conventions and best practices
