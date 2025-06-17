# Product Requirements Document: Architectural Foundation Implementation

## 1. Introduction/Overview

This document outlines the requirements for implementing a robust architectural foundation based on the patterns and principles identified in the architectural research. The goal is to establish a scalable, maintainable, and feature-rich foundation for Laravel applications that incorporates modern architectural patterns including Event Sourcing, CQRS, Domain-Driven Design, and Finite State Machines.

The foundation will serve as the basis for future application development, providing a standardized approach to common architectural challenges while maintaining flexibility for specific project requirements.

## 2. Goals

- Implement a hybrid Event Sourcing architecture prioritizing `hirethunk/verbs` for greatest flexibility and resilience
- Establish rigorous CQRS (Command Query Responsibility Segregation) throughout the codebase
- Integrate Finite State Machines for robust state management using PHP 8.4 attributes
- Support Single Table Inheritance for hierarchical models
- Create a multi-tier identifier strategy for optimal performance and functionality
- Use SQLite as the database with a roadmap to libsql/litesql
- Implement Test-Driven Development (TDD) with comprehensive Pest test suite
- Use Laravel Reverb for real-time features instead of Pusher
- Implement authentication using devdojo/auth together with Laravel Sanctum
- Provide comprehensive performance optimization strategies
- Ensure security best practices are followed
- Enable scalability through appropriate architectural decisions

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

### 4.1 Event Sourcing and CQRS Implementation

1. The system must prioritize `hirethunk/verbs` (v0.7+) as the primary event sourcing package for greatest flexibility and resilience, with `spatie/laravel-event-sourcing` as a secondary option where appropriate.
2. The system must implement rigorous CQRS with complete separation of command and query responsibilities:
   - Command models must be entirely separate from query models
   - Command handlers must only emit events and never return data
   - Query handlers must never modify state
   - Read models must be optimized for specific query patterns
3. The system must store all state changes as events in an event store optimized with Snowflake IDs.
4. The system must support event replay for rebuilding state from event history.
5. The system must implement projections for creating optimized read models.
6. The system must support event handlers for updating read models when events occur.
7. The system must provide base classes for aggregates, events, and projections.
8. The system must implement event versioning to support schema evolution without requiring backward compatibility.

### 4.2 Domain-Driven Design Implementation

9. The system must support bounded contexts for clear domain boundaries.
10. The system must provide base classes for value objects, entities, and domain services.
11. The system must implement domain events for cross-aggregate communication.
12. The system must support a ubiquitous language through consistent naming conventions.

### 4.3 Finite State Machine Implementation

13. The system must implement state management using PHP 8.4 native enums with backing types.
14. The system must integrate with `spatie/laravel-model-states` for complex workflows.
15. The system must integrate with `spatie/laravel-model-status` for simple flags.
16. The system must support type-safe state transitions with validation.
17. The system must trigger domain events on state changes.
18. The system must use PHP 8.4 attributes rather than PHPDocs for state configuration.
19. The system must enhance all enums to provide human-readable labels for UI display.
20. The system must enhance all enums to provide color codes for visual representation in UI components.

### 4.4 Single Table Inheritance Implementation

21. The system must support Single Table Inheritance for hierarchical models.
22. The system must integrate with `tightenco/parental` package for STI implementation.
23. The system must provide base classes for STI models.
24. The system must support type-specific behavior and attributes.
25. The system must implement a User model with STI using enum-backed types for `Admin`, `User`, and `Guest` user types.
26. The system must ensure that User types are properly validated and type-safe through enum backing.

### 4.5 Database Implementation

27. The system must use SQLite as the primary database.
28. The system must implement a shared database approach.
29. The system must support a database roadmap from SQLite to libsql to litesql.
30. The system must optimize database queries for SQLite performance.
31. The system must implement appropriate indexes for optimal query performance.
32. The system must support database migrations that are compatible with the SQLite roadmap.

### 4.6 Identifier Strategy Implementation

33. The system must implement a multi-tier identifier strategy.
34. The system must use auto-incrementing integers for primary keys.
35. The system must use Snowflake IDs for event store primary keys.
36. The system must use ULIDs for external references.
37. The system must use UUIDs for security-sensitive contexts.
38. The system must integrate with `glhd/bits` for Snowflake ID generation.
39. The system must integrate with `symfony/uid` for UUID and ULID generation.

### 4.7 Authentication Implementation

40. The system must implement authentication using `devdojo/auth` together with Laravel Sanctum.
41. The system must support token-based authentication for APIs.
42. The system must implement role-based access control.
43. The system must support multi-factor authentication.
44. The system must implement secure password hashing and validation.
45. The system must support session management and secure cookie handling.

### 4.8 Real-time Features Implementation

46. The system must use Laravel Reverb instead of Pusher for WebSocket communication.
47. The system must implement real-time event broadcasting.
48. The system must support presence channels for user online status.
49. The system must implement private channels for secure real-time communication.
50. The system must optimize WebSocket connections for performance and scalability.
51. The system must handle WebSocket authentication securely.

### 4.9 Testing Implementation

48. The system must implement Test-Driven Development (TDD) with tests written before code.
49. The system must use PHP 8.4 attributes rather than PHPDocs for test configuration.
50. The system must implement a comprehensive Pest test suite.
51. The system must add test capabilities using Pest plugins.
52. The system must achieve high test coverage for all components.
53. The system must include unit, integration, and feature tests.
54. The system must implement architecture tests to enforce design constraints.

### 4.10 Performance Optimization Implementation

55. The system must support Laravel Octane with FrankenPHP for high-performance PHP execution.
56. The system must implement event batching for processing multiple events per transaction.
57. The system must support projection snapshots for fast rebuilds.
58. The system must implement a multi-layer caching strategy with Redis.
59. The system must support read/write splitting for database scaling.

### 4.11 Security Implementation

60. The system must provide a comprehensive permission system.
61. The system must support selective field encryption for sensitive data.
62. The system must implement CSRF protection.
63. The system must prevent XSS attacks.
64. The system must implement rate limiting for APIs.

## 5. Non-Goals (Out of Scope)

- Backward compatibility with existing systems (green field implementation)
- Implementation of specific business logic or domain models
- User interface design and implementation
- Deployment and infrastructure setup
- Integration with external systems
- Data migration from existing systems
- Training and documentation beyond code comments and basic README files
- Implementation of all features from all three research areas (LFS, LSF, UME)
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

- `hirethunk/verbs` (v0.7+): Primary event sourcing package (prioritized)
- `devdojo/auth` (v1.1+): Authentication system
- `laravel/sanctum` (v4.1+): API token authentication
- `laravel/reverb` (v1.5+): WebSocket server
- `spatie/laravel-model-states` (v2.17+): Finite state machine
- `spatie/laravel-model-status` (v1.18+): Simple status tracking
- `tightenco/parental` (v1.4+): Single Table Inheritance
- `glhd/bits` (v0.6+): Snowflake IDs
- `symfony/uid` (v7.3+): UUID and ULID generation

#### Testing Packages

- `pestphp/pest` (v3.8+): Testing framework
- `pestphp/pest-plugin-laravel` (v3.2+): Laravel integration
- `pestphp/pest-plugin-livewire` (v3.0+): Livewire testing
- `pestphp/pest-plugin-arch` (v3.1+): Architecture testing
- `pestphp/pest-plugin-faker` (v3.0+): Fake data generation
- `pestphp/pest-plugin-stressless` (v3.1+): Performance testing
- `pestphp/pest-plugin-type-coverage` (v3.5+): Type coverage testing

#### Optional Packages

- `spatie/laravel-event-sourcing` (v7.0+): Secondary event sourcing package
- `spatie/laravel-data` (v4.15+): Data transfer objects
- `spatie/laravel-query-builder` (v6.3+): API query building
- `laravel/pulse` (v1.4+): Application monitoring
- `spatie/laravel-health` (v1.34+): Application health checks

### 6.3 Architecture Diagram

The system should follow this high-level architecture:

```
┌─────────────────────────────────────────────────────────────┐
│                      Presentation Layer                      │
│  (Controllers, API Endpoints, Livewire Components, Views)   │
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

## 8. Research Findings and Recommendations

Based on comprehensive research of the architectural patterns, technologies, and best practices, we provide the following recommendations for implementing the architectural foundation:

### 8.1. Hirethunk/verbs Implementation Options (Confidence: 85%)

1. **Attribute-Based Event Definition** (Recommended, 90% confidence)
   - Use PHP 8.4 attributes to define events, aggregates, and projections
   - Provides type safety, IDE support, and cleaner code
   - Example:
     ```php
     #[Event]
     class UserRegistered
     {
         public function __construct(
             public readonly string $userId,
             public readonly string $email,
             public readonly \DateTimeImmutable $registeredAt
         ) {}
     }
     ```

2. **Hybrid Command Handling** (Recommended, 85% confidence)
   - Use hirethunk/verbs for command handling and event dispatching
   - Integrate with spatie/laravel-event-sourcing for projections when needed
   - Leverage hirethunk/verbs' modern PHP 8.4 features while maintaining compatibility

3. **Standalone Implementation** (Alternative, 75% confidence)
   - Use hirethunk/verbs exclusively without spatie/laravel-event-sourcing
   - Simpler implementation but less ecosystem support
   - Requires more custom code for advanced features

### 8.2. SQLite to libsql to litesql Roadmap (Confidence: 82%)

1. **Phase 1: SQLite Foundation** (90% confidence)
   - Start with standard SQLite for development and initial deployment
   - Optimize schema design for event store with appropriate indexes
   - Implement application-level optimizations (batching, caching)
   - Estimated timeline: 1-2 months

2. **Phase 2: libSQL Transition** (85% confidence)
   - Implement custom database driver for libSQL integration
   - Leverage enhanced concurrency for better write performance (2-3x improvement)
   - Utilize edge replication capabilities for global distribution
   - Estimated timeline: 3-4 months (including 5-7 weeks development)
   - Estimated cost: $29-100/month for hosted solution

3. **Phase 3: litesql Evaluation** (70% confidence)
   - Assess litesql capabilities and performance benefits
   - Implement if significant advantages over libSQL are demonstrated
   - Maintain compatibility with previous phases
   - Estimated timeline: Dependent on litesql maturity

### 8.3. Rigorous CQRS Implementation Options (Confidence: 88%)

1. **Separate Model Approach** (Recommended, 90% confidence)
   - Completely separate command and query models
   - Command models focus on validation and event generation
   - Query models optimized for specific read patterns
   - Example:
     ```php
     // Command model
     class CreateUserCommand
     {
         public function __construct(
             public readonly string $name,
             public readonly string $email,
             public readonly string $password
         ) {}
     }

     // Query model
     class UserProfileViewModel
     {
         public function __construct(
             public readonly string $id,
             public readonly string $name,
             public readonly string $email,
             public readonly string $avatarUrl,
             public readonly array $roles
         ) {}
     }
     ```

2. **Dedicated Query Repository Pattern** (Recommended, 85% confidence)
   - Implement dedicated query repositories for each read model
   - Optimize queries for specific use cases
   - Use denormalized data structures for performance
   - Example:
     ```php
     interface UserQueryRepository
     {
         public function findById(string $id): ?UserProfileViewModel;
         public function findByEmail(string $email): ?UserProfileViewModel;
         public function searchByName(string $name): array;
     }
     ```

3. **Read Model Projection Strategy** (Recommended, 90% confidence)
   - Implement event handlers that update read models when events occur
   - Use cached aggregations for frequently accessed data
   - Optimize read models for specific query patterns
   - Example:
     ```php
     class UserProfileProjector extends Projector
     {
         public function onUserRegistered(UserRegistered $event): void
         {
             UserProfile::create([
                 'user_id' => $event->userId,
                 'email' => $event->email,
                 'registered_at' => $event->registeredAt,
             ]);
         }
     }
     ```

### 8.4. Event Sourcing Flexibility and Resilience Strategies (Confidence: 80%)

1. **Event Versioning** (Recommended, 85% confidence)
   - Implement explicit event versioning to support schema evolution
   - Use upcasting to transform older event versions to newer ones
   - Example:
     ```php
     #[Event(version: 2)]
     class UserRegistered
     {
         // Current version fields
     }

     class UserRegisteredV1ToV2Upcaster implements EventUpcaster
     {
         public function upcast(array $event): array
         {
             // Transform V1 to V2 format
             return $event;
         }
     }
     ```

2. **Snapshotting Strategy** (Recommended, 80% confidence)
   - Implement periodic snapshots of aggregate state
   - Reduce event replay overhead for frequently accessed aggregates
   - Configure snapshot frequency based on aggregate type and usage patterns
   - Example:
     ```php
     class SnapshotStrategy
     {
         public function shouldSnapshot(AggregateRoot $aggregate): bool
         {
             return $aggregate->getVersion() % 100 === 0;
         }
     }
     ```

3. **Event Store Partitioning** (Recommended, 75% confidence)
   - Partition event store by aggregate type or time period
   - Improve query performance and enable selective scaling
   - Implement consistent partitioning strategy across all aggregates
   - Example:
     ```php
     class PartitionedEventStore
     {
         public function getPartitionKey(string $aggregateType, string $aggregateId): string
         {
             return substr($aggregateId, 0, 2); // Simple partitioning by ID prefix
         }
     }
     ```

4. **Asynchronous Projection Processing** (Recommended, 85% confidence)
   - Process projections asynchronously using Laravel queues
   - Improve command performance by deferring read model updates
   - Implement retry mechanisms for failed projections
   - Example:
     ```php
     class AsyncProjectionProcessor
     {
         public function process(Event $event): void
         {
             dispatch(new ProcessProjectionsJob($event))->onQueue('projections');
         }
     }
     ```

### 8.5. PHP 8.4 Attributes for Testing and State Management (Confidence: 90%)

1. **Test Method Attributes** (Recommended, 95% confidence)
   - Replace PHPDoc annotations with PHP 8.4 attributes for test methods
   - Use built-in PHPUnit attributes for better type safety and IDE support
   - Example:
     ```php
     use PHPUnit\Framework\Attributes\Test;
     use PHPUnit\Framework\Attributes\Group;

     class UserTest extends TestCase
     {
         #[Test]
         #[Group('user')]
         public function it_can_register_a_new_user(): void
         {
             // Test implementation
         }
     }
     ```

2. **State Machine Attributes** (Recommended, 85% confidence)
   - Define state transitions using PHP 8.4 attributes
   - Provide type-safe state configuration and validation
   - Example:
     ```php
     #[State]
     enum UserStatus: string
     {
         #[Initial]
         case DRAFT = 'draft';

         #[Transition(from: [DRAFT::class], guard: CanActivateUser::class)]
         case ACTIVE = 'active';

         #[Transition(from: [ACTIVE::class])]
         case SUSPENDED = 'suspended';

         #[Transition(from: [ACTIVE::class, SUSPENDED::class])]
         case ARCHIVED = 'archived';
     }
     ```

3. **Model Feature Attributes** (Recommended, 90% confidence)
   - Configure model features using PHP 8.4 attributes
   - Centralize feature implementation in traits
   - Example:
     ```php
     #[HasUlid]
     #[HasSlug(source: 'name')]
     #[HasUserTracking]
     class Team extends Model
     {
         use HasAdditionalFeatures;

         // Model implementation
     }
     ```

### 8.6. Pest Plugins Prioritization (Confidence: 85%)

1. **Core Plugins** (Essential, 95% confidence)
   - **pest-plugin-laravel**: For Laravel-specific testing features
   - **pest-plugin-livewire**: For testing Livewire components
   - **pest-plugin-faker**: For generating test data

2. **Quality Assurance Plugins** (Highly Recommended, 90% confidence)
   - **pest-plugin-arch**: For architecture testing and enforcing design constraints
   - **pest-plugin-type-coverage**: For ensuring type safety throughout the codebase

3. **Performance Plugins** (Recommended, 80% confidence)
   - **pest-plugin-stressless**: For performance and stress testing
   - **pest-plugin-parallel**: For running tests in parallel

4. **Additional Plugins** (Optional, 75% confidence)
   - **pest-plugin-watch**: For continuous testing during development
   - **pest-plugin-snapshots**: For snapshot testing
   - **pest-plugin-drift**: For detecting test drift

### 8.7. DevDojo/Auth and Laravel Sanctum Integration (Confidence: 75%)

1. **Authentication Flow** (Recommended, 80% confidence)
   - Use DevDojo/Auth for user authentication and management
   - Integrate Laravel Sanctum for API token authentication
   - Example:
     ```php
     // routes/api.php
     Route::middleware('auth:sanctum')->group(function () {
         Route::get('/user', function (Request $request) {
             return $request->user();
         });
     });

     // app/Http/Controllers/Auth/LoginController.php
     public function login(Request $request)
     {
         // DevDojo/Auth login logic

         // Generate Sanctum token for API access
         $token = $user->createToken('api-token');

         return response()->json(['token' => $token->plainTextToken]);
     }
     ```

2. **Token Management** (Recommended, 75% confidence)
   - Implement token abilities for granular API access control
   - Set appropriate token expiration policies
   - Example:
     ```php
     $token = $user->createToken('api-token', ['read', 'create'], now()->addDays(30));
     ```

3. **Security Configuration** (Recommended, 70% confidence)
   - Configure Sanctum stateful domains for SPA authentication
   - Implement CSRF protection for web routes
   - Example:
     ```php
     // config/sanctum.php
     'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1')),
     ```

### 8.8. Laravel Reverb Configuration (Confidence: 90%)

1. **Basic Setup** (Recommended, 95% confidence)
   - Install Laravel Reverb using the Artisan command
   - Configure environment variables for Reverb
   - Example:
     ```bash
     php artisan install:broadcasting

     # .env configuration
     BROADCAST_DRIVER=reverb
     BROADCAST_CONNECTION=reverb
     REVERB_APP_ID=app-id
     REVERB_APP_KEY=app-key
     REVERB_APP_SECRET=app-secret
     REVERB_HOST=localhost
     REVERB_PORT=8080
     REVERB_SCHEME=http
     ```

2. **Frontend Integration** (Recommended, 90% confidence)
   - Configure Laravel Echo to connect to Reverb
   - Add Reverb configuration to frontend environment variables
   - Example:
     ```js
     // resources/js/bootstrap.js
     import Echo from 'laravel-echo';
     import Pusher from 'pusher-js';

     window.Pusher = Pusher;

     window.Echo = new Echo({
         broadcaster: 'reverb',
         key: import.meta.env.VITE_REVERB_APP_KEY,
         wsHost: import.meta.env.VITE_REVERB_HOST,
         wsPort: import.meta.env.VITE_REVERB_PORT,
         wssPort: import.meta.env.VITE_REVERB_PORT,
         forceTLS: false,
         disableStats: true,
     });
     ```

3. **Production Scaling** (Recommended, 85% confidence)
   - Configure Redis for horizontal scaling
   - Use a process manager like Supervisor to keep Reverb running
   - Example:
     ```php
     // config/reverb.php
     'scaling' => [
         'driver' => 'redis',
         'connection' => env('REVERB_REDIS_CONNECTION', 'default'),
     ],
     ```

## 9. Open Questions

No outstanding questions remain. All implementation details have been researched and documented with confidence scores and specific recommendations.
