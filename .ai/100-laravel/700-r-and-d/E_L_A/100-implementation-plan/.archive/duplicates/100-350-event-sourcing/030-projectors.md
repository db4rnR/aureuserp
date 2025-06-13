# Phase 1: Event Sourcing Projectors

**Version:** 1.1.0 **Date:** 2023-11-13 **Author:** AI Assistant **Status:** Complete **Progress:** 100%

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Projector Concept](#projector-concept)
  - [What is a Projector?](#what-is-a-projector)
  - [Projector Responsibilities](#projector-responsibilities)
  - [Projector Types](#projector-types)
- [Implementing Projectors](#implementing-projectors)
  - [Base Projector Structure](#base-projector-structure)
  - [Event Handlers](#event-handlers)
  - [Projector Registration](#projector-registration)
- [Integration with spatie/laravel-event-sourcing](#integration-with-spatielaravel-event-sourcing)
  - [Projector Class](#projector-class)
  - [Event Handling Methods](#event-handling-methods)
  - [Projector Options](#projector-options)
- [Building Read Models](#building-read-models)
  - [Read Model Design](#read-model-design)
  - [Optimizing for Queries](#optimizing-for-queries)
  - [Handling Relationships](#handling-relationships)
- [Projector Examples](#projector-examples)
  - [User Projector](#user-projector)
  - [Team Projector](#team-projector)
  - [Post Projector](#post-projector)
  - [Todo Projector](#todo-projector)
  - [Comment Projector](#comment-projector)
  - [Message Projector](#message-projector)
- [Resetting Projections](#resetting-projections)
  - [When to Reset](#when-to-reset)
  - [How to Reset](#how-to-reset)
  - [Partial Resets](#partial-resets)
- [Common Patterns and Best Practices](#common-patterns-and-best-practices)
  - [Single Responsibility](#single-responsibility)
  - [Performance Optimization](#performance-optimization)
  - [Error Handling](#error-handling)
  - [Idempotency](#idempotency)
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

This document provides a comprehensive guide to implementing projectors in event sourcing for the Enhanced Laravel
Application (ELA). Projectors are responsible for building and maintaining read models based on events from the event
store. This document covers the concept of projectors, their implementation using `spatie/laravel-event-sourcing`, and
best practices for designing effective projectors.

## Prerequisites

- **Required Prior Steps:**

  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [CQRS Configuration](../030-core-components/030-cqrs-configuration.md)
  - [Package Installation](../030-core-components/010-package-installation.md) (specifically `spatie/laravel-event-sourcing`)

- **Required Packages:**

  - `spatie/laravel-event-sourcing`: ^7.0

- **Required Knowledge:**

  - Understanding of event sourcing principles
  - Familiarity with CQRS pattern
  - Understanding of Laravel Eloquent ORM

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task                                | Estimated Time             |
| ----------------------------------- | -------------------------- |
| Understanding projector concepts    | 2 hours                    |
| Setting up base projector structure | 1 hour                     |
| Implementing event handlers         | 2 hours per aggregate      |
| Optimizing read models              | 1 hour per aggregate       |
| Testing projectors                  | 1 hour per aggregate       |
| **Total**                           | **5+ hours per aggregate** |

</details>

## Projector Concept

<details>
<summary>Projector Flow Diagram</summary>

````mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart LR
    A[Event Store] --> B[Projector]
    B --> C{Event Type?}
    C -->|UserCreated| D[Handle UserCreated]
    C -->|UserUpdated| E[Handle UserUpdated]
    C -->|UserDeleted| F[Handle UserDeleted]
    D --> G[Update User Read Model]
    E --> G
    F --> G
```mermaid

For dark mode, see [Projector Flow (Dark Mode)](../../illustrations/mermaid/dark/projector-flow-dark.mmd)
</details>

### What is a Projector?

A projector is a component in event sourcing that processes events from the event store and builds read models optimized for querying. Projectors are responsible for:

1. Listening for specific events
2. Processing those events to update read models
3. Maintaining the state of read models based on the event history

Projectors are a key part of the CQRS pattern, separating the read side (queries) from the write side (commands).

### Projector Responsibilities

Projectors have several key responsibilities:

1. **Event Processing**: Processing events from the event store
2. **Read Model Building**: Creating and updating read models based on events
3. **Query Optimization**: Structuring read models for efficient querying
4. **State Maintenance**: Ensuring read models reflect the current state of the system

### Projector Types

There are several types of projectors that can be implemented:

1. **Model Projectors**: Build and maintain Eloquent models
2. **View Projectors**: Build and maintain views or materialized views in the database
3. **Cache Projectors**: Build and maintain cached data structures
4. **Search Projectors**: Build and maintain search indexes

In the ELA, we primarily use Model Projectors to build and maintain Eloquent models.

## Implementing Projectors

### Base Projector Structure

In the ELA, projectors are implemented as classes that extend `Spatie\EventSourcing\EventHandlers\Projectors\Projector`:

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector
{
    // Event handlers
}
```php
### Event Handlers

Event handlers are methods that process specific events:

```php
public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
{
    User::create([
        'id' => $aggregateUuid,
        'name' => $event->payload['name'],
        'email' => $event->payload['email'],
        'profile' => $event->payload['profile'],
        'state' => PendingActivation::class,
    ]);
}

public function onUserActivated(UserActivated $event, string $aggregateUuid)
{
    $user = User::findOrFail($aggregateUuid);
    $user->state = new Active();
    $user->save();
}
```php

### Projector Registration

Projectors are registered in the `config/event-sourcing.php` configuration file:

```php
'projectors' => [
    \App\Projectors\UserProjector::class,
    \App\Projectors\TeamProjector::class,
    \App\Projectors\PostProjector::class,
    \App\Projectors\TodoProjector::class,
    \App\Projectors\CommentProjector::class,
    \App\Projectors\MessageProjector::class,
],
```php
Alternatively, projectors can be auto-discovered by configuring the auto-discovery directories:

```php
'auto_discover_projectors_and_reactors' => [
    app()->path(),
],
```php

## Integration with spatie/laravel-event-sourcing

### Projector Class

The `Projector` class from `spatie/laravel-event-sourcing` provides the foundation for implementing projectors:

```php
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector
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
```php

The method receives the event object and the aggregate UUID as parameters.

### Projector Options

Projectors can be configured with various options:

```php
class UserProjector extends Projector
{
    public function resetState()
    {
        // Reset the projector state
        User::query()->delete();
    }

    public function streamEventsBy(): array
    {
        return [
            UserRegistered::class,
            UserActivated::class,
            UserProfileUpdated::class,
            // ...
        ];
    }
}
```mermaid
## Building Read Models

<details>
<summary>Read Model Architecture Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
classDiagram
    class EventStore {
        +StoredEvent[] events
        +persist(Event event)
        +retrieveAll()
        +retrieveAllForAggregate(string uuid)
    }

    class Projector {
        +onUserCreated(UserCreated event)
        +onUserUpdated(UserUpdated event)
        +onUserDeleted(UserDeleted event)
        +reset()
    }

    class ReadModel {
        +id
        +attributes
        +create()
        +update()
        +delete()
    }

    EventStore --> Projector: events
    Projector --> ReadModel: updates
```mermaid

For dark mode, see [Read Model Architecture (Dark Mode)](../../illustrations/mermaid/dark/read-model-architecture-dark.mmd)
</details>

### Read Model Design

Read models should be designed for efficient querying:

1. **Denormalization**: Denormalize data to reduce joins
2. **Indexing**: Add indexes to frequently queried columns
3. **Caching**: Use caching for frequently accessed data
4. **Materialized Views**: Consider using materialized views for complex queries

### Optimizing for Queries

<details>
<summary>Read Model Optimization Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[Event Store] --> B[Projector]
    B --> C[Base Read Model]
    B --> D[Search Optimized Model]
    B --> E[Reporting Model]
    B --> F[API Model]
    C --> G[Basic Queries]
    D --> H[Full-text Search]
    E --> I[Analytics & Reports]
    F --> J[API Responses]
```mermaid
For dark mode, see [Read Model Optimization (Dark Mode)](../../illustrations/mermaid/dark/read-model-optimization-dark.mmd)
</details>

Read models should be optimized for the specific queries they need to support:

```php
// Example: Optimizing for user search
public function onUserProfileUpdated(UserProfileUpdated $event, string $aggregateUuid)
{
    $user = User::findOrFail($aggregateUuid);
    $user->name = $event->payload['name'];
    $user->profile = $event->payload['profile'];

    // Extract searchable fields for optimization
    $user->search_vector = implode(' ', [
        $user->name,
        $user->email,
        $user->profile['bio'] ?? '',
        $user->profile['location'] ?? '',
    ]);

    $user->save();
}
```php

### Handling Relationships

Projectors need to handle relationships between read models:

```php
public function onTeamMemberAdded(TeamMemberAdded $event, string $aggregateUuid)
{
    $team = Team::findOrFail($aggregateUuid);
    $user = User::findOrFail($event->payload['user_id']);

    // Create the relationship
    TeamMember::create([
        'team_id' => $team->id,
        'user_id' => $user->id,
        'role' => $event->payload['role'],
        'added_at' => $event->payload['added_at'],
    ]);
}
```php
## Projector Examples

### User Projector

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\Users\UserRegistered;
use App\Events\Users\UserActivated;
use App\Events\Users\UserProfileUpdated;
use App\Events\Users\UserEmailChanged;
use App\Events\Users\UserDeactivated;
use App\Events\Users\UserSuspended;
use App\Events\Users\UserUnsuspended;
use App\Events\Users\UserArchived;
use App\Models\User;
use App\States\User\PendingActivation;
use App\States\User\Active;
use App\States\User\Suspended;
use App\States\User\Deactivated;
use App\States\User\Archived;

class UserProjector extends Projector
{
    public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
    {
        User::create([
            'id' => $aggregateUuid,
            'name' => $event->payload['name'],
            'email' => $event->payload['email'],
            'profile' => $event->payload['profile'],
            'state' => PendingActivation::class,
        ]);
    }

    public function onUserActivated(UserActivated $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);
        $user->state = new Active();
        $user->save();
    }

    public function onUserProfileUpdated(UserProfileUpdated $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);
        $user->name = $event->payload['name'];
        $user->profile = $event->payload['profile'];
        $user->save();
    }

    public function onUserEmailChanged(UserEmailChanged $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);
        $user->email = $event->payload['new_email'];
        $user->save();
    }

    public function onUserDeactivated(UserDeactivated $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);
        $user->state = new Deactivated();

        // Record the status with reason
        $user->setStatus('deactivated', $event->payload['reason']);

        $user->save();
    }

    public function onUserSuspended(UserSuspended $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);
        $user->state = new Suspended();

        // Record the status with reason
        $user->setStatus('suspended', $event->payload['reason']);

        $user->save();
    }

    public function onUserUnsuspended(UserUnsuspended $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);
        $user->state = new Active();

        // Record the status
        $user->setStatus('active');

        $user->save();
    }

    public function onUserArchived(UserArchived $event, string $aggregateUuid)
    {
        $user = User::findOrFail($aggregateUuid);
        $user->state = new Archived();

        // Record the status with reason
        $user->setStatus('archived', $event->payload['reason']);

        // Trigger soft delete
        $user->delete();

        $user->save();
    }
}
```php

### Team Projector

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\Teams\TeamCreated;
use App\Events\Teams\TeamUpdated;
use App\Events\Teams\TeamMemberAdded;
use App\Events\Teams\TeamMemberRemoved;
use App\Events\Teams\TeamMemberRoleChanged;
use App\Events\Teams\TeamArchived;
use App\Models\Team;
use App\Models\TeamMember;
use App\States\Team\Forming;
use App\States\Team\Active;
use App\States\Team\Archived;

class TeamProjector extends Projector
{
    public function onTeamCreated(TeamCreated $event, string $aggregateUuid)
    {
        Team::create([
            'id' => $aggregateUuid,
            'name' => $event->payload['name'],
            'slug' => $event->payload['slug'],
            'description' => $event->payload['description'],
            'settings' => $event->payload['settings'],
            'state' => Forming::class,
        ]);
    }

    public function onTeamUpdated(TeamUpdated $event, string $aggregateUuid)
    {
        $team = Team::findOrFail($aggregateUuid);
        $team->name = $event->payload['name'];
        $team->slug = $event->payload['slug'];
        $team->description = $event->payload['description'];
        $team->settings = $event->payload['settings'];
        $team->save();
    }

    public function onTeamMemberAdded(TeamMemberAdded $event, string $aggregateUuid)
    {
        $team = Team::findOrFail($aggregateUuid);

        TeamMember::create([
            'team_id' => $aggregateUuid,
            'user_id' => $event->payload['user_id'],
            'role' => $event->payload['role'],
            'added_at' => $event->payload['added_at'],
        ]);

        // If this is the first member or the team is in forming state, activate the team
        if ($team->members()->count() === 1 && $team->state->equals(new Forming())) {
            $team->state = new Active();
            $team->save();
        }
    }

    public function onTeamMemberRemoved(TeamMemberRemoved $event, string $aggregateUuid)
    {
        TeamMember::where('team_id', $aggregateUuid)
            ->where('user_id', $event->payload['user_id'])
            ->delete();
    }

    public function onTeamMemberRoleChanged(TeamMemberRoleChanged $event, string $aggregateUuid)
    {
        TeamMember::where('team_id', $aggregateUuid)
            ->where('user_id', $event->payload['user_id'])
            ->update(['role' => $event->payload['new_role']]);
    }

    public function onTeamArchived(TeamArchived $event, string $aggregateUuid)
    {
        $team = Team::findOrFail($aggregateUuid);
        $team->state = new Archived();

        // Record the status with reason
        $team->setStatus('archived', $event->payload['reason']);

        // Trigger soft delete
        $team->delete();

        $team->save();
    }
}
```php
### Post Projector

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\Posts\PostCreated;
use App\Events\Posts\PostUpdated;
use App\Events\Posts\PostPublished;
use App\Events\Posts\PostUnpublished;
use App\Events\Posts\PostScheduled;
use App\Events\Posts\PostArchived;
use App\Events\Posts\PostTagAdded;
use App\Events\Posts\PostTagRemoved;
use App\Models\Post;
use App\States\Post\Draft;
use App\States\Post\PendingReview;
use App\States\Post\Published;
use App\States\Post\Scheduled;
use App\States\Post\Archived;

class PostProjector extends Projector
{
    public function onPostCreated(PostCreated $event, string $aggregateUuid)
    {
        Post::create([
            'id' => $aggregateUuid,
            'title' => $event->payload['title'],
            'slug' => $event->payload['slug'],
            'content' => $event->payload['content'],
            'excerpt' => $event->payload['excerpt'],
            'author_id' => $event->payload['author_id'],
            'team_id' => $event->payload['team_id'],
            'meta' => $event->payload['meta'],
            'state' => Draft::class,
        ]);

        // Add tags
        $post = Post::findOrFail($aggregateUuid);
        $post->syncTags($event->payload['tags']);
    }

    public function onPostUpdated(PostUpdated $event, string $aggregateUuid)
    {
        $post = Post::findOrFail($aggregateUuid);
        $post->title = $event->payload['title'];
        $post->slug = $event->payload['slug'];
        $post->content = $event->payload['content'];
        $post->excerpt = $event->payload['excerpt'];
        $post->meta = $event->payload['meta'];
        $post->save();
    }

    public function onPostPublished(PostPublished $event, string $aggregateUuid)
    {
        $post = Post::findOrFail($aggregateUuid);
        $post->state = new Published();
        $post->published_at = $event->payload['published_at'];
        $post->scheduled_at = null;
        $post->save();

        // Record the status
        $post->setStatus('published');
    }

    public function onPostUnpublished(PostUnpublished $event, string $aggregateUuid)
    {
        $post = Post::findOrFail($aggregateUuid);
        $post->state = new Draft();
        $post->published_at = null;
        $post->save();

        // Record the status
        $post->setStatus('draft');
    }

    public function onPostScheduled(PostScheduled $event, string $aggregateUuid)
    {
        $post = Post::findOrFail($aggregateUuid);
        $post->state = new Scheduled();
        $post->scheduled_at = $event->payload['scheduled_at'];
        $post->save();

        // Record the status
        $post->setStatus('scheduled');
    }

    public function onPostArchived(PostArchived $event, string $aggregateUuid)
    {
        $post = Post::findOrFail($aggregateUuid);
        $post->state = new Archived();

        // Record the status with reason
        $post->setStatus('archived', $event->payload['reason']);

        // Trigger soft delete
        $post->delete();

        $post->save();
    }

    public function onPostTagAdded(PostTagAdded $event, string $aggregateUuid)
    {
        $post = Post::findOrFail($aggregateUuid);
        $post->attachTag($event->payload['tag']);
    }

    public function onPostTagRemoved(PostTagRemoved $event, string $aggregateUuid)
    {
        $post = Post::findOrFail($aggregateUuid);
        $post->detachTag($event->payload['tag']);
    }
}
```php

### Todo Projector

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\Todos\TodoCreated;
use App\Events\Todos\TodoUpdated;
use App\Events\Todos\TodoAssigned;
use App\Events\Todos\TodoStarted;
use App\Events\Todos\TodoCompleted;
use App\Events\Todos\TodoCancelled;
use App\Models\Todo;
use App\States\Todo\Pending;
use App\States\Todo\InProgress;
use App\States\Todo\Completed;
use App\States\Todo\Cancelled;

class TodoProjector extends Projector
{
    public function onTodoCreated(TodoCreated $event, string $aggregateUuid)
    {
        Todo::create([
            'id' => $aggregateUuid,
            'title' => $event->payload['title'],
            'description' => $event->payload['description'],
            'team_id' => $event->payload['team_id'],
            'user_id' => $event->payload['user_id'],
            'due_date' => $event->payload['due_date'],
            'priority' => $event->payload['priority'],
            'state' => Pending::class,
        ]);

        // Add tags
        $todo = Todo::findOrFail($aggregateUuid);
        $todo->syncTags($event->payload['tags']);
    }

    public function onTodoUpdated(TodoUpdated $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);
        $todo->title = $event->payload['title'];
        $todo->description = $event->payload['description'];
        $todo->due_date = $event->payload['due_date'];
        $todo->priority = $event->payload['priority'];
        $todo->save();

        // Update tags
        $todo->syncTags($event->payload['tags']);
    }

    public function onTodoAssigned(TodoAssigned $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);
        $todo->user_id = $event->payload['user_id'];
        $todo->save();

        // Record the assignment
        $todo->setStatus('assigned', null, ['user_id' => $event->payload['user_id']]);
    }

    public function onTodoStarted(TodoStarted $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);
        $todo->state = new InProgress();
        $todo->save();

        // Record the status
        $todo->setStatus('in_progress');
    }

    public function onTodoCompleted(TodoCompleted $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);
        $todo->state = new Completed();
        $todo->completed_at = $event->payload['completed_at'];
        $todo->save();

        // Record the status
        $todo->setStatus('completed');
    }

    public function onTodoCancelled(TodoCancelled $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);
        $todo->state = new Cancelled();

        // Record the status with reason
        $todo->setStatus('cancelled', $event->payload['reason']);

        $todo->save();
    }
}
```php
### Comment Projector

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\Comments\CommentCreated;
use App\Events\Comments\CommentUpdated;
use App\Events\Comments\CommentDeleted;
use App\Events\Comments\CommentApproved;
use App\Events\Comments\CommentRejected;
use App\Events\Comments\ReactionAdded;
use App\Events\Comments\ReactionRemoved;
use App\Models\Comment;
use App\Models\CommentReaction;
use App\States\Comment\Pending;
use App\States\Comment\Approved;
use App\States\Comment\Rejected;
use App\States\Comment\Deleted;

class CommentProjector extends Projector
{
    public function onCommentCreated(CommentCreated $event, string $aggregateUuid)
    {
        Comment::create([
            'id' => $aggregateUuid,
            'content' => $event->payload['content'],
            'user_id' => $event->payload['user_id'],
            'commentable_type' => $event->payload['commentable_type'],
            'commentable_id' => $event->payload['commentable_id'],
            'parent_id' => $event->payload['parent_id'],
            'state' => Pending::class,
        ]);
    }

    public function onCommentUpdated(CommentUpdated $event, string $aggregateUuid)
    {
        $comment = Comment::findOrFail($aggregateUuid);
        $comment->content = $event->payload['content'];
        $comment->save();
    }

    public function onCommentDeleted(CommentDeleted $event, string $aggregateUuid)
    {
        $comment = Comment::findOrFail($aggregateUuid);
        $comment->state = new Deleted();

        // Record the status
        $comment->setStatus('deleted');

        // Trigger soft delete
        $comment->delete();

        $comment->save();
    }

    public function onCommentApproved(CommentApproved $event, string $aggregateUuid)
    {
        $comment = Comment::findOrFail($aggregateUuid);
        $comment->state = new Approved();
        $comment->approved_at = $event->payload['approved_at'];
        $comment->save();

        // Record the status
        $comment->setStatus('approved');
    }

    public function onCommentRejected(CommentRejected $event, string $aggregateUuid)
    {
        $comment = Comment::findOrFail($aggregateUuid);
        $comment->state = new Rejected();
        $comment->rejected_at = $event->payload['rejected_at'];
        $comment->rejection_reason = $event->payload['reason'];
        $comment->save();

        // Record the status with reason
        $comment->setStatus('rejected', $event->payload['reason']);
    }

    public function onReactionAdded(ReactionAdded $event, string $aggregateUuid)
    {
        CommentReaction::create([
            'comment_id' => $aggregateUuid,
            'user_id' => $event->payload['user_id'],
            'type' => $event->payload['type'],
            'created_at' => $event->payload['added_at'],
        ]);
    }

    public function onReactionRemoved(ReactionRemoved $event, string $aggregateUuid)
    {
        CommentReaction::where('comment_id', $aggregateUuid)
            ->where('user_id', $event->payload['user_id'])
            ->where('type', $event->payload['type'])
            ->delete();
    }
}
```php

### Message Projector

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\Messages\ConversationCreated;
use App\Events\Messages\MessageSent;
use App\Events\Messages\MessageEdited;
use App\Events\Messages\MessageDeleted;
use App\Events\Messages\ParticipantAdded;
use App\Events\Messages\ParticipantRemoved;
use App\Events\Messages\MessageRead;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\ConversationParticipant;
use App\Models\MessageReadReceipt;

class MessageProjector extends Projector
{
    public function onConversationCreated(ConversationCreated $event, string $aggregateUuid)
    {
        Conversation::create([
            'id' => $aggregateUuid,
            'type' => $event->payload['type'],
            'name' => $event->payload['name'],
            'creator_id' => $event->payload['creator_id'],
            'created_at' => $event->payload['created_at'],
        ]);
    }

    public function onMessageSent(MessageSent $event, string $aggregateUuid)
    {
        $message = Message::create([
            'id' => $event->payload['message_id'],
            'conversation_id' => $aggregateUuid,
            'sender_id' => $event->payload['sender_id'],
            'content' => $event->payload['content'],
            'attachments' => $event->payload['attachments'] ?? null,
            'created_at' => $event->payload['sent_at'],
        ]);

        // Mark as read by sender
        MessageReadReceipt::create([
            'message_id' => $message->id,
            'user_id' => $event->payload['sender_id'],
            'read_at' => $event->payload['sent_at'],
        ]);
    }

    public function onMessageEdited(MessageEdited $event, string $aggregateUuid)
    {
        Message::where('id', $event->payload['message_id'])
            ->where('conversation_id', $aggregateUuid)
            ->update([
                'content' => $event->payload['content'],
                'edited_at' => $event->payload['edited_at'],
            ]);
    }

    public function onMessageDeleted(MessageDeleted $event, string $aggregateUuid)
    {
        Message::where('id', $event->payload['message_id'])
            ->where('conversation_id', $aggregateUuid)
            ->update([
                'deleted_at' => $event->payload['deleted_at'],
            ]);
    }

    public function onParticipantAdded(ParticipantAdded $event, string $aggregateUuid)
    {
        ConversationParticipant::create([
            'conversation_id' => $aggregateUuid,
            'user_id' => $event->payload['user_id'],
            'added_by' => $event->payload['added_by'],
            'added_at' => $event->payload['added_at'],
        ]);
    }

    public function onParticipantRemoved(ParticipantRemoved $event, string $aggregateUuid)
    {
        ConversationParticipant::where('conversation_id', $aggregateUuid)
            ->where('user_id', $event->payload['user_id'])
            ->update([
                'removed_by' => $event->payload['removed_by'],
                'removed_at' => $event->payload['removed_at'],
            ]);
    }

    public function onMessageRead(MessageRead $event, string $aggregateUuid)
    {
        MessageReadReceipt::updateOrCreate(
            [
                'message_id' => $event->payload['message_id'],
                'user_id' => $event->payload['user_id'],
            ],
            [
                'read_at' => $event->payload['read_at'],
            ]
        );
    }
}
```php
## Resetting Projections

### When to Reset

Projections may need to be reset in the following scenarios:

1. **Schema Changes**: When the schema of a read model changes
2. **Bug Fixes**: When bugs in projectors are fixed
3. **New Projectors**: When new projectors are added
4. **Data Corruption**: When read models become corrupted

### How to Reset

Projections can be reset using the `event-sourcing:replay` Artisan command:

```bash
# Phase 1: Reset all projectors
php artisan event-sourcing:replay

# Phase 1: Reset a specific projector
php artisan event-sourcing:replay "App\Projectors\UserProjector"
```bash

### Partial Resets

Partial resets can be performed by specifying a starting event number:

```bash
php artisan event-sourcing:replay --from=1000
```php
## Common Patterns and Best Practices

### Single Responsibility

Each projector should focus on a single read model or a closely related set of read models:

- **UserProjector**: Handles user-related read models
- **TeamProjector**: Handles team-related read models
- **PostProjector**: Handles post-related read models

### Performance Optimization

Optimize projectors for performance:

1. **Batch Processing**: Process events in batches when possible
2. **Indexing**: Ensure read models have appropriate indexes
3. **Caching**: Use caching for frequently accessed data
4. **Queued Projectors**: Consider using queued projectors for non-critical read models

### Error Handling

Implement robust error handling in projectors:

```php
public function onUserRegistered(UserRegistered $event, string $aggregateUuid)
{
    try {
        User::create([
            'id' => $aggregateUuid,
            'name' => $event->payload['name'],
            'email' => $event->payload['email'],
            'profile' => $event->payload['profile'],
            'state' => PendingActivation::class,
        ]);
    } catch (\Exception $e) {
        // Log the error
        \Log::error('Error in UserProjector::onUserRegistered', [
            'event' => $event,
            'aggregateUuid' => $aggregateUuid,
            'error' => $e->getMessage(),
        ]);

        // Rethrow the exception to prevent the projector from marking the event as processed
        throw $e;
    }
}
```php

### Idempotency

Ensure projectors are idempotent to handle replays:

```php
public function onTeamMemberAdded(TeamMemberAdded $event, string $aggregateUuid)
{
    // Check if the team member already exists
    $exists = TeamMember::where('team_id', $aggregateUuid)
        ->where('user_id', $event->payload['user_id'])
        ->exists();

    if (!$exists) {
        TeamMember::create([
            'team_id' => $aggregateUuid,
            'user_id' => $event->payload['user_id'],
            'role' => $event->payload['role'],
            'added_at' => $event->payload['added_at'],
        ]);
    }
}
```markdown
## Benefits and Challenges

### Benefits

1. **Optimized Read Models**: Read models can be optimized for specific queries
2. **Scalability**: Read and write sides can be scaled independently
3. **Flexibility**: Read models can be easily rebuilt from the event store
4. **Separation of Concerns**: Clear separation between write and read models

### Challenges

1. **Eventual Consistency**: Read models may lag behind the event store
2. **Complexity**: Managing multiple read models adds complexity
3. **Performance**: Processing large numbers of events can be slow
4. **Maintenance**: Keeping projectors up to date with changing requirements

### Mitigation Strategies

1. **Snapshots**: Use snapshots to improve performance
2. **Caching**: Cache read models to reduce database load
3. **Queued Projectors**: Use queued projectors for non-critical read models
4. **Monitoring**: Monitor projector performance and lag

## Troubleshooting

### Common Issues

<details>
<summary>Projections not updating</summary>

**Symptoms:**

- Read models are not reflecting the latest events
- Some events seem to be missing from the read models

**Possible Causes:**

- Projector not registered in the configuration
- Errors in projector event handlers
- Events not being dispatched correctly

**Solutions:**

1. Verify that the projector is registered in `config/event-sourcing.php`
2. Check the logs for errors in projector event handlers
3. Ensure events are being dispatched correctly
4. Reset the projector using `php artisan event-sourcing:replay`
</details>

<details>
<summary>Slow projector performance</summary>

**Symptoms:**

- Projectors take a long time to process events
- Read models lag significantly behind the event store

**Possible Causes:**

- Inefficient event handlers
- Missing indexes on read models
- Large number of events to process

**Solutions:**

1. Optimize event handlers for performance
2. Add appropriate indexes to read models
3. Consider using snapshots for aggregates with many events
4. Use queued projectors for non-critical read models
</details>

<details>
<summary>Errors during projection reset</summary>

**Symptoms:**

- Errors when running `php artisan event-sourcing:replay`
- Incomplete or corrupted read models after reset

**Possible Causes:**

- Bugs in projector event handlers
- Missing or changed event properties
- Database constraints or validation errors

**Solutions:**

1. Fix bugs in projector event handlers
2. Ensure event handlers handle missing or changed properties gracefully
3. Check database constraints and validation rules
4. Implement proper error handling in projectors
</details>

### Solutions

For detailed solutions to common issues, refer to the
[Event Sourcing Troubleshooting Guide](070-testing.md#troubleshooting).

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [Event Sourcing Reactors](040-reactors.md) - Detailed documentation on reactor implementation
- [Event Sourcing Queries](060-queries.md) - Detailed documentation on query implementation
- [Event Sourcing Testing](070-testing.md) - Detailed documentation on testing event-sourced applications

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date       | Changes                                                                                                                                | Author       |
| ------- | ---------- | -------------------------------------------------------------------------------------------------------------------------------------- | ------------ |
| 1.1.0   | 2025-05-18 | Added projector flow diagram, read model architecture diagram, read model optimization diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0   | 2025-05-18 | Initial version                                                                                                                        | AI Assistant |

</details>
````
