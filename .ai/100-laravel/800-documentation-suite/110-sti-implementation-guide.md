# 9. STI Implementation Quick Reference

## 9.1. Overview

Single Table Inheritance (STI) quick reference guide for implementing user and organization models across the Laravel Service Framework R&D streams.

**Confidence Score: 92%** - Very high confidence based on established Laravel STI patterns and extensive R&D stream analysis.

## 9.2. STI Fundamentals

### 9.2.1. What is STI?

STI stores multiple related classes in a single database table, using a `type` column to distinguish between different model types.

**Benefits:**

-   Single table for related models
-   Shared database columns
-   Polymorphic relationships work seamlessly
-   Faster queries (no joins required)

**Trade-offs:**

-   Sparse tables (some columns null for some types)
-   Table can grow large
-   Schema changes affect all types

### 9.2.2. When to Use STI

✅ **Good Use Cases:**

-   User types (Admin, Customer, Manager)
-   Organization types (Corporation, Partnership, LLC)
-   Similar models with slight behavioral differences
-   Shared relationships and attributes

❌ **Avoid STI When:**

-   Models have vastly different attributes
-   Table would become too sparse (>30% null columns)
-   Different models need different database indexes
-   Models have no behavioral relationship

## 9.3. Base STI Pattern

### 9.3.1. Abstract Base Model

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseSTIModel extends Model
{
    protected $guarded = [];

    /**
     * Boot the model and set up automatic type assignment
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->type) {
                $model->type = static::getModelType();
            }
        });

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', static::getModelType());
        });
    }

    /**
     * Define the model type for this class
     */
    abstract public static function getModelType(): string;

    /**
     * Create a new query builder with type constraint
     */
    public function newQuery(): Builder
    {
        return parent::newQuery()->where('type', static::getModelType());
    }

    /**
     * Get the table associated with the model (for child classes)
     */
    public function getTable(): string
    {
        if (!isset($this->table)) {
            // Use the parent class name for the table
            $parentClass = get_parent_class($this);
            $this->table = app($parentClass)->getTable();
        }

        return $this->table;
    }
}
```

### 9.3.2. Migration Template

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index(); // STI type column
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Admin-specific columns
            $table->json('admin_permissions')->nullable();
            $table->timestamp('last_admin_login')->nullable();

            // Customer-specific columns
            $table->string('customer_tier')->nullable();
            $table->decimal('account_balance', 10, 2)->nullable();

            // Manager-specific columns
            $table->string('department')->nullable();
            $table->json('team_members')->nullable();

            $table->rememberToken();
            $table->timestamps();

            // Indexes for STI queries
            $table->index(['type', 'created_at']);
            $table->index(['type', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
```

## 9.4. User Model Implementation

### 9.4.1. Base User Model

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;

abstract class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($user) {
            if (!$user->type) {
                $user->type = static::getModelType();
            }
        });

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', static::getModelType());
        });
    }

    abstract public static function getModelType(): string;

    /**
     * Get user's display name based on type
     */
    public function getDisplayName(): string
    {
        return $this->name;
    }

    /**
     * Check if user has specific type
     */
    public function isType(string $type): bool
    {
        return $this->type === $type;
    }

    /**
     * Factory method to create user of specific type
     */
    public static function createOfType(string $type, array $attributes = []): ?User
    {
        $class = match($type) {
            'admin' => AdminUser::class,
            'customer' => CustomerUser::class,
            'manager' => ManagerUser::class,
            default => null
        };

        return $class ? $class::create($attributes) : null;
    }
}
```

### 9.4.2. Concrete User Types

**Admin User:**

```php
<?php

declare(strict_types=1);

namespace App\Models\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AdminUser extends User
{
    protected $casts = [
        'admin_permissions' => 'array',
        'last_admin_login' => 'datetime',
    ];

    public static function getModelType(): string
    {
        return 'admin';
    }

    /**
     * Admin-specific scope
     */
    public function scopeRecentlyActive($query, int $days = 30)
    {
        return $query->where('last_admin_login', '>=', now()->subDays($days));
    }

    /**
     * Check if admin has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->admin_permissions ?? []);
    }

    /**
     * Grant permission to admin
     */
    public function grantPermission(string $permission): void
    {
        $permissions = $this->admin_permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['admin_permissions' => $permissions]);
        }
    }

    /**
     * Update last admin login timestamp
     */
    public function updateAdminLogin(): void
    {
        $this->update(['last_admin_login' => now()]);
    }
}
```

**Customer User:**

```php
<?php

declare(strict_types=1);

namespace App\Models\Users;

use App\Models\User;

class CustomerUser extends User
{
    protected $casts = [
        'account_balance' => 'decimal:2',
    ];

    public static function getModelType(): string
    {
        return 'customer';
    }

    /**
     * Customer tier constants
     */
    const TIER_BRONZE = 'bronze';
    const TIER_SILVER = 'silver';
    const TIER_GOLD = 'gold';
    const TIER_PLATINUM = 'platinum';

    /**
     * Get available tiers
     */
    public static function getAvailableTiers(): array
    {
        return [
            self::TIER_BRONZE,
            self::TIER_SILVER,
            self::TIER_GOLD,
            self::TIER_PLATINUM,
        ];
    }

    /**
     * Scope for specific tier
     */
    public function scopeOfTier($query, string $tier)
    {
        return $query->where('customer_tier', $tier);
    }

    /**
     * Check if customer has sufficient balance
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->account_balance >= $amount;
    }

    /**
     * Deduct amount from balance
     */
    public function deductBalance(float $amount): bool
    {
        if (!$this->hasSufficientBalance($amount)) {
            return false;
        }

        $this->decrement('account_balance', $amount);
        return true;
    }

    /**
     * Add amount to balance
     */
    public function addBalance(float $amount): void
    {
        $this->increment('account_balance', $amount);
    }
}
```

**Manager User:**

```php
<?php

declare(strict_types=1);

namespace App\Models\Users;

use App\Models\User;

class ManagerUser extends User
{
    protected $casts = [
        'team_members' => 'array',
    ];

    public static function getModelType(): string
    {
        return 'manager';
    }

    /**
     * Scope for specific department
     */
    public function scopeInDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Get team members
     */
    public function getTeamMembers(): array
    {
        return $this->team_members ?? [];
    }

    /**
     * Add team member
     */
    public function addTeamMember(int $userId): void
    {
        $teamMembers = $this->getTeamMembers();
        if (!in_array($userId, $teamMembers)) {
            $teamMembers[] = $userId;
            $this->update(['team_members' => $teamMembers]);
        }
    }

    /**
     * Remove team member
     */
    public function removeTeamMember(int $userId): void
    {
        $teamMembers = array_filter(
            $this->getTeamMembers(),
            fn($id) => $id !== $userId
        );
        $this->update(['team_members' => array_values($teamMembers)]);
    }

    /**
     * Check if manages user
     */
    public function managesUser(int $userId): bool
    {
        return in_array($userId, $this->getTeamMembers());
    }
}
```

## 9.5. Organization Model Implementation

### 9.5.1. Base Organization Model

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

abstract class Organization extends Model
{
    protected $fillable = [
        'name',
        'type',
        'tax_id',
        'email',
        'phone',
        'address',
    ];

    protected $casts = [
        'address' => 'array',
        'formation_date' => 'date',
        'partnership_terms' => 'array',
        'board_members' => 'array',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($organization) {
            if (!$organization->type) {
                $organization->type = static::getModelType();
            }
        });

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', static::getModelType());
        });
    }

    abstract public static function getModelType(): string;

    /**
     * Get users belonging to this organization
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get organization's display name
     */
    public function getDisplayName(): string
    {
        return $this->name;
    }

    /**
     * Factory method to create organization of specific type
     */
    public static function createOfType(string $type, array $attributes = []): ?Organization
    {
        $class = match($type) {
            'corporation' => Corporation::class,
            'partnership' => Partnership::class,
            'llc' => LLC::class,
            default => null
        };

        return $class ? $class::create($attributes) : null;
    }
}
```

### 9.5.2. Concrete Organization Types

**Corporation:**

```php
<?php

declare(strict_types=1);

namespace App\Models\Organizations;

use App\Models\Organization;

class Corporation extends Organization
{
    protected $casts = [
        'board_members' => 'array',
        'incorporation_date' => 'date',
        'share_structure' => 'array',
    ];

    public static function getModelType(): string
    {
        return 'corporation';
    }

    /**
     * Get board members
     */
    public function getBoardMembers(): array
    {
        return $this->board_members ?? [];
    }

    /**
     * Add board member
     */
    public function addBoardMember(array $member): void
    {
        $boardMembers = $this->getBoardMembers();
        $boardMembers[] = $member;
        $this->update(['board_members' => $boardMembers]);
    }

    /**
     * Check if corporation is public
     */
    public function isPublic(): bool
    {
        return $this->corporation_type === 'public';
    }
}
```

## 9.6. Common Patterns and Best Practices

### 9.6.1. Querying STI Models

**Get all users of a specific type:**

```php
// Using the concrete class (recommended)
$adminUsers = AdminUser::all();

// Using the base class with where clause
$adminUsers = User::where('type', 'admin')->get();

// Using factory method
$newAdmin = User::createOfType('admin', ['name' => 'John Doe']);
```

**Cross-type queries:**

```php
// Get all users regardless of type
$allUsers = User::withoutGlobalScope('type')->get();

// Get users of multiple types
$staffUsers = User::withoutGlobalScope('type')
    ->whereIn('type', ['admin', 'manager'])
    ->get();
```

### 9.6.2. Relationships with STI Models

**Polymorphic relationships:**

```php
class Comment extends Model
{
    public function commentable()
    {
        return $this->morphTo();
    }
}

// Works seamlessly with STI
$admin = AdminUser::find(1);
$admin->comments()->create(['content' => 'Admin comment']);

$customer = CustomerUser::find(2);
$customer->comments()->create(['content' => 'Customer comment']);
```

### 9.6.3. Factory Definitions

**User Factory:**

```php
<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Users\{AdminUser, CustomerUser, ManagerUser};
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => AdminUser::getModelType(),
            'admin_permissions' => ['user_management', 'system_settings'],
            'last_admin_login' => now(),
        ]);
    }

    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => CustomerUser::getModelType(),
            'customer_tier' => fake()->randomElement(['bronze', 'silver', 'gold']),
            'account_balance' => fake()->randomFloat(2, 0, 10000),
        ]);
    }

    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => ManagerUser::getModelType(),
            'department' => fake()->randomElement(['IT', 'Sales', 'Marketing']),
            'team_members' => [],
        ]);
    }
}
```

## 9.7. Testing STI Models

### 9.7.1. Feature Tests

```php
<?php

declare(strict_types=1);

use App\Models\Users\{AdminUser, CustomerUser, ManagerUser};

test('admin users can be created with correct type', function () {
    $admin = AdminUser::factory()->create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
    ]);

    expect($admin->type)->toBe('admin');
    expect($admin)->toBeInstanceOf(AdminUser::class);
});

test('user queries are scoped by type', function () {
    AdminUser::factory()->create();
    CustomerUser::factory()->create();
    ManagerUser::factory()->create();

    expect(AdminUser::count())->toBe(1);
    expect(CustomerUser::count())->toBe(1);
    expect(ManagerUser::count())->toBe(1);
});

test('cross-type queries work without global scope', function () {
    AdminUser::factory()->create();
    CustomerUser::factory()->create();

    $allUsers = User::withoutGlobalScope('type')->get();
    expect($allUsers)->toHaveCount(2);
});
```

### 9.7.2. Unit Tests

```php
<?php

declare(strict_types=1);

use App\Models\Users\AdminUser;

test('admin user has correct permissions methods', function () {
    $admin = AdminUser::factory()->create([
        'admin_permissions' => ['user_management'],
    ]);

    expect($admin->hasPermission('user_management'))->toBeTrue();
    expect($admin->hasPermission('system_settings'))->toBeFalse();

    $admin->grantPermission('system_settings');

    expect($admin->hasPermission('system_settings'))->toBeTrue();
});
```

## 9.8. Common Pitfalls and Solutions

### 9.8.1. Forgetting Type in Mass Assignment

❌ **Wrong:**

```php
// This will fail because type isn't set
User::create(['name' => 'John', 'email' => 'john@test.com']);
```

✅ **Correct:**

```php
// Use concrete class
AdminUser::create(['name' => 'John', 'email' => 'john@test.com']);

// Or use factory method
User::createOfType('admin', ['name' => 'John', 'email' => 'john@test.com']);
```

### 9.8.2. Querying Across Types

❌ **Wrong:**

```php
// This will only return users of the current model's type
$users = AdminUser::where('created_at', '>', now()->subDays(7))->get();
```

✅ **Correct:**

```php
// Remove global scope to query across types
$users = User::withoutGlobalScope('type')
    ->where('created_at', '>', now()->subDays(7))
    ->get();
```

### 9.8.3. Table Name Issues

❌ **Wrong:**

```php
class AdminUser extends User
{
    protected $table = 'admin_users'; // Don't do this!
}
```

✅ **Correct:**

```php
class AdminUser extends User
{
    // Inherits table name from parent User class
    // Or override getTable() method if needed
}
```

## 9.9. Performance Considerations

### 9.9.1. Indexing Strategy

```sql
-- Essential indexes for STI tables
CREATE INDEX idx_users_type ON users(type);
CREATE INDEX idx_users_type_created_at ON users(type, created_at);
CREATE INDEX idx_users_type_email ON users(type, email);

-- Type-specific indexes
CREATE INDEX idx_users_admin_last_login ON users(last_admin_login)
    WHERE type = 'admin';
CREATE INDEX idx_users_customer_tier ON users(customer_tier)
    WHERE type = 'customer';
```

### 9.9.2. Query Optimization

```php
// Use specific model classes for better query optimization
$recentAdmins = AdminUser::where('last_admin_login', '>', now()->subDays(30))
    ->with('roles') // Eager load relationships
    ->get();

// Avoid N+1 queries with STI relationships
$organizations = Corporation::with('users')->get();
```

## 9.10. Checklist for STI Implementation

### 9.10.1. Before Implementation

-   [ ] Confirm models share enough attributes (>70% column usage)
-   [ ] Verify behavioral relationships justify STI
-   [ ] Design database schema with appropriate indexes
-   [ ] Plan migration strategy for existing data

### 9.10.2. During Implementation

-   [ ] Create abstract base model with proper boot method
-   [ ] Implement getModelType() in all concrete classes
-   [ ] Add global scope for automatic type filtering
-   [ ] Create factories for all STI types
-   [ ] Write comprehensive tests

### 9.10.3. After Implementation

-   [ ] Verify query performance with indexes
-   [ ] Test cross-type queries work correctly
-   [ ] Confirm relationships function properly
-   [ ] Document type-specific methods and scopes
-   [ ] Monitor for sparse table issues

**Remember:** STI is powerful but can become complex. When in doubt, ask a senior developer for guidance on whether STI is the right choice for your use case!
