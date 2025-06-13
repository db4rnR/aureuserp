# Phase 1: Event Sourcing State Machines

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
- [State Machine Concept](#state-machine-concept)
  - [What is a State Machine?](#what-is-a-state-machine)
  - [State Machine Responsibilities](#state-machine-responsibilities)
  - [State Machine Types](#state-machine-types)
- [Integration with spatie/laravel-model-states](#integration-with-spatielaravel-model-states)
  - [State Classes](#state-classes)
  - [State Transitions](#state-transitions)
  - [Transition Validation](#transition-validation)
- [Implementing State Machines](#implementing-state-machines)
  - [Base State Structure](#base-state-structure)
  - [Transition Classes](#transition-classes)
  - [Transition Validation](#transition-validation-1)
- [Integration with Event Sourcing](#integration-with-event-sourcing)
  - [States in Aggregates](#states-in-aggregates)
  - [States in Projections](#states-in-projections)
  - [State Transitions in Commands](#state-transitions-in-commands)
- [State Machine Examples](#state-machine-examples)
  - [User State Machine](#user-state-machine)
  - [Team State Machine](#team-state-machine)
  - [Post State Machine](#post-state-machine)
  - [Todo State Machine](#todo-state-machine)
  - [Comment State Machine](#comment-state-machine)
- [Common Patterns and Best Practices](#common-patterns-and-best-practices)
  - [State Validation](#state-validation)
  - [Transition Logging](#transition-logging)
  - [State History](#state-history)
  - [State Visualization](#state-visualization)
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

This document provides a comprehensive guide to implementing state machines in event sourcing for the Enhanced Laravel Application (ELA). State machines are used to model and enforce the lifecycle of aggregates, ensuring that they transition between states in a controlled and predictable manner. This document covers the concept of state machines, their implementation using `spatie/laravel-model-states`, and integration with event sourcing.

## Prerequisites

- **Required Prior Steps:**
  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [Event Sourcing Projectors](030-projectors.md)
  - [Package Installation](../030-core-components/010-package-installation.md) (specifically `spatie/laravel-model-states`)

- **Required Packages:**
  - `spatie/laravel-event-sourcing`: ^7.0
  - `spatie/laravel-model-states`: ^2.0

- **Required Knowledge:**
  - Understanding of event sourcing principles
  - Familiarity with state machines
  - Understanding of Laravel Eloquent ORM

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Understanding state machine concepts | 2 hours |
| Setting up base state structure | 1 hour |
| Implementing state classes | 1 hour per aggregate |
| Implementing transition classes | 1 hour per aggregate |
| Testing state machines | 1 hour per aggregate |
| **Total** | **4+ hours per aggregate** |
</details>

## State Machine Concept

### What is a State Machine?

A state machine is a mathematical model of computation that describes the behavior of a system in terms of states, transitions between states, and actions. In the context of event sourcing, state machines are used to:

1. Define the possible states of an aggregate
2. Define the allowed transitions between states
3. Enforce business rules during state transitions
4. Provide a clear and explicit model of the aggregate lifecycle

State machines help ensure that aggregates transition between states in a controlled and predictable manner, preventing invalid state transitions and enforcing business rules.

### State Machine Responsibilities

State machines have several key responsibilities:

1. **State Definition**: Define the possible states of an aggregate
2. **Transition Definition**: Define the allowed transitions between states
3. **Validation**: Validate that transitions are allowed based on the current state
4. **Action Execution**: Execute actions during state transitions

### State Machine Types

There are several types of state machines that can be implemented:

1. **Finite State Machines (FSM)**: A finite set of states with transitions between them
2. **Hierarchical State Machines**: States can contain nested states
3. **Statecharts**: Extended state machines with additional features like history, parallel states, and guards

In the ELA, we primarily use Finite State Machines (FSM) implemented using `spatie/laravel-model-states`.

## Integration with spatie/laravel-model-states

### State Classes

State classes are implemented using `spatie/laravel-model-states`:

```php
<?php

namespace App\States\User;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class UserState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(PendingActivation::class)
            ->allowTransition(PendingActivation::class, Active::class)
            ->allowTransition(Active::class, Suspended::class)
            ->allowTransition(Active::class, Deactivated::class)
            ->allowTransition(Suspended::class, Active::class)
            ->allowTransition(Deactivated::class, Active::class)
            ->allowTransition([Active::class, Suspended::class, Deactivated::class], Archived::class);
    }
}
```text

### State Transitions

State transitions are defined in the state config:

```php
public static function config(): StateConfig
{
    return parent::config()
        ->default(PendingActivation::class)
        ->allowTransition(PendingActivation::class, Active::class)
        ->allowTransition(Active::class, Suspended::class)
        ->allowTransition(Active::class, Deactivated::class)
        ->allowTransition(Suspended::class, Active::class)
        ->allowTransition(Deactivated::class, Active::class)
        ->allowTransition([Active::class, Suspended::class, Deactivated::class], Archived::class);
}
```php
### Transition Validation

Transitions can be validated using transition classes:

```php
<?php

namespace App\States\User\Transitions;

use App\States\User\Active;
use App\States\User\PendingActivation;
use Spatie\ModelStates\Transition;

class ActivateUser extends Transition
{
    private string $activationCode;

    public function __construct(string $activationCode)
    {
        $this->activationCode = $activationCode;
    }

    public function handle(): Active
    {
        // Validate activation code
        if ($this->activationCode !== $this->model->activation_code) {
            throw new InvalidActivationCodeException('Invalid activation code');
        }

        // Clear activation code
        $this->model->activation_code = null;
        $this->model->save();

        return new Active();
    }
}
```text

## Implementing State Machines

### Base State Structure

In the ELA, state machines are implemented using a base state class and concrete state classes:

```php
<?php

namespace App\States\User;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class UserState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(PendingActivation::class)
            ->allowTransition(PendingActivation::class, Active::class)
            ->allowTransition(Active::class, Suspended::class)
            ->allowTransition(Active::class, Deactivated::class)
            ->allowTransition(Suspended::class, Active::class)
            ->allowTransition(Deactivated::class, Active::class)
            ->allowTransition([Active::class, Suspended::class, Deactivated::class], Archived::class);
    }
}
```php
Concrete state classes:

```php
<?php

namespace App\States\User;

class PendingActivation extends UserState
{
    public static $name = 'pending_activation';
}

class Active extends UserState
{
    public static $name = 'active';
}

class Suspended extends UserState
{
    public static $name = 'suspended';
}

class Deactivated extends UserState
{
    public static $name = 'deactivated';
}

class Archived extends UserState
{
    public static $name = 'archived';
}
```text

### Transition Classes

Transition classes handle the logic of transitioning from one state to another:

```php
<?php

namespace App\States\User\Transitions;

use App\States\User\Active;
use App\States\User\Suspended;
use Spatie\ModelStates\Transition;

class SuspendUser extends Transition
{
    private string $reason;
    private ?string $suspendedUntil;

    public function __construct(string $reason, ?string $suspendedUntil = null)
    {
        $this->reason = $reason;
        $this->suspendedUntil = $suspendedUntil;
    }

    public function handle(): Suspended
    {
        // Record suspension reason and duration
        $this->model->suspension_reason = $this->reason;
        $this->model->suspended_until = $this->suspendedUntil;
        $this->model->save();

        return new Suspended();
    }
}
```php
### Transition Validation

Transitions can be validated to ensure they are allowed:

```php
<?php

namespace App\States\User\Transitions;

use App\States\User\Active;
use App\States\User\Archived;
use App\Exceptions\Users\CannotArchiveActiveUserException;
use Spatie\ModelStates\Transition;

class ArchiveUser extends Transition
{
    private string $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    public function canTransition(): bool
    {
        // Check if user has active subscriptions
        if ($this->model->subscriptions()->active()->exists()) {
            return false;
        }

        return true;
    }

    public function handle(): Archived
    {
        // Check if transition is allowed
        if (!$this->canTransition()) {
            throw new CannotArchiveActiveUserException('Cannot archive user with active subscriptions');
        }

        // Record archival reason
        $this->model->archival_reason = $this->reason;
        $this->model->save();

        return new Archived();
    }
}
```text

## Integration with Event Sourcing

### States in Aggregates

States are used in aggregates to enforce business rules:

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Events\Users\UserRegistered;
use App\Events\Users\UserActivated;
use App\Events\Users\UserSuspended;
use App\States\User\PendingActivation;
use App\States\User\Active;
use App\States\User\Suspended;
use App\Exceptions\Users\InvalidUserStateTransitionException;

class UserAggregateRoot extends AggregateRoot
{
    protected string $name;
    protected string $email;
    protected array $profile;
    protected string $state;

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
                "Cannot activate a user that is not in pending activation state"
            );
        }

        $this->recordThat(new UserActivated([
            'activated_at' => now(),
        ]));

        return $this;
    }

    public function suspendUser(string $reason, ?string $suspendedUntil = null): self
    {
        if ($this->state !== Active::class) {
            throw new InvalidUserStateTransitionException(
                "Cannot suspend a user that is not active"
            );
        }

        $this->recordThat(new UserSuspended([
            'reason' => $reason,
            'suspended_until' => $suspendedUntil,
            'suspended_at' => now(),
        ]));

        return $this;
    }

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

    protected function applyUserSuspended(UserSuspended $event): void
    {
        $this->state = Suspended::class;
    }
}
```php
### States in Projections

States are used in projections to update read models:

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\Users\UserRegistered;
use App\Events\Users\UserActivated;
use App\Events\Users\UserSuspended;
use App\Models\User;
use App\States\User\PendingActivation;
use App\States\User\Active;
use App\States\User\Suspended;

class UserProjector extends Projector
{
    public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
    {
        User::create([
            'id' => $aggregateUuid,
            'name' => $event->payload['name'],
            'email' => $event->payload['email'],
            'profile' => $event->payload['profile'],
            'state' => PendingActivation::class,
        ]);
    }

    public function onUserActivated(UserActivated $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);
        $user->state = new Active();
        $user->save();
    }

    public function onUserSuspended(UserSuspended $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);
        $user->state = new Suspended();
        $user->suspension_reason = $event->payload['reason'];
        $user->suspended_until = $event->payload['suspended_until'];
        $user->save();
    }
}
```text

### State Transitions in Commands

State transitions are triggered by commands:

```php
<?php

namespace App\CommandHandlers\Users;

use App\Commands\Users\ActivateUserCommand;
use App\Aggregates\UserAggregateRoot;
use Hirethunk\Verbs\CommandHandler;

class ActivateUserCommandHandler extends CommandHandler
{
    public function handle(ActivateUserCommand $command)
    {
        // Retrieve the user aggregate
        $aggregate = UserAggregateRoot::retrieve($command->userId);

        // Activate the user
        $aggregate->activateUser();

        // Persist the events
        $aggregate->persist();

        return $this->success();
    }
}
```php
## State Machine Examples

### User State Machine

```php
<?php

namespace App\States\User;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class UserState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(PendingActivation::class)
            ->allowTransition(PendingActivation::class, Active::class)
            ->allowTransition(Active::class, Suspended::class)
            ->allowTransition(Active::class, Deactivated::class)
            ->allowTransition(Suspended::class, Active::class)
            ->allowTransition(Deactivated::class, Active::class)
            ->allowTransition([Active::class, Suspended::class, Deactivated::class], Archived::class);
    }
}

class PendingActivation extends UserState
{
    public static $name = 'pending_activation';
}

class Active extends UserState
{
    public static $name = 'active';
}

class Suspended extends UserState
{
    public static $name = 'suspended';
}

class Deactivated extends UserState
{
    public static $name = 'deactivated';
}

class Archived extends UserState
{
    public static $name = 'archived';
}
```text

User model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\States\User\UserState;

class User extends Model
{
    use HasStates;

    protected $casts = [
        'state' => UserState::class,
        'profile' => 'array',
    ];

    protected $fillable = [
        'id',
        'name',
        'email',
        'profile',
        'state',
    ];
}
```php
### Team State Machine

```php
<?php

namespace App\States\Team;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class TeamState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Forming::class)
            ->allowTransition(Forming::class, Active::class)
            ->allowTransition(Active::class, Archived::class);
    }
}

class Forming extends TeamState
{
    public static $name = 'forming';
}

class Active extends TeamState
{
    public static $name = 'active';
}

class Archived extends TeamState
{
    public static $name = 'archived';
}
```text

Team model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\States\Team\TeamState;

class Team extends Model
{
    use HasStates;

    protected $casts = [
        'state' => TeamState::class,
        'settings' => 'array',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'settings',
        'state',
    ];
}
```php
### Post State Machine

```php
<?php

namespace App\States\Post;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class PostState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition(Draft::class, PendingReview::class)
            ->allowTransition(Draft::class, Published::class)
            ->allowTransition(Draft::class, Scheduled::class)
            ->allowTransition(PendingReview::class, Published::class)
            ->allowTransition(PendingReview::class, Draft::class)
            ->allowTransition(Published::class, Draft::class)
            ->allowTransition(Scheduled::class, Published::class)
            ->allowTransition(Scheduled::class, Draft::class)
            ->allowTransition([Draft::class, PendingReview::class, Published::class, Scheduled::class], Archived::class);
    }
}

class Draft extends PostState
{
    public static $name = 'draft';
}

class PendingReview extends PostState
{
    public static $name = 'pending_review';
}

class Published extends PostState
{
    public static $name = 'published';
}

class Scheduled extends PostState
{
    public static $name = 'scheduled';
}

class Archived extends PostState
{
    public static $name = 'archived';
}
```text

Post model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\States\Post\PostState;

class Post extends Model
{
    use HasStates;

    protected $casts = [
        'state' => PostState::class,
        'meta' => 'array',
    ];

    protected $fillable = [
        'id',
        'title',
        'slug',
        'content',
        'excerpt',
        'author_id',
        'team_id',
        'meta',
        'state',
        'published_at',
        'scheduled_at',
    ];
}
```php
### Todo State Machine

```php
<?php

namespace App\States\Todo;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class TodoState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, InProgress::class)
            ->allowTransition(Pending::class, Completed::class)
            ->allowTransition(Pending::class, Cancelled::class)
            ->allowTransition(InProgress::class, Completed::class)
            ->allowTransition(InProgress::class, Cancelled::class);
    }
}

class Pending extends TodoState
{
    public static $name = 'pending';
}

class InProgress extends TodoState
{
    public static $name = 'in_progress';
}

class Completed extends TodoState
{
    public static $name = 'completed';
}

class Cancelled extends TodoState
{
    public static $name = 'cancelled';
}
```text

Todo model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\States\Todo\TodoState;

class Todo extends Model
{
    use HasStates;

    protected $casts = [
        'state' => TodoState::class,
    ];

    protected $fillable = [
        'id',
        'title',
        'description',
        'team_id',
        'user_id',
        'due_date',
        'priority',
        'state',
        'completed_at',
    ];
}
```php
### Comment State Machine

```php
<?php

namespace App\States\Comment;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class CommentState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Approved::class)
            ->allowTransition(Pending::class, Rejected::class)
            ->allowTransition(Pending::class, Deleted::class)
            ->allowTransition(Approved::class, Deleted::class)
            ->allowTransition(Rejected::class, Deleted::class);
    }
}

class Pending extends CommentState
{
    public static $name = 'pending';
}

class Approved extends CommentState
{
    public static $name = 'approved';
}

class Rejected extends CommentState
{
    public static $name = 'rejected';
}

class Deleted extends CommentState
{
    public static $name = 'deleted';
}
```text

Comment model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\States\Comment\CommentState;

class Comment extends Model
{
    use HasStates;

    protected $casts = [
        'state' => CommentState::class,
    ];

    protected $fillable = [
        'id',
        'content',
        'user_id',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'state',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];
}
```php
## Common Patterns and Best Practices

### State Validation

Validate state transitions to ensure they are allowed:

```php
public function activateUser(): self
{
    if ($this->state !== PendingActivation::class) {
        throw new InvalidUserStateTransitionException(
            "Cannot activate a user that is not in pending activation state"
        );
    }

    $this->recordThat(new UserActivated([
        'activated_at' => now(),
    ]));

    return $this;
}
```text

### Transition Logging

Log state transitions for auditing purposes:

```php
public function onUserSuspended(UserSuspended $event, string $aggregateUuid)
{
    $user = User::findOrFail($aggregateUuid);
    $user->state = new Suspended();
    $user->suspension_reason = $event->payload['reason'];
    $user->suspended_until = $event->payload['suspended_until'];
    $user->save();

    // Log the state transition
    Log::info("User suspended: {$user->email}", [
        'reason' => $event->payload['reason'],
        'suspended_until' => $event->payload['suspended_until'],
    ]);
}
```php
### State History

Track state history for auditing purposes:

```php
public function onUserSuspended(UserSuspended $event, string $aggregateUuid)
{
    $user = User::findOrFail($aggregateUuid);
    $user->state = new Suspended();
    $user->suspension_reason = $event->payload['reason'];
    $user->suspended_until = $event->payload['suspended_until'];
    $user->save();

    // Record state history
    $user->stateHistory()->create([
        'from_state' => Active::class,
        'to_state' => Suspended::class,
        'reason' => $event->payload['reason'],
        'transitioned_at' => now(),
    ]);
}
```text

### State Visualization

Visualize state machines for documentation purposes:

<details>
<summary>User State Machine Visualization</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
stateDiagram-v2
    [*] --> PendingActivation
    PendingActivation --> Active: activate
    Active --> Suspended: suspend
    Active --> Deactivated: deactivate
    Suspended --> Active: unsuspend
    Deactivated --> Active: reactivate
    Active --> Archived: archive
    Suspended --> Archived: archive
    Deactivated --> Archived: archive
    Archived --> [*]
```text
For dark mode, see [User State Machine (Dark Mode)](../../illustrations/mermaid/dark/user-state-machine-dark.mmd)
</details>

<details>
<summary>Team State Machine Visualization</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
stateDiagram-v2
    [*] --> Forming
    Forming --> Active: activate
    Active --> Archived: archive
    Archived --> [*]
```text

For dark mode, see [Team State Machine (Dark Mode)](../../illustrations/mermaid/dark/team-state-machine-dark.mmd)
</details>

<details>
<summary>Post State Machine Visualization</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
stateDiagram-v2
    [*] --> Draft
    Draft --> PendingReview: submit
    Draft --> Published: publish
    Draft --> Scheduled: schedule
    PendingReview --> Published: approve
    PendingReview --> Draft: reject
    Published --> Draft: unpublish
    Scheduled --> Published: publish
    Scheduled --> Draft: unschedule
    Draft --> Archived: archive
    PendingReview --> Archived: archive
    Published --> Archived: archive
    Scheduled --> Archived: archive
    Archived --> [*]
```

For dark mode, see [Post State Machine (Dark Mode)](../../illustrations/mermaid/dark/post-state-machine-dark.mmd)
</details>

## Benefits and Challenges

### Benefits

1. **Explicit State Modeling**: State machines provide an explicit model of the aggregate lifecycle
2. **Business Rule Enforcement**: State machines enforce business rules during state transitions
3. **Validation**: State machines validate that transitions are allowed based on the current state
4. **Documentation**: State machines provide clear documentation of the aggregate lifecycle

### Challenges

1. **Complexity**: State machines add complexity to the codebase
2. **Maintenance**: State machines require maintenance as business rules evolve
3. **Integration**: Integrating state machines with event sourcing requires careful planning

### Mitigation Strategies

1. **Clear Documentation**: Document state machines thoroughly to help developers understand the system
2. **Visualization**: Visualize state machines for documentation purposes
3. **Testing**: Thoroughly test state machines to ensure they behave as expected
4. **Refactoring**: Refactor state machines as business rules evolve

## Troubleshooting

### Common Issues

<details>
<summary>Invalid state transitions</summary>

**Symptoms:**
- Exceptions about invalid state transitions
- Aggregates not transitioning to the expected state

**Possible Causes:**
- Missing state transition definitions
- Incorrect state validation
- Incorrect state application in aggregates

**Solutions:**
1. Ensure state transitions are defined correctly in the state config
2. Verify state validation in command methods
3. Check that state is applied correctly in apply methods
</details>

<details>
<summary>State not persisting</summary>

**Symptoms:**
- State changes not being persisted to the database
- State reverting to previous value after save

**Possible Causes:**
- Missing state cast in model
- Incorrect state application in projectors
- Database constraints or validation errors

**Solutions:**
1. Ensure state is cast correctly in the model
2. Verify state application in projector event handlers
3. Check database constraints and validation rules
</details>

<details>
<summary>State history not being recorded</summary>

**Symptoms:**
- State history not being recorded
- Missing state transitions in audit logs

**Possible Causes:**
- Missing state history recording in projectors
- Incorrect state history model
- Database constraints or validation errors

**Solutions:**
1. Ensure state history is recorded in projector event handlers
2. Verify state history model is defined correctly
3. Check database constraints and validation rules
</details>

### Solutions

For detailed solutions to common issues, refer to the following resources:

- [Spatie Laravel Model States Documentation](https:/spatie.be/docs/laravel-model-states)
- [Spatie Event Sourcing Documentation](https:/spatie.be/docs/laravel-event-sourcing)
- [State Machine Patterns](https:/refactoring.guru/design-patterns/state)

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing Testing](070-testing.md) - Detailed documentation on testing event-sourced applications

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added state machine visualizations for User, Team, and Post, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
