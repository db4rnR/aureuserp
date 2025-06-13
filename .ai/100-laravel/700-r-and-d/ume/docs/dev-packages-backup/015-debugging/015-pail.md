# Laravel Pail

## 1. Overview

Laravel Pail is a log viewer for Laravel that provides a real-time, CLI-based log viewer with powerful filtering and search capabilities.

### 1.1. Package Information

- **Package Name**: laravel/pail
- **Version**: ^1.2.2
- **GitHub**: [https://github.com/laravel/pail](https://github.com/laravel/pail)
- **Documentation**: [https://laravel.com/docs/10.x/pail](https://laravel.com/docs/10.x/pail)

## 2. Key Features

- Real-time log viewing in the terminal
- Powerful filtering and search capabilities
- Multiple log viewing modes
- Custom highlighting
- Artisan command interface
- Support for multiple log channels

## 3. Usage Examples

### 3.1. Basic Usage

```sh
## Start the log viewer
php artisan pail

## Start with filtering
php artisan pail --filter="error"

## Filter by specific log level
php artisan pail --level=error

## Multiple filters
php artisan pail --filter="user" --level=info
```

### 3.2. Interactive Options

While running Pail, you can use the following keys:

- `f` - Toggle filter input
- `l` - Toggle log level filter
- `c` - Clear screen
- `p` - Pause/resume log streaming
- `q` - Quit Pail

### 3.3. Custom Format

```sh
## Use custom format
php artisan pail --format="[{level}] {message}"

## Show full context
php artisan pail --show-context
```

## 4. Configuration

Pail can be configured in your application's config:

```php
<?php

declare(strict_types=1);

// In config/logging.php
'channels' => [
    'pail' => [
        'driver' => 'pail',
        'level' => env('LOG_LEVEL', 'debug'),
    ],
    
    // Or add to stack
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'pail'],
        'ignore_exceptions' => false,
    ],
],
```

## 5. Best Practices

### 5.1. Using with Dev Script

Integrate Pail with your development workflow:

* In composer.json
```json
"scripts": {
    "dev": [
        "Composer\\Config::disableProcessTimeout",
        "pnpm dlx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"php artisan pail --timeout=0\" \"pnpm run dev\" --names=server,queue,logs,vite"
    ]
}
```

### 5.2. Custom Styling

Customize log entry styling for better visibility:

```php
<?php

declare(strict_types=1);

// In AppServiceProvider.php
public function boot()
{
    if ($this->app->environment('local')) {
        \Laravel\Pail\Facades\Pail::styleUsing(function ($entry) {
            return match ($entry->level) {
                'error' => 'red bold',
                'warning' => 'yellow bold',
                'info' => 'blue',
                'debug' => 'gray',
                default => '',
            };
        });
    }
}
```
