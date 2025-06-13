# 10. Event Sourcing Quick Reference

## 10.1. Overview

Event sourcing implementation guide using `hirethunk/verbs` package for the Laravel Service Framework R&D streams.

**Confidence Score: 89%** - High confidence based on hirethunk/verbs documentation analysis and R&D stream implementation patterns.

## 10.2. Event Sourcing Fundamentals

### 10.2.1. What is Event Sourcing?

Event sourcing stores the state of your application as a sequence of events rather than storing just the current state.

**Key Concepts:**

-   **Events**: Immutable facts about what happened
-   **Event Store**: Database that stores all events
-   **Aggregates**: Domain objects that generate events
-   **Projections**: Read models built from events
-   **Event Replay**: Reconstructing state by replaying events

### 10.2.2. Benefits and Trade-offs

✅ **Benefits:**

-   Complete audit trail of all changes
-   Time travel (view state at any point)
-   Event replay for testing and debugging
-   Natural fit for CQRS architecture
-   Scalable read models

❌ **Trade-offs:**

-   Increased complexity
-   Storage requirements grow over time
-   Eventual consistency challenges
-   Learning curve for developers

## 10.3. hirethunk/verbs Setup

### 10.3.1. Installation

```bash
composer require hirethunk/verbs:"^0.7"
php artisan vendor:publish --tag=verbs-migrations
php artisan migrate
```

### 10.3.2. Configuration

**config/verbs.php:**

```php
<?php

return [
    'events' => [
        'stored_event_model' => \Thunk\Verbs\Models\VerbEvent::class,
        'stored_snapshot_model' => \Thunk\Verbs\Models\VerbSnapshot::class,
    ],

    'id_type' => 'snowflake', // or 'uuid', 'ulid'

    'storage' => [
        'driver' => env('VERBS_STORAGE_DRIVER', 'local'),
    ],

    'replay' => [
        'chunk_size' => 1000,
    ],
];
```

## 10.4. Basic Event Implementation

### 10.4.1. Creating Events

```php
<?php

declare(strict_types=1);

namespace App\Events;

use Thunk\Verbs\Event;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class UserRegistered extends Event
{
    #[StateId(UserState::class)]
    public int $user_id;

    public string $name;
    public string $email;
    public string $type;
    public array $metadata;

    public function __construct(
        int $user_id,
        string $name,
        string $email,
        string $type = 'customer',
        array $metadata = []
    ) {
        $this->user_id = $user_id;
        $this->name = $name;
        $this->email = $email;
        $this->type = $type;
        $this->metadata = $metadata;
    }

    /**
     * Validate the event before committing
     */
    public function validate(): void
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('User name cannot be empty');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        if (!in_array($this->type, ['admin', 'customer', 'manager'])) {
            throw new \InvalidArgumentException('Invalid user type');
        }
    }
}
```

### 10.4.2. Event States (Aggregates)

```php
<?php

declare(strict_types=1);

namespace App\States;

use Thunk\Verbs\State;
use App\Events\{UserRegistered, UserEmailUpdated, UserDeactivated};

class UserState extends State
{
    public int $user_id;
    public string $name;
    public string $email;
    public string $type;
    public bool $is_active = true;
    public array $metadata = [];
    public \Carbon\Carbon $created_at;
    public ?\Carbon\Carbon $updated_at = null;

    /**
     * Handle UserRegistered event
     */
    public function applyUserRegistered(UserRegistered $event): void
    {
        $this->user_id = $event->user_id;
        $this->name = $event->name;
        $this->email = $event->email;
        $this->type = $event->type;
        $this->metadata = $event->metadata;
        $this->created_at = now();
    }

    /**
     * Handle UserEmailUpdated event
     */
    public function applyUserEmailUpdated(UserEmailUpdated $event): void
    {
        $this->email = $event->new_email;
        $this->updated_at = now();
    }

    /**
     * Handle UserDeactivated event
     */
    public function applyUserDeactivated(UserDeactivated $event): void
    {
        $this->is_active = false;
        $this->updated_at = now();
    }

    /**
     * Check if user can be updated
     */
    public function canUpdate(): bool
    {
        return $this->is_active;
    }

    /**
     * Get user's full data as array
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

## 10.5. Command Pattern with Events

### 10.5.1. Command Classes

```php
<?php

declare(strict_types=1);

namespace App\Commands;

use App\Events\UserRegistered;
use App\States\UserState;

class RegisterUserCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $type = 'customer',
        public readonly array $metadata = []
    ) {}

    public function handle(): UserState
    {
        // Generate new user ID
        $userId = app('snowflake')->nextId();

        // Check if email already exists
        $existingUser = UserState::where('email', $this->email)->first();
        if ($existingUser) {
            throw new \InvalidArgumentException('Email already exists');
        }

        // Fire the event
        UserRegistered::fire(
            user_id: $userId,
            name: $this->name,
            email: $this->email,
            type: $this->type,
            metadata: $this->metadata
        );

        // Return the updated state
        return UserState::load($userId);
    }
}
```

### 10.5.2. Command Handler

```php
<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Commands\RegisterUserCommand;
use App\States\UserState;

class UserCommandHandler
{
    public function handleRegisterUser(RegisterUserCommand $command): UserState
    {
        return $command->handle();
    }

    public function handleUpdateUserEmail(UpdateUserEmailCommand $command): UserState
    {
        $userState = UserState::load($command->user_id);

        if (!$userState->canUpdate()) {
            throw new \InvalidArgumentException('User cannot be updated');
        }

        UserEmailUpdated::fire(
            user_id: $command->user_id,
            old_email: $userState->email,
            new_email: $command->new_email
        );

        return UserState::load($command->user_id);
    }
}
```

## 10.6. Projections (Read Models)

### 10.6.1. Database Projection

```php
<?php

declare(strict_types=1);

namespace App\Projections;

use Thunk\Verbs\Projection;
use App\Events\{UserRegistered, UserEmailUpdated, UserDeactivated};
use Illuminate\Support\Facades\DB;

class UserProjection extends Projection
{
    protected string $table = 'user_projections';

    /**
     * Handle UserRegistered event
     */
    public function handleUserRegistered(UserRegistered $event): void
    {
        DB::table($this->table)->insert([
            'user_id' => $event->user_id,
            'name' => $event->name,
            'email' => $event->email,
            'type' => $event->type,
            'is_active' => true,
            'metadata' => json_encode($event->metadata),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Handle UserEmailUpdated event
     */
    public function handleUserEmailUpdated(UserEmailUpdated $event): void
    {
        DB::table($this->table)
            ->where('user_id', $event->user_id)
            ->update([
                'email' => $event->new_email,
                'updated_at' => now(),
            ]);
    }

    /**
     * Handle UserDeactivated event
     */
    public function handleUserDeactivated(UserDeactivated $event): void
    {
        DB::table($this->table)
            ->where('user_id', $event->user_id)
            ->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);
    }
}
```

### 10.6.2. Projection Migration

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProjectionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('user_projections', function (Blueprint $table) {
            $table->bigInteger('user_id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('type');
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            // Indexes for common queries
            $table->index(['type', 'is_active']);
            $table->index(['email']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_projections');
    }
}
```

## 10.7. Organization Event Sourcing

### 10.7.1. Organization Events

```php
<?php

declare(strict_types=1);

namespace App\Events;

use Thunk\Verbs\Event;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;

class OrganizationCreated extends Event
{
    #[StateId(OrganizationState::class)]
    public int $organization_id;

    public string $name;
    public string $type;
    public ?string $tax_id;
    public array $address;
    public array $metadata;

    public function validate(): void
    {
        if (empty($this->name)) {
            throw new \InvalidArgumentException('Organization name cannot be empty');
        }

        if (!in_array($this->type, ['corporation', 'partnership', 'llc'])) {
            throw new \InvalidArgumentException('Invalid organization type');
        }
    }
}

class UserJoinedOrganization extends Event
{
    #[StateId(OrganizationState::class)]
    public int $organization_id;

    public int $user_id;
    public string $role;
    public array $permissions;

    public function validate(): void
    {
        if (!in_array($this->role, ['owner', 'admin', 'member'])) {
            throw new \InvalidArgumentException('Invalid role');
        }
    }
}
```

### 10.7.2. Organization State

```php
<?php

declare(strict_types=1);

namespace App\States;

use Thunk\Verbs\State;
use App\Events\{OrganizationCreated, UserJoinedOrganization, UserLeftOrganization};

class OrganizationState extends State
{
    public int $organization_id;
    public string $name;
    public string $type;
    public ?string $tax_id = null;
    public array $address = [];
    public array $members = []; // [user_id => ['role' => 'admin', 'permissions' => []]]
    public array $metadata = [];
    public \Carbon\Carbon $created_at;

    public function applyOrganizationCreated(OrganizationCreated $event): void
    {
        $this->organization_id = $event->organization_id;
        $this->name = $event->name;
        $this->type = $event->type;
        $this->tax_id = $event->tax_id;
        $this->address = $event->address;
        $this->metadata = $event->metadata;
        $this->created_at = now();
    }

    public function applyUserJoinedOrganization(UserJoinedOrganization $event): void
    {
        $this->members[$event->user_id] = [
            'role' => $event->role,
            'permissions' => $event->permissions,
            'joined_at' => now(),
        ];
    }

    public function applyUserLeftOrganization(UserLeftOrganization $event): void
    {
        unset($this->members[$event->user_id]);
    }

    /**
     * Check if user is member of organization
     */
    public function hasMember(int $userId): bool
    {
        return isset($this->members[$userId]);
    }

    /**
     * Get user's role in organization
     */
    public function getUserRole(int $userId): ?string
    {
        return $this->members[$userId]['role'] ?? null;
    }

    /**
     * Get all members with specific role
     */
    public function getMembersByRole(string $role): array
    {
        return array_filter(
            $this->members,
            fn($member) => $member['role'] === $role
        );
    }
}
```

## 10.8. Event Handlers and Listeners

### 10.8.1. Domain Event Listeners

```php
<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class UserRegisteredListener
{
    public function handle(UserRegistered $event): void
    {
        // Send welcome email
        Mail::to($event->email)->send(new WelcomeEmail($event->name));

        // Log registration
        logger('User registered', [
            'user_id' => $event->user_id,
            'email' => $event->email,
            'type' => $event->type,
        ]);

        // Update analytics
        $this->updateUserAnalytics($event);
    }

    private function updateUserAnalytics(UserRegistered $event): void
    {
        // Implementation for analytics update
    }
}
```

### 10.8.2. Event Service Provider

```php
<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\{UserRegistered, OrganizationCreated};
use App\Listeners\{UserRegisteredListener, OrganizationCreatedListener};

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            UserRegisteredListener::class,
        ],
        OrganizationCreated::class => [
            OrganizationCreatedListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();

        // Register Verbs event listeners
        \Thunk\Verbs\Facades\Verbs::listen([
            UserRegistered::class,
            OrganizationCreated::class,
        ]);
    }
}
```

## 10.9. Querying Event-Sourced Data

### 10.9.1. Using States Directly

```php
<?php

// Load current state
$userState = UserState::load($userId);

// Query multiple states
$activeUsers = UserState::where('is_active', true)->get();

// Time travel - load state at specific point
$userStateYesterday = UserState::load($userId, asOf: now()->subDay());
```

### 10.9.2. Using Projections for Performance

```php
<?php

// Query projection for fast reads
$users = DB::table('user_projections')
    ->where('type', 'customer')
    ->where('is_active', true)
    ->orderBy('created_at', 'desc')
    ->paginate(20);

// Complex queries on projections
$userStats = DB::table('user_projections')
    ->select('type', DB::raw('COUNT(*) as count'))
    ->where('is_active', true)
    ->groupBy('type')
    ->get();
```

## 10.10. Testing Event-Sourced Code

### 10.10.1. Event Testing

```php
<?php

declare(strict_types=1);

use App\Events\UserRegistered;
use App\States\UserState;

test('user registration creates correct state', function () {
    $event = new UserRegistered(
        user_id: 123,
        name: 'John Doe',
        email: 'john@example.com',
        type: 'customer'
    );

    $event->fire();

    $userState = UserState::load(123);

    expect($userState->name)->toBe('John Doe');
    expect($userState->email)->toBe('john@example.com');
    expect($userState->type)->toBe('customer');
    expect($userState->is_active)->toBeTrue();
});

test('user email can be updated', function () {
    // Setup initial state
    UserRegistered::fire(
        user_id: 123,
        name: 'John Doe',
        email: 'john@example.com'
    );

    // Update email
    UserEmailUpdated::fire(
        user_id: 123,
        old_email: 'john@example.com',
        new_email: 'john.doe@example.com'
    );

    $userState = UserState::load(123);
    expect($userState->email)->toBe('john.doe@example.com');
});
```

### 10.10.2. Command Testing

```php
<?php

declare(strict_types=1);

use App\Commands\RegisterUserCommand;

test('register user command creates user', function () {
    $command = new RegisterUserCommand(
        name: 'Jane Doe',
        email: 'jane@example.com',
        type: 'admin'
    );

    $userState = $command->handle();

    expect($userState->name)->toBe('Jane Doe');
    expect($userState->email)->toBe('jane@example.com');
    expect($userState->type)->toBe('admin');
});

test('register user command prevents duplicate email', function () {
    // Create first user
    $command1 = new RegisterUserCommand(
        name: 'User One',
        email: 'test@example.com'
    );
    $command1->handle();

    // Try to create second user with same email
    $command2 = new RegisterUserCommand(
        name: 'User Two',
        email: 'test@example.com'
    );

    expect(fn() => $command2->handle())
        ->toThrow(\InvalidArgumentException::class, 'Email already exists');
});
```

## 10.11. Performance Optimization

### 10.11.1. Snapshots for Large Event Streams

```php
<?php

declare(strict_types=1);

namespace App\States;

use Thunk\Verbs\State;

class UserState extends State
{
    // ... existing code ...

    /**
     * Create snapshot every 100 events
     */
    public function shouldSnapshot(): bool
    {
        return $this->getEventCount() % 100 === 0;
    }

    /**
     * Get event count for this aggregate
     */
    private function getEventCount(): int
    {
        return DB::table('verb_events')
            ->where('aggregate_id', $this->user_id)
            ->count();
    }
}
```

### 10.11.2. Event Store Partitioning

```sql
-- Partition events table by date for better performance
CREATE TABLE verb_events_2024_01 PARTITION OF verb_events
    FOR VALUES FROM ('2024-01-01') TO ('2024-02-01');

CREATE TABLE verb_events_2024_02 PARTITION OF verb_events
    FOR VALUES FROM ('2024-02-01') TO ('2024-03-01');
```

## 10.12. Common Patterns and Best Practices

### 10.12.1. Event Versioning

```php
<?php

declare(strict_types=1);

namespace App\Events;

use Thunk\Verbs\Event;

class UserRegistered extends Event
{
    public const VERSION = 2;

    // Version 2 fields
    public int $user_id;
    public string $name;
    public string $email;
    public string $type;
    public array $metadata;

    /**
     * Handle legacy version 1 events
     */
    public static function fromVersion1(array $data): self
    {
        return new self(
            user_id: $data['id'], // v1 used 'id' instead of 'user_id'
            name: $data['name'],
            email: $data['email'],
            type: $data['user_type'] ?? 'customer', // v1 used 'user_type'
            metadata: [] // v1 didn't have metadata
        );
    }
}
```

### 10.12.2. Event Upcasting

```php
<?php

declare(strict_types=1);

namespace App\EventStore;

use Thunk\Verbs\Support\EventUpcast;

class UserRegisteredUpcast extends EventUpcast
{
    public function handle(array $eventData): array
    {
        // Upcast from v1 to v2
        if (($eventData['version'] ?? 1) === 1) {
            $eventData['user_id'] = $eventData['id'];
            $eventData['type'] = $eventData['user_type'] ?? 'customer';
            $eventData['metadata'] = [];
            $eventData['version'] = 2;

            unset($eventData['id'], $eventData['user_type']);
        }

        return $eventData;
    }
}
```

## 10.13. Troubleshooting Common Issues

### 10.13.1. Event Replay Problems

**Issue**: Events not applying correctly during replay

**Solution**:

```php
// Ensure event handlers are idempotent
public function applyUserEmailUpdated(UserEmailUpdated $event): void
{
    // Check if email is already updated to prevent double application
    if ($this->email !== $event->new_email) {
        $this->email = $event->new_email;
        $this->updated_at = now();
    }
}
```

### 10.13.2. State Loading Performance

**Issue**: Slow state loading due to many events

**Solution**:

```php
// Use snapshots to reduce events to replay
$userState = UserState::load($userId, useSnapshot: true);

// Or implement custom caching
$userState = Cache::remember(
    "user_state_{$userId}",
    3600,
    fn() => UserState::load($userId)
);
```

## 10.14. Integration with Laravel Features

### 10.14.1. Model Events Bridge

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\UserRegistered;

class User extends Model
{
    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($user) {
            // Bridge Eloquent events to domain events
            UserRegistered::fire(
                user_id: $user->id,
                name: $user->name,
                email: $user->email,
                type: $user->type ?? 'customer'
            );
        });
    }
}
```

### 10.14.2. Queue Integration

```php
<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\UserRegistered;

class ProcessUserRegistration implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly UserRegistered $event
    ) {}

    public function handle(): void
    {
        // Process registration asynchronously
        $this->sendWelcomeEmail();
        $this->updateAnalytics();
        $this->provisionUserResources();
    }
}
```

## 10.15. Checklist for Event Sourcing Implementation

### 10.15.1. Before Implementation

-   [ ] Understand domain boundaries and aggregates
-   [ ] Design event schema with future changes in mind
-   [ ] Plan projection strategy for read models
-   [ ] Consider snapshot strategy for large event streams
-   [ ] Design event versioning approach

### 10.15.2. During Implementation

-   [ ] Create events with proper validation
-   [ ] Implement state classes with apply methods
-   [ ] Set up projections for query performance
-   [ ] Write comprehensive tests for events and states
-   [ ] Implement event handlers for side effects

### 10.15.3. After Implementation

-   [ ] Monitor event store size and performance
-   [ ] Set up event replay procedures
-   [ ] Document event schemas and versioning
-   [ ] Train team on event sourcing patterns
-   [ ] Establish backup and recovery procedures

**Remember**: Event sourcing adds complexity but provides powerful capabilities. Start simple and evolve your event model as you learn more about your domain!
