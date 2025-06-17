# Phase 1: Team Aggregate

**Version:** 1.1.0 **Date:** 2023-11-13 **Author:** AI Assistant **Status:** Complete **Progress:** 100%

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Team Aggregate Structure](#team-aggregate-structure)
  - [State Properties](#state-properties)
  - [Team States](#team-states)
- [Team Commands](#team-commands)
  - [CreateTeam Command](#createteam-command)
  - [UpdateTeam Command](#updateteam-command)
  - [AddTeamMember Command](#addteammember-command)
  - [RemoveTeamMember Command](#removeteammember-command)
  - [ChangeTeamMemberRole Command](#changeteammemberrole-command)
  - [ArchiveTeam Command](#archiveteam-command)
- [Team Events](#team-events)
  - [TeamCreated Event](#teamcreated-event)
  - [TeamUpdated Event](#teamupdated-event)
  - [TeamMemberAdded Event](#teammemberadded-event)
  - [TeamMemberRemoved Event](#teammemberremoved-event)
  - [TeamMemberRoleChanged Event](#teammemberrolechanged-event)
  - [TeamArchived Event](#teamarchived-event)
- [Team Aggregate Implementation](#team-aggregate-implementation)
  - [Command Methods](#command-methods)
  - [Apply Methods](#apply-methods)
  - [Business Rules](#business-rules)
- [Team Membership Management](#team-membership-management)
  - [Member Roles](#member-roles)
  - [Invitations](#invitations)
  - [Permissions](#permissions)
- [State Transitions](#state-transitions)
  - [State Diagram](#state-diagram)
  - [Transition Rules](#transition-rules)
- [Command Handlers](#command-handlers)
  - [CreateTeamCommandHandler](#createteamcommandhandler)
  - [UpdateTeamCommandHandler](#updateteamcommandhandler)
  - [Other Command Handlers](#other-command-handlers)
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

This document provides detailed documentation on the Team aggregate in the event sourcing implementation for the
Enhanced Laravel Application (ELA). The Team aggregate is responsible for managing team creation, updates, membership
management, and archival. This document covers the commands, events, state transitions, and business rules for the Team
aggregate.

## Prerequisites

- **Required Prior Steps:**

  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [User Aggregate](020-010-user-aggregate.md)
  - [CQRS Configuration](../030-core-components/030-cqrs-configuration.md)
  - [Package Installation](../030-core-components/010-package-installation.md)

- **Required Packages:**

  - `spatie/laravel-event-sourcing`: ^7.0
  - `hirethunk/verbs`: ^1.0
  - `spatie/laravel-data`: ^3.0
  - `spatie/laravel-model-states`: ^2.0
  - `spatie/laravel-permission`: ^5.0

- **Required Knowledge:**

  - Understanding of event sourcing principles
  - Familiarity with team-based authorization
  - Understanding of state machines

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task                                    | Estimated Time |
| --------------------------------------- | -------------- |
| Setting up Team aggregate structure     | 1 hour         |
| Implementing Team commands              | 2 hours        |
| Implementing Team events                | 1 hour         |
| Implementing command methods            | 2 hours        |
| Implementing apply methods              | 1 hour         |
| Implementing team membership management | 2 hours        |
| Testing Team aggregate                  | 2 hours        |
| **Total**                               | **11 hours**   |

</details>

## Team Aggregate Structure

### State Properties

The Team aggregate maintains the following state properties:

```php
protected string $name;
protected string $slug;
protected ?string $description = null;
protected array $settings = [];
protected string $state;
protected array $members = [];
protected ?string $archiveReason = null;
```text

### Team States

The Team aggregate can be in one of the following states:

1. **Forming**: Team has been created but is still being set up
2. **Active**: Team is active and operational
3. **Archived**: Team has been archived

These states are implemented using `spatie/laravel-model-states` and are integrated with the event sourcing system.

## Team Commands

<details>
<summary>Team Command Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[CreateTeam Command] --> B[CreateTeamCommandHandler]
    B --> C[TeamAggregateRoot]
    C --> D{Valid?}
    D -->|Yes| E[Record TeamCreated Event]
    D -->|No| F[Throw Exception]
    E --> G[Apply TeamCreated Event]
    G --> H[Update Team State]
    E --> I[Event Store]
    I --> J[TeamProjector]
    J --> K[Team Model]
```php
For dark mode, see [Team Command Flow (Dark Mode)](../../illustrations/mermaid/dark/team-command-flow-dark.mmd)

</details>

### CreateTeam Command

Creates a new team.

```php
<?php

namespace App\Commands\Teams;

use Hirethunk\Verbs\Command;

class CreateTeamCommand extends Command
{
    public function __construct(
        public string $name,
        public string $ownerId,
        public ?string $description = null,
        public array $settings = []
    ) {}

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'ownerId' => ['required', 'string', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'settings' => ['sometimes', 'array'],
        ];
    }
}
```text

### UpdateTeam Command

Updates team information.

```php
<?php

namespace App\Commands\Teams;

use Hirethunk\Verbs\Command;

class UpdateTeamCommand extends Command
{
    public function __construct(
        public string $teamId,
        public string $name,
        public ?string $description = null,
        public array $settings = []
    ) {}

    public function rules(): array
    {
        return [
            'teamId' => ['required', 'string', 'exists:teams,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'settings' => ['sometimes', 'array'],
        ];
    }
}
```php
### AddTeamMember Command

Adds a member to a team.

```php
<?php

namespace App\Commands\Teams;

use Hirethunk\Verbs\Command;

class AddTeamMemberCommand extends Command
{
    public function __construct(
        public string $teamId,
        public string $userId,
        public string $role = 'member'
    ) {}

    public function rules(): array
    {
        return [
            'teamId' => ['required', 'string', 'exists:teams,id'],
            'userId' => ['required', 'string', 'exists:users,id'],
            'role' => ['required', 'string', 'in:owner,admin,member'],
        ];
    }
}
```text

### RemoveTeamMember Command

Removes a member from a team.

```php
<?php

namespace App\Commands\Teams;

use Hirethunk\Verbs\Command;

class RemoveTeamMemberCommand extends Command
{
    public function __construct(
        public string $teamId,
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'teamId' => ['required', 'string', 'exists:teams,id'],
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```php
### ChangeTeamMemberRole Command

Changes a team member's role.

```php
<?php

namespace App\Commands\Teams;

use Hirethunk\Verbs\Command;

class ChangeTeamMemberRoleCommand extends Command
{
    public function __construct(
        public string $teamId,
        public string $userId,
        public string $role
    ) {}

    public function rules(): array
    {
        return [
            'teamId' => ['required', 'string', 'exists:teams,id'],
            'userId' => ['required', 'string', 'exists:users,id'],
            'role' => ['required', 'string', 'in:owner,admin,member'],
        ];
    }
}
```text

### ArchiveTeam Command

Archives a team.

```php
<?php

namespace App\Commands\Teams;

use Hirethunk\Verbs\Command;

class ArchiveTeamCommand extends Command
{
    public function __construct(
        public string $teamId,
        public string $reason
    ) {}

    public function rules(): array
    {
        return [
            'teamId' => ['required', 'string', 'exists:teams,id'],
            'reason' => ['required', 'string', 'max:255'],
        ];
    }
}
```php
## Team Events

### TeamCreated Event

Represents a team creation event.

```php
<?php

namespace App\Events\Teams;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamCreated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:

- `name`: Team name
- `slug`: Team slug (URL-friendly version of the name)
- `description`: Team description
- `settings`: Team settings
- `owner_id`: ID of the team owner
- `created_at`: Creation timestamp

### TeamUpdated Event

Represents a team update event.

```php
<?php

namespace App\Events\Teams;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamUpdated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:

- `name`: Updated team name
- `slug`: Updated team slug
- `description`: Updated team description
- `settings`: Updated team settings
- `updated_at`: Update timestamp

### TeamMemberAdded Event

Represents a team member addition event.

```php
<?php

namespace App\Events\Teams;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamMemberAdded extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:

- `user_id`: ID of the added user
- `role`: Role assigned to the user
- `added_at`: Addition timestamp

### TeamMemberRemoved Event

Represents a team member removal event.

```php
<?php

namespace App\Events\Teams;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamMemberRemoved extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:

- `user_id`: ID of the removed user
- `removed_at`: Removal timestamp

### TeamMemberRoleChanged Event

Represents a team member role change event.

```php
<?php

namespace App\Events\Teams;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamMemberRoleChanged extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:

- `user_id`: ID of the user
- `old_role`: Previous role
- `new_role`: New role
- `changed_at`: Change timestamp

### TeamArchived Event

Represents a team archival event.

```php
<?php

namespace App\Events\Teams;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamArchived extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:

- `reason`: Archival reason
- `archived_at`: Archival timestamp

## Team Aggregate Implementation

### Command Methods

The Team aggregate implements methods to handle various commands:

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Events\Teams\TeamCreated;
use App\Events\Teams\TeamUpdated;
use App\Events\Teams\TeamMemberAdded;
use App\Events\Teams\TeamMemberRemoved;
use App\Events\Teams\TeamMemberRoleChanged;
use App\Events\Teams\TeamArchived;
use App\States\Team\Forming;
use App\States\Team\Active;
use App\States\Team\Archived;
use App\Exceptions\Teams\InvalidTeamStateTransitionException;
use App\Exceptions\Teams\TeamMemberNotFoundException;
use App\Exceptions\Teams\CannotRemoveLastOwnerException;
use Illuminate\Support\Str;

class TeamAggregateRoot extends AggregateRoot
{
    protected string $name;
    protected string $slug;
    protected ?string $description = null;
    protected array $settings = [];
    protected string $state;
    protected array $members = [];
    protected ?string $archiveReason = null;

    public function createTeam(string $name, string $ownerId, ?string $description = null, array $settings = []): self
    {
        $slug = Str::slug($name);

        $this->recordThat(new TeamCreated([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'settings' => $settings,
            'owner_id' => $ownerId,
            'created_at' => now(),
        ]));

        // Add the owner as a member with the 'owner' role
        $this->addTeamMember($ownerId, 'owner');

        return $this;
    }

    public function updateTeam(string $name, ?string $description = null, array $settings = []): self
    {
        if ($this->state === Archived::class) {
            throw new InvalidTeamStateTransitionException(
                "Cannot update an archived team"
            );
        }

        $slug = Str::slug($name);

        $this->recordThat(new TeamUpdated([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'settings' => $settings,
            'updated_at' => now(),
        ]));

        return $this;
    }

    public function addTeamMember(string $userId, string $role = 'member'): self
    {
        if ($this->state === Archived::class) {
            throw new InvalidTeamStateTransitionException(
                "Cannot add members to an archived team"
            );
        }

        // Check if user is already a member
        foreach ($this->members as $member) {
            if ($member['user_id'] === $userId) {
                // If the user is already a member with the same role, do nothing
                if ($member['role'] === $role) {
                    return $this;
                }

                // If the user is already a member but with a different role, change the role
                return $this->changeTeamMemberRole($userId, $role);
            }
        }

        $this->recordThat(new TeamMemberAdded([
            'user_id' => $userId,
            'role' => $role,
            'added_at' => now(),
        ]));

        // If this is the first member or the team is in forming state, activate the team
        if (count($this->members) === 1 && $this->state === Forming::class) {
            $this->activateTeam();
        }

        return $this;
    }

    public function removeTeamMember(string $userId): self
    {
        if ($this->state === Archived::class) {
            throw new InvalidTeamStateTransitionException(
                "Cannot remove members from an archived team"
            );
        }

        // Find the member
        $memberIndex = null;
        $memberRole = null;

        foreach ($this->members as $index => $member) {
            if ($member['user_id'] === $userId) {
                $memberIndex = $index;
                $memberRole = $member['role'];
                break;
            }
        }

        if ($memberIndex === null) {
            throw new TeamMemberNotFoundException("User is not a member of this team");
        }

        // Check if this is the last owner
        if ($memberRole === 'owner' && $this->countMembersWithRole('owner') === 1) {
            throw new CannotRemoveLastOwnerException("Cannot remove the last owner of a team");
        }

        $this->recordThat(new TeamMemberRemoved([
            'user_id' => $userId,
            'removed_at' => now(),
        ]));

        return $this;
    }

    public function changeTeamMemberRole(string $userId, string $role): self
    {
        if ($this->state === Archived::class) {
            throw new InvalidTeamStateTransitionException(
                "Cannot change member roles in an archived team"
            );
        }

        // Find the member
        $memberIndex = null;
        $oldRole = null;

        foreach ($this->members as $index => $member) {
            if ($member['user_id'] === $userId) {
                $memberIndex = $index;
                $oldRole = $member['role'];
                break;
            }
        }

        if ($memberIndex === null) {
            throw new TeamMemberNotFoundException("User is not a member of this team");
        }

        // If the role is the same, do nothing
        if ($oldRole === $role) {
            return $this;
        }

        // Check if this would remove the last owner
        if ($oldRole === 'owner' && $role !== 'owner' && $this->countMembersWithRole('owner') === 1) {
            throw new CannotRemoveLastOwnerException("Cannot change the role of the last owner");
        }

        $this->recordThat(new TeamMemberRoleChanged([
            'user_id' => $userId,
            'old_role' => $oldRole,
            'new_role' => $role,
            'changed_at' => now(),
        ]));

        return $this;
    }

    public function archiveTeam(string $reason): self
    {
        if ($this->state === Archived::class) {
            // Team is already archived, do nothing
            return $this;
        }

        $this->recordThat(new TeamArchived([
            'reason' => $reason,
            'archived_at' => now(),
        ]));

        return $this;
    }

    protected function activateTeam(): self
    {
        if ($this->state !== Forming::class) {
            return $this;
        }

        $this->state = Active::class;

        return $this;
    }

    protected function countMembersWithRole(string $role): int
    {
        $count = 0;

        foreach ($this->members as $member) {
            if ($member['role'] === $role) {
                $count++;
            }
        }

        return $count;
    }
}
```text

### Apply Methods

The Team aggregate implements apply methods to update its state based on events:

```php
protected function applyTeamCreated(TeamCreated $event): void
{
    $this->name = $event->payload['name'];
    $this->slug = $event->payload['slug'];
    $this->description = $event->payload['description'];
    $this->settings = $event->payload['settings'];
    $this->state = Forming::class;
}

protected function applyTeamUpdated(TeamUpdated $event): void
{
    $this->name = $event->payload['name'];
    $this->slug = $event->payload['slug'];
    $this->description = $event->payload['description'];
    $this->settings = $event->payload['settings'];
}

protected function applyTeamMemberAdded(TeamMemberAdded $event): void
{
    $this->members[] = [
        'user_id' => $event->payload['user_id'],
        'role' => $event->payload['role'],
        'added_at' => $event->payload['added_at'],
    ];

    // If this is the first member, activate the team
    if (count($this->members) === 1 && $this->state === Forming::class) {
        $this->state = Active::class;
    }
}

protected function applyTeamMemberRemoved(TeamMemberRemoved $event): void
{
    foreach ($this->members as $index => $member) {
        if ($member['user_id'] === $event->payload['user_id']) {
            unset($this->members[$index]);
            break;
        }
    }

    // Reindex the array
    $this->members = array_values($this->members);
}

protected function applyTeamMemberRoleChanged(TeamMemberRoleChanged $event): void
{
    foreach ($this->members as $index => $member) {
        if ($member['user_id'] === $event->payload['user_id']) {
            $this->members[$index]['role'] = $event->payload['new_role'];
            break;
        }
    }
}

protected function applyTeamArchived(TeamArchived $event): void
{
    $this->state = Archived::class;
    $this->archiveReason = $event->payload['reason'];
}
```php
### Business Rules

The Team aggregate enforces several business rules:

1. **State Transitions**: Only certain state transitions are allowed

   - Forming → Active
   - Active → Archived

2. **Team Membership**: Teams must have at least one owner

   - Cannot remove the last owner
   - Cannot change the role of the last owner to a non-owner role

3. **Team Updates**: Archived teams cannot be updated

4. **Membership Management**: Archived teams cannot have members added, removed, or roles changed

## Team Membership Management

### Member Roles

Team members can have one of the following roles:

1. **Owner**: Has full control over the team, including adding/removing members and archiving the team
2. **Admin**: Can manage team settings and members, but cannot archive the team
3. **Member**: Can participate in team activities but cannot manage the team

### Invitations

Team invitations are handled through a separate process:

1. A team owner or admin creates an invitation
2. The invitation is sent to the user
3. The user accepts or rejects the invitation
4. If accepted, the user is added to the team

### Permissions

Team permissions are integrated with `spatie/laravel-permission` and the team feature is enabled in
`config/permission.php`:

```php
'teams' => true,
```text

This allows for team-specific roles and permissions.

## State Transitions

### State Diagram

<details>
<summary>Team State Transitions Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
stateDiagram-v2
    [*] --> Forming: createTeam
    Forming --> Active: activate
    Active --> Archived: archive
    Archived --> [*]
```text
For a more detailed diagram, see
[Team Aggregate States (Light Mode)](../../illustrations/mermaid/light/team-aggregate-states-light.mmd)

For dark mode versions, see:

- [Team State Transitions (Dark Mode)](../../illustrations/mermaid/dark/team-state-transitions-dark.mmd)
- [Team Aggregate States (Dark Mode)](../../illustrations/mermaid/dark/team-aggregate-states-dark.mmd)
</details>

The Team aggregate supports the following state transitions:

```text
Forming → Active
Active → Archived
```php
### Transition Rules

State transitions are enforced by the Team aggregate's command methods:

```php
public function archiveTeam(string $reason): self
{
    if ($this->state === Archived::class) {
        // Team is already archived, do nothing
        return $this;
    }

    $this->recordThat(new TeamArchived([
        'reason' => $reason,
        'archived_at' => now(),
    ]));

    return $this;
}
```text

## Command Handlers

### CreateTeamCommandHandler

Handles team creation:

```php
<?php

namespace App\CommandHandlers\Teams;

use App\Commands\Teams\CreateTeamCommand;
use App\Aggregates\TeamAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use Illuminate\Support\Str;

class CreateTeamCommandHandler extends CommandHandler
{
    public function handle(CreateTeamCommand $command)
    {
        // Generate a UUID for the team
        $teamId = (string) Str::uuid();

        // Create the team
        TeamAggregateRoot::retrieve($teamId)
            ->createTeam(
                $command->name,
                $command->ownerId,
                $command->description,
                $command->settings
            )
            ->persist();

        return $teamId;
    }
}
```php
### UpdateTeamCommandHandler

Handles team updates:

```php
<?php

namespace App\CommandHandlers\Teams;

use App\Commands\Teams\UpdateTeamCommand;
use App\Aggregates\TeamAggregateRoot;
use Hirethunk\Verbs\CommandHandler;

class UpdateTeamCommandHandler extends CommandHandler
{
    public function handle(UpdateTeamCommand $command)
    {
        // Authorize the command
        $this->authorize('update', ['App\Models\Team', $command->teamId]);

        // Update the team
        TeamAggregateRoot::retrieve($command->teamId)
            ->updateTeam(
                $command->name,
                $command->description,
                $command->settings
            )
            ->persist();

        return $this->success();
    }
}
```text

### Other Command Handlers

Similar command handlers exist for other team commands:

- `AddTeamMemberCommandHandler`
- `RemoveTeamMemberCommandHandler`
- `ChangeTeamMemberRoleCommandHandler`
- `ArchiveTeamCommandHandler`

## Benefits and Challenges

### Benefits

1. **Complete Audit Trail**: Every team action is recorded as an event
2. **State Management**: Clear state transitions with enforced rules
3. **Membership History**: Complete history of team membership changes
4. **Temporal Queries**: The state of a team at any point in time can be reconstructed

### Challenges

1. **Complexity**: Event sourcing adds complexity to the team management system
2. **Performance**: Reconstructing team state from events can be slow for teams with many events
3. **Integration**: Integrating with Laravel's team-based authorization requires careful planning

### Mitigation Strategies

1. **Snapshots**: Use snapshots to improve performance for teams with many events
2. **Caching**: Cache team projections to improve read performance
3. **Clear Documentation**: Document the team aggregate thoroughly to help developers understand the system

## Troubleshooting

### Common Issues

<details>
<summary>Team state not updating correctly</summary>

**Symptoms:**

- Team state is not reflecting the expected state after a command
- State transitions are not working as expected

**Possible Causes:**

- Missing apply methods
- Incorrect state transition logic
- Events not being persisted

**Solutions:**

1. Ensure all apply methods are implemented correctly
2. Verify state transition logic in command methods
3. Check that events are being persisted with `persist()`
</details>

<details>
<summary>Team membership issues</summary>

**Symptoms:**

- Team members are not being added or removed correctly
- Team member roles are not being updated

**Possible Causes:**

- Incorrect implementation of member management methods
- Missing apply methods for member events
- Incorrect handling of member arrays

**Solutions:**

1. Ensure member management methods are implemented correctly
2. Add apply methods for all member-related events
3. Verify that the member array is being properly maintained
</details>

### Solutions

For detailed solutions to common issues, refer to the
[Event Sourcing Troubleshooting Guide](070-testing.md#troubleshooting).

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [User Aggregate](020-010-user-aggregate.md) - Detailed documentation on User aggregate
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing State Machines](080-state-machines.md) - Integration of event sourcing with state machines
- [Event Sourcing Roles and Permissions](090-roles-permissions.md) - Integration of event sourcing with roles and
  permissions

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date       | Changes                                                                      | Author       |
| ------- | ---------- | ---------------------------------------------------------------------------- | ------------ |
| 1.1.0   | 2025-05-18 | Added team state transitions diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0   | 2025-05-18 | Initial version                                                              | AI Assistant |

</details>
