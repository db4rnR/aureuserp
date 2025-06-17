# Pest Testing Framework

## 1. Overview

Pest is a modern testing framework with an elegant API for creating tests. It is built on top of PHPUnit but provides a more expressive and user-friendly syntax.

### 1.1. Package Information

- **Package Name**: pestphp/pest
- **Version**: ^3.8
- **GitHub**: [https://github.com/pestphp/pest](https://github.com/pestphp/pest)
- **Documentation**: [https://pestphp.com/docs/](https://pestphp.com/docs/)

## 2. Key Features

- Elegant and expressive syntax
- Simple assertions with expectation API
- Higher order testing
- Architecture testing (via plugin)
- Laravel integration (via plugin)
- Snapshot testing (via plugin)

## 3. Usage Examples

### 3.1. Basic Test

```php
<?php

declare(strict_types=1);

test('basic example', function () {
    expect(true)->toBeTrue();
});
```

### 3.2. Group of Tests

```php
<?php

declare(strict_types=1);

describe('User', function () {
    test('can be created', function () {
        // Test logic here
        expect(User::count())->toBe(1);
    });
    
    test('can be deleted', function () {
        // Test logic here
        expect(User::count())->toBe(0);
    });
});
```

## 4. Configuration

Our Pest configuration is located in `pest.config.php`:

```php
<?php

declare(strict_types=1);

return [
    'parallel' => [
        'processes' => 4,
    ],
    'coverage' => [
        'enabled' => true,
        'min' => 90,
    ],
];
```

## 5. Plugins

We use the following Pest plugins:

### 5.1. pest-plugin-arch

Architecture testing plugin that allows testing the code structure.

```php
<?php

declare(strict_types=1);

test('models use proper traits')
    ->expect('App\\Models')
    ->toUse('Illuminate\\Database\\Eloquent\\Factories\\HasFactory');
```

### 5.2. pest-plugin-laravel

Provides Laravel-specific test helpers.

```php
<?php

declare(strict_types=1);

test('homepage can be rendered')
    ->get('/')
    ->assertStatus(200);
```

### 5.3. pest-plugin-snapshots

Allows snapshot testing for verifying that output matches expected values.

```php
<?php

declare(strict_types=1);

test('json response matches snapshot')
    ->get('/api/users')
    ->assertStatus(200)
    ->assertMatchesSnapshot();
```
