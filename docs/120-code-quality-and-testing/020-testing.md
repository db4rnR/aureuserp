# 2. Testing with Pest

This document provides comprehensive instructions for setting up and using Pest for testing in the AureusERP project.

## 2.1. Introduction to Pest

[Pest](https://pestphp.com/) is a testing framework with a focus on simplicity and developer experience. It's built on top of PHPUnit and provides a more expressive syntax for writing tests.

Key features of Pest:
- Simple and expressive syntax
- Built on top of PHPUnit
- Supports all PHPUnit assertions
- Provides additional assertions and expectations
- Supports plugins for extended functionality

## 2.2. Installation and Setup

Pest is already included in the project's `composer.json` file. If you need to install it manually, run:

```bash
composer require pestphp/pest --dev
composer require pestphp/pest-plugin-laravel --dev
```

### 2.2.1. Initialize Pest

If Pest hasn't been initialized yet, run:

```bash
./vendor/bin/pest --init
```

This will create the necessary files for Pest to work.

### 2.2.2. Install Additional Plugins

The project uses several Pest plugins for enhanced testing capabilities:

```bash
composer require pestphp/pest-plugin-faker --dev
composer require pestphp/pest-plugin-livewire --dev
composer require pestphp/pest-plugin-arch --dev
composer require pestphp/pest-plugin-type-coverage --dev
composer require pestphp/pest-plugin-stressless --dev
composer require spatie/pest-plugin-snapshots --dev
```

## 2.3. Test Types

### 2.3.1. Unit Tests

Unit tests focus on testing individual components in isolation. Place unit tests in the `tests/Unit` directory.

Example unit test:

```php
<?php

test('sum calculates correctly', function () {
    $calculator = new Calculator();
    expect($calculator->sum(1, 2))->toBe(3);
});
```

### 2.3.2. Feature Tests

Feature tests focus on testing application features. Place feature tests in the `tests/Feature` directory.

Example feature test:

```php
<?php

test('user can view the homepage', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});
```

### 2.3.3. Integration Tests

Integration tests focus on testing how components work together. Place integration tests in the `tests/Integration` directory.

Example integration test:

```php
<?php

test('order process works end-to-end', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    
    $this->actingAs($user)
        ->post('/cart/add', ['product_id' => $product->id])
        ->assertRedirect('/cart');
        
    $this->post('/checkout')
        ->assertRedirect('/orders');
        
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
    ]);
});
```

### 2.3.4. Browser Tests

Browser tests use Laravel Dusk to test the application in a real browser. Place browser tests in the `tests/Browser` directory.

To set up Dusk:

```bash
composer require laravel/dusk --dev
php artisan dusk:install
```

Example browser test:

```php
<?php

use Laravel\Dusk\Browser;

test('user can log in', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    
    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
                ->type('email', 'test@example.com')
                ->type('password', 'password')
                ->press('Login')
                ->assertPathIs('/dashboard');
    });
});
```

## 2.4. Advanced Testing Features

### 2.4.1. Architectural Testing

The `pest-plugin-arch` plugin allows testing the architecture of your application.

Example architectural test:

```php
<?php

arch('models depend on nothing from the framework')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->toImplement('Illuminate\Contracts\Auth\Authenticatable')
    ->toUse('Illuminate\Database\Eloquent\Concerns')
    ->toUse('Illuminate\Database\Eloquent\Relations')
    ->toUse('Illuminate\Support\Str')
    ->toNotUse('Illuminate\Http')
    ->toNotUse('Illuminate\Routing')
    ->toNotUse('Illuminate\View')
    ->toNotUse('Illuminate\Foundation\Http');
```

### 2.4.2. Type Coverage Testing

The `pest-plugin-type-coverage` plugin allows testing the type coverage of your application.

Example type coverage test:

```php
<?php

typeCoverage()
    ->paths([
        app_path('Models'),
        app_path('Services'),
    ])
    ->minLevel(95);
```

### 2.4.3. Stress Testing

The `pest-plugin-stressless` plugin allows stress testing your application.

Example stress test:

```php
<?php

stress('api endpoint can handle load')
    ->url('/api/users')
    ->concurrency(10)
    ->duration(5)
    ->assert(function ($metrics) {
        expect($metrics->requests()->successful())->toBeGreaterThanOrEqual(100);
        expect($metrics->requests()->failed())->toBe(0);
        expect($metrics->duration()->p95())->toBeLessThan(500);
    });
```

### 2.4.4. Snapshot Testing

The `pest-plugin-snapshots` plugin allows snapshot testing.

Example snapshot test:

```php
<?php

test('api response matches snapshot', function () {
    $response = $this->get('/api/users');
    $response->assertStatus(200);
    expect($response->json())->toMatchSnapshot();
});
```

## 2.5. Test Factories

Laravel provides a powerful factory system for generating test data. Define factories in the `database/factories` directory.

Example factory:

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
```

Using factories in tests:

```php
<?php

test('user can be created', function () {
    $user = User::factory()->create();
    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->not->toBeEmpty();
});
```

## 2.6. Test Database

Configure your test database in the `phpunit.xml` file:

```xml
<php>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

For more complex database testing, consider using a dedicated test database:

```xml
<php>
    <env name="DB_CONNECTION" value="mysql"/>
    <env name="DB_DATABASE" value="testing"/>
</php>
```

## 2.7. Composer Scripts

Add the following scripts to your `composer.json` file:

```json
{
    "scripts": {
        "test": "pest",
        "test:coverage": "pest --coverage",
        "test:coverage-html": "pest --coverage --coverage-html=reports/coverage",
        "test:parallel": "pest --parallel",
        "test:type-coverage": "pest --type-coverage",
        "test:arch": "pest --group=arch",
        "test:stress": "pest --group=stress",
        "test:unit": "pest --group=unit",
        "test:feature": "pest --group=feature",
        "test:integration": "pest --group=integration"
    }
}
```

## 2.8. Running Tests

Run all tests:

```bash
composer test
```

Run specific test groups:

```bash
composer test:unit
composer test:feature
composer test:integration
```

Run tests with coverage:

```bash
composer test:coverage
composer test:coverage-html
```

Run tests in parallel:

```bash
composer test:parallel
```

## 2.9. Continuous Integration

Configure GitHub Actions to run tests on every push and pull request. See the [CI/CD documentation](030-ci-cd.md) for more details.

## 2.10. Best Practices

1. **Write tests first**: Follow Test-Driven Development (TDD) principles.
2. **Keep tests simple**: Each test should test one thing.
3. **Use descriptive test names**: Test names should describe what the test is testing.
4. **Use factories**: Use factories to generate test data.
5. **Clean up after tests**: Use database transactions to clean up after tests.
6. **Group related tests**: Use test groups to organize related tests.
7. **Aim for high coverage**: Aim for at least 90% code coverage.
8. **Test edge cases**: Test edge cases and error conditions.
9. **Use mocks and stubs**: Use mocks and stubs to isolate components.
10. **Keep tests fast**: Tests should run quickly to encourage frequent testing.
