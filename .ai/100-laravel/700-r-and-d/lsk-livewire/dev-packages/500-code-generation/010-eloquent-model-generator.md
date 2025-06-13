# Eloquent Model Generator

## 1. Overview

Eloquent Model Generator is a tool that generates Eloquent models based on your database schema. It automatically creates model files with proper property type hints, relationships, and other useful information derived from your database structure.

### 1.1. Package Information

- **Package Name**: krlove/eloquent-model-generator
- **Version**: ^2.0.1
- **GitHub**: [https://github.com/krlove/eloquent-model-generator](https://github.com/krlove/eloquent-model-generator)
- **Documentation**: [https://github.com/krlove/eloquent-model-generator#readme](https://github.com/krlove/eloquent-model-generator#readme)

## 2. Key Features

- Generates Eloquent models from database schema
- Creates property type hints
- Detects and adds relationships
- Supports custom templates
- Configurable output paths
- Customizable namespace
- Support for table prefixes
- PHPDoc generation
- Support for Laravel 12 and PHP 8.4
- Customizable model parent class
- Support for model traits

## 3. Installation

```bash
composer require --dev krlove/eloquent-model-generator
```

## 4. Configuration

### 4.1. Basic Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Krlove\\EloquentModelGenerator\\Provider\\GeneratorServiceProvider"
```

This creates a `config/eloquent_model_generator.php` file.

### 4.2. Configuration Options

The main configuration options in `config/eloquent_model_generator.php`:

```php
<?php

return [
    'namespace' => 'App\\Models',
    'base_class_name' => \Illuminate\Database\Eloquent\Model::class,
    'output_path' => app_path('Models'),
    'no_timestamps' => null,
    'date_format' => null,
    'connection' => null,
    'no_backup' => null,
    'db_types' => null,
    'table_prefix' => null,
    'custom_template_path' => null,
];
```

### 4.3. Command-Line Options

You can override configuration options when running the command:

```bash
php artisan krlove:generate:model User \
    --table-name=users \
    --output-path=app/Models \
    --namespace=App\\Models \
    --base-class-name=Illuminate\\Database\\Eloquent\\Model \
    --no-timestamps \
    --date-format=Y-m-d \
    --connection=mysql \
    --no-backup \
    --table-prefix=prefix_ \
    --template-path=path/to/template
```

## 5. Usage

### 5.1. Basic Usage

Generate a model for a specific table:

```bash
php artisan krlove:generate:model User --table-name=users
```

### 5.2. Specifying Output Path

Generate a model with a custom output path:

```bash
php artisan krlove:generate:model User --table-name=users --output-path=app/Models/Auth
```

### 5.3. Custom Namespace

Generate a model with a custom namespace:

```bash
php artisan krlove:generate:model User --table-name=users --namespace=App\\Models\\Auth
```

### 5.4. Custom Base Class

Generate a model with a custom base class:

```bash
php artisan krlove:generate:model User --table-name=users --base-class-name=App\\Models\\BaseModel
```

### 5.5. Timestamps Configuration

Generate a model without timestamps:

```bash
php artisan krlove:generate:model User --table-name=users --no-timestamps
```

### 5.6. Date Format

Generate a model with a custom date format:

```bash
php artisan krlove:generate:model User --table-name=users --date-format=Y-m-d
```

### 5.7. Database Connection

Generate a model using a specific database connection:

```bash
php artisan krlove:generate:model User --table-name=users --connection=mysql
```

### 5.8. Table Prefix

Generate a model with a table prefix:

```bash
php artisan krlove:generate:model User --table-name=users --table-prefix=prefix_
```

## 6. Integration with Laravel 12 and PHP 8.4

Eloquent Model Generator is compatible with Laravel 12 and PHP 8.4. It generates models with:

- PHP 8.4 syntax
- Type declarations
- Property promotion
- Attribute syntax
- Laravel 12 conventions

## 7. Advanced Usage

### 7.1. Custom Templates

Create a custom template for your models:

1. Create a template file (e.g., `resources/templates/model.template`):

```php
<?php

namespace {{ namespace }};

use {{ baseClassName }};
{{ properties }}
class {{ class }} extends {{ baseClass }}
{
    {{ body }}
    
    // Custom methods
    public function customMethod()
    {
        // Your custom code
    }
}
```

2. Use the custom template:

```bash
php artisan krlove:generate:model User --table-name=users --template-path=resources/templates/model.template
```

### 7.2. Generating Multiple Models

Generate models for all tables:

```bash
# Get all tables
php artisan db:table
# Generate models for each table
php artisan krlove:generate:model User --table-name=users
php artisan krlove:generate:model Post --table-name=posts
php artisan krlove:generate:model Comment --table-name=comments
```

### 7.3. Customizing Generated Models

After generating models, you may want to customize them:

1. Add custom methods
2. Add validation rules
3. Add scopes
4. Add accessors and mutators
5. Add custom relationships

## 8. Best Practices

1. **Generate Once, Customize Later**: Use the generator to create initial models, then customize them manually
2. **Version Control**: Keep generated models in version control
3. **Custom Templates**: Create custom templates for consistent model structure
4. **Review Relationships**: Always review and adjust generated relationships
5. **Add Missing Features**: Add features not detected by the generator (scopes, validation, etc.)

## 9. Troubleshooting

### 9.1. Relationship Detection Issues

If relationships aren't detected correctly:

1. Ensure your database follows naming conventions
2. Add relationships manually after generation
3. Use a custom template with predefined relationships

### 9.2. Type Hint Issues

If type hints are incorrect:

1. Check your database column types
2. Adjust type hints manually after generation
3. Create a custom template with specific type mappings

### 9.3. Namespace Issues

If you encounter namespace issues:

1. Ensure the namespace in your configuration matches your project structure
2. Check for typos in namespace parameters
3. Verify that the output path matches the namespace
