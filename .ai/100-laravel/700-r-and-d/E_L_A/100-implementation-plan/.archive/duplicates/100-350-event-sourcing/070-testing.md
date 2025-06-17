# Phase 1: Event Sourcing Testing

**Version:** 1.1.0
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Complete
**Progress:** 100%

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Testing Approach](#testing-approach)
  - [Testing Layers](#testing-layers)
  - [Testing Strategies](#testing-strategies)
  - [Test Types](#test-types)
- [Testing Aggregates](#testing-aggregates)
  - [Aggregate Test Structure](#aggregate-test-structure)
  - [Testing Command Methods](#testing-command-methods)
  - [Testing Business Rules](#testing-business-rules)
  - [Testing Event Application](#testing-event-application)
- [Testing Projectors](#testing-projectors)
  - [Projector Test Structure](#projector-test-structure)
  - [Testing Event Handlers](#testing-event-handlers)
  - [Testing Read Models](#testing-read-models)
- [Testing Reactors](#testing-reactors)
  - [Reactor Test Structure](#reactor-test-structure)
  - [Testing Side Effects](#testing-side-effects)
  - [Mocking External Services](#mocking-external-services)
- [Testing Queries](#testing-queries)
  - [Query Test Structure](#query-test-structure)
  - [Testing Query Handlers](#testing-query-handlers)
  - [Testing Query Validation](#testing-query-validation)
- [Integration Testing](#integration-testing)
  - [Command to Query Flow](#command-to-query-flow)
  - [End-to-End Testing](#end-to-end-testing)
  - [Testing Event Replay](#testing-event-replay)
- [Test Examples](#test-examples)
  - [User Aggregate Tests](#user-aggregate-tests)
  - [Team Projector Tests](#team-projector-tests)
  - [Post Reactor Tests](#post-reactor-tests)
  - [Todo Query Tests](#todo-query-tests)
- [Common Patterns and Best Practices](#common-patterns-and-best-practices)
  - [Test Data Generation](#test-data-generation)
  - [Test Isolation](#test-isolation)
  - [Test Performance](#test-performance)
  - [Test Coverage](#test-coverage)
- [Troubleshooting](#troubleshooting)
  - [Common Issues](#common-issues)
  - [Solutions](#solutions)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides a comprehensive guide to testing event sourcing components in the Enhanced Laravel Application (ELA). Testing is a critical aspect of event sourcing implementations, ensuring that aggregates, projectors, reactors, and queries behave as expected. This document covers testing strategies, approaches, and best practices for each component of the event sourcing architecture.

## Prerequisites

- **Required Prior Steps:**
  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [Event Sourcing Projectors](030-projectors.md)
  - [Event Sourcing Reactors](040-reactors.md)
  - [Event Sourcing Queries](060-queries.md)
  - [Package Installation](../030-core-components/010-package-installation.md)

- **Required Packages:**
  - `spatie/laravel-event-sourcing`: ^7.0
  - `hirethunk/verbs`: ^1.0
  - `phpunit/phpunit`: ^10.0
  - `mockery/mockery`: ^1.5

- **Required Knowledge:**
  - Understanding of event sourcing principles
  - Familiarity with PHPUnit testing framework
  - Understanding of testing patterns and best practices

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher
  - SQLite for testing

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Setting up testing environment | 2 hours |
| Writing aggregate tests | 2 hours per aggregate |
| Writing projector tests | 2 hours per projector |
| Writing reactor tests | 2 hours per reactor |
| Writing query tests | 1 hour per query |
| Writing integration tests | 4 hours |
| **Total** | **7+ hours per aggregate** |
</details>

## Testing Approach

<details>
<summary>Testing Layers Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
pyramid-schema
    Testing Pyramid
    Unit Tests: 70
    Integration Tests: 20
    End-to-End Tests: 10
```text

For dark mode, see [Testing Layers (Dark Mode)](../../illustrations/mermaid/dark/testing-layers-dark.mmd)
</details>

### Testing Layers

Testing event sourcing components involves multiple layers:

1. **Unit Tests**: Test individual components in isolation
   - Aggregate command methods
   - Projector event handlers
   - Reactor event handlers
   - Query handlers

2. **Integration Tests**: Test interactions between components
   - Command to event flow
   - Event to projection flow
   - Event to reaction flow
   - Command to query flow

3. **End-to-End Tests**: Test complete flows from command to query
   - User registration to user retrieval
   - Team creation to team listing
   - Post publication to post search

### Testing Strategies

Several testing strategies are employed:

1. **Behavior-Driven Testing**: Focus on the behavior of components
2. **State-Based Testing**: Verify the state of aggregates and read models
3. **Interaction Testing**: Verify interactions between components
4. **Snapshot Testing**: Compare current state with expected snapshots

### Test Types

Different types of tests are used for different components:

1. **Aggregate Tests**: Test aggregate behavior and state transitions
2. **Projector Tests**: Test read model creation and updates
3. **Reactor Tests**: Test side effects triggered by events
4. **Query Tests**: Test query handling and result formatting
5. **Integration Tests**: Test complete flows from command to query

## Testing Aggregates

<details>
<summary>Test Structure Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
classDiagram
    class TestCase {
        +setUp()
        +tearDown()
    }

    class AggregateTest {
        +it_can_execute_command()
        +it_cannot_execute_invalid_command()
        +it_applies_event_correctly()
    }

    class ProjectorTest {
        +it_creates_read_model_from_event()
        +it_updates_read_model_from_event()
        +it_deletes_read_model_from_event()
    }

    class ReactorTest {
        +it_triggers_side_effect_from_event()
        +it_handles_external_service_failure()
    }

    class QueryTest {
        +it_returns_correct_data()
        +it_validates_input_correctly()
        +it_handles_empty_results()
    }

    TestCase <|-- AggregateTest
    TestCase <|-- ProjectorTest
    TestCase <|-- ReactorTest
    TestCase <|-- QueryTest
```php
For dark mode, see [Test Structure (Dark Mode)](../../illustrations/mermaid/dark/test-structure-dark.mmd)
</details>

### Aggregate Test Structure

Aggregate tests follow a standard structure:

```php
<?php

namespace Tests\Unit\Aggregates;

use Tests\TestCase;
use App\Aggregates\UserAggregateRoot;
use App\Events\Users\UserRegistered;
use App\Events\Users\UserActivated;
use App\States\User\PendingActivation;
use App\States\User\Active;
use App\Exceptions\Users\InvalidUserStateTransitionException;

class UserAggregateTest extends TestCase
{
    /** @test */
    public function it_can_register_a_user()
    {
        // Arrange
        $aggregate = UserAggregateRoot::retrieve('user-id');

        // Act
        $aggregate->registerUser('John Doe', 'john@example.com', ['bio' => 'Test bio']);

        // Assert
        $events = $aggregate->getRecordedEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRegistered::class, $events[0]);
        $this->assertEquals('John Doe', $events[0]->payload['name']);
        $this->assertEquals('john@example.com', $events[0]->payload['email']);
        $this->assertEquals(['bio' => 'Test bio'], $events[0]->payload['profile']);
    }

    /** @test */
    public function it_can_activate_a_user()
    {
        // Arrange
        $aggregate = UserAggregateRoot::retrieve('user-id')
            ->registerUser('John Doe', 'john@example.com');

        // Clear recorded events
        $aggregate->clearRecordedEvents();

        // Act
        $aggregate->activateUser();

        // Assert
        $events = $aggregate->getRecordedEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserActivated::class, $events[0]);
    }

    /** @test */
    public function it_cannot_activate_an_already_active_user()
    {
        // Arrange
        $aggregate = UserAggregateRoot::retrieve('user-id')
            ->registerUser('John Doe', 'john@example.com')
            ->activateUser();

        // Clear recorded events
        $aggregate->clearRecordedEvents();

        // Act & Assert
        $this->expectException(InvalidUserStateTransitionException::class);
        $aggregate->activateUser();
    }
}
```text

### Testing Command Methods

Command methods are tested by:

1. Setting up the aggregate in the desired state
2. Executing the command method
3. Asserting that the expected events are recorded
4. Asserting that the aggregate state is updated correctly

```php
/** @test */
public function it_can_update_user_profile()
{
    // Arrange
    $aggregate = UserAggregateRoot::retrieve('user-id')
        ->registerUser('John Doe', 'john@example.com')
        ->activateUser();

    // Clear recorded events
    $aggregate->clearRecordedEvents();

    // Act
    $aggregate->updateUserProfile('John Smith', ['bio' => 'Updated bio']);

    // Assert
    $events = $aggregate->getRecordedEvents();
    $this->assertCount(1, $events);
    $this->assertInstanceOf(UserProfileUpdated::class, $events[0]);
    $this->assertEquals('John Smith', $events[0]->payload['name']);
    $this->assertEquals(['bio' => 'Updated bio'], $events[0]->payload['profile']);
}
```php
### Testing Business Rules

Business rules are tested by:

1. Setting up the aggregate in a state that violates a business rule
2. Executing a command method that should fail
3. Asserting that the expected exception is thrown

```php
/** @test */
public function it_cannot_update_profile_of_deactivated_user()
{
    // Arrange
    $aggregate = UserAggregateRoot::retrieve('user-id')
        ->registerUser('John Doe', 'john@example.com')
        ->activateUser()
        ->deactivateUser('No longer active');

    // Act & Assert
    $this->expectException(InvalidUserStateTransitionException::class);
    $aggregate->updateUserProfile('John Smith', ['bio' => 'Updated bio']);
}
```text

### Testing Event Application

Event application is tested by:

1. Creating an aggregate instance
2. Applying events to the aggregate
3. Asserting that the aggregate state is updated correctly

```php
/** @test */
public function it_applies_user_registered_event()
{
    // Arrange
    $event = new UserRegistered([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'profile' => ['bio' => 'Test bio'],
        'registered_at' => now(),
    ]);

    // Act
    $aggregate = UserAggregateRoot::retrieve('user-id');
    $aggregate->apply($event);

    // Assert
    $this->assertEquals('John Doe', $aggregate->name);
    $this->assertEquals('john@example.com', $aggregate->email);
    $this->assertEquals(['bio' => 'Test bio'], $aggregate->profile);
    $this->assertEquals(PendingActivation::class, $aggregate->state);
}
```php
## Testing Projectors

### Projector Test Structure

Projector tests follow a standard structure:

```php
<?php

namespace Tests\Unit\Projectors;

use Tests\TestCase;
use App\Projectors\UserProjector;
use App\Events\Users\UserRegistered;
use App\Events\Users\UserActivated;
use App\Models\User;
use App\States\User\PendingActivation;
use App\States\User\Active;
use Illuminate\Support\Str;

class UserProjectorTest extends TestCase
{
    protected UserProjector $projector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projector = new UserProjector();
    }

    /** @test */
    public function it_creates_a_user_when_user_registered_event_is_handled()
    {
        // Arrange
        $uuid = (string) Str::uuid();
        $event = new UserRegistered([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'profile' => ['bio' => 'Test bio'],
            'registered_at' => now(),
        ]);

        // Act
        $this->projector->onUserRegistered($event, $uuid);

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $uuid,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'state' => PendingActivation::class,
        ]);

        $user = User::find($uuid);
        $this->assertEquals(['bio' => 'Test bio'], $user->profile);
    }

    /** @test */
    public function it_updates_user_state_when_user_activated_event_is_handled()
    {
        // Arrange
        $uuid = (string) Str::uuid();
        $user = User::create([
            'id' => $uuid,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'profile' => ['bio' => 'Test bio'],
            'state' => PendingActivation::class,
        ]);

        $event = new UserActivated([
            'activated_at' => now(),
        ]);

        // Act
        $this->projector->onUserActivated($event, $uuid);

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $uuid,
            'state' => Active::class,
        ]);

        $user->refresh();
        $this->assertTrue($user->state->equals(new Active()));
    }
}
```text

### Testing Event Handlers

Event handlers are tested by:

1. Creating an event instance
2. Executing the event handler method
3. Asserting that the read model is updated correctly

```php
/** @test */
public function it_updates_user_profile_when_user_profile_updated_event_is_handled()
{
    // Arrange
    $uuid = (string) Str::uuid();
    $user = User::create([
        'id' => $uuid,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'profile' => ['bio' => 'Test bio'],
        'state' => Active::class,
    ]);

    $event = new UserProfileUpdated([
        'name' => 'John Smith',
        'profile' => ['bio' => 'Updated bio'],
        'updated_at' => now(),
    ]);

    // Act
    $this->projector->onUserProfileUpdated($event, $uuid);

    // Assert
    $this->assertDatabaseHas('users', [
        'id' => $uuid,
        'name' => 'John Smith',
    ]);

    $user->refresh();
    $this->assertEquals(['bio' => 'Updated bio'], $user->profile);
}
```php
### Testing Read Models

Read models are tested by:

1. Setting up the read model in a specific state
2. Executing the event handler method
3. Asserting that the read model is updated correctly

```php
/** @test */
public function it_soft_deletes_user_when_user_archived_event_is_handled()
{
    // Arrange
    $uuid = (string) Str::uuid();
    $user = User::create([
        'id' => $uuid,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'profile' => ['bio' => 'Test bio'],
        'state' => Active::class,
    ]);

    $event = new UserArchived([
        'reason' => 'No longer needed',
        'archived_at' => now(),
    ]);

    // Act
    $this->projector->onUserArchived($event, $uuid);

    // Assert
    $this->assertSoftDeleted('users', [
        'id' => $uuid,
    ]);

    $user = User::withTrashed()->find($uuid);
    $this->assertTrue($user->state->equals(new Archived()));
}
```text

## Testing Reactors

### Reactor Test Structure

Reactor tests follow a standard structure:

```php
<?php

namespace Tests\Unit\Reactors;

use Tests\TestCase;
use App\Reactors\UserReactor;
use App\Events\Users\UserRegistered;
use App\Events\Users\UserActivated;
use App\Mail\WelcomeEmail;
use App\Mail\AccountActivatedEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserReactorTest extends TestCase
{
    protected UserReactor $reactor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reactor = new UserReactor();

        // Fake mail
        Mail::fake();
    }

    /** @test */
    public function it_sends_welcome_email_when_user_registered_event_is_handled()
    {
        // Arrange
        $uuid = (string) Str::uuid();
        $event = new UserRegistered([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'profile' => ['bio' => 'Test bio'],
            'registered_at' => now(),
        ]);

        // Act
        $this->reactor->onUserRegistered($event, $uuid);

        // Assert
        Mail::assertSent(WelcomeEmail::class, function ($mail) {
            return $mail->hasTo('john@example.com') &&
                   $mail->name === 'John Doe';
        });
    }

    /** @test */
    public function it_sends_activation_email_when_user_activated_event_is_handled()
    {
        // Arrange
        $uuid = (string) Str::uuid();
        $user = User::create([
            'id' => $uuid,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'profile' => ['bio' => 'Test bio'],
            'state' => PendingActivation::class,
        ]);

        $event = new UserActivated([
            'activated_at' => now(),
        ]);

        // Act
        $this->reactor->onUserActivated($event, $uuid);

        // Assert
        Mail::assertSent(AccountActivatedEmail::class, function ($mail) {
            return $mail->hasTo('john@example.com') &&
                   $mail->name === 'John Doe';
        });
    }
}
```php
### Testing Side Effects

Side effects are tested by:

1. Faking the service that handles the side effect (e.g., Mail, Notification)
2. Executing the reactor event handler method
3. Asserting that the side effect is triggered correctly

```php
/** @test */
public function it_sends_deactivation_email_when_user_deactivated_event_is_handled()
{
    // Arrange
    $uuid = (string) Str::uuid();
    $user = User::create([
        'id' => $uuid,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'profile' => ['bio' => 'Test bio'],
        'state' => Active::class,
    ]);

    $event = new UserDeactivated([
        'reason' => 'No longer active',
        'deactivated_at' => now(),
    ]);

    // Act
    $this->reactor->onUserDeactivated($event, $uuid);

    // Assert
    Mail::assertSent(AccountDeactivatedEmail::class, function ($mail) use ($event) {
        return $mail->hasTo('john@example.com') &&
               $mail->name === 'John Doe' &&
               $mail->reason === 'No longer active';
    });
}
```text

### Mocking External Services

External services are mocked to isolate the reactor:

```php
/** @test */
public function it_logs_user_registration_when_user_registered_event_is_handled()
{
    // Arrange
    $uuid = (string) Str::uuid();
    $event = new UserRegistered([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'profile' => ['bio' => 'Test bio'],
        'registered_at' => now(),
    ]);

    // Mock the logger
    $loggerMock = Mockery::mock('Illuminate\Log\LogManager');
    $loggerMock->shouldReceive('info')
        ->once()
        ->with("User registered: john@example.com");

    $this->app->instance('log', $loggerMock);

    // Act
    $this->reactor->onUserRegistered($event, $uuid);

    // Assert
    // Assertion is handled by the mock expectations
}
```php
## Testing Queries

### Query Test Structure

Query tests follow a standard structure:

```php
<?php

namespace Tests\Unit\Queries;

use Tests\TestCase;
use App\Queries\Users\GetUserQuery;
use App\QueryHandlers\Users\GetUserQueryHandler;
use App\Models\User;
use App\States\User\Active;
use Illuminate\Support\Str;

class GetUserQueryTest extends TestCase
{
    protected GetUserQueryHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new GetUserQueryHandler();
    }

    /** @test */
    public function it_returns_user_by_id()
    {
        // Arrange
        $uuid = (string) Str::uuid();
        $user = User::create([
            'id' => $uuid,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'profile' => ['bio' => 'Test bio'],
            'state' => Active::class,
        ]);

        $query = new GetUserQuery($uuid);

        // Act
        $result = $this->handler->handle($query);

        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($uuid, $result->id);
        $this->assertEquals('John Doe', $result->name);
        $this->assertEquals('john@example.com', $result->email);
    }

    /** @test */
    public function it_throws_exception_when_user_not_found()
    {
        // Arrange
        $uuid = (string) Str::uuid();
        $query = new GetUserQuery($uuid);

        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->handler->handle($query);
    }
}
```text

### Testing Query Handlers

Query handlers are tested by:

1. Setting up the necessary data in the database
2. Creating a query instance
3. Executing the query handler
4. Asserting that the result matches the expected output

```php
/** @test */
public function it_returns_paginated_users()
{
    // Arrange
    User::create([
        'id' => (string) Str::uuid(),
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'state' => Active::class,
    ]);

    User::create([
        'id' => (string) Str::uuid(),
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'state' => Active::class,
    ]);

    $query = new ListUsersQuery(
        search: null,
        state: null,
        sortBy: 'name',
        sortDirection: 'asc',
        page: 1,
        perPage: 10
    );

    $handler = new ListUsersQueryHandler();

    // Act
    $result = $handler->handle($query);

    // Assert
    $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
    $this->assertEquals(2, $result->total());
    $this->assertEquals('Jane Smith', $result->items()[0]->name);
    $this->assertEquals('John Doe', $result->items()[1]->name);
}
```php
### Testing Query Validation

Query validation is tested by:

1. Creating a query instance with invalid data
2. Executing the query through the query bus
3. Asserting that the expected validation exception is thrown

```php
/** @test */
public function it_validates_query_parameters()
{
    // Arrange
    $query = new ListUsersQuery(
        search: null,
        state: 'invalid_state', // Invalid state
        sortBy: 'name',
        sortDirection: 'asc',
        page: 0, // Invalid page
        perPage: 200 // Invalid perPage
    );

    // Act & Assert
    try {
        $this->queryBus->dispatch($query);
        $this->fail('Expected validation exception was not thrown');
    } catch (\Illuminate\Validation\ValidationException $e) {
        $this->assertArrayHasKey('state', $e->errors());
        $this->assertArrayHasKey('page', $e->errors());
        $this->assertArrayHasKey('perPage', $e->errors());
    }
}
```text

## Integration Testing

<details>
<summary>Integration Testing Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
sequenceDiagram
    participant Test as Test Case
    participant Command as Command
    participant Aggregate as Aggregate
    participant Event as Event Store
    participant Projector as Projector
    participant ReadModel as Read Model
    participant Query as Query

    Test->>Command: Dispatch Command
    Command->>Aggregate: Handle Command
    Aggregate->>Event: Record Events
    Event->>Projector: Process Events
    Projector->>ReadModel: Update Read Model
    Test->>Query: Execute Query
    Query->>ReadModel: Retrieve Data
    ReadModel->>Query: Return Data
    Query->>Test: Return Result
    Test->>Test: Assert Result
```php
For dark mode, see [Integration Testing Flow (Dark Mode)](../../illustrations/mermaid/dark/integration-testing-flow-dark.mmd)
</details>

### Command to Query Flow

Integration tests for the command to query flow follow this structure:

```php
<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Commands\Users\RegisterUserCommand;
use App\Queries\Users\GetUserQuery;
use Illuminate\Support\Str;

class UserIntegrationTest extends TestCase
{
    /** @test */
    public function it_can_register_and_retrieve_a_user()
    {
        // Arrange
        $name = 'John Doe';
        $email = 'john@example.com';
        $profile = ['bio' => 'Test bio'];

        $command = new RegisterUserCommand($name, $email, $profile);

        // Act - Execute the command
        $userId = $this->commandBus->dispatch($command);

        // Assert - Verify the user was created
        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
        ]);

        // Act - Retrieve the user
        $query = new GetUserQuery($userId);
        $user = $this->queryBus->dispatch($query);

        // Assert - Verify the retrieved user
        $this->assertEquals($userId, $user->id);
        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($profile, $user->profile);
    }
}
```text

### End-to-End Testing

End-to-end tests cover complete flows from HTTP request to response:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\States\User\Active;
use Illuminate\Support\Str;

class UserFeatureTest extends TestCase
{
    /** @test */
    public function it_can_register_a_user()
    {
        // Arrange
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'bio' => 'Test bio',
        ];

        // Act
        $response = $this->postJson('/api/users/register', $data);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
        ]);

        $userId = $response->json('id');

        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function it_can_retrieve_a_user()
    {
        // Arrange
        $user = User::create([
            'id' => (string) Str::uuid(),
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'profile' => ['bio' => 'Test bio'],
            'state' => Active::class,
        ]);

        // Act
        $response = $this->getJson('/api/users/' . $user->id);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }
}
```php
### Testing Event Replay

Event replay is tested to ensure that projections can be rebuilt correctly:

```php
<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Commands\Users\RegisterUserCommand;
use App\Commands\Users\ActivateUserCommand;
use App\Models\User;
use App\Projectors\UserProjector;
use Illuminate\Support\Facades\Artisan;

class EventReplayTest extends TestCase
{
    /** @test */
    public function it_can_rebuild_projections_from_events()
    {
        // Arrange - Create a user
        $command = new RegisterUserCommand('John Doe', 'john@example.com', ['bio' => 'Test bio']);
        $userId = $this->commandBus->dispatch($command);

        // Activate the user
        $activateCommand = new ActivateUserCommand($userId);
        $this->commandBus->dispatch($activateCommand);

        // Verify the user exists
        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        // Act - Delete the user from the database (but not the events)
        User::where('id', $userId)->delete();

        // Verify the user no longer exists
        $this->assertDatabaseMissing('users', [
            'id' => $userId,
        ]);

        // Replay events
        Artisan::call('event-sourcing:replay', [
            '--projector' => [UserProjector::class],
        ]);

        // Assert - Verify the user was rebuilt
        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        // Verify the user state is correct
        $user = User::find($userId);
        $this->assertTrue($user->state->equals(new Active()));
    }
}
```text

## Test Examples

### User Aggregate Tests

```php
<?php

namespace Tests\Unit\Aggregates;

use Tests\TestCase;
use App\Aggregates\UserAggregateRoot;
use App\Events\Users\UserRegistered;
use App\Events\Users\UserActivated;
use App\Events\Users\UserProfileUpdated;
use App\Events\Users\UserDeactivated;
use App\Exceptions\Users\InvalidUserStateTransitionException;

class UserAggregateTest extends TestCase
{
    /** @test */
    public function it_can_register_a_user()
    {
        // Arrange
        $aggregate = UserAggregateRoot::retrieve('user-id');

        // Act
        $aggregate->registerUser('John Doe', 'john@example.com', ['bio' => 'Test bio']);

        // Assert
        $events = $aggregate->getRecordedEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserRegistered::class, $events[0]);
        $this->assertEquals('John Doe', $events[0]->payload['name']);
        $this->assertEquals('john@example.com', $events[0]->payload['email']);
        $this->assertEquals(['bio' => 'Test bio'], $events[0]->payload['profile']);
    }

    /** @test */
    public function it_can_activate_a_user()
    {
        // Arrange
        $aggregate = UserAggregateRoot::retrieve('user-id')
            ->registerUser('John Doe', 'john@example.com');

        // Clear recorded events
        $aggregate->clearRecordedEvents();

        // Act
        $aggregate->activateUser();

        // Assert
        $events = $aggregate->getRecordedEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserActivated::class, $events[0]);
    }

    /** @test */
    public function it_cannot_activate_an_already_active_user()
    {
        // Arrange
        $aggregate = UserAggregateRoot::retrieve('user-id')
            ->registerUser('John Doe', 'john@example.com')
            ->activateUser();

        // Act & Assert
        $this->expectException(InvalidUserStateTransitionException::class);
        $aggregate->activateUser();
    }
}
```php
### Team Projector Tests

```php
<?php

namespace Tests\Unit\Projectors;

use Tests\TestCase;
use App\Projectors\TeamProjector;
use App\Events\Teams\TeamCreated;
use App\Events\Teams\TeamMemberAdded;
use App\Models\Team;
use App\Models\TeamMember;
use App\States\Team\Forming;
use App\States\Team\Active;
use Illuminate\Support\Str;

class TeamProjectorTest extends TestCase
{
    protected TeamProjector $projector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->projector = new TeamProjector();
    }

    /** @test */
    public function it_creates_a_team_when_team_created_event_is_handled()
    {
        // Arrange
        $uuid = (string) Str::uuid();
        $event = new TeamCreated([
            'name' => 'Test Team',
            'slug' => 'test-team',
            'description' => 'Test team description',
            'settings' => ['setting1' => 'value1'],
            'owner_id' => 'user-id',
            'created_at' => now(),
        ]);

        // Act
        $this->projector->onTeamCreated($event, $uuid);

        // Assert
        $this->assertDatabaseHas('teams', [
            'id' => $uuid,
            'name' => 'Test Team',
            'slug' => 'test-team',
            'description' => 'Test team description',
            'state' => Forming::class,
        ]);

        $team = Team::find($uuid);
        $this->assertEquals(['setting1' => 'value1'], $team->settings);
    }

    /** @test */
    public function it_adds_a_team_member_when_team_member_added_event_is_handled()
    {
        // Arrange
        $uuid = (string) Str::uuid();
        $team = Team::create([
            'id' => $uuid,
            'name' => 'Test Team',
            'slug' => 'test-team',
            'description' => 'Test team description',
            'settings' => ['setting1' => 'value1'],
            'state' => Forming::class,
        ]);

        $event = new TeamMemberAdded([
            'user_id' => 'user-id',
            'role' => 'owner',
            'added_at' => now(),
        ]);

        // Act
        $this->projector->onTeamMemberAdded($event, $uuid);

        // Assert
        $this->assertDatabaseHas('team_members', [
            'team_id' => $uuid,
            'user_id' => 'user-id',
            'role' => 'owner',
        ]);

        $team->refresh();
        $this->assertTrue($team->state->equals(new Active()));
    }
}
```text

### Post Reactor Tests

```php
<?php

namespace Tests\Unit\Reactors;

use Tests\TestCase;
use App\Reactors\PostReactor;
use App\Events\Posts\PostPublished;
use App\Models\Post;
use App\Models\User;
use App\Mail\PostPublishedEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PostReactorTest extends TestCase
{
    protected PostReactor $reactor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reactor = new PostReactor();

        // Fake mail
        Mail::fake();
    }

    /** @test */
    public function it_sends_email_when_post_published_event_is_handled()
    {
        // Arrange
        $uuid = (string) Str::uuid();
        $author = User::create([
            'id' => 'user-id',
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $post = Post::create([
            'id' => $uuid,
            'title' => 'Test Post',
            'slug' => 'test-post',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'author_id' => 'user-id',
        ]);

        $event = new PostPublished([
            'published_at' => now(),
        ]);

        // Act
        $this->reactor->onPostPublished($event, $uuid);

        // Assert
        Mail::assertSent(PostPublishedEmail::class, function ($mail) use ($post) {
            return $mail->hasTo('john@example.com') &&
                   $mail->title === 'Test Post' &&
                   $mail->slug === 'test-post';
        });
    }
}
```php
### Todo Query Tests

```php
<?php

namespace Tests\Unit\Queries;

use Tests\TestCase;
use App\Queries\Todos\ListTodosQuery;
use App\QueryHandlers\Todos\ListTodosQueryHandler;
use App\Models\Todo;
use App\Models\User;
use App\Models\Team;
use App\States\Todo\Pending;
use App\States\Todo\Completed;
use Illuminate\Support\Str;

class ListTodosQueryTest extends TestCase
{
    protected ListTodosQueryHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new ListTodosQueryHandler();
    }

    /** @test */
    public function it_returns_filtered_todos()
    {
        // Arrange
        $team = Team::create([
            'id' => (string) Str::uuid(),
            'name' => 'Test Team',
        ]);

        $user = User::create([
            'id' => (string) Str::uuid(),
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        // Create a pending todo
        Todo::create([
            'id' => (string) Str::uuid(),
            'title' => 'Pending Todo',
            'description' => 'This is a pending todo',
            'team_id' => $team->id,
            'user_id' => $user->id,
            'state' => Pending::class,
        ]);

        // Create a completed todo
        Todo::create([
            'id' => (string) Str::uuid(),
            'title' => 'Completed Todo',
            'description' => 'This is a completed todo',
            'team_id' => $team->id,
            'user_id' => $user->id,
            'state' => Completed::class,
        ]);

        $query = new ListTodosQuery(
            search: null,
            state: 'pending',
            userId: $user->id,
            teamId: $team->id,
            tags: null,
            sortBy: 'title',
            sortDirection: 'asc',
            page: 1,
            perPage: 10
        );

        // Act
        $result = $this->handler->handle($query);

        // Assert
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
        $this->assertEquals('Pending Todo', $result->items()[0]->title);
    }
}
```text

## Common Patterns and Best Practices

### Test Data Generation

Use factories to generate test data:

```php
// Create a user
$user = User::factory()->create([
    'state' => Active::class,
]);

// Create a team with members
$team = Team::factory()
    ->has(TeamMember::factory()->count(3))
    ->create([
        'state' => Active::class,
    ]);
```php
### Test Isolation

Ensure tests are isolated from each other:

```php
protected function setUp(): void
{
    parent::setUp();

    // Use a separate database connection for testing
    $this->app['config']->set('database.default', 'sqlite');
    $this->app['config']->set('database.connections.sqlite', [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ]);

    // Migrate the database
    $this->artisan('migrate:fresh');
}
```text

### Test Performance

Optimize tests for performance:

```php
// Use database transactions to speed up tests
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserAggregateTest extends TestCase
{
    use DatabaseTransactions;

    // Test methods
}
```php
### Test Coverage

Ensure comprehensive test coverage:

```php
// Run tests with coverage report
$ vendor/bin/phpunit --coverage-html coverage
```text

## Troubleshooting

### Common Issues

<details>
<summary>Tests failing due to missing events</summary>

**Symptoms:**
- Tests fail with errors about missing events
- Aggregates don't behave as expected in tests

**Possible Causes:**
- Events not being recorded correctly
- Events not being applied correctly
- Missing apply methods

**Solutions:**
1. Ensure events are being recorded with `recordThat()`
2. Ensure apply methods are implemented correctly
3. Check that event classes are correctly defined
4. Verify that event payloads contain all required data
</details>

<details>
<summary>Projector tests failing</summary>

**Symptoms:**
- Projector tests fail with database errors
- Read models not being updated correctly

**Possible Causes:**
- Missing database migrations
- Incorrect event handling in projectors
- Database constraints or validation errors

**Solutions:**
1. Ensure database migrations are run before tests
2. Verify that projector event handlers are implemented correctly
3. Check database constraints and validation rules
4. Use database transactions to isolate tests
</details>

<details>
<summary>Reactor tests failing</summary>

**Symptoms:**
- Reactor tests fail with errors about missing services
- Side effects not being triggered correctly

**Possible Causes:**
- Missing service mocks
- Incorrect event handling in reactors
- External dependencies not being mocked correctly

**Solutions:**
1. Ensure all external services are mocked or faked
2. Verify that reactor event handlers are implemented correctly
3. Check that mock expectations are set up correctly
4. Use Laravel's built-in fakes for Mail, Notification, etc.
</details>

### Solutions

For detailed solutions to common issues, refer to the following resources:

- [Spatie Event Sourcing Documentation](https:/spatie.be/docs/laravel-event-sourcing)
- [Laravel Testing Documentation](https:/laravel.com/docs/testing)
- [PHPUnit Documentation](https:/phpunit.readthedocs.io)

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing Reactors](040-reactors.md) - Detailed documentation on reactor implementation
- [Event Sourcing Queries](060-queries.md) - Detailed documentation on query implementation

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added testing layers diagram, test structure diagram, integration testing flow diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
