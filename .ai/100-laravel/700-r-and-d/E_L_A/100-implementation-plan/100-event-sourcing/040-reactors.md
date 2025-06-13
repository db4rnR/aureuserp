# Phase 1: Event Sourcing Reactors

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
- [Reactor Concept](#reactor-concept)
  - [What is a Reactor?](#what-is-a-reactor)
  - [Reactor Responsibilities](#reactor-responsibilities)
  - [Reactor Types](#reactor-types)
- [Implementing Reactors](#implementing-reactors)
  - [Base Reactor Structure](#base-reactor-structure)
  - [Event Handlers](#event-handlers)
  - [Reactor Registration](#reactor-registration)
- [Integration with spatie/laravel-event-sourcing](#integration-with-spatielaravel-event-sourcing)
  - [Reactor Class](#reactor-class)
  - [Event Handling Methods](#event-handling-methods)
  - [Queued Reactors](#queued-reactors)
- [Handling Side Effects](#handling-side-effects)
  - [Email Notifications](#email-notifications)
  - [Push Notifications](#push-notifications)
  - [External API Calls](#external-api-calls)
  - [Scheduled Tasks](#scheduled-tasks)
- [Integration with Laravel's Queue System](#integration-with-laravels-queue-system)
  - [Queue Configuration](#queue-configuration)
  - [Queue Monitoring](#queue-monitoring)
  - [Failed Jobs](#failed-jobs)
- [Reactor Examples](#reactor-examples)
  - [User Reactor](#user-reactor)
  - [Team Reactor](#team-reactor)
  - [Post Reactor](#post-reactor)
  - [Todo Reactor](#todo-reactor)
  - [Comment Reactor](#comment-reactor)
  - [Message Reactor](#message-reactor)
- [Common Patterns and Best Practices](#common-patterns-and-best-practices)
  - [Single Responsibility](#single-responsibility)
  - [Idempotency](#idempotency)
  - [Error Handling](#error-handling)
  - [Logging](#logging)
- [Benefits and Challenges](#benefits-and-challenges)
  - [Benefits](#benefits)
  - [Challenges](#challenges)
  - [Mitigation Strategies](#mitigation-strategies)
- [Troubleshooting](#troubleshooting)
  - [Common Issues](#common-issues)
  - [Solutions](#solutions)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides a comprehensive guide to implementing reactors in event sourcing for the Enhanced Laravel Application (ELA). Reactors are responsible for handling side effects based on events from the event store. This document covers the concept of reactors, their implementation using `spatie/laravel-event-sourcing`, and best practices for designing effective reactors.

## Prerequisites

- **Required Prior Steps:**
  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [Event Sourcing Projectors](030-projectors.md)
  - [CQRS Configuration](../030-core-components/030-cqrs-configuration.md)
  - [Package Installation](../030-core-components/010-package-installation.md) (specifically `spatie/laravel-event-sourcing`)

- **Required Packages:**
  - `spatie/laravel-event-sourcing`: ^7.0
  - `laravel/reverb`: ^1.0 (for real-time notifications)

- **Required Knowledge:**
  - Understanding of event sourcing principles
  - Familiarity with CQRS pattern
  - Understanding of Laravel's queue system

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher
  - Redis or another queue driver

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Understanding reactor concepts | 1 hour |
| Setting up base reactor structure | 1 hour |
| Implementing event handlers | 2 hours per aggregate |
| Testing reactors | 1 hour per aggregate |
| **Total** | **4+ hours per aggregate** |
</details>

## Reactor Concept

<details>
<summary>Reactor Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart LR
    A[Event Store] --> B[Reactor]
    B --> C{Event Type?}
    C -->|UserRegistered| D[Send Welcome Email]
    C -->|TeamCreated| E[Send Team Notification]
    C -->|PostPublished| F[Send Social Media Update]
    D --> G[Email Service]
    E --> H[Notification Service]
    F --> I[Social Media API]
```text

For dark mode, see [Reactor Flow (Dark Mode)](../../illustrations/mermaid/dark/reactor-flow-dark.mmd)
</details>

### What is a Reactor?

A reactor is a component in event sourcing that processes events from the event store and performs side effects. Unlike projectors, which update read models, reactors perform actions such as sending notifications, calling external APIs, or triggering other processes. Reactors are responsible for:

1. Listening for specific events
2. Performing side effects based on those events
3. Ensuring side effects are performed reliably

Reactors are a key part of the CQRS pattern, handling the side effects of commands and events.

### Reactor Responsibilities

Reactors have several key responsibilities:

1. **Event Processing**: Processing events from the event store
2. **Side Effect Execution**: Performing side effects based on events
3. **Error Handling**: Handling errors in side effect execution
4. **Idempotency**: Ensuring side effects are performed exactly once

### Reactor Types

There are several types of reactors that can be implemented:

1. **Notification Reactors**: Send notifications to users
2. **Integration Reactors**: Integrate with external systems
3. **Process Reactors**: Trigger other processes or workflows
4. **Audit Reactors**: Record audit logs or metrics

In the ELA, we use a combination of these reactor types to handle various side effects.

## Implementing Reactors

### Base Reactor Structure

In the ELA, reactors are implemented as classes that extend `Spatie\EventSourcing\EventHandlers\Reactors\Reactor`:

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class UserReactor extends Reactor
{
    // Event handlers
}
```php
### Event Handlers

Event handlers are methods that process specific events:

```php
public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
{
    // Send welcome email
    Mail::to($event->payload['email'])->send(new WelcomeEmail($event->payload['name']));

    // Log the registration
    Log::info("User registered: {$event->payload['email']}");
}

public function onUserActivated(UserActivated $event, string $aggregateUuid)
{
    // Send activation confirmation email
    $user = User::findOrFail($aggregateUuid);
    Mail::to($user->email)->send(new AccountActivatedEmail($user->name));
}
```text

### Reactor Registration

Reactors are registered in the `config/event-sourcing.php` configuration file:

```php
'reactors' => [
    \App\Reactors\UserReactor::class,
    \App\Reactors\TeamReactor::class,
    \App\Reactors\PostReactor::class,
    \App\Reactors\TodoReactor::class,
    \App\Reactors\CommentReactor::class,
    \App\Reactors\MessageReactor::class,
],
```php
Alternatively, reactors can be auto-discovered by configuring the auto-discovery directories:

```php
'auto_discover_projectors_and_reactors' => [
    app()->path(),
],
```text

## Integration with spatie/laravel-event-sourcing

### Reactor Class

The `Reactor` class from `spatie/laravel-event-sourcing` provides the foundation for implementing reactors:

```php
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class UserReactor extends Reactor
{
    // Implementation
}
```php
### Event Handling Methods

Event handling methods follow a naming convention of `on{EventName}`:

```php
public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
{
    // Handle the event
}
```text

The method receives the event object and the aggregate UUID as parameters.

### Queued Reactors

Reactors can be queued to handle side effects asynchronously:

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserReactor extends Reactor implements ShouldQueue
{
    public $queue = 'reactors';

    // Event handlers
}
```php
## Handling Side Effects

### Email Notifications

Reactors can send email notifications:

```php
public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
{
    Mail::to($event->payload['email'])->send(new WelcomeEmail($event->payload['name']));
}
```text

### Push Notifications

Reactors can send push notifications:

```php
public function onMessageSent(MessageSent $event, string $aggregateUuid)
{
    $conversation = Conversation::findOrFail($aggregateUuid);
    $sender = User::findOrFail($event->payload['sender_id']);

    foreach ($conversation->participants as $participant) {
        if ($participant->user_id !== $event->payload['sender_id']) {
            $participant->user->notify(new NewMessageNotification(
                $sender->name,
                $event->payload['content'],
                $conversation->id
            ));
        }
    }
}
```php
### External API Calls

Reactors can make calls to external APIs:

```php
public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
{
    $client = new Client();
    $client->post('https://api.analytics.com/track', [
        'json' => [
            'event' => 'user_registered',
            'user_id' => $aggregateUuid,
            'email' => $event->payload['email'],
            'timestamp' => now()->timestamp,
        ],
    ]);
}
```text

### Scheduled Tasks

Reactors can schedule tasks:

```php
public function onPostScheduled(PostScheduled $event, string $aggregateUuid)
{
    $scheduledAt = Carbon::parse($event->payload['scheduled_at']);

    PublishScheduledPostJob::dispatch($aggregateUuid)
        ->delay($scheduledAt);
}
```php
## Integration with Laravel's Queue System

<details>
<summary>Queue Integration Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
sequenceDiagram
    participant A as Aggregate
    participant E as Event Store
    participant R as Reactor
    participant Q as Queue
    participant W as Queue Worker
    participant S as Side Effect

    A->>E: Store Event
    E->>R: Dispatch Event
    R->>Q: Push Job
    Q->>W: Process Job
    W->>S: Execute Side Effect
    alt Success
        S->>W: Return Success
        W->>Q: Mark Job as Completed
    else Failure
        S->>W: Return Failure
        W->>Q: Retry Job
    end
```text

For dark mode, see [Queue Integration (Dark Mode)](../../illustrations/mermaid/dark/queue-integration-dark.mmd)
</details>

### Queue Configuration

Configure queues in `config/queue.php`:

```php
'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],
```php
### Queue Monitoring

Monitor queues using Laravel Horizon:

```php
// config/horizon.php
'environments' => [
    'production' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default', 'reactors'],
            'balance' => 'simple',
            'processes' => 10,
            'tries' => 3,
        ],
    ],
],
```text

### Failed Jobs

Handle failed jobs:

```php
// app/Exceptions/Handler.php
public function register()
{
    $this->reportable(function (QueueFailedException $e) {
        // Log the failure
        Log::error('Queue job failed', [
            'exception' => $e->getMessage(),
            'job' => $e->job->resolveName(),
            'payload' => $e->job->payload(),
        ]);
    });
}
```javascript
## Reactor Examples

<details>
<summary>Reactor Architecture Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
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
```text

For dark mode, see [Reactor Architecture (Dark Mode)](../../illustrations/mermaid/dark/reactor-architecture-dark.mmd)
</details>

### User Reactor

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use App\Events\Users\UserRegistered;
use App\Events\Users\UserActivated;
use App\Events\Users\UserDeactivated;
use App\Events\Users\UserSuspended;
use App\Events\Users\UserUnsuspended;
use App\Events\Users\UserArchived;
use App\Mail\WelcomeEmail;
use App\Mail\AccountActivatedEmail;
use App\Mail\AccountDeactivatedEmail;
use App\Mail\AccountSuspendedEmail;
use App\Mail\AccountUnsuspendedEmail;
use App\Mail\AccountArchivedEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserReactor extends Reactor implements ShouldQueue
{
    public $queue = 'reactors';

    public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
    {
        // Send welcome email
        Mail::to($event->payload['email'])->send(new WelcomeEmail($event->payload['name']));

        // Log the registration
        Log::info("User registered: {$event->payload['email']}");
    }

    public function onUserActivated(UserActivated $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);

        // Send activation confirmation email
        Mail::to($user->email)->send(new AccountActivatedEmail($user->name));

        // Log the activation
        Log::info("User activated: {$user->email}");
    }

    public function onUserDeactivated(UserDeactivated $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);

        // Send deactivation email
        Mail::to($user->email)->send(new AccountDeactivatedEmail(
            $user->name,
            $event->payload['reason']
        ));

        // Log the deactivation
        Log::info("User deactivated: {$user->email}", [
            'reason' => $event->payload['reason'],
        ]);
    }

    public function onUserSuspended(UserSuspended $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);

        // Send suspension email
        Mail::to($user->email)->send(new AccountSuspendedEmail(
            $user->name,
            $event->payload['reason'],
            $event->payload['suspended_until']
        ));

        // Log the suspension
        Log::info("User suspended: {$user->email}", [
            'reason' => $event->payload['reason'],
            'suspended_until' => $event->payload['suspended_until'],
        ]);
    }

    public function onUserUnsuspended(UserUnsuspended $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);

        // Send unsuspension email
        Mail::to($user->email)->send(new AccountUnsuspendedEmail($user->name));

        // Log the unsuspension
        Log::info("User unsuspended: {$user->email}");
    }

    public function onUserArchived(UserArchived $event, string $aggregateUuid)
    {
        $user = User::withTrashed()->findOrFail($aggregateUuid);

        // Send archival email
        Mail::to($user->email)->send(new AccountArchivedEmail(
            $user->name,
            $event->payload['reason']
        ));

        // Log the archival
        Log::info("User archived: {$user->email}", [
            'reason' => $event->payload['reason'],
        ]);
    }
}
```php
### Team Reactor

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use App\Events\Teams\TeamCreated;
use App\Events\Teams\TeamMemberAdded;
use App\Events\Teams\TeamMemberRemoved;
use App\Events\Teams\TeamMemberRoleChanged;
use App\Events\Teams\TeamArchived;
use App\Mail\TeamCreatedEmail;
use App\Mail\TeamMemberAddedEmail;
use App\Mail\TeamMemberRemovedEmail;
use App\Mail\TeamMemberRoleChangedEmail;
use App\Mail\TeamArchivedEmail;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class TeamReactor extends Reactor implements ShouldQueue
{
    public $queue = 'reactors';

    public function onTeamCreated(TeamCreated $event, string $aggregateUuid)
    {
        $owner = User::findOrFail($event->payload['owner_id']);

        // Send team creation email
        Mail::to($owner->email)->send(new TeamCreatedEmail(
            $owner->name,
            $event->payload['name']
        ));

        // Log the team creation
        Log::info("Team created: {$event->payload['name']}", [
            'owner_id' => $event->payload['owner_id'],
        ]);
    }

    public function onTeamMemberAdded(TeamMemberAdded $event, string $aggregateUuid)
    {
        $team = Team::findOrFail($aggregateUuid);
        $user = User::findOrFail($event->payload['user_id']);

        // Send team member added email
        Mail::to($user->email)->send(new TeamMemberAddedEmail(
            $user->name,
            $team->name,
            $event->payload['role']
        ));

        // Notify team owner and admins
        foreach ($team->members()->whereIn('role', ['owner', 'admin'])->get() as $member) {
            if ($member->user_id !== $event->payload['user_id']) {
                Mail::to($member->user->email)->send(new TeamMemberAddedNotificationEmail(
                    $member->user->name,
                    $team->name,
                    $user->name,
                    $event->payload['role']
                ));
            }
        }

        // Log the team member addition
        Log::info("Team member added: {$user->email} to {$team->name}", [
            'role' => $event->payload['role'],
        ]);
    }

    public function onTeamMemberRemoved(TeamMemberRemoved $event, string $aggregateUuid)
    {
        $team = Team::findOrFail($aggregateUuid);
        $user = User::findOrFail($event->payload['user_id']);

        // Send team member removed email
        Mail::to($user->email)->send(new TeamMemberRemovedEmail(
            $user->name,
            $team->name
        ));

        // Notify team owner and admins
        foreach ($team->members()->whereIn('role', ['owner', 'admin'])->get() as $member) {
            if ($member->user_id !== $event->payload['user_id']) {
                Mail::to($member->user->email)->send(new TeamMemberRemovedNotificationEmail(
                    $member->user->name,
                    $team->name,
                    $user->name
                ));
            }
        }

        // Log the team member removal
        Log::info("Team member removed: {$user->email} from {$team->name}");
    }

    public function onTeamMemberRoleChanged(TeamMemberRoleChanged $event, string $aggregateUuid)
    {
        $team = Team::findOrFail($aggregateUuid);
        $user = User::findOrFail($event->payload['user_id']);

        // Send team member role changed email
        Mail::to($user->email)->send(new TeamMemberRoleChangedEmail(
            $user->name,
            $team->name,
            $event->payload['old_role'],
            $event->payload['new_role']
        ));

        // Log the team member role change
        Log::info("Team member role changed: {$user->email} in {$team->name}", [
            'old_role' => $event->payload['old_role'],
            'new_role' => $event->payload['new_role'],
        ]);
    }

    public function onTeamArchived(TeamArchived $event, string $aggregateUuid)
    {
        $team = Team::withTrashed()->findOrFail($aggregateUuid);

        // Notify all team members
        foreach ($team->members as $member) {
            Mail::to($member->user->email)->send(new TeamArchivedEmail(
                $member->user->name,
                $team->name,
                $event->payload['reason']
            ));
        }

        // Log the team archival
        Log::info("Team archived: {$team->name}", [
            'reason' => $event->payload['reason'],
        ]);
    }
}
```text

### Post Reactor

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use App\Events\Posts\PostCreated;
use App\Events\Posts\PostPublished;
use App\Events\Posts\PostUnpublished;
use App\Events\Posts\PostScheduled;
use App\Events\Posts\PostArchived;
use App\Mail\PostPublishedEmail;
use App\Mail\PostScheduledEmail;
use App\Jobs\PublishScheduledPostJob;
use App\Models\Post;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class PostReactor extends Reactor implements ShouldQueue
{
    public $queue = 'reactors';

    public function onPostCreated(PostCreated $event, string $aggregateUuid)
    {
        $author = User::findOrFail($event->payload['author_id']);

        // Log the post creation
        Log::info("Post created: {$event->payload['title']}", [
            'author_id' => $event->payload['author_id'],
            'team_id' => $event->payload['team_id'],
        ]);
    }

    public function onPostPublished(PostPublished $event, string $aggregateUuid)
    {
        $post = Post::findOrFail($aggregateUuid);
        $author = User::findOrFail($post->author_id);

        // Send post published email to author
        Mail::to($author->email)->send(new PostPublishedEmail(
            $author->name,
            $post->title,
            $post->slug
        ));

        // If post belongs to a team, notify team members
        if ($post->team_id) {
            $team = Team::findOrFail($post->team_id);

            foreach ($team->members as $member) {
                if ($member->user_id !== $post->author_id) {
                    Mail::to($member->user->email)->send(new TeamPostPublishedEmail(
                        $member->user->name,
                        $author->name,
                        $post->title,
                        $post->slug,
                        $team->name
                    ));
                }
            }
        }

        // Log the post publication
        Log::info("Post published: {$post->title}", [
            'author_id' => $post->author_id,
            'team_id' => $post->team_id,
            'published_at' => $event->payload['published_at'],
        ]);
    }

    public function onPostUnpublished(PostUnpublished $event, string $aggregateUuid)
    {
        $post = Post::findOrFail($aggregateUuid);

        // Log the post unpublication
        Log::info("Post unpublished: {$post->title}", [
            'author_id' => $post->author_id,
            'team_id' => $post->team_id,
            'unpublished_at' => $event->payload['unpublished_at'],
        ]);
    }

    public function onPostScheduled(PostScheduled $event, string $aggregateUuid)
    {
        $post = Post::findOrFail($aggregateUuid);
        $author = User::findOrFail($post->author_id);
        $scheduledAt = Carbon::parse($event->payload['scheduled_at']);

        // Send post scheduled email to author
        Mail::to($author->email)->send(new PostScheduledEmail(
            $author->name,
            $post->title,
            $scheduledAt->format('Y-m-d H:i:s')
        ));

        // Schedule the post publication job
        PublishScheduledPostJob::dispatch($aggregateUuid)
            ->delay($scheduledAt);

        // Log the post scheduling
        Log::info("Post scheduled: {$post->title}", [
            'author_id' => $post->author_id,
            'team_id' => $post->team_id,
            'scheduled_at' => $event->payload['scheduled_at'],
        ]);
    }

    public function onPostArchived(PostArchived $event, string $aggregateUuid)
    {
        $post = Post::withTrashed()->findOrFail($aggregateUuid);

        // Log the post archival
        Log::info("Post archived: {$post->title}", [
            'author_id' => $post->author_id,
            'team_id' => $post->team_id,
            'reason' => $event->payload['reason'],
            'archived_at' => $event->payload['archived_at'],
        ]);
    }
}
```php
### Todo Reactor

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use App\Events\Todos\TodoCreated;
use App\Events\Todos\TodoAssigned;
use App\Events\Todos\TodoStarted;
use App\Events\Todos\TodoCompleted;
use App\Events\Todos\TodoCancelled;
use App\Mail\TodoAssignedEmail;
use App\Mail\TodoCompletedEmail;
use App\Mail\TodoCancelledEmail;
use App\Models\Todo;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class TodoReactor extends Reactor implements ShouldQueue
{
    public $queue = 'reactors';

    public function onTodoCreated(TodoCreated $event, string $aggregateUuid)
    {
        // Log the todo creation
        Log::info("Todo created: {$event->payload['title']}", [
            'team_id' => $event->payload['team_id'],
            'user_id' => $event->payload['user_id'],
        ]);

        // If todo is assigned to a user, notify them
        if ($event->payload['user_id']) {
            $this->notifyAssignedUser($aggregateUuid, $event->payload['user_id']);
        }
    }

    public function onTodoAssigned(TodoAssigned $event, string $aggregateUuid)
    {
        // Log the todo assignment
        Log::info("Todo assigned", [
            'todo_id' => $aggregateUuid,
            'user_id' => $event->payload['user_id'],
        ]);

        // If todo is assigned to a user, notify them
        if ($event->payload['user_id']) {
            $this->notifyAssignedUser($aggregateUuid, $event->payload['user_id']);
        }
    }

    public function onTodoStarted(TodoStarted $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);

        // Log the todo start
        Log::info("Todo started: {$todo->title}", [
            'todo_id' => $aggregateUuid,
            'user_id' => $todo->user_id,
            'team_id' => $todo->team_id,
        ]);
    }

    public function onTodoCompleted(TodoCompleted $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);

        // Log the todo completion
        Log::info("Todo completed: {$todo->title}", [
            'todo_id' => $aggregateUuid,
            'user_id' => $todo->user_id,
            'team_id' => $todo->team_id,
            'completed_at' => $event->payload['completed_at'],
        ]);

        // Notify team members about the completion
        $this->notifyTeamAboutCompletion($todo);
    }

    public function onTodoCancelled(TodoCancelled $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);

        // Log the todo cancellation
        Log::info("Todo cancelled: {$todo->title}", [
            'todo_id' => $aggregateUuid,
            'user_id' => $todo->user_id,
            'team_id' => $todo->team_id,
            'reason' => $event->payload['reason'],
        ]);

        // If todo was assigned to a user, notify them
        if ($todo->user_id) {
            $user = User::findOrFail($todo->user_id);

            Mail::to($user->email)->send(new TodoCancelledEmail(
                $user->name,
                $todo->title,
                $event->payload['reason']
            ));
        }
    }

    protected function notifyAssignedUser(string $todoId, string $userId): void
    {
        $todo = Todo::findOrFail($todoId);
        $user = User::findOrFail($userId);

        Mail::to($user->email)->send(new TodoAssignedEmail(
            $user->name,
            $todo->title,
            $todo->description,
            $todo->due_date
        ));
    }

    protected function notifyTeamAboutCompletion(Todo $todo): void
    {
        $team = Team::findOrFail($todo->team_id);
        $completer = $todo->user_id ? User::findOrFail($todo->user_id) : null;

        foreach ($team->members as $member) {
            if (!$completer || $member->user_id !== $completer->id) {
                Mail::to($member->user->email)->send(new TodoCompletedEmail(
                    $member->user->name,
                    $todo->title,
                    $completer ? $completer->name : 'Someone',
                    $team->name
                ));
            }
        }
    }
}
```text

### Comment Reactor

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use App\Events\Comments\CommentCreated;
use App\Events\Comments\CommentUpdated;
use App\Events\Comments\CommentApproved;
use App\Events\Comments\CommentRejected;
use App\Events\Comments\ReactionAdded;
use App\Mail\CommentAddedEmail;
use App\Mail\CommentApprovedEmail;
use App\Mail\CommentRejectedEmail;
use App\Mail\ReactionAddedEmail;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentReactor extends Reactor implements ShouldQueue
{
    public $queue = 'reactors';

    public function onCommentCreated(CommentCreated $event, string $aggregateUuid)
    {
        $comment = Comment::findOrFail($aggregateUuid);
        $user = User::findOrFail($event->payload['user_id']);

        // Log the comment creation
        Log::info("Comment created", [
            'comment_id' => $aggregateUuid,
            'user_id' => $event->payload['user_id'],
            'commentable_type' => $event->payload['commentable_type'],
            'commentable_id' => $event->payload['commentable_id'],
        ]);

        // Notify the owner of the commentable item
        $this->notifyCommentableOwner($comment, $user);
    }

    public function onCommentUpdated(CommentUpdated $event, string $aggregateUuid)
    {
        // Log the comment update
        Log::info("Comment updated", [
            'comment_id' => $aggregateUuid,
        ]);
    }

    public function onCommentApproved(CommentApproved $event, string $aggregateUuid)
    {
        $comment = Comment::findOrFail($aggregateUuid);
        $user = User::findOrFail($comment->user_id);

        // Log the comment approval
        Log::info("Comment approved", [
            'comment_id' => $aggregateUuid,
            'approved_at' => $event->payload['approved_at'],
        ]);

        // Notify the comment author
        Mail::to($user->email)->send(new CommentApprovedEmail(
            $user->name,
            $this->getCommentableTitle($comment)
        ));
    }

    public function onCommentRejected(CommentRejected $event, string $aggregateUuid)
    {
        $comment = Comment::findOrFail($aggregateUuid);
        $user = User::findOrFail($comment->user_id);

        // Log the comment rejection
        Log::info("Comment rejected", [
            'comment_id' => $aggregateUuid,
            'reason' => $event->payload['reason'],
            'rejected_at' => $event->payload['rejected_at'],
        ]);

        // Notify the comment author
        Mail::to($user->email)->send(new CommentRejectedEmail(
            $user->name,
            $this->getCommentableTitle($comment),
            $event->payload['reason']
        ));
    }

    public function onReactionAdded(ReactionAdded $event, string $aggregateUuid)
    {
        $comment = Comment::findOrFail($aggregateUuid);
        $reactor = User::findOrFail($event->payload['user_id']);
        $commentAuthor = User::findOrFail($comment->user_id);

        // Log the reaction addition
        Log::info("Reaction added", [
            'comment_id' => $aggregateUuid,
            'user_id' => $event->payload['user_id'],
            'type' => $event->payload['type'],
        ]);

        // Notify the comment author about the reaction
        if ($reactor->id !== $commentAuthor->id) {
            Mail::to($commentAuthor->email)->send(new ReactionAddedEmail(
                $commentAuthor->name,
                $reactor->name,
                $event->payload['type'],
                $this->getCommentableTitle($comment)
            ));
        }
    }

    protected function notifyCommentableOwner(Comment $comment, User $commenter): void
    {
        $commentable = $comment->commentable;

        if (!$commentable) {
            return;
        }

        // Get the owner of the commentable item
        $owner = $this->getCommentableOwner($commentable);

        if (!$owner || $owner->id === $commenter->id) {
            return;
        }

        Mail::to($owner->email)->send(new CommentAddedEmail(
            $owner->name,
            $commenter->name,
            $this->getCommentableTitle($comment),
            $comment->content
        ));
    }

    protected function getCommentableOwner($commentable): ?User
    {
        if (method_exists($commentable, 'user') && $commentable->user) {
            return $commentable->user;
        }

        if (method_exists($commentable, 'author') && $commentable->author) {
            return $commentable->author;
        }

        if (property_exists($commentable, 'user_id') && $commentable->user_id) {
            return User::find($commentable->user_id);
        }

        if (property_exists($commentable, 'author_id') && $commentable->author_id) {
            return User::find($commentable->author_id);
        }

        return null;
    }

    protected function getCommentableTitle(Comment $comment): string
    {
        $commentable = $comment->commentable;

        if (!$commentable) {
            return 'Unknown item';
        }

        if (method_exists($commentable, 'getTitle')) {
            return $commentable->getTitle();
        }

        if (property_exists($commentable, 'title') && $commentable->title) {
            return $commentable->title;
        }

        if (property_exists($commentable, 'name') && $commentable->name) {
            return $commentable->name;
        }

        return class_basename($commentable) . ' #' . $commentable->id;
    }
}
```php
### Message Reactor

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use App\Events\Messages\ConversationCreated;
use App\Events\Messages\MessageSent;
use App\Events\Messages\ParticipantAdded;
use App\Events\Messages\ParticipantRemoved;
use App\Models\Conversation;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Notifications\AddedToConversationNotification;
use App\Notifications\RemovedFromConversationNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageReactor extends Reactor implements ShouldQueue
{
    public $queue = 'reactors';

    public function onConversationCreated(ConversationCreated $event, string $aggregateUuid)
    {
        // Log the conversation creation
        Log::info("Conversation created", [
            'conversation_id' => $aggregateUuid,
            'type' => $event->payload['type'],
            'creator_id' => $event->payload['creator_id'],
        ]);
    }

    public function onMessageSent(MessageSent $event, string $aggregateUuid)
    {
        $conversation = Conversation::findOrFail($aggregateUuid);
        $sender = User::findOrFail($event->payload['sender_id']);

        // Log the message sending
        Log::info("Message sent", [
            'conversation_id' => $aggregateUuid,
            'message_id' => $event->payload['message_id'],
            'sender_id' => $event->payload['sender_id'],
        ]);

        // Notify all participants except the sender
        foreach ($conversation->participants as $participant) {
            if ($participant->user_id !== $sender->id) {
                $participant->user->notify(new NewMessageNotification(
                    $sender->name,
                    $event->payload['content'],
                    $conversation->id,
                    $event->payload['message_id']
                ));
            }
        }

        // Broadcast the message to the conversation channel
        broadcast(new MessageSentEvent(
            $conversation->id,
            $event->payload['message_id'],
            $sender->id,
            $sender->name,
            $event->payload['content'],
            $event->payload['sent_at']
        ))->toPresence('conversation.' . $conversation->id);
    }

    public function onParticipantAdded(ParticipantAdded $event, string $aggregateUuid)
    {
        $conversation = Conversation::findOrFail($aggregateUuid);
        $user = User::findOrFail($event->payload['user_id']);
        $addedBy = User::findOrFail($event->payload['added_by']);

        // Log the participant addition
        Log::info("Participant added to conversation", [
            'conversation_id' => $aggregateUuid,
            'user_id' => $event->payload['user_id'],
            'added_by' => $event->payload['added_by'],
        ]);

        // Notify the added user
        $user->notify(new AddedToConversationNotification(
            $conversation->name ?? 'Conversation',
            $addedBy->name,
            $conversation->id
        ));

        // Notify other participants
        foreach ($conversation->participants as $participant) {
            if ($participant->user_id !== $user->id && $participant->user_id !== $addedBy->id) {
                $participant->user->notify(new ParticipantAddedNotification(
                    $conversation->name ?? 'Conversation',
                    $user->name,
                    $addedBy->name,
                    $conversation->id
                ));
            }
        }

        // Broadcast the participant addition to the conversation channel
        broadcast(new ParticipantAddedEvent(
            $conversation->id,
            $user->id,
            $user->name,
            $addedBy->id,
            $addedBy->name,
            $event->payload['added_at']
        ))->toPresence('conversation.' . $conversation->id);
    }

    public function onParticipantRemoved(ParticipantRemoved $event, string $aggregateUuid)
    {
        $conversation = Conversation::findOrFail($aggregateUuid);
        $user = User::findOrFail($event->payload['user_id']);
        $removedBy = User::findOrFail($event->payload['removed_by']);

        // Log the participant removal
        Log::info("Participant removed from conversation", [
            'conversation_id' => $aggregateUuid,
            'user_id' => $event->payload['user_id'],
            'removed_by' => $event->payload['removed_by'],
        ]);

        // Notify the removed user
        $user->notify(new RemovedFromConversationNotification(
            $conversation->name ?? 'Conversation',
            $removedBy->name,
            $conversation->id
        ));

        // Notify other participants
        foreach ($conversation->participants as $participant) {
            if ($participant->user_id !== $user->id && $participant->user_id !== $removedBy->id) {
                $participant->user->notify(new ParticipantRemovedNotification(
                    $conversation->name ?? 'Conversation',
                    $user->name,
                    $removedBy->name,
                    $conversation->id
                ));
            }
        }

        // Broadcast the participant removal to the conversation channel
        broadcast(new ParticipantRemovedEvent(
            $conversation->id,
            $user->id,
            $user->name,
            $removedBy->id,
            $removedBy->name,
            $event->payload['removed_at']
        ))->toPresence('conversation.' . $conversation->id);
    }
}
```text

## Common Patterns and Best Practices

### Single Responsibility

Each reactor should focus on a specific type of side effect:

- **NotificationReactor**: Handles sending notifications
- **EmailReactor**: Handles sending emails
- **IntegrationReactor**: Handles integration with external systems

### Idempotency

Reactors should be designed to be idempotent to handle retries:

```php
public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
{
    // Check if welcome email has already been sent
    $user = User::findOrFail($aggregateUuid);

    if (!$user->welcome_email_sent) {
        Mail::to($user->email)->send(new WelcomeEmail($user->name));

        // Mark welcome email as sent
        $user->welcome_email_sent = true;
        $user->save();
    }
}
```php
### Error Handling

Implement robust error handling in reactors:

```php
public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
{
    try {
        Mail::to($event->payload['email'])->send(new WelcomeEmail($event->payload['name']));
    } catch (\Exception $e) {
        // Log the error
        Log::error('Error sending welcome email', [
            'event' => $event,
            'aggregateUuid' => $aggregateUuid,
            'error' => $e->getMessage(),
        ]);

        // Rethrow the exception to trigger a retry
        throw $e;
    }
}
```text

### Logging

Log all side effects for debugging and auditing:

```php
public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
{
    // Send welcome email
    Mail::to($event->payload['email'])->send(new WelcomeEmail($event->payload['name']));

    // Log the email sending
    Log::info('Welcome email sent', [
        'user_id' => $aggregateUuid,
        'email' => $event->payload['email'],
    ]);
}
```

## Benefits and Challenges

### Benefits

1. **Separation of Concerns**: Reactors separate side effects from business logic
2. **Scalability**: Side effects can be processed asynchronously
3. **Reliability**: Failed side effects can be retried
4. **Auditability**: Side effects are logged and can be audited

### Challenges

1. **Eventual Consistency**: Side effects may lag behind the event store
2. **Error Handling**: Handling failures in side effects requires careful planning
3. **Testing**: Testing reactors can be complex due to external dependencies
4. **Idempotency**: Ensuring side effects are performed exactly once can be challenging

### Mitigation Strategies

1. **Queued Reactors**: Use queued reactors for better scalability and reliability
2. **Retry Mechanisms**: Implement retry mechanisms for failed side effects
3. **Circuit Breakers**: Use circuit breakers to prevent cascading failures
4. **Monitoring**: Monitor reactor performance and failures

## Troubleshooting

### Common Issues

<details>
<summary>Side effects not being triggered</summary>

**Symptoms:**
- Emails not being sent
- Notifications not being delivered
- External systems not being updated

**Possible Causes:**
- Reactor not registered in the configuration
- Errors in reactor event handlers
- Events not being dispatched correctly

**Solutions:**
1. Verify that the reactor is registered in `config/event-sourcing.php`
2. Check the logs for errors in reactor event handlers
3. Ensure events are being dispatched correctly
4. Check the queue worker status if using queued reactors
</details>

<details>
<summary>Duplicate side effects</summary>

**Symptoms:**
- Multiple emails being sent for the same event
- Multiple notifications being delivered for the same event
- Multiple updates to external systems for the same event

**Possible Causes:**
- Lack of idempotency in reactor event handlers
- Multiple instances of the same event being dispatched
- Queue worker retries without proper idempotency checks

**Solutions:**
1. Implement idempotency checks in reactor event handlers
2. Ensure events are dispatched exactly once
3. Use unique identifiers for side effects to prevent duplicates
</details>

<details>
<summary>Slow reactor performance</summary>

**Symptoms:**
- Side effects taking a long time to be processed
- Queue backlog growing

**Possible Causes:**
- Inefficient reactor event handlers
- External dependencies being slow
- Insufficient queue workers

**Solutions:**
1. Optimize reactor event handlers for performance
2. Implement timeouts for external dependencies
3. Increase the number of queue workers
4. Use separate queues for different types of reactors
</details>

### Solutions

For detailed solutions to common issues, refer to the [Event Sourcing Troubleshooting Guide](070-testing.md#troubleshooting).

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing Queries](060-queries.md) - Detailed documentation on query implementation
- [Event Sourcing Testing](070-testing.md) - Detailed documentation on testing event-sourced applications
- [Event Sourcing Real-time](110-real-time.md) - Integration of event sourcing with real-time functionality

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added reactor flow diagram, queue integration diagram, reactor architecture diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
