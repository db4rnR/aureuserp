# Phase 1.5: Event Sourcing Implementation

**Version:** 1.1.0
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Complete
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
  - [Required Prior Steps](#required-prior-steps)
  - [Required Packages](#required-packages)
  - [Required Knowledge](#required-knowledge)
  - [Required Environment](#required-environment)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Current Architecture Analysis](#current-architecture-analysis)
- [Proposed Event Sourcing Implementation](#proposed-event-sourcing-implementation)
  - [Core Concepts and Components](#1-core-concepts-and-components)
  - [Integration with Existing Architecture](#2-integration-with-existing-architecture)
  - [Event Store Configuration](#3-event-store-configuration)
  - [Integration with State Machines](#4-integration-with-state-machines)
  - [Rebuilding Projections](#5-rebuilding-projections)
  - [Implementation Plan Updates](#6-implementation-plan-updates)
- [Benefits of This Approach](#benefits-of-this-approach)
- [Potential Challenges](#potential-challenges)
- [Recommendations](#recommendations)
- [Conclusion](#conclusion)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

Event sourcing is a powerful architectural pattern where the state of your application is determined by a sequence of events rather than just the current state. This document outlines how to implement event sourcing in the Enhanced Laravel Application (ELA) based on the current architecture and requirements.

## Prerequisites

Before implementing event sourcing, ensure you have:

### Required Prior Steps
- [SoftDeletes and UserTracking Implementation](../090-model-features/010-softdeletes-usertracking.md) completed
- [CQRS Configuration](../030-core-components/030-cqrs-configuration.md) completed
- All Phase 0 implementation steps completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed
- Spatie Laravel Event Sourcing (`spatie/laravel-event-sourcing`) installed
- Hirethunk Verbs (`hirethunk/verbs`) installed
- Spatie Laravel Model States (`spatie/laravel-model-states`) installed

### Required Knowledge
- Basic understanding of event sourcing concepts
- Familiarity with CQRS pattern
- Understanding of domain-driven design
- Knowledge of state machines and state transitions

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x
- Database connection configured
- Redis for event storage (optional but recommended)

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Understand Core Concepts | 60 minutes |
| Configure Event Store | 30 minutes |
| Implement Aggregates | 60 minutes |
| Create Event Classes | 45 minutes |
| Implement Projectors | 60 minutes |
| Integrate with State Machines | 45 minutes |
| Test Event Sourcing Implementation | 60 minutes |
| **Total** | **310 minutes** |
</details>

> **Note:** These time estimates assume familiarity with event sourcing concepts. Actual time may vary based on experience level and the complexity of your domain model.

## Current Architecture Analysis

Looking at the existing documentation:

1. **CQRS Implementation**: The application already uses `hirethunk/verbs` for a pragmatic CQRS approach
2. **Event Sourcing Support**: The implementation plan includes `spatie/laravel-event-sourcing`
3. **State Management**: We're using `spatie/laravel-model-states` for state machines

This gives us a solid foundation for implementing event sourcing, but we need to define a clear strategy for how these components will work together.

## Proposed Event Sourcing Implementation

### 1. Core Concepts and Components

<details>
<summary>Light Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[Domain Event] --> B[Event Store]
    B --> C[Event Stream]
    C --> D[Projector]
    D --> E[Read Model]
    A --> F[Reactor]
    F --> G[Side Effects]

    H[Aggregate] --> |Applies| A
    I[Command] --> |Handled by| H
```text
</details>

<details>
<summary>Dark Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
flowchart TD
    A[Domain Event] --> B[Event Store]
    B --> C[Event Stream]
    C --> D[Projector]
    D --> E[Read Model]
    A --> F[Reactor]
    F --> G[Side Effects]

    H[Aggregate] --> |Applies| A
    I[Command] --> |Handled by| H
```php
</details>

> **Note:** All diagrams are available in both light and dark modes in the [illustrations folder](../../illustrations/README.md).

#### Key Components:

1. **Domain Events**: Immutable records of something that happened in the domain
2. **Event Store**: Persistent storage for all events
3. **Aggregates**: Domain objects that handle commands and apply events
4. **Projectors**: Build and maintain read models based on events
5. **Reactors**: Execute side effects when specific events occur

### 2. Integration with Existing Architecture

Here's how we can integrate event sourcing with our current architecture:

#### 2.1 Using hirethunk/verbs with Spatie Event Sourcing

The `hirethunk/verbs` package already provides a command bus and event history, while `spatie/laravel-event-sourcing` provides robust event sourcing capabilities. We can integrate them as follows:

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Events\TodoCreated;
use App\Events\TodoCompleted;
use App\Events\TodoAssigned;

class TodoAggregateRoot extends AggregateRoot
{
    public function createTodo(string $title, string $description, int $userId, int $teamId): self
    {
        $this->recordThat(new TodoCreated([
            'title' => $title,
            'description' => $description,
            'user_id' => $userId,
            'team_id' => $teamId,
        ]));

        return $this;
    }

    public function assignTodo(int $userId): self
    {
        $this->recordThat(new TodoAssigned([
            'user_id' => $userId,
        ]));

        return $this;
    }

    public function completeTodo(): self
    {
        $this->recordThat(new TodoCompleted([
            'completed_at' => now(),
        ]));

        return $this;
    }
}
```text

#### 2.2 Command Handlers with Event Sourcing

We can modify our command handlers to use event sourcing:

```php
<?php

namespace App\CommandHandlers;

use Hirethunk\Verbs\CommandHandler;
use App\Commands\CompleteTodo;
use App\Aggregates\TodoAggregateRoot;

class CompleteTodoHandler extends CommandHandler
{
    public function handle(CompleteTodo $command)
    {
        $aggregateRoot = TodoAggregateRoot::retrieve($command->todo_id);

        $aggregateRoot->completeTodo();

        $aggregateRoot->persist();

        return $this->success();
    }
}
```php
#### 2.3 Projectors for Read Models

Projectors will build and maintain read models based on events:

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\TodoCreated;
use App\Events\TodoCompleted;
use App\Events\TodoAssigned;
use App\Models\Todo;
use App\States\Todo\Completed;

class TodoProjector extends Projector
{
    public function onTodoCreated(TodoCreated $event, string $aggregateUuid)
    {
        Todo::create([
            'id' => $aggregateUuid,
            'title' => $event->title,
            'description' => $event->description,
            'user_id' => $event->user_id,
            'team_id' => $event->team_id,
            'status' => Draft::class,
        ]);
    }

    public function onTodoAssigned(TodoAssigned $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);
        $todo->user_id = $event->user_id;
        $todo->save();
    }

    public function onTodoCompleted(TodoCompleted $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);
        $todo->status = new Completed();
        $todo->completed_at = $event->completed_at;
        $todo->save();
    }
}
```text

#### 2.4 Reactors for Side Effects

Reactors handle side effects when events occur:

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use App\Events\TodoCompleted;
use App\Notifications\TodoCompletedNotification;

class TodoCompletionReactor extends Reactor
{
    public function onTodoCompleted(TodoCompleted $event, string $aggregateUuid)
    {
        $todo = \App\Models\Todo::findOrFail($aggregateUuid);

        // Notify the todo creator
        $todo->createdBy->notify(new TodoCompletedNotification($todo));

        // Update search index
        $todo->searchable();
    }
}
```php
### 3. Event Store Configuration

We'll configure the event store using the Spatie Event Sourcing package:

```php
// config/event-sourcing.php
return [
    'event_handlers' => [
        'projectors' => [
            \App\Projectors\TodoProjector::class,
            \App\Projectors\UserProjector::class,
            \App\Projectors\TeamProjector::class,
            // Add other projectors here
        ],
        'reactors' => [
            \App\Reactors\TodoCompletionReactor::class,
            \App\Reactors\UserRegistrationReactor::class,
            // Add other reactors here
        ],
    ],

    'event_class' => \Spatie\EventSourcing\StoredEvents\ShouldBeStored::class,

    'stored_event_model' => \Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent::class,

    'snapshot_model' => \Spatie\EventSourcing\Snapshots\EloquentSnapshot::class,

    'repositories' => [
        'stored_events' => \Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository::class,
        'snapshots' => \Spatie\EventSourcing\Snapshots\EloquentSnapshotRepository::class,
    ],
];
```text

### 4. Integration with State Machines

We can integrate event sourcing with our state machines by having events trigger state transitions:

```php
<?php

namespace App\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TodoStatusChanged extends ShouldBeStored
{
    public function __construct(
        public string $from,
        public string $to,
        public ?string $reason = null
    ) {}
}

// In the projector
public function onTodoStatusChanged(TodoStatusChanged $event, string $aggregateUuid)
{
    $todo = Todo::findOrFail($aggregateUuid);

    // Use the state machine to transition
    $todo->status->transitionTo($event->to);
    $todo->save();
}
```php
### 5. Rebuilding Projections

One of the key benefits of event sourcing is the ability to rebuild projections:

```php
// Command to rebuild all projections
php artisan event-sourcing:replay

// Command to rebuild specific projectors
php artisan event-sourcing:replay App\\Projectors\\TodoProjector
```text

### 6. Implementation Plan Updates

To fully implement event sourcing, we should update our implementation plan:

1. **Add Event Classes**: Create domain event classes for all significant state changes
2. **Create Aggregates**: Define aggregate roots for core domain entities
3. **Implement Projectors**: Build projectors for maintaining read models
4. **Develop Reactors**: Create reactors for handling side effects
5. **Update Command Handlers**: Modify command handlers to use event sourcing
6. **Configure Event Store**: Set up the event store and snapshots
7. **Testing**: Develop comprehensive tests for the event sourcing implementation

## Benefits of This Approach

1. **Complete Audit Trail**: Every state change is recorded as an event
2. **Temporal Queries**: Ability to determine the state of the system at any point in time
3. **Event Replay**: Rebuild projections or create new ones from existing events
4. **Separation of Concerns**: Clear separation between write and read models
5. **Business Insights**: Events provide valuable data for business analytics

## Potential Challenges

1. **Learning Curve**: Event sourcing requires a different mindset
2. **Eventual Consistency**: Read models may be temporarily out of sync with write models
3. **Event Schema Evolution**: Handling changes to event schemas over time
4. **Performance Considerations**: Event store queries can be expensive
5. **Snapshots Management**: Determining when to create snapshots for performance

## Recommendations

1. **Start Small**: Begin with one bounded context (e.g., Todos)
2. **Incremental Adoption**: Gradually expand to other parts of the application
3. **Comprehensive Testing**: Ensure thorough testing of event sourcing components
4. **Documentation**: Maintain clear documentation of events and their meanings
5. **Monitoring**: Implement monitoring for event processing and projection rebuilding

## Conclusion

Implementing event sourcing in the Enhanced Laravel Application will provide significant benefits in terms of auditability, flexibility, and business insights. By leveraging the existing CQRS architecture with `hirethunk/verbs` and integrating it with `spatie/laravel-event-sourcing`, we can create a robust event-sourced system that maintains a complete history of all domain events while providing performant read models for the application's UI.

> **Reference:**
> - [Spatie Laravel Event Sourcing Documentation](https:/spatie.be/docs/laravel-event-sourcing/v7/introduction)
> - [Hirethunk Verbs Documentation](https:/verbs.thunk.dev/docs/getting-started/quickstart)
> - [Event Sourcing Pattern](https:/martinfowler.com/eaaDev/EventSourcing.html)

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: Events not being stored

**Symptoms:**
- Events are not appearing in the event store
- Aggregates are not being updated

**Possible Causes:**
- Event store configuration issues
- Missing event classes
- Incorrect aggregate implementation

**Solutions:**
1. Verify the event store configuration in `config/event-sourcing.php`
2. Ensure event classes are properly defined and extend `SpatieEventSourcing\StoredEvents\ShouldBeStored`
3. Check that aggregates are correctly implementing `SpatieEventSourcing\AggregateRoots\AggregateRoot`

### Issue: Projections not updating

**Symptoms:**
- Events are stored but projections are not updated
- Read models are out of sync with events

**Possible Causes:**
- Projector not registered
- Errors in projector methods
- Queue worker not running

**Solutions:**
1. Ensure projectors are registered in the event sourcing configuration
2. Check for errors in projector methods
3. Verify that queue workers are running if using queued projectors

### Issue: Aggregate state inconsistencies

**Symptoms:**
- Aggregate state doesn't match expected state after applying events
- Unexpected behavior when reconstructing aggregates

**Possible Causes:**
- Events applied in incorrect order
- Missing event handlers in aggregate
- Logic errors in apply methods

**Solutions:**
1. Verify event ordering and timestamps
2. Ensure all events have corresponding apply methods in the aggregate
3. Review logic in apply methods for correctness

### Issue: Performance problems

**Symptoms:**
- Slow response times when retrieving aggregates
- High memory usage

**Possible Causes:**
- Too many events without snapshots
- Inefficient projectors
- Missing indexes on event store tables

**Solutions:**
1. Implement snapshots for aggregates with many events
2. Optimize projector implementations
3. Add appropriate indexes to event store tables

### Issue: Integration with state machines

**Symptoms:**
- State transitions not triggering events
- Events not causing state transitions

**Possible Causes:**
- Missing integration between state machines and event sourcing
- Incorrect event handling in state transitions

**Solutions:**
1. Ensure state transitions dispatch events
2. Verify that events are properly handled to trigger state transitions
3. Check for circular dependencies between events and states

</details>

## Related Documents

- [SoftDeletes and UserTracking Implementation](../090-model-features/010-softdeletes-usertracking.md) - For previous implementation step
- [CQRS Configuration](../030-core-components/030-cqrs-configuration.md) - For CQRS configuration details
- [Model Status Implementation](../090-model-features/020-model-status-implementation.md) - For next implementation step

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Wrapped tables in collapsible sections, updated status to Complete | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
</details>

---

**Previous Step:** [SoftDeletes and UserTracking Implementation](../090-model-features/010-softdeletes-usertracking.md) | **Next Step:** [Model Status Implementation](../090-model-features/020-model-status-implementation.md)
