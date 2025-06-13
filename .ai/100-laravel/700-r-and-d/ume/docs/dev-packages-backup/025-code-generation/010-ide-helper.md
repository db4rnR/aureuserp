# Laravel IDE Helper

## 1. Overview

Laravel IDE Helper is a package that generates helper files to improve IDE autocompletion for Laravel projects, making development more efficient.

### 1.1. Package Information

- **Package Name**: barryvdh/laravel-ide-helper
- **Version**: ^3.5.5
- **GitHub**: [https://github.com/barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper)
- **Documentation**: [https://github.com/barryvdh/laravel-ide-helper/blob/master/README.md](https://github.com/barryvdh/laravel-ide-helper/blob/master/README.md)

## 2. Key Features

- Generates a `_ide_helper.php` file with accurate autocompletion information
- Provides accurate method completion for facades
- Generates PhpDoc for models
- Adds proper return type hints for relations
- Generates autocompletion for Laravel Fluent methods

## 3. Usage Examples

### 3.1. Generate Main Helper File

```sh
// Generate the main helper file
php artisan ide-helper:generate

// Force regeneration
php artisan ide-helper:generate --force
```

### 3.2. Generate Model PhpDocs

```sh
# Generate phpDoc for models
php artisan ide-helper:models

# Generate docs for specific models
php artisan ide-helper:models "App\Models\User" "App\Models\Post"

# Generate without asking for confirmation
php artisan ide-helper:models -n

# Generate in a separate file (_ide_helper_models.php)
php artisan ide-helper:models --write
```

### 3.3. Generate Metadata for PHPStorm

```sh
# Generate a .phpstorm.meta.php file
php artisan ide-helper:meta
```

## 4. Configuration

Our IDE Helper configuration is customized in `config/ide-helper.php`:

```php
<?php

declare(strict_types=1);

return [
    'filename'  => '_ide_helper.php',
    'meta_filename' => '.phpstorm.meta.php',
    
    'include_fluent' => true,
    'include_factory_builders' => true,
    
    'write_model_magic_where' => true,
    'write_model_external_builder_methods' => true,
    'write_model_relation_count_properties' => true,
    
    'write_eloquent_model_mixins' => true,
    
    'model_locations' => [
        'app/Models',
    ],
    
    'ignored_models' => [
        // List models to ignore
    ],
    
    'extra' => [
        'Eloquent' => ['Illuminate\\Database\\Eloquent\\Builder', 'Illuminate\\Database\\Query\\Builder'],
        'Session' => ['Illuminate\\Session\\Store'],
    ],
    
    'magic' => [],
    
    'interfaces' => [],
    
    'custom_db_types' => [],
    
    'model_camel_case_properties' => false,
    
    'type_overrides' => [
        'integer' => 'int',
        'boolean' => 'bool',
    ],
    
    'include_class_docblocks' => false,
    
    'force_fqn' => false,
    
    'additional_relation_types' => [],
    
    'post_migrate' => [
        // Artisan commands to run after a migration is executed
        'ide-helper:models --write',
    ],
];
```

## 5. Best Practices

### 5.1. Setup in Composer

Automatically update the helpers when dependencies change:

```json
"scripts": {
    "post-update-cmd": [
        "@php artisan ide-helper:generate",
        "@php artisan ide-helper:meta"
    ]
}
```

### 5.2. Gitignore Configuration

It's good practice to ignore the helper files in Git:

```gitignore
# IDE Helper
_ide_helper.php
.phpstorm.meta.php
_ide_helper_models.php
```

### 5.3. Keeping Models Documentation Updated

Run the model helper after migrations:

```php
// In AppServiceProvider.php
public function register()
{
    if ($this->app->environment('local')) {
        $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
    }
}
```
