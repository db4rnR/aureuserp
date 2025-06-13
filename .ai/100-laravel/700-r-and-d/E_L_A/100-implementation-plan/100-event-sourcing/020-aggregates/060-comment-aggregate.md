# Phase 1: Comment Aggregate

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
- [Comment Aggregate Structure](#comment-aggregate-structure)
  - [State Properties](#state-properties)
  - [Comment States](#comment-states)
- [Comment Commands](#comment-commands)
  - [CreateComment Command](#createcomment-command)
  - [UpdateComment Command](#updatecomment-command)
  - [DeleteComment Command](#deletecomment-command)
  - [ApproveComment Command](#approvecomment-command)
  - [RejectComment Command](#rejectcomment-command)
  - [AddReaction Command](#addreaction-command)
  - [RemoveReaction Command](#removereaction-command)
- [Comment Events](#comment-events)
  - [CommentCreated Event](#commentcreated-event)
  - [CommentUpdated Event](#commentupdated-event)
  - [CommentDeleted Event](#commentdeleted-event)
  - [CommentApproved Event](#commentapproved-event)
  - [CommentRejected Event](#commentrejected-event)
  - [ReactionAdded Event](#reactionadded-event)
  - [ReactionRemoved Event](#reactionremoved-event)
- [Comment Aggregate Implementation](#comment-aggregate-implementation)
  - [Command Methods](#command-methods)
  - [Apply Methods](#apply-methods)
  - [Business Rules](#business-rules)
- [Integration with Reactions](#integration-with-reactions)
  - [Reaction Types](#reaction-types)
  - [Reaction Restrictions](#reaction-restrictions)
- [Comment Configuration](#comment-configuration)
  - [Enabling/Disabling Comments](#enablingdisabling-comments)
  - [Reactions-Only Mode](#reactions-only-mode)
  - [Approval Requirements](#approval-requirements)
- [State Transitions](#state-transitions)
  - [State Diagram](#state-diagram)
  - [Transition Rules](#transition-rules)
- [Command Handlers](#command-handlers)
  - [CreateCommentCommandHandler](#createcommentcommandhandler)
  - [UpdateCommentCommandHandler](#updatecommentcommandhandler)
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

This document provides detailed documentation on the Comment aggregate in the event sourcing implementation for the Enhanced Laravel Application (ELA). The Comment aggregate is responsible for managing comment creation, updates, moderation, and reactions. This document covers the commands, events, state transitions, and business rules for the Comment aggregate.

## Prerequisites

- **Required Prior Steps:**
  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [User Aggregate](020-010-user-aggregate.md)
  - [Post Aggregate](020-030-post-aggregate.md)
  - [CQRS Configuration](../030-core-components/030-cqrs-configuration.md)
  - [Package Installation](../030-core-components/010-package-installation.md)

- **Required Packages:**
  - `spatie/laravel-event-sourcing`: ^7.0
  - `hirethunk/verbs`: ^1.0
  - `spatie/laravel-data`: ^3.0
  - `spatie/laravel-model-states`: ^2.0
  - `spatie/laravel-comments`: ^1.0

- **Required Knowledge:**
  - Understanding of event sourcing principles
  - Familiarity with comment systems
  - Understanding of state machines

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Setting up Comment aggregate structure | 1 hour |
| Implementing Comment commands | 1 hour |
| Implementing Comment events | 1 hour |
| Implementing command methods | 2 hours |
| Implementing apply methods | 1 hour |
| Integrating with reactions | 1 hour |
| Testing Comment aggregate | 2 hours |
| **Total** | **9 hours** |
</details>

## Comment Aggregate Structure

### State Properties

The Comment aggregate maintains the following state properties:

```php
protected string $content;
protected string $userId;
protected string $commentableType;
protected string $commentableId;
protected ?string $parentId = null;
protected string $state;
protected array $reactions = [];
protected ?string $deletedAt = null;
protected ?string $approvedAt = null;
protected ?string $rejectedAt = null;
protected ?string $rejectionReason = null;
protected bool $isReactionsOnly = false;
```text

### Comment States

The Comment aggregate can be in one of the following states:

1. **Pending**: Comment is pending approval
2. **Approved**: Comment is approved and visible
3. **Rejected**: Comment is rejected and not visible
4. **Deleted**: Comment is deleted

These states are implemented using `spatie/laravel-model-states` and are integrated with the event sourcing system.

## Comment Commands

<details>
<summary>Comment Command Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[CreateComment Command] --> B[CreateCommentCommandHandler]
    B --> C[CommentAggregateRoot]
    C --> D{Valid?}
    D -->|Yes| E[Record CommentCreated Event]
    D -->|No| F[Throw Exception]
    E --> G[Apply CommentCreated Event]
    G --> H[Update Comment State]
    E --> I[Event Store]
    I --> J[CommentProjector]
    J --> K[Comment Model]
```php
For dark mode, see [Comment Command Flow (Dark Mode)](../../illustrations/mermaid/dark/comment-command-flow-dark.mmd)
</details>

### CreateComment Command

Creates a new comment.

```php
<?php

namespace App\Commands\Comments;

use Hirethunk\Verbs\Command;

class CreateCommentCommand extends Command
{
    public function __construct(
        public string $content,
        public string $commentableType,
        public string $commentableId,
        public ?string $parentId = null,
        public ?string $reactionType = null
    ) {}

    public function rules(): array
    {
        return [
            'content' => ['required_without:reactionType', 'string', 'max:10000'],
            'commentableType' => ['required', 'string'],
            'commentableId' => ['required', 'string'],
            'parentId' => ['nullable', 'string', 'exists:comments,id'],
            'reactionType' => ['nullable', 'string'],
        ];
    }
}
```text

### UpdateComment Command

Updates a comment.

```php
<?php

namespace App\Commands\Comments;

use Hirethunk\Verbs\Command;

class UpdateCommentCommand extends Command
{
    public function __construct(
        public string $commentId,
        public string $content
    ) {}

    public function rules(): array
    {
        return [
            'commentId' => ['required', 'string', 'exists:comments,id'],
            'content' => ['required', 'string', 'max:10000'],
        ];
    }
}
```php
### DeleteComment Command

Deletes a comment.

```php
<?php

namespace App\Commands\Comments;

use Hirethunk\Verbs\Command;

class DeleteCommentCommand extends Command
{
    public function __construct(
        public string $commentId
    ) {}

    public function rules(): array
    {
        return [
            'commentId' => ['required', 'string', 'exists:comments,id'],
        ];
    }
}
```text

### ApproveComment Command

Approves a pending comment.

```php
<?php

namespace App\Commands\Comments;

use Hirethunk\Verbs\Command;

class ApproveCommentCommand extends Command
{
    public function __construct(
        public string $commentId
    ) {}

    public function rules(): array
    {
        return [
            'commentId' => ['required', 'string', 'exists:comments,id'],
        ];
    }
}
```php
### RejectComment Command

Rejects a pending comment.

```php
<?php

namespace App\Commands\Comments;

use Hirethunk\Verbs\Command;

class RejectCommentCommand extends Command
{
    public function __construct(
        public string $commentId,
        public ?string $reason = null
    ) {}

    public function rules(): array
    {
        return [
            'commentId' => ['required', 'string', 'exists:comments,id'],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
```text

### AddReaction Command

Adds a reaction to a comment.

```php
<?php

namespace App\Commands\Comments;

use Hirethunk\Verbs\Command;

class AddReactionCommand extends Command
{
    public function __construct(
        public string $commentId,
        public string $type,
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'commentId' => ['required', 'string', 'exists:comments,id'],
            'type' => ['required', 'string'],
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```php
### RemoveReaction Command

Removes a reaction from a comment.

```php
<?php

namespace App\Commands\Comments;

use Hirethunk\Verbs\Command;

class RemoveReactionCommand extends Command
{
    public function __construct(
        public string $commentId,
        public string $type,
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'commentId' => ['required', 'string', 'exists:comments,id'],
            'type' => ['required', 'string'],
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```text

## Comment Events

### CommentCreated Event

Represents a comment creation event.

```php
<?php

namespace App\Events\Comments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CommentCreated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `content`: Comment content
- `user_id`: ID of the comment author
- `commentable_type`: Type of the commentable model
- `commentable_id`: ID of the commentable model
- `parent_id`: ID of the parent comment (if any)
- `created_at`: Creation timestamp

### CommentUpdated Event

Represents a comment update event.

```php
<?php

namespace App\Events\Comments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CommentUpdated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `content`: Updated comment content
- `updated_at`: Update timestamp

### CommentDeleted Event

Represents a comment deletion event.

```php
<?php

namespace App\Events\Comments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CommentDeleted extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `deleted_at`: Deletion timestamp

### CommentApproved Event

Represents a comment approval event.

```php
<?php

namespace App\Events\Comments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CommentApproved extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `approved_at`: Approval timestamp

### CommentRejected Event

Represents a comment rejection event.

```php
<?php

namespace App\Events\Comments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CommentRejected extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `reason`: Rejection reason
- `rejected_at`: Rejection timestamp

### ReactionAdded Event

Represents a reaction addition event.

```php
<?php

namespace App\Events\Comments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ReactionAdded extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `type`: Reaction type
- `user_id`: ID of the user who added the reaction
- `added_at`: Addition timestamp

### ReactionRemoved Event

Represents a reaction removal event.

```php
<?php

namespace App\Events\Comments;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ReactionRemoved extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `type`: Reaction type
- `user_id`: ID of the user who removed the reaction
- `removed_at`: Removal timestamp

## Comment Aggregate Implementation

### Command Methods

The Comment aggregate implements methods to handle various commands:

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Events\Comments\CommentCreated;
use App\Events\Comments\CommentUpdated;
use App\Events\Comments\CommentDeleted;
use App\Events\Comments\CommentApproved;
use App\Events\Comments\CommentRejected;
use App\Events\Comments\ReactionAdded;
use App\Events\Comments\ReactionRemoved;
use App\States\Comment\Pending;
use App\States\Comment\Approved;
use App\States\Comment\Rejected;
use App\States\Comment\Deleted;
use App\Exceptions\Comments\InvalidCommentStateTransitionException;
use App\Exceptions\Comments\ReactionsOnlyException;

class CommentAggregateRoot extends AggregateRoot
{
    protected string $content;
    protected string $userId;
    protected string $commentableType;
    protected string $commentableId;
    protected ?string $parentId = null;
    protected string $state;
    protected array $reactions = [];
    protected ?string $deletedAt = null;
    protected ?string $approvedAt = null;
    protected ?string $rejectedAt = null;
    protected ?string $rejectionReason = null;
    protected bool $isReactionsOnly = false;

    public function createComment(
        string $content,
        string $userId,
        string $commentableType,
        string $commentableId,
        ?string $parentId = null
    ): self {
        // Check if reactions-only mode is enabled
        if ($this->isReactionsOnly && !empty($content)) {
            throw new ReactionsOnlyException("Only reactions are allowed for this resource");
        }

        $this->recordThat(new CommentCreated([
            'content' => $content,
            'user_id' => $userId,
            'commentable_type' => $commentableType,
            'commentable_id' => $commentableId,
            'parent_id' => $parentId,
            'created_at' => now(),
        ]));

        return $this;
    }

    public function updateComment(string $content): self
    {
        if (in_array($this->state, [Rejected::class, Deleted::class])) {
            throw new InvalidCommentStateTransitionException(
                "Cannot update a rejected or deleted comment"
            );
        }

        // Check if reactions-only mode is enabled
        if ($this->isReactionsOnly) {
            throw new ReactionsOnlyException("Only reactions are allowed for this resource");
        }

        $this->recordThat(new CommentUpdated([
            'content' => $content,
            'updated_at' => now(),
        ]));

        return $this;
    }

    public function deleteComment(): self
    {
        if ($this->state === Deleted::class) {
            // Comment is already deleted, do nothing
            return $this;
        }

        $this->recordThat(new CommentDeleted([
            'deleted_at' => now(),
        ]));

        return $this;
    }

    public function approveComment(): self
    {
        if ($this->state !== Pending::class) {
            throw new InvalidCommentStateTransitionException(
                "Cannot approve a comment that is not pending"
            );
        }

        $this->recordThat(new CommentApproved([
            'approved_at' => now(),
        ]));

        return $this;
    }

    public function rejectComment(?string $reason = null): self
    {
        if ($this->state !== Pending::class) {
            throw new InvalidCommentStateTransitionException(
                "Cannot reject a comment that is not pending"
            );
        }

        $this->recordThat(new CommentRejected([
            'reason' => $reason,
            'rejected_at' => now(),
        ]));

        return $this;
    }

    public function addReaction(string $type, string $userId): self
    {
        if ($this->state === Deleted::class) {
            throw new InvalidCommentStateTransitionException(
                "Cannot add reactions to a deleted comment"
            );
        }

        // Check if user has already added this reaction
        foreach ($this->reactions as $reaction) {
            if ($reaction['user_id'] === $userId && $reaction['type'] === $type) {
                // User has already added this reaction, do nothing
                return $this;
            }
        }

        $this->recordThat(new ReactionAdded([
            'type' => $type,
            'user_id' => $userId,
            'added_at' => now(),
        ]));

        return $this;
    }

    public function removeReaction(string $type, string $userId): self
    {
        if ($this->state === Deleted::class) {
            throw new InvalidCommentStateTransitionException(
                "Cannot remove reactions from a deleted comment"
            );
        }

        // Check if user has added this reaction
        $hasReaction = false;
        foreach ($this->reactions as $reaction) {
            if ($reaction['user_id'] === $userId && $reaction['type'] === $type) {
                $hasReaction = true;
                break;
            }
        }

        if (!$hasReaction) {
            // User has not added this reaction, do nothing
            return $this;
        }

        $this->recordThat(new ReactionRemoved([
            'type' => $type,
            'user_id' => $userId,
            'removed_at' => now(),
        ]));

        return $this;
    }

    public function configureReactionsOnly(bool $reactionsOnly): self
    {
        $this->isReactionsOnly = $reactionsOnly;

        return $this;
    }
}
```text

### Apply Methods

The Comment aggregate implements apply methods to update its state based on events:

```php
protected function applyCommentCreated(CommentCreated $event): void
{
    $this->content = $event->payload['content'];
    $this->userId = $event->payload['user_id'];
    $this->commentableType = $event->payload['commentable_type'];
    $this->commentableId = $event->payload['commentable_id'];
    $this->parentId = $event->payload['parent_id'];
    $this->state = Pending::class;
}

protected function applyCommentUpdated(CommentUpdated $event): void
{
    $this->content = $event->payload['content'];
}

protected function applyCommentDeleted(CommentDeleted $event): void
{
    $this->state = Deleted::class;
    $this->deletedAt = $event->payload['deleted_at'];
}

protected function applyCommentApproved(CommentApproved $event): void
{
    $this->state = Approved::class;
    $this->approvedAt = $event->payload['approved_at'];
}

protected function applyCommentRejected(CommentRejected $event): void
{
    $this->state = Rejected::class;
    $this->rejectedAt = $event->payload['rejected_at'];
    $this->rejectionReason = $event->payload['reason'];
}

protected function applyReactionAdded(ReactionAdded $event): void
{
    $this->reactions[] = [
        'type' => $event->payload['type'],
        'user_id' => $event->payload['user_id'],
        'added_at' => $event->payload['added_at'],
    ];
}

protected function applyReactionRemoved(ReactionRemoved $event): void
{
    foreach ($this->reactions as $index => $reaction) {
        if ($reaction['user_id'] === $event->payload['user_id'] && $reaction['type'] === $event->payload['type']) {
            unset($this->reactions[$index]);
            break;
        }
    }

    // Reindex the array
    $this->reactions = array_values($this->reactions);
}
```javascript
### Business Rules

The Comment aggregate enforces several business rules:

1. **State Transitions**: Only certain state transitions are allowed
   - Pending → Approved, Rejected, Deleted
   - Approved → Deleted
   - Rejected → Deleted
   - Deleted → (no transitions allowed)

2. **Comment Updates**: Rejected or deleted comments cannot be updated

3. **Reactions**: Deleted comments cannot have reactions added or removed

4. **Reactions-Only Mode**: If reactions-only mode is enabled, comments with content cannot be created or updated

## Integration with Reactions

<details>
<summary>Reaction Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[AddReaction Command] --> B[AddReactionCommandHandler]
    B --> C[CommentAggregateRoot]
    C --> D{Valid?}
    D -->|Yes| E[Record ReactionAdded Event]
    D -->|No| F[Throw Exception]
    E --> G[Apply ReactionAdded Event]
    G --> H[Update Comment Reactions]
    E --> I[Event Store]
    I --> J[CommentProjector]
    J --> K[Comment Model]
```text

For dark mode, see [Reaction Flow (Dark Mode)](../../illustrations/mermaid/dark/reaction-flow-dark.mmd)
</details>

### Reaction Types

Reactions are configured through the `spatie/laravel-comments` package:

```php
// config/comments.php
'allowed_reactions' => ['👍', '🥳', '👀', '😍', '💅'],
```php
### Reaction Restrictions

Reactions can be added to comments, but there are some restrictions:

1. A user can only add each reaction type once to a comment
2. Reactions cannot be added to deleted comments
3. Reactions can be removed by the user who added them

## Comment Configuration

### Enabling/Disabling Comments

Comments can be enabled or disabled for a commentable model:

```php
// In the commentable model
public function commentsAreEnabled(): bool
{
    return $this->comments_enabled;
}
```text

### Reactions-Only Mode

Commentable models can be configured to allow only reactions:

```php
// In the commentable model
public function commentsAreReactionsOnly(): bool
{
    return $this->reactions_only;
}
```php
This is enforced in the Comment aggregate:

```php
public function createComment(
    string $content,
    string $userId,
    string $commentableType,
    string $commentableId,
    ?string $parentId = null
): self {
    // Check if reactions-only mode is enabled
    if ($this->isReactionsOnly && !empty($content)) {
        throw new ReactionsOnlyException("Only reactions are allowed for this resource");
    }

    // Rest of the method...
}
```text

### Approval Requirements

Comments can be configured to require approval:

```php
// config/comments.php
'automatically_approve_all_comments' => false,
```php
## State Transitions

### State Diagram

<details>
<summary>Comment State Transitions Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
stateDiagram-v2
    [*] --> Pending: createComment
    Pending --> Approved: approveComment
    Pending --> Rejected: rejectComment
    Pending --> Deleted: deleteComment
    Approved --> Deleted: deleteComment
    Rejected --> Deleted: deleteComment
    Deleted --> [*]
```text

For dark mode, see [Comment State Transitions (Dark Mode)](../../illustrations/mermaid/dark/comment-state-transitions-dark.mmd)
</details>

The Comment aggregate supports the following state transitions:

```bash
Pending → Approved
Pending → Rejected
Pending → Deleted
Approved → Deleted
Rejected → Deleted
```text

### Transition Rules

State transitions are enforced by the Comment aggregate's command methods:

```php
public function approveComment(): self
{
    if ($this->state !== Pending::class) {
        throw new InvalidCommentStateTransitionException(
            "Cannot approve a comment that is not pending"
        );
    }

    $this->recordThat(new CommentApproved([
        'approved_at' => now(),
    ]));

    return $this;
}
```php
## Command Handlers

### CreateCommentCommandHandler

Handles comment creation:

```php
<?php

namespace App\CommandHandlers\Comments;

use App\Commands\Comments\CreateCommentCommand;
use App\Aggregates\CommentAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use Illuminate\Support\Str;
use App\Models\Comment;

class CreateCommentCommandHandler extends CommandHandler
{
    public function handle(CreateCommentCommand $command)
    {
        // Check if comments are enabled for the commentable model
        $commentableType = $command->commentableType;
        $commentableId = $command->commentableId;
        $commentable = $commentableType::findOrFail($commentableId);

        if (method_exists($commentable, 'commentsAreEnabled') && !$commentable->commentsAreEnabled()) {
            throw new \Exception("Comments are disabled for this resource");
        }

        // Check if reactions-only mode is enabled
        $reactionsOnly = method_exists($commentable, 'commentsAreReactionsOnly') && $commentable->commentsAreReactionsOnly();

        // Generate a UUID for the comment
        $commentId = (string) Str::uuid();

        // Create the comment
        $commentAggregate = CommentAggregateRoot::retrieve($commentId);

        // Configure reactions-only mode
        if ($reactionsOnly) {
            $commentAggregate->configureReactionsOnly(true);
        }

        // Create the comment
        $commentAggregate->createComment(
            $command->content,
            auth()->id(),
            $command->commentableType,
            $command->commentableId,
            $command->parentId
        )->persist();

        // If a reaction was included, add it
        if ($command->reactionType) {
            $commentAggregate->addReaction($command->reactionType, auth()->id())->persist();
        }

        return $commentId;
    }
}
```text

### UpdateCommentCommandHandler

Handles comment updates:

```php
<?php

namespace App\CommandHandlers\Comments;

use App\Commands\Comments\UpdateCommentCommand;
use App\Aggregates\CommentAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use App\Models\Comment;

class UpdateCommentCommandHandler extends CommandHandler
{
    public function handle(UpdateCommentCommand $command)
    {
        // Authorize the command
        $comment = Comment::findOrFail($command->commentId);
        $this->authorize('update', $comment);

        // Update the comment
        CommentAggregateRoot::retrieve($command->commentId)
            ->updateComment($command->content)
            ->persist();

        return $this->success();
    }
}
```

### Other Command Handlers

Similar command handlers exist for other comment commands:

- `DeleteCommentCommandHandler`
- `ApproveCommentCommandHandler`
- `RejectCommentCommandHandler`
- `AddReactionCommandHandler`
- `RemoveReactionCommandHandler`

## Benefits and Challenges

### Benefits

1. **Complete Audit Trail**: Every comment action is recorded as an event
2. **State Management**: Clear state transitions with enforced rules
3. **Reaction History**: Complete history of reactions
4. **Temporal Queries**: The state of a comment at any point in time can be reconstructed

### Challenges

1. **Complexity**: Event sourcing adds complexity to the comment system
2. **Performance**: Reconstructing comment state from events can be slow for comments with many events
3. **Integration**: Integrating with the `spatie/laravel-comments` package requires careful planning

### Mitigation Strategies

1. **Snapshots**: Use snapshots to improve performance for comments with many events
2. **Caching**: Cache comment projections to improve read performance
3. **Clear Documentation**: Document the comment aggregate thoroughly to help developers understand the system

## Troubleshooting

### Common Issues

<details>
<summary>Comment state not updating correctly</summary>

**Symptoms:**
- Comment state is not reflecting the expected state after a command
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
<summary>Reactions not working correctly</summary>

**Symptoms:**
- Reactions are not being added or removed correctly
- Reaction counts are incorrect

**Possible Causes:**
- Incorrect implementation of reaction methods
- Missing apply methods for reaction events
- Projector not updating the comment model correctly

**Solutions:**
1. Ensure reaction methods are implemented correctly
2. Add apply methods for reaction events
3. Verify that the projector is updating the comment model correctly
</details>

### Solutions

For detailed solutions to common issues, refer to the [Event Sourcing Troubleshooting Guide](070-testing.md#troubleshooting).

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [User Aggregate](020-010-user-aggregate.md) - Detailed documentation on User aggregate
- [Post Aggregate](020-030-post-aggregate.md) - Detailed documentation on Post aggregate
- [Event Sourcing Comments and Reactions](100-comments-reactions.md) - Integration of event sourcing with comments and reactions

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added comment state transitions diagram, command flow diagram, reaction flow diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
