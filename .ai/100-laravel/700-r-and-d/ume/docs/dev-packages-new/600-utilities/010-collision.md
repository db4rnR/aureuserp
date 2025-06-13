# Collision

## 1. Overview

Collision is a package by Nuno Maduro that provides beautiful error reporting for PHP applications. It enhances the console output for errors and exceptions, making it easier to debug issues in your application.

### 1.1. Package Information

- **Package Name**: nunomaduro/collision
- **Version**: ^8.1.0
- **GitHub**: [https://github.com/nunomaduro/collision](https://github.com/nunomaduro/collision)
- **Documentation**: [https://github.com/nunomaduro/collision#readme](https://github.com/nunomaduro/collision#readme)

## 2. Key Features

- Beautiful error reporting in the console
- Detailed stack traces
- Syntax highlighting
- Code snippets around the error
- Integration with Laravel
- Integration with PHPUnit/Pest
- Support for parallel testing
- Interactive debugging
- Support for PHP 8.4
- Customizable output
- Ignition integration

## 3. Installation

Collision is included by default in Laravel applications. If you need to add it manually:

```bash
composer require --dev nunomaduro/collision
```

## 4. Configuration

### 4.1. Laravel Integration

Collision is automatically integrated with Laravel through the `NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider` service provider.

### 4.2. PHPUnit/Pest Integration

For PHPUnit/Pest integration, add the Collision listener to your `phpunit.xml` file:

```xml
<phpunit>
    <!-- ... -->
    <listeners>
        <listener class="NunoMaduro\Collision\Adapters\Phpunit\Listener" />
    </listeners>
</phpunit>
```

### 4.3. Custom Configuration

You can customize Collision by creating a `.collision` file in your project root:

```php
<?php

return [
    // Configures the output
    'output' => [
        'format' => 'normal', // Options: normal, compact
        'verbosity' => 'normal', // Options: normal, verbose, very_verbose, debug
        'ignition' => true, // Whether to integrate with Ignition
    ],
    
    // Configures the editor
    'editor' => 'phpstorm', // Options: phpstorm, vscode, sublime, atom, textmate
    
    // Configures the ignored exceptions
    'ignored_exceptions' => [
        // List of exception classes to ignore
    ],
];
```

## 5. Usage

### 5.1. Basic Usage

Collision works automatically when an error or exception occurs in your application. It will display a detailed error report in the console.

### 5.2. Manual Usage

You can manually use Collision to handle exceptions:

```php
use NunoMaduro\Collision\Provider;

$provider = new Provider();
$provider->register();

try {
    // Your code
} catch (\Throwable $e) {
    $handler = $provider->getHandler();
    $handler->setEditor('phpstorm');
    $handler->setInspector(new Inspector($e));
    $handler->handle();
}
```

### 5.3. PHPUnit/Pest Usage

When running tests with PHPUnit or Pest, Collision will automatically enhance the output:

```bash
php artisan test
```

### 5.4. Artisan Commands

Collision enhances the output of Artisan commands:

```bash
php artisan migrate
php artisan make:controller UserController
```

## 6. Integration with Laravel 12 and PHP 8.4

Collision is fully compatible with Laravel 12 and PHP 8.4. It provides enhanced error reporting for:

- Laravel Artisan commands
- Laravel tests with PHPUnit/Pest
- Laravel queue workers
- Laravel scheduled tasks
- PHP 8.4 features and syntax

## 7. Advanced Usage

### 7.1. Custom Editors

Configure Collision to open files in your preferred editor:

```php
// .collision
return [
    'editor' => 'phpstorm',
];
```

Supported editors:
- `phpstorm`
- `vscode`
- `sublime`
- `atom`
- `textmate`
- `emacs`
- `vim`
- `neovim`

### 7.2. Ignoring Exceptions

Configure Collision to ignore specific exceptions:

```php
// .collision
return [
    'ignored_exceptions' => [
        \Symfony\Component\Console\Exception\CommandNotFoundException::class,
    ],
];
```

### 7.3. Custom Output Format

Configure Collision to use a different output format:

```php
// .collision
return [
    'output' => [
        'format' => 'compact',
    ],
];
```

## 8. Best Practices

1. **Keep Updated**: Regularly update Collision to get the latest features and bug fixes
2. **Configure Editor**: Set up your preferred editor for better integration
3. **Use with Ignition**: Use Collision alongside Ignition for comprehensive error reporting
4. **Customize for CI**: Configure a more compact output format for CI environments
5. **Ignore Noise**: Configure ignored exceptions to reduce noise in your error reports

## 9. Troubleshooting

### 9.1. Conflicts with Other Packages

If Collision conflicts with other error reporting packages:

1. Ensure you're using compatible versions
2. Check for duplicate error handlers
3. Configure one package as the primary error handler

### 9.2. Performance Issues

If you experience performance issues:

1. Use a more compact output format
2. Configure ignored exceptions for noisy errors
3. Disable Ignition integration if not needed

### 9.3. Missing Stack Traces

If stack traces are missing or incomplete:

1. Ensure you have appropriate error reporting settings in PHP
2. Check if exceptions are being caught and rethrown without preserving the stack trace
3. Verify that you're not suppressing errors with the `@` operator
