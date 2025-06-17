# PeckPHP

## 1. Overview

PeckPHP is a simple PHP execution tool that allows you to run PHP code from the command line with a simplified syntax, making it useful for quick testing and debugging.

### 1.1. Package Information

- **Package Name**: peckphp/peck
- **Version**: ^0.1.3
- **GitHub**: [https://github.com/peckphp/peck](https://github.com/peckphp/peck)
- **Documentation**: [https://github.com/peckphp/peck#readme](https://github.com/peckphp/peck#readme)

## 2. Key Features

- Run PHP code from the command line
- Simple, concise syntax
- Easy access to Laravel's Application instance
- Support for short flags and options
- Ideal for quick testing and one-off scripts
- Laravel integration

## 3. Usage Examples

### 3.1. Basic Usage

```sh
## Run a simple PHP expression
./vendor/bin/peck "2 + 2"

## Echo a string
./vendor/bin/peck "echo 'Hello, World!';"

## Complex expressions
./vendor/bin/peck "array_sum(range(1, 10))"
```

### 3.2. Running Scripts

```php
// Create a script file
// script.php
<?php

$numbers = range(1, 10);
$sum = array_sum($numbers);
echo "The sum is: $sum\n";

// Run the script
./vendor/bin/peck script.php
```

### 3.3. Laravel Integration

```sh
## List all users
./vendor/bin/peck "App\\Models\\User::count()"

## Create a new user
./vendor/bin/peck "App\\Models\\User::create(['name' => 'Test', 'email' => 'test@example.com', 'password' => bcrypt('password')])"

## Use Laravel's app container
./vendor/bin/peck "app('cache')->get('key')"
```

## 4. Configuration

Peck requires no specific configuration:

```php
<?php

declare(strict_types=1);

// No configuration needed, Peck works out of the box
```

## 5. Best Practices

### 5.1. Using as a REPL

Use Peck for interactive PHP testing:

```sh
## Quick database queries
./vendor/bin/peck "DB::table('users')->whereEmail('test@example.com')->first()"

## Test helpers
./vendor/bin/peck "Str::slug('Hello World')"

## Generate test data
./vendor/bin/peck "factory(App\\Models\\User::class, 10)->create()"
```

### 5.2. Creating Utility Scripts

Create utility scripts for common tasks:

```php
<?php

declare(strict_types=1);

// cleanup.php
// Clear all caches
Artisan::call('cache:clear');
Artisan::call('config:clear');
Artisan::call('route:clear');
Artisan::call('view:clear');
echo "All caches cleared!\n";

// Run with Peck
// ./vendor/bin/peck cleanup.php
```

### 5.3. Integration with Composer Scripts

Add Peck commands to Composer scripts:

```json
"scripts": {
    "cache:stats": "./vendor/bin/peck \"printf('Cache entries: %d', Cache::count())\"",
    "db:stats": "./vendor/bin/peck \"printf('Users: %d, Posts: %d', App\\Models\\User::count(), App\\Models\\Post::count())\""
}
```
