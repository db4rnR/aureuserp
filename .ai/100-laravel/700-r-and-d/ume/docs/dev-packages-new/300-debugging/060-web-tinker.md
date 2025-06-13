# Laravel Web Tinker

## 1. Overview

Laravel Web Tinker is a package by Spatie that provides a web interface for Laravel Tinker. It allows you to run PHP code in your Laravel application through a browser, making it easier to debug and experiment with your code.

### 1.1. Package Information

- **Package Name**: spatie/laravel-web-tinker
- **Version**: ^1.10.1
- **GitHub**: [https://github.com/spatie/laravel-web-tinker](https://github.com/spatie/laravel-web-tinker)
- **Documentation**: [https://github.com/spatie/laravel-web-tinker#readme](https://github.com/spatie/laravel-web-tinker#readme)

## 2. Key Features

- Web interface for Laravel Tinker
- Syntax highlighting
- Command history
- Autocomplete
- Secure access control
- Customizable interface
- Easy installation
- Low overhead
- Support for Laravel 12 and PHP 8.4

## 3. Installation

```bash
composer require --dev spatie/laravel-web-tinker
```

### 3.1. Configuration

Publish the configuration file and assets:

```bash
php artisan vendor:publish --provider="Spatie\WebTinker\WebTinkerServiceProvider" --tag="config"
php artisan vendor:publish --provider="Spatie\WebTinker\WebTinkerServiceProvider" --tag="assets"
```

This creates a `config/web-tinker.php` file and publishes the necessary assets to your `public` directory.

## 4. Configuration

### 4.1. Basic Configuration

The main configuration options in `config/web-tinker.php`:

```php
<?php

return [
    // The path where Web Tinker will be accessible from
    'path' => 'tinker',
    
    // Middleware to apply to the Web Tinker routes
    'middleware' => [
        'web',
        Spatie\WebTinker\Http\Middleware\Authorize::class,
    ],
    
    // Allowed IP addresses
    'allowed_ips' => [
        '127.0.0.1',
        '::1',
    ],
    
    // Automatically qualify class names
    'auto_qualify_classes' => true,
    
    // Automatically qualify object properties
    'auto_qualify_objects' => true,
];
```

### 4.2. Access Control

Configure who can access Web Tinker:

```php
// config/web-tinker.php
'allowed_ips' => [
    '127.0.0.1',
    '::1',
    '192.168.1.10',
],
```

Or use custom middleware:

```php
// config/web-tinker.php
'middleware' => [
    'web',
    'auth',
    'can:use-web-tinker',
],
```

## 5. Usage

### 5.1. Accessing Web Tinker

Once installed, you can access Web Tinker at:

```
https://your-app.test/tinker
```

### 5.2. Running Code

In the Web Tinker interface, you can run PHP code just like you would in the Laravel Tinker console:

```php
// Query the database
User::find(1);

// Create a new model
$user = new User();
$user->name = 'John Doe';
$user->email = 'john@example.com';
$user->password = Hash::make('password');
$user->save();

// Use Laravel facades
Cache::get('key');

// Test a service
app(UserService::class)->doSomething();
```

### 5.3. Command History

Web Tinker keeps a history of your commands. Use the up and down arrow keys to navigate through your command history.

### 5.4. Autocomplete

Web Tinker provides autocomplete suggestions as you type. Press Tab to accept a suggestion.

## 6. Integration with Laravel 12 and PHP 8.4

Laravel Web Tinker is fully compatible with Laravel 12 and PHP 8.4. It supports all Laravel features and PHP 8.4 syntax.

## 7. Security Considerations

### 7.1. Production Usage

Web Tinker should generally not be used in production environments, as it allows executing arbitrary PHP code. If you must use it in production:

1. Restrict access to specific IP addresses:
   ```php
   'allowed_ips' => [
       '123.456.789.012', // Your office IP
   ],
   ```

2. Use authentication and authorization:
   ```php
   'middleware' => [
       'web',
       'auth',
       'can:use-web-tinker',
   ],
   ```

3. Consider disabling it entirely in production:
   ```php
   // AppServiceProvider.php
   public function register()
   {
       if (app()->environment('production')) {
           $this->app->bind(
               \Spatie\WebTinker\Http\Controllers\WebTinkerController::class,
               function () {
                   abort(404);
               }
           );
       }
   }
   ```

### 7.2. Code Execution Risks

Be aware that Web Tinker executes PHP code directly in your application. This means:

1. You can modify database records
2. You can trigger emails and notifications
3. You can interact with external services
4. You can potentially cause data loss or corruption

Always be careful with the code you execute in Web Tinker.

## 8. Advanced Usage

### 8.1. Custom Path

Change the path where Web Tinker is accessible:

```php
// config/web-tinker.php
'path' => 'admin/tinker',
```

### 8.2. Custom Middleware

Add custom middleware for additional functionality:

```php
// config/web-tinker.php
'middleware' => [
    'web',
    'auth',
    'can:use-web-tinker',
    App\Http\Middleware\LogWebTinkerUsage::class,
],
```

### 8.3. Custom Styling

Web Tinker's interface can be customized by publishing and modifying its assets:

```bash
php artisan vendor:publish --provider="Spatie\WebTinker\WebTinkerServiceProvider" --tag="assets"
```

This publishes the CSS and JavaScript files to your `public/vendor/web-tinker` directory.

## 9. Best Practices

1. **Development Only**: Only use Web Tinker in development environments
2. **Secure Access**: Restrict access to authorized users
3. **Be Careful**: Be cautious with the code you execute
4. **Use for Debugging**: Use Web Tinker for debugging and experimentation, not for production tasks
5. **Document Usage**: Document common Web Tinker commands for your team

## 10. Alternatives

If Web Tinker doesn't meet your needs, consider these alternatives:

1. **Laravel Tinker**: The command-line version of Tinker
2. **Laravel Telescope**: For more comprehensive debugging
3. **Laravel Debugbar**: For in-browser debugging
4. **Laravel Ray**: For detailed debugging with a desktop app
