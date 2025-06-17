## Relevant Files

### For PHP/Laravel Projects:
- `app/Foundation/EventSourcing` - Directory for event sourcing implementation including aggregates, events, and projections.
- `app/Foundation/Domain` - Directory for domain-driven design components including value objects and entities.
- `app/Foundation/StateMachine` - Directory for finite state machine implementation.
- `app/Foundation/Models` - Directory for base model classes including STI implementation.
- `app/Foundation/Identifiers` - Directory for identifier strategy implementation.
- `app/Foundation/Database` - Directory for database configuration and optimization.
- `app/Foundation/Auth` - Directory for authentication implementation.
- `app/Foundation/Realtime` - Directory for real-time features implementation.
- `config/event-sourcing.php` - Configuration file for event sourcing.
- `config/state-machine.php` - Configuration file for state machine.
- `config/identifiers.php` - Configuration file for identifier strategy.
- `config/reverb.php` - Configuration file for Laravel Reverb.
- `database/migrations` - Directory for database migrations.
- `tests/Unit/Foundation` - Directory for unit tests.
- `tests/Feature/Foundation` - Directory for feature tests.
- `tests/Arch` - Directory for architecture tests.

### Notes

#### For PHP/Laravel Projects:
- Unit tests should be placed in the `tests/Unit` directory, mirroring the structure of the `app` directory.
- Feature tests should be placed in the `tests/Feature` directory.
- Use `php artisan test` or `./vendor/bin/pest` to run tests. Add `--filter=TestClassName` to run specific tests.
- For Pest tests, use `./vendor/bin/pest --coverage` to generate coverage reports.
- Follow TDD principles by writing tests before implementing features.
- Use PHP 8.4 attributes instead of PHPDoc annotations for test configuration.

## Package Installation and Configuration

This section lists all packages that will be installed during each task, and indicates if configuration completion of package setup is during another task.

### Task 2.0: Event Sourcing and CQRS Architecture
- `hirethunk/verbs` (v0.7+) - Installed in Task 2.1, configured in Tasks 2.1-2.8
- `spatie/laravel-event-sourcing` (v7.0+) - Installed in Task 2.9, configured in Task 2.9

### Task 4.0: State Management and Single Table Inheritance
- `spatie/laravel-model-states` (v2.11+) - Installed in Task 4.2, configured in Tasks 4.2, 4.4, 4.5
- `spatie/laravel-model-status` (v1.18+) - Installed in Task 4.3, configured in Task 4.3
- `tightenco/parental` (v1.4+) - Installed in Task 4.6, configured in Tasks 4.6, 4.7

### Task 5.0: Database and Identifier Strategy
- `glhd/bits` (v0.6+) - Installed in Task 5.5, configured in Task 5.5
- `symfony/uid` (v7.3+) - Installed in Task 5.6, configured in Task 5.6

### Task 6.0: Authentication and Real-time Features
- `devdojo/auth` (v1.1+) - Installed in Task 6.1, configured in Tasks 6.1, 6.3, 6.4
- `laravel/sanctum` (v4.1+) - Installed in Task 6.2, configured in Task 6.2
- `laravel/reverb` (v1.5+) - Installed in Task 6.5, configured in Tasks 6.5-6.9

### Task 7.0: Testing Framework and Security Features
- `pestphp/pest` (v3.8+) - Installed in Task 7.1, configured in Task 7.1
- `pestphp/pest-plugin-laravel` (v3.2+) - Installed in Task 7.1, configured in Task 7.1
- `pestphp/pest-plugin-livewire` (v3.0+) - Installed in Task 7.1, configured in Task 7.1
- `pestphp/pest-plugin-arch` (v3.1+) - Installed in Task 7.3, configured in Task 7.3
- `pestphp/pest-plugin-faker` (v3.0+) - Installed in Task 7.1, configured in Task 7.1
- `pestphp/pest-plugin-stressless` (v3.1+) - Installed in Task 7.1, configured in Task 7.1
- `pestphp/pest-plugin-type-coverage` (v3.5+) - Installed in Task 7.1, configured in Task 7.1

## Tasks

- [✅] 1.0 Set up Foundation and Core Infrastructure
  - [✅] 1.1 Create Foundation directory structure and namespace organization
  - [✅] 1.2 Set up service provider for Foundation components
  - [✅] 1.3 Create configuration files for Foundation components
  - [✅] 1.4 Implement dependency injection container configuration
  - [✅] 1.5 Set up exception handling and logging infrastructure
  - [✅] 1.6 Create base interfaces and abstract classes for Foundation components
  - [✅] 1.7 Implement service discovery and auto-registration mechanisms

- [✅] 2.0 Implement Event Sourcing and CQRS Architecture
  - [✅] 2.1 Implement Event Store with Snowflake IDs using hirethunk/verbs
  - [✅] 2.2 Create base classes for aggregates, events, and projections
  - [✅] 2.3 Implement command bus with strict separation from queries
  - [✅] 2.4 Implement query bus with optimized read models
  - [✅] 2.5 Create event dispatching and handling infrastructure
  - [✅] 2.6 Implement event versioning and upcasting mechanism
  - [✅] 2.7 Create projection rebuilding and snapshot functionality
  - [✅] 2.8 Implement event replay mechanism for debugging and testing
  - [✅] 2.9 Set up integration with spatie/laravel-event-sourcing as secondary option

- [✅] 3.0 Implement Domain-Driven Design Components
  - [✅] 3.1 Create base classes for value objects with immutability
  - [✅] 3.2 Implement entity base classes with identity management
  - [✅] 3.3 Create domain service interfaces and base implementations
  - [✅] 3.4 Implement repository pattern for domain persistence
  - [✅] 3.5 Create factory pattern for domain object creation
  - [✅] 3.6 Implement specification pattern for business rules
  - [✅] 3.7 Set up bounded context infrastructure and cross-context communication

- [✅] 4.0 Implement State Management and Single Table Inheritance
  - [✅] 4.1 Create base enum classes for state representation using PHP 8.4 enums
  - [✅] 4.2 Implement integration with spatie/laravel-model-states
  - [✅] 4.3 Implement integration with spatie/laravel-model-status
  - [✅] 4.4 Create state transition validation and guards
  - [✅] 4.5 Implement event dispatching on state transitions
  - [✅] 4.6 Enhance enums with human-readable labels for UI display
  - [✅] 4.7 Enhance enums with color codes for visual representation
  - [✅] 4.8 Create base classes for Single Table Inheritance using tightenco/parental
  - [✅] 4.9 Implement type-specific behavior and attributes for STI models
  - [✅] 4.10 Create User model with STI using enum-backed types (Admin, User, Guest)
  - [✅] 4.11 Create migration helpers for STI models

- [✅] 5.0 Implement Database and Identifier Strategy
  - [✅] 5.1 Configure SQLite as primary database with optimization
  - [✅] 5.2 Implement database migration strategy compatible with SQLite roadmap
  - [✅] 5.3 Create database schema for event store and projections
  - [✅] 5.4 Implement multi-tier identifier strategy with auto-increment primary keys
  - [✅] 5.5 Integrate glhd/bits for Snowflake ID generation
  - [✅] 5.6 Integrate symfony/uid for UUID and ULID generation
  - [✅] 5.7 Create identifier factory for consistent ID generation
  - [✅] 5.8 Implement database query optimization for SQLite

- [ ] 6.0 Implement Authentication and Real-time Features
  - [ ] 6.1 Integrate devdojo/auth for authentication system
  - [ ] 6.2 Implement Laravel Sanctum for API token authentication
  - [ ] 6.3 Create role-based access control infrastructure
  - [ ] 6.4 Implement multi-factor authentication support
  - [ ] 6.5 Set up Laravel Reverb for WebSocket communication
  - [ ] 6.6 Implement real-time event broadcasting
  - [ ] 6.7 Create presence channel infrastructure
  - [ ] 6.8 Implement private channel authentication
  - [ ] 6.9 Create WebSocket connection optimization

- [ ] 7.0 Implement Testing Framework and Security Features
  - [ ] 7.1 Set up Pest testing framework with PHP 8.4 attributes
  - [ ] 7.2 Implement test helpers for event sourcing and CQRS
  - [ ] 7.3 Create architecture testing infrastructure with pest-plugin-arch
  - [ ] 7.4 Implement comprehensive test suite for Foundation components
  - [ ] 7.5 Create security middleware for common vulnerabilities
  - [ ] 7.6 Implement field encryption for sensitive data
  - [ ] 7.7 Set up CSRF protection and XSS prevention
  - [ ] 7.8 Implement API rate limiting
  - [ ] 7.9 Create security testing infrastructure
