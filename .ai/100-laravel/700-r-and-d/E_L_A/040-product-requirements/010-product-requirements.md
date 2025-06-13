# Product Requirements Document (PRD) - Enhanced Laravel Application

**Version:** 2.4.0
**Date:** 2023-11-13
**Author:** AI Assistant (based on user input and review)
**Status:** Updated with Approved Recommendations

---

<details>
<summary>Table of Contents</summary>

- [Executive Summary](#executive-summary)
  - [Key Features](#key-features)
  - [Technical Approach](#technical-approach)
  - [Business Value](#business-value)
- [Version History](#version-history)
- [1. Introduction & Overview](#1-introduction--overview)
  - [Key Features](#key-features-1)
- [2. Goals & Objectives](#2-goals--objectives)
  - [2.1 Security & Access Control](#21-security--access-control)
  - [2.2 Data & Content Management](#22-data--content-management)
- [3. Target Audience](#3-target-audience)
  - [3.1. User Types](#31-user-types)
  - [3.2. User Personas](#32-user-personas)
    - [Alex (System Administrator)](#alex-system-administrator)
    - [Maria (Team Manager)](#maria-team-manager)
    - [Carlos (Regular User)](#carlos-regular-user)
    - [TechCorp (API Consumer)](#techcorp-api-consumer)
- [4. Functional Requirements](#4-functional-requirements)
  <details>
  <summary>Expand Functional Requirements (25 items)</summary>

  - [4.0. Feature Prioritization Matrix](#40-feature-prioritization-matrix)
  - [4.1. Authentication & Authorization](#41-authentication--authorization)
  - [4.2. User Management](#42-user-management)
  - [4.3. Team Management](#43-team-management)
  - [4.4. Category Management](#44-category-management)
  - [4.5. Todo Management](#45-todo-management)
  - [4.6. Media Management](#46-media-management)
  - [4.7. Tag Management](#47-tag-management)
  - [4.8. Comment Management](#48-comment-management)
  - [4.9. Search](#49-search)
  - [4.10. Activity Logging](#410-activity-logging)
  - [4.11. Application Settings](#411-application-settings)
  - [4.12. Notifications](#412-notifications)
  - [4.13. Multilingual Support](#413-multilingual-support)
  - [4.14. Background Job Processing](#414-background-job-processing)
  - [4.15. Feature Flags](#415-feature-flags)
  - [4.16. Cookie Consent](#416-cookie-consent)
  - [4.17. RSS Feeds](#417-rss-feeds)
  - [4.18. Blogging](#418-blogging)
  - [4.19. Chat](#419-chat)
  - [4.20. Comprehensive Admin Portal](#420-comprehensive-admin-portal)
    - [4.20.1. Platform](#4201-platform)
    - [4.20.2. Functionality](#4202-functionality)
    - [4.20.3. Integration](#4203-integration)
    - [4.20.4. Customization](#4204-customization)
    - [4.20.5. Dashboard](#4205-dashboard)
    - [4.20.6. Command History & Snapshot UI](#4206-command-history--snapshot-ui)
      - [4.20.6.1. Goal](#42061-goal)
      - [4.20.6.2. Command Log Resource](#42062-command-log-resource)
      - [4.20.6.3. Snapshot Viewing](#42063-snapshot-viewing)
      - [4.20.6.4. Snapshot Detail View](#42064-snapshot-detail-view)
      - [4.20.6.5. Permissions](#42065-permissions)
      - [4.20.6.6. Read-Only](#42066-read-only)
  - [4.21. Hierarchical Data Management](#421-hierarchical-data-management)
    - [4.21.1. Configuration Strategy](#4211-configuration-strategy)
    - [4.21.2. Admin UI](#4212-admin-ui)
    - [4.21.3. Defaults](#4213-defaults)
    - [4.21.4. Enforcement](#4214-enforcement)
    - [4.21.5. Scope & Limitations](#4215-scope--limitations)
  - [4.22. Command History & Snapshots](#422-command-history--snapshots)
  - [4.23. Public-Facing API](#423-public-facing-api)
  - [4.24. Advanced Reporting & Analytics](#424-advanced-reporting--analytics)
    - [4.24.1. Goal](#4241-goal)
    - [4.24.2. Approach](#4242-approach)
    - [4.24.3. Implementation Strategy](#4243-implementation-strategy)
    - [4.24.4. Standard Reports](#4244-standard-reports)
    - [4.24.5. Report Features](#4245-report-features)
    - [4.24.6. Data Considerations](#4246-data-considerations)
  </details>
- [5. Non-Functional Requirements](#5-non-functional-requirements)
  <details>
  <summary>Expand Non-Functional Requirements</summary>

  - [Performance](#performance)
  - [Scalability](#scalability)
  - [Reliability & Availability](#reliability--availability)
  - [Security](#security)
  - [Maintainability & Code Quality](#maintainability--code-quality)
  - [Usability & Accessibility](#usability--accessibility)
  - [Development Experience](#development-experience)
  - [Data Privacy & Compliance](#data-privacy--compliance)
    - [Data Purging Mechanisms](#data-purging-mechanisms)
      - [Goal](#goal)
      - [Scope of Data](#scope-of-data)
      - [Implementation](#implementation)
      - [Retention Policies](#retention-policies)
      - [Admin Interface](#admin-interface)
  </details>
- [6. Technical Requirements](#6-technical-requirements)
  <details>
  <summary>Expand Technical Requirements</summary>

  - [6.1. Framework](#61-framework)
  - [6.2. Frontend](#62-frontend)
  - [6.3. Backend Architecture](#63-backend-architecture)
    - [6.3.1. Strategy](#631-strategy)
    - [6.3.2. Implementation](#632-implementation)
    - [6.3.3. Commands](#633-commands)
    - [6.3.4. Command Bus](#634-command-bus)
  - [6.4. Real-time](#64-real-time)
  - [6.5. Background Processing](#65-background-processing)
  - [6.6. Performance](#66-performance)
  - [6.7. Database](#67-database)
  - [6.8. Search](#68-search)
  - [6.9. State Machines](#69-state-machines)
  - [6.10. Admin Panel](#610-admin-panel)
  - [6.11. Hierarchies](#611-hierarchies)
  - [6.12. Enhanced Enums](#612-enhanced-enums)
  </details>
- [7. Data Model / Database Design](#7-data-model--database-design)
- [8. Search Strategy](#8-search-strategy)
- [9. API Design](#9-api-design)
- [10. User Interface Considerations](#10-user-interface-considerations)
- [11. Package vs. Custom Build Analysis & Recommendations](#11-package-vs-custom-build-analysis--recommendations)
- [12. Future Considerations / Roadmap](#12-future-considerations--roadmap)
- [13. Out of Scope](#13-out-of-scope)
- [14. Assumptions](#14-assumptions)
  - [14.1 Technical Assumptions](#141-technical-assumptions)
  - [14.2 Project Assumptions](#142-project-assumptions)
- [15. Glossary](#15-glossary)
</details>

## Executive Summary

The Enhanced Laravel Application (ELA) provides a sophisticated web platform built on Laravel 12 and PHP 8.4, designed to deliver robust data management, collaboration, content publishing, and real-time communication capabilities.

## Key Features
- **Team-Based Collaboration** with hierarchical organization and fine-grained permissions
- **Content Management** including blogging, todos, and categorization
- **Real-Time Communication** through an integrated chat system
- **Comprehensive Administration** via a customized Filament admin panel
- **Event Sourcing Architecture** providing complete audit trail and temporal query capabilities
- **Public API** for programmatic access and integration

## Technical Approach
The application implements an event sourcing architecture with pragmatic CQRS using the spatie/laravel-event-sourcing and hirethunk/verbs package suites. Event sourcing provides a complete history of all state changes, enabling powerful temporal queries and enhanced system resilience. This is complemented by state machines for complex business logic and real-time capabilities through Laravel Reverb. The system is designed for performance using Laravel Octane and horizontal scalability.

## Business Value
This application provides organizations with a unified platform for internal collaboration and content management, reducing tool fragmentation while maintaining high security and compliance standards. The event sourcing architecture delivers exceptional business value through complete audit trails, point-in-time recovery capabilities, enhanced debugging, and reliable system recovery. Organizations gain unprecedented visibility into system changes and user actions, supporting compliance requirements and enabling advanced business analytics based on event streams.

---

## Version History

<details>
<summary>Click to expand version history</summary>

| Version | Date       | Author | Changes | Approved By |
|---------|------------|--------|---------|-------------|
| 1.0.0 | 2025-05-10 | AI Assistant | Initial draft | Project Manager |
| 2.0.0 | 2025-05-11 | AI Assistant | Added advanced chat features, reporting, API specifications | Technical Lead |
| 2.1.0 | 2025-05-11 | AI Assistant | Refined CQRS implementation, updated package recommendations | Architecture Committee |
| 2.2.0 | 2025-05-12 | AI Assistant | Updated based on stakeholder feedback, added implementation details | Project Sponsor |
| 2.3.0 | 2025-05-13 | AI Assistant | Added executive summary, version history, updated Tailwind CSS version | Technical Lead |
| 2.3.1 | 2025-05-17 | AI Assistant | Updated version numbering to use semantic versioning | Technical Lead |
| 2.4.0 | 2025-05-18 | AI Assistant | Updated to highlight event sourcing architecture, benefits, and implementation | Technical Lead
</details>

---

## <span style="color:#1E90FF;">1. Introduction & Overview</span>

This document outlines the requirements for a sophisticated web application built on the **Laravel 12 framework** and **PHP 8.4**. The application provides a robust platform for data management, collaboration, content publishing, real-time communication, administration, and programmatic access via a public API.

### Key Features
* **Security:** Multi-Factor Authentication (MFA), role-based access control
* **Data Organization:** Hierarchical data structures with configurable depth and complex move validation
* **Search:** Comprehensive search via **Typesense** with permission-aware filtering
* **Real-time Capabilities:** Advanced chat features using **Laravel Reverb**
* **Performance:** Efficient background processing with **Laravel Horizon**, high performance via **Laravel Octane**
* **Content Management:** Integrated blogging with workflow states
* **Communication:** Direct messaging with advanced features (read receipts, typing indicators)
* **Administration:** Comprehensive portal built with **Filament** including UI for command history
* **User Experience:** Model avatars, activity tracking, notifications
* **Data Governance:** Soft deletion and defined data purging mechanisms

The architecture emphasizes **Event Sourcing** (implemented via `spatie/laravel-event-sourcing`), the **CQRS pattern** (pragmatically applied via `hirethunk/verbs`), **state machines** for lifecycle management, enhanced **Enums**, and a **Livewire** frontend. Advanced reporting and analytics capabilities are also included, leveraging the rich event history provided by the event sourcing architecture.

**Complexity & Risk Assessment:** This project involves significant complexity due to the synergistic use of advanced concepts like Event Sourcing, CQRS, custom real-time chat with advanced features, hierarchical data structures with team scoping and complex move validation, multiple state machines, MFA, a public API, and the integration of numerous third-party packages (Typesense, Reverb, Spatie suite including Event Sourcing, Filament, `hirethunk/verbs`, etc.). Potential risks include: extended development timelines, the need for team members proficient in these specific technologies (or allocated learning time), challenges in ensuring seamless integration between components, event schema evolution management, and the necessity for rigorous, comprehensive testing strategies.

### <span style="color:#1E90FF;">1.1. Core Concept: Team Scoping</span>

A fundamental principle of this application is **Team Scoping**. Teams act as primary organizational units and often serve as data boundaries. Many resources, such as Categories (see 4.4), are directly associated with a specific Team. User permissions, search filtering (see 8), and potentially communication scopes (see 4.19) are frequently evaluated within the context of Team membership or hierarchy.

**Benefits of Team Scoping:**
* Data isolation and security
* Structured collaboration
* Clear ownership boundaries
* Simplified permission management
* Contextual user experience

**Implementation Considerations:**
* Careful data modeling with team_id foreign keys
* Validation rules enforcing team boundaries
* Permission checks incorporating team context
* UI components filtered by team context
* Search queries with team-based filtering

---

## <span style="color:#1E90FF;">2. Goals & Objectives</span>

### 2.1 Security & Access Control
*   **Secure Access:** Implement robust authentication including Multi-Factor Authentication (MFA).
*   **Robust Security & Permissions:** Implement fine-grained access control using `spatie/laravel-permission`.

### 2.2 Data & Content Management
*   **Efficient Data Management:** Intuitive interfaces for managing Users, Teams, Categories, Todos, Blog Posts, etc., including visual identification via avatars.
*   **Structured Lifecycles:** Implement clear state machines for core models (User, Team, Post, Todo) with visually distinct states.
*   **Advanced Hierarchical Organization:** Support nested structures for Teams, Categories, and Todos with configurable maximum depth and robust validation for moving entire sub-trees.
*   **Team-Scoped Data:** Ensure key resources like Categories are strictly bound to Teams.
*   **Content Publishing:** Provide a fully featured blogging platform with lifecycle management and RSS feeds.

### 2.3 Communication & Collaboration
*   **Advanced Real-time Communication:** Enable configurable direct messaging and chat between users with features like file attachments, read receipts, and typing indicators.
*   **Powerful Search:** Allow users to quickly find information across all relevant data types using Typesense, respecting permissions and team scoping.

### 2.4 Integration & Extensibility
*   **Extensibility via API:** Provide a secure, versioned, and well-documented public-facing API for third-party integrations and potential mobile applications.
*   **Insightful Analytics:** Offer advanced reporting and analytics capabilities for admins and managers.

### 2.5 Technical Excellence
*   **Event-Sourced Architecture:** Implement event sourcing using `spatie/laravel-event-sourcing` to maintain a complete history of all state changes, enabling temporal queries and enhanced system resilience.
*   **Scalable & Performant Architecture:** Leverage modern Laravel tools (Octane, Horizon, Reverb) for high throughput with event store optimizations and snapshot strategies.
*   **Comprehensive Auditability:** Track all state changes through the event store, providing a complete and immutable audit trail. Complement with userstamps, activity logs, and CQRS command history (`hirethunk/verbs`) with UI for audit. Implement soft deletion and defined data purging mechanisms in line with GDPR.
*   **Rich User Experience:** Deliver an interactive and responsive UI using Livewire and a dedicated Filament admin panel with event history visualization.
*   **Maintainability & Developer Experience:** Utilize modern tooling (Laravel 12, PHP 8.4), testing practices, event sourcing, pragmatic CQRS, state machines, enhanced Enums.
*   **Centralized Administration:** Offer a comprehensive Filament admin portal for managing all application aspects, including event store monitoring and projection rebuilding.

---

## <span style="color:#1E90FF;">3. Target Audience</span>

### <span style="color:#1E90FF;">3.1. User Types</span>

<details>
<summary>Click to expand user types table</summary>

| User Type | Color Code | Primary Responsibilities | Access Level |
|-----------|------------|--------------------------|-------------|
| <span style="color:#FF4500;">`Admin`</span> | #FF4500 | System-wide configuration, user management | Full access to all reports, command history, and data purging tools via Filament |
| <span style="color:#FFA500;">`Manager`</span> | #FFA500 | Oversees specific Teams and related content | Team-scoped access to reports and management features. Permissions are explicit per team |
| <span style="color:#4682B4;">`User`</span> | #4682B4 | Collaborates within Teams | Manages personal Todos, uses advanced chat, interacts with blog content. Can enable MFA |
| <span style="color:#20B2AA;">`Customer`</span> | #20B2AA | External stakeholder | Limited access to specific content or restricted chat features |
| <span style="color:#778899;">`Guest`</span> | #778899 | Minimal interaction | Authenticated user with minimal permissions |
| <span style="color:#6A5ACD;">`API Consumer`</span> | #6A5ACD | Programmatic access | External applications or services interacting via the public API, authenticated with API tokens |
</details>

### <span style="color:#1E90FF;">3.2. User Personas</span>

#### <span style="color:#FF4500;">Alex (System Administrator)</span>
- **Role:** Admin
- **Demographics:** 35 years old, IT professional with 10+ years of experience
- **Goals:**
  - Ensure system security and stability
  - Configure system settings efficiently
  - Monitor user activity and system performance
  - Manage user accounts and permissions
- **Pain Points:**
  - Complex permission management across multiple teams
  - Difficulty tracking system changes and identifying issues
  - Time-consuming user management tasks
- **Needs:**
  - Comprehensive admin dashboard with key metrics
  - Detailed audit logs and command history
  - Efficient user and permission management tools
  - System health monitoring

#### <span style="color:#FFA500;">Maria (Team Manager)</span>
- **Role:** Manager
- **Demographics:** 42 years old, department head with 15 years of industry experience
- **Goals:**
  - Organize team structure and workflows
  - Track team progress and performance
  - Ensure content quality and timely delivery
  - Facilitate team communication
- **Pain Points:**
  - Difficulty maintaining visibility across team activities
  - Challenges in organizing hierarchical data
  - Time spent on reporting and status updates
- **Needs:**
  - Team-specific dashboards and reports
  - Content approval workflows
  - Team communication tools
  - Hierarchical data management

#### <span style="color:#4682B4;">Carlos (Regular User)</span>
- **Role:** User
- **Demographics:** 28 years old, knowledge worker who collaborates with multiple teams
- **Goals:**
  - Manage personal tasks efficiently
  - Collaborate with team members
  - Share and consume content
  - Stay informed about relevant updates
- **Pain Points:**
  - Information overload from multiple sources
  - Difficulty tracking tasks across different projects
  - Challenges in finding relevant information
- **Needs:**
  - Intuitive task management
  - Efficient communication tools
  - Powerful search functionality
  - Personalized notifications

#### <span style="color:#6A5ACD;">TechCorp (API Consumer)</span>
- **Role:** API Consumer
- **Demographics:** External system integrating with the platform
- **Goals:**
  - Access and update data programmatically
  - Integrate platform data with other systems
  - Automate workflows across platforms
- **Pain Points:**
  - Complex authentication requirements
  - Inconsistent API responses
  - Limited documentation
- **Needs:**
  - Well-documented API endpoints
  - Consistent response formats
  - Reliable authentication mechanism
  - Appropriate rate limits

---

## <span style="color:#1E90FF;">4. Functional Requirements</span>

### <span style="color:#1E90FF;">4.0. Feature Prioritization Matrix</span>

The following matrix provides prioritization and effort estimation for key features:

<details>
<summary>Click to expand feature prioritization matrix</summary>

| Feature | Priority | Effort | Risk | Value | Timeline |
|---------|----------|--------|------|-------|----------|
| **Authentication & Authorization** | Must | M | Medium | High | MVP |
| **User Management** | Must | M | Low | High | MVP |
| **Team Management** | Must | L | Medium | High | MVP |
| **Category Management** | Must | M | Low | Medium | MVP |
| **Todo Management** | Must | M | Low | Medium | MVP |
| **Media Management** | Must | M | Low | Medium | MVP |
| **Admin Portal (Filament)** | Must | L | Medium | High | MVP |
| **Basic Search** | Must | M | Medium | High | MVP |
| **Advanced Team & Category Management** | Should | L | High | Medium | 1.0 |
| **Hierarchical Data with Validation** | Should | XL | High | Medium | 1.0 |
| **Blogging Feature** | Should | L | Medium | Medium | 1.0 |
| **Basic Chat** | Should | L | Medium | High | 1.0 |
| **Tagging & Comments** | Should | M | Low | Medium | 1.0 |
| **Activity Logging** | Should | S | Low | Medium | 1.0 |
| **Application Settings** | Should | S | Low | Medium | 1.0 |
| **Notifications** | Should | M | Medium | High | 1.0 |
| **Advanced Chat Features** | Could | XL | High | Medium | 2.0 |
| **Public API** | Could | L | Medium | High | 2.0 |
| **Advanced Reporting** | Could | L | Medium | Medium | 2.0 |
| **Command History UI** | Could | M | Medium | Medium | 2.0 |
| **Data Purging** | Could | M | Medium | Medium | 2.0 |
| **Multilingual Support** | Could | L | Medium | Medium | 2.0 |

**Priority Legend:**
- **Must:** Critical for MVP launch
- **Should:** Important but not critical for initial launch
- **Could:** Valuable but can be deferred
- **Won't:** Out of scope for current versions

**Effort Legend:**
- **S:** Small (1-2 weeks for 1 developer)
- **M:** Medium (2-4 weeks for 1 developer)
- **L:** Large (4-8 weeks for 1 developer)
- **XL:** Extra Large (8+ weeks or requires multiple developers)
</details>

* **4.1. Authentication & Authorization**
    * **4.1.1. User Registration:** Configurable as Open or Invite-Only via application settings.
    * **4.1.2. User Login:** Standard email/password authentication with security best practices.
    * **4.1.3. Password Reset:** Secure password recovery workflow.
    * **4.1.4. Social Authentication:** Integration with popular providers via `laravel/socialite`.
    * **4.1.5. Role-Based Access Control (RBAC):** Implementation using `spatie/laravel-permission` for roles, permissions, and assignments.
    * **4.1.6. User Impersonation:** Secure admin capability via `lab404/laravel-impersonate`, integrated with Filament.
    * **4.1.7. User Types (Single Table Inheritance):** Uses `tightenco/parental` with `type` string column. Initial types: `Admin`, `Manager`, `User`, `Customer`, `Guest`.
    * **4.1.8. User State Machine:** Manage user lifecycle using `spatie/laravel-model-states` and enhanced PHP Enums (see 6.12). State column: `status`.
       *   States: `Invited`, `PendingActivation`, `Active`, `Suspended`, `Deactivated`.

           <details>
                  <summary><strong>User State Machine Diagram - Click to expand</strong></summary>

           ~~~mermaid
           stateDiagram-v2
               [*] --> Invited : Admin Creates/Invites
               Invited --> PendingActivation : User Registers / Claims Invite
               [*] --> PendingActivation : User Self-Registers (if enabled)
               PendingActivation --> Active: Activate / Verify Email
               Active --> Suspended: Suspend Action
               Suspended --> Active: Reinstate Action
               Active --> Deactivated: Deactivate Action
               Suspended --> Deactivate: Deactivate Action
               Deactivated --> [*] : (Soft Delete, Potentially Purge Data Later)
           ~~~
           </details>

    * **4.1.9. Data Feed Subscription Permissions:** Specific permission (`subscribe_to_data_feeds`) controls access
      to real-time data change notifications/feeds.
    * **4.1.10. User Avatars:** Users can upload and manage a profile picture via `spatie/laravel-medialibrary`.
      Managed in user profile (Livewire) and Filament.
    * **4.1.11. Multi-Factor Authentication (MFA) / Two-Factor Authentication (2FA) (v2.0 Scope)**
        *   **4.1.11.1. Goal:** Enhance account security.
        *   **4.1.11.2. Implementation:** Leverage **Laravel Fortify's Built-in 2FA** for TOTP-based authentication
            (e.g., Google Authenticator, Authy).
        *   **4.1.11.3. User Experience:** Enable/disable in profile, QR code setup, recovery codes provided. Prompt
            for 2FA code after successful password login.
        *   **4.1.11.4. Scope:** Configurable as optional for all users or mandatory for specific roles (e.g.,
            `Admin`, `Manager`).
        *   **4.1.11.5. Administration (Filament):** Admins can view MFA status, disable MFA for locked-out users
            (with audit), and reset recovery codes.

* **4.2. User Management (Admin)**
    *   CRUD operations for users (Filament), including avatars.
    *   Assign/revoke roles & permissions (Filament).
    *   View user activity logs (Filament).
    *   Manage user state transitions (Filament).
    *   Invite Users.
    *   View user tracking info (`created_by`, `updated_by`) and MFA status.

* **4.3. Team Management**
    * **4.3.1. Team Administration:** CRUD operations for Teams via Filament admin interface.
    * **4.3.2. Team Avatars:** Visual identification using `spatie/laravel-medialibrary`.
    * **4.3.3. Hierarchical Structure:** Self-referential hierarchy using `staudenmeir/laravel-adjacency-list` with custom Filament UI.
    * **4.3.4. Configurable Depth:** Maximum hierarchy depth controlled via application settings (see 4.21).
    * **4.3.5. Team Membership:** User-Team association with role assignments within team context.
    * **4.3.6. Sluggable URLs:** SEO-friendly URLs via `spatie/laravel-sluggable`.
    * **4.3.7. Team State Machine:** Lifecycle management with states `Forming`, `Active`, `Archived` using `spatie/laravel-model-states` and enhanced Enums.

        <details>
        <summary><strong>Team State Machine Diagram - Click to expand</strong></summary>

        ~~~mermaid
        stateDiagram-v2
            [*] --> Forming
            Forming --> Active: Launch
            Active --> Archived: Archive
            Archived --> Active: Restore
            Archived --> [*] : (Soft Delete)
        ~~~
        </details>

    *   **4.3.8. Team Hierarchy Permission Inheritance:** Explicit per team (**No Inheritance** for v2.0).
        *   **4.3.8.1. Rationale:** This approach provides simpler implementation, clear boundaries between teams, reduced risk of unintended access, and easier permission auditing.
        *   **4.3.8.2. Future Consideration:** Permission inheritance may be considered as an opt-in feature in future versions after the core system is stable and well-tested.

* **4.4. Category Management**
    *   CRUD (Filament).
    *   **Strict Team Scoping:** `team_id` FK (NN). No global categories.
    *   **Uniqueness:** Slugs/names unique within `Team`.
    *   **Hierarchy:** Self-referential (`staudenmeir/laravel-adjacency-list`), parent within same `Team`.
    *   **Configurable Depth:** Application settings (see 4.21).
    *   **Associations:** Link to Posts, Todos (respecting team boundaries).
    *   **Authorization:** Team-contextual policies.

* **4.5. Todo Management**
    * CRUD (Filament/Dedicated UI).
    * Self-referential hierarchy (`staudenmeir/laravel-adjacency-list`).
    * **Configurable Depth:** Application settings (see 4.21).
    * Associate with Users, Teams, Categories (consistent scoping).
    * Sluggable Todos (`spatie/laravel-sluggable`).
    * **Todo State Machine:** `Pending`, `InProgress`, `Completed`, `Cancelled` using `spatie/laravel-model-states` and enhanced Enums.

        <details>
            <summary><strong>Todo State Machine Diagram - Click to expand</strong></summary>

        ~~~mermaid
        stateDiagram-v2
            [*] --> Pending
            Pending --> InProgress: Start
            InProgress --> Completed: Complete
            InProgress --> Pending: Pause / Re-queue
            Pending --> Cancelled: Cancel
            InProgress --> Cancelled: Cancel
        ~~~
        </details>

* **4.6. Media Management**
    *   Uploads via `spatie/laravel-medialibrary`.
    *   Associate media with models (User avatars, Team avatars, Post images, Todo attachments, Chat attachments). Distinct media collections.
    *   Managed via Filament (`filament/spatie-laravel-media-library-plugin`).
    *   Image conversions (thumbnails, responsive variants).

* **4.7. Tagging**
    *   `spatie/laravel-tags`. Define tag types.
    *   Managed via Filament (`filament/spatie-laravel-tags-plugin`).

* **4.8. Comments**
    *   `spatie/laravel-comments` with Livewire UI (`spatie/laravel-comments-livewire`).
    *   Manageable (delete, approve) via Filament.

* **4.9. Search**
    *   Global search via **Typesense** (`laravel/scout`, `typesense/laravel-scout-driver`).
    *   Index relevant models (Users, Teams, Categories, Todos, Posts, Tags).
    *   **Permission Enforcement (Index-Level Filtering):** Index `team_id`, `is_public`, status. Typesense `filter_by` clauses based on user context.
    *   Real-time indexing via Horizon queues.
    *   Search UI in Livewire & Filament.

* **4.10. Activity Logging**
    *   `spatie/laravel-activitylog`. Log key events (state transitions, CRUD, soft deletes/restores).
    *   View logs via Filament (`filament/spatie-laravel-activitylog-plugin`).

* **4.11. Application Settings**
    *   `spatie/laravel-settings`.
    *   Managed via custom Filament page (incl. hierarchy depth).

* **4.12. Notifications**
    *   Laravel Notifications system. Channels: Database, Mail, Slack.
    *   Real-time in-app via Reverb + Livewire.
    *   Triggered by events. Manageable in user profiles / Filament.

* **4.13. Multilingual Support**
    *   UI strings: `spatie/laravel-translation-loader`.
    *   Model translations: `spatie/laravel-translatable`.
    *   Managed via Filament (`filament/spatie-laravel-translatable-plugin`).

* **4.14. Background Job Processing**
    *   `laravel/horizon`. Monitor via Horizon dashboard (link from Filament).

* **4.15. Feature Flags**
    *   `laravel/pennant`. Potentially managed via custom Filament resource.

* **4.16. Cookie Consent**
    *   `statikbe/laravel-cookie-consent`.

* **4.17. Unique Identifiers**
    *   Slugs: `spatie/laravel-sluggable` (User, Team, Post, Todo).
    *   Snowflake IDs: `godruoyi/php-snowflake` on primary models.

* **4.18. Blogging Feature**
    * **4.18.1. Post Management (CRUD):** Filament. Title, Slug, Content (Rich Editor), Author, Featured Image, Excerpt, Meta, Tags, Categories.
    * **4.18.2. Post State Machine:** `Draft`, `PendingReview`, `Published`, `Scheduled`, `Archived` using `spatie/laravel-model-states` and enhanced Enums.
        <details>
            <summary><strong>Post State Machine Diagram - Click to expand</strong></summary>

        ~~~mermaid
        stateDiagram-v2
            [*] --> Draft
            Draft --> PendingReview: Submit for Review
            Draft --> Published: Publish Directly (permission-based)
            PendingReview --> Draft: Reject / Request Changes
            PendingReview --> Published: Approve & Publish
            Draft --> Scheduled: Schedule
            PendingReview --> Scheduled: Approve & Schedule
            Scheduled --> Published: Time Reached (Scheduled Job)
            Published --> Archived: Archive
            Archived --> Draft: Unarchive to Draft
        ~~~
        </details>

    * **4.18.3. Permissions:** `spatie/laravel-permission`, Filament policies.
    * **4.18.4. Pub/Sub Notifications:** Laravel Events on state transitions, broadcast via Reverb.
    * **4.18.5. RSS Feeds:** `spatie/laravel-feed` for Published posts.

---

*   **4.19. Real-time Direct Messaging / Chat**
    *   **4.19.1. Core Functionality:** Custom build (Livewire + Reverb). Models: `Conversation`, `Message`, `Participant`.
    *   **4.19.2. Baseline Features:** Text messages, persistence, conversation list (participant avatars), unread indicators, basic presence, emoji support.
    *   **4.19.3. Real-time Delivery:** Reverb broadcasting + Livewire.
    *   **4.19.4. Configurable Chat Scopes:** Controlled by `spatie/laravel-permission` and custom logic:
        *   `1-to-1`
        *   `Within Same Team`
        *   `Within Team Hierarchy` (adjacency list logic, no permission inheritance)
        *   `Across Root-Level Teams` / Ad-hoc Groups
        *   Scoping by `User Type`
    *   **4.19.5. UI Components:** Livewire components for conversation list, chat window, input, etc.
    *   **4.19.6. Permissions:** Granular chat-related permissions.
    *   **4.19.7. Advanced Chat Features (v2.0 Scope - Phased Implementation)**
        *   **Implementation Priority:** Features will be implemented in the following order to manage risk and allow for user feedback:
            1. Typing Indicators
            2. Read Receipts
            3. Message Quoting/Replying
            4. File Attachments
            5. Message Editing/Deletion (with time-limited policy)
        *   **4.19.7.1. Typing Indicators:**
            *   "User X is typing..." display.
            *   Client-side detection, Reverb client events, debouncing.
            *   **Rationale:** Highest value-to-effort ratio with minimal implementation risk.
        *   **4.19.7.2. Read Receipts:**
            *   Visual indicators for "Delivered" and "Seen".
            *   1-to-1: Clear seen status.
            *   Group Chats: "Seen by X participants" with ability to **List of Viewers (on hover/click)**.
            *   Implementation: `message_read_status` pivot, Reverb events, Livewire updates.
            *   **Rationale:** Important feedback mechanism with moderate implementation complexity.
        *   **4.19.7.3. Message Quoting/Replying:**
            *   UI to select and reply to a previous message, showing quoted original.
            *   `Message` model: `parent_message_id` FK.
            *   **Rationale:** Critical for maintaining conversation context in busy chats.
        *   **4.19.7.4. File Attachments:**
            *   Via `spatie/laravel-medialibrary` on `Message` model.
            *   UI for picking/displaying files. Configurable types/sizes.
            *   Security: Validation, malware scanning (significant NFR if full scanning required).
            *   **Rationale:** Essential functionality but requires careful security implementation.
        *   **4.19.7.5. Message Editing & Deletion (for Sender):**
            *   Policy: **Time-Limited Editing/Deletion (48 hours after sending)**.
            *   UI: Context menu. "(edited)" indicator for edited messages. Disabled edit/delete options after time limit.
            *   Implementation: Soft delete for deletion. Track `edited_at`. Reverb events for updates.
            *   **4.19.7.5.1. Rationale:** Time-limited approach balances user flexibility with conversation integrity while aligning with user expectations from other messaging platforms.

*   **4.20. Comprehensive Admin Portal**
    *   **4.20.1. Platform:** `filament/filament` >=v3.3 (or latest compatible with Laravel 12).
    *   **4.20.2. Functionality:** Manage Users (avatars, MFA status), Roles, Permissions, Teams (avatars, hierarchy), Categories, Todos, Posts, Tags, Media, Settings, Activity Logs, Comments, User Tracking info.
    *   **4.20.3. Integration:** Recommended Filament plugins.
    *   **4.20.4. Customization:** Custom Filament resources/pages/actions/widgets.
    *   **4.20.5. Dashboard:** Basic Filament dashboard with key stats widgets.
    *   **4.20.6. Command History & Snapshot UI (Filament - v2.0 Scope)**
        *   **4.20.6.1. Goal:** Audit/debug UI for `hirethunk/verbs` history data.
        *   **4.20.6.2. Command Log Resource:** Filament resource for `command_logs`. Table view (ID, Name, Status, Handled At, User). Filters. View page for payload, results, associated snapshots.
        *   **4.20.6.3. Snapshot Viewing:** **Integrate Snapshots into relevant Model Resources.** Add "History/Snapshots" tab/relation manager to view/edit pages of snapshotted models, listing their snapshots.
        *   **4.20.6.4. Snapshot Detail View:** Display snapshot data (formatted JSON/array), version, timestamp, link to command. Implement a **Basic Diff Viewer** to compare a snapshot with another version of the *same subject*.
        *   **4.20.6.5. Permissions:** Restricted to admin roles.
        *   **4.20.6.6. Read-Only:** UI is for viewing only.

*   **4.21. Configurable Hierarchy Depth**
    *   **4.21.1. Configuration Strategy:**
        *   **Team hierarchy depth:** Global setting via `spatie/laravel-settings`
        *   **Category and Todo hierarchies:** Team-specific depth settings within system-defined upper bounds
    *   **4.21.2. Admin UI:** Filament settings page with global and team-specific configuration options.
    *   **4.21.3. Defaults:** Sensible defaults (e.g., 5 levels for Teams, 3 for Categories, 3 for Todos).
    *   **4.21.4. Enforcement:** CQRS Command Handlers/Service Layer & Validation Rules.
    *   **4.21.5. Scope & Limitations (v1.2 logic):** Validates single item moves.
    *   **4.21.5.1. Rationale:** Team-specific depth settings provide flexibility for different organizational needs while maintaining reasonable constraints for performance and usability.
    *   **4.21.6. Complex Hierarchy Move Validation (v2.0 Scope)**
        *   **4.21.6.1. Goal:** Prevent moving a sub-tree if any descendant would exceed max depth.
        *   **4.21.6.2. Validation Logic:** When moving `Item_M` with descendants under `NewParent_P`, calculate new potential depth for `Item_M` and all its descendants. If any exceed `max_depth`, disallow move.
        *   **4.21.6.3. Enforcement:** Primarily in Command Handlers. UX feedback via form validation (ideal: pre-filter parent choices; minimum: clear error on submission).
        *   **4.21.6.4. Performance:** Optimize traversal. For failures on very large sub-trees: **Simple Rejection with an error message.**
        *   **4.21.6.5. Scope:** Applies to all hierarchical models.

---

*   **4.22. Audit Trails & Soft Deletes**
    *   **4.22.1. User Tracking:** Core models track `created_by`, `updated_by`, `deleted_by` via `wildside/userstamps` or custom trait.
    *   **4.22.2. Soft Deletes:** Core models use `SoftDeletes` trait (`deleted_at` column). `deleted_by` populated on soft delete.

*   **4.23. Public-Facing API (v2.0 Scope)**
    *   **4.23.1. Purpose:** Enable third-party integrations, support future mobile apps.
    *   **4.23.2. Design:** RESTful, JSON, versioned endpoints (`/api/v1/...`).
    *   **4.23.3. Authentication:** **Laravel Sanctum API Tokens**.
    *   **4.23.4. Authorization:** `spatie/laravel-permission` via Policies.
    *   **4.23.5. Versioning:** URI versioning. Clear deprecation policy.
    *   **4.23.6. Rate Limiting:** Laravel's built-in rate limiting, configurable.
    *   **4.23.7. Data Transformation:** Laravel API Resources. Support for including related data.
    *   **4.23.8. Initial Scoped Resources (Read-Only examples):** Users, Teams, Categories, Posts.
    *   **4.23.9. Future CRUD Endpoints:** To be defined by priority (e.g., creating Todos).
    *   **4.23.10. Error Handling:** Consistent JSON error responses, standard HTTP status codes.
    *   **4.23.11. Documentation:** **OpenAPI (Swagger) Specification**, generated using a suitable Laravel package.
    *   **4.23.12. Testing:** Automated feature tests for all API endpoints.

*   **4.24. Advanced Reporting & Analytics (v2.0 Scope)**
    *   **4.24.1. Goal:** Provide insights for `Admin` and `Manager` roles.
    *   **4.24.2. Approach:** Reports accessible via Filament. Combination of direct queries, aggregated data, visualizations.
    *   **4.24.3. Implementation Strategy:** **Custom Filament Pages with Charting Libraries** (e.g., Chart.js, ApexCharts). Data via Eloquent/DB queries.
    *   **4.24.4. Standard Reports (Initial Set):**
        *   User Activity: Registrations, Logins, Engagement, Counts by status/type.
        *   Content Reports: Posts created/published, Most viewed/commented, Content by Category/Tag, Todo metrics.
        *   Team Reports: Activity summaries, resource utilization.
        *   System Health/Audit: Job summaries, Search trends, Command execution summaries.
    *   **4.24.5. Report Features:** Filtering (date, type, team, status), Data Export (CSV/Excel), Visualizations (charts, tables), Permission-based visibility.
    *   **4.24.6. Data Considerations:** Optimized queries, potential read replicas or summary tables. View tracking mechanism needed for "Most Viewed Posts".

---

## <span style="color:#1E90FF;">5. Non-Functional Requirements</span>

* **5.1. <span style="color:#FFD700;">Performance</span>:** Optimized via `laravel/octane`, Livewire, efficient queries, Typesense, Horizon. Monitored by `laravel/pulse`. Sub-500ms TTFB target.
* **5.2. <span style="color:#FFD700;">Scalability</span>:** Horizontal scaling for Octane, Horizon, Reverb, DB, Typesense. Stateless design where possible.
* **5.3. <span style="color:#FFD700;">Reliability & Availability</span>:** Error handling/reporting (`sentry/sentry-laravel`), structured logging, automated DB backups (`spatie/laravel-backup`), HA production setup, queue monitoring.
* **5.4. <span style="color:#FF4500;">`Security`</span>:** Secure auth (incl. MFA), OWASP Top 10 protection, strict authorization, dependency monitoring, API security (Sanctum), media upload security. Command history/audit data protection.
* **5.5. <span style="color:#FFD700;">Maintainability & Code Quality</span>:** PSR-12 (`laravel/pint`), static analysis, comprehensive testing, refactoring support, clear architectural patterns (Pragmatic CQRS via `hirethunk/verbs`, State Machines, Services), documented code, Filament structure, proactive dependency management, full audit trails (userstamps, activity logs, command history).
* **5.6. <span style="color:#FFD700;">Usability & Accessibility</span>:** Intuitive UI (Livewire, Filament), WCAG 2.1 AA adherence, responsive design. Avatars & status indicators for clarity.
* **5.7. <span style="color:#FFD700;">Development Experience</span>:** Standardized local environment (`laravel/sail`), debug/monitoring tools. Development on **PHP 8.4** and **Laravel 12**.
* **5.8. <span style="color:#FFD700;">Data Privacy & Compliance:** Adherence to **GDPR**. Mechanisms for data subject rights. Data minimization, retention policies. Clear privacy policy.
    *   **5.8.1. Data Purging Mechanisms (v2.0 Scope)**
        *   **5.8.1.1. Goal:** Permanently delete data per GDPR "right to erasure" and retention policies.
        *   **5.8.1.2. Scope of Data:** User account, user-generated content, associated media, related audit data (activity log, command history/snapshots), user's chat messages.
            *   **5.8.1.2.1. Hybrid Data Purging Strategy:**
                *   **Public content** (blog posts, comments): Anonymize author but preserve content
                *   **Team content** (todos, categories): Anonymize or delete based on team policy setting
                *   **Private messages**: Complete deletion with conversation context preservation
                *   **User profile data**: Complete deletion
            *   **5.8.1.2.2. Rationale:** This hybrid approach provides the best balance between regulatory compliance, user experience, and implementation complexity.
        *   **5.8.1.3. Implementation:** Queued background job (`PurgeUserDataJob`). Verification, anonymization/deletion steps for each data type. Thorough audit logging of purge actions.
        *   **5.8.1.4. Retention Policies (Automated):** Define and implement via scheduled commands for soft-deleted items, audit logs, etc.
        *   **5.8.1.5. Admin Interface (Filament):** Initiate user purge (with warnings/confirmation), view purge status, configure retention policies.

---

## <span style="color:#1E90FF;">6. Design & Architecture Considerations</span>

*   **6.1. Framework:** `laravel/framework` ^12.0. **PHP Version:** ^8.4.
*   **6.2. Frontend:** `livewire/livewire` ^3.x (or latest stable compatible with Laravel 12), Alpine.js ^3.x, Tailwind CSS ^4.x.
*   **6.3. Backend Architecture: Event Sourcing with Pragmatic CQRS**
    * **Strategy:** Implement event sourcing for core domain entities using `spatie/laravel-event-sourcing`. Core business logic (state transitions, complex associations, operations with significant side-effects/events) MUST use Commands. Simpler CRUD operations *can* use traditional Controller/Livewire actions.
    * **Implementation:** Utilize the `spatie/laravel-event-sourcing` package for event sourcing and the `hirethunk/verbs` package suite for command handling. For detailed implementation guidelines, see [Event Sourcing Implementation](../100-implementation-plan/100-350-event-sourcing/050-implementation.md).
    * **Commands:** `hirethunk/verbs`. `spatie/laravel-data` for DTOs within commands.
    * **Command Bus:** `hirethunk/verbs` bus mechanism.
    * **Command Handlers:** Dedicated classes per `hirethunk/verbs` conventions, integrated with event sourcing aggregates.
    * **Aggregates:** Domain objects that handle commands and apply events using `spatie/laravel-event-sourcing`.
    * **Event Store:** Persistent storage for all domain events using `spatie/laravel-event-sourcing`.
    * **Projectors:** Build and maintain read models based on events using `spatie/laravel-event-sourcing`.
    * **Reactors:** Execute side effects when specific events occur using `spatie/laravel-event-sourcing`.
    * **Command History & Snapshots:** `hirethunk/verbs` history and snapshots. Configure recorded events/states.
    * **Queries:** Dedicated Query Services/Classes or Eloquent Scopes.
    * **Key Aggregates:** The following domain entities will be implemented as event-sourced aggregates:
        * User
        * Team
        * Post
        * Todo
        * Comment
        * Message
    * **Testing Approach:** All event-sourced components must have comprehensive unit and integration tests. See [Event Sourcing Testing](../100-implementation-plan/100-350-event-sourcing/070-testing.md) for detailed testing guidelines.
    * **Events:** Domain events via `spatie/laravel-event-sourcing` and Laravel's Event system. Listeners (often queued) for side effects.
    * **Diagram (Reflecting `hirethunk/verbs` usage):**
        <details>
            <summary><strong>Event Sourcing with CQRS Flowchart Diagram - Click to expand</strong></summary>

        ```mermaid
        %%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
        flowchart TD
            A["Request / User Action"] --> B["Controller / Livewire Component"]

            subgraph "Write Side - Event Sourcing with CQRS"
                B_CMD["Controller / Livewire Component"] -->|"Sends Verbs Command Object"| C{"Command Bus
            (hirethunk/verbs)"}
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
        ```

        For dark mode, see [Event Sourcing with CQRS Flowchart (Dark Mode)](illustrations/mermaid/dark/event-sourcing-cqrs-dark.mmd)
        </details>

* **6.4. Real-time:** `laravel/reverb`.
* **6.5. Background Processing:** `laravel/horizon` (Requires Redis).
* **6.6. Performance:** `laravel/octane` (Swoole or RoadRunner).
* **6.7. Database:** Relational DB (PostgreSQL 15+ recommended, MySQL 8+ acceptable). Must support storage for `hirethunk/verbs-history`.
* **6.8. Search:** `typesense/typesense-php` via `laravel/scout` driver.
* **6.9. State Machines:** `spatie/laravel-model-states` using enhanced PHP Enums (see 6.12). Status history tracking via `spatie/laravel-model-status`.
* **6.10. Admin Panel:** `filament/filament` >=v3.3 (or latest stable compatible with Laravel 12).
* **6.11. Hierarchies:** `staudenmeir/laravel-adjacency-list` with configurable depth limits and complex move validation (see 4.21.6).
* **6.12. Enhanced Enums:** All custom PHP Enums (States, Types) to provide `getLabel()` and `getColor()` methods using native PHP 8.4 Enum features following Filament standards.

---

## <span style="color:#1E90FF;">7. Data Model / Database Design</span>

This section details the database schema for core application entities, complemented by a detailed Mermaid Class Diagram and a high-level Entity Relationship Diagram (ERD).

*   **Core Models Referenced:** User, Team, Category, Todo, Post, Role, Permission, Tag, Media, Comment, ActivityLog, Setting, StateHistory, Conversation, Message, Participant, Notification, Job, FailedJob, Feed, **CommandLog**, **Snapshot** (`hirethunk/verbs` tables).

*   **General Schema Conventions & Traits:**
    *   **Primary Keys (PK):** `id` (BIGINT UNSIGNED, AUTO_INCREMENT).
    *   **Snowflake IDs:** `snowflake_id` (BIGINT UNSIGNED, UNIQUE, INDEX).
    *   **Userstamps:** Core custom models (User, Team, Category, Post, Todo, Conversation, Message) include `created_by`, `updated_by`, `deleted_by` (BIGINT UNSIGNED, NULLABLE, FK to `users.id`, INDEX).
    *   **Soft Deletes:** Core custom models use `SoftDeletes` (`deleted_at` TIMESTAMP, NULLABLE, INDEX).
    *   **Slugs:** User, Team, Post, Todo models have a `slug` (STRING, UNIQUE, INDEX).
    *   **State Machines:** `status` column (STRING, INDEX) for User, Team, Post, Todo.
    *   **Hierarchies:** `parent_id`, `path`, `depth` for Team, Category, Todo.
    *   **Standard Timestamps:** `created_at`, `updated_at`.
    *   **Spatie & `hirethunk/verbs` Tables:** Schemas as defined by respective packages.

*   **Detailed Schema for Core Entities (Summary - refer to Class Diagram for attribute details):**
    *   **USER (`users`):** id, snowflake_id, slug, type, email, password, email_verified_at, status, userstamps, timestamps, softDeletes.
    *   **TEAM (`teams`):** id, snowflake_id, slug, name, parent_id, path, depth, status, userstamps, timestamps, softDeletes.
    *   **CATEGORY (`categories`):** id, snowflake_id, team_id (FK NN), name, slug (UQ on team_id,slug), parent_id, path, depth, userstamps, timestamps, softDeletes.
    *   **POST (`posts`):** id, snowflake_id, user_id (Author FK NN), title, slug, content, excerpt, status, published_at, scheduled_for, userstamps, timestamps, softDeletes.
    *   **TODO (`todos`):** id, snowflake_id, title, slug, description, user_id (Assignee FK NULL), team_id (FK NULL), parent_id, path, depth, status, due_date, completed_at, userstamps, timestamps, softDeletes.
    *   **CONVERSATION (`conversations`):** id, uuid (UQ), name, type, userstamps, timestamps, softDeletes.
    *   **MESSAGE (`messages`):** id, uuid (UQ), conversation_id (FK NN), user_id (Sender FK NN), body, userstamps, timestamps, softDeletes.
    *   **COMMAND_LOG (`command_logs`):** As per `hirethunk/verbs`.
    *   **SNAPSHOT (`snapshots`):** As per `hirethunk/verbs`.

    <details>
    <summary><strong>Mermaid Class Diagram (Detailed Attributes) - Click to expand</strong></summary>

    ~~~mermaid
    classDiagram
        class USER {
            +bigint id [PK]
            +bigint snowflake_id [UQ, IDX]
            +string slug [UQ, IDX]
            +string type [IDX] %% STI type
            +string email [UQ]
            +string password %% HASH
            +timestamp email_verified_at [NULL]
            +string status [IDX] %% User state
            +bigint created_by [FK, NULL, IDX]
            +bigint updated_by [FK, NULL, IDX]
            +bigint deleted_by [FK, NULL, IDX]
            +timestamp created_at
            +timestamp updated_at
            +timestamp deleted_at [NULL, IDX]
        }

        class TEAM {
            +bigint id [PK]
            +bigint snowflake_id [UQ, IDX]
            +string slug [UQ, IDX]
            +string name
            +bigint parent_id [FK, NULL, IDX] %% Hierarchy
            +string path [IDX]
            +int depth [IDX]
            +string status [IDX] %% Team state
            +bigint created_by [FK, NULL, IDX]
            +bigint updated_by [FK, NULL, IDX]
            +bigint deleted_by [FK, NULL, IDX]
            +timestamp created_at
            +timestamp updated_at
            +timestamp deleted_at [NULL, IDX]
        }

        class CATEGORY {
            +bigint id [PK]
            +bigint snowflake_id [UQ, IDX]
            +bigint team_id [FK, NN, IDX]
            +string name
            +string slug [UQ(team_id, slug)]
            +bigint parent_id [FK, NULL, IDX] %% Hierarchy
            +string path [IDX]
            +int depth [IDX]
            +bigint created_by [FK, NULL, IDX]
            +bigint updated_by [FK, NULL, IDX]
            +bigint deleted_by [FK, NULL, IDX]
            +timestamp created_at
            +timestamp updated_at
            +timestamp deleted_at [NULL, IDX]
        }

        class POST {
            +bigint id [PK]
            +bigint snowflake_id [UQ, IDX]
            +bigint user_id [FK, NN, IDX] %% Author
            +string title
            +string slug [UQ, IDX]
            +text content
            +text excerpt [NULL]
            +string status [IDX] %% Post state
            +timestamp published_at [NULL, IDX]
            +timestamp scheduled_for [NULL]
            +bigint created_by [FK, NULL, IDX]
            +bigint updated_by [FK, NULL, IDX]
            +bigint deleted_by [FK, NULL, IDX]
            +timestamp created_at
            +timestamp updated_at
            +timestamp deleted_at [NULL, IDX]
        }

        class TODO {
            +bigint id [PK]
            +bigint snowflake_id [UQ, IDX]
            +string title
            +string slug [UQ, IDX]
            +text description [NULL]
            +bigint user_id [FK, NULL, IDX] %% Assignee
            +bigint team_id [FK, NULL, IDX] %% Associated Team
            +bigint parent_id [FK, NULL, IDX] %% Hierarchy
            +string path [IDX]
            +int depth [IDX]
            +string status [IDX] %% Todo state
            +timestamp due_date [NULL]
            +timestamp completed_at [NULL]
            +bigint created_by [FK, NULL, IDX]
            +bigint updated_by [FK, NULL, IDX]
            +bigint deleted_by [FK, NULL, IDX]
            +timestamp created_at
            +timestamp updated_at
            +timestamp deleted_at [NULL, IDX]
        }

        class CONVERSATION {
            +bigint id [PK]
            +string uuid [UQ, IDX]
            +string name [NULL]
            +string type [IDX]
            +bigint created_by [FK, NULL, IDX]
            +bigint updated_by [FK, NULL, IDX]
            +timestamp created_at
            +timestamp updated_at
            +timestamp deleted_at [NULL, IDX]
        }

        class MESSAGE {
            +bigint id [PK]
            +string uuid [UQ, IDX]
            +bigint conversation_id [FK, NN, IDX]
            +bigint user_id [FK, NN, IDX] %% Sender
            +text body
            +bigint created_by [FK, NULL, IDX]
            +bigint updated_by [FK, NULL, IDX]
            +timestamp created_at [IDX]
            +timestamp updated_at
            +timestamp deleted_at [NULL, IDX]
        }

        class COMMAND_LOG {
            +bigint id [PK]
            +uuid command_id [UQ, IDX]
            +string name [IDX]
            +text payload
            +text results [NULL]
            +timestamp handled_at [IDX]
            +string status
        }

        class SNAPSHOT {
            +bigint id [PK]
            +uuid command_id [FK, IDX]
            +string subject_type [IDX]
            +string subject_id [IDX]
            +int version [IDX]
            +text data
            +timestamp created_at [IDX]
        }

        class ROLE { %% Spatie Permission
            +bigint id [PK]
            +string name [UQ, IDX]
            +string guard_name [IDX]
        }

        class PERMISSION { %% Spatie Permission
            +bigint id [PK]
            +string name [UQ, IDX]
            +string guard_name [IDX]
        }

        class MEDIA { %% Spatie Media Library
            +bigint id [PK]
            +string model_type [IDX]
            +bigint model_id [IDX]
            +string uuid [UQ]
            +string collection_name [IDX]
            %% ... other Spatie Media attributes ...
        }

        class TAGS { %% Spatie Tags
            +bigint id [PK]
            +json name
            +json slug
            +string type [NULL, IDX]
        }

        class TAGGABLES { %% Spatie Tags Pivot
            +bigint tag_id [FK]
            +string taggable_type [IDX]
            +bigint taggable_id [IDX]
            +[PK: tag_id, taggable_type, taggable_id]
        }

        class COMMENTS { %% Spatie Comments
            +bigint id [PK]
            +string commentable_type [IDX]
            +bigint commentable_id [IDX]
            +string commenter_type [NULL, IDX]
            +bigint commenter_id [NULL, IDX]
            +text comment
            +boolean approved
            +bigint parent_id [FK, NULL] %% For threaded comments
        }

        class ACTIVITY_LOG { %% Spatie ActivityLog
            +bigint id [PK]
            +string log_name [NULL, IDX]
            +text description
            +string subject_type [NULL, IDX]
            +bigint subject_id [NULL, IDX]
            +string causer_type [NULL, IDX]
            +bigint causer_id [NULL, IDX]
            +json properties [NULL]
            +uuid batch_uuid [NULL, IDX]
            +string event [NULL, IDX]
        }

        USER "1" --o "*" POST : authors
        USER "1" --o "*" TODO : assignedTo
        USER "1" --o "*" MESSAGE : sends
        USER "1" --o "*" COMMENTS : commentsAsCommenter
        USER "1" --o "*" ACTIVITY_LOG : causesActivity
        USER "1" -- "*" MEDIA : hasAvatar (Polymorphic)
        USER "*" -- "*" ROLE : model_has_roles (Pivot)
        USER "*" -- "*" CONVERSATION : conversation_user (Pivot)

        TEAM "1" --o "*" TEAM : parentOf (Hierarchy)
        TEAM "1" --o "*" CATEGORY : hasCategories
        TEAM "1" --o "*" TODO : associatedWith (Optional)
        TEAM "1" -- "*" MEDIA : hasAvatar (Polymorphic)

        CATEGORY "1" --o "*" CATEGORY : parentOf (Hierarchy)
        CATEGORY "*" -- "1" TEAM : belongsTo

        POST "*" --o "1" USER : authoredBy
        POST "*" -- "*" CATEGORY : categorizables (Pivot)
        POST "*" -- "*" TAGS : taggables (Pivot)
        POST "*" -- "*" MEDIA : hasMedia (Polymorphic)
        POST "*" -- "*" COMMENTS : commentable (Polymorphic)

        TODO "*" --o "1" USER : assignedTo (Optional)
        TODO "*" --o "1" TEAM : associatedWith (Optional)
        TODO "1" --o "*" TODO : parentOf (Hierarchy)
        TODO "*" -- "*" CATEGORY : categorizables (Pivot)
        TODO "*" -- "*" TAGS : taggables (Pivot)
        TODO "*" -- "*" MEDIA : hasMedia (Polymorphic)
        TODO "*" -- "*" COMMENTS : commentable (Polymorphic)

        CONVERSATION "1" --o "*" MESSAGE : hasMessages
        CONVERSATION "*" -- "*" USER : conversation_user (Pivot)

        ROLE "*" -- "*" PERMISSION : role_has_permissions (Pivot)

        COMMAND_LOG "1" --o "*" SNAPSHOT : mayGenerate
    ~~~
    </details>

    <br>

    <details>
    <summary><strong>High-Level Entity Relationship Diagram (ERD - No Attributes) - Click to expand</strong></summary>
    This diagram shows only the main entities and their relationships to provide a quick overview of the data structure.

    ~~~mermaid
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

        %% Spatie generic relations implied:
        %% MEDIA is polymorphic to USER, TEAM, POST, TODO etc.
        %% COMMENTS is polymorphic to POST, TODO etc.
        %% TAGS is polymorphic to POST, TODO etc. via TAGGABLES
        %% ACTIVITY_LOG subject is polymorphic, causer is USER
    ~~~
    </details>

---

## <span style="color:#1E90FF;">8. Search Strategy (Typesense)</span>

*   Configure `laravel/scout` with `typesense` driver (`typesense/laravel-scout-driver`).
*   Implement `Laravel\Scout\Searchable` trait on relevant models (User, Team, Category, Todo, Post, Tag). Handle soft deletes exclusion via `shouldBeSearchable`.
*   Define `toSearchableArray()`: Include `status`, `team_id`, `is_public`, relevant FKs, translated fields, names, avatar URLs.
*   Use `shouldBeSearchable()` to control index inclusion (check `status`, `deleted_at`).
*   Rely on Horizon queues (`queue:work --queue=scout`) for indexing.
*   Implement search UI in Livewire and Filament.
*   **Search queries MUST construct Typesense `filter_by` clauses based on user permissions and context.**

---

## <span style="color:#1E90FF;">9. API Design (Optional - but Public API is now in Scope as 4.23)</span>

*   This section can be merged with/superseded by the details in **4.23 Public-Facing API**.
*   **<span style="color:#778899;">Status: Public API is now IN SCOPE for v2.0 as defined in Section 4.23.</span>**

---

## <span style="color:#1E90FF;">10. User Interface (UI/UX) Considerations</span>

*   **Frontend:** Livewire, Alpine.js, Tailwind CSS. Real-time via Reverb.
*   **Admin Panel:** Filament.
*   **Consistency:** Maintain consistent design language.
*   **Accessibility:** Strive for WCAG 2.1 AA.
*   **Responsiveness:** Ensure usability across devices.
*   **Visual Cues:** Utilize avatars (User, Team, Chat) and status indicators (labels/colors from Enums).
*   **Team Scoping in UI:** Filter relevant UI elements based on team context.
*   **Hierarchy Depth Feedback:** Provide validation feedback in forms, including for complex moves.
*   **Command History UI (Filament):** As defined in 4.20.6, provide contextual access to command history and snapshots with diffing capabilities.
*   **MFA Setup/Management UI:** Clear and secure interface for users to manage their MFA settings.
*   **Advanced Chat UI:** Incorporate UI elements for file attachments, read receipts (including list of viewers), typing indicators, message quoting/replying, and message editing/deletion.
*   **Reporting UI (Filament):** Clear presentation of reports with filters, visualizations, and export options.

---

## <span style="color:#1E90FF;">11. Package vs. Custom Build Analysis & Recommendations</span>

<details>
<summary>Click to expand package recommendations</summary>

| Feature | Recommendation | Confidence | Rationale |
|---------|---------------|------------|----------|
| **Blogging** | Custom Build (Spatie stack) | 90% | Provides flexibility for custom workflows while leveraging Spatie's robust packages |
| **Chat (Core)** | Custom Build (Livewire + Reverb) | 85% | Core chat functionality can be reliably built with these technologies |
| **Chat (Advanced)** | Custom Build (Livewire + Reverb) | 75% | Advanced features increase complexity and development risk |
| **Permissions** | `spatie/laravel-permission` | 100% | Industry standard, well-maintained package with excellent Laravel integration |
| **Admin Portal** | `filament/filament` | 95% | Mature admin panel framework with extensive plugin ecosystem |
| **User Tracking** | `wildside/userstamps` | 95% | Reliable package for tracking user actions |
| **Enhanced Enums** | Native PHP 8.4 Enums | 95% | Takes full advantage of PHP 8.4 features with custom methods following Filament standards |
| **CQRS & History** | `hirethunk/verbs` | 85% | Mandated package that provides solid CQRS implementation |
| **MFA** | Laravel Fortify's 2FA logic | 90% | Native Laravel solution with proven security track record |
| **Public API Auth** | Laravel Sanctum API Tokens | 90% | Official Laravel package designed specifically for API authentication |
| **API Documentation** | OpenAPI Specification (tool-generated) | 95% | Industry standard for API documentation with good tooling support |
| **Advanced Reporting** | Custom Filament Pages + Charting Libraries | 85% | Custom approach allows for tailored reporting solutions |

**Recommended Filament Plugins:**
* `filament/spatie-laravel-media-library-plugin`
* `filament/spatie-laravel-tags-plugin`
* `filament/spatie-laravel-translatable-plugin`
* `filament/spatie-laravel-activitylog-plugin`
* `bezhansalleh/filament-shield` - For role-based access control in the admin panel
* Consider additional plugins for charting or advanced table features
</details>

---

## <span style="color:#1E90FF;">12. Future Considerations / Roadmap</span>
*(Items not yet detailed for current scope, or next major iterations)*

*   **E-commerce Functionality:** Product catalogs, shopping cart, checkout, order management.
*   **Billing/Subscription Management:** Integration with payment gateways (`laravel/cashier`), plan management, recurring billing.
*   Full Event Sourcing (if more advanced audit/replay beyond `hirethunk/verbs` history is needed).
*   Mobile Application (would leverage the Public API).
*   Integration with other third-party services (e.g., CRM, accounting).
*   More sophisticated collaboration features (e.g., real-time document co-editing).
*   Advanced AI-driven analytics or content recommendations.

---

## <span style="color:#1E90FF;">13. Out of Scope (for v2.0)</span>
*(This section is now for items explicitly deferred beyond the current major feature set)*

*   **E-commerce Functionality** (as detailed in Future Considerations).
*   **Billing/Subscription Management** (as detailed in Future Considerations).
*   Any other feature not explicitly mentioned as "in scope" for v2.0.

---

## <span style="color:#1E90FF;">14. Assumptions</span>

### 14.1 Technical Assumptions
*   Infrastructure available (Laravel/Octane, Redis/Horizon, Reverb, DB, Typesense).
*   **PHP 8.4** environment. **Laravel 12** stable and ecosystem compatible.
*   Pragmatic CQRS with `hirethunk/verbs` meets needs.
*   Tailwind CSS for styling. Default hierarchy depths acceptable.
*   Chosen packages are maintained and compatible with Laravel 12 / PHP 8.4.

### 14.2 Project Assumptions
*   Business logic details will be clarified during sprint planning and execution.
*   Development team has proficiency with the tech stack (including new features like advanced chat, reporting UI, API development).
*   Stakeholders will be available for timely feedback and decision-making.
*   Sufficient resources (time, budget, personnel) allocated for the significantly expanded v2.0 scope.
*   Testing resources available for thorough validation of complex features.

### 14.3 Risk Mitigation
*   Regular technical spikes to validate complex features early.
*   Phased implementation approach for high-risk features.
*   Comprehensive automated testing strategy.
*   Regular stakeholder demos to ensure alignment.

---

## <span style="color:#1E90FF;">15. Glossary</span>

| Term | Definition |
|------|------------|
| **MFA (Multi-Factor Authentication)** | Security process requiring multiple verification methods beyond just a password |
| **TOTP (Time-based One-Time Password)** | Algorithm for generating temporary codes used in MFA, typically via authenticator apps |
| **CQRS (Command Query Responsibility Segregation)** | Architectural pattern separating read and write operations |
| **State Machine** | Design pattern that manages object lifecycle through predefined states and transitions |
| **Read Receipt (Chat)** | Indication that a message has been seen by recipients |
| **Typing Indicator (Chat)** | Visual cue that a user is composing a message |
| **API Token (Sanctum)** | Secure string for authenticating API requests |
| **OpenAPI (Swagger)** | Specification for designing, building, and documenting RESTful APIs |
| **Data Purging** | Permanent deletion of data according to policy or request |
| **Team Scoping** | Design principle where data access and operations are bounded by team membership |
| **Soft Delete** | Marking records as deleted without physically removing them from the database |
| **Userstamps** | Tracking which users created, updated, or deleted records |
| **Livewire** | Laravel package for building dynamic interfaces without writing JavaScript |
| **Filament** | Admin panel framework for Laravel applications |
| **Typesense** | Search engine optimized for developer experience and typo tolerance |
| **Laravel Reverb** | Laravel's WebSocket server for real-time features |
| **Laravel Horizon** | Dashboard and configuration system for Laravel Redis queues |
| **Laravel Octane** | Package for serving Laravel applications through high-powered application servers |

--- END OF REVISED FILE 010-000-ela-prd.md ---
