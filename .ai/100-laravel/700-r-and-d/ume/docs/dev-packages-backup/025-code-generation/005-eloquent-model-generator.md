# Eloquent Model Generator

## 1. Overview

Eloquent Model Generator is a tool for generating Eloquent models from existing database tables, saving development time and ensuring consistency.

### 1.1. Package Information

- **Package Name**: magentron/eloquent-model-generator
- **Version**: ^12.0.6
- **GitHub**: [https://github.com/magentron/eloquent-model-generator](https://github.com/magentron/eloquent-model-generator)
- **Documentation**: [https://github.com/magentron/eloquent-model-generator/blob/master/README.md](https://github.com/magentron/eloquent-model-generator/blob/master/README.md)

## 2. Key Features

- Generate Eloquent models from database schema
- Automatically determine column types
- Add proper relations between tables
- Customize namespace, base class, and output path
- Add custom traits and interfaces to generated models

## 3. Usage Examples

### 3.1. Basic Usage

```sh
// Generate a model for the 'users' table
php artisan generate:model User --table=users

// Generate model with custom namespace
php artisan generate:model User --table=users --namespace=App\\Models\\Admin

// Generate model with relations
php artisan generate:model Post --table=posts --with-relations
```

### 3.2. Configuration Options

```sh
// Available options
php artisan generate:model ModelName
    --table=table_name
    --output-path=models
    --namespace=App\\Models
    --base-class=Illuminate\\Database\\Eloquent\\Model
    --no-timestamps
    --date-format=Y-m-d H:i:s
    --connection=connection
    --with-relations
    --relation-namespace=App\\Models
    --template=path/to/template
```

## 4. Configuration

The generator can be configured through a published config file:

```php
<?php

declare(strict_types=1);

return [
    'namespace'           => 'App\\Models',
    'base_class_name'     => \Illuminate\Database\Eloquent\Model::class,
    'output_path'         => 'app/Models',
    'no_timestamps'       => false,
    'date_format'         => 'Y-m-d H:i:s',
    'connection'          => null,
    'with_relations'      => true,
    'relation_namespace'  => 'App\\Models',
    'template'            => null,
];
```

## 5. Best Practices

### 5.1. Customizing Generated Models

After generating models, you may want to customize them:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory, HasSlug;
    
    // Add custom code...
    
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
```

### 5.2. Regenerating Models

Models can be regenerated, but custom code will be lost. Consider:

1. Using traits for custom functionality
2. Extending generated models
3. Using model events in service providers
