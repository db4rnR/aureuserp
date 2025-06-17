# Symfony Var Dumper

## 1. Overview

Symfony Var Dumper is a component that provides mechanisms for walking through any arbitrary PHP variable. It provides a better `dump()` function that you can use instead of `var_dump()` to display structured information about variables in a more readable way.

### 1.1. Package Information

- **Package Name**: symfony/var-dumper
- **Version**: ^7.0.3
- **GitHub**: [https://github.com/symfony/var-dumper](https://github.com/symfony/var-dumper)
- **Documentation**: [https://symfony.com/doc/current/components/var_dumper.html](https://symfony.com/doc/current/components/var_dumper.html)

## 2. Key Features

- Enhanced variable dumping
- Syntax highlighting
- Collapsible structures
- Clickable references
- Support for objects and resources
- Customizable output
- CLI and HTML output formats
- Integration with Laravel
- Support for PHP 8.4
- Dump server for centralized debugging
- Cloner for deep object inspection

## 3. Installation

Symfony Var Dumper is included by default in Laravel applications. If you need to add it manually:

```bash
composer require --dev symfony/var-dumper
```

## 4. Basic Usage

### 4.1. Dumping Variables

```php
// Dump a variable
dump($var);

// Dump multiple variables
dump($var1, $var2, $var3);

// Dump and die
dd($var);

// Dump and die with multiple variables
dd($var1, $var2, $var3);
```

### 4.2. Output Formats

The output format depends on the context:

- In a CLI environment, the output is optimized for the terminal
- In a web environment, the output is HTML with syntax highlighting and collapsible structures

## 5. Integration with Laravel 12 and PHP 8.4

Symfony Var Dumper is fully integrated with Laravel 12 and PHP 8.4. Laravel provides the `dump()` and `dd()` helper functions that use Var Dumper internally.

### 5.1. Laravel-Specific Features

```php
// Dump a variable and continue execution
dump($user);

// Dump a variable and end execution
dd($user);

// Dump a request
dump(request());

// Dump a query
dump(DB::getQueryLog());

// Dump a collection
dump(User::all());
```

### 5.2. Blade Integration

You can use the `@dump` directive in Blade templates:

```blade
@dump($user)

{{-- Dump multiple variables --}}
@dump($user, $posts)
```

## 6. Advanced Usage

### 6.1. Dump Server

Start a dump server to collect all dumps in a central place:

```bash
php artisan dump-server
```

Configure the dump server in your `.env` file:

```
VAR_DUMPER_FORMAT=server
VAR_DUMPER_SERVER=tcp://127.0.0.1:9912
```

### 6.2. Custom Dumpers

Create custom dumpers for specific types:

```php
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;

// Override the default dumper
VarDumper::setHandler(function ($var) {
    $cloner = new VarCloner();
    $dumper = PHP_SAPI === 'cli' ? new CliDumper() : new HtmlDumper();
    
    // Customize the dumper
    if ($dumper instanceof HtmlDumper) {
        $dumper->setTheme('light');
    }
    
    $dumper->dump($cloner->cloneVar($var));
});
```

### 6.3. Dump Context

Add context to your dumps:

```php
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\ContextProvider\CliContextProvider;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Dumper\ServerDumper;
use Symfony\Component\VarDumper\VarDumper;

// Add source context to dumps
$cloner = new VarCloner();
$fallbackDumper = PHP_SAPI === 'cli' ? new CliDumper() : new HtmlDumper();
$dumper = new ServerDumper('tcp://127.0.0.1:9912', $fallbackDumper, [
    'cli' => new CliContextProvider(),
    'source' => new SourceContextProvider(),
]);

VarDumper::setHandler(function ($var) use ($cloner, $dumper) {
    $dumper->dump($cloner->cloneVar($var));
});
```

## 7. Best Practices

1. **Use for Debugging Only**: Remove or comment out `dump()` calls before committing code
2. **Prefer `dd()` for API Debugging**: Use `dd()` when debugging API endpoints to prevent further execution
3. **Use Dump Server**: Use the dump server for centralized debugging in complex applications
4. **Combine with Ray**: Use Var Dumper alongside Ray for comprehensive debugging
5. **Be Mindful of Large Objects**: Dumping large objects can impact performance

## 8. Troubleshooting

### 8.1. Memory Issues

If you encounter memory issues when dumping large objects:

1. Use the `dump()` function with caution on large objects
2. Configure the maximum depth and maximum string length:
   ```php
   $cloner = new VarCloner();
   $cloner->setMaxItems(250);
   $cloner->setMaxString(500);
   ```

### 8.2. Output Issues

If the output is not displaying correctly:

1. Check if another package is overriding the `dump()` function
2. Verify that the appropriate dumper is being used for your environment
3. Try using the dump server for more reliable output

### 8.3. Performance Issues

If you experience performance issues:

1. Avoid dumping large objects or collections
2. Use selective dumping with specific properties
3. Remove all `dump()` calls in production code
