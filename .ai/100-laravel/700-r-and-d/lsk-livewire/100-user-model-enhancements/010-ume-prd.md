
# User Model Enhancements (UME) - Product Requirements Document

**[NOTE] Document Theme:** This Markdown document uses standard formatting. For optimal viewing, including user-configurable light/dark modes and potentially better Mermaid diagram rendering, please use a Markdown preview tool or platform that supports theme switching (e.g., some IDE extensions, documentation platforms). Mermaid diagrams include basic theme settings for improved default contrast.

## Executive Summary

This document outlines comprehensive enhancements to the `App\Models\User` model and related functionalities, leveraging a suite of powerful Laravel packages and core architectural patterns to create a robust, feature-rich, and maintainable user management system. These enhancements focus on improving data structure, auditability, extensibility, security, real-time capabilities, and developer experience. The architecture emphasizes **Services** for orchestration, mandatory **ULIDs**, **Enums** for clarity, **Events & Listeners** for decoupling, **Queues** for performance, and **State/State-Machine patterns** for managing complex lifecycles.

**Key Benefits:**

-   **Structured User Data**: Granular name components, improved initials generation, advanced avatar/media management via `spatie/laravel-medialibrary`. **Mandatory, unique ULIDs** and slugs for core models.
-   **Enhanced Auditability & Decoupled Architecture**: Automatic user tracking (`created_by`, `updated_by`), detailed event logging (`spatie/laravel-activitylog`), and explicit use of **Services, Events, Listeners, and Queues** for modular, scalable operations.
-   **Extended Functionality**: Centralized common features (ULIDs, Slugs via `spatie/laravel-sluggable`, Search via `laravel/scout` & `typesense/typesense-php`, Tags via `spatie/laravel-tags`, Translations via `spatie/laravel-translatable`) through `HasAdditionalFeatures` trait.
-   **Robust Permissions & Teams**: Hierarchical teams with team-specific roles/permissions (`spatie/laravel-permission`). Real-time features restricted by team hierarchy.
-   **Comprehensive Status & State Management**: Sophisticated user account lifecycle management using **State Machines** (`spatie/laravel-model-states`), presence status tracking (`spatie/laravel-model-status`), real-time UI updates (`laravel/reverb`, `laravel-echo`), and clear definition using **Enums**.
-   **Enhanced Security**: **Laravel Fortify** for Two-Factor Authentication, **Laravel Passport** as default for token-based API authentication, **Laravel Sanctum** for SPA authentication, device management, and password policies.
-   **Rich User Interaction**: Impersonation (`lab404/laravel-impersonate`), comments (`spatie/laravel-comments`), social logins (`laravel/socialite`), user-specific settings (`spatie/laravel-settings`), **real-time presence**, and **restricted real-time chat**.
-   **Internationalization**: Strong support for diverse user bases using `spatie/laravel-translatable` and `spatie/laravel-translation-loader`.
-   **Performance & Monitoring**: Optimized queries, caching, background processing (`laravel/horizon`), monitoring (`laravel/pulse`, `laravel/telescope`), and high-performance runtime options (`laravel/octane`).
-   **UI Development Considerations**: Acknowledges the need for frontend components to interact with new backend features, requiring careful integration.

**Impact:** Requires database migrations (adding non-nullable ULIDs, Fortify columns, state fields), model updates, service/listener implementation, and package setup (Passport, Fortify). Backward compatibility for `name` attribute maintained. Features rolled out using `laravel/pennant`.

## Table of Contents

-   [Executive Summary](#executive-summary)
-   [I. Introduction](#i-introduction)
    -   [1.1. Purpose and Scope](#11-purpose-and-scope)
    -   [1.2. Target Stack (Including UI Specificity)](#12-target-stack-including-ui-specificity)
    -   [1.3. Key Dependencies](#13-key-dependencies)
    -   [1.4. Compatibility Considerations](#14-compatibility-considerations)
-   [II. Core Architectural Patterns](#ii-core-architectural-patterns)
    -   [2.1. Overview Diagram](#21-overview-diagram)
    -   [2.2. Service Layer](#22-service-layer)
    -   [2.3. Mandatory ULIDs](#23-mandatory-ulids)
    -   [2.4. Enums](#24-enums)
    -   [2.5. Events and Listeners (vs. Observers)](#25-events-and-listeners-vs-observers)
    -   [2.6. Queues](#26-queues)
    -   [2.7. State & State Machines](#27-state--state-machines)
    -   [2.8. Architectural Flow Example (User Registration)](#28-architectural-flow-example-user-registration)
-   [III. User & Supporting Model Features](#iii-user--supporting-model-features)
    -   [3. Core User Model Features (`App\Models\User`)](#3-core-user-model-features-appmodelsuser)
        -   [3.1. Impersonation (`lab404/laravel-impersonate`)](#31-impersonation-lab404laravel-impersonate)
        -   [3.2. User Name Components](#32-user-name-components)
        -   [3.3. User Avatar & Media (`spatie/laravel-medialibrary`)](#33-user-avatar--media-spatielaravel-medialibrary)
        -   [3.4. User Initials](#34-user-initials)
        -   [3.5. Comments Interaction (`spatie/laravel-comments`)](#35-comments-interaction-spatielaravel-comments)
        -   [3.6. Email Verification (Utilizing State Machine)](#36-email-verification-utilizing-state-machine)
        -   [3.7. Social Authentication (`laravel/socialite`)](#37-social-authentication-laravelsocialite)
        -   [3.8. User Settings (`spatie/laravel-settings`)](#38-user-settings-spatielaravel-settings)
        -   [3.9. Standard Laravel Features](#39-standard-laravel-features)
    -   [4. User Tracking (`App\Models\Traits\HasUserTracking`)](#4-user-tracking-appmodelstraitshasusertracking)
    -   [5. Additional Model Features (`App\Models\Traits\HasAdditionalFeatures`)](#5-additional-model-features-appmodelstraitshasadditionalfeatures)
        -   [5.1. Included Functionality (Package Integration)](#51-included-functionality-package-integration)
        -   [5.2. Key Features](#52-key-features)
-   [IV. Functional Area Enhancements](#iv-functional-area-enhancements)
    -   [6. Team Model and Permissions (`spatie/laravel-permission`)](#6-team-model-and-permissions-spatielaravel-permission)
        -   [6.1. Team Model Implementation](#61-team-model-implementation)
        -   [6.2. User Model Updates for Teams](#62-user-model-updates-for-teams)
        -   [6.3. Team-Based Roles & Permissions](#63-team-based-roles--permissions)
        -   [6.4. Middleware and Policies](#64-middleware-and-policies)
    -   [7. API Documentation (Passport & Sanctum)](#7-api-documentation-passport--sanctum)
        -   [7.1. API Resources (`UserResource`)](#71-api-resources-userresource)
        -   [7.2. API Endpoints (Interaction via Services)](#72-api-endpoints-interaction-via-services)
        -   [7.3. API Authentication (Passport Default, Sanctum for SPA)](#73-api-authentication-passport-default-sanctum-for-spa)
        -   [7.4. API Validation](#74-api-validation)
    -   [8. Security Features](#8-security-features)
        -   [8.1. Two-Factor Authentication (Fortify)](#81-two-factor-authentication-fortify)
        -   [8.2. Device Management & Session Control](#82-device-management--session-control)
        -   [8.3. Password Policies](#83-password-policies)
        -   [8.4. Account Recovery](#84-account-recovery)
        -   [8.5. Data Backup (`spatie/laravel-backup`)](#85-data-backup-spatielaravel-backup)
    -   [9. User Status & State Management](#9-user-status--state-management)
        -   [9.1. Account State Machine (`laravel-model-states`, Enums)](#91-account-state-machine-laravel-model-states-enums)
        -   [9.2. Presence Status Tracking (`laravel-model-status`, Enums)](#92-presence-status-tracking-laravel-model-status-enums)
        -   [9.3. Real-time Updates (Reverb, Echo, Queued Listeners)](#93-real-time-updates-reverb-echo-queued-listeners)
        -   [9.4. Implementation Examples (State Transitions & Events)](#94-implementation-examples-state-transitions--events)
    -   [10. Search Integration (`laravel/scout`, `typesense/typesense-php`)](#10-search-integration-laravelscout-typesensetypesense-php)
    -   [11. Feature Flags (`laravel/pennant`)](#11-feature-flags-laravelpennant)
    -   [12. Real-time Presence (Reverb, Echo, Restricted)](#12-real-time-presence-reverb-echo-restricted)
        -   [12.1. Backend Implementation (Events, Listeners, Channels)](#121-backend-implementation-events-listeners-channels)
        -   [12.2. Frontend Implementation Considerations](#122-frontend-implementation-considerations)
        -   [12.3. Authorization Restrictions](#123-authorization-restrictions)
    -   [13. Real-time Chat (Reverb, Echo, Restricted)](#13-real-time-chat-reverb-echo-restricted)
        -   [13.1. Models and Database](#131-models-and-database)
        -   [13.2. Backend Implementation (Services, API, Events, Listeners, Queues)](#132-backend-implementation-services-api-events-listeners-queues)
        -   [13.3. Frontend Implementation Considerations](#133-frontend-implementation-considerations)
        -   [13.4. Authorization Restrictions](#134-authorization-restrictions)
    -   [14. Internationalization (`spatie/laravel-translatable`, `spatie/laravel-translation-loader`)](#14-internationalization-spatielaravel-translatable-spatielaravel-translation-loader)
-   [V. Implementation Strategy & Project Management](#v-implementation-strategy--project-management)
    -   [15. Implementation Details](#15-implementation-details)
        -   [15.1. Implementation Approach](#151-implementation-approach)
        -   [15.2. Database Migration](#152-database-migration)
        -   [15.3. Data Migration](#153-data-migration)
        -   [15.4. User Model Updates](#154-user-model-updates)
        -   [15.5. UserFactory Updates](#155-userfactory-updates)
        -   [15.6. UserSeeder Creation](#156-userseeder-creation)
        -   [15.7. DatabaseSeeder Update](#157-databaseseeder-update)
    -   [16. UI Updates (Implementation Considerations)](#16-ui-updates-implementation-considerations)
    -   [17. Testing (PestPHP & Quality Tools)](#17-testing-pestphp--quality-tools)
    -   [18. Performance Considerations](#18-performance-considerations)
    -   [19. Risk Assessment](#19-risk-assessment)
    -   [20. Implementation Timeline](#20-implementation-timeline)
    -   [21. Documentation and Training](#21-documentation-and-training)
    -   [22. Maintenance and Support](#22-maintenance-and-support)
    -   [23. Future Roadmap](#23-future-roadmap)
-   [VI. Summary](#vi-summary)

---

## I. Introduction

### 1.1. Purpose and Scope

This document details significant enhancements planned for the `App\Models\User` model and associated systems within the application. The scope includes:

-   Refining core model structures (`User`, `Team`).
-   Integrating key Laravel packages for enhanced functionality.
-   Implementing robust authentication, authorization, and API strategies.
-   Adding restricted real-time features (presence, chat).
-   Establishing and enforcing **core architectural patterns** (Services, ULIDs, Enums, Events/Listeners, Queues, State/State-Machines) for consistency, maintainability, and scalability.
-   Defining implementation steps, UI considerations, testing strategies, and project management aspects.

### 1.2. Target Stack (Including UI Specificity)

This section outlines the planned technology stack. Backend choices and architectural patterns are firm requirements. UI technologies represent the current plan.

-   **Backend:** Laravel (latest stable version)
-   **Frontend (Planned):**
    -   **Primary SPA:** Inertia.js with React
    -   **New Backend-Driven Components:** Livewire/Volt
    -   **Styling:** TailwindCSS
    -   **Real-time Integration:** Laravel Echo (JS library)
-   **Real-time Server:** Laravel Reverb
-   **Database:** PostgreSQL (or MySQL, assumed compatible)
-   **Queue Worker:** Laravel Horizon
-   **Testing:** PestPHP, PHPUnit, Laravel Dusk (Browser Testing)
-   **Server:** PHP (latest stable), Nginx/Apache, Octane (Optional)
-   **Search:** Typesense (via Laravel Scout)
-   **Monitoring:** Laravel Telescope, Laravel Pulse

*> **[NOTE] UI Agnosticism:** While UI requirements are described functionally, specific code examples or implementation notes may reference the planned stack (Inertia/React, Livewire/Volt, Echo). The backend implementation aims to be agnostic, providing APIs and events consumable by various frontend technologies.*

### 1.3. Key Dependencies

*(Dependency table remains largely the same, focusing on backend packages)*

| Package                             | Purpose                                                                | Section(s)                |
| :---------------------------------- | :--------------------------------------------------------------------- | :------------------------ |
| `spatie/laravel-permission`         | Roles and Permissions                                                  | [6](#6-team-model-and-permissions-spatielaravel-permission)                     |
| `spatie/laravel-medialibrary`       | File/Avatar Management                                                 | [3.3](#33-user-avatar--media-spatielaravel-medialibrary)                   |
| `spatie/laravel-activitylog`        | Audit Trails (via Listeners)                                           | [2.5](#25-events-and-listeners-vs-observers), [4](#4-user-tracking-appmodelstraitshasusertracking) |
| `spatie/laravel-model-status`       | Simple Status Tracking (e.g., Presence)                                | [9.2](#92-presence-status-tracking-laravel-model-status-enums)                   |
| **`spatie/laravel-model-states`**   | **State Machine Implementation** (e.g., Account Lifecycle)             | [2.7](#27-state--state-machines), [9.1](#91-account-state-machine-laravel-model-states-enums) |
| `spatie/laravel-sluggable`          | Automatic Slug Generation                                              | [5.1](#51-included-functionality-package-integration)                   |
| `spatie/laravel-tags`               | Tagging Functionality                                                  | [5.1](#51-included-functionality-package-integration)                   |
| `spatie/laravel-translatable`       | Model Attribute Translation                                            | [14](#14-internationalization-spatielaravel-translatable-spatielaravel-translation-loader), [5.1](#51-included-functionality-package-integration) |
| `spatie/laravel-translation-loader` | Database Translation Loading                                           | [14](#14-internationalization-spatielaravel-translatable-spatielaravel-translation-loader)                   |
| `spatie/laravel-settings`           | User/Application Settings                                              | [3.8](#38-user-settings-spatielaravel-settings)                   |
| `spatie/laravel-comments`           | Commenting System                                                      | [3.5](#35-comments-interaction-spatielaravel-comments)                   |
| `spatie/laravel-backup`             | Database & File Backups                                                | [8.5](#85-data-backup-spatielaravel-backup)                   |
| `lab404/laravel-impersonate`        | User Impersonation                                                     | [3.1](#31-impersonation-lab404laravel-impersonate)                   |
| `laravel/socialite`                 | OAuth Social Logins                                                    | [3.7](#37-social-authentication-laravelsocialite)                   |
| `laravel/scout`                     | Full-Text Search Abstraction                                           | [10](#10-search-integration-laravelscout-typesensetypesense-php)                    |
| `typesense/typesense-php`           | Typesense Driver for Scout                                             | [10](#10-search-integration-laravelscout-typesensetypesense-php)                    |
| `laravel/reverb`                    | Scalable WebSocket Server                                              | [9.3](#93-real-time-updates-reverb-echo-queued-listeners), [12](#12-real-time-presence-reverb-echo-restricted), [13](#13-real-time-chat-reverb-echo-restricted) |
| `laravel/echo`                      | **Frontend** WebSocket Listener Library                                | [9.3](#93-real-time-updates-reverb-echo-queued-listeners), [12.2](#122-frontend-implementation-considerations), [13.3](#133-frontend-implementation-considerations) |
| `laravel/pennant`                   | Feature Flags                                                          | [11](#11-feature-flags-laravelpennant)                    |
| `laravel/horizon`                   | Queue Monitoring Dashboard                                             | [2.6](#26-queues), [18](#18-performance-considerations)                 |
| `laravel/telescope`                 | Debug Assistant                                                        | [18](#18-performance-considerations)                 |
| `laravel/pulse`                     | Performance Monitoring                                                 | [18](#18-performance-considerations)                 |
| `laravel/octane`                    | High-Performance Server Runner                                         | [18](#18-performance-considerations)                 |
| `laravel/fortify`                   | Backend authentication scaffolding (Login, Register, **2FA**)          | [8.1](#81-two-factor-authentication-fortify)                   |
| `laravel/passport`                  | **OAuth2 server implementation** for API authentication                | [7.3](#73-api-authentication-passport-default-sanctum-for-spa)                   |
| `laravel/sanctum`                   | **SPA authentication**, Simple API token auth                          | [7.3](#73-api-authentication-passport-default-sanctum-for-spa)                   |


### 1.4. Compatibility Considerations

-   **Database Changes:** Requires careful migration planning, especially for **mandatory** non-nullable `ulid`, `slug`, and `state` columns.
-   **`name` Attribute:** Maintained via accessor for backward compatibility. New code **must** prefer granular components.
-   **API Authentication:** Default API guard change to Passport requires client updates. SPA auth remains Sanctum.
-   **Feature Flags:** `laravel/pennant` used for gradual rollout.

---

## II. Core Architectural Patterns

This project **must** explicitly adopt and consistently apply the following architectural patterns across all relevant features and domains to ensure consistency, testability, scalability, and maintainability.

### 2.1. Overview Diagram

~~~mermaid
%%{
  init: {
    'theme': 'base',
    'themeVariables': {
      'primaryColor': '#f3f4f6',      טית'background': '#ffffff',          טית'nodeBorder': '#1f2937',     טית'clusterBkg': '#f9fafb',        טית'clusterBorder': '#d1d5db',    טית'lineColor': '#6b7280',       טית'textColor': '#111827',        טית'primaryTextColor': '#111827',  טית'secondaryTextColor': '#374151'
    }
  }
}%%
graph TD
    subgraph "Request Flow"
        direction LR
        Req(Request) --> C[Controller/Action]
        C --> Svc(Service Layer);
        Svc --> M(Models);
        Svc --> Evt(Event Dispatcher);
        M -- Interacts with --> DB[(Database)];
    end

    subgraph "Core Architectural Components"
        direction TB
        Arch(Patterns)
        subgraph "Patterns Details"
            direction LR
            P1[Services: Orchestration]
            P2[Mandatory ULIDs: IDs]
            P3[Enums: Fixed Values]
            P4[Events/Listeners: Decoupling]
            P5[Queues: Async Tasks]
            P6[State/State Machines: Lifecycles]
        end
        Arch --> P1 & P2 & P3 & P4 & P5 & P6
    end

    subgraph "Asynchronous Processing"
        direction TB
        Evt --> Q((Queue / Horizon));
        Q --> Lsn(Listeners);
        Lsn --> SvcSideEffect(Service Layer - Side Effects);
        SvcSideEffect --> API((Ext. API))
        SvcSideEffect --> Mail((Mail))
        SvcSideEffect --> SearchIdx((Search Index))
        SvcSideEffect --> RT(Real-time / Reverb)
        SvcSideEffect --> Log(Activity Log)
    end

    Svc -- Uses --> Arch;
    M -- Adheres to --> P2 & P3 & P6;
    Evt -- Triggers --> P4 & P5;

    style Arch fill:#1f2937,stroke:#fff,stroke-width:2px,color:#fff
    style Req fill:#60a5fa,stroke:#1e3a8a,color:#fff
    style C fill:#e5e7eb,stroke:#4b5563
    style Svc fill:#d1fae5,stroke:#065f46
    style M fill:#fef3c7,stroke:#b45309
    style Evt fill:#fecaca,stroke:#b91c1c
    style Q fill:#e0e7ff,stroke:#3730a3,stroke-dasharray: 5 5
    style Lsn fill:#e0e7ff,stroke:#3730a3
    style SvcSideEffect fill:#dbeafe,stroke:#1e40af
    style DB fill:#e5e7eb,stroke:#4b5563
    style API fill:#fde68a,stroke:#a16207
    style Mail fill:#fde68a,stroke:#a16207
    style SearchIdx fill:#fde68a,stroke:#a16207
    style RT fill:#fde68a,stroke:#a16207
    style Log fill:#fde68a,stroke:#a16207

    linkStyle default stroke:#9ca3af,stroke-width:1px;
~~~

*Figure 1: High-level overview of core architectural patterns and their interplay within the application flow.*

### 2.2. Service Layer

-   **Purpose:** Encapsulate business logic and orchestrate actions involving multiple steps or models.
-   **Implementation:** Dedicated Service classes (e.g., `App\Services\UserService`, `App\Services\TeamService`) **must** be used for non-trivial operations initiated by Controllers, Commands, or potentially Listeners.
-   **Responsibilities:** Services coordinate interactions with Models, dispatch Events, interact with other Services, and return results (or throw exceptions). They keep Controllers thin and focused on request/response handling.
-   **Dependency Injection:** Services **should** be resolved via Laravel's service container.

    ```php
    // Example: Controller using a Service
    class UserController extends Controller {
        public function __construct(private UserService $userService) {}

        public function store(StoreUserRequest $request): JsonResponse {
            try {
                $user = $this->userService->createUser($request->validated());
                return UserResource::make($user)->response()->setStatusCode(201);
            } catch (\Exception $e) {
                // Handle exceptions appropriately
                return response()->json(['error' => 'Failed to create user.'], 500);
            }
        }
    }
    ```

### 2.3. Mandatory ULIDs

-   All primary Eloquent models **must** have a `ulid` column (non-nullable, unique, indexed). See Section I.
-   Serves as the **preferred public identifier** (route model binding, API responses).
-   Generation **must** be handled automatically during model creation (e.g., via `HasUlid` trait).

### 2.4. Enums

-   PHP 8.1+ backed **Enums** **must** be used for fixed sets of values (statuses, types). See Section I.
-   **Must** use Enum casting on model attributes.
-   **Should** include helper methods (`label`, `color`, `icon`) for consistent representation. **[ACCESSIBILITY]** When defining conceptual colors (e.g., `Green` for Active), also consider providing alternative indicators (like icons or text labels) for colorblind users. UI implementations **must** not rely solely on color to convey status.

### 2.5. Events and Listeners (vs. Observers)

-   **Events/Listeners:** This is the **preferred pattern** for handling side effects of domain actions.
    -   Significant domain actions (e.g., `UserRegistered`, `AccountSuspended`, `ChatMessageSent`) **must** dispatch specific **Events**. Events are simple data containers.
    -   Side effects (sending emails, logging activity, updating search indexes, broadcasting) **must** be handled by dedicated **Listeners**.
    -   *Rationale:* Promotes decoupling, improves testability (can test listeners in isolation, assert events were dispatched), and makes application flow clearer than implicit Observer magic.
-   **Observers:** Laravel Observers can react to Eloquent model events (`created`, `updated`, `deleted`). While available, their use **should be limited** to:
    -   Cross-cutting concerns tightly coupled to the model's lifecycle *internal state* (e.g., cache invalidation, simple automatic field population like `created_by`).
    -   **Avoid** placing complex business logic or side effects involving external systems directly in Observers. Prefer dispatching an Event from the Observer or Service instead.

### 2.6. Queues

-   Listeners performing I/O-bound or time-consuming tasks **must** implement `ShouldQueue`. See Section I.
-   Use named queues (via Horizon) for workload management.
    *   *Rationale:* Ensures application responsiveness and reliability for background tasks.

### 2.7. State & State Machines

-   **Purpose:** Manage and enforce the lifecycle and allowed transitions for models with complex states (e.g., User Account, Order Status).
-   **Implementation:**
    -   For models with non-trivial lifecycles (e.g., `User` account status: Pending -> Active -> Suspended -> Active -> Deactivated), the **State Machine pattern** **must** be implemented using `spatie/laravel-model-states`.
    -   Define abstract State classes and concrete State classes representing each possible status (e.g., `App\States\User\AccountState`, `App\States\User\Active`, `App\States\User\Suspended`).
    -   Allowed transitions between states **must** be explicitly defined within the State classes (`AllowTransitionTo`).
    -   The model **must** use the `HasStates` trait and cast the state attribute (e.g., `account_state`) to the base State class.
    -   Transitions **must** be attempted via the model's `transitionTo()` method.
    -   State transition methods on the model (e.g., `$user->suspend()`) **must** encapsulate the `transitionTo()` call and **must** dispatch an appropriate domain **Event** upon successful transition (e.g., `AccountSuspended`).
-   **Simple Statuses:** For simpler status tracking without enforced transitions (e.g., User Presence: Online/Offline/Away), direct Enum casting or `spatie/laravel-model-status` can be used, but changes **must** still dispatch Events.

### 2.8. Architectural Flow Example (User Registration)

~~~mermaid
%%{
  init: {
    'theme': 'base',
    'themeVariables': {
      'primaryColor': '#f3f4f6', 'background': '#ffffff', 'nodeBorder': '#1f2937',
      'lineColor': '#6b7280', 'textColor': '#111827', 'primaryTextColor': '#111827'
    }
  }
}%%
sequenceDiagram
    participant C as Client/UI
    participant R as Router/Controller
    participant Val as FormRequest Validator
    participant Svc as UserService
    participant U as User Model
    participant State as AccountState (Initial: Pending)
    participant E as Event Dispatcher
    participant Q as Queue (Horizon)
    participant L1 as SendWelcomeEmailListener (Queued)
    participant L2 as LogRegistrationActivityListener (Queued)
    participant Mail as Mail Service
    participant DB_Act as Activity Log DB

    C->>R: POST /register (UserData)
    R->>Val: Validate Request(UserData)
    activate Val
    Val-->>R: Return Validated Data
    deactivate Val
    R->>Svc: createUser(validatedData)
    activate Svc
    Svc->>U: User::create([...])
    activate U
    Note over U: 'creating' event sets ULID, Slug
    U->>State: Set initial state (e.g., PendingValidation::class)
    U-->>Svc: Return User instance (with state)
    deactivate U
    Svc->>E: dispatch(new UserRegistered(user))
    activate E
    E->>Q: Push Job(UserRegistered Event) -> 'listeners' queue
    deactivate E
    Svc-->>R: Return User instance
    deactivate Svc
    R-->>C: UserResource Response (201 Created)

    Q-->>L1: Process Job: SendWelcomeEmail
    activate L1
    L1->>Mail: Send Welcome Email to user.email
    Mail-->>L1: Sent OK
    deactivate L1
    Q-->>L2: Process Job: LogRegistrationActivity
    activate L2
    L2->>DB_Act: Create Activity Log Entry for user registration
    DB_Act-->>L2: Logged OK
    deactivate L2
~~~
*Figure 2: Example sequence diagram for User Registration, illustrating the interaction between Controller, Service, Model, State, Event Dispatcher, Queue, and Listeners.*

---

## III. User & Supporting Model Features

### 3. Core User Model Features (`App\Models\User`)

#### 3.1. Impersonation (`lab404/laravel-impersonate`)

-   **Functionality:** Allows authorized users to operate as another user.
-   **Implementation:** Uses `lab404/laravel-impersonate` package, `Impersonate` trait, `canImpersonate`/`canBeImpersonated` methods.
-   **[UI IMPACT]** Requires frontend elements to trigger impersonation routes.

#### 3.2. User Name Components

-   **Structure:** `given_name`, `family_name`, `other_names`.
-   **Backward Compatibility:** `full_name` / `getNameAttribute` maintained.
-   **[UI IMPACT]** Forms need separate fields; display logic adapts.

#### 3.3. User Avatar & Media (`spatie/laravel-medialibrary`)

-   **Functionality:** Manages profile pictures via 'avatar' collection.
-   **Implementation:** Uses `spatie/laravel-medialibrary`, `HasMedia`/`InteractsWithMedia` traits. Handles uploads, storage, conversions.
-   **Methods:** `getAvatarUrlAttribute()`, `defaultAvatarUrl()`.
-   **[UI IMPACT]** Requires avatar display and upload components interacting with backend endpoint.

#### 3.4. User Initials

-   **Functionality:** Generates reliable initials from name components or fallbacks.
-   **Implementation:** Enhanced `initials()` method on `User` model.
-   **[UI IMPACT]** Use initials (via API) for avatar fallbacks.

#### 3.5. Comments Interaction (`spatie/laravel-comments`)

-   **Functionality:** Allows users to comment on other models.
-   **Implementation:** Uses `spatie/laravel-comments`, `CanComment`/`InteractsWithComments`.
-   **[UI IMPACT]** Requires comment display and submission form components.

#### 3.6. Email Verification (Utilizing State Machine)

-   **Functionality:** Standard email verification process integrated with account state.
-   **Implementation:** User model implements `MustVerifyEmail`. Initial user state **must** be set to `PendingValidation` (or similar) by the `UserService::createUser` method. Fortify handles sending the verification link. The verification controller action **must** transition the user's state to `Active` using `$user->transitionTo(Active::class)` upon successful verification and dispatch an `EmailVerified` event.
-   **[UI IMPACT]** UI prompts unverified users; may reflect "Pending Validation" status.

#### 3.7. Social Authentication (`laravel/socialite`)

-   **Functionality:** Register/login via third-party providers.
-   **Implementation:** Uses `laravel/socialite`. Requires backend controller logic (likely delegating to `AuthService` or `UserService`) to handle OAuth callbacks, find/create users, and potentially set initial state.
-   **[UI IMPACT]** Requires provider login buttons on UI forms.

#### 3.8. User Settings (`spatie/laravel-settings`)

-   **Functionality:** Stores user-specific preferences.
-   **Implementation:** Uses `spatie/laravel-settings`, `HasSettings` trait, dedicated `UserSettings` class.
-   **[UI IMPACT]** Requires UI forms to modify settings defined in `UserSettings`.

#### 3.9. Standard Laravel Features

-   Continues use of `Authenticatable`, `Notifiable`, `HasFactory`, `SoftDeletes`.

### 4. User Tracking (`App\Models\Traits\HasUserTracking`)

-   **Functionality:** Auto-populates `created_by_id`, `updated_by_id` via model events (observers within the trait).
-   **Usage:** Apply to models needing basic creation/update audit trail.

### 5. Additional Model Features (`App\Models\Traits\HasAdditionalFeatures`)

-   **Purpose:** Convenience trait bundling common package integrations. Underlying patterns (ULIDs, Slugs, Events, State) are mandatory project-wide.
-   **Scope:** Core resource models (`User`, `Team`).

#### 5.1. Included Functionality (Package Integration)

-   **ULID Generation:** Integrates `App\Traits\HasUlid`.
-   **Slugging (`spatie/laravel-sluggable`):** Configures mandatory, unique `slug` generation.
-   **Search (`laravel/scout`):** Implements `Searchable` trait.
-   **Tagging (`spatie/laravel-tags`):** Implements tagging traits.
-   **Translation (`spatie/laravel-translatable`):** Implements `HasTranslations` trait.
-   **Activity Logging (`spatie/laravel-activitylog`):** Implements `LogsActivity` trait. **[NOTE]** For significant domain events, prefer explicit Event dispatch + dedicated Listener using ActivityLog service over automatic logging via this trait alone.

#### 5.2. Key Features

-   ULIDs, Slugs, Search, Tags, Translations, Activity Logging (basic).

---

## IV. Functional Area Enhancements

### 6. Team Model and Permissions (`spatie/laravel-permission`)

-   **Goal:** Hierarchical teams with team-scoped permissions.

#### 6.1. Team Model Implementation

-   **Model:** `App\Models\Team`.
-   **Fields:** `id`, `ulid`(**M**), `slug`(**M**), `owner_id`, `name`, `parent_id`, etc.
-   **Traits:** **Must** use `HasUlid`. **Should** use `HasSlug`, `HasUserTracking`, `LogsActivity`.
-   **Relationships:** `owner()`, `users()`, `parent()`, `children()`, `roles()`.

##### Team Hierarchy Example

~~~mermaid
%%{init: {'theme': 'base', 'themeVariables': {'primaryColor': '#f3f4f6', 'lineColor': '#6b7280', 'textColor': '#111827'}}}%%
graph TD
    T1("Team A (Top)"):::topLevel;
    T2("Team B (Top)"):::topLevel;
    T1S1("Sub Team A1"):::subLevel;
    T1S2("Sub Team A2"):::subLevel;
    T1S1S1("Sub-Sub A1a"):::subSubLevel;
    T2S1("Sub Team B1"):::subLevel;

    T1 -- Child --> T1S1;
    T1 -- Child --> T1S2;
    T1S1 -- Child --> T1S1S1;
    T2 -- Child --> T2S1;

    classDef topLevel fill:#c7d2fe,stroke:#4338ca,color:#1e1b4b;
    classDef subLevel fill:#e0e7ff,stroke:#4f46e5,color:#312e81;
    classDef subSubLevel fill:#eef2ff,stroke:#6366f1,color:#3730a3;
~~~
*Figure 3: Example visualization of a hierarchical team structure with styling.*

#### 6.2. User Model Updates for Teams

-   **Relationships:** `teams()`, `ownedTeams()`, `currentTeam()`.
-   **Membership:** `belongsToTeam()`, `isOwnerOfTeam()`. `switchTeam()` method (likely calls `TeamService`).
-   **`current_team_id`:** FK on `users`.
-   **[UI IMPACT]** UI needs team switching mechanism, interacting with backend service/method.

#### 6.3. Team-Based Roles & Permissions

-   **Implementation:** `spatie/laravel-permission` configured for teams. Roles/Permissions assigned in team context.
-   **Assignment/Checking:** `$user->assignRole('Editor', $team)`, `$user->can('edit articles', $team)`. Managed via `PermissionService` or `TeamService`.

#### 6.4. Middleware and Policies

-   **Middleware:** Protect routes based on team roles (e.g., `EnsureUserHasTeamRole:Admin`).
-   **Policies:** Incorporate team permission checks (`$user->can('edit articles', $article->team)`).

### 7. API Documentation (Passport & Sanctum)

#### 7.1. API Resources (`UserResource`)

-   **Standard:** Use Laravel API Resources for consistent JSON output.
-   **Content:** `ulid`, name components, `email`, `initials`, `avatar_url`, `account_state` (value/label), etc. Conditionally load relations.

#### 7.2. API Endpoints (Interaction via Services)

-   Standard RESTful endpoints where applicable.
-   Controller actions **must** delegate business logic to **Service classes** (e.g., `UserService`, `TeamService`, `ChatMessageService`). Services handle model interaction, event dispatching, state transitions.
-   Authentication endpoints (provided by Passport/Fortify/Sanctum).

#### 7.3. API Authentication (Passport Default, Sanctum for SPA)

-   **Default Token Auth: Laravel Passport:** Primary for token-based APIs (`auth:api` middleware).
-   **SPA Authentication: Laravel Sanctum:** Recommended for first-party SPA (`web` guard).

#### 7.4. API Validation

-   Use Laravel Form Requests for request validation within Controllers before passing data to Services.

### 8. Security Features

#### 8.1. Two-Factor Authentication (Fortify)

-   **Implementation:** Uses **Laravel Fortify** backend logic. User model uses `TwoFactorAuthenticatable` trait. DB columns required.
-   **[UI IMPACT]** Requires frontend components for full 2FA setup & login flow interaction with Fortify endpoints.

#### 8.2. Device Management & Session Control

-   **Implementation:** Leverage Fortify session features or custom logic (e.g., `SessionService`).
-   **[UI IMPACT]** Requires UI components to display sessions and trigger remote logout via backend API/Service.

#### 8.3. Password Policies

-   **Implementation:** Use Laravel validation rules (`Password::min(...)`) and Fortify password reset.

#### 8.4. Account Recovery

-   **Implementation:** Use Fortify password reset. Consider `AccountRecoveryService` for more complex scenarios.

#### 8.5. Data Backup (`spatie/laravel-backup`)

-   **Implementation:** Use `spatie/laravel-backup` configured for regular, secure backups.

### 9. User Status & State Management

**[ARCHITECTURAL NOTE]** This section leverages both State Machines for enforced lifecycles (Account Status) and simpler status tracking (Presence), both integrated with Events and Queues.

#### 9.1. Account State Machine (`laravel-model-states`, Enums)

-   **Purpose:** Manage User account lifecycle (Pending -> Active -> Suspended -> etc.) with enforced transitions.
-   **Implementation:**
    -   **Must** use `spatie/laravel-model-states`.
    -   Define `AccountState` classes (e.g., `PendingValidation`, `Active`, `Suspended`, `Deactivated`, `Deleted`).
    -   Define allowed transitions using `AllowTransitionTo`.
    -   `User` model uses `HasStates`, casts `account_state` attribute.
    -   Account status values **must** align with `App\Enums\AccountStatus` (or similar Enum) for consistency. State classes can reference the corresponding Enum case.
    -   State transitions **must** be initiated via `UserService` or dedicated model methods (e.g., `$user->suspend(string $reason)`). These methods **must**:
        1.  Attempt transition using `$user->transitionTo(Suspended::class)`.
        2.  On success, dispatch a specific domain **Event** (e.g., `AccountSuspended($user, $reason)`). Event listeners handle side effects (logging, notifications, permission changes).

##### Account State Machine Diagram

~~~mermaid
%%{
  init: {
    'theme': 'base',
    'themeVariables': { 'primaryColor': '#f3f4f6', 'lineColor': '#6b7280', 'textColor': '#111827' }
  }
}%%
stateDiagram-v2
    direction LR
    [*] --> PendingValidation : Register (via UserService)

    PendingValidation --> Active : ValidateEmail (via VerificationController -> UserService)
        note on link
            Event: EmailVerified
        end note

    Active --> Suspended : SuspendAccount (via AdminService/ModerationService)
        note on link
             Event: AccountSuspended
        end note

    Suspended --> Active : ReinstateAccount (via AdminService)
        note on link
             Event: AccountReinstated
        end note

    Active --> Deactivated : UserDeactivateRequest (via UserService/SettingsController)
        note on link
             Event: AccountDeactivationRequested
        end note
    Suspended --> Deactivated : UserDeactivateRequest (via UserService/SettingsController)
         note on link
              Event: AccountDeactivationRequested
         end note

    Deactivated --> Active : ReactivateAccount (via UserService/SupportAction)
         note on link
              Event: AccountReactivated
         end note

    Active --> Deleted : AdminDeleteAction (via AdminService)
         note on link
              Event: AccountDeleted
         end note
    Suspended --> Deleted : AdminDeleteAction (via AdminService)
         note on link
              Event: AccountDeleted
         end note
    Deactivated --> Deleted : AdminDeleteAction (via AdminService)
         note on link
              Event: AccountDeleted
         end note

    Deleted --> [*] : Final State

    state PendingValidation {
        direction LR
        [*] --> ValidationPending
    }
    state Active {
        direction LR
        [*] --> Operational
    }
     state Suspended {
        direction LR
        [*] --> TemporarilyDisabled
        note right of TemporarilyDisabled : Permissions revoked,\nLogin blocked
    }
     state Deactivated {
        direction LR
        [*] --> UserDisabled
        note right of UserDisabled : User initiated,\nLogin blocked,\nData potentially anonymized later
    }
     state Deleted {
        direction LR
        [*] --> PermanentlyRemoved
        note right of PermanentlyRemoved : Data purged or fully anonymized
    }
~~~
*Figure 4: State machine diagram for User Account lifecycle, showing states, transitions triggered by services/actions, and associated Events.*

#### 9.2. Presence Status Tracking (`laravel-model-status`, Enums)

-   **Purpose:** Track simpler user presence (Online, Offline, Away) without enforced transitions.
-   **Implementation:**
    -   Can use `spatie/laravel-model-status` (if history/reasons needed) or a simple Enum-casted column (`presence_status`) on the `User` model.
    -   **Must** use `App\Enums\PresenceStatus` Enum.
    -   Updates triggered by **Listeners** reacting to Events like `Login`, `Logout`, potentially custom events from WebSocket connections (e.g., `UserWentAway`), or explicit user actions via `PresenceService`.
    -   Presence updates **must** dispatch a `UserPresenceChanged(User $user, PresenceStatus $newStatus)` Event.

#### 9.3. Real-time Updates (Reverb, Echo, Queued Listeners)

-   **Goal:** Reflect state/status changes instantly in the UI.
-   **Implementation:**
    -   Listeners for state/status change events (e.g., `AccountSuspended`, `UserPresenceChanged`) **must** handle broadcasting.
    -   Broadcasting logic **must** be queued (Listener implements `ShouldQueue` and targets `broadcasts` queue). Use `ShouldBroadcastNow` interface *only* if instantaneous broadcasting is absolutely critical and non-blocking.
    -   Broadcast on relevant private channels (user-specific, team-specific).
    -   **[UI IMPACT]** Frontend **must** use `laravel-echo` (or equivalent) to subscribe to channels and update UI based on received broadcast events.

#### 9.4. Implementation Examples (State Transitions & Events)

~~~php
// Example within UserService or dedicated AccountService
use App\Enums\AccountStatus;
use App\Events\AccountSuspended;
use App\Models\User;
use App\States\User\Suspended;
use Illuminate\Support\Facades\Log;

class AccountService {
    public function suspendUser(User $user, User $moderator, string $reason): bool
    {
        if (!$user->canTransitionTo(Suspended::class)) {
            Log::warning("Invalid state transition attempted: User {$user->ulid} to Suspended from {$user->account_state}");
            // Optional: throw custom exception
            return false;
        }

        try {
            // Attempt state transition
            $user->transitionTo(Suspended::class);

            // **MUST** Dispatch event *after* successful transition
            event(new AccountSuspended($user, $moderator, $reason));

            // Optional: Update simple status field if using both systems
            // if (method_exists($user, 'setStatus')) {
            //    $user->setStatus(AccountStatus::Suspended->value, $reason);
            // }

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to suspend user {$user->ulid}: " . $e->getMessage());
            // Potentially revert state if possible/needed, or handle error
            return false;
        }
    }
}

// App\Listeners\LogAccountSuspensionActivity (implements ShouldQueue)
use App\Events\AccountSuspended;
use Spatie\Activitylog\Facades\Activity;

class LogAccountSuspensionActivity implements ShouldQueue {
    public $queue = 'listeners'; // Send to specific queue

    public function handle(AccountSuspended $event): void {
        activity()
           ->performedOn($event->suspendedUser)
           ->causedBy($event->moderator)
           ->withProperties(['reason' => $event->reason])
           ->log('Account suspended');
    }
}

// App\Listeners\RevokeUserPermissionsOnSuspension (implements ShouldQueue)
// ... handles removing roles/permissions ...

// App\Listeners\BroadcastAccountStatusUpdate (implements ShouldQueue)
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
// ... broadcasts the status change ...
~~~

### 10. Search Integration (`laravel/scout`, `typesense/typesense-php`)

-   **Goal:** Fast full-text user search.
-   **Implementation:** Use **Laravel Scout** + **Typesense**. User model uses `Searchable`. `toSearchableArray()` defined. Indexing updates **must** happen via queued listeners reacting to relevant events (UserCreated, UserUpdated) or Scout's built-in queueing.

### 11. Feature Flags (`laravel/pennant`)

-   **Goal:** Control feature rollout.
-   **Implementation:** Use **Laravel Pennant**. Define features, check using `Feature::active()`.
-   **[UI IMPACT]** UI components need to conditionally render based on feature flags passed from the backend or checked via a dedicated API if needed.

### 12. Real-time Presence (Reverb, Echo, Restricted)

-   **Goal:** Real-time presence tracking, **restricted** to **top-level teams**.

#### 12.1. Backend Implementation (Events, Listeners, Channels)

-   **Presence Updates:**
    -   Listeners (queued) for `Login`, `Logout` events update `presence_status` and dispatch `UserPresenceChanged`.
    -   A `PresenceService` might handle explicit status changes (e.g., user sets status to 'Away') and dispatch `UserPresenceChanged`.
    -   Consider listeners for WebSocket connect/disconnect events (if framework supports reliably) to update status.
-   **Broadcasting:** `UserPresenceChanged` event triggers a queued listener that broadcasts the update.
-   **Presence Channel (`presence-team.{teamId}`):**
    -   Defined in `routes/channels.php`.
    -   Authorization logic **must** verify auth, team membership, AND **top-level team status**.
    -   Returns necessary user data (`ulid`, `name`, `avatar_url`, etc.) for authorized users joining.

##### Presence Authorization Flow

*(Mermaid diagram conceptually similar to previous version, emphasizing authorization checks)*
~~~mermaid
%%{init: {'theme': 'base', 'themeVariables': {'primaryColor': '#f3f4f6', 'lineColor': '#6b7280', 'textColor': '#111827'}}}%%
sequenceDiagram
    participant FE as Frontend App
    participant Echo as Laravel Echo Lib
    participant BC as Broadcast Channel (Reverb)
    participant AuthBE as Backend Auth (channels.php)
    participant DB as Database

    FE->>Echo: join('presence-team.{teamId}')
    Echo->>BC: Attempt Join
    BC->>AuthBE: Authorize User Request (user, teamId)
    activate AuthBE
    AuthBE->>DB: Find Team(teamId)
    DB-->>AuthBE: Team Data (or null)
    alt Team Exists AND Team.parent_id == null
        AuthBE->>DB: Check User Membership(user, team)
        DB-->>AuthBE: Is Member (true/false)
        alt User Is Member
             AuthBE-->>BC: AUTHORIZED (User Data)
        else User Not Member
             AuthBE-->>BC: DENIED (Membership)
        end
    else Team Not Found or Not Top-Level
        AuthBE-->>BC: DENIED (Invalid Team)
    end
    deactivate AuthBE

    alt Authorized
        BC-->>Echo: Auth Success
        Echo-->>FE: .here(), .joining(), .leaving() events
    else Denied
        BC-->>Echo: Auth Failure
        Echo-->>FE: Error event
    end
~~~
*Figure 5: Sequence diagram illustrating the authorization flow for joining a restricted presence channel.*

#### 12.2. Frontend Implementation Considerations

-   **Technology:** Requires frontend JS using `laravel-echo` (or equivalent).
-   **Functionality:**
    -   **Must** join `presence-team.{teamId}` channel **only** in top-level team context.
    -   **Must** handle `here`, `joining`, `leaving` events to update local presence state.
    -   **Must** display presence indicators based on state within the top-level team UI. **[ACCESSIBILITY]** Use clear visual indicators (e.g., shape + color, like a green circle for online, gray square for offline) and potentially ARIA attributes, not just color alone.
    -   **Must** handle errors and channel leaving on context switch/logout.

*> Illustrative JS example remains conceptually similar to previous version.*

#### 12.3. Authorization Restrictions

-   Presence restricted **strictly** to **top-level teams**. Backend **must** enforce.

### 13. Real-time Chat (Reverb, Echo, Restricted)

-   **Goal:** Basic real-time chat, **strictly restricted** to **top-level teams**.

#### 13.1. Models and Database

-   **`ChatMessage` Model:** `id`, `ulid`(**M**), `team_id` (FK, top-level), `user_id`, `content`, `timestamps`. Uses `HasUlid`. Indexes required.

#### 13.2. Backend Implementation (Services, API, Events, Listeners, Queues)

-   **`ChatMessageService`:** Contains logic for creating messages, potentially fetching history.
-   **API Endpoints:**
    -   `POST /api/teams/{team}/chat/messages`: Controller validates, calls `ChatMessageService::sendMessage(User $user, Team $team, string $content)`. **Authorization must check** membership AND top-level team. Service creates message, dispatches `ChatMessageSent` event.
    -   `GET /api/teams/{team}/chat/messages`: Controller validates access (membership + top-level), calls service or queries directly for paginated history.
-   **Event:** `ChatMessageSent(ChatMessage $message)` (**must** implement `ShouldQueue`).
-   **Listener (Queued):** `BroadcastChatMessage` listens for `ChatMessageSent`, implements `ShouldBroadcast`, broadcasts on `PrivateChannel("chat.team.{$message->team_id}")` with message data (`ulid`, content, sender info, timestamp). Targets `broadcasts` queue.
-   **Channel Authorization (`chat.team.{teamId}`):** Defined in `routes/channels.php`. **Must** verify membership AND top-level team status.

##### Chat Message Sending Flow

*(Mermaid diagram conceptually similar to previous version, highlighting Service layer)*
~~~mermaid
%%{init: {'theme': 'base', 'themeVariables': {'primaryColor': '#f3f4f6', 'lineColor': '#6b7280', 'textColor': '#111827'}}}%%
sequenceDiagram
    participant FE as Frontend App
    participant API as API Endpoint (POST /api/...)
    participant Auth as Auth Middleware/Policy
    participant Val as FormRequest Validator
    participant CTRL as Controller
    participant Svc as ChatMessageService
    participant DB as Database (chat_messages)
    participant E as Event Dispatcher
    participant Q as Queue (Horizon)
    participant Listener as BroadcastChatMessage (Queued)
    participant WS as WebSocket Server (Reverb)
    participant Echo as Laravel Echo Lib (Other Clients)
    participant OtherFE as Other Frontend Clients

    FE->>API: Send Message Request (teamId, {content: "Hello"})
    API->>Auth: Check Auth & Membership & Top-Level Team
    activate Auth
    alt Authorized
        Auth-->>API: OK
        deactivate Auth
        API->>Val: Validate Request Data
        activate Val
        alt Valid Data
            Val-->>API: OK
            deactivate Val
            API->>CTRL: handle(request)
            activate CTRL
            CTRL->>Svc: sendMessage(user, team, content)
            activate Svc
            Svc->>DB: Create ChatMessage Record
            DB-->>Svc: Return new ChatMessage
            Svc->>E: dispatch(new ChatMessageSent(message))
            activate E
            E->>Q: Push Job (ChatMessageSent) -> 'listeners' queue
            deactivate E
            Svc-->>CTRL: Return ChatMessage
            deactivate Svc
            CTRL-->>API: Return ChatMessageResource (201)
            deactivate CTRL
            API-->>FE: Success Response (New Message)

            Q-->>Listener: Process Job: BroadcastChatMessage
            activate Listener
            Listener->>WS: Broadcast 'chat.message.new' on 'chat.team.{teamId}'
            deactivate Listener
            WS->>Echo: Push Message via WebSocket
            Echo->>OtherFE: Deliver 'chat.message.new' event
            OtherFE->>OtherFE: Update Chat UI
        else Invalid Data
            deactivate Val
            Val-->>API: Validation Errors (422)
            API-->>FE: Error Response
        end
    else Not Authorized
        deactivate Auth
        Auth-->>API: Forbidden (403)
        API-->>FE: Error Response
    end
~~~
*Figure 6: Sequence diagram for sending a chat message, incorporating the Service layer.*

#### 13.3. Frontend Implementation Considerations

-   **Technology:** Requires frontend JS using `laravel-echo` (or equivalent) and API interaction.
-   **Functionality:**
    -   **Must** implement chat UI component scoped to a **top-level team**.
    -   **Must** fetch history via `GET` API on load (for the correct team).
    -   **Must** join `Echo.private('chat.team.' + teamId)` **only** in top-level team context.
    -   **Must** listen for `chat.message.new` and append messages to display.
    -   **Must** provide input/send mechanism interacting with `POST` API.
    -   **Must** handle context switching (leave/join channels, fetch history).

#### 13.4. Authorization Restrictions

-   Chat restricted **strictly** to **top-level teams**. Backend **must** enforce.

### 14. Internationalization (`spatie/laravel-translatable`, `spatie/laravel-translation-loader`)

-   **Goal:** Support multiple languages.
-   **Implementation:** Use `spatie/laravel-translatable` for model attributes (if needed), `spatie/laravel-translation-loader` for UI strings. Configure locales. Set locale via middleware (using user preference from `UserSettings`).
-   **[UI IMPACT]** UI **must** use the translation system (`__()` or equivalent mechanism for passing/using translations in the chosen frontend framework).

---

## V. Implementation Strategy & Project Management

### 15. Implementation Details

#### 15.1. Implementation Approach

1.  Install/Configure Backend Packages.
2.  Database Migrations (ULIDs, slugs, names, state, teams, chat).
3.  Data Migration Logic & Execution.
4.  Implement **Service Layer** classes (`UserService`, `TeamService`, `ChatMessageService`, `AccountService`, etc.).
5.  Define Core Constructs (Enums, Events, Listeners, State Machines). Register Listeners, ensure queueing.
6.  Update Models (`User`, `Team`) - Apply traits, methods. **Ensure mandatory ULID/Slug/State setup**. Delegate logic to Services where appropriate.
7.  Update Factories/Seeders.
8.  **Implement UI Components:** Develop necessary frontend components (agnostic description in Section 16) interacting with backend APIs, services (indirectly), and real-time events.
9.  Write Comprehensive Tests (Backend Unit/Feature covering Services, Events, States; Frontend/Browser tests for UI flows).
10. Update API Resources/Controllers (thin, delegating to Services), Apply Auth Middleware.
11. Documentation.

#### 15.2. Database Migration

*(Migration PHP code remains the same as previous version, ensuring `account_state` column is included)*
*> **[WARNING]** Critical step involving schema and data changes. Test thoroughly on staging. Backup production.*
~~~php
// --- Enhance Users Table ---
Schema::table('users', function (Blueprint $table) {
    // ... (ulid, name components, user tracking, slug as before) ...

    // Fortify 2FA Columns (as before)
    $table->text('two_factor_secret')->nullable()->after('password');
    $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
    $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');

    // **MANDATORY** Account State (for spatie/laravel-model-states)
    $table->string('account_state')->nullable()->after('remember_token'); // Initially nullable for migration

    // Optional: Presence Status (simple enum cast example)
    // $table->string('presence_status')->nullable()->index();
    // $table->timestamp('last_seen_at')->nullable();

    // Current Team Context (as before)
    // $table->foreignId('current_team_id')->nullable()->constrained('teams')->nullOnDelete();
});

// --- Data Migration for Existing Users ---
// (Call populateExistingUsers helper method - ensure it sets a default 'account_state' if needed)
$this->populateExistingUsers();

// --- Make ULID, Slug, State non-nullable AFTER population ---
Schema::table('users', function (Blueprint $table) {
    $table->ulid('ulid')->nullable(false)->change();
    $table->string('slug')->nullable(false)->change();
    // Set default state before making non-nullable if applicable
    // DB::table('users')->whereNull('account_state')->update(['account_state' => App\States\User\Active::class]); // Example default
    $table->string('account_state')->nullable(false)->change();
});

// --- Create Teams Table --- (as before)
// --- Create team_user Pivot Table --- (as before)
// --- Create Chat Messages Table --- (as before)

// Helper method: populateExistingUsers() needs update
protected function populateExistingUsers(): void {
    // ... (logic for ulid, slug, name splitting as before) ...
    DB::table('users')
        ->where('id', $user->id)
        ->update([
            // ... (given_name, family_name, ulid, slug) ...
            // Set a sensible default state for existing users
            'account_state' => class_exists(\App\States\User\Active::class) ? \App\States\User\Active::class : 'active', // Default to Active state class or a fallback string if class doesn't exist yet
        ]);
    // ...
}
~~~


#### 15.3. Data Migration

-   Logic within migration (`populateExistingUsers`) **must** also set a default `account_state` before the column is made non-nullable.
-   Consider batch command for very large tables.

#### 15.4. User Model Updates

*(Model PHP code remains largely the same as previous agnostic version, ensuring HasStates trait and `account_state` cast are present and correct)*
*> **[ARCHITECTURAL NOTE]** Model focuses on data structure, relationships, casts, basic accessors, and configuration for traits (Sluggable, Media, Search, ActivityLog, States). Complex logic resides in Services.*
~~~php
// Example ensuring state machine setup in User model:
use App\States\User\AccountState; // State Machine Base Class
use Spatie\ModelStates\HasStates; // State Machine Trait

class User extends Authenticatable /* ... implements ... */ {
    use HasStates; // Add State Machine trait
    // ... other traits (HasUlid, HasSlug, HasApiTokens, Notifiable, etc.)

    protected $casts = [
        // ... other casts
        'account_state' => AccountState::class, // **MANDATORY** cast for state machine
        'password' => 'hashed',
    ];

    // ... rest of model (accessors, relationships, package configs like getSlugOptions, registerMediaCollections, etc.)
    // **AVOID** complex business logic or state transition logic directly in the model.
    // Methods like suspend() should ideally be in AccountService, which calls $user->transitionTo().
}
~~~


#### 15.5. UserFactory Updates

*(Factory PHP code remains the same as previous agnostic version, ensuring `account_state` is set)*
~~~php
// Ensure factory sets a default state:
public function definition(): array {
    return [
        // ... other fields
        'account_state' => class_exists(\App\States\User\Active::class) ? \App\States\User\Active::class : null, // Default state
    ];
}

// Update unverified state:
public function unverified(): static {
    return $this->state(fn (array $attributes) => [
        'email_verified_at' => null,
        'account_state' => class_exists(\App\States\User\PendingValidation::class) ? \App\States\User\PendingValidation::class : null, // Set pending state
    ]);
}
~~~

#### 15.6. UserSeeder Creation

*(Seeder PHP code remains the same as previous agnostic version, ensuring appropriate states are set)*
~~~php
// Example: Ensure Admin user is set to Active state
$adminUser = User::factory()->create([
    // ... other fields
    'account_state' => class_exists(\App\States\User\Active::class) ? \App\States\User\Active::class : null,
]);
~~~

#### 15.7. DatabaseSeeder Update

*(DatabaseSeeder PHP code remains the same)*

### 16. UI Updates (Implementation Considerations)

**[NOTE] UI Agnosticism:** This section describes *what* the UI needs to do functionally. The specific implementation depends on the chosen stack (Planned: Inertia/React, Livewire/Volt - See Section 1.2).

**General UI Requirements:**

-   **Profile/Settings:** Forms with separate name fields; avatar upload/display controls.
-   **2FA Management:** Components for enable(QR/secret)/confirm/disable/recovery codes, login challenge input. Must interact with Fortify backend endpoints.
-   **Session Management:** List active sessions, allow remote logout via backend API/Service.
-   **Presence Display:** Real-time indicators (shape + color, e.g., ● Green / ■ Gray) next to users in **top-level team** contexts. Requires `laravel-echo` (or equivalent) integration.
-   **Chat Interface:** Dedicated component for **top-level teams**: fetch history (API), display messages, listen via Echo (private channel), send messages (API). Handle context switching.
-   **Status Display:** Reflect user `account_state` where relevant (e.g., "Active", "Suspended" badge - **[ACCESSIBILITY]** use text + color/icon).
-   **Backend-Driven Components (Planned: Livewire/Volt):** Admin UIs for managing users, teams, roles, statuses.

### 17. Testing (PestPHP & Quality Tools)

-   **Unit Tests:** Test Services, Listeners, State transitions, Enums, Model helpers.
-   **Feature Tests:** Test API endpoints (via Services), Auth flows (Passport, Fortify 2FA), Event dispatching/listening, Queue execution, Channel authorization, State machine logic, Search.
-   **Browser Tests (Dusk):** Test critical UI flows matching the chosen frontend implementation (Login+2FA, Profile Update, Chat, Presence).
-   **Static Analysis & Code Style:** PHPStan/Larastan, Pint/PHP-CS-Fixer.

### 18. Performance Considerations

*(Points remain the same - focus on backend optimization)*
- DB Indexing, Query Optimization, Caching, Queues (Horizon), Real-time (Reverb), Search (Typesense), Media Library, Octane, Monitoring (Pulse, Telescope).

### 19. Risk Assessment

*(Risk table includes previous items + UI challenges)*

| Risk                                       | Likelihood | Impact | Mitigation Strategy                                                                                                                               |
| :----------------------------------------- | :--------- | :----- | :------------------------------------------------------------------------------------------------------------------------------------------------ |
| Data Migration Failure (ULID/Slug/State)   | Medium     | High   | **Thorough testing** of migration script (incl. default state) on staging. **Backup** production. Batch command for large tables.                 |
| Backward Compatibility Issues (`name` attr) | Low        | Medium | Maintain accessor. Encourage components. Code review.                                                                                             |
| Package Conflicts/Bugs                   | Low-Medium | Medium | Keep packages updated. Test updates. Pin versions if needed.                                                                                      |
| API Authentication Changes Break Clients | Medium     | High   | Communicate changes. Migration path. API versioning.                                                                                              |
| Performance Degradation (Real-time/Search) | Medium     | Medium | Load testing. Monitoring (Horizon, Pulse, Reverb, Typesense). Optimize backend logic (Services, Listeners, Queries, Auth). Scale infra.           |
| Security Vulnerabilities (Auth/Perms/State)| Low        | High   | Best practices. Dep updates. Audits/scans. Strict authorization (Policies, Middleware, Service checks). Secure state transitions.                  |
| Complexity Increase / Maintainability    | Medium     | Medium | Adhere strictly to **Architectural Patterns** (Sec II). Documentation. Tests. Code reviews. Refactor Services/Listeners.                            |
| Feature Rollout Issues (Pennant)         | Low        | Low    | Test flags thoroughly. Gradual rollout. Monitor.                                                                                                  |
| **UI Implementation Challenges**           | Medium     | Medium | Ensure chosen UI stack integrates well (Echo, API). Allocate sufficient time. Component libraries. Clear contracts between backend & frontend.      |
| **State Management Complexity**            | Medium     | Medium | Clear state diagrams. Robust testing of transitions & side effects (Listeners). Keep state logic encapsulated. Good logging around transitions. |


### 20. Implementation Timeline

*(Placeholder - Phases refined slightly)*

-   **Phase 1 (Foundation):** Backend setup, Core Architecture (Services, ULID, Enums, Events, State etc.), Migrations, Data Migration, Base Model Updates.
-   **Phase 2 (Core Features):** Implement Services & backend logic for Teams/Perms, 2FA, API Auth, Status/State Management, Search. Basic Admin UI.
-   **Phase 3 (Real-time & UX):** Implement Services & backend for Presence & Chat. Implement required Frontend components (Profile, Settings, 2FA, Presence, Chat) using chosen stack. Feature Flags.
-   **Phase 4 (Stabilization & Rollout):** Comprehensive Testing (Backend & Frontend), Documentation, Deployment, Monitoring.

### 21. Documentation and Training

-   **Developer Docs:** Update README, document **Services**, Models, **State Machines**, Events/Listeners, API, Real-time channels, architecture. Backend examples. **Document required UI interactions/contracts.**
-   **User Docs:** Guides on 2FA, Teams, Chat, Presence.
-   **Training:** Developer session covering architecture, patterns, services, state management, backend implementation, and **frontend interaction points**.

### 22. Maintenance and Support

-   Regular Backend (Composer) & Frontend (NPM/Yarn) dependency updates.
-   Backend & Frontend monitoring (Logs, Horizon, Pulse, JS error tracking).
-   Bug Fixing (Backend & Frontend).
-   Refactoring (Services, Listeners, UI components).

### 23. Future Roadmap

*(Items remain the same)*
- Direct Messaging, Enhanced Presence, Profile Completion, Advanced Search, User Groups, Notification Preferences, Full OAuth2 Server, Webhooks, Audit Log UI.

---

## VI. Summary

These comprehensive enhancements establish a modern, robust, and maintainable user management system, architected around clear patterns: **Services** for orchestration, **ULIDs** for identification, **Enums** for clarity, **Events & Listeners** for decoupling, **Queues** for performance, and **State Machines** for managing complex lifecycles like user accounts. Leveraging the Laravel ecosystem and key packages provides significant functionality efficiently.

Key backend improvements include structured data, enhanced security (**2FA**), team-based permissions, detailed auditability, sophisticated status/state management, and real-time capabilities (**Presence**, restricted **Chat**).

Successful implementation requires careful integration with a capable frontend (**[UI IMPACT]**). The backend provides the necessary APIs, real-time events, and state management logic, but the UI must be developed to consume these effectively, handle user interactions for features like 2FA and chat, and reflect real-time updates for presence and status. Adherence to the defined architectural patterns is crucial for managing complexity and ensuring long-term maintainability.
