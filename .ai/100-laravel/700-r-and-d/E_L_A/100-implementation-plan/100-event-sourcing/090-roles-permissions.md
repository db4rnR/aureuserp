# Phase 1: Event Sourcing Roles and Permissions

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
- [Roles and Permissions Concept](#roles-and-permissions-concept)
  - [What are Roles and Permissions?](#what-are-roles-and-permissions)
  - [Roles and Permissions Responsibilities](#roles-and-permissions-responsibilities)
  - [Roles and Permissions Types](#roles-and-permissions-types)
- [Integration with spatie/laravel-permission](#integration-with-spatielaravel-permission)
  - [Role and Permission Models](#role-and-permission-models)
  - [Team-based Permissions](#team-based-permissions)
  - [Permission Caching](#permission-caching)
- [Implementing Roles and Permissions](#implementing-roles-and-permissions)
  - [Base Role and Permission Structure](#base-role-and-permission-structure)
  - [Role and Permission Seeding](#role-and-permission-seeding)
  - [Role and Permission Assignment](#role-and-permission-assignment)
- [Integration with Event Sourcing](#integration-with-event-sourcing)
  - [Roles and Permissions in Aggregates](#roles-and-permissions-in-aggregates)
  - [Roles and Permissions in Projections](#roles-and-permissions-in-projections)
  - [Roles and Permissions in Commands](#roles-and-permissions-in-commands)
- [Role and Permission Examples](#role-and-permission-examples)
  - [User Roles and Permissions](#user-roles-and-permissions)
  - [Team Roles and Permissions](#team-roles-and-permissions)
  - [Post Roles and Permissions](#post-roles-and-permissions)
  - [Todo Roles and Permissions](#todo-roles-and-permissions)
  - [Comment Roles and Permissions](#comment-roles-and-permissions)
- [Common Patterns and Best Practices](#common-patterns-and-best-practices)
  - [Permission Naming](#permission-naming)
  - [Role Hierarchy](#role-hierarchy)
  - [Permission Checks](#permission-checks)
  - [Permission Caching](#permission-caching-1)
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

This document provides a comprehensive guide to implementing roles and permissions in event sourcing for the Enhanced Laravel Application (ELA). Roles and permissions are used to control access to resources and actions, ensuring that users can only perform actions they are authorized to perform. This document covers the concept of roles and permissions, their implementation using `spatie/laravel-permission`, and integration with event sourcing.

## Prerequisites

- **Required Prior Steps:**
  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [Event Sourcing Projectors](030-projectors.md)
  - [Package Installation](../030-core-components/010-package-installation.md) (specifically `spatie/laravel-permission`)

- **Required Packages:**
  - `spatie/laravel-event-sourcing`: ^7.0
  - `spatie/laravel-permission`: ^5.0

- **Required Knowledge:**
  - Understanding of event sourcing principles
  - Familiarity with role-based access control (RBAC)
  - Understanding of Laravel Eloquent ORM

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Understanding roles and permissions concepts | 2 hours |
| Setting up base role and permission structure | 2 hours |
| Implementing role and permission seeding | 1 hour |
| Implementing role and permission assignment | 2 hours |
| Testing roles and permissions | 2 hours |
| **Total** | **9 hours** |
</details>

## Roles and Permissions Concept

<details>
<summary>Role Hierarchy Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    SuperAdmin[Super Administrator] --> Admin[Administrator]
    Admin --> User[User]

    TeamOwner[Team Owner] --> TeamAdmin[Team Administrator]
    TeamAdmin --> TeamMember[Team Member]

    SuperAdmin -.-> TeamOwner
    Admin -.-> TeamAdmin
    User -.-> TeamMember

    classDef global fill:#f9f9f9,stroke:#333,stroke-width:1px
    classDef team fill:#e6f7ff,stroke:#333,stroke-width:1px

    class SuperAdmin,Admin,User global
    class TeamOwner,TeamAdmin,TeamMember team
```text

For dark mode, see [Role Hierarchy (Dark Mode)](../../illustrations/mermaid/dark/role-hierarchy-dark.mmd)
</details>

### What are Roles and Permissions?

Roles and permissions are used to control access to resources and actions in an application:

- **Permissions**: Granular access controls that define what actions can be performed on what resources
- **Roles**: Collections of permissions that can be assigned to users

In the context of event sourcing, roles and permissions are used to:

1. Control who can execute commands
2. Control who can access read models
3. Control who can perform side effects
4. Provide a clear and explicit model of the access control system

Roles and permissions help ensure that users can only perform actions they are authorized to perform, preventing unauthorized access and enforcing business rules.

### Roles and Permissions Responsibilities

Roles and permissions have several key responsibilities:

1. **Access Control**: Control who can access what resources
2. **Action Control**: Control who can perform what actions
3. **Authorization**: Authorize users to perform actions based on their roles and permissions
4. **Auditing**: Provide a clear audit trail of who performed what actions

### Roles and Permissions Types

There are several types of roles and permissions that can be implemented:

1. **Global Roles and Permissions**: Apply across the entire application
2. **Team-based Roles and Permissions**: Apply within the context of a team
3. **Resource-specific Permissions**: Apply to specific resources
4. **Action-specific Permissions**: Apply to specific actions

In the ELA, we use a combination of these types to provide a flexible and powerful access control system.

## Integration with spatie/laravel-permission

### Role and Permission Models

Role and permission models are implemented using `spatie/laravel-permission`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'team_id',
    ];
}
```php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
    ];
}
```text

### Team-based Permissions

Team-based permissions are implemented using the `team_id` field in the `roles` table:

```php
// config/permission.php
return [
    'models' => [
        'role' => App\Models\Role::class,
        'permission' => App\Models\Permission::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'teams' => true,
];
```php
### Permission Caching

Permission caching is implemented to improve performance:

```php
// config/permission.php
return [
    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'model_key' => 'name',
        'store' => 'default',
    ],
];
```text

## Implementing Roles and Permissions

<details>
<summary>Permission Structure Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
classDiagram
    class Permission {
        +string name
        +string guard_name
        +int team_id
    }

    class Role {
        +string name
        +string guard_name
        +int team_id
        +givePermissionTo(Permission)
        +revokePermissionTo(Permission)
        +syncPermissions(Permission[])
    }

    class User {
        +assignRole(Role)
        +removeRole(Role)
        +syncRoles(Role[])
        +hasRole(Role)
        +hasPermissionTo(Permission)
        +hasPermissionViaRole(Permission)
    }

    class Team {
        +int id
        +string name
    }

    Role "*" -- "*" Permission : has
    User "*" -- "*" Role : has
    User "*" -- "*" Permission : has
    Team "1" -- "*" Role : has
    Team "1" -- "*" Permission : has
```php
For dark mode, see [Permission Structure (Dark Mode)](../../illustrations/mermaid/dark/permission-structure-dark.mmd)
</details>

### Base Role and Permission Structure

In the ELA, roles and permissions are implemented using a base structure:

```php
// Global roles
$roles = [
    'super_admin' => 'Super Administrator',
    'admin' => 'Administrator',
    'user' => 'User',
];

// Team roles
$teamRoles = [
    'team_owner' => 'Team Owner',
    'team_admin' => 'Team Administrator',
    'team_member' => 'Team Member',
];

// Permissions
$permissions = [
    // User permissions
    'user.view' => 'View users',
    'user.create' => 'Create users',
    'user.update' => 'Update users',
    'user.delete' => 'Delete users',

    // Team permissions
    'team.view' => 'View teams',
    'team.create' => 'Create teams',
    'team.update' => 'Update teams',
    'team.delete' => 'Delete teams',

    // Post permissions
    'post.view' => 'View posts',
    'post.create' => 'Create posts',
    'post.update' => 'Update posts',
    'post.delete' => 'Delete posts',
    'post.publish' => 'Publish posts',

    // Todo permissions
    'todo.view' => 'View todos',
    'todo.create' => 'Create todos',
    'todo.update' => 'Update todos',
    'todo.delete' => 'Delete todos',
    'todo.assign' => 'Assign todos',

    // Comment permissions
    'comment.view' => 'View comments',
    'comment.create' => 'Create comments',
    'comment.update' => 'Update comments',
    'comment.delete' => 'Delete comments',
    'comment.moderate' => 'Moderate comments',
];
```text

### Role and Permission Seeding

Roles and permissions are seeded using a database seeder:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User permissions
            'user.view',
            'user.create',
            'user.update',
            'user.delete',

            // Team permissions
            'team.view',
            'team.create',
            'team.update',
            'team.delete',

            // Post permissions
            'post.view',
            'post.create',
            'post.update',
            'post.delete',
            'post.publish',

            // Todo permissions
            'todo.view',
            'todo.create',
            'todo.update',
            'todo.delete',
            'todo.assign',

            // Comment permissions
            'comment.view',
            'comment.create',
            'comment.update',
            'comment.delete',
            'comment.moderate',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create global roles and assign permissions
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'user.view',
            'user.create',
            'user.update',
            'team.view',
            'team.create',
            'team.update',
            'post.view',
            'post.create',
            'post.update',
            'post.publish',
            'todo.view',
            'todo.create',
            'todo.update',
            'todo.assign',
            'comment.view',
            'comment.create',
            'comment.update',
            'comment.moderate',
        ]);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'user.view',
            'team.view',
            'team.create',
            'post.view',
            'post.create',
            'todo.view',
            'todo.create',
            'comment.view',
            'comment.create',
        ]);

        // Create team roles
        $teamOwner = Role::create(['name' => 'team_owner', 'team_id' => null]);
        $teamOwner->givePermissionTo([
            'team.view',
            'team.update',
            'team.delete',
            'post.view',
            'post.create',
            'post.update',
            'post.delete',
            'post.publish',
            'todo.view',
            'todo.create',
            'todo.update',
            'todo.delete',
            'todo.assign',
            'comment.view',
            'comment.create',
            'comment.update',
            'comment.delete',
            'comment.moderate',
        ]);

        $teamAdmin = Role::create(['name' => 'team_admin', 'team_id' => null]);
        $teamAdmin->givePermissionTo([
            'team.view',
            'team.update',
            'post.view',
            'post.create',
            'post.update',
            'post.publish',
            'todo.view',
            'todo.create',
            'todo.update',
            'todo.assign',
            'comment.view',
            'comment.create',
            'comment.update',
            'comment.moderate',
        ]);

        $teamMember = Role::create(['name' => 'team_member', 'team_id' => null]);
        $teamMember->givePermissionTo([
            'team.view',
            'post.view',
            'post.create',
            'post.update',
            'todo.view',
            'todo.create',
            'todo.update',
            'comment.view',
            'comment.create',
            'comment.update',
        ]);
    }
}
```php
### Role and Permission Assignment

Roles and permissions are assigned to users:

```php
// Assign global role to user
$user->assignRole('admin');

// Assign team role to user
$user->assignRole('team_admin', $team);

// Give direct permission to user
$user->givePermissionTo('post.publish');

// Give team permission to user
$user->givePermissionTo('post.publish', $team);
```text

## Integration with Event Sourcing

<details>
<summary>Event Sourcing Integration Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
sequenceDiagram
    participant User
    participant Command
    participant CommandHandler
    participant PermissionChecker
    participant Aggregate
    participant EventStore

    User->>Command: Dispatch Command
    Command->>CommandHandler: Handle Command
    CommandHandler->>PermissionChecker: Check Permission
    alt Has Permission
        PermissionChecker->>CommandHandler: Permission Granted
        CommandHandler->>Aggregate: Execute Command
        Aggregate->>EventStore: Record Events
    else No Permission
        PermissionChecker->>CommandHandler: Permission Denied
        CommandHandler->>User: Unauthorized Exception
    end
```php
For dark mode, see [Event Sourcing Integration (Dark Mode)](../../illustrations/mermaid/dark/event-sourcing-permissions-dark.mmd)
</details>

### Roles and Permissions in Aggregates

Roles and permissions are used in aggregates to enforce business rules:

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Events\Teams\TeamCreated;
use App\Events\Teams\TeamMemberAdded;
use App\Events\Teams\TeamMemberRoleChanged;
use App\Exceptions\Teams\UserNotTeamMemberException;
use App\Exceptions\Teams\UserNotTeamOwnerException;

class TeamAggregateRoot extends AggregateRoot
{
    protected string $name;
    protected string $slug;
    protected string $description;
    protected array $settings;
    protected array $members = [];

    public function createTeam(string $name, string $slug, string $description, array $settings, string $ownerId): self
    {
        $this->recordThat(new TeamCreated([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'settings' => $settings,
            'owner_id' => $ownerId,
            'created_at' => now(),
        ]));

        // Add owner as a member with the team_owner role
        $this->recordThat(new TeamMemberAdded([
            'user_id' => $ownerId,
            'role' => 'team_owner',
            'added_at' => now(),
        ]));

        return $this;
    }

    public function addMember(string $userId, string $role, string $addedBy): self
    {
        // Check if the user adding the member is a team member
        if (!isset($this->members[$addedBy])) {
            throw new UserNotTeamMemberException("User {$addedBy} is not a member of this team");
        }

        // Check if the user adding the member has the right role
        if (!in_array($this->members[$addedBy], ['team_owner', 'team_admin'])) {
            throw new UserNotTeamOwnerException("User {$addedBy} does not have permission to add members");
        }

        $this->recordThat(new TeamMemberAdded([
            'user_id' => $userId,
            'role' => $role,
            'added_by' => $addedBy,
            'added_at' => now(),
        ]));

        return $this;
    }

    public function changeMemberRole(string $userId, string $newRole, string $changedBy): self
    {
        // Check if the user changing the role is a team member
        if (!isset($this->members[$changedBy])) {
            throw new UserNotTeamMemberException("User {$changedBy} is not a member of this team");
        }

        // Check if the user changing the role has the right role
        if (!in_array($this->members[$changedBy], ['team_owner', 'team_admin'])) {
            throw new UserNotTeamOwnerException("User {$changedBy} does not have permission to change member roles");
        }

        // Check if the user being changed is a team member
        if (!isset($this->members[$userId])) {
            throw new UserNotTeamMemberException("User {$userId} is not a member of this team");
        }

        $this->recordThat(new TeamMemberRoleChanged([
            'user_id' => $userId,
            'old_role' => $this->members[$userId],
            'new_role' => $newRole,
            'changed_by' => $changedBy,
            'changed_at' => now(),
        ]));

        return $this;
    }

    protected function applyTeamCreated(TeamCreated $event): void
    {
        $this->name = $event->payload['name'];
        $this->slug = $event->payload['slug'];
        $this->description = $event->payload['description'];
        $this->settings = $event->payload['settings'];
    }

    protected function applyTeamMemberAdded(TeamMemberAdded $event): void
    {
        $this->members[$event->payload['user_id']] = $event->payload['role'];
    }

    protected function applyTeamMemberRoleChanged(TeamMemberRoleChanged $event): void
    {
        $this->members[$event->payload['user_id']] = $event->payload['new_role'];
    }
}
```text

### Roles and Permissions in Projections

Roles and permissions are used in projections to update read models:

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\Teams\TeamCreated;
use App\Events\Teams\TeamMemberAdded;
use App\Events\Teams\TeamMemberRoleChanged;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Spatie\Permission\Models\Role;

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
        ]);
    }

    public function onTeamMemberAdded(TeamMemberAdded $event, string $aggregateUuid)
    {
        $team = Team::findOrFail($aggregateUuid);
        $user = User::findOrFail($event->payload['user_id']);

        TeamMember::create([
            'team_id' => $aggregateUuid,
            'user_id' => $event->payload['user_id'],
            'role' => $event->payload['role'],
            'added_at' => $event->payload['added_at'],
        ]);

        // Assign team role to user
        $user->assignRole($event->payload['role'], $team);
    }

    public function onTeamMemberRoleChanged(TeamMemberRoleChanged $event, string $aggregateUuid)
    {
        $team = Team::findOrFail($aggregateUuid);
        $user = User::findOrFail($event->payload['user_id']);

        TeamMember::where('team_id', $aggregateUuid)
            ->where('user_id', $event->payload['user_id'])
            ->update(['role' => $event->payload['new_role']]);

        // Remove old team role from user
        $user->removeRole($event->payload['old_role'], $team);

        // Assign new team role to user
        $user->assignRole($event->payload['new_role'], $team);
    }
}
```php
### Roles and Permissions in Commands

Roles and permissions are used in commands to authorize actions:

```php
<?php

namespace App\CommandHandlers\Teams;

use App\Commands\Teams\AddTeamMemberCommand;
use App\Aggregates\TeamAggregateRoot;
use Hirethunk\Verbs\CommandHandler;

class AddTeamMemberCommandHandler extends CommandHandler
{
    public function handle(AddTeamMemberCommand $command)
    {
        // Authorize the command
        $this->authorize('addMember', ['App\Models\Team', $command->teamId]);

        // Retrieve the team aggregate
        $aggregate = TeamAggregateRoot::retrieve($command->teamId);

        // Add the member
        $aggregate->addMember($command->userId, $command->role, auth()->id());

        // Persist the events
        $aggregate->persist();

        return $this->success();
    }
}
```text

## Role and Permission Examples

### User Roles and Permissions

User roles and permissions are defined as follows:

```php
// Global roles
$roles = [
    'super_admin' => 'Super Administrator',
    'admin' => 'Administrator',
    'user' => 'User',
];

// User permissions
$permissions = [
    'user.view' => 'View users',
    'user.create' => 'Create users',
    'user.update' => 'Update users',
    'user.delete' => 'Delete users',
];
```php
User role assignments:

```php
// Assign super_admin role to user
$user->assignRole('super_admin');

// Assign admin role to user
$user->assignRole('admin');

// Assign user role to user
$user->assignRole('user');
```text

User permission checks:

```php
// Check if user has permission to view users
if ($user->can('user.view')) {
    // User can view users
}

// Check if user has permission to create users
if ($user->can('user.create')) {
    // User can create users
}

// Check if user has permission to update users
if ($user->can('user.update')) {
    // User can update users
}

// Check if user has permission to delete users
if ($user->can('user.delete')) {
    // User can delete users
}
```php
### Team Roles and Permissions

Team roles and permissions are defined as follows:

```php
// Team roles
$teamRoles = [
    'team_owner' => 'Team Owner',
    'team_admin' => 'Team Administrator',
    'team_member' => 'Team Member',
];

// Team permissions
$permissions = [
    'team.view' => 'View teams',
    'team.create' => 'Create teams',
    'team.update' => 'Update teams',
    'team.delete' => 'Delete teams',
];
```text

Team role assignments:

```php
// Assign team_owner role to user for a specific team
$user->assignRole('team_owner', $team);

// Assign team_admin role to user for a specific team
$user->assignRole('team_admin', $team);

// Assign team_member role to user for a specific team
$user->assignRole('team_member', $team);
```php
Team permission checks:

```php
// Check if user has permission to view a specific team
if ($user->hasPermissionTo('team.view', $team)) {
    // User can view the team
}

// Check if user has permission to update a specific team
if ($user->hasPermissionTo('team.update', $team)) {
    // User can update the team
}

// Check if user has permission to delete a specific team
if ($user->hasPermissionTo('team.delete', $team)) {
    // User can delete the team
}
```text

### Post Roles and Permissions

Post permissions are defined as follows:

```php
// Post permissions
$permissions = [
    'post.view' => 'View posts',
    'post.create' => 'Create posts',
    'post.update' => 'Update posts',
    'post.delete' => 'Delete posts',
    'post.publish' => 'Publish posts',
];
```php
Post permission checks:

```php
// Check if user has permission to view posts
if ($user->can('post.view')) {
    // User can view posts
}

// Check if user has permission to create posts
if ($user->can('post.create')) {
    // User can create posts
}

// Check if user has permission to update a specific post
if ($user->can('post.update', $post)) {
    // User can update the post
}

// Check if user has permission to delete a specific post
if ($user->can('post.delete', $post)) {
    // User can delete the post
}

// Check if user has permission to publish a specific post
if ($user->can('post.publish', $post)) {
    // User can publish the post
}
```text

### Todo Roles and Permissions

Todo permissions are defined as follows:

```php
// Todo permissions
$permissions = [
    'todo.view' => 'View todos',
    'todo.create' => 'Create todos',
    'todo.update' => 'Update todos',
    'todo.delete' => 'Delete todos',
    'todo.assign' => 'Assign todos',
];
```php
Todo permission checks:

```php
// Check if user has permission to view todos
if ($user->can('todo.view')) {
    // User can view todos
}

// Check if user has permission to create todos
if ($user->can('todo.create')) {
    // User can create todos
}

// Check if user has permission to update a specific todo
if ($user->can('todo.update', $todo)) {
    // User can update the todo
}

// Check if user has permission to delete a specific todo
if ($user->can('todo.delete', $todo)) {
    // User can delete the todo
}

// Check if user has permission to assign a specific todo
if ($user->can('todo.assign', $todo)) {
    // User can assign the todo
}
```text

### Comment Roles and Permissions

Comment permissions are defined as follows:

```php
// Comment permissions
$permissions = [
    'comment.view' => 'View comments',
    'comment.create' => 'Create comments',
    'comment.update' => 'Update comments',
    'comment.delete' => 'Delete comments',
    'comment.moderate' => 'Moderate comments',
];
```php
Comment permission checks:

```php
// Check if user has permission to view comments
if ($user->can('comment.view')) {
    // User can view comments
}

// Check if user has permission to create comments
if ($user->can('comment.create')) {
    // User can create comments
}

// Check if user has permission to update a specific comment
if ($user->can('comment.update', $comment)) {
    // User can update the comment
}

// Check if user has permission to delete a specific comment
if ($user->can('comment.delete', $comment)) {
    // User can delete the comment
}

// Check if user has permission to moderate comments
if ($user->can('comment.moderate')) {
    // User can moderate comments
}
```text

## Common Patterns and Best Practices

### Permission Naming

Use a consistent naming convention for permissions:

```php
// Resource-based naming convention
$permissions = [
    'resource.action' => 'Description',
];

// Examples
$permissions = [
    'user.view' => 'View users',
    'user.create' => 'Create users',
    'user.update' => 'Update users',
    'user.delete' => 'Delete users',
];
```php
### Role Hierarchy

Implement a role hierarchy to simplify permission management:

```php
// Role hierarchy
$roles = [
    'super_admin' => 'Super Administrator', // Has all permissions
    'admin' => 'Administrator', // Has most permissions
    'user' => 'User', // Has basic permissions
];

// Team role hierarchy
$teamRoles = [
    'team_owner' => 'Team Owner', // Has all team permissions
    'team_admin' => 'Team Administrator', // Has most team permissions
    'team_member' => 'Team Member', // Has basic team permissions
];
```text

### Permission Checks

Use the appropriate permission check method:

```php
// Check if user has a global permission
if ($user->can('user.view')) {
    // User can view users
}

// Check if user has a team permission
if ($user->hasPermissionTo('team.update', $team)) {
    // User can update the team
}

// Check if user has a role
if ($user->hasRole('admin')) {
    // User is an admin
}

// Check if user has a team role
if ($user->hasRole('team_admin', $team)) {
    // User is a team admin
}
```php
### Permission Caching

Use permission caching to improve performance:

```php
// config/permission.php
return [
    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'model_key' => 'name',
        'store' => 'default',
    ],
];
```text

## Benefits and Challenges

### Benefits

1. **Fine-grained Access Control**: Roles and permissions provide fine-grained control over who can access what resources and perform what actions
2. **Separation of Concerns**: Roles and permissions separate access control from business logic
3. **Flexibility**: Roles and permissions can be easily modified to adapt to changing requirements
4. **Auditability**: Roles and permissions provide a clear audit trail of who performed what actions

### Challenges

1. **Complexity**: Managing roles and permissions can become complex as the application grows
2. **Performance**: Permission checks can impact performance if not properly optimized
3. **Maintenance**: Keeping roles and permissions up to date requires ongoing maintenance

### Mitigation Strategies

1. **Role Hierarchy**: Implement a role hierarchy to simplify permission management
2. **Permission Caching**: Use permission caching to improve performance
3. **Clear Naming Conventions**: Use clear and consistent naming conventions for roles and permissions
4. **Documentation**: Document roles and permissions thoroughly to help developers understand the system

## Troubleshooting

### Common Issues

<details>
<summary>Permission checks not working</summary>

**Symptoms:**
- Permission checks return unexpected results
- Users can access resources they shouldn't be able to access
- Users can't access resources they should be able to access

**Possible Causes:**
- Incorrect permission assignments
- Missing role assignments
- Cache issues
- Incorrect permission check method

**Solutions:**
1. Verify that the user has the correct roles and permissions
2. Clear the permission cache
3. Use the correct permission check method
4. Check for typos in permission names
</details>

<details>
<summary>Team-based permissions not working</summary>

**Symptoms:**
- Team-based permission checks return unexpected results
- Users can access team resources they shouldn't be able to access
- Users can't access team resources they should be able to access

**Possible Causes:**
- Incorrect team role assignments
- Missing team permission assignments
- Incorrect permission check method
- `teams` flag not enabled in config

**Solutions:**
1. Verify that the user has the correct team roles and permissions
2. Ensure the `teams` flag is enabled in the config
3. Use the correct permission check method for team permissions
4. Check for typos in team role and permission names
</details>

<details>
<summary>Performance issues with permission checks</summary>

**Symptoms:**
- Permission checks are slow
- Application performance degrades with many permission checks

**Possible Causes:**
- Missing permission caching
- Too many permission checks
- Inefficient permission check methods

**Solutions:**
1. Enable permission caching
2. Optimize permission checks by checking roles instead of individual permissions
3. Use middleware to check permissions once per request
4. Use policy classes to centralize permission checks
</details>

### Solutions

For detailed solutions to common issues, refer to the following resources:

- [Spatie Laravel Permission Documentation](https:/spatie.be/docs/laravel-permission)
- [Laravel Authorization Documentation](https:/laravel.com/docs/authorization)
- [Role-Based Access Control (RBAC) Patterns](https:/en.wikipedia.org/wiki/Role-based_access_control)

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing State Machines](080-state-machines.md) - Detailed documentation on state machine implementation

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added role hierarchy diagram, permission structure diagram, event sourcing integration diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
