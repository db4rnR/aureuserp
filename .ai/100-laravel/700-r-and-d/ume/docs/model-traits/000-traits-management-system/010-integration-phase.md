# Phase 2: Integration

## 1. Objectives

- Adapt existing traits to use the new Traits Management System
- Refactor `HasUserTracking` and `HasAdditionalFeatures` to extend the base trait
- Ensure backward compatibility with existing models
- Implement trait-specific configurations

## 2. Refactor HasUserTracking Trait

### 2.1. Update HasUserTracking to Extend TraitBase

```php
<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Traits\Base\TraitBase;
use App\Models\Traits\Base\TraitConfig;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

/**
 * Trait HasUserTracking
 *
 * Automatically tracks and maintains created_by, updated_by, and deleted_by attributes
 * for Eloquent models.
 *
 * @property int|null $created_by_id ID of the user who created this record
 * @property int|null $updated_by_id ID of the user who last updated this record
 * @property int|null $deleted_by_id ID of the user who deleted this record (for soft deletes)
 * @property-read \App\Models\User|null $createdBy User who created this record
 * @property-read \App\Models\User|null $updatedBy User who last updated this record
 * @property-read \App\Models\User|null $deletedBy User who deleted this record
 */
trait HasUserTracking
{
    use TraitBase;
    
    /**
     * Initialize the HasUserTracking trait.
     */
    public function initializeHasUserTracking(): void
    {
        // This method will be called when the model is instantiated
        $this->fireTraitEvent('initialized', ['model' => $this]);
    }
    
    /**
     * Boot the HasUserTracking trait.
     */
    protected static function bootHasUserTracking(): void
    {
        if (!static::isFeatureEnabled('enabled', 'HasUserTracking')) {
            return;
        }
        
        // Set created_by and updated_by on creation
        static::creating(function (Model $model): void {
            if (!static::isFeatureEnabled('created_by', 'HasUserTracking')) {
                return;
            }
            
            $userId = self::getCurrentUserId();
            
            if ($userId) {
                $createdByColumn = $model->getUserTrackingColumnName('created');
                $updatedByColumn = $model->getUserTrackingColumnName('updated');
                
                if (!$model->{$createdByColumn}) {
                    $model->{$createdByColumn} = $userId;
                }
                
                if (!$model->{$updatedByColumn}) {
                    $model->{$updatedByColumn} = $userId;
                }
                
                $model->fireTraitEvent('created_by_set', [
                    'model' => $model,
                    'user_id' => $userId,
                ]);
                
                $model->recordMetric('created_by_set');
            }
        });
        
        // Rest of the event handlers...
        // (Updated to use TraitBase methods for events, metrics, etc.)
    }
    
    /**
     * Get the column name used for tracking the specified user action type.
     *
     * @param string $type The type of action (created, updated, deleted)
     * @return string The column name
     */
    protected function getUserTrackingColumnName(string $type): string
    {
        return $this->cacheTraitValue("column_name_{$type}", function () use ($type) {
            return TraitConfig::get('HasUserTracking', "columns.{$type}", "{$type}_by_id");
        });
    }
    
    // Rest of the trait methods...
    // (Updated to use TraitBase methods for caching, logging, etc.)
}
```

### 2.2. Create HasUserTracking Configuration

```php
<?php

// config/traits/user_tracking.php

return [
    /*
    |--------------------------------------------------------------------------
    | User Tracking Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the HasUserTracking trait.
    |
    */
    
    // Enable/disable the entire trait
    'enabled' => env('USER_TRACKING_ENABLED', true),
    
    // Features that can be enabled/disabled
    'features' => [
        'created_by' => env('USER_TRACKING_CREATED_BY_ENABLED', true),
        'updated_by' => env('USER_TRACKING_UPDATED_BY_ENABLED', true),
        'deleted_by' => env('USER_TRACKING_DELETED_BY_ENABLED', true),
    ],
    
    // Column names for tracking
    'columns' => [
        'created' => 'created_by_id',
        'updated' => 'updated_by_id',
        'deleted' => 'deleted_by_id',
    ],
    
    // User model to use for relationships
    'user_model' => \App\Models\User::class,
    
    // Cache settings
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 hour
    ],
    
    // Logging settings
    'logging' => [
        'enabled' => true,
        'level' => 'info',
    ],
];
```

## 3. Refactor HasAdditionalFeatures Trait

### 3.1. Update HasAdditionalFeatures to Extend TraitBase

```php
<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Traits\Base\TraitBase;
use App\Models\Traits\Base\TraitConfig;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

/**
 * Trait HasAdditionalFeatures
 *
 * Provides core model features: ULID generation, slugging, activity logging,
 * comments, tagging, search indexing, and soft deletes.
 *
 * @property string|null $ulid Universally Unique Lexicographically Sortable Identifier
 * @property string|null $slug URL-friendly slug for the model
 * @property array<string, mixed> $translations Translations for translatable attributes
 * @property bool $published Whether the model is published and searchable
 * 
 * @method static Builder|static published() Scope to get only published models
 * @method static Builder|static draft() Scope to get only draft models
 * @method static Builder|static withAllTags(array|string $tags, ?string $type = null) Scope to get models with all the given tags
 * @method static Builder|static withAnyTags(array|string $tags, ?string $type = null) Scope to get models with any of the given tags
 * @method static Builder|static withoutTags(array|string $tags, ?string $type = null) Scope to get models without any of the given tags
 */
trait HasAdditionalFeatures
{
    use TraitBase;
    
    /**
     * Initialize the HasAdditionalFeatures trait.
     */
    public function initializeHasAdditionalFeatures(): void
    {
        // This method will be called when the model is instantiated
        $this->fireTraitEvent('initialized', ['model' => $this]);
        
        // Set up translatable attributes from config if not already set
        if (!isset($this->translatable) && $this->isFeatureEnabled('translatable', 'HasAdditionalFeatures')) {
            $this->translatable = $this->getTranslatableAttributes();
        }
    }
    
    // Rest of the trait implementation...
    // (Updated to use TraitBase methods for events, metrics, caching, etc.)
}
```

### 3.2. Create HasAdditionalFeatures Configuration

```php
<?php

// config/traits/additional_features.php

return [
    /*
    |--------------------------------------------------------------------------
    | Additional Features Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the HasAdditionalFeatures trait.
    |
    */
    
    // Enable/disable the entire trait
    'enabled' => env('ADDITIONAL_FEATURES_ENABLED', true),
    
    // Features that can be enabled/disabled
    'features' => [
        'ulid' => env('FEATURE_ULID_ENABLED', true),
        'sluggable' => env('FEATURE_SLUGGABLE_ENABLED', true),
        'translatable' => env('FEATURE_TRANSLATABLE_ENABLED', true),
        'activity_log' => env('FEATURE_ACTIVITY_LOG_ENABLED', true),
        'comments' => env('FEATURE_COMMENTS_ENABLED', true),
        'tags' => env('FEATURE_TAGS_ENABLED', true),
        'searchable' => env('FEATURE_SEARCHABLE_ENABLED', true),
        'soft_deletes' => env('FEATURE_SOFT_DELETES_ENABLED', true),
    ],
    
    // Feature-specific configurations
    // (Same as the existing configuration, but organized under the traits system)
];
```

## 4. Create Migration Path for Existing Models

### 4.1. Create a Migration Helper Command

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MigrateToTraitsSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'traits:migrate-models
                            {--model= : The model to migrate (or "all" for all models)}
                            {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing models to use the new Traits Management System';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelOption = $this->option('model');
        $dryRun = $this->option('dry-run');
        
        if ($modelOption === 'all') {
            $this->migrateAllModels($dryRun);
        } elseif ($modelOption) {
            $this->migrateModel($modelOption, $dryRun);
        } else {
            $this->error('Please specify a model to migrate or use --model=all');
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Migrate all models in the application.
     */
    protected function migrateAllModels(bool $dryRun): void
    {
        $modelFiles = File::glob(app_path('Models/*.php'));
        $count = 0;
        
        foreach ($modelFiles as $file) {
            $className = 'App\\Models\\' . pathinfo($file, PATHINFO_FILENAME);
            
            if (class_exists($className)) {
                $this->migrateModel($className, $dryRun);
                $count++;
            }
        }
        
        $this->info("Migrated {$count} models to the new Traits Management System");
    }
    
    /**
     * Migrate a specific model.
     */
    protected function migrateModel(string $modelClass, bool $dryRun): void
    {
        // Implementation details for migrating a model
        // This would analyze the model, detect traits, and update configurations
    }
}
```

### 4.2. Create a Backward Compatibility Layer

```php
<?php

namespace App\Models\Traits\Compatibility;

/**
 * Provides backward compatibility for models using the old trait system.
 */
trait BackwardCompatibility
{
    /**
     * Map old method names to new method names.
     *
     * @var array<string, string>
     */
    protected static $methodMap = [
        // HasUserTracking
        'withoutUserTracking' => 'withoutFeature',
        
        // HasAdditionalFeatures
        'withoutFeatures' => 'withoutFeatures',
    ];
    
    /**
     * Handle calls to missing methods.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (isset(static::$methodMap[$method])) {
            $newMethod = static::$methodMap[$method];
            return $this->$newMethod(...$parameters);
        }
        
        return parent::__call($method, $parameters);
    }
    
    /**
     * Handle calls to missing static methods.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if (isset(static::$methodMap[$method])) {
            $newMethod = static::$methodMap[$method];
            return static::$newMethod(...$parameters);
        }
        
        return parent::__callStatic($method, $parameters);
    }
}
```

## 5. Next Steps

After completing the Integration phase, we will move on to the Extension phase, where we'll add advanced features and optimizations to the Traits Management System. See [Extension Phase](015-extension-phase.md) for details.
