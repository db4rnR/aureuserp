# Technical Architecture Document (TAD) - Enhanced Laravel Application

**Version:** 1.4.0
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Updated with Current Package Versions

---

<details>
<summary>Table of Contents</summary>

- [1. Introduction](#1-introduction)
  - [1.1. Purpose](#11-purpose)
  - [1.2. Scope](#12-scope)
  - [1.3. References](#13-references)
- [2. System Architecture Overview](#2-system-architecture-overview)
  <details>
  <summary>Expand Architecture Overview</summary>

  - [2.1. High-Level Architecture](#21-high-level-architecture)
  - [2.2. Key Architectural Patterns](#22-key-architectural-patterns)
    - [2.2.1. Pragmatic CQRS](#221-pragmatic-cqrs)
    - [2.2.2. State Machines](#222-state-machines)
      - [2.2.2.1. Enhanced Enum Implementation](#2221-enhanced-enum-implementation)
    - [2.2.3. Hierarchical Data Structures](#223-hierarchical-data-structures)
    - [2.2.4. Team Scoping](#224-team-scoping)
  - [2.3. Technology Stack](#23-technology-stack)
  - [2.4. Deployment Architecture](#24-deployment-architecture)
    - [2.4.1. Scaling Considerations](#241-scaling-considerations)
    - [2.4.2. Infrastructure Requirements](#242-infrastructure-requirements)
  - [2.5. Technology Stack](#25-technology-stack)
    - [2.5.1. Backend](#251-backend)
    - [2.5.2. Frontend](#252-frontend)
    - [2.5.3. Key Packages](#253-key-packages)
  </details>
- [3. Database Architecture](#3-database-architecture)
  <details>
  <summary>Expand Database Architecture</summary>

  - [3.1. Database Schema Overview](#31-database-schema-overview)
  - [3.2. Entity Relationship Diagram](#32-entity-relationship-diagram)
  - [3.3. Core Entities](#33-core-entities)
    - [3.3.1. User](#331-user)
    - [3.3.2. Team](#332-team)
    - [3.3.3. Category](#333-category)
    - [3.3.4. Post](#334-post)
    - [3.3.5. Todo](#335-todo)
    - [3.3.6. Conversation](#336-conversation)
    - [3.3.7. Message](#337-message)
    - [3.3.8. Command Log and Snapshot](#338-command-log-and-snapshot)
  - [3.4. Database Migrations Strategy](#34-database-migrations-strategy)
    - [3.4.1. Migration Approach](#341-migration-approach)
    - [3.4.2. Migration Sequence](#342-migration-sequence)
    - [3.4.3. Schema Changes and Data Migrations](#343-schema-changes-and-data-migrations)
    - [3.4.4. Rollback Procedures](#344-rollback-procedures)
  </details>
- [4. Authentication & Authorization](#4-authentication--authorization)
  <details>
  <summary>Expand Authentication & Authorization</summary>

  - [4.1. Authentication](#41-authentication)
    - [4.1.1. User Authentication](#411-user-authentication)
    - [4.1.2. Authentication Flow](#412-authentication-flow)
    - [4.1.3. Multi-Factor Authentication](#413-multi-factor-authentication)
    - [4.1.4. API Authentication](#414-api-authentication)
  - [4.2. Authorization](#42-authorization)
    - [4.2.1. Role-Based Access Control](#421-role-based-access-control)
    - [4.2.2. Team-Based Permissions](#422-team-based-permissions)
    - [4.2.3. Policy Implementation](#423-policy-implementation)
  </details>
- [5. Routing and Model Binding](#5-routing-and-model-binding)
  - [5.1. Route Model Binding with Snowflake IDs and Slugs](#51-route-model-binding-with-snowflake-ids-and-slugs)
    - [5.1.1. Snowflake ID Implementation](#511-snowflake-id-implementation)
    - [5.1.2. Slug Implementation](#512-slug-implementation)
- [6. API Architecture](#6-api-architecture)
  <details>
  <summary>Expand API Architecture</summary>

  - [6.1. Internal API](#61-internal-api)
  - [6.2. Public API](#62-public-api)
    - [6.2.1. API Design](#621-api-design)
      - [6.2.1.1. RESTful Endpoints](#6211-restful-endpoints)
      - [6.2.1.2. Authentication](#6212-authentication)
      - [6.2.1.3. Rate Limiting](#6213-rate-limiting)
      - [6.2.1.4. API Documentation Framework](#6214-api-documentation-framework)
    - [6.2.2. Internal API](#622-internal-api)
      - [6.2.2.1. Livewire Components](#6221-livewire-components)
  </details>
- [7. Frontend Architecture](#7-frontend-architecture)
  - [7.1. Frontend Framework](#71-frontend-framework)
  - [7.2. Component Structure](#72-component-structure)
  - [7.3. State Management](#73-state-management)
- [8. Security Architecture](#8-security-architecture)
  <details>
  <summary>Expand Security Architecture</summary>

  - [8.1. Authentication Security](#81-authentication-security)
  - [8.2. Authorization Security](#82-authorization-security)
  - [8.3. Data Security](#83-data-security)
  - [8.4. API Security](#84-api-security)
  - [8.5. File Upload Security](#85-file-upload-security)
  - [8.6. Audit and Logging](#86-audit-and-logging)
  - [8.7. Security Threat Model](#87-security-threat-model)
    - [8.7.1. Threat Assessment Process](#871-threat-assessment-process)
    - [8.7.2. Security Testing Strategy](#872-security-testing-strategy)
  </details>
- [9. Search Implementation](#9-search-implementation)
  - [9.1. Search Engine](#91-search-engine)
  - [9.2. Indexing Strategy](#92-indexing-strategy)
  - [9.3. Search UI](#93-search-ui)
- [10. Real-time Communication](#10-real-time-communication)
  - [10.1. WebSocket Implementation](#101-websocket-implementation)
  - [10.2. Chat Architecture](#102-chat-architecture)
  - [10.3. Notifications](#103-notifications)
- [11. Performance Optimization](#11-performance-optimization)
  <details>
  <summary>Expand Performance Optimization</summary>

  - [11.1. Performance Targets](#111-performance-targets)
  - [11.2. Caching Strategy](#112-caching-strategy)
  - [11.3. Database Optimization](#113-database-optimization)
  - [11.4. N+1 Query Prevention](#114-n1-query-prevention)
  - [11.5. Application Performance](#115-application-performance)
  - [11.6. Database Performance](#116-database-performance)
  - [11.7. Frontend Performance](#117-frontend-performance)
  </details>
- [12. Testing Strategy](#12-testing-strategy)
  - [12.1. Unit Testing](#121-unit-testing)
  - [12.2. Feature Testing](#122-feature-testing)
  - [12.3. Browser Testing](#123-browser-testing)
  - [12.4. API Testing](#124-api-testing)
  - [12.5. Performance Testing](#125-performance-testing)
- [13. Deployment Strategy](#13-deployment-strategy)
  - [13.1. Environments](#131-environments)
  - [13.2. CI/CD Pipeline](#132-cicd-pipeline)
  - [13.3. Deployment Process](#133-deployment-process)
  - [13.4. Rollback Strategy](#134-rollback-strategy)
- [14. Maintenance and Monitoring](#14-maintenance-and-monitoring)
  - [14.1. Logging](#141-logging)
  - [14.2. Monitoring](#142-monitoring)
  - [14.3. Backup and Recovery](#143-backup-and-recovery)
  - [14.4. Updates and Patches](#144-updates-and-patches)
- [15. Key Decisions](#15-key-decisions)
- [16. Appendix](#16-appendix)
</details>

## 1. Introduction

### 1.1. Purpose
This Technical Architecture Document (TAD) provides a detailed technical specification for implementing the Enhanced Laravel Application as defined in the Product Requirements Document (PRD v2.2). It serves as a blueprint for developers, architects, and other technical stakeholders involved in the development process.

### 1.2. Scope
This document covers the technical architecture, system design, implementation approach, and technical considerations for the Enhanced Laravel Application. It includes system architecture diagrams, database schema details, API specifications, security architecture, performance considerations, and infrastructure requirements.

### 1.3. References
- Product Requirements Document (PRD) v2.2
- Laravel 12 Documentation
- PHP 8.4 Documentation
- hirethunk/verbs Documentation
- spatie/laravel-event-sourcing Documentation
- Filament Documentation

---

## 2. System Architecture Overview

### 2.1. High-Level Architecture

The Enhanced Laravel Application follows a modern, layered architecture built on Laravel 12 and PHP 8.4, with an event sourcing architecture and pragmatic CQRS approach for core business logic.

<details>
<summary>Light Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    subgraph "Client Layer"
        A1[Web Browser]
        A2[Mobile App]
        A3[API Consumers]
    end

    subgraph "Presentation Layer"
        B1[Livewire Components]
        B2[Filament Admin Panel]
        B3[API Controllers]
    end

    subgraph "Application Layer"
        C1[Controllers]
        C2[Commands/Handlers]
        C3[Query Services]
        C4[Events/Listeners]
    end

    subgraph "Domain Layer"
        D1[Models]
        D2[Services]
        D3[State Machines]
        D4[Policies]
        D5[Aggregates]
        D6[Events]
    end

    subgraph "Infrastructure Layer"
        E1[Database]
        E2[Search Engine]
        E3[Queue System]
        E4[WebSockets]
        E5[File Storage]
        E6[Cache]
        E7[Event Store]
    end

    A1 --> B1
    A1 --> B2
    A2 --> B3
    A3 --> B3

    B1 --> C1
    B1 --> C2
    B1 --> C3
    B2 --> C1
    B2 --> C2
    B2 --> C3
    B3 --> C1
    B3 --> C2
    B3 --> C3

    C1 --> D1
    C1 --> D2
    C1 --> D4
    C2 --> D1
    C2 --> D2
    C2 --> D3
    C2 --> D4
    C2 --> D5
    D5 --> D6
    D6 --> E7
    C3 --> D1
    C4 --> D1
    C4 --> D2

    D1 --> E1
    D1 --> E2
    D2 --> E1
    D2 --> E3
    D2 --> E4
    D2 --> E5
    D2 --> E6
```text
</details>

<details>
<summary>Dark Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
flowchart TD
    subgraph "Client Layer"
        A1[Web Browser]
        A2[Mobile App]
        A3[API Consumers]
    end

    subgraph "Presentation Layer"
        B1[Livewire Components]
        B2[Filament Admin Panel]
        B3[API Controllers]
    end

    subgraph "Application Layer"
        C1[Controllers]
        C2[Commands/Handlers]
        C3[Query Services]
        C4[Events/Listeners]
    end

    subgraph "Domain Layer"
        D1[Models]
        D2[Services]
        D3[State Machines]
        D4[Policies]
        D5[Aggregates]
        D6[Events]
    end

    subgraph "Infrastructure Layer"
        E1[Database]
        E2[Search Engine]
        E3[Queue System]
        E4[WebSockets]
        E5[File Storage]
        E6[Cache]
        E7[Event Store]
    end

    A1 --> B1
    A1 --> B2
    A2 --> B3
    A3 --> B3

    B1 --> C1
    B1 --> C2
    B1 --> C3
    B2 --> C1
    B2 --> C2
    B2 --> C3
    B3 --> C1
    B3 --> C2
    B3 --> C3

    C1 --> D1
    C1 --> D2
    C1 --> D4
    C2 --> D1
    C2 --> D2
    C2 --> D3
    C2 --> D4
    C2 --> D5
    D5 --> D6
    D6 --> E7
    C3 --> D1
    C4 --> D1
    C4 --> D2

    D1 --> E1
    D1 --> E2
    D2 --> E1
    D2 --> E3
    D2 --> E4
    D2 --> E5
    D2 --> E6
```php
</details>

> **Note:** All diagrams are available in both light and dark modes in the [illustrations folder](../illustrations/index.md).

### 2.2. Key Architectural Patterns

#### 2.2.1. Event Sourcing
The application implements event sourcing using the `spatie/laravel-event-sourcing` package, which provides a robust foundation for storing and processing domain events. For detailed implementation guidelines, see [Event Sourcing Implementation](../100-implementation-plan/100-350-event-sourcing/050-implementation.md).

- **Domain Events**: Immutable records of something that happened in the domain
- **Event Store**: Persistent storage for all events
- **Aggregates**: Domain objects that handle commands and apply events
- **Projectors**: Build and maintain read models based on events
- **Reactors**: Execute side effects when specific events occur

**Key Aggregates**: The following domain entities will be implemented as event-sourced aggregates:
- User
- Team
- Post
- Todo
- Comment
- Message

**Testing Approach**: All event-sourced components must have comprehensive unit and integration tests. See [Event Sourcing Testing](../100-implementation-plan/100-350-event-sourcing/070-testing.md) for detailed testing guidelines.

<details>
<summary>Light Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[User Action] --> B[Command]
    B --> C[Command Handler]
    C --> D[Aggregate]
    D --> E[Event]
    E --> F[Event Store]
    E --> G[Projector]
    G --> H[Read Model]
    E --> I[Reactor]
    I --> J[Side Effect]
```text
</details>

<details>
<summary>Dark Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
flowchart TD
    A[User Action] --> B[Command]
    B --> C[Command Handler]
    C --> D[Aggregate]
    D --> E[Event]
    E --> F[Event Store]
    E --> G[Projector]
    G --> H[Read Model]
    E --> I[Reactor]
    I --> J[Side Effect]
```sql
</details>

#### 2.2.2. Pragmatic CQRS (Command Query Responsibility Segregation)
The application implements a pragmatic CQRS pattern using the `hirethunk/verbs` package suite, integrated with event sourcing:

- **Commands**: Used for operations that change state (create, update, delete)
- **Queries**: Used for operations that read state
- **Command Handlers**: Process commands and apply business logic, often through aggregates
- **Command History**: Records commands and state changes for audit purposes

<details>
<summary>Light Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A["Request / User Action"] --> B["Controller / Livewire Component"]

    subgraph "Write Side - Event Sourcing with CQRS"
        B_CMD["Controller / Livewire Component"] -->|"Sends Verbs Command Object"| C{"Command Bus (hirethunk/verbs)"}
        C --> D["Verb Command Handler"]
        D --> AGG["Aggregate Root (spatie/laravel-event-sourcing)"]
        AGG -->|"Records"| DE["Domain Event"]
        D --> F["Validation Logic"]
        DE --> ES[("Event Store")]
        D --> H["Command History (verbs)"]
        H --> G_HIST[("Database: Stores History")]
        DE --> I{"Event Bus"}
    end

    subgraph "Write Side - Simple CRUD - Optional"
        B_SCRUD["Controller / Livewire Component"] --> E_SCRUD["Domain Services / Models"]
        E_SCRUD --> G_SCRUD[("Database: Persists State")]
    end

    B --> B_CMD
    B --> B_SCRUD

    subgraph "Read Side - Projections"
        I --> PROJ["Projectors"]
        PROJ --> G_READ[("Read Models")]
        B_QUERY["Controller / Livewire Component"] -->|"Query Parameters"| J["Query Service / Eloquent Scopes"]
        J --> G_READ
        J --> K["Response Data / View Model"]
    end

    B --> B_QUERY
    B_QUERY --> K_OUT["Response Data / View Model"]

    subgraph "Side Effects - Reactors"
        I --> REACT["Reactors"]
        REACT --> M["Notifications"]
        REACT --> N["Search Indexing"]
        REACT --> O["Cache Updates"]
        REACT --> P["..."]
    end
```text
</details>

<details>
<summary>Dark Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
flowchart TD
    A["Request / User Action"] --> B["Controller / Livewire Component"]

    subgraph "Write Side - Event Sourcing with CQRS"
        B_CMD["Controller / Livewire Component"] -->|"Sends Verbs Command Object"| C{"Command Bus (hirethunk/verbs)"}
        C --> D["Verb Command Handler"]
        D --> AGG["Aggregate Root (spatie/laravel-event-sourcing)"]
        AGG -->|"Records"| DE["Domain Event"]
        D --> F["Validation Logic"]
        DE --> ES[("Event Store")]
        D --> H["Command History (verbs)"]
        H --> G_HIST[("Database: Stores History")]
        DE --> I{"Event Bus"}
    end

    subgraph "Write Side - Simple CRUD - Optional"
        B_SCRUD["Controller / Livewire Component"] --> E_SCRUD["Domain Services / Models"]
        E_SCRUD --> G_SCRUD[("Database: Persists State")]
    end

    B --> B_CMD
    B --> B_SCRUD

    subgraph "Read Side - Projections"
        I --> PROJ["Projectors"]
        PROJ --> G_READ[("Read Models")]
        B_QUERY["Controller / Livewire Component"] -->|"Query Parameters"| J["Query Service / Eloquent Scopes"]
        J --> G_READ
        J --> K["Response Data / View Model"]
    end

    B --> B_QUERY
    B_QUERY --> K_OUT["Response Data / View Model"]

    subgraph "Side Effects - Reactors"
        I --> REACT["Reactors"]
        REACT --> M["Notifications"]
        REACT --> N["Search Indexing"]
        REACT --> O["Cache Updates"]
        REACT --> P["..."]
    end
```php
</details>

#### 2.2.3. State Machines
The application uses state machines via `spatie/laravel-model-states` and native PHP 8.4 Enums to manage the lifecycle of key entities. Each enum will implement custom methods for `label()` and `color()` to provide visual representation:

- User: `Invited`, `PendingActivation`, `Active`, `Suspended`, `Deactivated`, `Archived`
- Team: `Forming`, `Active`, `Archived`
- Post: `Draft`, `PendingReview`, `Published`, `Scheduled`, `Archived`
- Todo: `Pending`, `InProgress`, `Completed`, `Cancelled`

#### 2.2.4. Integration of Event Sourcing and State Machines

The application integrates event sourcing with state machines to provide a robust and auditable state transition system:

- **State Transitions as Events**: Each state transition is recorded as an event in the event store
- **Event-Driven State Changes**: State changes are triggered by domain events
- **Projectors for State Updates**: Projectors update the current state in read models based on events
- **Temporal State Queries**: The event store enables querying the state of an entity at any point in time
- **Complete Transition History**: All state transitions are recorded with timestamps and reasons

#### 2.2.5. Event Schema Evolution Management

The application implements a strategy for managing event schema evolution to ensure backward compatibility and system resilience:

- **Immutable Events**: Once stored, events are never modified
- **Versioned Events**: New event versions are created when the schema changes
- **Upconverting Events**: Older event versions are upconverted to the latest version when retrieved
- **Backward Compatibility**: Projectors and reactors handle both old and new event versions
- **Migration Strategy**: When significant schema changes are needed, a migration strategy is implemented to convert old events to new formats

For detailed implementation guidelines, see [Event Sourcing Implementation](../100-implementation-plan/100-350-event-sourcing/050-implementation.md).

```php
// Example of a state transition event
class TodoStatusChanged extends ShouldBeStored
{
    public function __construct(
        public string $from,
        public string $to,
        public ?string $reason = null,
        public string $changedBy = null
    ) {}
}

// Example of a projector handling state transitions
class TodoProjector extends Projector
{
    public function onTodoStatusChanged(TodoStatusChanged $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);

        // Update the state machine state
        $todo->status->transitionTo($event->to);

        // Also record in the statuses table with the reason
        $todo->setStatus("state_changed_to_{$event->to}", $event->reason);

        $todo->save();
    }
}
```text

##### 2.2.3.1. Enhanced Enum Implementation
All state enums will be implemented using native PHP 8.4 Enums with custom methods following Filament standards:

```php
// Example implementation of enhanced PHP 8.4 Enum for User Status
enum UserStatus: string
{
    case Invited = 'invited';
    case PendingActivation = 'pending_activation';
    case Active = 'active';
    case Suspended = 'suspended';
    case Deactivated = 'deactivated';

    /**
     * Get the label for the enum value.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Invited => 'Invited',
            self::PendingActivation => 'Pending Activation',
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Deactivated => 'Deactivated',
        };
    }

    /**
     * Get the color for the enum value.
     * Uses Filament color system: primary, secondary, success, warning, danger, info, gray
     */
    public function getColor(): string
    {
        return match($this) {
            self::Invited => 'gray',
            self::PendingActivation => 'info',
            self::Active => 'success',
            self::Suspended => 'warning',
            self::Deactivated => 'danger',
        };
    }

    /**
     * Get all enum values as an array for select inputs.
     */
    public static function getSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->getLabel()])
            ->toArray();
    }
}
```php
This approach follows Filament's conventions for working with enums, making integration with Filament forms, tables, and other components seamless. The `getLabel()` and `getColor()` methods provide human-readable labels and consistent color coding across the application.

#### 2.2.5. Team Scoping
Team scoping is a fundamental principle where data access and operations are bounded by team membership. This is implemented through:

- Data modeling with team_id foreign keys
- Validation rules enforcing team boundaries
- Permission checks incorporating team context
- UI components filtered by team context
- Search queries with team-based filtering

#### 2.2.6. Hierarchical Data Structures
The application supports hierarchical data structures for Teams, Categories, and Todos using `staudenmeir/laravel-adjacency-list` with configurable depth limits and complex move validation.

### 2.3. Technology Stack

### 2.4 Deployment Architecture

The Enhanced Laravel Application is designed for horizontal scalability with the following deployment architecture:

<details>
<summary>Light Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TB
    subgraph "Client Layer"
        Browser["Web Browser"]
        MobileApp["Mobile App"]
        ExternalAPI["External API Clients"]
    end

    subgraph "Load Balancing"
        LB["Load Balancer"]
    end

    subgraph "Application Layer"
        WebServer1["Web Server 1\nFrankenPHP + Laravel Octane"]
        WebServer2["Web Server 2\nFrankenPHP + Laravel Octane"]
        WebServer3["Web Server 3\nFrankenPHP + Laravel Octane"]
    end

    subgraph "Queue Processing"
        HorizonWorker1["Horizon Worker 1"]
        HorizonWorker2["Horizon Worker 2"]
    end

    subgraph "Real-time Layer"
        ReverbServer1["Reverb Server 1"]
        ReverbServer2["Reverb Server 2"]
    end

    subgraph "Data Layer"
        PrimaryDB[("Primary Database\nPostgreSQL")]
        ReadReplica[("Read Replica\nPostgreSQL")]
        Redis[("Redis\nCache + Queue")]
        Typesense["Typesense\nSearch Engine"]
    end

    subgraph "Storage Layer"
        ObjectStorage["Object Storage\nMedia Files"]
    end

    Browser --> LB
    MobileApp --> LB
    ExternalAPI --> LB

    LB --> WebServer1
    LB --> WebServer2
    LB --> WebServer3

    WebServer1 --> PrimaryDB
    WebServer2 --> PrimaryDB
    WebServer3 --> PrimaryDB

    WebServer1 --> ReadReplica
    WebServer2 --> ReadReplica
    WebServer3 --> ReadReplica

    WebServer1 --> Redis
    WebServer2 --> Redis
    WebServer3 --> Redis

    Redis --> HorizonWorker1
    Redis --> HorizonWorker2

    HorizonWorker1 --> PrimaryDB
    HorizonWorker2 --> PrimaryDB

    WebServer1 --> ReverbServer1
    WebServer2 --> ReverbServer1
    WebServer3 --> ReverbServer2

    Browser --> ReverbServer1
    Browser --> ReverbServer2

    WebServer1 --> Typesense
    WebServer2 --> Typesense
    WebServer3 --> Typesense

    WebServer1 --> ObjectStorage
    WebServer2 --> ObjectStorage
    WebServer3 --> ObjectStorage

    HorizonWorker1 --> ObjectStorage
    HorizonWorker2 --> ObjectStorage
```text
</details>

<details>
<summary>Dark Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
flowchart TB
    subgraph "Client Layer"
        Browser["Web Browser"]
        MobileApp["Mobile App"]
        ExternalAPI["External API Clients"]
    end

    subgraph "Load Balancing"
        LB["Load Balancer"]
    end

    subgraph "Application Layer"
        WebServer1["Web Server 1\nFrankenPHP + Laravel Octane"]
        WebServer2["Web Server 2\nFrankenPHP + Laravel Octane"]
        WebServer3["Web Server 3\nFrankenPHP + Laravel Octane"]
    end

    subgraph "Queue Processing"
        HorizonWorker1["Horizon Worker 1"]
        HorizonWorker2["Horizon Worker 2"]
    end

    subgraph "Real-time Layer"
        ReverbServer1["Reverb Server 1"]
        ReverbServer2["Reverb Server 2"]
    end

    subgraph "Data Layer"
        PrimaryDB[("Primary Database\nPostgreSQL")]
        ReadReplica[("Read Replica\nPostgreSQL")]
        Redis[("Redis\nCache + Queue")]
        Typesense["Typesense\nSearch Engine"]
    end

    subgraph "Storage Layer"
        ObjectStorage["Object Storage\nMedia Files"]
    end

    Browser --> LB
    MobileApp --> LB
    ExternalAPI --> LB

    LB --> WebServer1
    LB --> WebServer2
    LB --> WebServer3

    WebServer1 --> PrimaryDB
    WebServer2 --> PrimaryDB
    WebServer3 --> PrimaryDB

    WebServer1 --> ReadReplica
    WebServer2 --> ReadReplica
    WebServer3 --> ReadReplica

    WebServer1 --> Redis
    WebServer2 --> Redis
    WebServer3 --> Redis

    Redis --> HorizonWorker1
    Redis --> HorizonWorker2

    HorizonWorker1 --> PrimaryDB
    HorizonWorker2 --> PrimaryDB

    WebServer1 --> ReverbServer1
    WebServer2 --> ReverbServer1
    WebServer3 --> ReverbServer2

    Browser --> ReverbServer1
    Browser --> ReverbServer2

    WebServer1 --> Typesense
    WebServer2 --> Typesense
    WebServer3 --> Typesense

    WebServer1 --> ObjectStorage
    WebServer2 --> ObjectStorage
    WebServer3 --> ObjectStorage

    HorizonWorker1 --> ObjectStorage
    HorizonWorker2 --> ObjectStorage
```php
</details>

> **Note:** All diagrams are available in both light and dark modes in the [illustrations folder](../illustrations/index.md).

### 2.4.1 Scaling Considerations

<details>
<summary>Click to expand scaling considerations</summary>

| Component | Scaling Approach | Scaling Trigger |
|-----------|------------------|----------------|
| Web Servers | Horizontal scaling | CPU > 70%, Memory > 80% |
| Horizon Workers | Horizontal scaling | Queue backlog > 1000 jobs |
| Reverb Servers | Horizontal scaling | Concurrent connections > 10,000 |
| Database | Read replicas + Vertical scaling | Read query latency > 100ms |
| Typesense | Horizontal scaling (cluster) | Search latency > 200ms |
</details>

### 2.4.2 Infrastructure Requirements

<details>
<summary>Click to expand infrastructure requirements</summary>

| Component | Minimum Specs | Recommended Specs |
|-----------|---------------|-------------------|
| Web Servers | 2 vCPU, 4GB RAM | 4 vCPU, 8GB RAM |
| Horizon Workers | 2 vCPU, 4GB RAM | 4 vCPU, 8GB RAM |
| Reverb Servers | 2 vCPU, 4GB RAM | 4 vCPU, 8GB RAM |
| Database | 4 vCPU, 8GB RAM, 100GB SSD | 8 vCPU, 16GB RAM, 500GB SSD |
| Redis | 2 vCPU, 4GB RAM | 4 vCPU, 8GB RAM |
| Typesense | 2 vCPU, 4GB RAM | 4 vCPU, 8GB RAM |
</details>

### 2.5 Technology Stack

#### 2.5.1. Backend
- **Framework**: Laravel 12
- **PHP Version**: 8.4
- **Database**:
  - **Production**: PostgreSQL 15+ (primary database for production environment)
  - **Development/Testing**: SQLite (for simplified development and testing)
- **Cache & Queue**: Redis
- **Search**: Typesense via Laravel Scout with query-time permission filtering
- **WebSockets**: Laravel Reverb
- **Background Processing**: Laravel Horizon
- **Performance**: Laravel Octane with FrankenPHP (selected for HTTP/3 support and developer experience)

#### 2.5.2. Frontend
- **Framework**: Livewire 3.x
- **UI Components**:
  - **Flux**: `livewire/flux` - Core UI components
  - **Flux Pro**: `livewire/flux-pro` - Advanced UI components
- **JavaScript**: Alpine.js 3.x
- **CSS**: Tailwind CSS 4.x (default with Laravel 12)
  - Note: Filament 3.3+ uses Tailwind CSS 3.x internally, which is managed within Filament
- **Admin Panel**: Filament 3.3+

#### 2.5.3. Key Packages

##### Core Laravel Packages
- **Application Framework**: `laravel/framework` (^12.0)
- **REPL**: `laravel/tinker` (^2.10.1)
- **Performance**: `laravel/octane` (^2.9) with `runtime/frankenphp-symfony` (^0.2.0)
- **Queue Monitoring**: `laravel/horizon` (^5.32)
- **Debugging**: `laravel/telescope` (^5.7, dev)
- **Feature Flags**: `laravel/pennant` (^1.16)
- **Application Monitoring**: `laravel/pulse` (^1.0)
- **API Authentication**: `laravel/sanctum` (^4.0)
- **Authentication Scaffolding**: `laravel/fortify` (^1.25)
- **WebSockets**: `laravel/reverb` (^1.5)
- **Search**: `laravel/scout` (^10.15)
- **Log Viewer**: `laravel/pail` (^1.2, dev)
- **Docker Environment**: `laravel/sail` (^1.41, dev)

##### CQRS and State Management
- **CQRS**: `hirethunk/verbs` (^0.7.0)
- **Event Sourcing**: `spatie/laravel-event-sourcing` (^7.11)
- **State Machines**: `spatie/laravel-model-states` (^2.11)
- **Status History**: `spatie/laravel-model-status` (^1.18)

##### Authorization and Access Control
- **Permissions**: `spatie/laravel-permission` (^6.17)
- **Admin Panel Access Control**: `bezhansalleh/filament-shield` (^3.3)
- **User Impersonation**: `lab404/laravel-impersonate` (^1.7)

##### Data Management
- **Media**: `spatie/laravel-medialibrary` (^11.0) with `intervention/image` (^3.11)
- **Tags**: `spatie/laravel-tags` (^4.5)
- **Comments**: `spatie/laravel-comments` (^2.2), `spatie/laravel-comments-livewire` (^3.0)
- **Activity Log**: `spatie/laravel-activitylog` (^4.7)
- **Settings**: `spatie/laravel-settings` (^3.4)
- **Slugs**: `spatie/laravel-sluggable` (^3.7)
- **Hierarchies**: `staudenmeir/laravel-adjacency-list` (^1.25)
- **Single Table Inheritance**: `tightenco/parental` (^1.4)
- **User Tracking**: `wildside/userstamps` (^3.0)
- **Snowflake IDs**: `godruoyi/php-snowflake` (^3.2)
- **Backups**: `spatie/laravel-backup` (^9.3)
- **Health Checks**: `spatie/laravel-health` (^1.34)
- **Schedule Monitoring**: `spatie/laravel-schedule-monitor` (^3.0)
- **Geographic Data**: `nnjeim/world` (^1.1)
- **HTTP Client**: `php-http/curl-client` (^2.3)

##### Frontend and UI
- **Livewire Components**: `livewire/livewire` (^3.6.1), `livewire/volt` (^1.7.0)
- **UI Components**: `livewire/flux` (^2.1.1), `livewire/flux-pro` (^2.1)
- **CSS Framework**: `tailwindcss` (^4.1.6)
- **JavaScript Framework**: `alpinejs` (^3.14.9), `@alpinejs/focus` (^3.14.9)
- **UI Utilities**:
  - `class-variance-authority` (^0.7.1)
  - `clsx` (^2.1.1)
  - `tailwind-merge` (^3.3.0)
  - `tailwindcss-animate` (^1.0.7)
- **Build Tools**:
  - `@tailwindcss/vite` (^4.1.6)
  - `autoprefixer` (^10.4.21)
  - `vite` (^6.3.5)
  - `laravel-vite-plugin` (^1.2.0)
  - `vite-plugin-compression` (^0.5.1)
  - `vite-plugin-dynamic-import` (^1.6.0)
  - `vite-plugin-eslint` (^1.8.1)
  - `vite-plugin-inspector` (^1.0.4)
- **HTTP Client**: `axios` (^1.9.0)
- **WebSockets Client**: `laravel-echo` (^2.1.3), `pusher-js` (^8.4.0)
- **Admin Panel**: `filament/filament` (^3.3) and related packages
- **Filament Plugins**:
  - `filament/spatie-laravel-media-library-plugin` (^3.3)
  - `filament/spatie-laravel-tags-plugin` (^3.3)
  - `filament/spatie-laravel-translatable-plugin` (^3.3)
  - `bezhansalleh/filament-shield` (^3.3)
  - `shuvroroy/filament-spatie-laravel-backup` (^2.2)
  - `shuvroroy/filament-spatie-laravel-health` (^2.3)
  - `rmsramos/activitylog` (^1.0)
  - `mvenghaus/filament-plugin-schedule-monitor` (^3.0)

##### Security and Compliance
- **Cookie Consent**: `statikbe/laravel-cookie-consent` (^1.10)
- **Security Advisories**: `roave/security-advisories` (dev-latest, dev)

##### Development and Testing
- **Code Styling**: `laravel/pint` (^1.18, dev)
- **Browser Testing**: `laravel/dusk` (^8.3, dev)
- **Testing Framework**:
  - `pestphp/pest` (^3.8, dev)
  - `pestphp/pest-plugin-laravel` (^3.2, dev)
  - `pestphp/pest-plugin-arch` (^3.1, dev)
  - `spatie/pest-plugin-snapshots` (^2.2, dev)
  - `brianium/paratest` (^7.8, dev)
  - `mockery/mockery` (^1.6, dev)
- **IDE Helper**: `barryvdh/laravel-ide-helper` (^3.5, dev)
- **Debugging**:
  - `barryvdh/laravel-debugbar` (^3.15, dev)
  - `itsgoingd/clockwork` (^5.3, dev)
  - `spatie/laravel-ray` (^1.40, dev)
  - `spatie/laravel-web-tinker` (^1.10, dev)
  - `spatie/laravel-blade-comments` (^1.4, dev)
  - `spatie/laravel-horizon-watcher` (^1.1, dev)
  - `spatie/laravel-login-link` (^1.6, dev)
  - `spatie/laravel-missing-page-redirector` (^2.11, dev)
  - `spatie/laravel-queueable-action` (^2.16, dev)
- **Static Analysis**:
  - `larastan/larastan` (^3.4, dev)
  - `rector/rector` (^2.0, dev)
  - `rector/type-perfect` (^2.1, dev)
  - `driftingly/rector-laravel` (^2.0, dev)
  - `php-parallel-lint/php-parallel-lint` (^1.4, dev)
- **Code Quality**:
  - `nunomaduro/phpinsights` (^2.13, dev)
  - `nunomaduro/collision` (^8.6, dev)
  - `infection/infection` (^0.29.14, dev)
  - `laravel-shift/blueprint` (^2.12, dev)
  - `ergebnis/composer-normalize` (^2.47, dev)
  - `jasonmccreary/laravel-test-assertions` (^2.8, dev)
  - `peckphp/peck` (^0.1.3, dev)
- **End-to-End Testing**:
  - `@playwright/test` (^1.52.0, npm package)
  - `vitest` (^3.1.3, npm package)
- **Fake Data**: `fakerphp/faker` (^1.23, dev)

##### Enhanced Functionality
- **Enhanced Enums**: Native PHP 8.4 Enums with custom methods for `label()` and `color()` following Filament standards

---

## 3. Database Architecture

### 3.1. Database Schema Overview

The database schema consists of core application entities and their relationships, as defined in the PRD. While PostgreSQL is recommended for production, SQLite can be used for development and testing environments due to its simplicity and zero configuration.

The schema follows these conventions:

- **Primary Keys**: `id` (BIGINT UNSIGNED, AUTO_INCREMENT)
- **Snowflake IDs**: `snowflake_id` (BIGINT UNSIGNED, UNIQUE, INDEX)
- **Userstamps**: `created_by`, `updated_by`, `deleted_by` (BIGINT UNSIGNED, NULLABLE, FK to `users.id`, INDEX)
- **Soft Deletes**: `deleted_at` (TIMESTAMP, NULLABLE, INDEX)
- **Slugs**: `slug` (STRING, UNIQUE, INDEX) for User, Team, Post, Todo models
- **State Machines**: `status` column (STRING, INDEX) for User, Team, Post, Todo
- **Hierarchies**: `parent_id`, `path`, `depth` for Team, Category, Todo
- **Standard Timestamps**: `created_at`, `updated_at`

### 3.2. Entity Relationship Diagram (ERD)

<details>
<summary>Light Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
erDiagram
    USER ||--o{ POST : "authors"
    USER ||--o{ TODO : "assigned to"
    USER ||--o{ MESSAGE : "sends"
    USER ||--o{ COMMENTS : "comments"
    USER ||--o{ ACTIVITY_LOG : "causer"
    USER }o--o{ CONVERSATION : "participates in"
    USER }|..|{ ROLE : "has"

    TEAM ||--o{ TEAM : "parent of"
    TEAM ||--|{ CATEGORY : "has"
    TEAM ||--o{ TODO : "related to"

    CATEGORY ||--o{ CATEGORY : "parent of"

    POST }o..o{ CATEGORY : "categorized as"
    POST }o..o{ TAGS : "tagged with"
    POST }o..o{ MEDIA : "has media"
    POST }o..o{ COMMENTS : "has comments"

    TODO }o..o{ CATEGORY : "categorized as"
    TODO }o..o{ TAGS : "tagged with"
    TODO }o..o{ MEDIA : "has media"
    TODO }o..o{ COMMENTS : "has comments"

    CONVERSATION ||--o{ MESSAGE : "contains"

    ROLE }|..|{ PERMISSION : "has"

    COMMAND_LOG ||--o{ SNAPSHOT : "generates"
```text
</details>

<details>
<summary>Dark Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
erDiagram
    USER ||--o{ POST : "authors"
    USER ||--o{ TODO : "assigned to"
    USER ||--o{ MESSAGE : "sends"
    USER ||--o{ COMMENTS : "comments"
    USER ||--o{ ACTIVITY_LOG : "causer"
    USER }o--o{ CONVERSATION : "participates in"
    USER }|..|{ ROLE : "has"

    TEAM ||--o{ TEAM : "parent of"
    TEAM ||--|{ CATEGORY : "has"
    TEAM ||--o{ TODO : "related to"

    CATEGORY ||--o{ CATEGORY : "parent of"

    POST }o..o{ CATEGORY : "categorized as"
    POST }o..o{ TAGS : "tagged with"
    POST }o..o{ MEDIA : "has media"
    POST }o..o{ COMMENTS : "has comments"

    TODO }o..o{ CATEGORY : "categorized as"
    TODO }o..o{ TAGS : "tagged with"
    TODO }o..o{ MEDIA : "has media"
    TODO }o..o{ COMMENTS : "has comments"

    CONVERSATION ||--o{ MESSAGE : "contains"

    ROLE }|..|{ PERMISSION : "has"

    COMMAND_LOG ||--o{ SNAPSHOT : "generates"
```php
</details>

> **Note:** All diagrams are available in both light and dark modes in the [illustrations folder](../illustrations/index.md).

### 3.3. Core Entities

#### 3.3.1. User
- **Table**: `users`
- **Key Fields**: id, snowflake_id, slug, type, email, password, email_verified_at, status, userstamps, timestamps, softDeletes
- **Relationships**: authors Posts, assigned to Todos, sends Messages, comments, participates in Conversations, has Roles
- **State Machine**: `Invited`, `PendingActivation`, `Active`, `Suspended`, `Deactivated`

#### 3.3.2. Team
- **Table**: `teams`
- **Key Fields**: id, snowflake_id, slug, name, parent_id, path, depth, status, userstamps, timestamps, softDeletes
- **Relationships**: parent of Teams, has Categories, related to Todos
- **State Machine**: `Forming`, `Active`, `Archived`
- **Hierarchy**: Self-referential with parent_id, path, depth

#### 3.3.3. Category
- **Table**: `categories`
- **Key Fields**: id, snowflake_id, team_id, name, slug, parent_id, path, depth, userstamps, timestamps, softDeletes
- **Relationships**: belongs to Team, parent of Categories, categorizes Posts and Todos
- **Hierarchy**: Self-referential with parent_id, path, depth
- **Team Scoping**: Strict team_id FK (NN), no global categories

#### 3.3.4. Post
- **Table**: `posts`
- **Key Fields**: id, snowflake_id, user_id, title, slug, content, excerpt, status, published_at, scheduled_for, userstamps, timestamps, softDeletes
- **Relationships**: authored by User, categorized by Categories, tagged with Tags, has Media, has Comments
- **State Machine**: `Draft`, `PendingReview`, `Published`, `Scheduled`, `Archived`

#### 3.3.5. Todo
- **Table**: `todos`
- **Key Fields**: id, snowflake_id, title, slug, description, user_id, team_id, parent_id, path, depth, status, due_date, completed_at, userstamps, timestamps, softDeletes
- **Relationships**: assigned to User, associated with Team, parent of Todos, categorized by Categories, tagged with Tags, has Media, has Comments
- **State Machine**: `Pending`, `InProgress`, `Completed`, `Cancelled`
- **Hierarchy**: Self-referential with parent_id, path, depth

#### 3.3.6. Conversation
- **Table**: `conversations`
- **Key Fields**: id, uuid, name, type, userstamps, timestamps, softDeletes
- **Relationships**: has Messages, has Participants (Users)

#### 3.3.7. Message
- **Table**: `messages`
- **Key Fields**: id, uuid, conversation_id, user_id, body, userstamps, timestamps, softDeletes
- **Relationships**: belongs to Conversation, sent by User

#### 3.3.8. Command Log and Snapshot
- **Tables**: `command_logs`, `snapshots`
- **Key Fields**: As per `hirethunk/verbs`
- **Relationships**: Command Log generates Snapshots

### 3.4. Database Migrations Strategy

#### 3.4.1. Migration Approach

Database migrations will be created following Laravel's migration system:

1. **Core Tables**: Create base tables for all core entities
2. **Foreign Keys**: Add foreign key constraints after all tables are created
3. **Indexes**: Add indexes for performance optimization
4. **Seeding**: Provide seeders for development and testing environments
5. **Database Agnostic**: Ensure migrations are compatible with both PostgreSQL and SQLite to support different environments
   - Use Laravel's schema builder features that abstract database differences
   - Be mindful of SQLite limitations (e.g., ALTER TABLE constraints, JSON support)
   - Consider conditional schema modifications based on database driver

#### 3.4.2. Migration Sequence

<details>
<summary>Light Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A["Start Migration Process"] --> B["Create Base Tables (No Foreign Keys)"]
    B --> C["Add Foreign Key Constraints"]
    C --> D["Add Indexes"]
    D --> E["Run Seeders"]
    E --> F["Migration Complete"]

    subgraph "Base Tables Order"
        B1["1. Users"] --> B2["2. Teams"] --> B3["3. Categories"] --> B4["4. Todos"]
        B4 --> B5["5. Posts"] --> B6["6. Conversations"] --> B7["7. Messages"]
        B7 --> B8["8. Roles & Permissions"] --> B9["9. Media"] --> B10["10. Tags"]
        B10 --> B11["11. Comments"] --> B12["12. Settings"] --> B13["13. Activity Logs"]
    end
```text
</details>

<details>
<summary>Dark Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
flowchart TD
    A["Start Migration Process"] --> B["Create Base Tables (No Foreign Keys)"]
    B --> C["Add Foreign Key Constraints"]
    C --> D["Add Indexes"]
    D --> E["Run Seeders"]
    E --> F["Migration Complete"]

    subgraph "Base Tables Order"
        B1["1. Users"] --> B2["2. Teams"] --> B3["3. Categories"] --> B4["4. Todos"]
        B4 --> B5["5. Posts"] --> B6["6. Conversations"] --> B7["7. Messages"]
        B7 --> B8["8. Roles & Permissions"] --> B9["9. Media"] --> B10["10. Tags"]
        B10 --> B11["11. Comments"] --> B12["12. Settings"] --> B13["13. Activity Logs"]
    end
```php
</details>

> **Note:** All diagrams are available in both light and dark modes in the [illustrations folder](../illustrations/index.md).

#### 3.4.3. Schema Changes and Data Migrations

For future schema changes after the initial deployment:

1. **Planning**:
   - Document the required changes
   - Assess impact on existing data
   - Determine if data migration is needed

2. **Implementation**:
   - Create new migration files for schema changes
   - Use Laravel's schema builder for database-agnostic changes
   - For complex changes, use separate migration files for different database drivers

3. **Data Migration**:
   - For simple data transformations, include in migration files
   - For complex data migrations, create dedicated Artisan commands
   - Consider using batch processing for large datasets

4. **Testing**:
   - Test migrations on a copy of production data
   - Verify data integrity after migration
   - Measure migration time for planning deployment window

5. **Deployment**:
   - Schedule during low-traffic periods
   - Consider using maintenance mode for critical migrations
   - Have rollback plan ready

#### 3.4.4. Rollback Procedures

1. **Schema Rollback**:
   - Ensure all migrations have proper `down()` methods
   - Test rollback procedures before deployment
   - Document specific rollback commands

2. **Data Rollback**:
   - For critical migrations, create backup of affected data
   - Implement restore procedures for data
   - Consider point-in-time recovery options with database backups

---

## 4. Authentication & Authorization

### 4.1. Authentication

#### 4.1.1. User Registration
- Configurable as Open or Invite-Only via application settings
- Standard email/password registration
- Social authentication via `laravel/socialite`

#### 4.1.2. Authentication Flow

<details>
<summary>Light Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
sequenceDiagram
    actor User
    participant Browser
    participant App as Laravel Application
    participant Auth as Authentication Service
    participant MFA as MFA Service
    participant DB as Database

    %% Registration Flow
    User->>Browser: Access Registration Page
    Browser->>App: GET /register
    App->>Browser: Return Registration Form
    User->>Browser: Fill Registration Form
    Browser->>App: POST /register
    App->>Auth: Validate Registration Data
    Auth->>DB: Create User Account
    DB-->>Auth: User Created
    Auth->>App: Return Success
    App->>Browser: Redirect to Email Verification

    %% Login Flow
    User->>Browser: Access Login Page
    Browser->>App: GET /login
    App->>Browser: Return Login Form
    User->>Browser: Enter Credentials
    Browser->>App: POST /login
    App->>Auth: Validate Credentials
    Auth->>DB: Check Credentials
    DB-->>Auth: Credentials Valid
    Auth->>DB: Check MFA Enabled
    DB-->>Auth: MFA Status

    alt MFA Enabled
        Auth->>App: Request MFA Code
        App->>Browser: Show MFA Input Form
        User->>Browser: Enter MFA Code
        Browser->>App: POST MFA Code
        App->>MFA: Validate MFA Code
        MFA-->>App: MFA Valid
    end

    Auth->>App: Create Session
    App->>Browser: Redirect to Dashboard
```text
</details>

<details>
<summary>Dark Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
sequenceDiagram
    actor User
    participant Browser
    participant App as Laravel Application
    participant Auth as Authentication Service
    participant MFA as MFA Service
    participant DB as Database

    %% Registration Flow
    User->>Browser: Access Registration Page
    Browser->>App: GET /register
    App->>Browser: Return Registration Form
    User->>Browser: Fill Registration Form
    Browser->>App: POST /register
    App->>Auth: Validate Registration Data
    Auth->>DB: Create User Account
    DB-->>Auth: User Created
    Auth->>App: Return Success
    App->>Browser: Redirect to Email Verification

    %% Login Flow
    User->>Browser: Access Login Page
    Browser->>App: GET /login
    App->>Browser: Return Login Form
    User->>Browser: Enter Credentials
    Browser->>App: POST /login
    App->>Auth: Validate Credentials
    Auth->>DB: Check Credentials
    DB-->>Auth: Credentials Valid
    Auth->>DB: Check MFA Enabled
    DB-->>Auth: MFA Status

    alt MFA Enabled
        Auth->>App: Request MFA Code
        App->>Browser: Show MFA Input Form
        User->>Browser: Enter MFA Code
        Browser->>App: POST MFA Code
        App->>MFA: Validate MFA Code
        MFA-->>App: MFA Valid
    end

    Auth->>App: Create Session
    App->>Browser: Redirect to Dashboard
```php
</details>

> **Note:** All diagrams are available in both light and dark modes in the [illustrations folder](../illustrations/index.md).

#### 4.1.3. Multi-Factor Authentication (MFA)
- Implementation using Laravel Fortify's built-in 2FA for TOTP-based authentication
- Custom UI components built with Flux to provide a modern, user-friendly experience
- QR code setup, recovery codes provided
- Comprehensive testing for the authentication flow
- Configurable as optional for all users or mandatory for specific roles
- Notifications for MFA setup, changes, and recovery code usage

#### 4.1.4. API Authentication
- Laravel Sanctum API Tokens for the public-facing API

### 4.2. Authorization

#### 4.2.1. Role-Based Access Control (RBAC)
- Implementation using `spatie/laravel-permission`
- Predefined roles: `Admin`, `Manager`, `User`, `Customer`, `Guest`
- Custom permissions for fine-grained access control

#### 4.2.2. Team-Based Permissions
- Explicit permissions per team (no inheritance)
- Team-contextual policies for authorization checks

#### 4.2.3. Policy Implementation
- Laravel Policies for all core models
- Integration with Filament for admin panel access control

---

## 5. Routing and Model Binding

### 5.1. Route Model Binding with Snowflake IDs and Slugs

#### 5.1.1. Snowflake ID Implementation
- **Generation**: Snowflake IDs are generated using the `godruoyi/php-snowflake` package
- **Storage**: Stored in the `snowflake_id` column (BIGINT UNSIGNED) for all core models
- **Benefits**:
  - Globally unique across distributed systems
  - Time-sortable (contains timestamp component)
  - No coordination needed between nodes
  - More compact than UUIDs

#### 5.1.2. Slug Implementation
- **Generation**: Slugs are generated using the `spatie/laravel-sluggable` package
- **Storage**: Stored in the `slug` column (STRING) for User, Team, Post, and Todo models
- **Benefits**:
  - Human-readable URLs
  - SEO-friendly
  - Provides a level of obfuscation for internal IDs

#### 5.1.3. Route Model Binding Strategy
- **Primary Binding**: Models are primarily bound by their Snowflake ID
  ```php
  // Route definition
  Route::get('/posts/{post}', [PostController::class, 'show']);

  // Model implementation
  public function getRouteKeyName()
  {
      return 'snowflake_id';
  }
  ```markdown
- **Slug Binding**: For SEO-sensitive routes, models can be bound by slug
  ```php
  // Route definition
  Route::get('/posts/{post:slug}', [PostController::class, 'show']);
  ```markdown
- **Composite Binding**: For enhanced security, models can be bound by both Snowflake ID and slug
  ```php
  // Custom route binding in RouteServiceProvider
  Route::bind('post', function ($value) {
      return Post::where('snowflake_id', $value)
          ->orWhere('slug', $value)
          ->firstOrFail();
  });
  ```markdown
### 5.2. API Design

#### 5.2.1. Public-Facing API

##### 5.2.1.1. API Architecture
- RESTful API design
- JSON response format
- URI versioning (`/api/v1/...`)
- Laravel API Resources for data transformation

##### 5.2.1.2. Authentication & Authorization
- Laravel Sanctum API Tokens for authentication
- Role-based authorization via `spatie/laravel-permission`
- Rate limiting using Laravel's built-in rate limiting

##### 5.2.1.3. Endpoints
- Initial scoped resources (Read-Only): Users, Teams, Categories, Posts
- Future CRUD endpoints to be defined by priority

##### 5.2.1.4. API Documentation Framework
- OpenAPI (Swagger) Specification
- Generated using `vyuldashev/laravel-openapi` package
- Documentation structure:

<details>
<summary>Click to expand OpenAPI specification example</summary>

```yaml
openapi: 3.0.0
info:
  title: Enhanced Laravel Application API
  version: 1.0.0
  description: Public API for the Enhanced Laravel Application
  contact:
    name: API Support
    email: api-support@example.com

servers:
  - url: https://api.example.com/api/v1
    description: Production API Server
  - url: https://staging-api.example.com/api/v1
    description: Staging API Server

security:
  - bearerAuth: []

paths:
  /users:
    get:
      summary: List users
      description: Returns a paginated list of users
      parameters:
        - name: page
          in: query
          schema:
            type: integer
            default: 1
        - name: per_page
          in: query
          schema:
            type: integer
            default: 15
      responses:
        '200':
          description: Successful response
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/UserCollection'
        '401':
          $ref: '#/components/responses/Unauthorized'
        '403':
          $ref: '#/components/responses/Forbidden'

components:
  securitySchemes:
    bearerAuth:
      type: http
      scheme: bearer
      bearerFormat: JWT

  schemas:
    User:
      type: object
      properties:
        id:
          type: string
          format: snowflake
        name:
          type: string
        email:
          type: string
          format: email
        # Additional properties...

    UserCollection:
      type: object
      properties:
        data:
          type: array
          items:
            $ref: '#/components/schemas/User'
        meta:
          $ref: '#/components/schemas/PaginationMeta'

    PaginationMeta:
      type: object
      properties:
        current_page:
          type: integer
        from:
          type: integer
        last_page:
          type: integer
        path:
          type: string
        per_page:
          type: integer
        to:
          type: integer
        total:
          type: integer

  responses:
    Unauthorized:
      description: Unauthorized
      content:
        application/json:
          schema:
            $ref: '#/components/schemas/Error'

    Forbidden:
      description: Forbidden
      content:
        application/json:
          schema:
            $ref: '#/components/schemas/Error'

    Error:
      type: object
      properties:
        message:
          type: string
        errors:
          type: object
```text
</details>

- Interactive documentation UI available at `/api/documentation`
- API versioning reflected in documentation
- Authentication methods clearly documented
- Examples provided for all endpoints

#### 5.2.2. Internal API

##### 5.2.2.1. Livewire Components
- Livewire components for frontend-backend communication
- Alpine.js for client-side interactivity

##### 5.2.2.2. Real-time Communication
- Laravel Reverb for WebSocket communication
- Event broadcasting for real-time updates

---

## 6. User Interface (UI/UX) Architecture

### 6.1. Frontend Technology Stack
- **CSS Framework**: Tailwind CSS 4.x (default with Laravel 12)
  - Modern utility-first approach for rapid UI development
  - Note: Filament 3.3+ uses Tailwind CSS 3.x internally, which is managed within Filament
- **JavaScript Framework**: Alpine.js 3.x for client-side interactivity
- **Component Framework**: Livewire 3.x for dynamic, reactive components
- **UI Component Libraries**:
  - **Flux**: Core UI components from the official Livewire UI kit
  - **Flux Pro**: Advanced UI components including Autocomplete, Calendar, Date Picker, Rich Text Editor, Tables, etc.
- **Admin Panel**: Filament 3.3+ for comprehensive administration interface

### 6.2. UI Components
- **Flux Components**: Leverage the official Livewire UI kit for core components
  - Buttons, Badges, Cards, Inputs, Dropdowns, Modals, etc.
- **Flux Pro Components**: Use advanced components for complex UI requirements
  - Autocomplete, Calendar, Date Picker, Rich Text Editor, Tables, etc.
- **Custom Components**: Extend Flux components or develop custom Livewire components as needed
- **Design System**: Implement consistent design patterns across the application using Flux's theming capabilities
- **Responsive Design**: Ensure usability across devices (desktop, tablet, mobile)
- **Accessibility**: Strive for WCAG 2.1 AA compliance

### 6.3. Livewire/Volt Functional Paradigm

#### 6.3.1. Overview
The application will use Livewire's Volt Single File Components (SFC) with a functional programming approach as the primary endpoint technology. This approach provides a clean, maintainable way to build dynamic interfaces with less boilerplate than traditional class-based Livewire components.

#### 6.3.2. When to Use Volt vs. Traditional Livewire
- **Use Volt SFCs for:**
  - Most user-facing pages and components
  - Components with moderate complexity
  - Rapid development of new features
  - Improved readability and maintainability

- **Use Traditional Class-based Livewire for:**
  - Complex components with extensive logic
  - Components that extend existing Livewire components
  - Components that require advanced lifecycle hooks
  - Components that need to be reused across multiple projects

#### 6.3.3. Implementing the Functional Paradigm
- **Directory Structure**: Organize Volt components in feature-based directories
- **State Management**: Use state functions for managing component state
- **Computed Properties**: Leverage computed properties for derived values
- **Actions**: Implement actions as pure functions where possible
- **Lifecycle Hooks**: Use hooks for component lifecycle management

#### 6.3.4. Integration with CQRS Pattern
Volt components integrate seamlessly with the CQRS pattern:

```php
<?php

use function Livewire\Volt\{state, computed, mount, action};
use App\Commands\CreateTodo;
use App\Queries\GetTodosByUser;
use Hirethunk\Verbs\CommandBus;

// State declaration
state([
    'title' => '',
    'description' => '',
    'todos' => [],
]);

// Lifecycle hook
mount(function (CommandBus $commandBus) {
    $this->todos = app(GetTodosByUser::class)->execute(auth()->id());
});

// Computed property
computed(function () {
    return count($this->todos);
})->as('todoCount');

// Action with CQRS command
action(function (CommandBus $commandBus) {
    $command = new CreateTodo([
        'title' => $this->title,
        'description' => $this->description,
        'user_id' => auth()->id(),
    ]);

    $result = $commandBus->dispatch($command);

    if ($result->wasSuccessful()) {
        $this->title = '';
        $this->description = '';
        $this->todos = app(GetTodosByUser::class)->execute(auth()->id());
        $this->dispatch('todo-created');
    }
})->as('createTodo');
```

This example demonstrates:
- Dispatching commands via the `hirethunk/verbs` command bus
- Using dedicated action functions to encapsulate command creation and dispatch
- Implementing query functions that call query services
- Separating read and write operations within the component

### 6.4. Visual Elements
- **Model Avatars**: Visual identification for Users, Teams, and Chat
- **Status Indicators**: Use colors and labels from Enums to provide visual cues
- **Team Context**: Filter UI elements based on team context
- **Hierarchy Visualization**: Provide clear visual feedback for hierarchical structures

### 6.5. User Experience Considerations
- **Form Validation**: Immediate feedback for validation errors, including complex move validation
- **Loading States**: Clear indication of loading/processing states
- **Error Handling**: User-friendly error messages and recovery options
- **Confirmation Dialogs**: For destructive or significant actions

### 6.6. Admin Interface
- **Filament Resources**: Custom resources for all core models
- **Dashboard**: Key metrics and statistics
- **Command History UI**: Access to command history and snapshots with diffing capabilities
- **Custom Pages**: For specialized functionality like reporting

---

## 7. Security Architecture

### 7.1. Authentication Security
- Secure password hashing (Laravel's default)
- Multi-Factor Authentication (MFA) for enhanced security
- Rate limiting for login attempts
- Session management and secure cookies

### 7.2. Authorization Security
- Role-based access control (RBAC)
- Team-based permissions
- Policy-based authorization

### 7.3. Data Security
- Input validation and sanitization
- Protection against OWASP Top 10 vulnerabilities
- CSRF protection
- XSS protection
- SQL injection protection

### 7.4. API Security
- Token-based authentication (Sanctum)
- Rate limiting
- Scope-based access control

### 7.5. File Upload Security
- Validation of file types and sizes
- Secure storage of uploaded files
- Restricted tiered approach for file uploads:
  - **Initial Phase (v1.0)**: Only low-risk files permitted (images, plain text)
    - Enhanced validation only (extension, MIME type, content analysis)
    - No external virus scanning service required
  - **Future Phase**: Medium-risk files (PDFs, standard Office documents) using OPSWAT MetaDefender
    - Not included in initial release
    - Will be implemented in a later version
  - **Not on Roadmap**: High-risk files (executables, scripts, Office documents with macros)
    - These file types will not be supported

### 7.6. Audit and Logging
- Activity logging via `spatie/laravel-activitylog`
- Command history via `hirethunk/verbs`
- User tracking (userstamps)

### 7.7. Security Threat Model

The application implements a comprehensive security threat model based on the STRIDE methodology:

<details>
<summary>Click to expand security threat model</summary>

| Threat Type | Description | Mitigation Strategies |
|-------------|-------------|------------------------|
| **Spoofing** | Impersonating another user or system | - Strong authentication (including MFA)<br>- Secure session management<br>- API token validation<br>- Rate limiting on authentication endpoints |
| **Tampering** | Unauthorized modification of data | - Input validation<br>- CSRF protection<br>- Signed routes for sensitive operations<br>- Audit logging<br>- Command history |
| **Repudiation** | Denying having performed an action | - Comprehensive audit logging<br>- Command history with user tracking<br>- Userstamps on all models<br>- Secure logging mechanisms |
| **Information Disclosure** | Exposing sensitive information | - Data encryption<br>- Authorization checks<br>- Team scoping<br>- Secure API design<br>- Content security policies<br>- Proper error handling |
| **Denial of Service** | Making the system unavailable | - Rate limiting<br>- Request throttling<br>- Efficient database queries<br>- Caching strategies<br>- Monitoring and alerting |
| **Elevation of Privilege** | Gaining unauthorized capabilities | - Role-based access control<br>- Policy-based authorization<br>- Principle of least privilege<br>- Regular permission audits |
</details>

#### 7.7.1. Threat Assessment Process

1. **Asset Identification**: Identify critical assets (user data, content, configurations)
2. **Threat Identification**: Apply STRIDE to each asset
3. **Risk Assessment**: Evaluate likelihood and impact
4. **Mitigation Planning**: Implement controls based on risk level
5. **Validation**: Regular security testing and code reviews

#### 7.7.2. Security Testing Strategy

- Regular automated security scanning
- Manual penetration testing before major releases
- Dependency vulnerability scanning
- Code reviews with security focus
- Security-focused test cases

---

## 8. Search Implementation

### 8.1. Search Architecture
- Typesense search engine via Laravel Scout
- Real-time indexing via Horizon queues
- Permission-aware filtering

### 8.2. Indexed Models
- User
- Team
- Category
- Todo
- Post
- Tag

### 8.3. Search Implementation Details
- `Laravel\Scout\Searchable` trait on relevant models
- `toSearchableArray()` method to define indexed fields including `team_id`, `status`, and `is_public` flags
- `shouldBeSearchable()` method to control index inclusion based on model status
- **Permission filtering at query time** (selected approach):
  - Typesense `filter_by` clauses constructed dynamically based on user permissions and context
  - Avoids data duplication in search indexes
  - Provides flexibility for complex permission scenarios
  - Aligns with team scoping concept

### 8.4. Search UI
- Livewire components for frontend search interface
- Filament integration for admin search

---

## 9. Real-time Features

### 9.1. WebSocket Architecture
- Laravel Reverb as the WebSocket server
- Event broadcasting for real-time updates

### 9.2. Real-time Notifications
- In-app notifications via Reverb + Livewire
- Notification channels: Database, Mail, Slack

### 9.3. Chat Implementation
- Fully custom implementation using Livewire + Reverb (selected for complete control and team scoping integration)
- Models: `Conversation`, `Message`, `Participant`
- Advanced features implemented in phases:
  1. Typing indicators
  2. Read receipts
  3. Message quoting/replying
  4. File attachments
  5. Message editing/deletion (with time-limited policy)

### 9.4. Data Feed Subscriptions
- Real-time data change notifications/feeds
- Controlled by specific permission (`subscribe_to_data_feeds`)

---

## 10. Performance Optimization

### 10.1. Performance Targets

<details>
<summary>Click to expand performance targets</summary>

| Operation | Target Response Time | Maximum Acceptable Time |
|-----------|----------------------|-------------------------|
| Page Load (First Contentful Paint) | < 1.5s | 3s |
| Page Load (Time to Interactive) | < 2.5s | 5s |
| API Response (Simple Query) | < 100ms | 300ms |
| API Response (Complex Query) | < 300ms | 1s |
| Search Results | < 200ms | 500ms |
| Real-time Message Delivery | < 100ms | 500ms |
| Background Job Processing | < 5s | 30s |
</details>

### 10.2. Caching Strategy

<details>
<summary>Click to expand caching strategy</summary>

| Content Type | Cache Location | TTL | Invalidation Strategy |
|--------------|----------------|-----|------------------------|
| API Responses | Redis | 5 minutes | Tag-based invalidation |
| Database Queries | Redis | 10 minutes | Model event listeners |
| Static Assets | CDN | 1 week | Version-based URLs |
| User Permissions | Redis | 15 minutes | Role/permission updates |
| Search Results | Redis | 5 minutes | Model updates |
</details>

### 10.3. Database Optimization

<details>
<summary>Click to expand database optimization</summary>

| Table | Indexing Strategy | Partitioning Strategy |
|-------|-------------------|------------------------|
| Users | Compound index on (email, deleted_at) | None |
| Teams | Compound index on (slug, deleted_at) | None |
| Posts | Compound index on (team_id, status, created_at) | By team_id (future) |
| Messages | Compound index on (conversation_id, created_at) | By conversation_id and date |
| Command Logs | Compound index on (subject_type, subject_id, created_at) | By month |
</details>

### 10.4. N+1 Query Prevention

- Eager loading relationships in controllers/queries
- Global scopes for commonly joined tables
- Query debugging in development environment
- Monitoring slow queries in production

### 10.5. Application Performance
- Laravel Octane for high-performance application serving
  - FrankenPHP selected as the primary option for HTTP/3 support and developer experience
  - Swoole and RoadRunner available as alternatives based on deployment requirements
- Efficient database queries and indexing
- Caching strategy for frequently accessed data
- Lazy loading of relationships
- Eager loading where appropriate

### 10.6. Database Performance
- Proper indexing strategy
- Query optimization
- Database connection pooling
- Consider read replicas for reporting queries

### 10.7. Frontend Performance
- Livewire for efficient DOM updates
- Asset optimization (minification, compression)
- Lazy loading of components
- Efficient use of Alpine.js

### 10.4. Monitoring and Metrics
- Laravel Pulse for application monitoring
- Horizon dashboard for queue monitoring
- Custom metrics for business-specific performance indicators

---

## 11. Scalability Considerations

### 11.1. Horizontal Scaling
- Stateless design where possible
- Session storage in Redis
- Load balancing for web servers
- Horizontal scaling for Octane, Horizon, Reverb, DB, Typesense

### 11.2. Vertical Scaling
- Efficient use of server resources
- Performance optimization to reduce resource requirements

### 11.3. Database Scaling
- Connection pooling
- Read replicas for reporting queries
- Consider sharding for very large datasets (future consideration)

---

## 12. Deployment Architecture

### 12.1. Development Environment
- Laravel Sail for local development
- Docker containers for consistent environments
- Development-specific configuration
- SQLite database for simplified setup and faster testing
- Easy transition between SQLite (development) and PostgreSQL (production) using Laravel's database abstraction

### 12.2. Testing Environment
- Automated testing environment
- CI/CD pipeline integration
- Test database seeding

### 12.3. Staging Environment
- Mirror of production environment
- Final testing before production deployment
- Performance testing

### 12.4. Production Environment
- High-availability setup
- Load balancing
- Monitoring and alerting
- Backup and disaster recovery

---

## 13. Testing Strategy

### 13.1. Unit Testing
- PHPUnit/Pest for unit testing
- Test individual components in isolation
- Mock dependencies

### 13.2. Feature Testing
- Test complete features
- API endpoint testing
- Form submission testing

### 13.3. Integration Testing
- Test integration between components
- Database integration testing
- Third-party service integration testing

### 13.4. End-to-End Testing
- Browser testing with Playwright
- Integration with Laravel via custom helpers
- Cross-browser testing (Chromium, Firefox, WebKit)
- Test complete user flows
- Visual regression testing

#### 13.4.1. Playwright Implementation
- **Setup**: Playwright configured with Laravel integration
- **Test Structure**: Page Object Model pattern for maintainable tests
- **Authentication**: Helper methods for authentication flows
- **Database Seeding**: Integration with Laravel factories and seeders
- **Visual Testing**: Screenshot comparison for UI regression testing
- **CI Integration**: Configured to run in GitHub Actions

#### 13.4.2. Key Test Scenarios
- User registration and authentication flows
- Team creation and management
- Content creation and publishing workflows
- Permission-based access control
- Real-time feature testing

### 13.5. Performance Testing
- Load testing
- Stress testing
- Endurance testing

---

## 14. Maintenance and Monitoring

### 14.1. Logging
- Structured logging
- Log aggregation
- Error tracking with Sentry

### 14.2. Monitoring
- Application monitoring with Laravel Pulse
- Server monitoring
- Database monitoring
- Queue monitoring with Horizon

### 14.3. Backup and Recovery
- Automated database backups with `spatie/laravel-backup`
- File storage backups
- Disaster recovery plan

### 14.4. Updates and Patches
- Regular security updates
- Dependency updates
- Feature updates

---

## 15. Key Decisions

The following key decisions have been made and incorporated into this architecture document:

1. **Database Strategy**: PostgreSQL 15+ for production with SQLite for development/testing
2. **Octane Server**: FrankenPHP selected for its modern features and developer experience
3. **Enhanced Enum Implementation**: Native PHP 8.4 Enums with custom methods for `label()` and `color()`
4. **Chat Implementation**: Fully custom implementation using Livewire + Reverb
5. **Search Filtering Strategy**: Permission filtering at query time using Typesense `filter_by` clauses
6. **UI Component Libraries**: Flux and Flux Pro for consistent, high-quality UI components
7. **Route Model Binding**: Implementation based on Snowflake ID and/or slug for flexibility and SEO
8. **End-to-End Testing**: Playwright for comprehensive browser testing with Laravel integration
9. **Authentication**: Laravel Fortify for MFA/2FA implementation with custom Flux UI components
10. **API Documentation**: `vyuldashev/laravel-openapi` for generating OpenAPI documentation
11. **Hierarchical Data**: Pre-calculate all potential depths for move validation
12. **Command History UI**: Custom Filament pages and resources for history and diffing
13. **Data Purging**: Dedicated service with queue jobs for efficient data purging
14. **API Token Lifecycle**: User-managed tokens with expiration for better security
15. **File Upload Security**: Restricted tiered approach with only low-risk files permitted initially, medium-risk files planned for future phases using OPSWAT MetaDefender
16. **Frontend Approach**: Hybrid approach using Livewire/Volt functional paradigm SFC as primary endpoint technology

See the separate document "040-ela-questions-decisions-log.md" for a complete record of decisions and their rationales. This document is regularly updated as new decisions are made and questions are resolved.

---

## 16. Appendix

### 16.1. Glossary
- **CQRS**: Command Query Responsibility Segregation
- **MFA**: Multi-Factor Authentication
- **TOTP**: Time-based One-Time Password
- **RBAC**: Role-Based Access Control
- **API**: Application Programming Interface
- **REST**: Representational State Transfer
- **JSON**: JavaScript Object Notation
- **WebSocket**: Communication protocol providing full-duplex communication channels over a single TCP connection
- **Redis**: In-memory data structure store used as a database, cache, and message broker
- **PostgreSQL**: Open-source relational database management system
- **MySQL**: Open-source relational database management system
- **SQLite**: Serverless, self-contained, file-based relational database
- **Typesense**: Open-source, typo-tolerant search engine
- **FrankenPHP**: Modern Caddy-based PHP application server with HTTP/3 support
- **Laravel**: PHP web application framework
- **Livewire**: Full-stack framework for Laravel that makes building dynamic interfaces simple
- **Alpine.js**: Lightweight JavaScript framework for composing behavior directly in your markup
- **Tailwind CSS**: Utility-first CSS framework (version 4.x used with Laravel 12)
- **Filament**: Admin panel framework for Laravel applications

### 16.2. References
- Product Requirements Document (PRD) v2.2
- Laravel 12 Documentation
- PHP 8.4 Documentation
- hirethunk/verbs Documentation
- Filament Documentation
- Spatie Package Documentation
- Typesense Documentation
- Laravel Reverb Documentation
- Laravel Horizon Documentation
- Laravel Octane Documentation
