# Phase 1: Event Sourcing State Machine Integration

**Version:** 1.0.0
**Date:** 2025-05-19
**Author:** AI Assistant
**Status:** New
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [State Machines in Event Sourcing](#state-machines-in-event-sourcing)
  - [Benefits](#benefits)
  - [Implementation Approach](#implementation-approach)
- [State Machine Implementation](#state-machine-implementation)
  - [State Machine Interface](#state-machine-interface)
  - [State Machine Base Class](#state-machine-base-class)
  - [State Interface](#state-interface)
  - [Transition Interface](#transition-interface)
- [Integration with Aggregates](#integration-with-aggregates)
  - [State Machine Trait](#state-machine-trait)
  - [State Transitions in Aggregates](#state-transitions-in-aggregates)
- [Example: Todo Aggregate State Machine](#example-todo-aggregate-state-machine)
  - [Todo States](#todo-states)
  - [Todo Transitions](#todo-transitions)
  - [Todo State Machine](#todo-state-machine)
  - [Integration with Todo Aggregate](#integration-with-todo-aggregate)
- [Testing State Machines](#testing-state-machines)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document describes the integration of state machines with event sourcing in the Enhanced Laravel Application (ELA). State machines provide a structured way to manage the state transitions of aggregates, ensuring that only valid state transitions are allowed and that the business rules governing these transitions are enforced consistently.

## State Machines in Event Sourcing

### Benefits

Integrating state machines with event sourcing provides several benefits:

1. **Explicit State Management**: States and transitions are explicitly defined, making the business rules more visible and understandable.
2. **Validation of Transitions**: Only valid transitions are allowed, preventing invalid state changes.
3. **Separation of Concerns**: State transition logic is separated from other business logic.
4. **Documentation**: The state machine serves as documentation of the possible states and transitions.
5. **Testability**: State machines can be tested independently of the aggregates they are used in.

### Implementation Approach

We'll implement state machines using a combination of interfaces and classes:

1. **State Machine**: Manages the current state and allowed transitions.
2. **States**: Represent the possible states of an aggregate.
3. **Transitions**: Represent the possible transitions between states.
4. **Guards**: Validate whether a transition is allowed based on the current state and context.
5. **Actions**: Execute when a transition occurs.

## State Machine Implementation

### State Machine Interface

```php
namespace App\EventSourcing\StateMachines;

interface StateMachine
{
    public function getCurrentState(): State;
    public function can(string $transition): bool;
    public function apply(string $transition, array $context = []): void;
    public function getAvailableTransitions(): array;
}
```php
### State Machine Base Class

```php
namespace App\EventSourcing\StateMachines;

abstract class AbstractStateMachine implements StateMachine
{
    protected State $currentState;
    protected array $states = [];
    protected array $transitions = [];
    
    public function __construct(string $initialState)
    {
        $this->currentState = $this->states[$initialState];
    }
    
    public function getCurrentState(): State
    {
        return $this->currentState;
    }
    
    public function can(string $transition): bool
    {
        if (!isset($this->transitions[$transition])) {
            return false;
        }
        
        $transitionObj = $this->transitions[$transition];
        return $transitionObj->getFromState() === $this->currentState->getName();
    }
    
    public function apply(string $transition, array $context = []): void
    {
        if (!$this->can($transition)) {
            throw new InvalidTransitionException(
                "Cannot apply transition '{$transition}' from state '{$this->currentState->getName()}'"
            );
        }
        
        $transitionObj = $this->transitions[$transition];
        
        // Execute guards
        foreach ($transitionObj->getGuards() as $guard) {
            if (!$guard->isAllowed($context)) {
                throw new TransitionGuardFailedException(
                    "Transition '{$transition}' guard failed"
                );
            }
        }
        
        // Execute actions
        foreach ($transitionObj->getActions() as $action) {
            $action->execute($context);
        }
        
        // Change state
        $this->currentState = $this->states[$transitionObj->getToState()];
    }
    
    public function getAvailableTransitions(): array
    {
        $available = [];
        
        foreach ($this->transitions as $name => $transition) {
            if ($this->can($name)) {
                $available[] = $name;
            }
        }
        
        return $available;
    }
}
```php
### State Interface

```php
namespace App\EventSourcing\StateMachines;

interface State
{
    public function getName(): string;
}
```php
### Transition Interface

```php
namespace App\EventSourcing\StateMachines;

interface Transition
{
    public function getName(): string;
    public function getFromState(): string;
    public function getToState(): string;
    public function getGuards(): array;
    public function getActions(): array;
}
```php
## Integration with Aggregates

### State Machine Trait

```php
namespace App\EventSourcing\StateMachines;

trait HasStateMachine
{
    protected StateMachine $stateMachine;
    
    public function getStateMachine(): StateMachine
    {
        return $this->stateMachine;
    }
    
    public function getCurrentState(): string
    {
        return $this->stateMachine->getCurrentState()->getName();
    }
    
    public function canTransition(string $transition): bool
    {
        return $this->stateMachine->can($transition);
    }
    
    public function applyTransition(string $transition, array $context = []): void
    {
        $this->stateMachine->apply($transition, $context);
    }
    
    public function getAvailableTransitions(): array
    {
        return $this->stateMachine->getAvailableTransitions();
    }
}
```php
### State Transitions in Aggregates

When an aggregate needs to change state, it should:

1. Check if the transition is allowed
2. Apply the transition
3. Record an event that captures the state change

```php
public function markAsCompleted(): self
{
    if (!$this->canTransition('complete')) {
        throw new InvalidTransitionException("Cannot mark todo as completed");
    }
    
    $this->applyTransition('complete');
    
    $this->recordThat(new TodoMarkedAsCompleted($this->uuid));
    
    return $this;
}
```php
## Example: Todo Aggregate State Machine

### Todo States

```php
namespace App\EventSourcing\StateMachines\Todo;

use App\EventSourcing\StateMachines\State;

class TodoState implements State
{
    private string $name;
    
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
}

class TodoStates
{
    public const DRAFT = 'draft';
    public const ACTIVE = 'active';
    public const COMPLETED = 'completed';
    public const ARCHIVED = 'archived';
}
```php
### Todo Transitions

```php
namespace App\EventSourcing\StateMachines\Todo;

use App\EventSourcing\StateMachines\Transition;

class TodoTransition implements Transition
{
    private string $name;
    private string $fromState;
    private string $toState;
    private array $guards;
    private array $actions;
    
    public function __construct(
        string $name,
        string $fromState,
        string $toState,
        array $guards = [],
        array $actions = []
    ) {
        $this->name = $name;
        $this->fromState = $fromState;
        $this->toState = $toState;
        $this->guards = $guards;
        $this->actions = $actions;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getFromState(): string
    {
        return $this->fromState;
    }
    
    public function getToState(): string
    {
        return $this->toState;
    }
    
    public function getGuards(): array
    {
        return $this->guards;
    }
    
    public function getActions(): array
    {
        return $this->actions;
    }
}

class TodoTransitions
{
    public const ACTIVATE = 'activate';
    public const COMPLETE = 'complete';
    public const REOPEN = 'reopen';
    public const ARCHIVE = 'archive';
}
```php
### Todo State Machine

```php
namespace App\EventSourcing\StateMachines\Todo;

use App\EventSourcing\StateMachines\AbstractStateMachine;

class TodoStateMachine extends AbstractStateMachine
{
    public function __construct(string $initialState = TodoStates::DRAFT)
    {
        $this->states = [
            TodoStates::DRAFT => new TodoState(TodoStates::DRAFT),
            TodoStates::ACTIVE => new TodoState(TodoStates::ACTIVE),
            TodoStates::COMPLETED => new TodoState(TodoStates::COMPLETED),
            TodoStates::ARCHIVED => new TodoState(TodoStates::ARCHIVED),
        ];
        
        $this->transitions = [
            TodoTransitions::ACTIVATE => new TodoTransition(
                TodoTransitions::ACTIVATE,
                TodoStates::DRAFT,
                TodoStates::ACTIVE
            ),
            TodoTransitions::COMPLETE => new TodoTransition(
                TodoTransitions::COMPLETE,
                TodoStates::ACTIVE,
                TodoStates::COMPLETED
            ),
            TodoTransitions::REOPEN => new TodoTransition(
                TodoTransitions::REOPEN,
                TodoStates::COMPLETED,
                TodoStates::ACTIVE
            ),
            TodoTransitions::ARCHIVE => new TodoTransition(
                TodoTransitions::ARCHIVE,
                TodoStates::COMPLETED,
                TodoStates::ARCHIVED
            ),
        ];
        
        parent::__construct($initialState);
    }
}
```php
### Integration with Todo Aggregate

```php
namespace App\EventSourcing\Aggregates;

use App\EventSourcing\StateMachines\HasStateMachine;
use App\EventSourcing\StateMachines\Todo\TodoStateMachine;
use App\EventSourcing\StateMachines\Todo\TodoStates;
use App\EventSourcing\StateMachines\Todo\TodoTransitions;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class TodoAggregate extends AggregateRoot
{
    use HasStateMachine;
    
    private string $title;
    private ?string $description = null;
    
    public function __construct()
    {
        $this->stateMachine = new TodoStateMachine();
    }
    
    public function createTodo(string $title, ?string $description = null): self
    {
        $this->recordThat(new TodoCreated(
            $this->uuid(),
            $title,
            $description
        ));
        
        return $this;
    }
    
    public function activate(): self
    {
        if (!$this->canTransition(TodoTransitions::ACTIVATE)) {
            throw new InvalidTransitionException("Cannot activate todo");
        }
        
        $this->applyTransition(TodoTransitions::ACTIVATE);
        
        $this->recordThat(new TodoActivated($this->uuid()));
        
        return $this;
    }
    
    public function markAsCompleted(): self
    {
        if (!$this->canTransition(TodoTransitions::COMPLETE)) {
            throw new InvalidTransitionException("Cannot mark todo as completed");
        }
        
        $this->applyTransition(TodoTransitions::COMPLETE);
        
        $this->recordThat(new TodoMarkedAsCompleted($this->uuid()));
        
        return $this;
    }
    
    // Event handlers
    
    protected function applyTodoCreated(TodoCreated $event): void
    {
        $this->title = $event->title;
        $this->description = $event->description;
    }
    
    protected function applyTodoActivated(TodoActivated $event): void
    {
        // The state change is handled by the state machine
    }
    
    protected function applyTodoMarkedAsCompleted(TodoMarkedAsCompleted $event): void
    {
        // The state change is handled by the state machine
    }
}
```php
## Testing State Machines

```php
public function test_todo_state_machine_transitions()
{
    // Create a state machine in the draft state
    $stateMachine = new TodoStateMachine(TodoStates::DRAFT);
    
    // Check initial state
    $this->assertEquals(TodoStates::DRAFT, $stateMachine->getCurrentState()->getName());
    
    // Check available transitions
    $this->assertTrue($stateMachine->can(TodoTransitions::ACTIVATE));
    $this->assertFalse($stateMachine->can(TodoTransitions::COMPLETE));
    
    // Apply a transition
    $stateMachine->apply(TodoTransitions::ACTIVATE);
    
    // Check new state
    $this->assertEquals(TodoStates::ACTIVE, $stateMachine->getCurrentState()->getName());
    
    // Check new available transitions
    $this->assertTrue($stateMachine->can(TodoTransitions::COMPLETE));
    $this->assertFalse($stateMachine->can(TodoTransitions::ACTIVATE));
}
```

## Related Documents

- [Event Sourcing Implementation](050-implementation.md)
- [Aggregates](020-000-aggregates.md)
- [Todo Aggregate](020-040-todo-aggregate.md)
- [State Machines](080-state-machines.md)

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-19 | Initial version | AI Assistant |
