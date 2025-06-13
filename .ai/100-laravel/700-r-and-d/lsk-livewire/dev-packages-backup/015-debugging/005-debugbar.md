# Laravel Debugbar

## 1. Overview

Laravel Debugbar is a package that integrates PHP Debug Bar with Laravel, providing a powerful way to explore and debug your application while developing.

### 1.1. Package Information

- **Package Name**: barryvdh/laravel-debugbar
- **Version**: ^3.15.2
- **GitHub**: [https://github.com/barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar)
- **Documentation**: [https://github.com/barryvdh/laravel-debugbar/blob/master/readme.md](https://github.com/barryvdh/laravel-debugbar/blob/master/readme.md)

## 2. Key Features

- Request and response monitoring
- Database query logging with execution time
- View data and variables
- Route information
- Session and request data
- Exception handling
- Timeline visualization

## 3. Usage Examples

### 3.1. Basic Debugging

```php
<?php

declare(strict_types=1);

// Add messages to the debug bar
Debugbar::info('Info message');
Debugbar::error('Error message');
Debugbar::warning('Warning message');
Debugbar::addMessage('Another message', 'custom_label');

// Measure time
Debugbar::startMeasure('render', 'Rendering view');
// Some code here...
Debugbar::stopMeasure('render');

// Add data
Debugbar::addMeasure('Memory usage', 0, memory_get_usage(true));
Debugbar::addMeasure('Bootstrap', LARAVEL_START, microtime(true));
```

### 3.2. Timeline Measurement

```php
<?php

declare(strict_types=1);

Debugbar::startMeasure('long_operation', 'Long operation');
sleep(2);
Debugbar::stopMeasure('long_operation');
```

## 4. Configuration

The Debugbar config is published at `config/debugbar.php`:

```php
<?php

declare(strict_types=1);

return [
    'enabled' => env('DEBUGBAR_ENABLED', env('APP_DEBUG', false)),
    'storage' => [
        'enabled'    => true,
        'driver'     => 'file',
        'path'       => storage_path('debugbar'),
        'connection' => null,
        'provider'   => '',
        'hostname'   => '127.0.0.1',
        'port'       => 2304,
    ],
    'include_vendors' => true,
    'capture_ajax' => true,
    'add_ajax_timing' => true,
    'error_handler' => false,
    'clockwork' => false,
    
    // Other options...
];
```

## 5. Enable/Disable at Runtime

You can enable or disable the debugbar at runtime:

```php
<?php

declare(strict_types=1);

// To disable
\Debugbar::disable();

// To enable
\Debugbar::enable();

// Check if enabled
if (\Debugbar::isEnabled()) {
    // Debugbar is enabled
}
```

## 6. Using in Production

Debugbar should be disabled in production for security and performance reasons.

In `.env.production`:

```env
DEBUGBAR_ENABLED=false
```

In the service provider, you can add additional restrictions:

```php
<?php

declare(strict_types=1);

if ($app->environment('production')) {
    \Debugbar::disable();
}
```
