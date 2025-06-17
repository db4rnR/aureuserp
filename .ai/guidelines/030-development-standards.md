# 3. Development Standards

## 3.1. Code Style

### 3.1.1. PHP Standards

- Follow **PSR-12** coding standards
- Use Laravel Pint for code formatting (`composer pint`)
- Maintain consistent naming conventions across plugins
- Use type declarations and PHP 8.2+ features appropriately
- All PHP files must start with:

```php
<?php

declare(strict_types=1);

```

- All files must end with a blank last line

### 3.1.2. Configuration Files

**Code Style:**

- `.editorconfig` - Editor standards
- `.prettierrc.js` - Prettier configuration
- `pint.json` - Laravel Pint settings

**Static Analysis:**

- `phpstan.neon` - PHPStan configuration
- `rector.php` - Rector configuration

**Testing:**

- `phpunit.xml` - PHPUnit configuration
- `pest.config.php` - Pest settings
- `reports/coverage/` - Coverage reports

**CI/CD:**

- `.github/workflows/code-quality.yml` - GitHub Actions workflow

## 3.2. Architecture Patterns

### 3.2.1. Domain-Driven Design

- Follow **Domain-Driven Design** principles
- Maintain clear separation between layers:
  - Application Layer (Controllers, Middleware)
  - Domain Layer (Business Logic)
  - Infrastructure Layer (Database, External Services)
  - Presentation Layer (FilamentPHP Resources)

### 3.2.2. State and Feature Management

- Implement status/state-machine using `spatie/laravel-model-states` and `spatie/laravel-model-status`
- Use `spatie/laravel-model-flags` for feature flags backed by flags enum
- Consolidate functionality into `HasAdditionalFeatures` trait rather than separate traits

### 3.2.3. UI and Component Development

- Implement Livewire UI components as Volt Single File Components (SFC)
- Ensure custom Blade directives include suitable prefixes in names for uniqueness

## 3.3. Testing Requirements

> **Note:** For comprehensive testing standards, please refer to the [Testing Standards](060-testing-standards.md) document.

### 3.3.1. Coverage and Frameworks

- Achieve 90% code coverage
- Implement Pest/PHPUnit
- Use mutation testing
- Enable stress testing

### 3.3.2. Test Types

- Unit tests for individual components
- Feature tests for application features
- Integration tests for component interactions
- Browser tests using Laravel Dusk
- Architecture tests using PEST's architecture plugin

### 3.3.3. Architecture Testing

- Use PEST architecture tests (`pestphp/pest-plugin-arch`) to enforce architectural boundaries
- Define and enforce layer dependencies (e.g., controllers should not depend on repositories directly)
- Test that classes implement required interfaces and extend correct base classes
- Verify namespace organization and adherence to architectural patterns
- Example:

```php
test('controllers reside in correct namespace and follow naming convention', function () {
    expect('App\Http\Controllers')
        ->toHaveClasses(function ($class) {
            return $class->toExtend('App\Http\Controllers\Controller')
                ->andToHaveSuffix('Controller');
        });
});
```

### 3.3.4. State Test Type Safety

- All state-related tests (e.g., for Spatie Model States) must:
  - Use only available methods and properties on state classes
  - Avoid static `make()` calls unless the method exists and is type-safe
  - When calling `transitionTo()`, always pass a new state instance (not a raw enum or string)
  - Ensure all tests are strictly type-safe and compatible with the current state class API
  - Update all existing and future tests to comply with this rule

## 3.4. Laravel Development Standards

### 3.4.1. Data Access and ORM

- Use Eloquent as primary ORM
- Avoid raw SQL queries
- Maintain consistent data access patterns
- No direct query builders
- Use Eloquent relationships
- Optimize database access
- Follow Laravel conventions

### 3.4.2. Modern PHP and Laravel Features

- Use PHP 8 attributes over PHPDocs for robust type safety:
  - Use attributes for route definitions (`#[Route]`)
  - Use attributes for validation rules (`#[Rule]`)
  - Use attributes for middleware (`#[Middleware]`)
  - Use attributes for dependency injection (`#[Inject]`)
  - Use attributes for event listeners (`#[ListensTo]`)
  - Use attributes for policies (`#[Policy]`)
  - Example:
  ```php
  #[Route('users/{id}', methods: ['GET'])]
  #[Middleware(['auth', 'verified'])]
  public function show(#[FromRoute] int $id): View
  {
      // Method implementation
  }
  ```
- Use PHP 8's match expression over traditional if-else statements
- Target Laravel 12 and PHP 8.4 for all implementations
- Adhere to Laravel 12 best practice and custom
- Prefer the latest Laravel 12 patterns, tools, techniques

## 3.5. PHP Code Quality Standards

### 3.5.1. Static Analysis and Tooling

- Configure PHPStan (level 10)
- Implement Larastan
- Use Laravel Pint for code style
- Maintain `.editorconfig`

### 3.5.2. Development Dependencies

Key development dependencies include:

```json
{
    "require-dev": {
        "alebatistella/duskapiconf": "^1.2",
        "barryvdh/laravel-debugbar": "^3.15",
        "barryvdh/laravel-ide-helper": "^3.5",
        "brianium/paratest": "^7.8",
        "driftingly/rector-laravel": "^2.0",
        "ergebnis/composer-normalize": "^2.47",
        "fakerphp/faker": "^1.24",
        "jasonmccreary/laravel-test-assertions": "^2.8",
        "larastan/larastan": "^3.4",
        "laravel-shift/blueprint": "^2.12",
        "laravel/dusk": "^8.3",
        "laravel/pint": "^1.22",
        "laravel/sail": "^1.43",
        "laravel/telescope": "^5.8",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.8",
        "nunomaduro/phpinsights": "^2.13",
        "peckphp/peck": "^0.1",
        "pestphp/pest": "^3.8",
        "pestphp/pest-plugin": "^3.x-dev",
        "pestphp/pest-plugin-arch": "^3.1",
        "pestphp/pest-plugin-faker": "^3.0",
        "pestphp/pest-plugin-laravel": "^3.2",
        "pestphp/pest-plugin-livewire": "^3.0",
        "pestphp/pest-plugin-stressless": "^3.1",
        "pestphp/pest-plugin-type-coverage": "^3.5",
        "php-parallel-lint/php-parallel-lint": "^1.4",
        "rector/rector": "^2.0",
        "rector/type-perfect": "^2.1",
        "roave/security-advisories": "dev-latest",
        "soloterm/solo": "^0.5",
        "spatie/laravel-blade-comments": "^1.4",
        "spatie/laravel-horizon-watcher": "^1.1",
        "spatie/laravel-ray": "^1.40",
        "spatie/laravel-web-tinker": "^1.10",
        "spatie/pest-plugin-snapshots": "^2.2",
        "symfony/polyfill-php84": "^1.32",
        "symfony/var-dumper": "^7.3"
    }
}
```

### 3.5.3. Quality Assurance

- Set up CI/CD checks
- Monitor cyclomatic complexity
- Check duplicate code
- Validate security
- Weekly code audits
- Generate quality reports
- Track technical debt
- Plan refactoring

## 3.6. Security Standards

### 3.6.1. Authentication and Authorization

- Use Laravel's built-in authentication system
- Implement FilamentShield for permission management
- Follow role-based access control (RBAC) principles
- Implement proper middleware for route protection

### 3.6.2. Data Protection

- Encrypt sensitive data at rest
- Use HTTPS for all connections
- Implement proper input validation
- Protect against common web vulnerabilities (XSS, CSRF, SQL Injection)
- Follow OWASP security best practices

### 3.6.3. API Security

- Use Laravel Sanctum for API authentication
- Implement rate limiting
- Validate all API inputs
- Use proper HTTP status codes
- Document API security requirements

## 3.7. Performance Optimization

### 3.7.1. Database Optimization

- Optimize database queries with proper indexing
- Use eager loading to prevent N+1 query problems
- Implement caching for expensive operations
- Use database transactions appropriately

### 3.7.2. Frontend Optimization

- Optimize asset loading for production
- Minimize JavaScript and CSS
- Use lazy loading for images and components
- Implement proper caching strategies

### 3.7.3. Application Performance

- Use queues for background processing
- Implement caching for expensive operations
- Monitor application performance
- Optimize memory usage

## 3.8. Code Organization and Structure

### 3.8.1. Namespace Standards

- Ensure all classes have correct and complete namespace declarations
- Follow PSR-4 autoloading standard
- Organize namespaces to reflect the application's domain structure
- Use consistent namespace prefixes across plugins
- Avoid deeply nested namespaces (maximum 4 levels recommended)
- Example namespace structure:
  ```
  App\Domain\Module\Submodule\ClassName
  ```

### 3.8.2. Traits and Attributes Implementation

- Ensure classes implement all required traits for their functionality
- Use PHP attributes instead of PHPDocs to document trait usage and class properties:
  - Replace `@property` PHPDocs with proper property declarations
  - Replace `@method` PHPDocs with interface implementations
  - Use attributes for trait behavior configuration
  - Use attributes for validation, casting, and other metadata
- Avoid trait conflicts by carefully managing method names
- Implement traits in a consistent order:
  1. Framework traits (e.g., `HasFactory`)
  2. Authentication traits (e.g., `Authenticatable`)
  3. Domain-specific traits (e.g., `HasUserTracking`)
  4. Feature traits (e.g., `HasAdditionalFeatures`)
- Use architecture tests to verify correct trait implementation
- Example of trait usage:
  ```php
  use HasFactory;
  use Authenticatable;
  use HasUserTracking;
  use HasAdditionalFeatures;
  ```
- Example of attribute usage over PHPDocs:
  ```php
  // Instead of:
  /**
   * @property string $name
   * @property Carbon $created_at
   */

  // Use proper property declarations:
  public string $name;
  public Carbon $created_at;

  // Instead of:
  /**
   * @method void sendNotification(string $message)
   */

  // Implement the interface:
  #[Override]
  public function sendNotification(string $message): void
  {
      // Implementation
  }

  // Use attributes for validation, casting, etc.:
  #[Cast('array')]
  #[Rule('required|array')]
  public $options;
  ```
