# Laravel Web Tinker

## 1. Overview

Laravel Web Tinker is a package by Spatie that provides a web interface for Laravel's Tinker, allowing you to execute PHP code in your browser within the context of your Laravel application.

### 1.1. Package Information

- **Package Name**: spatie/laravel-web-tinker
- **Version**: ^1.10.1
- **GitHub**: [https://github.com/spatie/laravel-web-tinker](https://github.com/spatie/laravel-web-tinker)
- **Documentation**: [https://github.com/spatie/laravel-web-tinker#readme](https://github.com/spatie/laravel-web-tinker#readme)

## 2. Key Features

- Web interface for Laravel Tinker
- Syntax highlighting
- Command history
- Secure access control
- Easy to use interface
- Ideal for quick testing and debugging

## 3. Usage Examples

### 3.1. Accessing Web Tinker

Once configured, Web Tinker is accessible at `/tinker` in your application.

### 3.2. Example Commands

In the Web Tinker interface, you can run commands such as:

```php
// Query the database
User::count();

// Create a new model
$user = new User(['name' => 'Test', 'email' => 'test@example.com']);
$user->save();

// Test a service
app(NewsletterService::class)->subscribe('test@example.com');

// Inspect configuration
config('app.name');
```

## 4. Configuration

The Web Tinker configuration is published at `config/web-tinker.php`:

```php
<?php

declare(strict_types=1);

return [
    /*
     * The path where the web interface will be accessible from.
     */
    'path' => 'tinker',
    
    /*
     * The middleware that will be applied to the web interface.
     */
    'middleware' => [
        'web',
        \Spatie\WebTinker\Http\Middleware\Authorize::class,
    ],
    
    /*
     * Possible values are 'auto', 'light' and 'dark'.
     */
    'theme' => 'auto',
    
    /*
     * By default this package will only run in local development.
     * Do not change this unless you know what you are doing.
     */
    'enabled' => env('APP_ENV') === 'local',
    
    /*
     * This class determines who can access the web tinker page.
     * By default only the developer who created the app can access the page.
     */
    'gate' => \Spatie\WebTinker\Http\Middleware\AuthorizeDefaultGate::class,
    
    /*
     * If you want to fine-tune PsySH configuration specify
     * configuration file name, relative to the root of your
     * application directory.
     */
    'config_file' => env('PSYSH_CONFIG', null),
];
```

## 5. Best Practices

### 5.1. Custom Access Control

Configure custom access control for Web Tinker:

```php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Gate;

class AuthorizeWebTinker
{
    public function handle($request, $next)
    {
        Gate::define('viewWebTinker', function ($user = null) {
            return $user && $user->hasRole('developer');
        });
        
        return $next($request);
    }
}
```

### 5.2. Security Considerations

Ensure Web Tinker is only accessible in safe environments:

```php
<?php

declare(strict_types=1);

// In config/web-tinker.php
'enabled' => env('APP_ENV') === 'local',

// In .env.production
APP_ENV=production  // This automatically disables Web Tinker
```

### 5.3. Custom Styling

Customize the Web Tinker interface:

```php
<?php

declare(strict_types=1);

// In a service provider
public function boot()
{
    // Publish and customize the web-tinker.css file
    $this->publishes([
        __DIR__.'/../resources/css/web-tinker.css' => public_path('vendor/web-tinker/web-tinker.css'),
    ], 'web-tinker-assets');
}
```
