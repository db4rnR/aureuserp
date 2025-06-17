# Laravel IDE Helper

## 1. Overview

Laravel IDE Helper is a package that generates helper files to improve IDE auto-completion for Laravel projects. It provides accurate type hints for facades, models, and other Laravel components, enhancing the development experience in IDEs like PhpStorm, VS Code, and others.

### 1.1. Package Information

- **Package Name**: barryvdh/laravel-ide-helper
- **Version**: ^3.0.0
- **GitHub**: [https://github.com/barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper)
- **Documentation**: [https://github.com/barryvdh/laravel-ide-helper#readme](https://github.com/barryvdh/laravel-ide-helper#readme)

## 2. Key Features

- Facade auto-completion
- Model property and method auto-completion
- Eloquent relationship auto-completion
- PHPDoc generation for models
- Meta file generation for better type inference
- Support for Laravel Macros
- Integration with PhpStorm, VS Code, and other IDEs
- Support for Laravel 12 and PHP 8.4
- Custom stub generation
- Automatic PHPDoc updates

## 3. Installation

```bash
composer require --dev barryvdh/laravel-ide-helper
```

## 4. Configuration

### 4.1. Basic Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider" --tag=config
```

This creates a `config/ide-helper.php` file.

### 4.2. Configuration Options

The main configuration options in `config/ide-helper.php`:

```php
<?php

return [
    // Location of the generated helper files
    'filename' => '_ide_helper.php',
    'meta_filename' => '.phpstorm.meta.php',
    'model_filename' => '_ide_helper_models.php',
    
    // Include helper files in your project
    'include_fluent' => true,
    'include_factory_builders' => true,
    'include_helpers' => true,
    
    // Model settings
    'model_locations' => [
        'app/Models',
    ],
    'model_camel_case_properties' => false,
    'model_snake_case_properties' => true,
    'model_write' => true,
    'model_write_reset' => true,
    
    // Extra classes to include
    'extra' => [
        'Eloquent' => ['Illuminate\\Database\\Eloquent\\Builder', 'Illuminate\\Database\\Query\\Builder'],
        'Session' => ['Illuminate\\Session\\Store'],
    ],
    
    // Magic methods to include
    'magic' => [
        'Log' => [
            'debug'     => 'Monolog\\Logger::debug',
            'info'      => 'Monolog\\Logger::info',
            'notice'    => 'Monolog\\Logger::notice',
            'warning'   => 'Monolog\\Logger::warning',
            'error'     => 'Monolog\\Logger::error',
            'critical'  => 'Monolog\\Logger::critical',
            'alert'     => 'Monolog\\Logger::alert',
            'emergency' => 'Monolog\\Logger::emergency',
        ],
    ],
    
    // Interface implementations
    'interfaces' => [
        'Countable' => ['count'],
        'IteratorAggregate' => ['getIterator'],
        'JsonSerializable' => ['jsonSerialize'],
    ],
];
```

## 5. Usage

### 5.1. Generating Helper Files

Generate the main helper file for facades:

```bash
php artisan ide-helper:generate
```

Generate the meta file for better auto-completion:

```bash
php artisan ide-helper:meta
```

Generate model helper files:

```bash
php artisan ide-helper:models
```

Generate helper files for PhpStorm:

```bash
php artisan ide-helper:phpstorm
```

### 5.2. Automatic Generation

Add these commands to your `composer.json` to automatically generate helper files:

```json
"scripts": {
    "post-update-cmd": [
        "@php artisan ide-helper:generate",
        "@php artisan ide-helper:meta"
    ]
}
```

### 5.3. Model Helper Options

Generate model helpers with various options:

```bash
# Generate model helpers without writing to files
php artisan ide-helper:models --no-write

# Generate model helpers for specific models
php artisan ide-helper:models "App\\Models\\User" "App\\Models\\Post"

# Reset existing PHPDocs
php artisan ide-helper:models --reset

# Generate model helpers with smart reset (only reset existing PHPDocs)
php artisan ide-helper:models --smart-reset
```

### 5.4. Ignoring Models

Ignore specific models by adding a `@codingStandardsIgnoreStart` and `@codingStandardsIgnoreEnd` comment block:

```php
/**
 * @codingStandardsIgnoreStart
 * @property int $id
 * @property string $name
 * @codingStandardsIgnoreEnd
 */
class User extends Model
{
    // ...
}
```

## 6. Integration with Laravel 12 and PHP 8.4

Laravel IDE Helper is compatible with Laravel 12 and PHP 8.4. It generates helper files that support:

- PHP 8.4 syntax
- Type declarations
- Property promotion
- Attribute syntax
- Laravel 12 features

## 7. IDE-Specific Integration

### 7.1. PhpStorm

PhpStorm works best with Laravel IDE Helper. To set it up:

1. Generate all helper files:
   ```bash
   php artisan ide-helper:generate
   php artisan ide-helper:meta
   php artisan ide-helper:models
   ```

2. Enable Laravel Plugin in PhpStorm
3. Configure PhpStorm to index the generated files
4. Add the generated files to your `.gitignore`

### 7.2. VS Code

For VS Code with PHP Intelephense or PHP Intellisense:

1. Generate all helper files
2. Ensure the extensions can read the helper files
3. Add the generated files to your `.gitignore`

### 7.3. Sublime Text

For Sublime Text with PHP Companion:

1. Generate all helper files
2. Configure PHP Companion to use the helper files
3. Add the generated files to your `.gitignore`

## 8. Advanced Usage

### 8.1. Custom Stubs

Create custom stubs for model generation:

1. Publish the stubs:
   ```bash
   php artisan ide-helper:stubs
   ```

2. Edit the stubs in the `resources/stubs/ide-helper` directory
3. Use the custom stubs:
   ```bash
   php artisan ide-helper:models --stub=custom
   ```

### 8.2. Macros

Add support for macros in your helper files:

```php
// AppServiceProvider.php
public function register()
{
    Builder::macro('whereLike', function ($attributes, string $searchTerm) {
        // Implementation
    });
}
```

Generate helper files to include the macro:

```bash
php artisan ide-helper:generate
```

### 8.3. Custom Model Factories

Add support for custom model factories:

```php
// config/ide-helper.php
'custom_db_types' => [
    'mysql' => [
        'geometry' => 'array',
        'json' => 'array',
    ],
],
```

## 9. Best Practices

1. **Add to .gitignore**: Add generated files to `.gitignore`:
   ```
   /.phpstorm.meta.php
   /_ide_helper.php
   /_ide_helper_models.php
   ```

2. **Automatic Generation**: Set up automatic generation in `composer.json`

3. **Regular Updates**: Regenerate helper files after adding new models or facades

4. **Custom PHPDoc**: Add custom PHPDoc for properties not detected automatically

5. **Model Namespaces**: Ensure model namespaces are correctly configured

## 10. Troubleshooting

### 10.1. Missing Facades

If facades are missing from the helper file:

1. Check if the facade is registered in `config/app.php`
2. Add the facade to the `extra` section in `config/ide-helper.php`
3. Regenerate the helper file

### 10.2. Incorrect Model Properties

If model properties are incorrect:

1. Ensure your database schema is up to date
2. Check if the model has the correct table name
3. Regenerate model helpers with `--reset` option
4. Add custom PHPDoc for properties not detected automatically

### 10.3. Performance Issues

If you experience performance issues:

1. Generate helper files only when needed
2. Exclude large models from automatic generation
3. Use the `--no-write` option for models to preview without writing
