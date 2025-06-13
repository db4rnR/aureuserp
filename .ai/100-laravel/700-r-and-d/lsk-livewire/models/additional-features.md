# Additional Features for Models

This document explains how to use the `HasAdditionalFeatures` trait to add powerful functionality to your Eloquent models.

## Overview

The `HasAdditionalFeatures` trait provides a comprehensive set of features for your models:

- **ULID Generation**: Automatically generates ULIDs (Universally Unique Lexicographically Sortable Identifiers)
- **Sluggable**: Creates URL-friendly slugs for your models
- **Translatable**: Supports multilingual content with automatic translations
- **Activity Logging**: Tracks all changes to your models
- **Comments**: Adds commenting functionality to your models
- **Tagging**: Allows tagging models with categories or keywords
- **Search Indexing**: Makes models searchable with Laravel Scout
- **Soft Deletes**: Implements soft delete functionality

## Features

- Configurable via a global configuration file
- Selectively enable/disable features
- Customizable column names and behavior
- Provides helper methods for common tasks
- Integrates with popular Laravel packages
- Supports temporary disabling of features
- Includes comprehensive logging options
- Provides scopes for filtering models

## Requirements

1. Your model's database table should have the appropriate columns for the features you want to use:
   - `ulid` (string) for ULID generation
   - `slug` (string) for sluggable models
   - `published` (boolean) for controlling search indexing
   - Appropriate columns for translatable content

2. Required packages should be installed based on the features you want to use:
   - `spatie/laravel-sluggable` for slugs
   - `spatie/laravel-translatable` for translations
   - `spatie/laravel-activitylog` for activity logging
   - `spatie/laravel-tags` for tagging
   - `spatie/laravel-comments` for comments
   - `laravel/scout` for search indexing

## Adding Additional Features to a Model

### Step 1: Add the trait to your model

```php
use App\Models\Traits\HasAdditionalFeatures;

class YourModel extends Model
{
    use HasAdditionalFeatures;
    
    // Rest of your model...
}
```

### Step 2: Configure the features

You can configure the features globally in the `config/additional-features.php` file or on a per-model basis by overriding methods.

## Global Configuration

The trait is highly configurable via the `config/additional-features.php` file:

```php
return [
    'enabled' => [
        'ulid' => true,
        'sluggable' => true,
        'translatable' => true,
        'activity_log' => true,
        'comments' => true,
        'tags' => true,
        'searchable' => true,
        'soft_deletes' => true,
    ],
    
    'ulid' => [
        'column' => 'ulid',
        'auto_generate' => true,
    ],
    
    'sluggable' => [
        'column' => 'slug',
        'source' => 'name',
        'locales' => ['en', 'de', 'es', 'fr', 'it', 'nl'],
        'update_on_change' => false,
    ],
    
    // Additional configuration options...
];
```

## Using the Features

### ULID Generation

ULIDs are automatically generated for new models if the `ulid` feature is enabled:

```php
$model = YourModel::create(['name' => 'Example']);
echo $model->ulid; // Outputs something like "01ARZ3NDEKTSV4RRFFQ69G5FAV"
```

### Sluggable Models

Slugs are automatically generated based on the configured source attribute:

```php
$model = YourModel::create(['name' => 'Example Title']);
echo $model->slug; // Outputs "example-title"
```

### Translatable Content

You can store and retrieve translations for attributes:

```php
$model = YourModel::create([
    'name' => [
        'en' => 'Example',
        'es' => 'Ejemplo',
        'fr' => 'Exemple',
    ],
]);

echo $model->getTranslation('name', 'es'); // Outputs "Ejemplo"
```

### Activity Logging

All changes to models are automatically logged:

```php
$model = YourModel::create(['name' => 'Example']);
$model->update(['name' => 'Updated Example']);

// Activity log will contain entries for both the creation and update
```

### Comments

Models can be commented on:

```php
$user = User::find(1);
$model = YourModel::find(1);

$comment = $model->comment('This is a comment', $user);
```

### Tagging

Models can be tagged with categories or keywords:

```php
$model = YourModel::find(1);
$model->attachTags(['important', 'featured']);

// Get models with specific tags
$models = YourModel::withAllTags(['important', 'featured'])->get();
```

### Search Indexing

Models are automatically indexed for search:

```php
// Search for models
$results = YourModel::search('example')->get();

// Filter by published status
$publishedModels = YourModel::published()->get();
$draftModels = YourModel::draft()->get();
```

### Soft Deletes

Models can be soft deleted and restored:

```php
$model = YourModel::find(1);
$model->delete(); // Soft delete

// Restore the model
YourModel::withTrashed()->find(1)->restore();
```

## Advanced Usage

### Temporarily Disabling Features

You can temporarily disable all features for specific operations:

```php
YourModel::withoutFeatures(function () {
    // Features are disabled within this callback
    $model = YourModel::create([
        'name' => 'No features for this creation',
    ]);
    
    return $model;
});

// Features are re-enabled outside the callback
```

### Custom Display Names

The trait provides a method to get a human-friendly display name for the model:

```php
$model = YourModel::find(1);
echo $model->getDisplayName(); // Uses the best available attribute for display
```

### Custom Searchable Fields

You can customize which fields are included in the search index:

```php
class YourModel extends Model
{
    use HasAdditionalFeatures;
    
    /**
     * Get the fields to include in the search index.
     */
    public function getSearchableFields(): array
    {
        return ['title', 'description', 'category'];
    }
}
```

## How It Works

The trait uses Laravel's model events to automatically handle various features:

- `creating`: Generates ULIDs for new models
- `created`, `updated`, `deleted`, `restored`: Logs model events
- Various other events are handled by the included traits

The trait dynamically enables/disables features based on configuration, making it highly flexible and customizable.
