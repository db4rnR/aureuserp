# Phase 3: Extension

## 1. Objectives

- Add advanced features to the Traits Management System
- Implement performance optimizations
- Add support for queue-based processing
- Implement telemetry and monitoring
- Add support for multi-tenancy

## 2. Implement Queue-Based Processing

### 2.1. Create a Trait Job Base Class

```php
<?php

namespace App\Models\Traits\Base;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class TraitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The model instance.
     *
     * @var Model
     */
    protected $model;
    
    /**
     * The operation to perform.
     *
     * @var string
     */
    protected $operation;
    
    /**
     * The data for the operation.
     *
     * @var array
     */
    protected $data;
    
    /**
     * Create a new job instance.
     *
     * @param Model $model
     * @param string $operation
     * @param array $data
     * @return void
     */
    public function __construct(Model $model, string $operation, array $data = [])
    {
        $this->model = $model;
        $this->operation = $operation;
        $this->data = $data;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $method = 'process' . ucfirst($this->operation);
        
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            throw new \RuntimeException("Unknown operation: {$this->operation}");
        }
    }
    
    /**
     * Get the trait name.
     *
     * @return string
     */
    abstract protected function getTraitName(): string;
    
    /**
     * Log a message about the job.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    protected function log(string $message, array $context = []): void
    {
        $context = array_merge($context, [
            'trait' => $this->getTraitName(),
            'model' => get_class($this->model),
            'model_id' => $this->model->getKey(),
            'operation' => $this->operation,
        ]);
        
        \Log::info("[TraitJob] {$message}", $context);
    }
}
```

### 2.2. Create Trait-Specific Job Classes

```php
<?php

namespace App\Models\Traits\Jobs;

use App\Models\Traits\Base\TraitJob;

class UserTrackingJob extends TraitJob
{
    /**
     * Get the trait name.
     *
     * @return string
     */
    protected function getTraitName(): string
    {
        return 'HasUserTracking';
    }
    
    /**
     * Process a cleanup operation.
     *
     * @return void
     */
    protected function processCleanup(): void
    {
        $this->log('Starting cleanup operation');
        
        // Implementation for cleanup operation
        // For example, removing orphaned user tracking records
        
        $this->log('Cleanup operation completed');
    }
    
    /**
     * Process an audit operation.
     *
     * @return void
     */
    protected function processAudit(): void
    {
        $this->log('Starting audit operation');
        
        // Implementation for audit operation
        // For example, verifying user tracking data integrity
        
        $this->log('Audit operation completed');
    }
}
```

### 2.3. Update TraitBase to Support Queue Operations

```php
<?php

// Add to the TraitBase trait

/**
 * Queue an operation to be processed in the background.
 *
 * @param string $operation The operation to queue
 * @param array $data The data for the operation
 * @param string|null $queue The queue to use
 * @return void
 */
protected function queueOperation(string $operation, array $data = [], ?string $queue = null): void
{
    $traitName = static::getTraitName();
    $jobClass = "\\App\\Models\\Traits\\Jobs\\{$traitName}Job";
    
    if (!class_exists($jobClass)) {
        throw new \RuntimeException("Job class not found for trait: {$traitName}");
    }
    
    $job = new $jobClass($this, $operation, $data);
    
    if ($queue) {
        $job->onQueue($queue);
    }
    
    dispatch($job);
    
    $this->logTraitMessage("Queued operation: {$operation}", $data);
    $this->recordMetric("queued_{$operation}");
}
```

## 3. Implement Telemetry and Monitoring

### 3.1. Create a Telemetry Service

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TelemetryService
{
    /**
     * Record a metric.
     *
     * @param string $name The metric name
     * @param mixed $value The metric value
     * @return void
     */
    public function record(string $name, $value = 1): void
    {
        // Increment counter in cache
        $cacheKey = "telemetry:{$name}";
        $currentValue = Cache::get($cacheKey, 0);
        Cache::put($cacheKey, $currentValue + $value, now()->addDay());
        
        // Log the metric if it's significant
        if ($value > 10 || $currentValue % 100 === 0) {
            Log::info("Telemetry: {$name} = {$value} (total: " . ($currentValue + $value) . ")");
        }
        
        // If a real monitoring service is configured, send the metric there
        if (config('services.monitoring.enabled')) {
            $this->sendToMonitoringService($name, $value);
        }
    }
    
    /**
     * Get all metrics.
     *
     * @return array<string, mixed>
     */
    public function getAllMetrics(): array
    {
        $metrics = [];
        $keys = Cache::get('telemetry:keys', []);
        
        foreach ($keys as $key) {
            $metrics[$key] = Cache::get("telemetry:{$key}", 0);
        }
        
        return $metrics;
    }
    
    /**
     * Reset all metrics.
     *
     * @return void
     */
    public function resetMetrics(): void
    {
        $keys = Cache::get('telemetry:keys', []);
        
        foreach ($keys as $key) {
            Cache::forget("telemetry:{$key}");
        }
        
        Cache::forget('telemetry:keys');
    }
    
    /**
     * Send a metric to the monitoring service.
     *
     * @param string $name The metric name
     * @param mixed $value The metric value
     * @return void
     */
    protected function sendToMonitoringService(string $name, $value): void
    {
        // Implementation for sending metrics to a monitoring service
        // This could be Prometheus, StatsD, New Relic, etc.
    }
}
```

### 3.2. Register the Telemetry Service

```php
<?php

// In the TraitsServiceProvider

/**
 * Register services.
 */
public function register(): void
{
    // Existing code...
    
    // Register the telemetry service
    $this->app->singleton('telemetry', function ($app) {
        return new \App\Services\TelemetryService();
    });
}
```

### 3.3. Create a Trait Monitoring Dashboard

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TraitMonitoringController extends Controller
{
    /**
     * Display the traits monitoring dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $metrics = app('telemetry')->getAllMetrics();
        $traitMetrics = [];
        
        // Group metrics by trait
        foreach ($metrics as $key => $value) {
            if (strpos($key, 'trait.') === 0) {
                $parts = explode('.', $key);
                $trait = $parts[1] ?? 'unknown';
                $metric = $parts[2] ?? 'unknown';
                
                $traitMetrics[$trait][$metric] = $value;
            }
        }
        
        return view('admin.traits.monitoring', [
            'traitMetrics' => $traitMetrics,
        ]);
    }
    
    /**
     * Reset all metrics.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        app('telemetry')->resetMetrics();
        
        return redirect()->route('admin.traits.monitoring')
            ->with('success', 'All metrics have been reset.');
    }
}
```

## 4. Implement Multi-Tenancy Support

### 4.1. Update TraitBase for Multi-Tenancy

```php
<?php

// Add to the TraitBase trait

/**
 * Get the current tenant ID.
 *
 * @return int|string|null
 */
protected function getCurrentTenantId()
{
    if (app()->bound('tenant')) {
        return app('tenant')->id;
    }
    
    return null;
}

/**
 * Scope a query to the current tenant.
 *
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopeForCurrentTenant($query)
{
    $tenantId = $this->getCurrentTenantId();
    
    if ($tenantId) {
        $tenantColumn = $this->getTenantColumn();
        return $query->where($tenantColumn, $tenantId);
    }
    
    return $query;
}

/**
 * Get the tenant column name.
 *
 * @return string
 */
protected function getTenantColumn(): string
{
    return config('traits.multi_tenancy.column', 'tenant_id');
}
```

### 4.2. Update Trait Configurations for Multi-Tenancy

```php
<?php

// Add to config/traits.php

'multi_tenancy' => [
    'enabled' => env('TRAITS_MULTI_TENANCY_ENABLED', false),
    'column' => env('TRAITS_MULTI_TENANCY_COLUMN', 'tenant_id'),
    'tenant_model' => env('TRAITS_MULTI_TENANCY_MODEL', \App\Models\Tenant::class),
],
```

### 4.3. Create a Tenant-Aware Trait Configuration

```php
<?php

// Update the TraitConfig class

/**
 * Get a configuration value for a trait, taking tenancy into account.
 *
 * @param string $traitName The trait name
 * @param string $key The configuration key
 * @param mixed $default The default value
 * @return mixed The configuration value
 */
public static function get(string $traitName, string $key, $default = null)
{
    $value = Config::get("traits.{$traitName}.{$key}", $default);
    
    // Check for tenant-specific overrides
    if (app()->has('tenant') && config('traits.multi_tenancy.enabled')) {
        $tenantId = app('tenant')->id;
        
        if ($tenantId) {
            $tenantOverride = Config::get("traits.tenant_overrides.{$tenantId}.{$traitName}.{$key}");
            
            if ($tenantOverride !== null) {
                return $tenantOverride;
            }
        }
    }
    
    // Check for model-specific overrides
    // (Existing code)
    
    return $value;
}

/**
 * Set a tenant-specific configuration override.
 *
 * @param int|string $tenantId The tenant ID
 * @param string $traitName The trait name
 * @param string $key The configuration key
 * @param mixed $value The configuration value
 * @return void
 */
public static function setTenantOverride($tenantId, string $traitName, string $key, $value): void
{
    Config::set("traits.tenant_overrides.{$tenantId}.{$traitName}.{$key}", $value);
}
```

## 5. Next Steps

After completing the Extension phase, we will move on to the Tooling phase, where we'll develop management and monitoring tools for the Traits Management System. See [Tooling Phase](020-tooling-phase.md) for details.
