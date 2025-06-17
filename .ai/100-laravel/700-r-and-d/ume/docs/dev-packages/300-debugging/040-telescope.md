# Laravel Telescope

## 1. Overview

Laravel Telescope is an elegant debug assistant for the Laravel framework. It provides insight into the requests coming into your application, exceptions, log entries, database queries, queued jobs, mail, notifications, cache operations, scheduled tasks, variable dumps, and more.

### 1.1. Package Information

- **Package Name**: laravel/telescope
- **Version**: ^5.7.0
- **GitHub**: [https://github.com/laravel/telescope](https://github.com/laravel/telescope)
- **Documentation**: [https://laravel.com/docs/10.x/telescope](https://laravel.com/docs/10.x/telescope)

## 2. Key Features

- Request monitoring
- Exception tracking
- Log entry viewing
- Database query logging
- Queue job monitoring
- Mail tracking
- Notification monitoring
- Cache operation tracking
- Scheduled task monitoring
- Variable dump viewing
- Model operation tracking
- Event monitoring
- Gate and policy checks
- Redis command monitoring
- Beautiful UI
- Customizable watchers
- Data pruning

## 3. Installation

```bash
composer require laravel/telescope
```

### 3.1. Configuration

Publish the configuration and assets:

```bash
php artisan telescope:install
php artisan migrate
```

This will:
- Create a `config/telescope.php` configuration file
- Create a migration for the Telescope database tables
- Publish Telescope's assets to your `public` directory

## 4. Configuration

### 4.1. Basic Configuration

The main configuration options in `config/telescope.php`:

```php
<?php

return [
    // Path where Telescope will be accessible from
    'path' => env('TELESCOPE_PATH', 'telescope'),
    
    // Enable or disable Telescope
    'enabled' => env('TELESCOPE_ENABLED', true),
    
    // Telescope storage driver
    'driver' => env('TELESCOPE_DRIVER', 'database'),
    
    // Storage settings
    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'chunk' => 1000,
        ],
    ],
    
    // Data pruning settings
    'prune' => [
        'hours' => env('TELESCOPE_PRUNE_HOURS', 24),
        'batch_size' => 1000,
    ],
    
    // Which watchers to enable
    'watchers' => [
        Watchers\CacheWatcher::class => env('TELESCOPE_CACHE_WATCHER', true),
        Watchers\CommandWatcher::class => env('TELESCOPE_COMMAND_WATCHER', true),
        Watchers\DumpWatcher::class => env('TELESCOPE_DUMP_WATCHER', true),
        Watchers\EventWatcher::class => env('TELESCOPE_EVENT_WATCHER', true),
        Watchers\ExceptionWatcher::class => env('TELESCOPE_EXCEPTION_WATCHER', true),
        Watchers\JobWatcher::class => env('TELESCOPE_JOB_WATCHER', true),
        Watchers\LogWatcher::class => env('TELESCOPE_LOG_WATCHER', true),
        Watchers\MailWatcher::class => env('TELESCOPE_MAIL_WATCHER', true),
        Watchers\ModelWatcher::class => env('TELESCOPE_MODEL_WATCHER', true),
        Watchers\NotificationWatcher::class => env('TELESCOPE_NOTIFICATION_WATCHER', true),
        Watchers\QueryWatcher::class => env('TELESCOPE_QUERY_WATCHER', true),
        Watchers\RedisWatcher::class => env('TELESCOPE_REDIS_WATCHER', true),
        Watchers\RequestWatcher::class => env('TELESCOPE_REQUEST_WATCHER', true),
        Watchers\ScheduleWatcher::class => env('TELESCOPE_SCHEDULE_WATCHER', true),
        Watchers\ViewWatcher::class => env('TELESCOPE_VIEW_WATCHER', true),
        Watchers\GateWatcher::class => env('TELESCOPE_GATE_WATCHER', true),
    ],
];
```

### 4.2. Environment Configuration

In your `.env` file:

```
# Enable or disable Telescope
TELESCOPE_ENABLED=true

# Path where Telescope will be accessible from
TELESCOPE_PATH=telescope

# Data pruning settings
TELESCOPE_PRUNE_HOURS=24

# Enable or disable specific watchers
TELESCOPE_CACHE_WATCHER=true
TELESCOPE_COMMAND_WATCHER=true
TELESCOPE_DUMP_WATCHER=true
TELESCOPE_EVENT_WATCHER=true
TELESCOPE_EXCEPTION_WATCHER=true
TELESCOPE_JOB_WATCHER=true
TELESCOPE_LOG_WATCHER=true
TELESCOPE_MAIL_WATCHER=true
TELESCOPE_MODEL_WATCHER=true
TELESCOPE_NOTIFICATION_WATCHER=true
TELESCOPE_QUERY_WATCHER=true
TELESCOPE_REDIS_WATCHER=true
TELESCOPE_REQUEST_WATCHER=true
TELESCOPE_SCHEDULE_WATCHER=true
TELESCOPE_VIEW_WATCHER=true
TELESCOPE_GATE_WATCHER=true
```

### 4.3. Authorization

Configure who can access Telescope in the `TelescopeServiceProvider`:

```php
/**
 * Register the Telescope gate.
 *
 * This gate determines who can access Telescope in non-local environments.
 *
 * @return void
 */
protected function gate()
{
    Gate::define('viewTelescope', function ($user) {
        return in_array($user->email, [
            'admin@example.com',
        ]);
    });
}
```

## 5. Usage

### 5.1. Accessing Telescope

Once installed, you can access Telescope at:

```
https://your-app.test/telescope
```

### 5.2. Viewing Requests

The Requests tab shows all HTTP requests to your application, including:
- Method and URI
- Status code
- Duration
- IP address
- Headers
- Session data
- Response

### 5.3. Viewing Exceptions

The Exceptions tab shows all exceptions thrown by your application, including:
- Exception class
- Message
- Stack trace
- Context

### 5.4. Viewing Database Queries

The Queries tab shows all database queries executed by your application, including:
- SQL query
- Duration
- Connection
- Location

### 5.5. Viewing Queue Jobs

The Jobs tab shows all queue jobs processed by your application, including:
- Job class
- Queue
- Status
- Duration

### 5.6. Viewing Logs

The Logs tab shows all log entries from your application, including:
- Level
- Message
- Context

### 5.7. Using the Dump Watcher

Use the `dump()` function to send variables to Telescope:

```php
dump($user);
```

These dumps will appear in the Dumps tab.

## 6. Integration with Laravel 12 and PHP 8.4

Laravel Telescope is fully compatible with Laravel 12 and PHP 8.4. It includes support for:

- Livewire components
- Volt components
- Laravel's new Folio pages
- PHP 8.4 features

## 7. Advanced Usage

### 7.1. Custom Watchers

Create custom watchers:

```php
<?php

namespace App\Watchers;

use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\Watchers\Watcher;

class CustomWatcher extends Watcher
{
    /**
     * Register the watcher.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function register($app)
    {
        // Register your watcher logic here
        
        // Example: Record a custom entry
        Telescope::recordCustom([
            'data' => 'Custom data',
        ]);
    }
}
```

Register the custom watcher in `config/telescope.php`:

```php
'watchers' => [
    // Other watchers...
    App\Watchers\CustomWatcher::class => env('TELESCOPE_CUSTOM_WATCHER', true),
],
```

### 7.2. Filtering Data

Filter the data Telescope collects:

```php
use Laravel\Telescope\Telescope;

Telescope::filter(function (IncomingEntry $entry) {
    if ($entry->type === 'request') {
        return $entry->content['method'] === 'POST';
    }

    return true;
});
```

### 7.3. Tagging Entries

Tag Telescope entries for easier filtering:

```php
use Laravel\Telescope\Telescope;

Telescope::tag(function (IncomingEntry $entry) {
    if ($entry->type === 'request') {
        return ['api', 'status:'.$entry->content['response_status']];
    }
    
    return [];
});
```

## 8. Performance Considerations

### 8.1. Production Usage

Telescope can impact performance in production. Consider:

1. Disabling in production:
   ```php
   'enabled' => env('TELESCOPE_ENABLED', env('APP_ENV') === 'local'),
   ```

2. Enabling only in production for specific users:
   ```php
   Telescope::filter(function (IncomingEntry $entry) {
       if (app()->environment('production')) {
           return auth()->check() && auth()->user()->isAdmin();
       }

       return true;
   });
   ```

### 8.2. Data Pruning

Configure data pruning to prevent database bloat:

```php
'prune' => [
    'hours' => 24,
    'batch_size' => 1000,
],
```

Run the pruning command:

```bash
php artisan telescope:prune
```

Schedule the pruning command:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('telescope:prune')->daily();
}
```

## 9. Best Practices

1. **Development Only**: Only enable in development by default
2. **Selective Watchers**: Only enable watchers you need
3. **Regular Pruning**: Schedule regular pruning
4. **Secure Access**: Restrict access to authorized users
5. **Combine with Other Tools**: Use alongside Laravel Debugbar and Ray
