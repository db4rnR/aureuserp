# Product Requirements Document: Foundation Phase Implementation

## 1. Introduction/Overview

This document outlines the requirements for implementing the foundation phase of a Laravel application based on Domain-Driven Design (DDD) principles and modern architectural patterns. The foundation phase establishes the core architectural components necessary for building a scalable, maintainable, and feature-rich application.

The goal is to create a solid foundation that incorporates event sourcing, CQRS (Command Query Responsibility Segregation), finite state machines, and other advanced patterns while adhering to Laravel's standard conventions for file naming and structure.

## 2. Goals

- Implement a hybrid Event Sourcing architecture using `hirethunk/verbs` for flexibility and resilience
- Establish rigorous CQRS throughout the codebase with clear separation of command and query responsibilities
- Integrate Finite State Machines for robust state management using PHP 8.4 attributes and enums
- Support Single Table Inheritance for hierarchical models
- Create a multi-tier identifier strategy for optimal performance and functionality
- Implement Domain-Driven Design principles with clear bounded contexts
- Follow Laravel's standard conventions for file naming and structure
- Ensure comprehensive test coverage with Pest PHP
- Optimize for performance and scalability

## 3. User Stories

### Core Architecture Team

- As an architect, I want to implement a hybrid Event Sourcing approach so that we can maintain a complete audit trail while leveraging both modern and mature packages.
- As a developer, I want clear separation between read and write operations so that I can optimize each independently.
- As a team lead, I want to establish Domain-Driven Design principles so that our codebase aligns with business domains and is more maintainable.
- As a developer, I want to use Finite State Machines for state management so that state transitions are type-safe and predictable.
- As a database administrator, I want a multi-tier identifier strategy so that we can optimize for different use cases while maintaining performance.

### Application Developers

- As an application developer, I want to use pre-built aggregates and projections so that I can quickly implement business logic without reinventing the wheel.
- As a frontend developer, I want optimized read models so that I can efficiently retrieve and display data.
- As a QA engineer, I want the ability to replay events so that I can reproduce and debug issues more effectively.
- As a security specialist, I want built-in security features so that our applications are protected against common vulnerabilities.

### Operations Team

- As an operations engineer, I want performance optimization strategies so that our applications can handle high traffic loads.
- As a DevOps specialist, I want scalability features so that we can grow our infrastructure as needed.
- As a monitoring specialist, I want built-in health checks so that we can proactively identify and address issues.

## 4. Functional Requirements

### 4.1 Directory Structure and Naming Conventions

1. The system must follow Laravel's standard directory structure with DDD adaptations:
   - `app/Domain/[BoundedContext]/` - Domain layer components for each bounded context
   - `app/Application/[BoundedContext]/` - Application layer components for each bounded context
   - `app/Infrastructure/` - Infrastructure layer components
   - `app/Interfaces/` - Interface layer components (HTTP, Console, etc.)

2. The system must use consistent naming conventions for DDD components:
   - Aggregates: `[Name]Aggregate` (e.g., `UserAggregate`)
   - Value Objects: `[Name]` (e.g., `Email`, `Address`)
   - Entities: `[Name]` (e.g., `User`, `Product`)
   - Domain Events: `[Past Tense Action]` (e.g., `UserRegistered`, `OrderPlaced`)
   - Commands: `[Imperative Verb][Noun]Command` (e.g., `RegisterUserCommand`, `PlaceOrderCommand`)
   - Queries: `[Verb][Noun]Query` (e.g., `GetUserQuery`, `FindProductsQuery`)
   - Projections: `[Name]Projection` (e.g., `UserProjection`, `OrderProjection`)

### 4.2 Event Sourcing and CQRS Implementation

3. The system must prioritize `hirethunk/verbs` (v0.7+) as the primary event sourcing package for greatest flexibility and resilience.
4. The system must implement rigorous CQRS with complete separation of command and query responsibilities:
   - Command models must be entirely separate from query models
   - Command handlers must only emit events and never return data
   - Query handlers must never modify state
   - Read models must be optimized for specific query patterns
5. The system must store all state changes as events in an event store optimized with Snowflake IDs.
6. The system must support event replay for rebuilding state from event history.
7. The system must implement projections for creating optimized read models.
8. The system must support event handlers for updating read models when events occur.
9. The system must provide base classes for aggregates, events, and projections.
10. The system must implement event versioning to support schema evolution without requiring backward compatibility.

### 4.3 Domain-Driven Design Implementation

11. The system must support bounded contexts for clear domain boundaries.
12. The system must provide base classes for value objects, entities, and domain services.
13. The system must implement domain events for cross-aggregate communication.
14. The system must support a ubiquitous language through consistent naming conventions.
15. The system must implement aggregates as the primary consistency boundary.
16. The system must use repositories for aggregate persistence.
17. The system must implement domain services for operations that don't naturally fit within an aggregate.

### 4.4 Finite State Machine Implementation

18. The system must implement state management using PHP 8.4 native enums with backing types.
19. The system must integrate with `spatie/laravel-model-states` for complex workflows.
20. The system must integrate with `spatie/laravel-model-status` for simple flags.
21. The system must support type-safe state transitions with validation.
22. The system must trigger domain events on state changes.
23. The system must use PHP 8.4 attributes rather than PHPDocs for state configuration.
24. The system must enhance all enums to provide human-readable labels for UI display.
25. The system must enhance all enums to provide color codes for visual representation in UI components.

### 4.5 Single Table Inheritance Implementation

26. The system must support Single Table Inheritance for hierarchical models.
27. The system must integrate with `tightenco/parental` package for STI implementation.
28. The system must provide base classes for STI models.
29. The system must support type-specific behavior and attributes.
30. The system must implement a User model with STI using enum-backed types for `Admin`, `User`, and `Guest` user types.
31. The system must ensure that User types are properly validated and type-safe through enum backing.

### 4.6 Identifier Strategy Implementation

32. The system must implement a multi-tier identifier strategy.
33. The system must use auto-incrementing integers for primary keys.
34. The system must use Snowflake IDs for event store primary keys.
35. The system must use ULIDs for external references.
36. The system must use UUIDs for security-sensitive contexts.
37. The system must integrate with `glhd/bits` for Snowflake ID generation.
38. The system must integrate with `symfony/uid` for UUID and ULID generation.

### 4.7 Testing Implementation

39. The system must implement Test-Driven Development (TDD) with tests written before code.
40. The system must use PHP 8.4 attributes rather than PHPDocs for test configuration.
41. The system must implement a comprehensive Pest test suite.
42. The system must add test capabilities using Pest plugins.
43. The system must achieve high test coverage for all components.
44. The system must include unit, integration, and feature tests.
45. The system must implement architecture tests to enforce design constraints.

## 5. Non-Goals (Out of Scope)

- Implementation of specific business logic or domain models beyond the foundation
- User interface design and implementation
- Deployment and infrastructure setup
- Integration with external systems
- Data migration from existing systems
- Training and documentation beyond code comments and basic README files
- Mobile application development
- Internationalization and localization

## 6. Technical Considerations

### 6.1 Technology Stack

- PHP 8.4+
- Laravel 12.x
- SQLite as primary database (with roadmap to libsql/litesql)
- Redis for caching and real-time features
- Laravel Reverb for WebSocket communication
- Livewire with Volt and Flux UI for frontend (optional)
- Tailwind CSS 4.x for styling (optional)
- Vite 6.x for build system (optional)

### 6.2 Package Dependencies

#### Core Packages

- `hirethunk/verbs` (v0.7+): Primary event sourcing package
- `spatie/laravel-event-sourcing` (v7.0+): Secondary event sourcing package
- `spatie/laravel-model-states` (v2.11+): Finite state machine
- `spatie/laravel-model-status` (v1.18+): Simple status tracking
- `tightenco/parental` (v1.4+): Single Table Inheritance
- `glhd/bits` (v0.6+): Snowflake IDs
- `symfony/uid` (v7.3+): UUID and ULID generation
- `spatie/laravel-data` (v4.15+): Data transfer objects
- `spatie/laravel-query-builder` (v6.3+): API query building

#### Testing Packages

- `pestphp/pest` (v3.8+): Testing framework
- `pestphp/pest-plugin-laravel` (v3.2+): Laravel integration
- `pestphp/pest-plugin-livewire` (v3.0+): Livewire testing
- `pestphp/pest-plugin-arch` (v3.1+): Architecture testing
- `pestphp/pest-plugin-faker` (v3.0+): Fake data generation
- `pestphp/pest-plugin-stressless` (v3.1+): Performance testing
- `pestphp/pest-plugin-type-coverage` (v3.5+): Type coverage testing

### 6.3 Architecture Diagram

The system should follow this high-level architecture:

```
┌─────────────────────────────────────────────────────────────┐
│                      Interfaces Layer                        │
│  (Controllers, API Endpoints, Console Commands, Views)       │
└───────────────────────────────┬─────────────────────────────┘
                                │
┌───────────────────────────────▼─────────────────────────────┐
│                     Application Layer                        │
│         (Commands, Queries, Application Services)            │
└───────────────────────────────┬─────────────────────────────┘
                                │
┌───────────────────────────────▼─────────────────────────────┐
│                       Domain Layer                           │
│  (Aggregates, Domain Events, Value Objects, Domain Services) │
└───────────────────────────────┬─────────────────────────────┘
                                │
┌───────────────────────────────▼─────────────────────────────┐
│                   Infrastructure Layer                       │
│     (Event Store, Projections, Repositories, External)       │
└─────────────────────────────────────────────────────────────┘
```

## 7. Success Metrics

- All functional requirements are implemented and tested
- Performance benchmarks show improvement over traditional CRUD approach:
  - Read operations are at least 20% faster
  - Write operations maintain consistency and reliability
- Code quality metrics meet or exceed industry standards:
  - 90%+ test coverage
  - Low cyclomatic complexity
  - No critical security vulnerabilities
- Developer experience is improved:
  - Reduced time to implement new features
  - Easier debugging and testing
  - Clear architecture and separation of concerns

## 8. Implementation Recommendations

### 8.1 Directory Structure Example

```
app/
├── Domain/
│   ├── User/
│   │   ├── Aggregates/
│   │   │   └── UserAggregate.php
│   │   ├── Commands/
│   │   │   └── RegisterUserCommand.php
│   │   ├── Events/
│   │   │   └── UserRegistered.php
│   │   ├── Exceptions/
│   │   │   └── UserAlreadyExistsException.php
│   │   ├── Models/
│   │   │   └── User.php
│   │   ├── ValueObjects/
│   │   │   ├── Email.php
│   │   │   └── Password.php
│   │   └── States/
│   │       └── UserStatus.php
│   └── Shared/
│       ├── ValueObjects/
│       │   └── Identifier.php
│       └── Exceptions/
│           └── DomainException.php
├── Application/
│   ├── User/
│   │   ├── CommandHandlers/
│   │   │   └── RegisterUserHandler.php
│   │   ├── QueryHandlers/
│   │   │   └── GetUserProfileHandler.php
│   │   ├── Projections/
│   │   │   └── UserProfileProjection.php
│   │   └── ReadModels/
│   │       └── UserProfileReadModel.php
│   └── Shared/
│       ├── CommandBus.php
│       └── QueryBus.php
├── Infrastructure/
│   ├── EventStore/
│   │   ├── EventStore.php
│   │   └── EventSerializer.php
│   ├── Persistence/
│   │   ├── Repositories/
│   │   │   └── UserRepository.php
│   │   └── ReadModelRepositories/
│   │       └── UserProfileRepository.php
│   └── Services/
│       └── IdentifierGenerator.php
└── Interfaces/
    ├── Http/
    │   ├── Controllers/
    │   │   └── UserController.php
    │   ├── Requests/
    │   │   └── RegisterUserRequest.php
    │   └── Resources/
    │       └── UserResource.php
    └── Console/
        └── Commands/
            └── RebuildProjectionsCommand.php
```

### 8.2 Implementation Approach

1. **Start with Core Infrastructure**:
   - Implement the event store and event sourcing infrastructure
   - Create base classes for aggregates, events, and projections
   - Set up the identifier strategy

2. **Implement Domain Layer**:
   - Create value objects and entities
   - Implement aggregates with event sourcing
   - Define domain events and commands

3. **Build Application Layer**:
   - Implement command and query handlers
   - Create projections for read models
   - Set up the command and query buses

4. **Develop Interface Layer**:
   - Create controllers and API endpoints
   - Implement console commands
   - Set up validation and resources

5. **Add Testing**:
   - Write unit tests for domain components
   - Create integration tests for application layer
   - Implement feature tests for interfaces
   - Add architecture tests to enforce constraints

## 9. Open Questions

### Answers to: 9. Open Questions

- What specific business domains will be implemented on top of this foundation?
  - Project Management
  - CMS (Including Blogs, Articles, Forums, Media)
  - Personal ToDo management
  - Social interaction (comments, reactions, mentions, notifications)
  - Real-time Chat, with Presence
  - eCommerce
  - Subscriptions
  - Category/Taxonomy: self-referential, polymorphic
- Performance: configure the project to integrate with separate prometheus/grafana implementation
- Multi-tenancy: required
  - Shared db to start
  - Use the Organisation model described in the `700-r-and-d/` folder
   - Organisation model is STI and self-referential
   - STI types for Organisation model: `Tenant`, `Division`, `Department`, `Team`, `Project`, `Other`
   - `Tenant` must be root-level Organisation
   - "Adjacency-list" packages are foundational
   - Tenant "awareness" as a trait
- No timeline pressure - best quality and efforts - "it'll be ready when it's ready"
- User STI model: `AdminUser`, `RegularUser`, `GuestUser`
  - RegularUser is default
  - All are "Tenant Aware", except AdminUser
