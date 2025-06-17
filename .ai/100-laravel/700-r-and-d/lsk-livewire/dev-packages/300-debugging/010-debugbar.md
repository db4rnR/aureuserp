# Laravel Debugbar

## 1. Overview

Laravel Debugbar is a package that integrates PHP Debug Bar with Laravel. It provides a powerful debugging and profiling tool that displays information about requests, queries, views, and more in a toolbar at the bottom of the page.

### 1.1. Package Information

- **Package Name**: barryvdh/laravel-debugbar
- **Version**: ^3.15.2
- **GitHub**: [https://github.com/barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar)
- **Documentation**: [https://github.com/barryvdh/laravel-debugbar/blob/master/readme.md](https://github.com/barryvdh/laravel-debugbar/blob/master/readme.md)

## 2. Key Features

- Request and response information
- Database query logging with syntax highlighting
- View rendering time and data
- Route information
- Session and request data
- Exception display
- Timeline of application events
- Memory usage tracking
- Event logging
- Cache operations tracking
- Laravel log entries
- Custom messages and timers

## 3. Installation

```bash
composer require --dev barryvdh/laravel-debugbar
```

### 3.1. Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

This creates a `config/debugbar.php` file.

## 4. Configuration

### 4.1. Basic Configuration

The main configuration options in `config/debugbar.php`:

```php
<?php

return [
    // Enable or disable Debugbar
    'enabled' => env('DEBUGBAR_ENABLED', null),
    
    // Storage settings
    'storage' => [
        'enabled'    => true,
        'driver'     => 'file',
        'path'       => storage_path('debugbar'),
        'connection' => null,
        'provider'   => '',
        'hostname'   => '127.0.0.1',
        'port'       => 2304,
    ],
    
    // Which collectors to enable
    'collectors' => [
        'phpinfo'         => true,
        'messages'        => true,
        'time'            => true,
        'memory'          => true,
        'exceptions'      => true,
        'log'             => true,
        'db'              => true,
        'views'           => true,
        'route'           => true,
        'auth'            => false,
        'gate'            => true,
        'session'         => true,
        'symfony_request' => true,
        'mail'            => true,
        'laravel'         => false,
        'events'          => false,
        'default_request' => false,
        'logs'            => false,
        'files'           => false,
        'config'          => false,
        'cache'           => false,
        'models'          => true,
        'livewire'        => true,
    ],
];
```

### 4.2. Environment Configuration

In your `.env` file:

```
# Enable or disable Debugbar
DEBUGBAR_ENABLED=true

# Enable only in development
APP_DEBUG=true
```

### 4.3. Query Logging

Configure database query logging:

```php
'options' => [
    'db' => [
        'with_params'       => true,
        'backtrace'         => true,
        'backtrace_exclude_paths' => [],
        'timeline'          => false,
        'duration_background' => true,
        'explain' => [
            'enabled' => false,
            'types' => ['SELECT'],
        ],
        'hints'             => false,
        'show_copy'         => false,
    ],
],
```

## 5. Usage

### 5.1. Basic Usage

Once installed and enabled, Debugbar will automatically appear at the bottom of your pages in development.

### 5.2. Collecting Data

Add custom messages to Debugbar:

```php
// Add a message
Debugbar::info('Info message');
Debugbar::error('Error message');
Debugbar::warning('Warning message');
Debugbar::addMessage('Another message', 'mylabel');

// Start/stop timing
Debugbar::startMeasure('render', 'Rendering time');
// Some code...
Debugbar::stopMeasure('render');

// Add data to a collector
Debugbar::addCollector(new DebugBar\DataCollector\ConfigCollector());
```

### 5.3. Disabling in Production

Ensure Debugbar is disabled in production:

```php
// config/debugbar.php
'enabled' => env('DEBUGBAR_ENABLED', null),

// .env.production
DEBUGBAR_ENABLED=false
```

### 5.4. JavaScript Integration

Debugbar includes JavaScript integration:

```javascript
// Access Debugbar from JavaScript
console.log(Debugbar.getData());

// Add messages from JavaScript
Debugbar.info('Info from JavaScript');
```

## 6. Integration with Laravel 12 and PHP 8.4

Laravel Debugbar is fully compatible with Laravel 12 and PHP 8.4. It includes support for:

- Livewire components
- Volt components
- Laravel's new Folio pages
- PHP 8.4 features

## 7. Advanced Usage

### 7.1. Custom Collectors

Create custom data collectors:

```php
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class CustomCollector extends DataCollector implements Renderable
{
    public function collect()
    {
        return [
            'data' => 'Custom data',
        ];
    }

    public function getName()
    {
        return 'custom';
    }

    public function getWidgets()
    {
        return [
            'custom' => [
                'icon' => 'gear',
                'widget' => 'PhpDebugBar.Widgets.VariableListWidget',
                'map' => 'custom',
                'default' => '{}',
            ],
        ];
    }
}

// Register the collector
Debugbar::addCollector(new CustomCollector());
```

### 7.2. Command Line Usage

For command line usage:

```php
// In your command
$this->info(Debugbar::getCollector('time')->getData());
```

### 7.3. API Responses

For API responses:

```php
// config/debugbar.php
'capture_ajax' => true,
```

## 8. Performance Considerations

### 8.1. Performance Impact

Debugbar can impact performance, especially with heavy query logging. Consider:

1. Disabling in production
2. Disabling collectors you don't need
3. Using selective query logging
4. Monitoring memory usage

### 8.2. Storage Cleanup

Debugbar stores data in `storage/debugbar`. Clean it regularly:

```php
// Artisan command
php artisan debugbar:clear
```

## 9. Best Practices

1. **Development Only**: Only enable in development environments
2. **Selective Collectors**: Only enable collectors you need
3. **Clear Storage**: Regularly clear debugbar storage
4. **Custom Messages**: Use custom messages for important events
5. **Timeline Measurements**: Use timeline for performance bottlenecks
6. **Query Analysis**: Review database queries for optimization opportunities
