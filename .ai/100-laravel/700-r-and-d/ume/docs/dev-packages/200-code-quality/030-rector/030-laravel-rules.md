# Rector Laravel Rules

## 1. Overview

This guide covers Laravel-specific Rector rules provided by the `driftingly/rector-laravel` package. These rules help automate Laravel-specific refactoring tasks and ensure compatibility with Laravel 12.

## 2. Laravel Rule Sets

The `driftingly/rector-laravel` package provides several rule sets for different Laravel versions:

```php
use Driftingly\RectorLaravel\Set\LaravelSetList;

$rectorConfig->sets([
    LaravelSetList::LARAVEL_90,   // Laravel 9.0 compatibility
    LaravelSetList::LARAVEL_100,  // Laravel 10.0 compatibility
]);
```

## 3. Key Laravel Rules

### 3.1. Controller Rules

#### 3.1.1. AddParentRegisterRector

Adds parent register call to service providers:

```php
// Before
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // ...
    }
}

// After
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();
        // ...
    }
}
```

#### 3.1.2. AddParentBootRector

Adds parent boot call to service providers:

```php
// Before
class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ...
    }
}

// After
class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
        // ...
    }
}
```

### 3.2. Eloquent Rules

#### 3.2.1. ModelPropertyToDateCastRector

Converts date properties to date casts:

```php
// Before
class Post extends Model
{
    protected $dates = [
        'published_at',
    ];
}

// After
class Post extends Model
{
    protected $casts = [
        'published_at' => 'datetime',
    ];
}
```

#### 3.2.2. EloquentMagicMethodToRelationRector

Converts magic method calls to relation methods:

```php
// Before
class Post extends Model
{
    public function getAuthorAttribute()
    {
        return User::find($this->user_id);
    }
}

// After
class Post extends Model
{
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
```

### 3.3. Blade Rules

#### 3.3.1. BladeDirectiveToBladeComponentRector

Converts Blade directives to Blade components:

```php
// Before
@include('components.alert', ['type' => 'danger', 'message' => $message])

// After
<x-alert type="danger" :message="$message" />
```

### 3.4. Request Rules

#### 3.4.1. FormRequestValidationToInlineValidationRector

Converts form request validation to inline validation:

```php
// Before
public function store(StorePostRequest $request)
{
    // ...
}

// After
public function store(Request $request)
{
    $validated = $request->validate([
        // validation rules from StorePostRequest
    ]);
    
    // ...
}
```

## 4. Laravel 12 Specific Rules

### 4.1. Upgrade Rules

These rules help upgrade from Laravel 11 to Laravel 12:

```php
use Driftingly\RectorLaravel\Set\Laravel12SetList;

$rectorConfig->sets([
    Laravel12SetList::LARAVEL_120,
]);
```

### 4.2. Key Laravel 12 Rules

#### 4.2.1. UpdateRoutingRector

Updates routing syntax for Laravel 12:

```php
// Before
Route::get('profile', 'ProfileController@index');

// After
Route::get('profile', [ProfileController::class, 'index']);
```

#### 4.2.2. UpdateValidationRulesRector

Updates validation rules for Laravel 12:

```php
// Before
$rules = [
    'email' => 'required|email',
];

// After
$rules = [
    'email' => ['required', 'email'],
];
```

## 5. Custom Laravel Rules

You can create custom Laravel-specific rules:

```php
namespace App\Rector;

use PhpParser\Node;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CustomLaravelRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Description of the rule',
            [
                new CodeSample(
                    // code before
                    '',
                    // code after
                    ''
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        // Implement your custom logic here
        
        return $node;
    }
}
```

## 6. Integration with Laravel 12 and PHP 8.4

The Laravel Rector rules are fully compatible with Laravel 12 and PHP 8.4. They help:

1. Upgrade code to use new Laravel 12 features
2. Refactor code to use PHP 8.4 features
3. Improve code quality according to Laravel best practices

## 7. Best Practices

1. **Run in Dry-Run Mode First**: Always preview changes before applying them
2. **Apply Rules Gradually**: Apply one rule set at a time
3. **Test After Refactoring**: Ensure tests pass after applying rules
4. **Combine with PHPStan**: Use PHPStan to verify type safety after refactoring
5. **Review Changes**: Manually review changes to ensure they maintain the intended behavior

## 8. Troubleshooting

### 8.1. Rule Conflicts

If you encounter conflicts between rules:

1. Apply rules one at a time
2. Skip conflicting rules
3. Apply rules in a specific order

### 8.2. False Positives

If rules make incorrect changes:

1. Skip the specific rule
2. Create a more specific rule
3. Report the issue to the package maintainer
