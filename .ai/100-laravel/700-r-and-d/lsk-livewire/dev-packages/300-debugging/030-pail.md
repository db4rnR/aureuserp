# Laravel Pail

## 1. Overview

Laravel Pail is a real-time log viewer for Laravel applications. It provides a beautiful, interactive terminal interface for viewing and filtering your Laravel logs in real-time, making debugging much easier.

### 1.1. Package Information

- **Package Name**: laravel/pail
- **Version**: ^1.2.2
- **GitHub**: [https://github.com/laravel/pail](https://github.com/laravel/pail)
- **Documentation**: [https://laravel.com/docs/10.x/pail](https://laravel.com/docs/10.x/pail)

## 2. Key Features

- Real-time log viewing
- Interactive terminal interface
- Log filtering by level, channel, and message
- Syntax highlighting
- Search functionality
- Automatic scrolling
- Customizable display options
- Support for multiple log channels
- Integration with Laravel's logging system
- Low overhead

## 3. Installation

```bash
composer require --dev laravel/pail
```

## 4. Usage

### 4.1. Basic Usage

Start the Pail log viewer:

```bash
php artisan pail
```

This will open an interactive terminal interface showing your Laravel logs in real-time.

### 4.2. Filtering Logs

Filter logs by level:

```bash
php artisan pail --level=error
```

Available levels: `debug`, `info`, `notice`, `warning`, `error`, `critical`, `alert`, `emergency`

### 4.3. Filtering by Channel

Filter logs by channel:

```bash
php artisan pail --channel=stack
```

### 4.4. Filtering by Message

Filter logs containing specific text:

```bash
php artisan pail --filter="User authentication"
```

### 4.5. Multiple Filters

Combine multiple filters:

```bash
php artisan pail --level=error --channel=stack --filter="Database"
```

### 4.6. Disabling Auto-Scrolling

Disable automatic scrolling:

```bash
php artisan pail --no-scroll
```

### 4.7. Setting a Timeout

Set a timeout for the Pail session:

```bash
php artisan pail --timeout=60
```

Set to 0 for no timeout:

```bash
php artisan pail --timeout=0
```

## 5. Integration with Laravel 12 and PHP 8.4

Laravel Pail is fully compatible with Laravel 12 and PHP 8.4. It works seamlessly with Laravel's logging system and supports all log channels and log levels.

## 6. Composer Scripts

Add a script to your `composer.json` for easy access:

```json
"scripts": {
    "logs": [
        "@php artisan pail --timeout=0"
    ]
}
```

Usage:

```bash
composer logs
```

## 7. Development Workflow

### 7.1. Local Development

In our project, we use Pail as part of our local development workflow:

```bash
# Start the development server with Pail
composer dev
```

This runs Pail alongside the development server, queue worker, and Vite:

```json
"dev": [
    "Composer\\Config::disableProcessTimeout",
    "pnpm dlx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"php artisan pail --timeout=0\" \"pnpm run dev\" --names=server,queue,logs,vite"
],
```

### 7.2. Debugging Workflow

A typical debugging workflow with Pail:

1. Start Pail in one terminal window
2. Run your application in another window
3. Watch the logs in real-time as you interact with your application
4. Filter logs to focus on specific issues
5. Search for specific error messages

## 8. Advanced Usage

### 8.1. Custom Log Channels

Pail works with custom log channels defined in your `config/logging.php`:

```php
'channels' => [
    'custom' => [
        'driver' => 'single',
        'path' => storage_path('logs/custom.log'),
        'level' => 'debug',
    ],
],
```

View logs from a specific channel:

```bash
php artisan pail --channel=custom
```

### 8.2. Integration with Other Tools

Pail works well alongside other debugging tools:

- Use Pail for real-time log viewing
- Use Laravel Debugbar for in-browser debugging
- Use Laravel Telescope for request and event monitoring
- Use Laravel Ray for detailed debugging

## 9. Best Practices

1. **Use in Development**: Pail is primarily a development tool
2. **Filter Effectively**: Use filters to focus on relevant logs
3. **Combine with Proper Logging**: Ensure your application logs appropriate information
4. **Use with Queue Monitoring**: Run Pail alongside queue workers to debug queue issues
5. **Keep Terminal Open**: Keep Pail running in a separate terminal during development

## 10. Troubleshooting

### 10.1. No Logs Appearing

If no logs are appearing in Pail:

1. Check that your application is actually logging messages
2. Verify that you're using the correct log channel
3. Ensure your log level is appropriate (e.g., debug logs won't appear if your log level is set to error)

### 10.2. Performance Considerations

Pail has minimal performance impact, but consider:

1. Using filters to reduce the number of displayed logs
2. Setting appropriate log levels in production
3. Only running Pail in development environments
