# Phase 1: Post Aggregate

**Version:** 1.1.0 **Date:** 2023-11-13 **Author:** AI Assistant **Status:** Complete **Progress:** 100%

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Post Aggregate Structure](#post-aggregate-structure)
  - [State Properties](#state-properties)
  - [Post States](#post-states)
- [Post Commands](#post-commands)
  - [CreatePost Command](#createpost-command)
  - [UpdatePost Command](#updatepost-command)
  - [PublishPost Command](#publishpost-command)
  - [UnpublishPost Command](#unpublishpost-command)
  - [SchedulePost Command](#schedulepost-command)
  - [ArchivePost Command](#archivepost-command)
  - [AddPostTag Command](#addposttag-command)
  - [RemovePostTag Command](#removeposttag-command)
- [Post Events](#post-events)
  - [PostCreated Event](#postcreated-event)
  - [PostUpdated Event](#postupdated-event)
  - [PostPublished Event](#postpublished-event)
  - [PostUnpublished Event](#postunpublished-event)
  - [PostScheduled Event](#postscheduled-event)
  - [PostArchived Event](#postarchived-event)
  - [PostTagAdded Event](#posttagadded-event)
  - [PostTagRemoved Event](#posttagremoved-event)
- [Post Aggregate Implementation](#post-aggregate-implementation)
  - [Command Methods](#command-methods)
  - [Apply Methods](#apply-methods)
  - [Business Rules](#business-rules)
- [Integration with Comments and Reactions](#integration-with-comments-and-reactions)
  - [Comment Configuration](#comment-configuration)
  - [Reaction Configuration](#reaction-configuration)
- [State Transitions](#state-transitions)
  - [State Diagram](#state-diagram)
  - [Transition Rules](#transition-rules)
- [Command Handlers](#command-handlers)
  - [CreatePostCommandHandler](#createpostcommandhandler)
  - [UpdatePostCommandHandler](#updatepostcommandhandler)
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

This document provides detailed documentation on the Post aggregate in the event sourcing implementation for the
Enhanced Laravel Application (ELA). The Post aggregate is responsible for managing post creation, updates, publication,
scheduling, and archival. This document covers the commands, events, state transitions, and business rules for the Post
aggregate.

## Prerequisites

- **Required Prior Steps:**

  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [User Aggregate](020-010-user-aggregate.md)
  - [Team Aggregate](020-020-team-aggregate.md)
  - [CQRS Configuration](../030-core-components/030-cqrs-configuration.md)
  - [Package Installation](../030-core-components/010-package-installation.md)

- **Required Packages:**

  - `spatie/laravel-event-sourcing`: ^7.0
  - `hirethunk/verbs`: ^1.0
  - `spatie/laravel-data`: ^3.0
  - `spatie/laravel-model-states`: ^2.0
  - `spatie/laravel-tags`: ^4.0
  - `spatie/laravel-comments`: ^1.0

- **Required Knowledge:**

  - Understanding of event sourcing principles
  - Familiarity with content management systems
  - Understanding of state machines

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task                                    | Estimated Time |
| --------------------------------------- | -------------- |
| Setting up Post aggregate structure     | 1 hour         |
| Implementing Post commands              | 2 hours        |
| Implementing Post events                | 1 hour         |
| Implementing command methods            | 2 hours        |
| Implementing apply methods              | 1 hour         |
| Integrating with comments and reactions | 2 hours        |
| Testing Post aggregate                  | 2 hours        |
| **Total**                               | **11 hours**   |

</details>

## Post Aggregate Structure

### State Properties

The Post aggregate maintains the following state properties:

```php
protected string $title;
protected string $slug;
protected string $content;
protected string $excerpt;
protected string $authorId;
protected ?string $teamId = null;
protected array $meta = [];
protected array $tags = [];
protected string $state;
protected ?string $publishedAt = null;
protected ?string $scheduledAt = null;
protected ?string $archivedAt = null;
protected ?string $archiveReason = null;
protected bool $commentsEnabled = true;
protected bool $reactionsOnly = false;
```text

### Post States

The Post aggregate can be in one of the following states:

1. **Draft**: Post is in draft mode and not publicly visible
2. **PendingReview**: Post is pending review before publication
3. **Published**: Post is published and publicly visible
4. **Scheduled**: Post is scheduled for future publication
5. **Archived**: Post has been archived

These states are implemented using `spatie/laravel-model-states` and are integrated with the event sourcing system.

## Post Commands

<details>
<summary>Post Command Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[CreatePost Command] --> B[CreatePostCommandHandler]
    B --> C[PostAggregateRoot]
    C --> D{Valid?}
    D -->|Yes| E[Record PostCreated Event]
    D -->|No| F[Throw Exception]
    E --> G[Apply PostCreated Event]
    G --> H[Update Post State]
    E --> I[Event Store]
    I --> J[PostProjector]
    J --> K[Post Model]
```php
For dark mode, see [Post Command Flow (Dark Mode)](../../illustrations/mermaid/dark/post-command-flow-dark.mmd)

</details>

### CreatePost Command

Creates a new post.

```php
<?php

namespace App\Commands\Posts;

use Hirethunk\Verbs\Command;

class CreatePostCommand extends Command
{
    public function __construct(
        public string $title,
        public string $content,
        public string $authorId,
        public ?string $teamId = null,
        public ?string $excerpt = null,
        public array $meta = [],
        public array $tags = []
    ) {}

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'authorId' => ['required', 'string', 'exists:users,id'],
            'teamId' => ['nullable', 'string', 'exists:teams,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'meta' => ['sometimes', 'array'],
            'tags' => ['sometimes', 'array'],
        ];
    }
}
```text

### UpdatePost Command

Updates post content.

```php
<?php

namespace App\Commands\Posts;

use Hirethunk\Verbs\Command;

class UpdatePostCommand extends Command
{
    public function __construct(
        public string $postId,
        public string $title,
        public string $content,
        public ?string $excerpt = null,
        public array $meta = []
    ) {}

    public function rules(): array
    {
        return [
            'postId' => ['required', 'string', 'exists:posts,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
```php
### PublishPost Command

Publishes a post.

```php
<?php

namespace App\Commands\Posts;

use Hirethunk\Verbs\Command;

class PublishPostCommand extends Command
{
    public function __construct(
        public string $postId
    ) {}

    public function rules(): array
    {
        return [
            'postId' => ['required', 'string', 'exists:posts,id'],
        ];
    }
}
```text

### UnpublishPost Command

Unpublishes a post.

```php
<?php

namespace App\Commands\Posts;

use Hirethunk\Verbs\Command;

class UnpublishPostCommand extends Command
{
    public function __construct(
        public string $postId
    ) {}

    public function rules(): array
    {
        return [
            'postId' => ['required', 'string', 'exists:posts,id'],
        ];
    }
}
```php
### SchedulePost Command

Schedules a post for future publication.

```php
<?php

namespace App\Commands\Posts;

use Hirethunk\Verbs\Command;

class SchedulePostCommand extends Command
{
    public function __construct(
        public string $postId,
        public string $scheduledAt
    ) {}

    public function rules(): array
    {
        return [
            'postId' => ['required', 'string', 'exists:posts,id'],
            'scheduledAt' => ['required', 'date', 'after:now'],
        ];
    }
}
```text

### ArchivePost Command

Archives a post.

```php
<?php

namespace App\Commands\Posts;

use Hirethunk\Verbs\Command;

class ArchivePostCommand extends Command
{
    public function __construct(
        public string $postId,
        public ?string $reason = null
    ) {}

    public function rules(): array
    {
        return [
            'postId' => ['required', 'string', 'exists:posts,id'],
            'reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
```php
### AddPostTag Command

Adds a tag to a post.

```php
<?php

namespace App\Commands\Posts;

use Hirethunk\Verbs\Command;

class AddPostTagCommand extends Command
{
    public function __construct(
        public string $postId,
        public string $tag
    ) {}

    public function rules(): array
    {
        return [
            'postId' => ['required', 'string', 'exists:posts,id'],
            'tag' => ['required', 'string', 'max:50'],
        ];
    }
}
```text

### RemovePostTag Command

Removes a tag from a post.

```php
<?php

namespace App\Commands\Posts;

use Hirethunk\Verbs\Command;

class RemovePostTagCommand extends Command
{
    public function __construct(
        public string $postId,
        public string $tag
    ) {}

    public function rules(): array
    {
        return [
            'postId' => ['required', 'string', 'exists:posts,id'],
            'tag' => ['required', 'string', 'max:50'],
        ];
    }
}
```php
## Post Events

### PostCreated Event

Represents a post creation event.

```php
<?php

namespace App\Events\Posts;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PostCreated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:

- `title`: Post title
- `slug`: Post slug (URL-friendly version of the title)
- `content`: Post content
- `excerpt`: Post excerpt
- `author_id`: ID of the post author
- `team_id`: ID of the team (if applicable)
- `meta`: Post metadata
- `tags`: Post tags
- `created_at`: Creation timestamp

### PostUpdated Event

Represents a post update event.

```php
<?php

namespace App\Events\Posts;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PostUpdated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:

- `title`: Updated post title
- `slug`: Updated post slug
- `content`: Updated post content
- `excerpt`: Updated post excerpt
- `meta`: Updated post metadata
- `updated_at`: Update timestamp

### PostPublished Event

Represents a post publication event.

```php
<?php

namespace App\Events\Posts;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PostPublished extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:

- `published_at`: Publication timestamp

### PostUnpublished Event

Represents a post unpublication event.

```php
<?php

namespace App\Events\Posts;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PostUnpublished extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:

- `unpublished_at`: Unpublication timestamp

### PostScheduled Event

Represents a post scheduling event.

```php
<?php

namespace App\Events\Posts;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PostScheduled extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:

- `scheduled_at`: Scheduled publication timestamp

### PostArchived Event

Represents a post archival event.

```php
<?php

namespace App\Events\Posts;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PostArchived extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:

- `reason`: Archival reason
- `archived_at`: Archival timestamp

### PostTagAdded Event

Represents a post tag addition event.

```php
<?php

namespace App\Events\Posts;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PostTagAdded extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:

- `tag`: Added tag
- `added_at`: Addition timestamp

### PostTagRemoved Event

Represents a post tag removal event.

```php
<?php

namespace App\Events\Posts;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PostTagRemoved extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:

- `tag`: Removed tag
- `removed_at`: Removal timestamp

## Post Aggregate Implementation

### Command Methods

The Post aggregate implements methods to handle various commands:

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Events\Posts\PostCreated;
use App\Events\Posts\PostUpdated;
use App\Events\Posts\PostPublished;
use App\Events\Posts\PostUnpublished;
use App\Events\Posts\PostScheduled;
use App\Events\Posts\PostArchived;
use App\Events\Posts\PostTagAdded;
use App\Events\Posts\PostTagRemoved;
use App\States\Post\Draft;
use App\States\Post\PendingReview;
use App\States\Post\Published;
use App\States\Post\Scheduled;
use App\States\Post\Archived;
use App\Exceptions\Posts\InvalidPostStateTransitionException;
use Illuminate\Support\Str;

class PostAggregateRoot extends AggregateRoot
{
    protected string $title;
    protected string $slug;
    protected string $content;
    protected string $excerpt;
    protected string $authorId;
    protected ?string $teamId = null;
    protected array $meta = [];
    protected array $tags = [];
    protected string $state;
    protected ?string $publishedAt = null;
    protected ?string $scheduledAt = null;
    protected ?string $archivedAt = null;
    protected ?string $archiveReason = null;
    protected bool $commentsEnabled = true;
    protected bool $reactionsOnly = false;

    public function createPost(
        string $title,
        string $content,
        string $authorId,
        ?string $teamId = null,
        ?string $excerpt = null,
        array $meta = [],
        array $tags = []
    ): self {
        $slug = Str::slug($title);

        if (empty($excerpt)) {
            $excerpt = Str::limit(strip_tags($content), 200);
        }

        $this->recordThat(new PostCreated([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'author_id' => $authorId,
            'team_id' => $teamId,
            'meta' => $meta,
            'tags' => $tags,
            'created_at' => now(),
        ]));

        return $this;
    }

    public function updatePost(
        string $title,
        string $content,
        ?string $excerpt = null,
        array $meta = []
    ): self {
        if ($this->state === Archived::class) {
            throw new InvalidPostStateTransitionException(
                "Cannot update an archived post"
            );
        }

        $slug = Str::slug($title);

        if (empty($excerpt)) {
            $excerpt = Str::limit(strip_tags($content), 200);
        }

        $this->recordThat(new PostUpdated([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'meta' => $meta,
            'updated_at' => now(),
        ]));

        return $this;
    }

    public function publishPost(): self
    {
        if ($this->state === Archived::class) {
            throw new InvalidPostStateTransitionException(
                "Cannot publish an archived post"
            );
        }

        if ($this->state === Published::class) {
            // Post is already published, do nothing
            return $this;
        }

        $this->recordThat(new PostPublished([
            'published_at' => now(),
        ]));

        return $this;
    }

    public function unpublishPost(): self
    {
        if ($this->state !== Published::class) {
            throw new InvalidPostStateTransitionException(
                "Cannot unpublish a post that is not published"
            );
        }

        $this->recordThat(new PostUnpublished([
            'unpublished_at' => now(),
        ]));

        return $this;
    }

    public function schedulePost(string $scheduledAt): self
    {
        if ($this->state === Archived::class) {
            throw new InvalidPostStateTransitionException(
                "Cannot schedule an archived post"
            );
        }

        if ($this->state === Published::class) {
            throw new InvalidPostStateTransitionException(
                "Cannot schedule a published post"
            );
        }

        $this->recordThat(new PostScheduled([
            'scheduled_at' => $scheduledAt,
        ]));

        return $this;
    }

    public function archivePost(?string $reason = null): self
    {
        if ($this->state === Archived::class) {
            // Post is already archived, do nothing
            return $this;
        }

        $this->recordThat(new PostArchived([
            'reason' => $reason,
            'archived_at' => now(),
        ]));

        return $this;
    }

    public function addPostTag(string $tag): self
    {
        if ($this->state === Archived::class) {
            throw new InvalidPostStateTransitionException(
                "Cannot add tags to an archived post"
            );
        }

        // Check if tag already exists
        if (in_array($tag, $this->tags)) {
            // Tag already exists, do nothing
            return $this;
        }

        $this->recordThat(new PostTagAdded([
            'tag' => $tag,
            'added_at' => now(),
        ]));

        return $this;
    }

    public function removePostTag(string $tag): self
    {
        if ($this->state === Archived::class) {
            throw new InvalidPostStateTransitionException(
                "Cannot remove tags from an archived post"
            );
        }

        // Check if tag exists
        if (!in_array($tag, $this->tags)) {
            // Tag doesn't exist, do nothing
            return $this;
        }

        $this->recordThat(new PostTagRemoved([
            'tag' => $tag,
            'removed_at' => now(),
        ]));

        return $this;
    }

    public function configureComments(bool $enabled, bool $reactionsOnly): self
    {
        if ($this->state === Archived::class) {
            throw new InvalidPostStateTransitionException(
                "Cannot configure comments for an archived post"
            );
        }

        $this->commentsEnabled = $enabled;
        $this->reactionsOnly = $reactionsOnly;

        return $this;
    }
}
```text

### Apply Methods

The Post aggregate implements apply methods to update its state based on events:

```php
protected function applyPostCreated(PostCreated $event): void
{
    $this->title = $event->payload['title'];
    $this->slug = $event->payload['slug'];
    $this->content = $event->payload['content'];
    $this->excerpt = $event->payload['excerpt'];
    $this->authorId = $event->payload['author_id'];
    $this->teamId = $event->payload['team_id'];
    $this->meta = $event->payload['meta'];
    $this->tags = $event->payload['tags'];
    $this->state = Draft::class;
}

protected function applyPostUpdated(PostUpdated $event): void
{
    $this->title = $event->payload['title'];
    $this->slug = $event->payload['slug'];
    $this->content = $event->payload['content'];
    $this->excerpt = $event->payload['excerpt'];
    $this->meta = $event->payload['meta'];
}

protected function applyPostPublished(PostPublished $event): void
{
    $this->state = Published::class;
    $this->publishedAt = $event->payload['published_at'];
    $this->scheduledAt = null;
}

protected function applyPostUnpublished(PostUnpublished $event): void
{
    $this->state = Draft::class;
    $this->publishedAt = null;
}

protected function applyPostScheduled(PostScheduled $event): void
{
    $this->state = Scheduled::class;
    $this->scheduledAt = $event->payload['scheduled_at'];
}

protected function applyPostArchived(PostArchived $event): void
{
    $this->state = Archived::class;
    $this->archivedAt = $event->payload['archived_at'];
    $this->archiveReason = $event->payload['reason'];
}

protected function applyPostTagAdded(PostTagAdded $event): void
{
    $this->tags[] = $event->payload['tag'];
}

protected function applyPostTagRemoved(PostTagRemoved $event): void
{
    $this->tags = array_filter($this->tags, function ($tag) use ($event) {
        return $tag !== $event->payload['tag'];
    });

    // Reindex the array
    $this->tags = array_values($this->tags);
}
```php
### Business Rules

The Post aggregate enforces several business rules:

1. **State Transitions**: Only certain state transitions are allowed

   - Draft → PendingReview, Published, Scheduled, Archived
   - PendingReview → Published, Draft, Archived
   - Published → Draft, Archived
   - Scheduled → Published, Draft, Archived
   - Archived → (no transitions allowed)

2. **Post Updates**: Archived posts cannot be updated

3. **Publication**: Archived posts cannot be published

4. **Scheduling**: Published or archived posts cannot be scheduled

5. **Tags**: Archived posts cannot have tags added or removed

## Integration with Comments and Reactions

The Post aggregate integrates with `spatie/laravel-comments` for comments and reactions.

### Comment Configuration

Comments can be enabled or disabled for a post, and can be configured to allow only reactions:

```php
public function configureComments(bool $enabled, bool $reactionsOnly): self
{
    if ($this->state === Archived::class) {
        throw new InvalidPostStateTransitionException(
            "Cannot configure comments for an archived post"
        );
    }

    $this->commentsEnabled = $enabled;
    $this->reactionsOnly = $reactionsOnly;

    return $this;
}
```text

### Reaction Configuration

Reactions are configured through the `spatie/laravel-comments` package:

```php
// config/comments.php
'allowed_reactions' => ['👍', '🥳', '👀', '😍', '💅'],
```php
## State Transitions

### State Diagram

<details>
<summary>Post State Transitions Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
stateDiagram-v2
    [*] --> Draft: createPost
    Draft --> PendingReview: submit
    Draft --> Published: publish
    Draft --> Scheduled: schedule
    Draft --> Archived: archive
    PendingReview --> Published: approve
    PendingReview --> Draft: reject
    PendingReview --> Archived: archive
    Published --> Draft: unpublish
    Published --> Archived: archive
    Scheduled --> Published: publish
    Scheduled --> Draft: unschedule
    Scheduled --> Archived: archive
    Archived --> [*]
```text

For a more detailed diagram, see
[Post Aggregate States (Light Mode)](../../illustrations/mermaid/light/post-aggregate-states-light.mmd)

For dark mode versions, see:

- [Post State Transitions (Dark Mode)](../../illustrations/mermaid/dark/post-state-transitions-dark.mmd)
- [Post Aggregate States (Dark Mode)](../../illustrations/mermaid/dark/post-aggregate-states-dark.mmd)
</details>

The Post aggregate supports the following state transitions:

```html
Draft → PendingReview
Draft → Published
Draft → Scheduled
Draft → Archived
PendingReview → Published
PendingReview → Draft
PendingReview → Archived
Published → Draft
Published → Archived
Scheduled → Published
Scheduled → Draft
Scheduled → Archived
```text

### Transition Rules

State transitions are enforced by the Post aggregate's command methods:

```php
public function publishPost(): self
{
    if ($this->state === Archived::class) {
        throw new InvalidPostStateTransitionException(
            "Cannot publish an archived post"
        );
    }

    if ($this->state === Published::class) {
        // Post is already published, do nothing
        return $this;
    }

    $this->recordThat(new PostPublished([
        'published_at' => now(),
    ]));

    return $this;
}
```php
## Command Handlers

### CreatePostCommandHandler

Handles post creation:

```php
<?php

namespace App\CommandHandlers\Posts;

use App\Commands\Posts\CreatePostCommand;
use App\Aggregates\PostAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use Illuminate\Support\Str;

class CreatePostCommandHandler extends CommandHandler
{
    public function handle(CreatePostCommand $command)
    {
        // Generate a UUID for the post
        $postId = (string) Str::uuid();

        // Create the post
        PostAggregateRoot::retrieve($postId)
            ->createPost(
                $command->title,
                $command->content,
                $command->authorId,
                $command->teamId,
                $command->excerpt,
                $command->meta,
                $command->tags
            )
            ->persist();

        return $postId;
    }
}
```text

### UpdatePostCommandHandler

Handles post updates:

```php
<?php

namespace App\CommandHandlers\Posts;

use App\Commands\Posts\UpdatePostCommand;
use App\Aggregates\PostAggregateRoot;
use Hirethunk\Verbs\CommandHandler;

class UpdatePostCommandHandler extends CommandHandler
{
    public function handle(UpdatePostCommand $command)
    {
        // Authorize the command
        $this->authorize('update', ['App\Models\Post', $command->postId]);

        // Update the post
        PostAggregateRoot::retrieve($command->postId)
            ->updatePost(
                $command->title,
                $command->content,
                $command->excerpt,
                $command->meta
            )
            ->persist();

        return $this->success();
    }
}
```

### Other Command Handlers

Similar command handlers exist for other post commands:

- `PublishPostCommandHandler`
- `UnpublishPostCommandHandler`
- `SchedulePostCommandHandler`
- `ArchivePostCommandHandler`
- `AddPostTagCommandHandler`
- `RemovePostTagCommandHandler`

## Benefits and Challenges

### Benefits

1. **Complete Audit Trail**: Every post action is recorded as an event
2. **State Management**: Clear state transitions with enforced rules
3. **Content History**: Complete history of post content changes
4. **Temporal Queries**: The state of a post at any point in time can be reconstructed

### Challenges

1. **Complexity**: Event sourcing adds complexity to the content management system
2. **Performance**: Reconstructing post state from events can be slow for posts with many events
3. **Integration**: Integrating with comments and reactions requires careful planning

### Mitigation Strategies

1. **Snapshots**: Use snapshots to improve performance for posts with many events
2. **Caching**: Cache post projections to improve read performance
3. **Clear Documentation**: Document the post aggregate thoroughly to help developers understand the system

## Troubleshooting

### Common Issues

<details>
<summary>Post state not updating correctly</summary>

**Symptoms:**

- Post state is not reflecting the expected state after a command
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
<summary>Scheduled posts not publishing automatically</summary>

**Symptoms:**

- Posts scheduled for publication are not automatically published at the scheduled time

**Possible Causes:**

- Missing scheduler configuration
- Incorrect implementation of the scheduled publication job
- Scheduler not running

**Solutions:**

1. Ensure the Laravel scheduler is configured and running
2. Verify the implementation of the scheduled publication job
3. Check the logs for any errors in the scheduler
</details>

### Solutions

For detailed solutions to common issues, refer to the
[Event Sourcing Troubleshooting Guide](070-testing.md#troubleshooting).

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [User Aggregate](020-010-user-aggregate.md) - Detailed documentation on User aggregate
- [Team Aggregate](020-020-team-aggregate.md) - Detailed documentation on Team aggregate
- [Comment Aggregate](020-050-comment-aggregate.md) - Detailed documentation on Comment aggregate
- [Event Sourcing Comments and Reactions](100-comments-reactions.md) - Integration of event sourcing with comments and
  reactions

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date       | Changes                                                                                            | Author       |
| ------- | ---------- | -------------------------------------------------------------------------------------------------- | ------------ |
| 1.1.0   | 2025-05-18 | Added post state transitions diagram, command flow diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0   | 2025-05-18 | Initial version                                                                                    | AI Assistant |

</details>
