# Reactor Architecture (Dark Mode)

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2a2a2a', 'primaryTextColor': '#ffffff', 'primaryBorderColor': '#555555', 'lineColor': '#999999', 'secondaryColor': '#252525', 'tertiaryColor': '#333333' }}}%%
classDiagram
    class EventStore {
        +StoredEvent[] events
        +persist(Event event)
        +retrieveAll()
        +retrieveAllForAggregate(string uuid)
    }
    
    class Reactor {
        +onUserRegistered(UserRegistered event)
        +onTeamCreated(TeamCreated event)
        +onPostPublished(PostPublished event)
    }
    
    class QueuedReactor {
        +queue: string
        +connection: string
        +delay: int
    }
    
    class SideEffect {
        +execute()
        +rollback()
    }
    
    class EmailNotification {
        +send()
    }
    
    class PushNotification {
        +send()
    }
    
    class ExternalAPICall {
        +execute()
    }
    
    EventStore --> Reactor: events
    Reactor <|-- QueuedReactor
    Reactor --> SideEffect: triggers
    SideEffect <|-- EmailNotification
    SideEffect <|-- PushNotification
    SideEffect <|-- ExternalAPICall
```
