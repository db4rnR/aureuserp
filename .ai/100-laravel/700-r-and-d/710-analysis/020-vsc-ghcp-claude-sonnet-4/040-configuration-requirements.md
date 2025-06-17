# 1. Configuration Requirements Analysis

## 1.1. Current Configuration Assessment

**Current State**: Minimal Laravel starter configuration
**Target State**: Complex multi-package configuration with event sourcing
**Configuration Complexity**: High - 15+ config files need modification
**Confidence: 90%** - Clear requirements from package analysis

## 1.2. Critical Configuration Files

### 1.2.1. Event Sourcing Configuration

~~~php
// config/event-sourcing.php (NEW FILE REQUIRED)
<?php

return [
    /*
     * The aggregate root repository is responsible for retrieving and saving events.
     */
    'aggregate_root_repository' => Spatie\EventSourcing\AggregateRoots\AggregateRootRepository::class,

    /*
     * The event serializer is responsible for serializing/deserializing events.
     */
    'event_serializer' => Spatie\EventSourcing\EventSerializers\JsonEventSerializer::class,

    /*
     * The snapshot repository is responsible for storing and retrieving snapshots.
     */
    'snapshot_repository' => Spatie\EventSourcing\Snapshots\SnapshotRepository::class,
    'snapshot_frequency' => 100, // Create snapshot every 100 events

    /*
     * The stored event model is responsible for storing events.
     */
    'stored_event_model' => Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent::class,

    /*
     * This is the name of the table that will be created by the migration.
     */
    'stored_events_table' => 'stored_events',
    'snapshots_table' => 'snapshots',

    /*
     * This is the queue connection that will be used for replaying events.
     */
    'queue_connection' => env('EVENT_SOURCING_QUEUE_CONNECTION', 'redis'),

    /*
     * Cache settings for performance optimization
     */
    'cache' => [
        'store' => env('EVENT_SOURCING_CACHE_STORE', 'redis'),
        'prefix' => 'event_sourcing',
        'ttl' => 3600, // 1 hour
    ],

    /*
     * Replay settings
     */
    'replay' => [
        'chunk_size' => 1000,
        'queue' => 'event-sourcing',
        'timeout' => 300, // 5 minutes
    ],

    /*
     * Event store settings
     */
    'event_store' => [
        'encryption' => [
            'enabled' => env('EVENT_SOURCING_ENCRYPTION', false),
            'key' => env('EVENT_SOURCING_ENCRYPTION_KEY'),
        ],
        'compression' => [
            'enabled' => env('EVENT_SOURCING_COMPRESSION', true),
            'algorithm' => 'gzip',
        ],
    ],
];
~~~

### 1.2.2. Enhanced Filament Configuration

~~~php
// config/filament.php (MODIFIED)
<?php

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

return [
    /*
     * Filament Panel Configuration
     */
    'panels' => [
        'admin' => [
            'id' => 'admin',
            'path' => env('FILAMENT_PATH', '/admin'),
            'domain' => env('FILAMENT_DOMAIN'),
            'spa' => env('FILAMENT_SPA_ENABLED', true),
            'brandName' => env('APP_NAME', 'Laravel'),
            'brandLogo' => null,
            'favicon' => null,
            'darkMode' => true,
            'collapsibleNavigationGroups' => true,
            'maxContentWidth' => 'full',
            'sidebarCollapsibleOnDesktop' => true,
            'sidebarFullyCollapsibleOnDesktop' => false,
            'navigationGroups' => [
                'Content Management' => [
                    'icon' => 'heroicon-o-document-text',
                    'collapsible' => true,
                ],
                'User Management' => [
                    'icon' => 'heroicon-o-users',
                    'collapsible' => true,
                ],
                'Project Management' => [
                    'icon' => 'heroicon-o-folder',
                    'collapsible' => true,
                ],
                'eCommerce' => [
                    'icon' => 'heroicon-o-shopping-bag',
                    'collapsible' => true,
                ],
                'System' => [
                    'icon' => 'heroicon-o-cog',
                    'collapsible' => true,
                ],
            ],
        ],
        
        'client' => [
            'id' => 'client',
            'path' => env('FILAMENT_CLIENT_PATH', '/portal'),
            'domain' => env('FILAMENT_CLIENT_DOMAIN'),
            'spa' => true,
            'brandName' => 'Client Portal',
            'middleware' => [
                'web',
                'auth:client',
            ],
        ],
    ],

    /*
     * Custom Field Components
     */
    'custom_fields' => [
        'status_field' => App\Filament\Forms\Components\StatusField::class,
        'enum_select' => App\Filament\Forms\Components\EnumSelect::class,
        'permission_picker' => App\Filament\Forms\Components\PermissionPicker::class,
    ],

    /*
     * Theme Configuration
     */
    'theme' => [
        'primary_color' => env('FILAMENT_PRIMARY_COLOR', '#3b82f6'),
        'secondary_color' => env('FILAMENT_SECONDARY_COLOR', '#64748b'),
        'custom_css' => resource_path('css/filament/admin/theme.css'),
        'custom_js' => resource_path('js/filament/admin/custom.js'),
    ],
];
~~~

### 1.2.3. Database Configuration Enhancement

~~~php
// config/database.php (MODIFIED SECTIONS)
<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
            // Enhanced for event sourcing
            'sticky' => true,
            'read_write_timeout' => 60,
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ],
        ],

        // Separate connection for event store (optional optimization)
        'event_store' => [
            'driver' => 'mysql',
            'host' => env('EVENT_STORE_DB_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('EVENT_STORE_DB_PORT', env('DB_PORT', '3306')),
            'database' => env('EVENT_STORE_DB_DATABASE', env('DB_DATABASE', 'forge')),
            'username' => env('EVENT_STORE_DB_USERNAME', env('DB_USERNAME', 'forge')),
            'password' => env('EVENT_STORE_DB_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => 'InnoDB',
        ],

        // Read replica for reporting (optional)
        'reporting' => [
            'driver' => 'mysql',
            'host' => env('REPORTING_DB_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('REPORTING_DB_PORT', env('DB_PORT', '3306')),
            'database' => env('REPORTING_DB_DATABASE', env('DB_DATABASE', 'forge')),
            'username' => env('REPORTING_DB_USERNAME', env('DB_USERNAME', 'forge')),
            'password' => env('REPORTING_DB_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => 'InnoDB',
        ],
    ],

    /*
     * Event Sourcing Database Settings
     */
    'event_sourcing' => [
        'connection' => env('EVENT_SOURCING_DB_CONNECTION', 'mysql'),
        'table_prefix' => env('EVENT_SOURCING_TABLE_PREFIX', ''),
        'partitioning' => [
            'enabled' => env('EVENT_SOURCING_PARTITIONING', false),
            'partition_by' => 'created_at', // or 'aggregate_root_id'
            'partition_count' => 12, // Monthly partitions
        ],
    ],
];
~~~

### 1.2.4. Queue Configuration for Event Processing

~~~php
// config/queue.php (ENHANCED)
<?php

return [
    'default' => env('QUEUE_CONNECTION', 'redis'),

    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
            'after_commit' => false,
        ],

        // Dedicated queue for event sourcing
        'event-sourcing' => [
            'driver' => 'redis',
            'connection' => 'event-sourcing',
            'queue' => 'event-sourcing',
            'retry_after' => 300, // 5 minutes for heavy operations
            'block_for' => null,
            'after_commit' => true, // Ensure events are committed
        ],

        // High priority queue for real-time features
        'high-priority' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'high-priority',
            'retry_after' => 30,
            'block_for' => null,
            'after_commit' => false,
        ],

        // Background processing queue
        'background' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'background',
            'retry_after' => 600, // 10 minutes
            'block_for' => null,
            'after_commit' => true,
        ],
    ],

    /*
     * Queue Priorities and Workers
     */
    'workers' => [
        'high-priority' => [
            'processes' => env('QUEUE_HIGH_PRIORITY_WORKERS', 3),
            'sleep' => 1,
            'max_time' => 60,
            'max_jobs' => 100,
        ],
        'event-sourcing' => [
            'processes' => env('QUEUE_EVENT_SOURCING_WORKERS', 2),
            'sleep' => 3,
            'max_time' => 300,
            'max_jobs' => 50,
        ],
        'default' => [
            'processes' => env('QUEUE_DEFAULT_WORKERS', 2),
            'sleep' => 3,
            'max_time' => 120,
            'max_jobs' => 100,
        ],
        'background' => [
            'processes' => env('QUEUE_BACKGROUND_WORKERS', 1),
            'sleep' => 5,
            'max_time' => 600,
            'max_jobs' => 10,
        ],
    ],
];
~~~

### 1.2.5. Cache Configuration for Performance

~~~php
// config/cache.php (ENHANCED)
<?php

return [
    'default' => env('CACHE_DRIVER', 'redis'),

    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],

        // Dedicated cache for event sourcing
        'event-sourcing' => [
            'driver' => 'redis',
            'connection' => 'event-sourcing-cache',
            'prefix' => 'es_cache',
            'serializer' => 'igbinary', // More efficient serialization
        ],

        // Cache for projections
        'projections' => [
            'driver' => 'redis',
            'connection' => 'projections-cache',
            'prefix' => 'proj_cache',
            'serializer' => 'igbinary',
        ],

        // Cache for static content
        'static' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/static'),
            'prefix' => 'static_cache',
        ],

        // Memory cache for request-level caching
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],
    ],

    /*
     * Cache Tags Configuration
     */
    'tags' => [
        'users' => ['user_data', 'permissions'],
        'projects' => ['project_data', 'tasks', 'time_entries'],
        'content' => ['cms_content', 'media', 'categories'],
        'events' => ['stored_events', 'projections'],
    ],

    /*
     * Cache Invalidation Rules
     */
    'invalidation' => [
        'user_updated' => ['users', 'permissions'],
        'project_status_changed' => ['projects'],
        'content_published' => ['content'],
        'event_recorded' => ['events', 'projections'],
    ],
];
~~~

## 1.3. Environment Configuration Templates

### 1.3.1. Development Environment

~~~bash
# .env.development
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# Enhanced Logging
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_dev
DB_USERNAME=root
DB_PASSWORD=

# Event Sourcing
EVENT_SOURCING_ENABLED=true
EVENT_SOURCING_QUEUE_CONNECTION=redis
EVENT_SOURCING_CACHE_STORE=redis
EVENT_SOURCING_ENCRYPTION=false
EVENT_SOURCING_COMPRESSION=true

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Queue Configuration
QUEUE_CONNECTION=redis
QUEUE_HIGH_PRIORITY_WORKERS=2
QUEUE_EVENT_SOURCING_WORKERS=1
QUEUE_DEFAULT_WORKERS=1
QUEUE_BACKGROUND_WORKERS=1

# Cache Configuration
CACHE_DRIVER=redis

# Filament Configuration
FILAMENT_PATH=/admin
FILAMENT_SPA_ENABLED=true
FILAMENT_PRIMARY_COLOR=#3b82f6

# Media Library
FILESYSTEM_DISK=local
MEDIA_DISK=public

# Search Configuration
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=

# Mail Configuration
MAIL_MAILER=log

# Social Features (Development)
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

# Performance Settings
DEBUGBAR_ENABLED=true
TELESCOPE_ENABLED=true
~~~

### 1.3.2. Production Environment Template

~~~bash
# .env.production
APP_NAME="Production App"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://your-domain.com

# Enhanced Logging
LOG_CHANNEL=daily
LOG_LEVEL=error
LOG_DAYS=14

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=laravel_prod
DB_USERNAME=laravel_user
DB_PASSWORD=secure_password

# Event Sourcing Production Settings
EVENT_SOURCING_ENABLED=true
EVENT_SOURCING_QUEUE_CONNECTION=redis
EVENT_SOURCING_CACHE_STORE=redis
EVENT_SOURCING_ENCRYPTION=true
EVENT_SOURCING_ENCRYPTION_KEY=base64:...
EVENT_SOURCING_COMPRESSION=true
EVENT_SOURCING_PARTITIONING=true

# Redis Configuration (Cluster)
REDIS_HOST=redis-cluster-endpoint
REDIS_PASSWORD=secure_redis_password
REDIS_PORT=6379
REDIS_CLUSTER=true

# Queue Configuration (Production)
QUEUE_CONNECTION=redis
QUEUE_HIGH_PRIORITY_WORKERS=5
QUEUE_EVENT_SOURCING_WORKERS=3
QUEUE_DEFAULT_WORKERS=3
QUEUE_BACKGROUND_WORKERS=2

# Cache Configuration
CACHE_DRIVER=redis

# Filament Configuration
FILAMENT_PATH=/admin
FILAMENT_SPA_ENABLED=true
FILAMENT_DOMAIN=admin.your-domain.com

# Media Library (S3)
FILESYSTEM_DISK=s3
MEDIA_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
AWS_USE_PATH_STYLE_ENDPOINT=false

# Search Configuration (Production)
SCOUT_DRIVER=elasticsearch
ELASTICSEARCH_HOST=https://elasticsearch-endpoint:9200
ELASTICSEARCH_USERNAME=elastic
ELASTICSEARCH_PASSWORD=secure_password

# Mail Configuration
MAIL_MAILER=ses
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

# Social Features (Production)
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=us2

# Security Settings
SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict

# Performance Settings
DEBUGBAR_ENABLED=false
TELESCOPE_ENABLED=false
OPCACHE_ENABLE=1
~~~

## 1.4. Service Provider Configuration

### 1.4.1. Custom Event Sourcing Service Provider

~~~php
// app/Providers/EventSourcingServiceProvider.php (NEW FILE)
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\EventSourcing\Facades\Projectionist;
use App\Projectors\UserProjector;
use App\Projectors\ProjectProjector;
use App\Projectors\ContentProjector;
use App\Reactors\EmailNotificationReactor;
use App\Reactors\ActivityLogReactor;

class EventSourcingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register event sourcing specific services
        $this->app->singleton('event-sourcing.snapshot-repository', function ($app) {
            return new \App\EventSourcing\SnapshotRepository(
                $app['cache.store']
            );
        });
    }

    public function boot(): void
    {
        if (!config('event-sourcing.enabled', true)) {
            return;
        }

        // Register projectors
        Projectionist::addProjectors([
            UserProjector::class,
            ProjectProjector::class,
            ContentProjector::class,
        ]);

        // Register reactors
        Projectionist::addReactors([
            EmailNotificationReactor::class,
            ActivityLogReactor::class,
        ]);

        // Register custom event serializers
        $this->registerEventSerializers();

        // Register snapshot repositories
        $this->registerSnapshotRepositories();
    }

    private function registerEventSerializers(): void
    {
        $this->app->bind(
            \Spatie\EventSourcing\EventSerializers\EventSerializer::class,
            \App\EventSourcing\CustomEventSerializer::class
        );
    }

    private function registerSnapshotRepositories(): void
    {
        $this->app->bind(
            \Spatie\EventSourcing\Snapshots\SnapshotRepository::class,
            \App\EventSourcing\SnapshotRepository::class
        );
    }
}
~~~

### 1.4.2. Enhanced Application Service Provider

~~~php
// app/Providers/AppServiceProvider.php (MODIFIED)
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register macros and custom implementations
        $this->registerMacros();
        
        // Register custom model implementations
        $this->registerCustomModels();
        
        // Register performance optimizations
        $this->registerPerformanceOptimizations();
    }

    public function boot(): void
    {
        // Security configurations
        Model::preventLazyLoading(!app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

        // Pagination configuration
        Paginator::defaultView('pagination::bootstrap-4');
        Paginator::defaultSimpleView('pagination::simple-bootstrap-4');

        // Global Gates
        $this->registerGlobalGates();

        // Custom validation rules
        $this->registerValidationRules();
    }

    private function registerMacros(): void
    {
        // Collection macros for event sourcing
        \Illuminate\Support\Collection::macro('toEventPayload', function () {
            return $this->map(function ($item) {
                return $item instanceof Model ? $item->toArray() : $item;
            })->toArray();
        });
    }

    private function registerCustomModels(): void
    {
        // Register model observers
        User::observe(\App\Observers\UserObserver::class);
        
        // Register custom model casts
        Model::macro('castAsEnum', function ($attribute, $enumClass) {
            return $enumClass::from($this->attributes[$attribute] ?? null);
        });
    }

    private function registerPerformanceOptimizations(): void
    {
        if (app()->isProduction()) {
            // Production optimizations
            \Illuminate\Database\Eloquent\Model::preventAccessingMissingAttributes();
            
            // Enable query result caching
            $this->app->singleton('query-cache', function () {
                return new \App\Services\QueryCacheService();
            });
        }
    }

    private function registerGlobalGates(): void
    {
        Gate::define('access-admin', function (User $user) {
            return $user->hasRole(['admin', 'super-admin']);
        });

        Gate::define('manage-organisation', function (User $user, $organisation) {
            return $user->organisation_id === $organisation->id 
                && $user->hasPermission('organisation.manage');
        });
    }

    private function registerValidationRules(): void
    {
        \Illuminate\Support\Facades\Validator::extend('enum_transition', function ($attribute, $value, $parameters, $validator) {
            if (count($parameters) < 2) {
                return false;
            }
            
            $enumClass = $parameters[0];
            $currentValue = $parameters[1];
            
            if (!enum_exists($enumClass)) {
                return false;
            }
            
            $currentEnum = $enumClass::from($currentValue);
            $targetEnum = $enumClass::from($value);
            
            return method_exists($currentEnum, 'canTransitionTo') 
                ? $currentEnum->canTransitionTo($targetEnum)
                : true;
        });
    }
}
~~~

## 1.5. Middleware Configuration

### 1.5.1. Event Sourcing Middleware

~~~php
// app/Http/Middleware/EnsureEventSourcingEnabled.php (NEW FILE)
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEventSourcingEnabled
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('event-sourcing.enabled', false)) {
            abort(503, 'Event sourcing is currently disabled');
        }

        return $next($request);
    }
}
~~~

### 1.5.2. Performance Monitoring Middleware

~~~php
// app/Http/Middleware/PerformanceMonitoring.php (NEW FILE)
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerformanceMonitoring
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        $response = $next($request);

        $executionTime = microtime(true) - $startTime;
        $memoryUsage = memory_get_usage(true) - $startMemory;

        // Log performance metrics for slow requests
        if ($executionTime > 1.0 || $memoryUsage > 10 * 1024 * 1024) { // 1 second or 10MB
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => round($executionTime, 3),
                'memory_usage' => $this->formatBytes($memoryUsage),
                'user_id' => auth()->id(),
            ]);
        }

        return $response;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
~~~

## 1.6. Configuration Validation

### 1.6.1. Configuration Health Check Command

~~~php
// app/Console/Commands/ConfigHealthCheck.php (NEW FILE)
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

class ConfigHealthCheck extends Command
{
    protected $signature = 'config:health-check {--fix}';
    protected $description = 'Check configuration health and optionally fix issues';

    public function handle()
    {
        $this->info('Running configuration health check...');
        
        $issues = [];
        
        // Check database connections
        $issues = array_merge($issues, $this->checkDatabaseConnections());
        
        // Check event sourcing configuration
        $issues = array_merge($issues, $this->checkEventSourcingConfig());
        
        // Check cache configuration
        $issues = array_merge($issues, $this->checkCacheConfig());
        
        // Check queue configuration
        $issues = array_merge($issues, $this->checkQueueConfig());
        
        // Check Filament configuration
        $issues = array_merge($issues, $this->checkFilamentConfig());
        
        if (empty($issues)) {
            $this->info('✅ All configuration checks passed!');
            return 0;
        }
        
        $this->warn('⚠️  Configuration issues found:');
        foreach ($issues as $issue) {
            $this->error("  • {$issue}");
        }
        
        if ($this->option('fix')) {
            $this->info('Attempting to fix issues...');
            return $this->fixIssues($issues);
        }
        
        $this->info('Run with --fix to attempt automatic fixes.');
        return 1;
    }
    
    private function checkDatabaseConnections(): array
    {
        $issues = [];
        
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $issues[] = "Default database connection failed: {$e->getMessage()}";
        }
        
        if (config('event-sourcing.enabled')) {
            try {
                DB::connection('event_store')->getPdo();
            } catch (\Exception $e) {
                $issues[] = "Event store database connection failed: {$e->getMessage()}";
            }
        }
        
        return $issues;
    }
    
    private function checkEventSourcingConfig(): array
    {
        $issues = [];
        
        if (!config('event-sourcing.enabled')) {
            return $issues;
        }
        
        // Check required tables exist
        $requiredTables = ['stored_events', 'snapshots'];
        foreach ($requiredTables as $table) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $issues[] = "Required event sourcing table '{$table}' does not exist";
            }
        }
        
        // Check queue connection
        try {
            Queue::connection(config('event-sourcing.queue_connection'))->size();
        } catch (\Exception $e) {
            $issues[] = "Event sourcing queue connection failed: {$e->getMessage()}";
        }
        
        return $issues;
    }
    
    private function checkCacheConfig(): array
    {
        $issues = [];
        
        try {
            Cache::store('redis')->put('health_check', 'test', 60);
            if (Cache::store('redis')->get('health_check') !== 'test') {
                $issues[] = 'Redis cache store not working properly';
            }
        } catch (\Exception $e) {
            $issues[] = "Redis cache connection failed: {$e->getMessage()}";
        }
        
        return $issues;
    }
    
    private function checkQueueConfig(): array
    {
        $issues = [];
        
        $connections = ['redis', 'event-sourcing', 'high-priority', 'background'];
        
        foreach ($connections as $connection) {
            try {
                Queue::connection($connection)->size();
            } catch (\Exception $e) {
                $issues[] = "Queue connection '{$connection}' failed: {$e->getMessage()}";
            }
        }
        
        return $issues;
    }
    
    private function checkFilamentConfig(): array
    {
        $issues = [];
        
        if (!config('filament.panels.admin.spa')) {
            $issues[] = 'Filament SPA mode is disabled - recommended for better performance';
        }
        
        $requiredDirs = [
            app_path('Filament/Resources'),
            app_path('Filament/Pages'),
            app_path('Filament/Widgets'),
        ];
        
        foreach ($requiredDirs as $dir) {
            if (!is_dir($dir)) {
                $issues[] = "Required Filament directory does not exist: {$dir}";
            }
        }
        
        return $issues;
    }
    
    private function fixIssues(array $issues): int
    {
        $fixed = 0;
        
        foreach ($issues as $issue) {
            if (str_contains($issue, 'directory does not exist')) {
                $dir = substr($issue, strrpos($issue, ':') + 2);
                if (mkdir($dir, 0755, true)) {
                    $this->info("✅ Created directory: {$dir}");
                    $fixed++;
                }
            }
        }
        
        $this->info("Fixed {$fixed} out of " . count($issues) . " issues.");
        return count($issues) - $fixed;
    }
}
~~~

## 1.7. Configuration Management Strategy

### 1.7.1. Environment-Specific Configuration Loading

~~~php
// config/app.php (ADDITION)
'configuration_profiles' => [
    'development' => [
        'event_sourcing' => true,
        'debug_toolbar' => true,
        'telescope' => true,
        'cache_driver' => 'array',
        'queue_driver' => 'sync',
    ],
    'staging' => [
        'event_sourcing' => true,
        'debug_toolbar' => false,
        'telescope' => true,
        'cache_driver' => 'redis',
        'queue_driver' => 'redis',
    ],
    'production' => [
        'event_sourcing' => true,
        'debug_toolbar' => false,
        'telescope' => false,
        'cache_driver' => 'redis',
        'queue_driver' => 'redis',
    ],
],
~~~

**Configuration Complexity Score**: 8.5/10 (High complexity)
**Implementation Timeline**: 2-3 weeks for full configuration setup
**Confidence: 87%** - Well-defined requirements with clear implementation path
**Risk Level**: Medium - Complexity in multi-environment coordination
