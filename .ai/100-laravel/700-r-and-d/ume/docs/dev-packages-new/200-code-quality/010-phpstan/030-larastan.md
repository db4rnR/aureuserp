# Larastan: Laravel-Specific PHPStan Extension

## 1. Overview

Larastan is a PHPStan extension specifically designed for Laravel applications. It provides additional rules, type definitions, and features that make PHPStan work seamlessly with Laravel's dynamic features and conventions.

## 2. Key Features

### 2.1. Laravel-Specific Type Inference

Larastan understands Laravel's magic methods and properties:

- Eloquent model property types
- Relationship method return types
- Dynamic property access
- Facade method calls
- Service container bindings

### 2.2. Framework-Aware Rules

Larastan includes rules specifically for Laravel:

- Proper usage of Eloquent methods
- Validation of route definitions
- Correct usage of blade directives
- Service container binding validation

### 2.3. Custom Stubs

Provides stub files for Laravel classes to improve type inference:

- Eloquent models
- Collections
- Query builder
- Facades

## 3. Configuration

### 3.1. Basic Configuration

Include Larastan in your PHPStan configuration:

```yaml
# phpstan.neon
includes:
    - ./vendor/larastan/larastan/extension.neon

parameters:
    # Larastan-specific settings
    checkModelProperties: true
    checkPhpDocMissingReturn: false
```

### 3.2. Model Property Settings

Control how Larastan handles Eloquent model properties:

```yaml
parameters:
    checkModelProperties: true  # Enable model property checking
    checkMissingIterableValueType: false  # Disable checking for missing iterable value types
```

### 3.3. Custom Model Properties

Define custom model properties:

```yaml
parameters:
    modelProperties:
        App\Models\User:
            custom_field: string
```

## 4. Laravel-Specific Features

### 4.1. Eloquent Models

Larastan understands Eloquent model properties based on database schema:

```php
// PHPStan correctly infers that $user->email is a string
$user = User::find(1);
$email = $user->email;
```

### 4.2. Relationships

Larastan correctly types relationship methods:

```php
// PHPStan knows this returns a Collection of Post models
$user->posts()->where('published', true)->get();
```

### 4.3. Facades

Larastan understands Laravel facades:

```php
// PHPStan correctly types the return value
$value = Cache::get('key', 'default');
```

## 5. Common Issues and Solutions

### 5.1. Dynamic Properties

If you have issues with dynamic properties:

```yaml
parameters:
    universalObjectCratesClasses:
        - App\Models\DynamicModel
```

### 5.2. Custom Collections

For custom collections:

```yaml
parameters:
    checkGenericClassInNonGenericObjectType: false
```

### 5.3. Ignoring Specific Laravel Patterns

Ignore common Laravel patterns that trigger false positives:

```yaml
parameters:
    ignoreErrors:
        - '#Call to an undefined method Illuminate\\Support\\HigherOrder#'
        - '#Access to an undefined property App\\Models#'
```

## 6. Advanced Usage

### 6.1. Custom Eloquent Builders

Configure custom query builders:

```yaml
parameters:
    eloquent:
        customBuilders:
            - App\Eloquent\CustomBuilder
```

### 6.2. Custom Facades

Register custom facades:

```yaml
parameters:
    facades:
        App\Facades\CustomFacade:
            methodName: App\Services\CustomService
```

## 7. Best Practices

1. Start with `checkModelProperties: false` and enable it later
2. Use `@property` PHPDoc annotations for non-column model properties
3. Create custom model property mappings for complex scenarios
4. Use `@mixin` to improve IDE support alongside Larastan

## 8. Troubleshooting

If you encounter issues with Larastan:

1. Ensure you're using a compatible version with your Laravel version
2. Check for conflicts with other PHPStan extensions
3. Try disabling specific features to isolate problems
4. Consult the [official documentation](https://github.com/larastan/larastan)
