# Nunomaduro Collision

## 1. Overview

Collision is a beautiful error reporting tool for command-line PHP applications created by Nuno Maduro. It's specifically designed to improve the experience of working with CLI applications, including Laravel's Artisan commands.

### 1.1. Package Information

- **Package Name**: nunomaduro/collision
- **Version**: ^8.6
- **GitHub**: [https://github.com/nunomaduro/collision](https://github.com/nunomaduro/collision)
- **Documentation**: [https://github.com/nunomaduro/collision#readme](https://github.com/nunomaduro/collision#readme)

## 2. Key Features

- Beautiful error reporting for CLI applications
- Detailed stack traces with syntax highlighting
- Support for Laravel and Laravel Zero
- Integration with PHPUnit and Pest
- Interactive debugging capabilities
- Clear visualization of exceptions

## 3. Usage Examples

### 3.1. Automatic Integration

Collision is automatically integrated with Laravel, so there's no need for manual configuration to get the basic functionality.

### 3.2. Custom Error Handler

```php
<?php

declare(strict_types=1);

use NunoMaduro\Collision\Provider;

// Register the error handler
(new Provider)->register();

// Now any exceptions will be displayed using Collision
throw new Exception('Something went wrong!');
```

### 3.3. With Artisan Commands

```php
<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExampleCommand extends Command
{
    protected $signature = 'example';
    
    public function handle()
    {
        // Any exceptions here will be displayed using Collision
        throw new \Exception('Command failed!');
    }
}
```

## 4. Configuration

Collision is configured through the Laravel framework's exception handling system:

```php
<?php

declare(strict_types=1);

// In app/Exceptions/Handler.php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    // Collision is automatically integrated here
    
    // You can customize how exceptions are reported
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Custom exception reporting
        });
    }
}
```

## 5. Best Practices

### 5.1. Using with Tests

Collision provides better error reporting in tests:

```php
<?php

declare(strict_types=1);

// Pest example with Collision providing error reporting
test('example', function () {
    expect(true)->toBeFalse(); // Collision will provide clear error output
});
```

### 5.2. Error Logging

Combine Collision with proper logging:

```php
<?php

declare(strict_types=1);

// In a command
try {
    // Some dangerous operation
} catch (Exception $e) {
    $this->error($e->getMessage());
    logger()->error('Command failed', [
        'exception' => $e,
        'context' => $this->argument('key'),
    ]);
    
    return Command::FAILURE;
}
```
