# Phase 1: Foundation

## 1. Objectives

- Establish the core architecture for the Traits Management System
- Create the base trait that all other traits will extend
- Implement the configuration system
- Set up the event system for trait operations

## 2. Create the Base Trait

### 2.1. Create the TraitBase Class

```php
<?php

namespace App\Models\Traits\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

trait TraitBase
{
    /**
     * Flag to enable/disable all trait features.
     *
     * @var bool
     */
    private static bool $featuresEnabled = true;
    
    /**
     * Disabled features for this trait.
     *
     * @var array<string, bool>
     */
    private static array $disabledFeatures = [];
    
    /**
     * Initialize the trait.
     * This method is automatically called by Laravel when the trait is used.
     */
    public function initializeTraitBase(): void
    {
        // This method will be called when the model is instantiated
        $this->fireTraitEvent('initialized', ['model' => $this]);
    }
    
    /**
     * Check if a specific feature is enabled.
     *
     * @param string $feature The feature to check
     * @param string|null $traitName The trait name (defaults to the current trait)
     * @return bool Whether the feature is enabled
     */
    protected static function isFeatureEnabled(string $feature, ?string $traitName = null): bool
    {
        if (!static::$featuresEnabled) {
            return false;
        }
        
        if (isset(static::$disabledFeatures[$feature]) && static::$disabledFeatures[$feature]) {
            return false;
        }
        
        $traitName = $traitName ?? static::getTraitName();
        $configKey = "traits.{$traitName}.features.{$feature}";
        
        return Config::get($configKey, true);
    }
    
    /**
     * Get the trait name.
     *
     * @return string The trait name
     */
    protected static function getTraitName(): string
    {
        $className = static::class;
        $parts = explode('\\', $className);
        return end($parts);
    }
    
    /**
     * Temporarily disable all features for a callback.
     *
     * @param callable $callback
     * @return mixed
     */
    public static function withoutFeatures(callable $callback)
    {
        $originalValue = static::$featuresEnabled;
        static::$featuresEnabled = false;
        
        try {
            return $callback();
        } finally {
            static::$featuresEnabled = $originalValue;
        }
    }
    
    /**
     * Temporarily disable a specific feature for a callback.
     *
     * @param string $feature The feature to disable
     * @param callable $callback
     * @return mixed
     */
    public static function withoutFeature(string $feature, callable $callback)
    {
        static::$disabledFeatures[$feature] = true;
        
        try {
            return $callback();
        } finally {
            static::$disabledFeatures[$feature] = false;
        }
    }
    
    /**
     * Fire a trait event.
     *
     * @param string $event The event name
     * @param array $payload The event payload
     * @return void
     */
    protected function fireTraitEvent(string $event, array $payload = []): void
    {
        $traitName = static::getTraitName();
        Event::dispatch("trait.{$traitName}.{$event}", $payload);
    }
    
    /**
     * Record a metric for telemetry.
     *
     * @param string $metric The metric name
     * @param mixed $value The metric value
     * @return void
     */
    protected function recordMetric(string $metric, $value = 1): void
    {
        $traitName = static::getTraitName();
        
        if (app()->bound('telemetry') && Config::get('traits.telemetry_enabled', false)) {
            app('telemetry')->record("trait.{$traitName}.{$metric}", $value);
        }
    }
    
    /**
     * Cache a value with an automatic key based on the model and trait.
     *
     * @param string $key The cache key suffix
     * @param \Closure $callback The callback to generate the value
     * @param int|\DateTimeInterface|\DateInterval|null $ttl The cache TTL
     * @return mixed The cached value
     */
    protected function cacheTraitValue(string $key, \Closure $callback, $ttl = null)
    {
        $traitName = static::getTraitName();
        $modelClass = get_class($this);
        $modelKey = $this->getKey();
        
        $cacheKey = "trait:{$traitName}:{$modelClass}:{$modelKey}:{$key}";
        
        return Cache::remember($cacheKey, $ttl ?? now()->addHour(), $callback);
    }
    
    /**
     * Log a trait-specific message.
     *
     * @param string $message The message to log
     * @param array $context The log context
     * @param string $level The log level
     * @return void
     */
    protected function logTraitMessage(string $message, array $context = [], string $level = 'info'): void
    {
        $traitName = static::getTraitName();
        $context = array_merge($context, [
            'trait' => $traitName,
            'model' => get_class($this),
            'model_id' => $this->getKey(),
        ]);
        
        Log::{$level}("[Trait: {$traitName}] {$message}", $context);
    }
}
```

### 2.2. Create the TraitConfig Class

```php
<?php

namespace App\Models\Traits\Base;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class TraitConfig
{
    /**
     * Get a configuration value for a trait.
     *
     * @param string $traitName The trait name
     * @param string $key The configuration key
     * @param mixed $default The default value
     * @return mixed The configuration value
     */
    public static function get(string $traitName, string $key, $default = null)
    {
        $value = Config::get("traits.{$traitName}.{$key}", $default);
        
        // Check for model-specific overrides
        if (app()->has('trait_model_config')) {
            $modelClass = app('trait_model_config.current_model');
            $modelKey = app('trait_model_config.current_key');
            
            if ($modelClass && $modelKey) {
                $modelOverride = Config::get("traits.model_overrides.{$modelClass}.{$traitName}.{$key}");
                
                if ($modelOverride !== null) {
                    return $modelOverride;
                }
            }
        }
        
        return $value;
    }
    
    /**
     * Set a configuration value for a trait.
     *
     * @param string $traitName The trait name
     * @param string $key The configuration key
     * @param mixed $value The configuration value
     * @return void
     */
    public static function set(string $traitName, string $key, $value): void
    {
        Config::set("traits.{$traitName}.{$key}", $value);
    }
    
    /**
     * Set a model-specific configuration override.
     *
     * @param string $modelClass The model class
     * @param string $traitName The trait name
     * @param string $key The configuration key
     * @param mixed $value The configuration value
     * @return void
     */
    public static function setModelOverride(string $modelClass, string $traitName, string $key, $value): void
    {
        Config::set("traits.model_overrides.{$modelClass}.{$traitName}.{$key}", $value);
    }
}
```

## 3. Create the Configuration System

### 3.1. Create the Base Configuration File

```php
<?php

// config/traits.php

return [
    /*
    |--------------------------------------------------------------------------
    | Traits System Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the Traits Management System.
    |
    */

    // Global enable/disable switch for all traits
    'enabled' => env('TRAITS_ENABLED', true),
    
    // Enable telemetry for traits
    'telemetry_enabled' => env('TRAITS_TELEMETRY_ENABLED', false),
    
    // Default cache TTL for trait operations
    'cache_ttl' => env('TRAITS_CACHE_TTL', 3600), // 1 hour
    
    // Model-specific overrides
    'model_overrides' => [
        // Example:
        // App\Models\User::class => [
        //     'HasUserTracking' => [
        //         'features' => [
        //             'created_by' => false,
        //         ],
        //     ],
        // ],
    ],
    
    // Individual trait configurations
    'HasUserTracking' => [
        'features' => [
            'created_by' => true,
            'updated_by' => true,
            'deleted_by' => true,
        ],
        'columns' => [
            'created_by' => 'created_by_id',
            'updated_by' => 'updated_by_id',
            'deleted_by' => 'deleted_by_id',
        ],
        'user_model' => \App\Models\User::class,
    ],
    
    'HasAdditionalFeatures' => [
        'features' => [
            'ulid' => true,
            'sluggable' => true,
            'translatable' => true,
            'activity_log' => true,
            'comments' => true,
            'tags' => true,
            'searchable' => true,
            'soft_deletes' => true,
        ],
        // Other configuration options...
    ],
];
```

### 3.2. Create a Service Provider for the Traits System

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TraitsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/traits.php', 'traits'
        );
        
        // Register the trait model config service
        $this->app->singleton('trait_model_config', function ($app) {
            return new \stdClass();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/traits.php' => config_path('traits.php'),
            ], 'config');
        }
        
        // Register event listeners for trait events
        $this->registerEventListeners();
    }
    
    /**
     * Register event listeners for trait events.
     */
    protected function registerEventListeners(): void
    {
        // Example:
        // Event::listen('trait.HasUserTracking.*', function ($event, $payload) {
        //     // Handle trait events
        // });
    }
}
```

## 4. Set Up the Event System

### 4.1. Create a Trait Event Dispatcher

```php
<?php

namespace App\Models\Traits\Base;

use Illuminate\Support\Facades\Event;

class TraitEventDispatcher
{
    /**
     * Dispatch a trait event.
     *
     * @param string $traitName The trait name
     * @param string $event The event name
     * @param array $payload The event payload
     * @return void
     */
    public static function dispatch(string $traitName, string $event, array $payload = []): void
    {
        // Dispatch a specific event
        Event::dispatch("trait.{$traitName}.{$event}", $payload);
        
        // Dispatch a wildcard event for all events of this trait
        Event::dispatch("trait.{$traitName}.*", [$event, $payload]);
        
        // Dispatch a global wildcard event for all trait events
        Event::dispatch("trait.*.*", [$traitName, $event, $payload]);
    }
}
```

### 4.2. Create a Trait Event Listener Base Class

```php
<?php

namespace App\Models\Traits\Base;

abstract class TraitEventListener
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     * @return void
     */
    public function subscribe($events): void
    {
        $events->listen(
            "trait.{$this->getTrait()}.{$this->getEvent()}",
            [$this, 'handle']
        );
    }
    
    /**
     * Get the trait name.
     *
     * @return string
     */
    abstract protected function getTrait(): string;
    
    /**
     * Get the event name.
     *
     * @return string
     */
    abstract protected function getEvent(): string;
    
    /**
     * Handle the event.
     *
     * @param array $payload
     * @return void
     */
    abstract public function handle(array $payload): void;
}
```

## 5. Next Steps

After completing the foundation phase, we will move on to the Integration phase, where we'll adapt existing traits to use the new Traits Management System. See [Integration Phase](010-integration-phase.md) for details.
