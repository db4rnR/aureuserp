# Phase 1: Todo Aggregate

**Version:** 1.1.0 **Date:** 2023-11-13 **Author:** AI Assistant **Status:** Complete **Progress:** 100%

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Todo Aggregate Structure](#todo-aggregate-structure)
  - [State Properties](#state-properties)
  - [Todo States](#todo-states)
- [Todo Commands](#todo-commands)
  - [CreateTodo Command](#createtodo-command)
  - [UpdateTodo Command](#updatetodo-command)
  - [AssignTodo Command](#assigntodo-command)
  - [StartTodo Command](#starttodo-command)
  - [CompleteTodo Command](#completetodo-command)
  - [CancelTodo Command](#canceltodo-command)
- [Todo Events](#todo-events)
  - [TodoCreated Event](#todocreated-event)
  - [TodoUpdated Event](#todoupdated-event)
  - [TodoAssigned Event](#todoassigned-event)
  - [TodoStarted Event](#todostarted-event)
  - [TodoCompleted Event](#todocompleted-event)
  - [TodoCancelled Event](#todocancelled-event)
- [Todo Aggregate Implementation](#todo-aggregate-implementation)
  - [Command Methods](#command-methods)
  - [Apply Methods](#apply-methods)
  - [Business Rules](#business-rules)
- [Integration with Teams and Users](#integration-with-teams-and-users)
  - [Team Integration](#team-integration)
  - [User Assignment](#user-assignment)
- [State Transitions](#state-transitions)
  - [State Diagram](#state-diagram)
  - [Transition Rules](#transition-rules)
- [Command Handlers](#command-handlers)
  - [CreateTodoCommandHandler](#createtodocommandhandler)
  - [UpdateTodoCommandHandler](#updatetodocommandhandler)
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

This document provides detailed documentation on the Todo aggregate in the event sourcing implementation for the
Enhanced Laravel Application (ELA). The Todo aggregate is responsible for managing todo creation, updates, assignment,
and status changes. This document covers the commands, events, state transitions, and business rules for the Todo
aggregate.

## Prerequisites

- **Required Prior Steps:**

  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [User Aggregate](020-010-user-aggregate.md)
  - [Team Aggregate](020-020-team-aggregate.md)
  - [CQRS Configuration](../030-core-components/030-cqrs-configuration.md)
  - [Package Installation](../030-core-components/010-package-installation.md)

- **Required Packages:**

  - `spatie/laravel-event-sourcing`: ^7.0
  - `hirethunk/verbs`: ^1.0
  - `spatie/laravel-data`: ^3.0
  - `spatie/laravel-model-states`: ^2.0

- **Required Knowledge:**

  - Understanding of event sourcing principles
  - Familiarity with task management systems
  - Understanding of state machines

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task                                | Estimated Time |
| ----------------------------------- | -------------- |
| Setting up Todo aggregate structure | 1 hour         |
| Implementing Todo commands          | 1 hour         |
| Implementing Todo events            | 1 hour         |
| Implementing command methods        | 2 hours        |
| Implementing apply methods          | 1 hour         |
| Integrating with teams and users    | 1 hour         |
| Testing Todo aggregate              | 2 hours        |
| **Total**                           | **9 hours**    |

</details>

## Todo Aggregate Structure

### State Properties

The Todo aggregate maintains the following state properties:

```php
protected string $title;
protected string $description;
protected string $state;
protected ?string $userId = null;
protected string $teamId;
protected ?string $dueDate = null;
protected int $priority = 0;
protected array $tags = [];
protected ?string $completedAt = null;
protected ?string $cancelledAt = null;
protected ?string $cancelReason = null;
```text

### Todo States

The Todo aggregate can be in one of the following states:

1. **Pending**: Todo is created but not started
2. **InProgress**: Todo is in progress
3. **Completed**: Todo is completed
4. **Cancelled**: Todo is cancelled

These states are implemented using `spatie/laravel-model-states` and are integrated with the event sourcing system.

## Todo Commands

<details>
<summary>Todo Command Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[CreateTodo Command] --> B[CreateTodoCommandHandler]
    B --> C[TodoAggregateRoot]
    C --> D{Valid?}
    D -->|Yes| E[Record TodoCreated Event]
    D -->|No| F[Throw Exception]
    E --> G[Apply TodoCreated Event]
    G --> H[Update Todo State]
    E --> I[Event Store]
    I --> J[TodoProjector]
    J --> K[Todo Model]
```php
For dark mode, see [Todo Command Flow (Dark Mode)](../../illustrations/mermaid/dark/todo-command-flow-dark.mmd)

</details>

### CreateTodo Command

Creates a new todo.

```php
<?php

namespace App\Commands\Todos;

use Hirethunk\Verbs\Command;

class CreateTodoCommand extends Command
{
    public function __construct(
        public string $title,
        public string $description,
        public string $teamId,
        public ?string $userId = null,
        public ?string $dueDate = null,
        public int $priority = 0,
        public array $tags = []
    ) {}

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'teamId' => ['required', 'string', 'exists:teams,id'],
            'userId' => ['nullable', 'string', 'exists:users,id'],
            'dueDate' => ['nullable', 'date'],
            'priority' => ['integer', 'min:0', 'max:10'],
            'tags' => ['sometimes', 'array'],
        ];
    }
}
```text

### UpdateTodo Command

Updates todo information.

```php
<?php

namespace App\Commands\Todos;

use Hirethunk\Verbs\Command;

class UpdateTodoCommand extends Command
{
    public function __construct(
        public string $todoId,
        public string $title,
        public string $description,
        public ?string $dueDate = null,
        public int $priority = 0,
        public array $tags = []
    ) {}

    public function rules(): array
    {
        return [
            'todoId' => ['required', 'string', 'exists:todos,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'dueDate' => ['nullable', 'date'],
            'priority' => ['integer', 'min:0', 'max:10'],
            'tags' => ['sometimes', 'array'],
        ];
    }
}
```php
### AssignTodo Command

Assigns a todo to a user.

```php
<?php

namespace App\Commands\Todos;

use Hirethunk\Verbs\Command;

class AssignTodoCommand extends Command
{
    public function __construct(
        public string $todoId,
        public ?string $userId = null
    ) {}

    public function rules(): array
    {
        return [
            'todoId' => ['required', 'string', 'exists:todos,id'],
            'userId' => ['nullable', 'string', 'exists:users,id'],
        ];
    }
}
```text

### StartTodo Command

Marks a todo as in progress.

```php
<?php

namespace App\Commands\Todos;

use Hirethunk\Verbs\Command;

class StartTodoCommand extends Command
{
    public function __construct(
        public string $todoId
    ) {}

    public function rules(): array
    {
        return [
            'todoId' => ['required', 'string', 'exists:todos,id'],
        ];
    }
}
```php
### CompleteTodo Command

Marks a todo as completed.

```php
<?php

namespace App\Commands\Todos;

use Hirethunk\Verbs\Command;

class CompleteTodoCommand extends Command
{
    public function __construct(
        public string $todoId
    ) {}

    public function rules(): array
    {
        return [
            'todoId' => ['required', 'string', 'exists:todos,id'],
        ];
    }
}
```text

### CancelTodo Command

Cancels a todo.

```php
<?php

namespace App\Commands\Todos;

use Hirethunk\Verbs\Command;

class CancelTodoCommand extends Command
{
    public function __construct(
        public string $todoId,
        public ?string $reason = null
    ) {}

    public function rules(): array
    {
        return [
            'todoId' => ['required', 'string', 'exists:todos,id'],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
```php
## Todo Events

### TodoCreated Event

Represents a todo creation event.

```php
<?php

namespace App\Events\Todos;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TodoCreated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:

- `title`: Todo title
- `description`: Todo description
- `team_id`: ID of the team
- `user_id`: ID of the assigned user (if any)
- `due_date`: Due date (if any)
- `priority`: Priority level
- `tags`: Todo tags
- `created_at`: Creation timestamp

### TodoUpdated Event

Represents a todo update event.

```php
<?php

namespace App\Events\Todos;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TodoUpdated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:

- `title`: Updated todo title
- `description`: Updated todo description
- `due_date`: Updated due date
- `priority`: Updated priority level
- `tags`: Updated todo tags
- `updated_at`: Update timestamp

### TodoAssigned Event

Represents a todo assignment event.

```php
<?php

namespace App\Events\Todos;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TodoAssigned extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:

- `user_id`: ID of the assigned user (or null if unassigned)
- `assigned_at`: Assignment timestamp

### TodoStarted Event

Represents a todo start event.

```php
<?php

namespace App\Events\Todos;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TodoStarted extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:

- `started_at`: Start timestamp

### TodoCompleted Event

Represents a todo completion event.

```php
<?php

namespace App\Events\Todos;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TodoCompleted extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:

- `completed_at`: Completion timestamp

### TodoCancelled Event

Represents a todo cancellation event.

```php
<?php

namespace App\Events\Todos;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TodoCancelled extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:

- `reason`: Cancellation reason
- `cancelled_at`: Cancellation timestamp

## Todo Aggregate Implementation

### Command Methods

The Todo aggregate implements methods to handle various commands:

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Events\Todos\TodoCreated;
use App\Events\Todos\TodoUpdated;
use App\Events\Todos\TodoAssigned;
use App\Events\Todos\TodoStarted;
use App\Events\Todos\TodoCompleted;
use App\Events\Todos\TodoCancelled;
use App\States\Todo\Pending;
use App\States\Todo\InProgress;
use App\States\Todo\Completed;
use App\States\Todo\Cancelled;
use App\Exceptions\Todos\InvalidTodoStateTransitionException;

class TodoAggregateRoot extends AggregateRoot
{
    protected string $title;
    protected string $description;
    protected string $state;
    protected ?string $userId = null;
    protected string $teamId;
    protected ?string $dueDate = null;
    protected int $priority = 0;
    protected array $tags = [];
    protected ?string $completedAt = null;
    protected ?string $cancelledAt = null;
    protected ?string $cancelReason = null;

    public function createTodo(
        string $title,
        string $description,
        string $teamId,
        ?string $userId = null,
        ?string $dueDate = null,
        int $priority = 0,
        array $tags = []
    ): self {
        $this->recordThat(new TodoCreated([
            'title' => $title,
            'description' => $description,
            'team_id' => $teamId,
            'user_id' => $userId,
            'due_date' => $dueDate,
            'priority' => $priority,
            'tags' => $tags,
            'created_at' => now(),
        ]));

        return $this;
    }

    public function updateTodo(
        string $title,
        string $description,
        ?string $dueDate = null,
        int $priority = 0,
        array $tags = []
    ): self {
        if (in_array($this->state, [Completed::class, Cancelled::class])) {
            throw new InvalidTodoStateTransitionException(
                "Cannot update a completed or cancelled todo"
            );
        }

        $this->recordThat(new TodoUpdated([
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate,
            'priority' => $priority,
            'tags' => $tags,
            'updated_at' => now(),
        ]));

        return $this;
    }

    public function assignTodo(?string $userId = null): self
    {
        if (in_array($this->state, [Completed::class, Cancelled::class])) {
            throw new InvalidTodoStateTransitionException(
                "Cannot assign a completed or cancelled todo"
            );
        }

        $this->recordThat(new TodoAssigned([
            'user_id' => $userId,
            'assigned_at' => now(),
        ]));

        return $this;
    }

    public function startTodo(): self
    {
        if ($this->state !== Pending::class) {
            throw new InvalidTodoStateTransitionException(
                "Cannot start a todo that is not in pending state"
            );
        }

        $this->recordThat(new TodoStarted([
            'started_at' => now(),
        ]));

        return $this;
    }

    public function completeTodo(): self
    {
        if (in_array($this->state, [Completed::class, Cancelled::class])) {
            throw new InvalidTodoStateTransitionException(
                "Cannot complete a todo that is already completed or cancelled"
            );
        }

        $this->recordThat(new TodoCompleted([
            'completed_at' => now(),
        ]));

        return $this;
    }

    public function cancelTodo(?string $reason = null): self
    {
        if (in_array($this->state, [Completed::class, Cancelled::class])) {
            throw new InvalidTodoStateTransitionException(
                "Cannot cancel a todo that is already completed or cancelled"
            );
        }

        $this->recordThat(new TodoCancelled([
            'reason' => $reason,
            'cancelled_at' => now(),
        ]));

        return $this;
    }
}
```text

### Apply Methods

The Todo aggregate implements apply methods to update its state based on events:

```php
protected function applyTodoCreated(TodoCreated $event): void
{
    $this->title = $event->payload['title'];
    $this->description = $event->payload['description'];
    $this->teamId = $event->payload['team_id'];
    $this->userId = $event->payload['user_id'];
    $this->dueDate = $event->payload['due_date'];
    $this->priority = $event->payload['priority'];
    $this->tags = $event->payload['tags'];
    $this->state = Pending::class;
}

protected function applyTodoUpdated(TodoUpdated $event): void
{
    $this->title = $event->payload['title'];
    $this->description = $event->payload['description'];
    $this->dueDate = $event->payload['due_date'];
    $this->priority = $event->payload['priority'];
    $this->tags = $event->payload['tags'];
}

protected function applyTodoAssigned(TodoAssigned $event): void
{
    $this->userId = $event->payload['user_id'];
}

protected function applyTodoStarted(TodoStarted $event): void
{
    $this->state = InProgress::class;
}

protected function applyTodoCompleted(TodoCompleted $event): void
{
    $this->state = Completed::class;
    $this->completedAt = $event->payload['completed_at'];
}

protected function applyTodoCancelled(TodoCancelled $event): void
{
    $this->state = Cancelled::class;
    $this->cancelledAt = $event->payload['cancelled_at'];
    $this->cancelReason = $event->payload['reason'];
}
```php
### Business Rules

The Todo aggregate enforces several business rules:

1. **State Transitions**: Only certain state transitions are allowed

   - Pending → InProgress, Completed, Cancelled
   - InProgress → Completed, Cancelled
   - Completed → (no transitions allowed)
   - Cancelled → (no transitions allowed)

2. **Todo Updates**: Completed or cancelled todos cannot be updated

3. **Todo Assignment**: Completed or cancelled todos cannot be assigned

4. **Todo Completion**: Only pending or in-progress todos can be completed

5. **Todo Cancellation**: Only pending or in-progress todos can be cancelled

## Integration with Teams and Users

### Team Integration

Todos are always associated with a team:

```php
public function createTodo(
    string $title,
    string $description,
    string $teamId,
    ?string $userId = null,
    ?string $dueDate = null,
    int $priority = 0,
    array $tags = []
): self {
    $this->recordThat(new TodoCreated([
        'title' => $title,
        'description' => $description,
        'team_id' => $teamId,
        'user_id' => $userId,
        'due_date' => $dueDate,
        'priority' => $priority,
        'tags' => $tags,
        'created_at' => now(),
    ]));

    return $this;
}
```text

### User Assignment

Todos can be assigned to a user or left unassigned:

```php
public function assignTodo(?string $userId = null): self
{
    if (in_array($this->state, [Completed::class, Cancelled::class])) {
        throw new InvalidTodoStateTransitionException(
            "Cannot assign a completed or cancelled todo"
        );
    }

    $this->recordThat(new TodoAssigned([
        'user_id' => $userId,
        'assigned_at' => now(),
    ]));

    return $this;
}
```text
## State Transitions

### State Diagram

<details>
<summary>Todo State Transitions Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
stateDiagram-v2
    [*] --> Pending: createTodo
    Pending --> InProgress: startTodo
    Pending --> Completed: completeTodo
    Pending --> Cancelled: cancelTodo
    InProgress --> Completed: completeTodo
    InProgress --> Cancelled: cancelTodo
    Completed --> [*]
    Cancelled --> [*]
```text

For a more detailed diagram, see
[Todo Aggregate States (Light Mode)](../../illustrations/mermaid/light/todo-aggregate-states-light.mmd)

For dark mode versions, see:

- [Todo State Transitions (Dark Mode)](../../illustrations/mermaid/dark/todo-state-transitions-dark.mmd)
- [Todo Aggregate States (Dark Mode)](../../illustrations/mermaid/dark/todo-aggregate-states-dark.mmd)
</details>

The Todo aggregate supports the following state transitions:

```bash
Pending → InProgress
Pending → Completed
Pending → Cancelled
InProgress → Completed
InProgress → Cancelled
```text

### Transition Rules

State transitions are enforced by the Todo aggregate's command methods:

```php
public function startTodo(): self
{
    if ($this->state !== Pending::class) {
        throw new InvalidTodoStateTransitionException(
            "Cannot start a todo that is not in pending state"
        );
    }

    $this->recordThat(new TodoStarted([
        'started_at' => now(),
    ]));

    return $this;
}
```php
## Command Handlers

### CreateTodoCommandHandler

Handles todo creation:

```php
<?php

namespace App\CommandHandlers\Todos;

use App\Commands\Todos\CreateTodoCommand;
use App\Aggregates\TodoAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use Illuminate\Support\Str;

class CreateTodoCommandHandler extends CommandHandler
{
    public function handle(CreateTodoCommand $command)
    {
        // Generate a UUID for the todo
        $todoId = (string) Str::uuid();

        // Create the todo
        TodoAggregateRoot::retrieve($todoId)
            ->createTodo(
                $command->title,
                $command->description,
                $command->teamId,
                $command->userId,
                $command->dueDate,
                $command->priority,
                $command->tags
            )
            ->persist();

        return $todoId;
    }
}
```text

### UpdateTodoCommandHandler

Handles todo updates:

```php
<?php

namespace App\CommandHandlers\Todos;

use App\Commands\Todos\UpdateTodoCommand;
use App\Aggregates\TodoAggregateRoot;
use Hirethunk\Verbs\CommandHandler;

class UpdateTodoCommandHandler extends CommandHandler
{
    public function handle(UpdateTodoCommand $command)
    {
        // Authorize the command
        $this->authorize('update', ['App\Models\Todo', $command->todoId]);

        // Update the todo
        TodoAggregateRoot::retrieve($command->todoId)
            ->updateTodo(
                $command->title,
                $command->description,
                $command->dueDate,
                $command->priority,
                $command->tags
            )
            ->persist();

        return $this->success();
    }
}
```

### Other Command Handlers

Similar command handlers exist for other todo commands:

- `AssignTodoCommandHandler`
- `StartTodoCommandHandler`
- `CompleteTodoCommandHandler`
- `CancelTodoCommandHandler`

## Benefits and Challenges

### Benefits

1. **Complete Audit Trail**: Every todo action is recorded as an event
2. **State Management**: Clear state transitions with enforced rules
3. **Task History**: Complete history of todo changes and assignments
4. **Temporal Queries**: The state of a todo at any point in time can be reconstructed

### Challenges

1. **Complexity**: Event sourcing adds complexity to the task management system
2. **Performance**: Reconstructing todo state from events can be slow for todos with many events
3. **Integration**: Integrating with teams and users requires careful planning

### Mitigation Strategies

1. **Snapshots**: Use snapshots to improve performance for todos with many events
2. **Caching**: Cache todo projections to improve read performance
3. **Clear Documentation**: Document the todo aggregate thoroughly to help developers understand the system

## Troubleshooting

### Common Issues

<details>
<summary>Todo state not updating correctly</summary>

**Symptoms:**

- Todo state is not reflecting the expected state after a command
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
<summary>Todo assignment issues</summary>

**Symptoms:**

- Todos are not being assigned correctly
- Assignment changes are not reflected in the UI

**Possible Causes:**

- Incorrect implementation of assignment methods
- Missing apply methods for assignment events
- Projector not updating the todo model correctly

**Solutions:**

1. Ensure assignment methods are implemented correctly
2. Add apply methods for assignment events
3. Verify that the projector is updating the todo model correctly
</details>

### Solutions

For detailed solutions to common issues, refer to the
[Event Sourcing Troubleshooting Guide](070-testing.md#troubleshooting).

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [User Aggregate](020-010-user-aggregate.md) - Detailed documentation on User aggregate
- [Team Aggregate](020-020-team-aggregate.md) - Detailed documentation on Team aggregate
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing State Machines](080-state-machines.md) - Integration of event sourcing with state machines

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date       | Changes                                                                                            | Author       |
| ------- | ---------- | -------------------------------------------------------------------------------------------------- | ------------ |
| 1.1.0   | 2025-05-18 | Added todo state transitions diagram, command flow diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0   | 2025-05-18 | Initial version                                                                                    | AI Assistant |

</details>
