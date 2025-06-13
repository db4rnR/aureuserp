# Event Catalog

**Version:** 1.0.0
**Date:** 2025-05-20
**Author:** AI Assistant
**Status:** New
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [1. Introduction](#1-introduction)
- [2. User Events](#2-user-events)
- [3. Team Events](#3-team-events)
- [4. Post Events](#4-post-events)
- [5. Todo Events](#5-todo-events)
- [6. Event Schema Evolution](#6-event-schema-evolution)
- [7. Related Documents](#7-related-documents)
- [8. Version History](#8-version-history)
</details>

## 1. Introduction

This document catalogs all domain events used in the Enhanced Laravel Application's event sourcing implementation. Each event is documented with its properties, purpose, and related aggregates.

<div style="background-color:#f8f9fa; padding:15px; border-radius:5px; margin:10px 0;">

### Event Naming Convention

All events follow these naming conventions:
- Past tense verb (e.g., `Created`, `Updated`, `Deleted`)
- Named after what happened, not what caused it
- Prefixed with the aggregate name (e.g., `User`, `Team`, `Post`, `Todo`)

</div>

## 2. User Events

### UserRegistered

**Purpose:** Indicates that a new user has registered in the system.

| Property | Type | Description |
|----------|------|-------------|
| `name` | string | User's full name |
| `email` | string | User's email address |
| `status` | string | Initial status (usually 'pending_activation') |

**Example:**
```php
new UserRegistered([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'status' => 'pending_activation',
]);
```

### UserActivated

**Purpose:** Indicates that a user account has been activated.

| Property | Type | Description |
|----------|------|-------------|
| `activated_at` | datetime | When the activation occurred |

### UserSuspended

**Purpose:** Indicates that a user account has been suspended.

| Property | Type | Description |
|----------|------|-------------|
| `reason` | string | Reason for suspension |
| `suspended_at` | datetime | When the suspension occurred |

### UserDeactivated

**Purpose:** Indicates that a user account has been deactivated.

| Property | Type | Description |
|----------|------|-------------|
| `reason` | string | Reason for deactivation |
| `deactivated_at` | datetime | When the deactivation occurred |

### UserProfileUpdated

**Purpose:** Indicates that a user's profile information has been updated.

| Property | Type | Description |
|----------|------|-------------|
| `profile_data` | array | Updated profile data |

## 3. Team Events

### TeamCreated

**Purpose:** Indicates that a new team has been created.

| Property | Type | Description |
|----------|------|-------------|
| `name` | string | Team name |
| `description` | string | Team description |
| `parent_id` | string | ID of parent team (if any) |
| `created_by` | string | ID of user who created the team |
| `status` | string | Initial status (usually 'forming') |

### TeamUpdated

**Purpose:** Indicates that team details have been updated.

| Property | Type | Description |
|----------|------|-------------|
| `name` | string | Updated team name |
| `description` | string | Updated team description |
| `parent_id` | string | Updated parent team ID |

### TeamMemberAdded

**Purpose:** Indicates that a member has been added to a team.

| Property | Type | Description |
|----------|------|-------------|
| `user_id` | string | ID of the added user |
| `role` | string | Role in the team |
| `added_at` | datetime | When the member was added |

### TeamMemberRemoved

**Purpose:** Indicates that a member has been removed from a team.

| Property | Type | Description |
|----------|------|-------------|
| `user_id` | string | ID of the removed user |
| `removed_at` | datetime | When the member was removed |

### TeamMemberRoleChanged

**Purpose:** Indicates that a member's role in a team has changed.

| Property | Type | Description |
|----------|------|-------------|
| `user_id` | string | ID of the user |
| `old_role` | string | Previous role |
| `new_role` | string | New role |

## 4. Post Events

### PostCreated

**Purpose:** Indicates that a new post has been created.

| Property | Type | Description |
|----------|------|-------------|
| `title` | string | Post title |
| `content` | string | Post content |
| `author_id` | string | ID of the post author |
| `team_id` | string | ID of the team (if any) |
| `status` | string | Initial status (usually 'draft') |

**Example:**
```php
new PostCreated([
    'title' => 'My First Post',
    'content' => 'This is the content of my first post.',
    'author_id' => 'user-123',
    'team_id' => 'team-456',
    'status' => 'draft',
]);
```

### PostUpdated

**Purpose:** Indicates that a post's content has been updated.

| Property | Type | Description |
|----------|------|-------------|
| `title` | string | Updated post title |
| `content` | string | Updated post content |

### PostPublished

**Purpose:** Indicates that a post has been published.

| Property | Type | Description |
|----------|------|-------------|
| `published_at` | datetime | When the post was published |

### PostScheduled

**Purpose:** Indicates that a post has been scheduled for publication.

| Property | Type | Description |
|----------|------|-------------|
| `scheduled_for` | datetime | When the post is scheduled to be published |

## 5. Todo Events

### TodoCreated

**Purpose:** Indicates that a new todo item has been created.

| Property | Type | Description |
|----------|------|-------------|
| `title` | string | Todo title |
| `description` | string | Todo description |
| `user_id` | string | ID of the assigned user (if any) |
| `team_id` | string | ID of the team (if any) |
| `status` | string | Initial status (usually 'pending') |

**Example:**
```php
new TodoCreated([
    'title' => 'Complete documentation',
    'description' => 'Finish the event sourcing documentation',
    'user_id' => 'user-123',
    'team_id' => 'team-456',
    'status' => 'pending',
]);
```

### TodoAssigned

**Purpose:** Indicates that a todo has been assigned to a user.

| Property | Type | Description |
|----------|------|-------------|
| `user_id` | string | ID of the assigned user |
| `assigned_at` | datetime | When the todo was assigned |

### TodoStarted

**Purpose:** Indicates that work on a todo has started.

| Property | Type | Description |
|----------|------|-------------|
| `started_at` | datetime | When work on the todo started |

### TodoCompleted

**Purpose:** Indicates that a todo has been completed.

| Property | Type | Description |
|----------|------|-------------|
| `completed_at` | datetime | When the todo was completed |

## 6. Event Schema Evolution

As the application evolves, event schemas may need to change. Here are strategies for handling event schema evolution:

<div style="background-color:#f8f9fa; padding:15px; border-radius:5px; margin:10px 0;">

### Event Versioning Strategies

1. **Upcasting**: Transform old event versions to new versions when they're loaded from the store
2. **Event Versioning**: Create new event classes for significant changes (e.g., `UserRegisteredV2`)
3. **Backward Compatibility**: Ensure new event handlers can process old event versions
4. **Forward Compatibility**: Design events to ignore unknown properties

</div>

### Implementing Upcasters

Upcasters transform events from one version to another:

```php
<?php

namespace App\Upcasters;

use Spatie\EventSourcing\EventSerializers\EventSerializer;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;
use Spatie\EventSourcing\Upcasters\Upcaster;

class UserRegisteredUpcaster implements Upcaster
{
    public function canUpcast(array $storedEvent): bool
    {
        return $storedEvent['event_class'] === 'App\\Events\\UserRegistered' &&
               $storedEvent['event_version'] < 2;
    }

    public function upcast(array $storedEvent): array
    {
        $storedEvent['event_version'] = 2;

        $eventData = json_decode($storedEvent['event_properties'], true);

        // Add new property with default value
        if (!isset($eventData['preferences'])) {
            $eventData['preferences'] = [
                'notifications' => true,
                'theme' => 'light',
            ];
        }

        $storedEvent['event_properties'] = json_encode($eventData);

        return $storedEvent;
    }
}
```

## 7. Related Documents

- [Command Catalog](100-command-catalog.md) - Catalog of all commands
- [Event Sourcing Overview](010-overview.md) - Overview of event sourcing concepts
- [Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Reactors](040-reactors.md) - Detailed documentation on reactor implementation

## 8. Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-20 | Initial version | AI Assistant |
