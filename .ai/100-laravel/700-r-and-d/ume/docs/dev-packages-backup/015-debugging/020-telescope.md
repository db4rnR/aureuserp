# Laravel Telescope

## 1. Overview

Laravel Telescope is an elegant debug assistant for the Laravel framework, providing insight into requests, exceptions, log entries, database queries, queued jobs, mail, notifications, cache operations, scheduled tasks, variable dumps, and more.

### 1.1. Package Information

- **Package Name**: laravel/telescope
- **Version**: ^5.7.0
- **GitHub**: [https://github.com/laravel/telescope](https://github.com/laravel/telescope)
- **Documentation**: [https://laravel.com/docs/telescope](https://laravel.com/docs/telescope)

## 2. Key Features

- Request monitoring
- Exception tracking
- Log entry recording
- Database query monitoring
- Queue job tracking
- Mail analysis
- Notification tracking
- Cache operation monitoring
- Scheduled tasks overview
- Dump recording
- Gate and policy checks

## 3. Usage Examples

### 3.1. Basic Usage

Telescope provides a web interface accessible at `/telescope` in your application.

```php
// You can also use the Telescope facades in your code
use Laravel\Telescope\Telescope;

// Record data
Telescope::recordCache($cacheName, $cacheData);

// Tag entries
Telescope::tag(function () {
    // Do something
}, ['tag1', 'tag2']);

// Hide sensitive request data
Telescope::hideRequestParameters(['password', 'secret']);

// Hide sensitive commands
Telescope::hideCommandParameters(['password', 'secret']);
```

### 3.2. Custom Watchers

```php
<?php

declare(strict_types=1);

// In a service provider
protected function gate()
{
    Gate::define('viewTelescope', function ($user) {
        return in_array($user->email, [
            'admin@example.com',
        ]);
    });
}

public function register()
{
    $this->app->singleton(TelescopeServiceProvider::class);

    Telescope::night();

    $this->hideSensitiveRequestDetails();

    Telescope::filter(function (IncomingEntry $entry) {
        if ($this->app->environment('local')) {
            return true;
        }

        return $entry->isReportableException() ||
               $entry->isFailedRequest() ||
               $entry->isFailedJob() ||
               $entry->isScheduledTask() ||
               $entry->hasMonitoredTag();
    });
}
```

## 4. Configuration

Telescope's configuration is available at `config/telescope.php`:

```php
<?php

declare(strict_types=1);

return [
    'domain' => env('TELESCOPE_DOMAIN', null),
    'path' => env('TELESCOPE_PATH', 'telescope'),
    'driver' => env('TELESCOPE_DRIVER', 'database'),
    'storage' => [
        'database' => [
            'connection' => env('TELESCOPE_DB_CONNECTION', 'mysql'),
            'chunk' => 1000,
        ],
    ],
    'enabled' => env('TELESCOPE_ENABLED', true),
    'middleware' => [
        'web',
        TelescopeApplicationServiceProvider::class . '::teleskopeAuthenticate',
    ],
    'only_paths' => [
        // 'api/*'
    ],
    'ignore_paths' => [
        'nova-api*',
    ],
    'ignore_commands' => [
        //
    ],
    'watchers' => [
        Watchers\BatchWatcher::class => env('TELESCOPE_BATCH_WATCHER', true),
        Watchers\CacheWatcher::class => env('TELESCOPE_CACHE_WATCHER', true),
        Watchers\CommandWatcher::class => [
            'enabled' => env('TELESCOPE_COMMAND_WATCHER', true),
            'ignore' => [],
        ],
        // Other watchers...
    ],
];
```

## 5. Best Practices

### 5.1. Securing Telescope

Always secure Telescope in production:

```php
<?php

declare(strict_types=1);

// In TelescopeServiceProvider.php
protected function gate()
{
    Gate::define('viewTelescope', function ($user) {
        return $user->hasRole('admin');
    });
}
```

### 5.2. Pruning Data

Configure pruning to prevent database bloat:

```php
// In your scheduler
$schedule->command('telescope:prune')->daily();

// Or with specific retention
$schedule->command('telescope:prune --hours=48')->daily();
```

### 5.3. Production Usage

For production environments, filter data to reduce overhead:

```php
<?php

declare(strict_types=1);

Telescope::filter(function (IncomingEntry $entry) {
    if ($this->app->environment('local')) {
        return true;
    }

    return $entry->isReportableException() ||
           $entry->isFailedJob() ||
           $entry->hasMonitoredTag();
});
```
