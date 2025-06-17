# 1. Tasks for Architectural Foundation Implementation

## 2. Relevant Files

### 2.1 For PHP/Laravel Projects:
- `composer.json` - Contains all package dependencies for the project.
- `config/app.php` - Service provider registration and application configuration.
- `bootstrap/providers.php` - Service provider bootstrapping for Laravel 12.x.
- `app/Providers/` - Directory for custom service providers.
- `config/filament.php` - Filament admin panel configuration.

#### 2.1.1 User Model and Related Files:
- `app/Models/User.php` - Base User model for STI implementation.
- `app/Models/AdminUser.php` - Admin user type implementation.
- `app/Models/RegularUser.php` - Regular user type implementation.
- `app/Models/GuestUser.php` - Guest user type implementation.
- `app/Traits/TenantAware.php` - Trait for tenant-aware models.
- `app/Enums/UserType.php` - Enum for user types.
- `tests/Unit/Models/UserTest.php` - Unit tests for User model.
- `tests/Feature/Auth/AuthenticationTest.php` - Feature tests for authentication.

#### 2.1.2 Organisation Model and Related Files:
- `app/Models/Organisation.php` - Base Organisation model for STI implementation.
- `app/Models/TenantOrganisation.php` - Tenant organization type implementation.
- `app/Models/DivisionOrganisation.php` - Division organization type implementation.
- `app/Models/DepartmentOrganisation.php` - Department organization type implementation.
- `app/Models/TeamOrganisation.php` - Team organization type implementation.
- `app/Models/ProjectOrganisation.php` - Project organization type implementation.
- `app/Models/OtherOrganisation.php` - Other organization type implementation.
- `tests/Unit/Models/OrganisationTest.php` - Unit tests for Organisation model.
- `tests/Feature/Models/OrganisationHierarchyTest.php` - Feature tests for organization hierarchy.

#### 2.1.3 DDD Architecture Files:
- `app/Domain/` - Directory for domain layer components.
- `app/Application/` - Directory for application layer components.
- `app/Infrastructure/` - Directory for infrastructure layer components.
- `app/Interfaces/` - Directory for interface layer components.

#### 2.1.4 Event Sourcing and CQRS Files:
- `app/Domain/Shared/Aggregate.php` - Base aggregate class.
- `app/Domain/Shared/DomainEvent.php` - Base domain event class.
- `app/Domain/Shared/Projection.php` - Base projection class.
- `app/Application/Shared/Command.php` - Base command class.
- `app/Application/Shared/CommandHandler.php` - Base command handler class.
- `app/Application/Shared/Query.php` - Base query class.
- `app/Application/Shared/QueryHandler.php` - Base query handler class.
- `app/Infrastructure/EventStore/EventStore.php` - Event store implementation.
- `app/Infrastructure/EventStore/Replay.php` - Event replay functionality.

#### 2.1.5 Identifier Strategy Files:
- `app/Infrastructure/Identifiers/IdentifierGenerator.php` - Identifier generation service.
- `app/Infrastructure/Identifiers/SnowflakeIdGenerator.php` - Snowflake ID generator.
- `app/Infrastructure/Identifiers/UlidGenerator.php` - ULID generator.
- `app/Infrastructure/Identifiers/UuidGenerator.php` - UUID generator.

## 3. Notes

### 3.1 For PHP/Laravel Projects:
- Unit tests should be placed in the `tests/Unit` directory, mirroring the structure of the `app` directory.
- Feature tests should be placed in the `tests/Feature` directory.
- Use `php artisan test` or `./vendor/bin/pest` to run tests. Add `--filter=TestClassName` to run specific tests.
- For Pest tests, use `./vendor/bin/pest --coverage` to generate coverage reports.

## 4. Tasks

- [✅] 4.1 Composer Package Installation and Configuration
  - [✅] 4.1.1 Install core event sourcing packages (`hirethunk/verbs`, `spatie/laravel-event-sourcing`)
  - [✅] 4.1.2 Install state management packages (`spatie/laravel-model-states`, `spatie/laravel-model-status`)
  - [✅] 4.1.3 Install Single Table Inheritance package (`tightenco/parental`)
  - [✅] 4.1.4 Install ID generation packages (`glhd/bits`, `symfony/uid`)
  - [✅] 4.1.5 Install data transfer and query building packages (`spatie/laravel-data`, `spatie/laravel-query-builder`)
  - [✅] 4.1.6 Install hierarchical data structure package (`staudenmeir/laravel-adjacency-list`)
  - [✅] 4.1.7 Configure service providers for all installed packages
  - [✅] 4.1.8 Set up middleware for all installed packages
  - [✅] 4.1.9 Write tests for package integrations
  - [✅] 4.1.10 Ensure compatibility with PHP 8.4+ and Laravel 12.x

- [✅] 4.2 Filament Packages/Plugins Implementation
  - [✅] 4.2.1 Install Filament core package (`filament/filament`)
  - [✅] 4.2.2 Install Spatie integration plugins (media-library, settings, tags, translatable)
  - [✅] 4.2.3 Install content management plugins (tiptap-editor, curator)
  - [✅] 4.2.4 Install security and monitoring plugins (shield, pulse, schedule-monitor)
  - [✅] 4.2.5 Install backup and health plugins (laravel-backup, laravel-health)
  - [✅] 4.2.6 Install utility plugins (activitylog, adjacency-list, spotlight, fabricator)
  - [✅] 4.2.7 Implement consistent theme and UI across all Filament components
  - [✅] 4.2.8 Configure permissions and roles for Filament admin access
  - [✅] 4.2.9 Integrate Filament plugins with domain models
  - [✅] 4.2.10 Create custom Filament resources for major domain entities
  - [✅] 4.2.11 Write tests for Filament functionality

- [ ] 4.3 STI User Model Implementation
  - [ ] 4.3.1 Create base User model with STI configuration
  - [ ] 4.3.2 Implement AdminUser type with global access
  - [ ] 4.3.3 Implement RegularUser type with tenant-specific access
  - [ ] 4.3.4 Implement GuestUser type with limited access
  - [ ] 4.3.5 Create tenant awareness trait for user types
  - [ ] 4.3.6 Implement type-safety using PHP 8.4 enums with backing types
  - [ ] 4.3.7 Integrate User model with Laravel's authentication system
  - [ ] 4.3.8 Implement authorization policies for each User type
  - [ ] 4.3.9 Create validation rules for each User type
  - [ ] 4.3.10 Write unit tests for User model and subtypes
  - [ ] 4.3.11 Write feature tests for User authentication and authorization

- [ ] 4.4 STI Self-Hierarchical Organisation Model
  - [ ] 4.4.1 Create base Organisation model with STI configuration
  - [ ] 4.4.2 Implement TenantOrganisation type (root-level organization)
  - [ ] 4.4.3 Implement DivisionOrganisation type (major organizational unit)
  - [ ] 4.4.4 Implement DepartmentOrganisation type (organizational unit within Division)
  - [ ] 4.4.5 Implement TeamOrganisation type (group within Department)
  - [ ] 4.4.6 Implement ProjectOrganisation type (temporary organizational unit)
  - [ ] 4.4.7 Implement OtherOrganisation type (miscellaneous organizational units)
  - [ ] 4.4.8 Configure self-referential hierarchy using adjacency lists
  - [ ] 4.4.9 Integrate with `staudenmeir/laravel-adjacency-list` for hierarchical queries
  - [ ] 4.4.10 Implement validation to ensure TenantOrganisation entities are root-level
  - [ ] 4.4.11 Create tenant awareness trait for models
  - [ ] 4.4.12 Implement cascading permissions through organizational hierarchy
  - [ ] 4.4.13 Write unit tests for Organisation model and subtypes
  - [ ] 4.4.14 Write feature tests for organizational hierarchy

- [ ] 4.5 Directory Structure and DDD Implementation
  - [ ] 4.5.1 Create directory structure for Domain-Driven Design
  - [ ] 4.5.2 Set up `app/Domain/[BoundedContext]/` directories for domain layer components
  - [ ] 4.5.3 Set up `app/Application/[BoundedContext]/` directories for application layer
  - [ ] 4.5.4 Set up `app/Infrastructure/` directory for infrastructure layer
  - [ ] 4.5.5 Set up `app/Interfaces/` directory for interface layer
  - [ ] 4.5.6 Implement naming conventions for DDD components
  - [ ] 4.5.7 Create base classes for aggregates, entities, and value objects
  - [ ] 4.5.8 Create base classes for commands and queries
  - [ ] 4.5.9 Create base classes for domain events
  - [ ] 4.5.10 Write tests for base DDD components

- [ ] 4.6 Event Sourcing and CQRS Implementation
  - [ ] 4.6.1 Configure `hirethunk/verbs` as primary event sourcing package
  - [ ] 4.6.2 Implement command models separate from query models
  - [ ] 4.6.3 Create command handlers that emit events
  - [ ] 4.6.4 Create query handlers that never modify state
  - [ ] 4.6.5 Implement event store with Snowflake IDs
  - [ ] 4.6.6 Create event replay functionality
  - [ ] 4.6.7 Implement projections for optimized read models
  - [ ] 4.6.8 Create event handlers for updating read models
  - [ ] 4.6.9 Implement base classes for aggregates, events, and projections
  - [ ] 4.6.10 Create event versioning system
  - [ ] 4.6.11 Write tests for event sourcing components
  - [ ] 4.6.12 Write tests for CQRS components

- [ ] 4.7 Identifier Strategy Implementation
  - [ ] 4.7.1 Configure auto-incrementing integers for primary keys
  - [ ] 4.7.2 Implement Snowflake IDs for event store primary keys
  - [ ] 4.7.3 Implement ULIDs for external references
  - [ ] 4.7.4 Implement UUIDs for security-sensitive contexts
  - [ ] 4.7.5 Integrate with `glhd/bits` for Snowflake ID generation
  - [ ] 4.7.6 Integrate with `symfony/uid` for UUID and ULID generation
  - [ ] 4.7.7 Create identifier generation services and interfaces
  - [ ] 4.7.8 Write tests for identifier generation and usage

- [ ] 4.8 Pest Architectural Tests
  - [ ] 4.8.1 Design architectural test strategy using Pest and pest-plugin-arch
  - [ ] 4.8.2 Implement layer dependency tests to enforce architectural boundaries
  - [ ] 4.8.3 Create tests to verify proper namespace usage
  - [ ] 4.8.4 Implement tests for DDD pattern compliance
  - [ ] 4.8.5 Create tests for CQRS pattern validation
  - [ ] 4.8.6 Implement tests for event sourcing architectural rules
  - [ ] 4.8.7 Create tests to verify proper model inheritance
  - [ ] 4.8.8 Implement tests for tenant awareness implementation
  - [ ] 4.8.9 Create tests for proper identifier usage across the application
  - [ ] 4.8.10 Set up CI/CD pipeline for running architectural tests
