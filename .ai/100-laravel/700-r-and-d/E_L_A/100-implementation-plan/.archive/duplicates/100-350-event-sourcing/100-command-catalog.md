# Command Catalog

**Version:** 1.0.0
**Date:** 2025-05-20
**Author:** AI Assistant
**Status:** New
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [1. Introduction](#1-introduction)
- [2. User Commands](#2-user-commands)
- [3. Team Commands](#3-team-commands)
- [4. Post Commands](#4-post-commands)
- [5. Todo Commands](#5-todo-commands)
- [6. Command Validation](#6-command-validation)
- [7. Related Documents](#7-related-documents)
- [8. Version History](#8-version-history)
</details>

## 1. Introduction

This document catalogs all commands used in the Enhanced Laravel Application's event sourcing implementation. Each command is documented with its properties, purpose, and related aggregates.

<div style="background-color:#f8f9fa; padding:15px; border-radius:5px; margin:10px 0;">

### Command Naming Convention

All commands follow these naming conventions:
- Imperative verb (e.g., `Register`, `Create`, `Update`, `Delete`)
- Named after the intent, not the result
- Prefixed with the aggregate name (e.g., `User`, `Team`, `Post`, `Todo`)

</div>

## 2. User Commands

### RegisterUser

**Purpose:** Register a new user in the system.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `name` | string | User's full name | Required, min:2, max:255 |
| `email` | string | User's email address | Required, email, unique:users |
| `password` | string | User's password | Required, min:8 |

**Example:**
```php
new RegisterUser([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'secure_password',
]);
```

**Handler:**
```php
class RegisterUserHandler extends CommandHandler
{
    public function handle(RegisterUser $command)
    {
        $aggregate = UserAggregate::retrieve(Str::uuid()->toString());

        $aggregate->registerUser(
            $command->name,
            $command->email
        );

        $aggregate->persist();

        // Create user credentials separately
        $user = User::where('email', $command->email)->first();
        $user->password = Hash::make($command->password);
        $user->save();

        return $this->success(['user_id' => $aggregate->uuid()]);
    }
}
```

### ActivateUser

**Purpose:** Activate a user account.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `user_id` | string | ID of the user to activate | Required, exists:users,id |

### SuspendUser

**Purpose:** Suspend a user account.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `user_id` | string | ID of the user to suspend | Required, exists:users,id |
| `reason` | string | Reason for suspension | Required, max:255 |

### DeactivateUser

**Purpose:** Deactivate a user account.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `user_id` | string | ID of the user to deactivate | Required, exists:users,id |
| `reason` | string | Reason for deactivation | Required, max:255 |

### UpdateUserProfile

**Purpose:** Update a user's profile information.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `user_id` | string | ID of the user | Required, exists:users,id |
| `profile_data` | array | Updated profile data | Required |

## 3. Team Commands

### CreateTeam

**Purpose:** Create a new team.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `name` | string | Team name | Required, min:2, max:255 |
| `description` | string | Team description | Nullable, max:1000 |
| `parent_id` | string | ID of parent team (if any) | Nullable, exists:teams,id |
| `created_by` | string | ID of user creating the team | Required, exists:users,id |

**Example:**
```php
new CreateTeam([
    'name' => 'Engineering Team',
    'description' => 'Team responsible for product development',
    'parent_id' => null,
    'created_by' => 'user-123',
]);
```

### UpdateTeam

**Purpose:** Update team details.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `team_id` | string | ID of the team | Required, exists:teams,id |
| `name` | string | Updated team name | Required, min:2, max:255 |
| `description` | string | Updated team description | Nullable, max:1000 |
| `parent_id` | string | Updated parent team ID | Nullable, exists:teams,id |

### AddTeamMember

**Purpose:** Add a member to a team.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `team_id` | string | ID of the team | Required, exists:teams,id |
| `user_id` | string | ID of the user to add | Required, exists:users,id |
| `role` | string | Role in the team | Required, in:admin,member,guest |

## 4. Post Commands

### CreatePost

**Purpose:** Create a new post.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `title` | string | Post title | Required, min:2, max:255 |
| `content` | string | Post content | Required |
| `author_id` | string | ID of the post author | Required, exists:users,id |
| `team_id` | string | ID of the team (if any) | Nullable, exists:teams,id |

**Example:**
```php
new CreatePost([
    'title' => 'My First Post',
    'content' => 'This is the content of my first post.',
    'author_id' => 'user-123',
    'team_id' => 'team-456',
]);
```

### UpdatePost

**Purpose:** Update a post's content.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `post_id` | string | ID of the post | Required, exists:posts,id |
| `title` | string | Updated post title | Required, min:2, max:255 |
| `content` | string | Updated post content | Required |

### PublishPost

**Purpose:** Publish a post.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `post_id` | string | ID of the post | Required, exists:posts,id |

## 5. Todo Commands

### CreateTodo

**Purpose:** Create a new todo item.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `title` | string | Todo title | Required, min:2, max:255 |
| `description` | string | Todo description | Nullable, max:1000 |
| `user_id` | string | ID of the assigned user (if any) | Nullable, exists:users,id |
| `team_id` | string | ID of the team (if any) | Nullable, exists:teams,id |

**Example:**
```php
new CreateTodo([
    'title' => 'Complete documentation',
    'description' => 'Finish the event sourcing documentation',
    'user_id' => 'user-123',
    'team_id' => 'team-456',
]);
```

### CompleteTodo

**Purpose:** Mark a todo as completed.

| Property | Type | Description | Validation |
|----------|------|-------------|------------|
| `todo_id` | string | ID of the todo | Required, exists:todos,id |

## 6. Command Validation

Commands are validated before they are handled. Here's how to implement command validation:

```php
<?php

namespace App\Commands;

use Hirethunk\Verbs\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class ValidatedCommand extends Command
{
    public function validate()
    {
        $validator = Validator::make(
            $this->toArray(),
            $this->rules()
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    abstract public function rules(): array;
}
```

## 7. Related Documents

- [Event Catalog](110-event-catalog.md) - Catalog of all domain events
- [Event Sourcing Overview](010-overview.md) - Overview of event sourcing concepts
- [Aggregates](020-000-aggregates.md) - Detailed documentation on aggregate implementation

## 8. Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-20 | Initial version | AI Assistant |
