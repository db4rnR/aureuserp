# Todo State Transitions (Dark Mode)

**Type:** State Diagram
**Created:** 2025-06-10
**Updated:** 2025-06-10
**Author:** AI Assistant

## Description

This diagram illustrates the state transitions for the Todo aggregate, showing how a todo item moves through different states based on events.

## Diagram Source

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
stateDiagram-v2
    [*] --> Pending: TodoCreated
    Pending --> InProgress: TodoStarted
    Pending --> Cancelled: TodoCancelled
    InProgress --> Completed: TodoCompleted
    InProgress --> Cancelled: TodoCancelled
    Completed --> InProgress: TodoReopened
    Cancelled --> Pending: TodoReopened
    
    state Pending {
        [*] --> NoDueDate
        NoDueDate --> DueDate: TodoDueDateSet
        DueDate --> Overdue: DueDatePassed
        DueDate --> NoDueDate: TodoDueDateRemoved
    }
    
    state InProgress {
        [*] --> LowPriority
        LowPriority --> MediumPriority: TodoPriorityChanged
        LowPriority --> HighPriority: TodoPriorityChanged
        MediumPriority --> LowPriority: TodoPriorityChanged
        MediumPriority --> HighPriority: TodoPriorityChanged
        HighPriority --> LowPriority: TodoPriorityChanged
        HighPriority --> MediumPriority: TodoPriorityChanged
    }
    
    classDef pending fill:#FFC107,stroke:#FFD54F,color:black
    classDef inprogress fill:#2196F3,stroke:#64B5F6,color:white
    classDef completed fill:#4CAF50,stroke:#81C784,color:white
    classDef cancelled fill:#9E9E9E,stroke:#E0E0E0,color:white
    
    class Pending pending
    class InProgress inprogress
    class Completed completed
    class Cancelled cancelled
```

## Related Documents

- [event-sourcing-guide.md](../../event-sourcing-guide.md)
- [100-350-event-sourcing-implementation.md](../../100-implementation-plan/100-350-event-sourcing-implementation.md)

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-06-10 | Initial version | AI Assistant |
