# Phase 1: Event Sourcing Aggregates

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
- [Aggregate Concept](#aggregate-concept)
  - [What is an Aggregate?](#what-is-an-aggregate)
  - [Aggregate Responsibilities](#aggregate-responsibilities)
  - [Aggregate Boundaries](#aggregate-boundaries)
  - [Aggregate Identity](#aggregate-identity)
- [Implementing Aggregates](#implementing-aggregates)
  - [Base Aggregate Structure](#base-aggregate-structure)
  - [Command Methods](#command-methods)
  - [Apply Methods](#apply-methods)
  - [State Management](#state-management)
- [Integration with spatie/laravel-event-sourcing](#integration-with-spatielaravel-event-sourcing)
  - [AggregateRoot Class](#aggregateroot-class)
  - [Retrieving and Persisting Aggregates](#retrieving-and-persisting-aggregates)
  - [Recording Events](#recording-events)
  - [Applying Events](#applying-events)
- [Integration with hirethunk/verbs](#integration-with-hirethunkverbs)
  - [Command Handling](#command-handling)
  - [Command Validation](#command-validation)
  - [Command Authorization](#command-authorization)
- [Relationship Between Aggregates, Commands, and Events](#relationship-between-aggregates-commands-and-events)
  - [Command to Aggregate](#command-to-aggregate)
  - [Aggregate to Event](#aggregate-to-event)
  - [Event to State](#event-to-state)
- [Common Patterns and Best Practices](#common-patterns-and-best-practices)
  - [Single Responsibility](#single-responsibility)
  - [Immutability](#immutability)
  - [Idempotency](#idempotency)
  - [Concurrency Control](#concurrency-control)
  - [Snapshots](#snapshots)
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

This document provides a comprehensive guide to implementing aggregates in event sourcing for the Enhanced Laravel Application (ELA). Aggregates are the central domain objects in event sourcing that handle commands, enforce business rules, and generate events. This document covers the concept of aggregates, their implementation using `spatie/laravel-event-sourcing` and `hirethunk/verbs`, and best practices for designing effective aggregates.

## Prerequisites

- **Required Prior Steps:**
  - [CQRS Configuration](../030-core-components/030-cqrs-configuration.md)
  - [Package Installation](../030-core-components/010-package-installation.md) (specifically `spatie/laravel-event-sourcing` and `hirethunk/verbs`)

- **Required Packages:**
  - `spatie/laravel-event-sourcing`: ^7.0
  - `hirethunk/verbs`: ^1.0
  - `spatie/laravel-data`: ^3.0

- **Required Knowledge:**
  - Basic understanding of Domain-Driven Design (DDD)
  - Familiarity with CQRS pattern
  - Understanding of event sourcing principles

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Understanding aggregate concepts | 2 hours |
| Setting up base aggregate structure | 1 hour |
| Implementing command methods | 2 hours per aggregate |
| Implementing apply methods | 1 hour per aggregate |
| Testing aggregates | 2 hours per aggregate |
| Integration with command handlers | 1 hour per aggregate |
| **Total** | **7+ hours per aggregate** |
</details>

## Aggregate Concept

### What is an Aggregate?

An aggregate is a cluster of domain objects that can be treated as a single unit. In event sourcing, an aggregate is responsible for:

1. Handling commands
2. Enforcing business rules
3. Generating events that represent state changes
4. Maintaining its internal state based on past events

Aggregates serve as the primary consistency boundary in the domain model, ensuring that business rules are consistently applied.

### Aggregate Responsibilities

Aggregates have several key responsibilities:

1. **Command Handling**: Receiving and processing commands from the application layer
2. **Business Rule Enforcement**: Validating that commands comply with business rules
3. **Event Generation**: Creating events that represent valid state changes
4. **State Management**: Maintaining internal state based on applied events

### Aggregate Boundaries

Defining appropriate aggregate boundaries is crucial for a well-designed event-sourced system:

- An aggregate should be a true business concept, not just a data structure
- Aggregates should be as small as possible while still maintaining consistency
- Cross-aggregate consistency should be eventually consistent, not immediate

### Aggregate Identity

Each aggregate instance has a unique identifier, typically a UUID. This identifier:

- Remains constant throughout the aggregate's lifecycle
- Is used to retrieve the aggregate's event stream
- Serves as a reference for relationships between aggregates

## Implementing Aggregates

### Base Aggregate Structure

In the ELA, aggregates are implemented as classes that extend `Spatie\EventSourcing\AggregateRoots\AggregateRoot`:

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class TodoAggregateRoot extends AggregateRoot
{
    // State properties
    protected string $title;
    protected string $description;
    protected int $userId;
    protected int $teamId;
    protected bool $isCompleted = false;

    // Command methods

    // Apply methods
}
```text

### Command Methods

Command methods handle incoming commands and record events:

```php
public function createTodo(string $title, string $description, int $userId, int $teamId): self
{
    $this->recordThat(new TodoCreated([
        'title' => $title,
        'description' => $description,
        'user_id' => $userId,
        'team_id' => $teamId,
        'created_at' => now(),
    ]));

    return $this;
}

public function completeTodo(): self
{
    // Business rule: Cannot complete an already completed todo
    if ($this->isCompleted) {
        throw new TodoAlreadyCompletedException("This todo is already completed");
    }

    $this->recordThat(new TodoCompleted([
        'completed_at' => now(),
    ]));

    return $this;
}
```php
### Apply Methods

Apply methods update the aggregate's internal state based on events:

```php
protected function applyTodoCreated(TodoCreated $event): void
{
    $this->title = $event->payload['title'];
    $this->description = $event->payload['description'];
    $this->userId = $event->payload['user_id'];
    $this->teamId = $event->payload['team_id'];
}

protected function applyTodoCompleted(TodoCompleted $event): void
{
    $this->isCompleted = true;
}
```text

### State Management

Aggregates maintain their state by applying events. This state is used to enforce business rules when handling commands:

```php
public function assignTodo(int $userId): self
{
    // Business rule: Cannot assign a completed todo
    if ($this->isCompleted) {
        throw new CannotAssignCompletedTodoException();
    }

    $this->recordThat(new TodoAssigned([
        'user_id' => $userId,
        'assigned_at' => now(),
    ]));

    return $this;
}
```php
## Integration with spatie/laravel-event-sourcing

### AggregateRoot Class

The `AggregateRoot` class from `spatie/laravel-event-sourcing` provides the foundation for implementing aggregates:

```php
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class UserAggregateRoot extends AggregateRoot
{
    // Implementation
}
```text

### Retrieving and Persisting Aggregates

Aggregates are retrieved and persisted using static methods:

```php
// Retrieve an aggregate
$todoAggregate = TodoAggregateRoot::retrieve($uuid);

// Modify the aggregate
$todoAggregate->completeTodo();

// Persist the aggregate (saves new events)
$todoAggregate->persist();
```php
### Recording Events

Events are recorded using the `recordThat` method:

```php
$this->recordThat(new TodoCreated([
    'title' => $title,
    'description' => $description,
    'user_id' => $userId,
    'team_id' => $teamId,
]));
```text

### Applying Events

Events are automatically applied to the aggregate using methods with the naming convention `apply{EventName}`:

```php
protected function applyTodoCreated(TodoCreated $event): void
{
    $this->title = $event->payload['title'];
    $this->description = $event->payload['description'];
    $this->userId = $event->payload['user_id'];
    $this->teamId = $event->payload['team_id'];
}
```php
## Integration with hirethunk/verbs

### Command Handling

Commands from `hirethunk/verbs` are handled by command handlers that interact with aggregates:

```php
<?php

namespace App\CommandHandlers;

use App\Commands\CreateTodoCommand;
use App\Aggregates\TodoAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use Illuminate\Support\Str;

class CreateTodoCommandHandler extends CommandHandler
{
    public function handle(CreateTodoCommand $command)
    {
        $aggregateUuid = (string) Str::uuid();

        TodoAggregateRoot::retrieve($aggregateUuid)
            ->createTodo(
                $command->title,
                $command->description,
                $command->userId,
                $command->teamId
            )
            ->persist();

        return $aggregateUuid;
    }
}
```text

### Command Validation

Commands are validated before they reach the aggregate:

```php
<?php

namespace App\Commands;

use Hirethunk\Verbs\Command;

class CreateTodoCommand extends Command
{
    public function __construct(
        public string $title,
        public string $description,
        public int $userId,
        public int $teamId
    ) {}

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'userId' => ['required', 'integer', 'exists:users,id'],
            'teamId' => ['required', 'integer', 'exists:teams,id'],
        ];
    }
}
```php
### Command Authorization

Authorization can be implemented in command handlers or using Laravel's authorization system:

```php
public function handle(AssignTodoCommand $command)
{
    // Authorize the command
    $this->authorize('assign', [Todo::class, $command->todoId]);

    TodoAggregateRoot::retrieve($command->todoId)
        ->assignTodo($command->userId)
        ->persist();

    return $this->success();
}
```text

## Relationship Between Aggregates, Commands, and Events

<details>
<summary>Aggregate Lifecycle Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[Command] --> B[Command Handler]
    B --> C[Aggregate]
    C --> D{Valid?}
    D -->|Yes| E[Record Event]
    D -->|No| F[Throw Exception]
    E --> G[Apply Event]
    G --> H[Update State]
    E --> I[Event Store]
    I --> J[Projectors]
    I --> K[Reactors]
    J --> L[Read Models]
    K --> M[Side Effects]
```php
For dark mode, see [Aggregate Lifecycle (Dark Mode)](../../illustrations/mermaid/dark/aggregate-lifecycle-dark.mmd)
</details>

### Command to Aggregate

1. A command is dispatched by the application
2. A command handler retrieves the appropriate aggregate
3. The command handler calls a method on the aggregate
4. The aggregate validates the command against business rules

### Aggregate to Event

1. The aggregate determines that a state change is valid
2. The aggregate creates an event representing the state change
3. The aggregate records the event using `recordThat`
4. The event is applied to update the aggregate's internal state

### Event to State

1. The event is stored in the event store
2. The event is processed by projectors to update read models
3. The event may trigger side effects via reactors

## Common Patterns and Best Practices

### Single Responsibility

Each aggregate should focus on a single business concept:

- **User Aggregate**: Handles user registration, profile updates, and account status changes
- **Team Aggregate**: Manages team creation, member management, and team settings
- **Post Aggregate**: Controls post creation, publication, and content management
- **Todo Aggregate**: Manages todo creation, assignment, and completion

### Immutability

Events should be immutable once created:

```php
// Good: Creating a new event with all necessary data
$this->recordThat(new TodoCreated([
    'title' => $title,
    'description' => $description,
    'user_id' => $userId,
    'team_id' => $teamId,
    'created_at' => now(),
]));

// Bad: Modifying an event after creation
$event = new TodoCreated();
$event->title = $title; // Don't do this
$this->recordThat($event);
```text

### Idempotency

Command handlers should be idempotent to handle retries:

```php
public function handle(CompleteTodoCommand $command)
{
    $todoAggregate = TodoAggregateRoot::retrieve($command->todoId);

    // Check if already completed before attempting to complete
    if (!$todoAggregate->isCompleted()) {
        $todoAggregate->completeTodo()->persist();
    }

    return $this->success();
}
```php
### Concurrency Control

Handle concurrent modifications with version checking:

```php
public function handle(UpdateTodoCommand $command)
{
    $todoAggregate = TodoAggregateRoot::retrieve($command->todoId);

    // Check version to prevent lost updates
    if ($todoAggregate->aggregateVersion() !== $command->expectedVersion) {
        throw new ConcurrencyException("Todo was modified by another user");
    }

    $todoAggregate->updateTodo($command->title, $command->description)->persist();

    return $this->success();
}
```text

### Snapshots

For aggregates with many events, use snapshots to improve performance:

```php
// Configure snapshots in config/event-sourcing.php
'snapshot_repository' => \Spatie\EventSourcing\Snapshots\EloquentSnapshotRepository::class,
'snapshot_threshold' => 50,
```

## Benefits and Challenges

### Benefits

1. **Complete Audit Trail**: Every state change is recorded as an event
2. **Business Rule Enforcement**: Rules are centralized in aggregates
3. **Temporal Queries**: The state at any point in time can be reconstructed
4. **Separation of Concerns**: Clear separation between write and read models

### Challenges

1. **Learning Curve**: Event sourcing requires a different mindset
2. **Performance Considerations**: Reconstructing state from events can be slow for aggregates with many events
3. **Schema Evolution**: Changing event schemas requires careful planning
4. **Eventual Consistency**: Read models may lag behind write models

### Mitigation Strategies

1. **Snapshots**: Use snapshots to improve performance for aggregates with many events
2. **Event Versioning**: Implement versioning for events to handle schema changes
3. **Projector Optimization**: Optimize projectors for efficient read model updates
4. **Command Validation**: Validate commands thoroughly before they reach aggregates

## Troubleshooting

### Common Issues

<details>
<summary>Events not being stored</summary>

**Symptoms:**
- Events are not appearing in the event store
- Aggregates are not being updated

**Possible Causes:**
- Event store configuration issues
- Missing event classes
- Incorrect aggregate implementation

**Solutions:**
1. Verify the event store configuration in `config/event-sourcing.php`
2. Ensure event classes are properly defined and extend `Spatie\EventSourcing\StoredEvents\ShouldBeStored`
3. Check that aggregates are correctly implementing `Spatie\EventSourcing\AggregateRoots\AggregateRoot`
</details>

<details>
<summary>Apply methods not being called</summary>

**Symptoms:**
- Aggregate state is not being updated
- Business rules based on state are not working correctly

**Possible Causes:**
- Incorrect method naming
- Missing apply methods
- Type hints not matching event classes

**Solutions:**
1. Ensure apply methods follow the naming convention `apply{EventName}`
2. Add apply methods for all events
3. Check that type hints match the event classes exactly
</details>

<details>
<summary>Concurrency issues</summary>

**Symptoms:**
- Lost updates when multiple users modify the same aggregate
- Unexpected exceptions during high traffic

**Possible Causes:**
- No concurrency control
- Race conditions in command handling

**Solutions:**
1. Implement version checking in command handlers
2. Use optimistic concurrency control
3. Consider using command queues for sequential processing
</details>

### Solutions

For detailed solutions to common issues, refer to the [Event Sourcing Troubleshooting Guide](070-testing.md#troubleshooting).

## Related Documents

- [User Aggregate](020-010-user-aggregate.md) - Detailed documentation on User aggregate
- [Team Aggregate](020-020-team-aggregate.md) - Detailed documentation on Team aggregate
- [Post Aggregate](020-030-post-aggregate.md) - Detailed documentation on Post aggregate
- [Todo Aggregate](020-040-todo-aggregate.md) - Detailed documentation on Todo aggregate
- [Comment Aggregate](020-050-comment-aggregate.md) - Detailed documentation on Comment aggregate
- [Message Aggregate](020-060-message-aggregate.md) - Detailed documentation on Message aggregate
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing Reactors](040-reactors.md) - Detailed documentation on reactor implementation
- [Event Sourcing Implementation](050-implementation.md) - Overview of event sourcing implementation

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added aggregate lifecycle diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
