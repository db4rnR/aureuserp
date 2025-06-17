# Phase 1: Event Sourcing Comments and Reactions

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
- [Comments and Reactions Concept](#comments-and-reactions-concept)
  - [What are Comments and Reactions?](#what-are-comments-and-reactions)
  - [Comments and Reactions Responsibilities](#comments-and-reactions-responsibilities)
  - [Comments and Reactions Types](#comments-and-reactions-types)
- [Integration with spatie/laravel-comments](#integration-with-spatielaravel-comments)
  - [Comment Model](#comment-model)
  - [Reaction Model](#reaction-model)
  - [Commentable Interface](#commentable-interface)
- [Implementing Comments and Reactions](#implementing-comments-and-reactions)
  - [Base Comment Structure](#base-comment-structure)
  - [Base Reaction Structure](#base-reaction-structure)
  - [Comment and Reaction Configuration](#comment-and-reaction-configuration)
- [Integration with Event Sourcing](#integration-with-event-sourcing)
  - [Comments and Reactions in Aggregates](#comments-and-reactions-in-aggregates)
  - [Comments and Reactions in Projections](#comments-and-reactions-in-projections)
  - [Comments and Reactions in Commands](#comments-and-reactions-in-commands)
- [Comment and Reaction Examples](#comment-and-reaction-examples)
  - [Post Comments and Reactions](#post-comments-and-reactions)
  - [Todo Comments and Reactions](#todo-comments-and-reactions)
  - [Team Comments and Reactions](#team-comments-and-reactions)
- [Common Patterns and Best Practices](#common-patterns-and-best-practices)
  - [Comment Moderation](#comment-moderation)
  - [Reaction Limitations](#reaction-limitations)
  - [Nested Comments](#nested-comments)
  - [Reactions-Only Mode](#reactions-only-mode)
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

This document provides a comprehensive guide to implementing comments and reactions in event sourcing for the Enhanced Laravel Application (ELA). Comments and reactions allow users to interact with content by adding comments and expressing reactions. This document covers the concept of comments and reactions, their implementation using `spatie/laravel-comments`, and integration with event sourcing.

## Prerequisites

- **Required Prior Steps:**
  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [Event Sourcing Projectors](030-projectors.md)
  - [Event Sourcing State Machines](080-state-machines.md)
  - [Event Sourcing Roles and Permissions](090-roles-permissions.md)
  - [Package Installation](../030-core-components/010-package-installation.md) (specifically `spatie/laravel-comments`)

- **Required Packages:**
  - `spatie/laravel-event-sourcing`: ^7.0
  - `spatie/laravel-comments`: ^1.0
  - `spatie/laravel-comments-livewire`: ^1.0

- **Required Knowledge:**
  - Understanding of event sourcing principles
  - Familiarity with comment systems
  - Understanding of state machines
  - Understanding of roles and permissions

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Understanding comments and reactions concepts | 1 hour |
| Setting up base comment and reaction structure | 2 hours |
| Implementing comment and reaction functionality | 3 hours |
| Integrating with event sourcing | 2 hours |
| Testing comments and reactions | 2 hours |
| **Total** | **10 hours** |
</details>

## Comments and Reactions Concept

<details>
<summary>Comment State Transitions Diagram</summary>

This diagram illustrates the state transitions for comments in the Enhanced Laravel Application (ELA). It shows the possible states a comment can be in and the valid transitions between these states.

![Comment State Transitions](../../illustrations/thumbnails/mermaid/light/comment-state-transitions-light-thumb.svg)

For the full diagram, see:
- [Comment State Transitions (Light Mode)](../../illustrations/mermaid/light/comment-state-transitions-light.mmd)
- [Comment State Transitions (Dark Mode)](../../illustrations/mermaid/dark/comment-state-transitions-dark.mmd)
</details>

### What are Comments and Reactions?

Comments and reactions are ways for users to interact with content:

- **Comments**: Text-based responses to content
- **Reactions**: Simple expressions of emotion or opinion (e.g., like, love, laugh)

In the context of event sourcing, comments and reactions are:

1. Entities with their own lifecycle
2. Associated with commentable entities (e.g., posts, todos)
3. Subject to moderation and approval
4. Tracked through events

Comments and reactions help create a more interactive and engaging user experience.

### Comments and Reactions Responsibilities

Comments and reactions have several key responsibilities:

1. **Content Interaction**: Allow users to interact with content
2. **Feedback Collection**: Collect user feedback on content
3. **Community Building**: Foster a sense of community among users
4. **Engagement Tracking**: Track user engagement with content

### Comments and Reactions Types

There are several types of comments and reactions that can be implemented:

1. **Top-level Comments**: Comments directly on content
2. **Nested Comments**: Replies to other comments
3. **Moderated Comments**: Comments that require approval before being visible
4. **Reactions**: Simple expressions of emotion or opinion

In the ELA, we use a combination of these types to provide a flexible and powerful comment and reaction system.

## Integration with spatie/laravel-comments

### Comment Model

The Comment model is implemented using `spatie/laravel-comments`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Comment as SpatieComment;
use Spatie\ModelStates\HasStates;
use App\States\Comment\CommentState;

class Comment extends SpatieComment
{
    use HasStates;

    protected $casts = [
        'state' => CommentState::class,
    ];

    protected $fillable = [
        'id',
        'content',
        'user_id',
        'commentable_type',
        'commentable_id',
        'parent_id',
        'state',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];
}
```text

### Reaction Model

The Reaction model is implemented as follows:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReaction extends Model
{
    protected $fillable = [
        'comment_id',
        'user_id',
        'type',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```php
### Commentable Interface

The Commentable interface is implemented using `spatie/laravel-comments`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\Comments\Models\Concerns\InteractsWithComments;

class Post extends Model implements \Spatie\Comments\Models\Interfaces\Commentable
{
    use HasComments;
    use InteractsWithComments;

    // Determine if comments are enabled for this model
    public function commentsAreEnabled(): bool
    {
        return $this->comments_enabled;
    }

    // Determine if comments should be automatically approved
    public function commentsAreAutoApproved(): bool
    {
        return $this->auto_approve_comments;
    }

    // Determine if only reactions are allowed (no text comments)
    public function commentsAreReactionsOnly(): bool
    {
        return $this->reactions_only;
    }
}
```text

## Implementing Comments and Reactions

<details>
<summary>Comment and Reaction Structure Diagram</summary>

This diagram illustrates the structure of comments and reactions in the Enhanced Laravel Application (ELA), showing the relationships between models and their integration with event sourcing.

![Comment and Reaction Structure](../../illustrations/thumbnails/mermaid/light/comment-reaction-structure-light-thumb.svg)

For the full diagram, see:
- [Comment and Reaction Structure (Light Mode)](../../illustrations/mermaid/light/comment-reaction-structure-light.mmd)
- [Comment and Reaction Structure (Dark Mode)](../../illustrations/mermaid/dark/comment-reaction-structure-dark.mmd)
</details>

### Base Comment Structure

In the ELA, comments are implemented using a base structure:

```php
<?php

namespace App\States\Comment;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class CommentState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Approved::class)
            ->allowTransition(Pending::class, Rejected::class)
            ->allowTransition(Pending::class, Deleted::class)
            ->allowTransition(Approved::class, Deleted::class)
            ->allowTransition(Rejected::class, Deleted::class);
    }
}

class Pending extends CommentState
{
    public static $name = 'pending';
}

class Approved extends CommentState
{
    public static $name = 'approved';
}

class Rejected extends CommentState
{
    public static $name = 'rejected';
}

class Deleted extends CommentState
{
    public static $name = 'deleted';
}
```php
### Base Reaction Structure

Reactions are implemented using a simple structure:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReaction extends Model
{
    protected $fillable = [
        'comment_id',
        'user_id',
        'type',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```text

### Comment and Reaction Configuration

Comments and reactions are configured in the `config/comments.php` file:

```php
<?php

return [
    'models' => [
        'comment' => \App\Models\Comment::class,
        'user' => \App\Models\User::class,
    ],

    'automatically_approve_all_comments' => false,

    'allowed_reactions' => ['👍', '🥳', '👀', '😍', '💅'],

    'notifications' => [
        'comment_created' => [
            'commentator' => true,
            'commentable_author' => true,
            'mentioned_users' => true,
            'parent_commentator' => true,
        ],
    ],
];
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
    participant CommentAggregate
    participant EventStore
    participant CommentProjector
    participant CommentModel

    User->>Command: AddCommentCommand
    Command->>CommandHandler: Handle
    CommandHandler->>CommentAggregate: addComment
    CommentAggregate->>EventStore: CommentAdded
    EventStore->>CommentProjector: Process Event
    CommentProjector->>CommentModel: Create Comment

    User->>Command: AddReactionCommand
    Command->>CommandHandler: Handle
    CommandHandler->>CommentAggregate: addReaction
    CommentAggregate->>EventStore: ReactionAdded
    EventStore->>CommentProjector: Process Event
    CommentProjector->>CommentModel: Add Reaction
```text

For dark mode, see [Event Sourcing Integration (Dark Mode)](../../illustrations/mermaid/dark/comment-event-sourcing-dark.mmd)
</details>

### Comments and Reactions in Aggregates

Comments and reactions are managed through the Comment aggregate:

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
}
```php
### Comments and Reactions in Projections

Comments and reactions are projected to read models:

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
```text

### Comments and Reactions in Commands

Comments and reactions are managed through commands:

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
```php
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

## Comment and Reaction Examples

### Post Comments and Reactions

Post comments and reactions are implemented as follows:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\Comments\Models\Concerns\InteractsWithComments;

class Post extends Model implements \Spatie\Comments\Models\Interfaces\Commentable
{
    use HasComments;
    use InteractsWithComments;

    // Determine if comments are enabled for this model
    public function commentsAreEnabled(): bool
    {
        return $this->comments_enabled;
    }

    // Determine if comments should be automatically approved
    public function commentsAreAutoApproved(): bool
    {
        return $this->auto_approve_comments;
    }

    // Determine if only reactions are allowed (no text comments)
    public function commentsAreReactionsOnly(): bool
    {
        return $this->reactions_only;
    }
}
```php
Creating a comment on a post:

```php
// Create a comment on a post
$command = new CreateCommentCommand(
    content: 'This is a great post!',
    commentableType: Post::class,
    commentableId: $post->id
);

$commentId = $this->commandBus->dispatch($command);
```text

Adding a reaction to a post comment:

```php
// Add a reaction to a comment
$command = new AddReactionCommand(
    commentId: $commentId,
    type: '👍'
);

$this->commandBus->dispatch($command);
```php
### Todo Comments and Reactions

Todo comments and reactions are implemented as follows:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\Comments\Models\Concerns\InteractsWithComments;

class Todo extends Model implements \Spatie\Comments\Models\Interfaces\Commentable
{
    use HasComments;
    use InteractsWithComments;

    // Determine if comments are enabled for this model
    public function commentsAreEnabled(): bool
    {
        return true; // Comments are always enabled for todos
    }

    // Determine if comments should be automatically approved
    public function commentsAreAutoApproved(): bool
    {
        return true; // Comments are automatically approved for todos
    }

    // Determine if only reactions are allowed (no text comments)
    public function commentsAreReactionsOnly(): bool
    {
        return false; // Both comments and reactions are allowed for todos
    }
}
```text

Creating a comment on a todo:

```php
// Create a comment on a todo
$command = new CreateCommentCommand(
    content: 'I\'ve started working on this task.',
    commentableType: Todo::class,
    commentableId: $todo->id
);

$commentId = $this->commandBus->dispatch($command);
```php
Adding a reaction to a todo comment:

```php
// Add a reaction to a comment
$command = new AddReactionCommand(
    commentId: $commentId,
    type: '👀'
);

$this->commandBus->dispatch($command);
```text

### Team Comments and Reactions

Team comments and reactions are implemented as follows:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\Comments\Models\Concerns\InteractsWithComments;

class Team extends Model implements \Spatie\Comments\Models\Interfaces\Commentable
{
    use HasComments;
    use InteractsWithComments;

    // Determine if comments are enabled for this model
    public function commentsAreEnabled(): bool
    {
        return $this->comments_enabled;
    }

    // Determine if comments should be automatically approved
    public function commentsAreAutoApproved(): bool
    {
        return false; // Comments require approval for teams
    }

    // Determine if only reactions are allowed (no text comments)
    public function commentsAreReactionsOnly(): bool
    {
        return $this->reactions_only;
    }
}
```php
Creating a comment on a team:

```php
// Create a comment on a team
$command = new CreateCommentCommand(
    content: 'Welcome to the team!',
    commentableType: Team::class,
    commentableId: $team->id
);

$commentId = $this->commandBus->dispatch($command);
```text

Approving a team comment:

```php
// Approve a comment
$command = new ApproveCommentCommand(
    commentId: $commentId
);

$this->commandBus->dispatch($command);
```php
## Common Patterns and Best Practices

### Comment Moderation

Implement comment moderation to ensure quality content:

```php
// Approve a comment
$command = new ApproveCommentCommand(
    commentId: $commentId
);

$this->commandBus->dispatch($command);

// Reject a comment
$command = new RejectCommentCommand(
    commentId: $commentId,
    reason: 'Inappropriate content'
);

$this->commandBus->dispatch($command);
```text

Use policies to control who can moderate comments:

```php
<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function approve(User $user, Comment $comment): bool
    {
        // Check if user has permission to moderate comments
        return $user->can('comment.moderate');
    }

    public function reject(User $user, Comment $comment): bool
    {
        // Check if user has permission to moderate comments
        return $user->can('comment.moderate');
    }
}
```php
### Reaction Limitations

Limit the types of reactions that can be added:

```php
// config/comments.php
return [
    'allowed_reactions' => ['👍', '🥳', '👀', '😍', '💅'],
];
```text

Validate reaction types in commands:

```php
<?php

namespace App\Commands\Comments;

use Hirethunk\Verbs\Command;

class AddReactionCommand extends Command
{
    public function __construct(
        public string $commentId,
        public string $type
    ) {}

    public function rules(): array
    {
        return [
            'commentId' => ['required', 'string', 'exists:comments,id'],
            'type' => ['required', 'string', 'in:' . implode(',', config('comments.allowed_reactions'))],
        ];
    }
}
```php
### Nested Comments

Implement nested comments to allow replies:

```php
// Create a reply to a comment
$command = new CreateCommentCommand(
    content: 'I agree with your comment!',
    commentableType: Post::class,
    commentableId: $post->id,
    parentId: $commentId
);

$replyId = $this->commandBus->dispatch($command);
```text

Retrieve nested comments:

```php
// Get replies to a comment
$replies = Comment::where('parent_id', $commentId)
    ->where('state', Approved::class)
    ->orderBy('created_at', 'asc')
    ->get();
```javascript
### Reactions-Only Mode

<details>
<summary>Reactions-Only Mode Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[User] --> B{Reactions-Only Mode?}
    B -->|Yes| C[Show Reaction Buttons]
    B -->|No| D[Show Comment Form and Reaction Buttons]
    C --> E[User Clicks Reaction]
    D --> F[User Adds Comment]
    D --> E
    E --> G[Add Reaction to Database]
    F --> H[Add Comment to Database]
    G --> I[Update UI]
    H --> I
```text

For dark mode, see [Reactions-Only Mode (Dark Mode)](../../illustrations/mermaid/dark/reactions-only-mode-dark.mmd)
</details>

Implement reactions-only mode to limit interaction to reactions:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\Comments\Models\Concerns\InteractsWithComments;

class Post extends Model implements \Spatie\Comments\Models\Interfaces\Commentable
{
    use HasComments;
    use InteractsWithComments;

    // Determine if only reactions are allowed (no text comments)
    public function commentsAreReactionsOnly(): bool
    {
        return $this->reactions_only;
    }
}
```php
Validate reactions-only mode in command handlers:

```php
<?php

namespace App\CommandHandlers\Comments;

use App\Commands\Comments\CreateCommentCommand;
use App\Aggregates\CommentAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use Illuminate\Support\Str;

class CreateCommentCommandHandler extends CommandHandler
{
    public function handle(CreateCommentCommand $command)
    {
        // Check if reactions-only mode is enabled
        $commentableType = $command->commentableType;
        $commentableId = $command->commentableId;
        $commentable = $commentableType::findOrFail($commentableId);

        $reactionsOnly = method_exists($commentable, 'commentsAreReactionsOnly') && $commentable->commentsAreReactionsOnly();

        if ($reactionsOnly && !empty($command->content)) {
            throw new \Exception("Only reactions are allowed for this resource");
        }

        // Rest of the handler...
    }
}
```text

## Benefits and Challenges

### Benefits

1. **User Engagement**: Comments and reactions increase user engagement with content
2. **Feedback Collection**: Comments and reactions provide valuable feedback on content
3. **Community Building**: Comments and reactions foster a sense of community among users
4. **Flexibility**: The system supports various types of interactions (comments, reactions, nested comments)

### Challenges

1. **Moderation**: Comments require moderation to ensure quality content
2. **Performance**: Large numbers of comments and reactions can impact performance
3. **Spam**: Comments can be targets for spam and abuse

### Mitigation Strategies

1. **Moderation System**: Implement a robust moderation system to ensure quality content
2. **Pagination**: Use pagination to handle large numbers of comments
3. **Rate Limiting**: Implement rate limiting to prevent spam and abuse
4. **Reactions-Only Mode**: Use reactions-only mode for sensitive content

## Troubleshooting

### Common Issues

<details>
<summary>Comments not appearing</summary>

**Symptoms:**
- Comments are created but not visible to users
- Comments are stuck in pending state

**Possible Causes:**
- Comments require approval but haven't been approved
- Comments are disabled for the commentable model
- User doesn't have permission to view comments

**Solutions:**
1. Check if comments require approval and approve them
2. Verify that comments are enabled for the commentable model
3. Check user permissions for viewing comments
</details>

<details>
<summary>Reactions not working</summary>

**Symptoms:**
- Reactions cannot be added to comments
- Reactions are not visible to users

**Possible Causes:**
- Reaction type is not allowed
- User has already added the same reaction
- Comment is deleted or rejected

**Solutions:**
1. Verify that the reaction type is allowed in the configuration
2. Check if the user has already added the same reaction
3. Ensure the comment is not deleted or rejected
</details>

<details>
<summary>Nested comments not working</summary>

**Symptoms:**
- Replies cannot be added to comments
- Replies are not visible to users

**Possible Causes:**
- Parent comment is not approved
- Parent comment is deleted or rejected
- Nested comments are not properly configured

**Solutions:**
1. Ensure the parent comment is approved
2. Verify that the parent comment is not deleted or rejected
3. Check the configuration for nested comments
</details>

### Solutions

For detailed solutions to common issues, refer to the following resources:

- [Spatie Laravel Comments Documentation](https:/spatie.be/docs/laravel-comments)
- [Laravel Documentation](https:/laravel.com/docs)
- [Event Sourcing Documentation](https:/spatie.be/docs/laravel-event-sourcing)

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing State Machines](080-state-machines.md) - Detailed documentation on state machine implementation
- [Event Sourcing Roles and Permissions](090-roles-permissions.md) - Detailed documentation on roles and permissions implementation

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added comment state transitions diagram, comment and reaction structure diagram, event sourcing integration diagram, reactions-only mode diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
