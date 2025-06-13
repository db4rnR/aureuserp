# Larastan (PHPStan for Laravel)

## 1. Overview

Larastan is a PHPStan extension for Laravel that provides static analysis for your Laravel application, helping to identify potential bugs and issues before runtime.

### 1.1. Package Information

- **Package Name**: larastan/larastan
- **Version**: ^3.2.0
- **GitHub**: [https://github.com/larastan/larastan](https://github.com/larastan/larastan)
- **Documentation**: [https://github.com/larastan/larastan#usage](https://github.com/larastan/larastan#usage)

## 2. Key Features

- Laravel-specific static analysis
- Type checking for Laravel facades, models, and relationships
- Custom rules for Laravel patterns
- Configurable strictness levels
- Integration with PHPStan

## 3. Usage Examples

### 3.1. Running Larastan

```bash
// Via composer script
composer analyze

// Directly
./vendor/bin/phpstan analyse
```

### 3.2. Configuration

Our phpstan configuration is located in `phpstan.neon`:

```yaml
includes:
    - ./vendor/larastan/larastan/extension.neon

parameters:
    level: 10
    paths:
        - app
        - config
        - database
        - routes
    excludePaths:
        - tests/
    checkMissingIterableValueType: true
    checkGenericClassInNonGenericObjectType: true
    checkModelProperties: true
    checkModelPropertiesValue: true
```

## 4. Integration with CI/CD

Larastan is integrated into our CI/CD pipeline to ensure code quality:

```yaml
# In GitHub Actions workflow
phpstan:
  runs-on: ubuntu-latest
  steps:
    - uses: actions/checkout@v3
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
    - name: Install dependencies
      run: composer install --prefer-dist
    - name: Run PHPStan
      run: ./vendor/bin/phpstan analyse
```

## 5. Common Issues and Solutions

### 5.1. Property Does Not Exist

If you encounter property existence errors for Eloquent models:

```php
// Add PHPDoc annotations to specify properties
/**
 * @property int $id
 * @property string $name
 * @property \DateTime $created_at
 */
class User extends Model
{
    // ...
}
```

### 5.2. Method Return Type

For method return type issues:

```php
// Add PHPDoc with specific return types
/**
 * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post>
 */
public function posts()
{
    return $this->hasMany(Post::class);
}
```
