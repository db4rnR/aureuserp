# Phase 1: User Aggregate

**Version:** 1.1.0
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Complete
**Progress:** 100%

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Estimated Time Requirements](#estimated-time-requirements)
- [User Aggregate Structure](#user-aggregate-structure)
  - [State Properties](#state-properties)
  - [User States](#user-states)
- [User Commands](#user-commands)
  - [RegisterUser Command](#registeruser-command)
  - [ActivateUser Command](#activateuser-command)
  - [UpdateUserProfile Command](#updateuserprofile-command)
  - [ChangeUserEmail Command](#changeuseremail-command)
  - [DeactivateUser Command](#deactivateuser-command)
  - [SuspendUser Command](#suspenduser-command)
  - [UnsuspendUser Command](#unsuspenduser-command)
  - [ArchiveUser Command](#archiveuser-command)
- [User Events](#user-events)
  - [UserRegistered Event](#userregistered-event)
  - [UserActivated Event](#useractivated-event)
  - [UserProfileUpdated Event](#userprofileupdated-event)
  - [UserEmailChanged Event](#useremailchanged-event)
  - [UserDeactivated Event](#userdeactivated-event)
  - [UserSuspended Event](#usersuspended-event)
  - [UserUnsuspended Event](#userunsuspended-event)
  - [UserArchived Event](#userarchived-event)
- [User Aggregate Implementation](#user-aggregate-implementation)
  - [Command Methods](#command-methods)
  - [Apply Methods](#apply-methods)
  - [Business Rules](#business-rules)
- [Integration with Authentication](#integration-with-authentication)
  - [User Registration](#user-registration)
  - [User Login](#user-login)
  - [Password Reset](#password-reset)
- [State Transitions](#state-transitions)
  - [State Diagram](#state-diagram)
  - [Transition Rules](#transition-rules)
- [Command Handlers](#command-handlers)
  - [RegisterUserCommandHandler](#registerusercommandhandler)
  - [ActivateUserCommandHandler](#activateusercommandhandler)
  - [Other Command Handlers](#other-command-handlers)
- [Benefits and Challenges](#benefits-and-challenges)
  - [Benefits](#benefits)
  - [Challenges](#challenges)
  - [Mitigation Strategies](#mitigation-strategies)
- [Troubleshooting](#troubleshooting)
  - [Common Issues](#common-issues)
  - [Solutions](#solutions)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides detailed documentation on the User aggregate in the event sourcing implementation for the Enhanced Laravel Application (ELA). The User aggregate is responsible for managing user registration, profile updates, account status changes, and other user-related operations. This document covers the commands, events, state transitions, and business rules for the User aggregate.

## Prerequisites

- **Required Prior Steps:**
  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [CQRS Configuration](../030-core-components/030-cqrs-configuration.md)
  - [Package Installation](../030-core-components/010-package-installation.md)

- **Required Packages:**
  - `spatie/laravel-event-sourcing`: ^7.0
  - `hirethunk/verbs`: ^1.0
  - `spatie/laravel-data`: ^3.0
  - `spatie/laravel-model-states`: ^2.0

- **Required Knowledge:**
  - Understanding of event sourcing principles
  - Familiarity with Laravel's authentication system
  - Understanding of state machines

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Setting up User aggregate structure | 1 hour |
| Implementing User commands | 2 hours |
| Implementing User events | 1 hour |
| Implementing command methods | 2 hours |
| Implementing apply methods | 1 hour |
| Integrating with authentication | 2 hours |
| Testing User aggregate | 2 hours |
| **Total** | **11 hours** |
</details>

## User Aggregate Structure

### State Properties

The User aggregate maintains the following state properties:

```php
protected string $name;
protected string $email;
protected array $profile = [];
protected string $state;
protected ?string $suspensionReason = null;
protected ?string $deactivationReason = null;
protected ?string $archiveReason = null;
```text

### User States

The User aggregate can be in one of the following states:

1. **Invited**: User has been invited but has not registered
2. **PendingActivation**: User has registered but not activated their account
3. **Active**: User has an active account
4. **Suspended**: User account has been temporarily suspended
5. **Deactivated**: User has deactivated their account
6. **Archived**: User account has been archived

These states are implemented using `spatie/laravel-model-states` and are integrated with the event sourcing system.

## User Commands

### RegisterUser Command

Registers a new user in the system.

```php
<?php

namespace App\Commands\Users;

use Hirethunk\Verbs\Command;

class RegisterUserCommand extends Command
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public array $profile = []
    ) {}

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'profile' => ['sometimes', 'array'],
        ];
    }
}
```php
### ActivateUser Command

Activates a user account after registration.

```php
<?php

namespace App\Commands\Users;

use Hirethunk\Verbs\Command;

class ActivateUserCommand extends Command
{
    public function __construct(
        public string $userId,
        public string $activationToken
    ) {}

    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'exists:users,id'],
            'activationToken' => ['required', 'string'],
        ];
    }
}
```text

### UpdateUserProfile Command

Updates a user's profile information.

```php
<?php

namespace App\Commands\Users;

use Hirethunk\Verbs\Command;

class UpdateUserProfileCommand extends Command
{
    public function __construct(
        public string $userId,
        public string $name,
        public array $profile = []
    ) {}

    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'profile' => ['sometimes', 'array'],
        ];
    }
}
```php
### ChangeUserEmail Command

Changes a user's email address.

```php
<?php

namespace App\Commands\Users;

use Hirethunk\Verbs\Command;

class ChangeUserEmailCommand extends Command
{
    public function __construct(
        public string $userId,
        public string $email,
        public string $password
    ) {}

    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'exists:users,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string'],
        ];
    }
}
```text

### DeactivateUser Command

Deactivates a user account.

```php
<?php

namespace App\Commands\Users;

use Hirethunk\Verbs\Command;

class DeactivateUserCommand extends Command
{
    public function __construct(
        public string $userId,
        public ?string $reason = null
    ) {}

    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'exists:users,id'],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
```php
### SuspendUser Command

Suspends a user account.

```php
<?php

namespace App\Commands\Users;

use Hirethunk\Verbs\Command;

class SuspendUserCommand extends Command
{
    public function __construct(
        public string $userId,
        public string $reason,
        public ?int $durationInDays = null
    ) {}

    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'exists:users,id'],
            'reason' => ['required', 'string', 'max:255'],
            'durationInDays' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
```text

### UnsuspendUser Command

Removes a suspension from a user account.

```php
<?php

namespace App\Commands\Users;

use Hirethunk\Verbs\Command;

class UnsuspendUserCommand extends Command
{
    public function __construct(
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```php
### ArchiveUser Command

Archives a user account.

```php
<?php

namespace App\Commands\Users;

use Hirethunk\Verbs\Command;

class ArchiveUserCommand extends Command
{
    public function __construct(
        public string $userId,
        public string $reason
    ) {}

    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'exists:users,id'],
            'reason' => ['required', 'string', 'max:255'],
        ];
    }
}
```text

## User Events

### UserRegistered Event

Represents a user registration event.

```php
<?php

namespace App\Events\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserRegistered extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `name`: User's name
- `email`: User's email address
- `profile`: User's profile information
- `registered_at`: Registration timestamp

### UserActivated Event

Represents a user activation event.

```php
<?php

namespace App\Events\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserActivated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `activated_at`: Activation timestamp

### UserProfileUpdated Event

Represents a user profile update event.

```php
<?php

namespace App\Events\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserProfileUpdated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `name`: Updated name
- `profile`: Updated profile information
- `updated_at`: Update timestamp

### UserEmailChanged Event

Represents a user email change event.

```php
<?php

namespace App\Events\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserEmailChanged extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `old_email`: Previous email address
- `new_email`: New email address
- `changed_at`: Change timestamp

### UserDeactivated Event

Represents a user deactivation event.

```php
<?php

namespace App\Events\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserDeactivated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `reason`: Deactivation reason
- `deactivated_at`: Deactivation timestamp

### UserSuspended Event

Represents a user suspension event.

```php
<?php

namespace App\Events\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserSuspended extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `reason`: Suspension reason
- `duration_in_days`: Suspension duration (optional)
- `suspended_at`: Suspension timestamp
- `suspended_until`: End of suspension timestamp (optional)

### UserUnsuspended Event

Represents a user unsuspension event.

```php
<?php

namespace App\Events\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserUnsuspended extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `unsuspended_at`: Unsuspension timestamp

### UserArchived Event

Represents a user archival event.

```php
<?php

namespace App\Events\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserArchived extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `reason`: Archival reason
- `archived_at`: Archival timestamp

## User Aggregate Implementation

### Command Methods

The User aggregate implements methods to handle various commands:

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Events\Users\UserRegistered;
use App\Events\Users\UserActivated;
use App\Events\Users\UserProfileUpdated;
use App\Events\Users\UserEmailChanged;
use App\Events\Users\UserDeactivated;
use App\Events\Users\UserSuspended;
use App\Events\Users\UserUnsuspended;
use App\Events\Users\UserArchived;
use App\States\User\PendingActivation;
use App\States\User\Active;
use App\States\User\Suspended;
use App\States\User\Deactivated;
use App\States\User\Archived;
use App\Exceptions\Users\InvalidUserStateTransitionException;

class UserAggregateRoot extends AggregateRoot
{
    protected string $name;
    protected string $email;
    protected array $profile = [];
    protected string $state;
    protected ?string $suspensionReason = null;
    protected ?string $deactivationReason = null;
    protected ?string $archiveReason = null;

    public function registerUser(string $name, string $email, array $profile = []): self
    {
        $this->recordThat(new UserRegistered([
            'name' => $name,
            'email' => $email,
            'profile' => $profile,
            'registered_at' => now(),
        ]));

        return $this;
    }

    public function activateUser(): self
    {
        if ($this->state !== PendingActivation::class) {
            throw new InvalidUserStateTransitionException(
                "Cannot activate user that is not in PendingActivation state"
            );
        }

        $this->recordThat(new UserActivated([
            'activated_at' => now(),
        ]));

        return $this;
    }

    public function updateUserProfile(string $name, array $profile = []): self
    {
        if ($this->state !== Active::class) {
            throw new InvalidUserStateTransitionException(
                "Cannot update profile of user that is not in Active state"
            );
        }

        $this->recordThat(new UserProfileUpdated([
            'name' => $name,
            'profile' => $profile,
            'updated_at' => now(),
        ]));

        return $this;
    }

    public function changeUserEmail(string $email): self
    {
        if ($this->state !== Active::class) {
            throw new InvalidUserStateTransitionException(
                "Cannot change email of user that is not in Active state"
            );
        }

        $this->recordThat(new UserEmailChanged([
            'old_email' => $this->email,
            'new_email' => $email,
            'changed_at' => now(),
        ]));

        return $this;
    }

    public function deactivateUser(?string $reason = null): self
    {
        if ($this->state !== Active::class) {
            throw new InvalidUserStateTransitionException(
                "Cannot deactivate user that is not in Active state"
            );
        }

        $this->recordThat(new UserDeactivated([
            'reason' => $reason,
            'deactivated_at' => now(),
        ]));

        return $this;
    }

    public function suspendUser(string $reason, ?int $durationInDays = null): self
    {
        if ($this->state !== Active::class) {
            throw new InvalidUserStateTransitionException(
                "Cannot suspend user that is not in Active state"
            );
        }

        $suspendedUntil = $durationInDays ? now()->addDays($durationInDays) : null;

        $this->recordThat(new UserSuspended([
            'reason' => $reason,
            'duration_in_days' => $durationInDays,
            'suspended_at' => now(),
            'suspended_until' => $suspendedUntil,
        ]));

        return $this;
    }

    public function unsuspendUser(): self
    {
        if ($this->state !== Suspended::class) {
            throw new InvalidUserStateTransitionException(
                "Cannot unsuspend user that is not in Suspended state"
            );
        }

        $this->recordThat(new UserUnsuspended([
            'unsuspended_at' => now(),
        ]));

        return $this;
    }

    public function archiveUser(string $reason): self
    {
        if (!in_array($this->state, [Active::class, Suspended::class, Deactivated::class])) {
            throw new InvalidUserStateTransitionException(
                "Cannot archive user that is not in Active, Suspended, or Deactivated state"
            );
        }

        $this->recordThat(new UserArchived([
            'reason' => $reason,
            'archived_at' => now(),
        ]));

        return $this;
    }
}
```php
### Apply Methods

The User aggregate implements apply methods to update its state based on events:

```php
protected function applyUserRegistered(UserRegistered $event): void
{
    $this->name = $event->payload['name'];
    $this->email = $event->payload['email'];
    $this->profile = $event->payload['profile'];
    $this->state = PendingActivation::class;
}

protected function applyUserActivated(UserActivated $event): void
{
    $this->state = Active::class;
}

protected function applyUserProfileUpdated(UserProfileUpdated $event): void
{
    $this->name = $event->payload['name'];
    $this->profile = $event->payload['profile'];
}

protected function applyUserEmailChanged(UserEmailChanged $event): void
{
    $this->email = $event->payload['new_email'];
}

protected function applyUserDeactivated(UserDeactivated $event): void
{
    $this->state = Deactivated::class;
    $this->deactivationReason = $event->payload['reason'];
}

protected function applyUserSuspended(UserSuspended $event): void
{
    $this->state = Suspended::class;
    $this->suspensionReason = $event->payload['reason'];
}

protected function applyUserUnsuspended(UserUnsuspended $event): void
{
    $this->state = Active::class;
    $this->suspensionReason = null;
}

protected function applyUserArchived(UserArchived $event): void
{
    $this->state = Archived::class;
    $this->archiveReason = $event->payload['reason'];
}
```text

### Business Rules

The User aggregate enforces several business rules:

1. **State Transitions**: Only certain state transitions are allowed
   - PendingActivation → Active
   - Active → Suspended, Deactivated, Archived
   - Suspended → Active, Archived
   - Deactivated → Archived

2. **Email Uniqueness**: Email addresses must be unique (enforced at the command validation level)

3. **Profile Updates**: Only active users can update their profiles

4. **Email Changes**: Only active users can change their email addresses

5. **Suspension**: Only active users can be suspended

6. **Archival**: Users can be archived from Active, Suspended, or Deactivated states

## Integration with Authentication

### User Registration

The User aggregate integrates with Laravel's authentication system for user registration:

```php
<?php

namespace App\CommandHandlers\Users;

use App\Commands\Users\RegisterUserCommand;
use App\Aggregates\UserAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterUserCommandHandler extends CommandHandler
{
    public function handle(RegisterUserCommand $command)
    {
        // Generate a UUID for the user
        $userId = (string) Str::uuid();

        // Create the user in the authentication system
        User::create([
            'id' => $userId,
            'name' => $command->name,
            'email' => $command->email,
            'password' => Hash::make($command->password),
        ]);

        // Record the event in the event sourcing system
        UserAggregateRoot::retrieve($userId)
            ->registerUser(
                $command->name,
                $command->email,
                $command->profile
            )
            ->persist();

        return $userId;
    }
}
```php
### User Login

User login uses Laravel's standard authentication system, which reads from the user projection:

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\States\User\Active;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if user is active
            if ($user->state->equals(new Active)) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            }

            // Log out if user is not active
            Auth::logout();

            return back()->withErrors([
                'email' => 'Your account is not active.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
```text

### Password Reset

Password reset uses Laravel's standard password reset functionality, which works with the user projection.

## State Transitions

### State Diagram

<details>
<summary>User State Transitions Diagram</summary>

This diagram illustrates the state transitions for the User aggregate in the Enhanced Laravel Application (ELA). It shows the possible states a user can be in and the valid transitions between these states.

![User Aggregate States](../../illustrations/thumbnails/mermaid/light/user-aggregate-states-light-thumb.svg)

For the full diagram, see:
- [User Aggregate States (Light Mode)](../../illustrations/mermaid/light/user-aggregate-states-light.mmd)
- [User Aggregate States (Dark Mode)](../../illustrations/mermaid/dark/user-aggregate-states-dark.mmd)
</details>

The User aggregate supports the following state transitions:

```bash
PendingActivation → Active
Active → Suspended
Active → Deactivated
Active → Archived
Suspended → Active (unsuspend)
Suspended → Archived
Deactivated → Archived
```text

### Transition Rules

State transitions are enforced by the User aggregate's command methods:

```php
public function activateUser(): self
{
    if ($this->state !== PendingActivation::class) {
        throw new InvalidUserStateTransitionException(
            "Cannot activate user that is not in PendingActivation state"
        );
    }

    $this->recordThat(new UserActivated([
        'activated_at' => now(),
    ]));

    return $this;
}
```php
## Command Handlers

### RegisterUserCommandHandler

Handles user registration:

```php
<?php

namespace App\CommandHandlers\Users;

use App\Commands\Users\RegisterUserCommand;
use App\Aggregates\UserAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterUserCommandHandler extends CommandHandler
{
    public function handle(RegisterUserCommand $command)
    {
        // Generate a UUID for the user
        $userId = (string) Str::uuid();

        // Create the user in the authentication system
        User::create([
            'id' => $userId,
            'name' => $command->name,
            'email' => $command->email,
            'password' => Hash::make($command->password),
        ]);

        // Record the event in the event sourcing system
        UserAggregateRoot::retrieve($userId)
            ->registerUser(
                $command->name,
                $command->email,
                $command->profile
            )
            ->persist();

        return $userId;
    }
}
```text

### ActivateUserCommandHandler

Handles user activation:

```php
<?php

namespace App\CommandHandlers\Users;

use App\Commands\Users\ActivateUserCommand;
use App\Aggregates\UserAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use App\Models\User;
use App\Exceptions\Users\InvalidActivationTokenException;

class ActivateUserCommandHandler extends CommandHandler
{
    public function handle(ActivateUserCommand $command)
    {
        $user = User::findOrFail($command->userId);

        // Verify activation token
        if (!$this->verifyActivationToken($user, $command->activationToken)) {
            throw new InvalidActivationTokenException("Invalid activation token");
        }

        // Activate the user
        UserAggregateRoot::retrieve($command->userId)
            ->activateUser()
            ->persist();

        return $this->success();
    }

    protected function verifyActivationToken(User $user, string $token): bool
    {
        // Implementation depends on how activation tokens are stored and verified
        // This is a placeholder
        return true;
    }
}
```

### Other Command Handlers

Similar command handlers exist for other user commands:

- `UpdateUserProfileCommandHandler`
- `ChangeUserEmailCommandHandler`
- `DeactivateUserCommandHandler`
- `SuspendUserCommandHandler`
- `UnsuspendUserCommandHandler`
- `ArchiveUserCommandHandler`

## Benefits and Challenges

### Benefits

1. **Complete Audit Trail**: Every user action is recorded as an event
2. **State Management**: Clear state transitions with enforced rules
3. **Temporal Queries**: The state of a user at any point in time can be reconstructed
4. **Separation of Concerns**: Clear separation between write and read models

### Challenges

1. **Complexity**: Event sourcing adds complexity to the user management system
2. **Performance**: Reconstructing user state from events can be slow for users with many events
3. **Integration**: Integrating with Laravel's authentication system requires careful planning

### Mitigation Strategies

1. **Snapshots**: Use snapshots to improve performance for users with many events
2. **Caching**: Cache user projections to improve read performance
3. **Clear Documentation**: Document the user aggregate thoroughly to help developers understand the system

## Troubleshooting

### Common Issues

<details>
<summary>User state not updating correctly</summary>

**Symptoms:**
- User state is not reflecting the expected state after a command
- State transitions are not working as expected

**Possible Causes:**
- Missing apply methods
- Incorrect state transition logic
- Events not being persisted

**Solutions:**
1. Ensure all apply methods are implemented correctly
2. Verify state transition logic in command methods
3. Check that events are being persisted with `persist()`
</details>

<details>
<summary>Authentication issues after state changes</summary>

**Symptoms:**
- Users cannot log in after state changes
- Authentication behaves unexpectedly

**Possible Causes:**
- Projector not updating the user model correctly
- State not being properly reflected in the user model
- Authentication middleware not checking user state

**Solutions:**
1. Ensure projectors update the user model correctly
2. Add state checks to authentication middleware
3. Verify that the user model's state field is properly cast
</details>

### Solutions

For detailed solutions to common issues, refer to the [Event Sourcing Troubleshooting Guide](070-testing.md#troubleshooting).

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [Team Aggregate](020-020-team-aggregate.md) - Detailed documentation on Team aggregate
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing State Machines](080-state-machines.md) - Integration of event sourcing with state machines
- [Event Sourcing Roles and Permissions](090-roles-permissions.md) - Integration of event sourcing with roles and permissions

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added user state transitions diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
