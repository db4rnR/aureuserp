# Phase 1: Event Sourcing Queries

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
- [Query Concept](#query-concept)
  - [What is a Query?](#what-is-a-query)
  - [Query Responsibilities](#query-responsibilities)
  - [Query Types](#query-types)
- [Implementing Queries](#implementing-queries)
  - [Base Query Structure](#base-query-structure)
  - [Query Handlers](#query-handlers)
  - [Query Registration](#query-registration)
- [Integration with hirethunk/verbs](#integration-with-hirethunkverbs)
  - [Query Class](#query-class)
  - [Query Handler Class](#query-handler-class)
  - [Query Validation](#query-validation)
- [Building Efficient Read Models](#building-efficient-read-models)
  - [Read Model Design](#read-model-design)
  - [Optimizing for Queries](#optimizing-for-queries)
  - [Caching Strategies](#caching-strategies)
- [Query Examples](#query-examples)
  - [User Queries](#user-queries)
  - [Team Queries](#team-queries)
  - [Post Queries](#post-queries)
  - [Todo Queries](#todo-queries)
  - [Comment Queries](#comment-queries)
  - [Message Queries](#message-queries)
- [Common Patterns and Best Practices](#common-patterns-and-best-practices)
  - [Single Responsibility](#single-responsibility)
  - [Performance Optimization](#performance-optimization)
  - [Error Handling](#error-handling)
  - [Pagination](#pagination)
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

This document provides a comprehensive guide to implementing queries in event sourcing for the Enhanced Laravel Application (ELA). Queries are responsible for retrieving data from read models optimized for specific use cases. This document covers the concept of queries, their implementation using `hirethunk/verbs`, and best practices for designing efficient queries.

## Prerequisites

- **Required Prior Steps:**
  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [Event Sourcing Projectors](030-projectors.md)
  - [CQRS Configuration](../030-core-components/030-cqrs-configuration.md)
  - [Package Installation](../030-core-components/010-package-installation.md) (specifically `hirethunk/verbs`)

- **Required Packages:**
  - `hirethunk/verbs`: ^1.0
  - `spatie/laravel-data`: ^3.0

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

| Task | Estimated Time |
|------|----------------|
| Understanding query concepts | 1 hour |
| Setting up base query structure | 1 hour |
| Implementing query handlers | 2 hours per aggregate |
| Optimizing read models | 1 hour per aggregate |
| Testing queries | 1 hour per aggregate |
| **Total** | **5+ hours per aggregate** |
</details>

## Query Concept

<details>
<summary>Query Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart LR
    A[Client] --> B[Query]
    B --> C[Query Handler]
    C --> D[Read Model]
    D --> E[Query Result]
    E --> A
```text

For dark mode, see [Query Flow (Dark Mode)](../../illustrations/mermaid/dark/query-flow-dark.mmd)
</details>

### What is a Query?

A query is a request for information from the system. In the context of CQRS and event sourcing, queries are responsible for retrieving data from read models that have been optimized for specific use cases. Queries are:

1. Immutable objects that represent a request for data
2. Processed by query handlers that retrieve data from read models
3. Designed to be efficient and optimized for specific use cases

Queries are a key part of the CQRS pattern, separating the read side (queries) from the write side (commands).

### Query Responsibilities

Queries have several key responsibilities:

1. **Data Retrieval**: Retrieving data from read models
2. **Filtering**: Filtering data based on criteria
3. **Sorting**: Sorting data based on criteria
4. **Pagination**: Paginating data for efficient retrieval
5. **Transformation**: Transforming data into the format required by the client

### Query Types

There are several types of queries that can be implemented:

1. **Item Queries**: Retrieve a single item by ID
2. **List Queries**: Retrieve a list of items with filtering, sorting, and pagination
3. **Search Queries**: Search for items based on search criteria
4. **Aggregate Queries**: Retrieve aggregated data (counts, sums, averages, etc.)
5. **Report Queries**: Generate reports based on data

In the ELA, we primarily use Item and List queries, with some Search and Aggregate queries for specific use cases.

## Implementing Queries

### Base Query Structure

In the ELA, queries are implemented as classes that extend `Hirethunk\Verbs\Query`:

```php
<?php

namespace App\Queries;

use Hirethunk\Verbs\Query;

class GetUserQuery extends Query
{
    public function __construct(
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```php
### Query Handlers

Query handlers are classes that process queries and return data:

```php
<?php

namespace App\QueryHandlers;

use App\Queries\GetUserQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\User;

class GetUserQueryHandler extends QueryHandler
{
    public function handle(GetUserQuery $query)
    {
        return User::findOrFail($query->userId);
    }
}
```text

### Query Registration

Queries and query handlers are automatically registered by the `hirethunk/verbs` package based on naming conventions. The package will automatically match `GetUserQuery` with `GetUserQueryHandler`.

## Integration with hirethunk/verbs

### Query Class

The `Query` class from `hirethunk/verbs` provides the foundation for implementing queries:

```php
use Hirethunk\Verbs\Query;

class GetUserQuery extends Query
{
    // Implementation
}
```php
### Query Handler Class

The `QueryHandler` class from `hirethunk/verbs` provides the foundation for implementing query handlers:

```php
use Hirethunk\Verbs\QueryHandler;

class GetUserQueryHandler extends QueryHandler
{
    // Implementation
}
```text

### Query Validation

Queries can include validation rules:

```php
public function rules(): array
{
    return [
        'userId' => ['required', 'string', 'exists:users,id'],
    ];
}
```sql
These rules are automatically validated by the `hirethunk/verbs` package before the query is processed.

## Building Efficient Read Models

<details>
<summary>Query Architecture Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
classDiagram
    class Query {
        +validate()
        +rules()
    }

    class QueryHandler {
        +handle(Query query)
    }

    class ReadModel {
        +id
        +attributes
        +find()
        +findAll()
    }

    class QueryResult {
        +data
        +meta
    }

    Query --> QueryHandler: processed by
    QueryHandler --> ReadModel: retrieves from
    ReadModel --> QueryResult: transformed to
```text

For dark mode, see [Query Architecture (Dark Mode)](../../illustrations/mermaid/dark/query-architecture-dark.mmd)
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
    C --> D[Indexed Columns]
    C --> E[Denormalized Data]
    C --> F[Cached Results]
    C --> G[Full-text Search]
    D --> H[Fast Lookups]
    E --> I[Reduced Joins]
    F --> J[Reduced Database Load]
    G --> K[Efficient Text Search]
```php
For dark mode, see [Read Model Optimization (Dark Mode)](../../illustrations/mermaid/dark/read-model-optimization-dark.mmd)
</details>

Read models should be optimized for the specific queries they need to support:

```php
// Example: Optimizing for user search
Schema::create('users', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name');
    $table->string('email')->unique();
    $table->json('profile')->nullable();
    $table->string('state');
    $table->text('search_vector')->nullable();

    // Add indexes for frequently queried columns
    $table->index('name');
    $table->index('email');
    $table->index('state');
    $table->index('search_vector');
});
```text

### Caching Strategies

Implement caching for frequently accessed data:

```php
public function handle(GetUserQuery $query)
{
    return Cache::remember('user:' . $query->userId, 3600, function () use ($query) {
        return User::findOrFail($query->userId);
    });
}
```php
## Query Examples

### User Queries

```php
<?php

namespace App\Queries\Users;

use Hirethunk\Verbs\Query;

class GetUserQuery extends Query
{
    public function __construct(
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Users;

use App\Queries\Users\GetUserQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\User;

class GetUserQueryHandler extends QueryHandler
{
    public function handle(GetUserQuery $query)
    {
        return User::findOrFail($query->userId);
    }
}
```php
```php
<?php

namespace App\Queries\Users;

use Hirethunk\Verbs\Query;

class ListUsersQuery extends Query
{
    public function __construct(
        public ?string $search = null,
        public ?string $state = null,
        public ?string $sortBy = 'name',
        public ?string $sortDirection = 'asc',
        public int $page = 1,
        public int $perPage = 15
    ) {}

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'in:pending_activation,active,suspended,deactivated,archived'],
            'sortBy' => ['nullable', 'string', 'in:name,email,created_at'],
            'sortDirection' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['integer', 'min:1'],
            'perPage' => ['integer', 'min:1', 'max:100'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Users;

use App\Queries\Users\ListUsersQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ListUsersQueryHandler extends QueryHandler
{
    public function handle(ListUsersQuery $query)
    {
        $users = User::query();

        // Apply search filter
        if ($query->search) {
            $users->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query->search}%")
                  ->orWhere('email', 'like', "%{$query->search}%")
                  ->orWhere('search_vector', 'like', "%{$query->search}%");
            });
        }

        // Apply state filter
        if ($query->state) {
            $users->where('state', $query->state);
        }

        // Apply sorting
        $users->orderBy($query->sortBy, $query->sortDirection);

        // Apply pagination
        return $users->paginate($query->perPage, ['*'], 'page', $query->page);
    }
}
```php
### Team Queries

```php
<?php

namespace App\Queries\Teams;

use Hirethunk\Verbs\Query;

class GetTeamQuery extends Query
{
    public function __construct(
        public string $teamId
    ) {}

    public function rules(): array
    {
        return [
            'teamId' => ['required', 'string', 'exists:teams,id'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Teams;

use App\Queries\Teams\GetTeamQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Team;

class GetTeamQueryHandler extends QueryHandler
{
    public function handle(GetTeamQuery $query)
    {
        return Team::with('members.user')->findOrFail($query->teamId);
    }
}
```php
```php
<?php

namespace App\Queries\Teams;

use Hirethunk\Verbs\Query;

class ListTeamsQuery extends Query
{
    public function __construct(
        public ?string $search = null,
        public ?string $state = null,
        public ?string $userId = null,
        public ?string $sortBy = 'name',
        public ?string $sortDirection = 'asc',
        public int $page = 1,
        public int $perPage = 15
    ) {}

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'in:forming,active,archived'],
            'userId' => ['nullable', 'string', 'exists:users,id'],
            'sortBy' => ['nullable', 'string', 'in:name,created_at'],
            'sortDirection' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['integer', 'min:1'],
            'perPage' => ['integer', 'min:1', 'max:100'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Teams;

use App\Queries\Teams\ListTeamsQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Team;
use App\Models\TeamMember;

class ListTeamsQueryHandler extends QueryHandler
{
    public function handle(ListTeamsQuery $query)
    {
        $teams = Team::query();

        // Apply search filter
        if ($query->search) {
            $teams->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query->search}%")
                  ->orWhere('description', 'like', "%{$query->search}%");
            });
        }

        // Apply state filter
        if ($query->state) {
            $teams->where('state', $query->state);
        }

        // Filter by user membership
        if ($query->userId) {
            $teamIds = TeamMember::where('user_id', $query->userId)
                ->pluck('team_id');

            $teams->whereIn('id', $teamIds);
        }

        // Apply sorting
        $teams->orderBy($query->sortBy, $query->sortDirection);

        // Apply pagination
        return $teams->paginate($query->perPage, ['*'], 'page', $query->page);
    }
}
```php
### Post Queries

```php
<?php

namespace App\Queries\Posts;

use Hirethunk\Verbs\Query;

class GetPostQuery extends Query
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

```php
<?php

namespace App\QueryHandlers\Posts;

use App\Queries\Posts\GetPostQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Post;

class GetPostQueryHandler extends QueryHandler
{
    public function handle(GetPostQuery $query)
    {
        return Post::with(['author', 'tags'])->findOrFail($query->postId);
    }
}
```php
```php
<?php

namespace App\Queries\Posts;

use Hirethunk\Verbs\Query;

class ListPostsQuery extends Query
{
    public function __construct(
        public ?string $search = null,
        public ?string $state = null,
        public ?string $authorId = null,
        public ?string $teamId = null,
        public ?array $tags = null,
        public ?string $sortBy = 'created_at',
        public ?string $sortDirection = 'desc',
        public int $page = 1,
        public int $perPage = 15
    ) {}

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'in:draft,pending_review,published,scheduled,archived'],
            'authorId' => ['nullable', 'string', 'exists:users,id'],
            'teamId' => ['nullable', 'string', 'exists:teams,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
            'sortBy' => ['nullable', 'string', 'in:title,created_at,published_at'],
            'sortDirection' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['integer', 'min:1'],
            'perPage' => ['integer', 'min:1', 'max:100'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Posts;

use App\Queries\Posts\ListPostsQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Post;

class ListPostsQueryHandler extends QueryHandler
{
    public function handle(ListPostsQuery $query)
    {
        $posts = Post::with(['author', 'tags']);

        // Apply search filter
        if ($query->search) {
            $posts->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query->search}%")
                  ->orWhere('content', 'like', "%{$query->search}%")
                  ->orWhere('excerpt', 'like', "%{$query->search}%");
            });
        }

        // Apply state filter
        if ($query->state) {
            $posts->where('state', $query->state);
        }

        // Filter by author
        if ($query->authorId) {
            $posts->where('author_id', $query->authorId);
        }

        // Filter by team
        if ($query->teamId) {
            $posts->where('team_id', $query->teamId);
        }

        // Filter by tags
        if ($query->tags) {
            $posts->withAllTags($query->tags);
        }

        // Apply sorting
        $posts->orderBy($query->sortBy, $query->sortDirection);

        // Apply pagination
        return $posts->paginate($query->perPage, ['*'], 'page', $query->page);
    }
}
```php
### Todo Queries

```php
<?php

namespace App\Queries\Todos;

use Hirethunk\Verbs\Query;

class GetTodoQuery extends Query
{
    public function __construct(
        public string $todoId
    ) {}

    public function rules(): array
    {
        return [
            'todoId' => ['required', 'string', 'exists:todos,id'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Todos;

use App\Queries\Todos\GetTodoQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Todo;

class GetTodoQueryHandler extends QueryHandler
{
    public function handle(GetTodoQuery $query)
    {
        return Todo::with(['user', 'team', 'tags'])->findOrFail($query->todoId);
    }
}
```php
```php
<?php

namespace App\Queries\Todos;

use Hirethunk\Verbs\Query;

class ListTodosQuery extends Query
{
    public function __construct(
        public ?string $search = null,
        public ?string $state = null,
        public ?string $userId = null,
        public ?string $teamId = null,
        public ?array $tags = null,
        public ?string $sortBy = 'due_date',
        public ?string $sortDirection = 'asc',
        public int $page = 1,
        public int $perPage = 15
    ) {}

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'in:pending,in_progress,completed,cancelled'],
            'userId' => ['nullable', 'string', 'exists:users,id'],
            'teamId' => ['nullable', 'string', 'exists:teams,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
            'sortBy' => ['nullable', 'string', 'in:title,due_date,priority,created_at'],
            'sortDirection' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['integer', 'min:1'],
            'perPage' => ['integer', 'min:1', 'max:100'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Todos;

use App\Queries\Todos\ListTodosQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Todo;

class ListTodosQueryHandler extends QueryHandler
{
    public function handle(ListTodosQuery $query)
    {
        $todos = Todo::with(['user', 'team', 'tags']);

        // Apply search filter
        if ($query->search) {
            $todos->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query->search}%")
                  ->orWhere('description', 'like', "%{$query->search}%");
            });
        }

        // Apply state filter
        if ($query->state) {
            $todos->where('state', $query->state);
        }

        // Filter by user
        if ($query->userId) {
            $todos->where('user_id', $query->userId);
        }

        // Filter by team
        if ($query->teamId) {
            $todos->where('team_id', $query->teamId);
        }

        // Filter by tags
        if ($query->tags) {
            $todos->withAllTags($query->tags);
        }

        // Apply sorting
        $todos->orderBy($query->sortBy, $query->sortDirection);

        // Apply pagination
        return $todos->paginate($query->perPage, ['*'], 'page', $query->page);
    }
}
```php
### Comment Queries

```php
<?php

namespace App\Queries\Comments;

use Hirethunk\Verbs\Query;

class GetCommentQuery extends Query
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

```php
<?php

namespace App\QueryHandlers\Comments;

use App\Queries\Comments\GetCommentQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Comment;

class GetCommentQueryHandler extends QueryHandler
{
    public function handle(GetCommentQuery $query)
    {
        return Comment::with(['user', 'reactions'])->findOrFail($query->commentId);
    }
}
```php
```php
<?php

namespace App\Queries\Comments;

use Hirethunk\Verbs\Query;

class ListCommentsQuery extends Query
{
    public function __construct(
        public string $commentableType,
        public string $commentableId,
        public ?string $state = null,
        public ?string $userId = null,
        public ?string $sortBy = 'created_at',
        public ?string $sortDirection = 'asc',
        public int $page = 1,
        public int $perPage = 15
    ) {}

    public function rules(): array
    {
        return [
            'commentableType' => ['required', 'string'],
            'commentableId' => ['required', 'string'],
            'state' => ['nullable', 'string', 'in:pending,approved,rejected,deleted'],
            'userId' => ['nullable', 'string', 'exists:users,id'],
            'sortBy' => ['nullable', 'string', 'in:created_at'],
            'sortDirection' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['integer', 'min:1'],
            'perPage' => ['integer', 'min:1', 'max:100'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Comments;

use App\Queries\Comments\ListCommentsQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Comment;

class ListCommentsQueryHandler extends QueryHandler
{
    public function handle(ListCommentsQuery $query)
    {
        $comments = Comment::with(['user', 'reactions'])
            ->where('commentable_type', $query->commentableType)
            ->where('commentable_id', $query->commentableId)
            ->whereNull('parent_id'); // Get only top-level comments

        // Apply state filter
        if ($query->state) {
            $comments->where('state', $query->state);
        } else {
            // By default, only show approved comments
            $comments->where('state', 'approved');
        }

        // Filter by user
        if ($query->userId) {
            $comments->where('user_id', $query->userId);
        }

        // Apply sorting
        $comments->orderBy($query->sortBy, $query->sortDirection);

        // Apply pagination
        return $comments->paginate($query->perPage, ['*'], 'page', $query->page);
    }
}
```php
```php
<?php

namespace App\Queries\Comments;

use Hirethunk\Verbs\Query;

class GetCommentRepliesQuery extends Query
{
    public function __construct(
        public string $commentId,
        public ?string $state = null,
        public ?string $sortBy = 'created_at',
        public ?string $sortDirection = 'asc',
        public int $page = 1,
        public int $perPage = 15
    ) {}

    public function rules(): array
    {
        return [
            'commentId' => ['required', 'string', 'exists:comments,id'],
            'state' => ['nullable', 'string', 'in:pending,approved,rejected,deleted'],
            'sortBy' => ['nullable', 'string', 'in:created_at'],
            'sortDirection' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['integer', 'min:1'],
            'perPage' => ['integer', 'min:1', 'max:100'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Comments;

use App\Queries\Comments\GetCommentRepliesQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Comment;

class GetCommentRepliesQueryHandler extends QueryHandler
{
    public function handle(GetCommentRepliesQuery $query)
    {
        $replies = Comment::with(['user', 'reactions'])
            ->where('parent_id', $query->commentId);

        // Apply state filter
        if ($query->state) {
            $replies->where('state', $query->state);
        } else {
            // By default, only show approved comments
            $replies->where('state', 'approved');
        }

        // Apply sorting
        $replies->orderBy($query->sortBy, $query->sortDirection);

        // Apply pagination
        return $replies->paginate($query->perPage, ['*'], 'page', $query->page);
    }
}
```php
### Message Queries

```php
<?php

namespace App\Queries\Messages;

use Hirethunk\Verbs\Query;

class GetConversationQuery extends Query
{
    public function __construct(
        public string $conversationId
    ) {}

    public function rules(): array
    {
        return [
            'conversationId' => ['required', 'string', 'exists:conversations,id'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Messages;

use App\Queries\Messages\GetConversationQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Conversation;

class GetConversationQueryHandler extends QueryHandler
{
    public function handle(GetConversationQuery $query)
    {
        return Conversation::with(['participants.user'])->findOrFail($query->conversationId);
    }
}
```php
```php
<?php

namespace App\Queries\Messages;

use Hirethunk\Verbs\Query;

class ListConversationsQuery extends Query
{
    public function __construct(
        public string $userId,
        public ?string $search = null,
        public ?string $type = null,
        public ?string $sortBy = 'updated_at',
        public ?string $sortDirection = 'desc',
        public int $page = 1,
        public int $perPage = 15
    ) {}

    public function rules(): array
    {
        return [
            'userId' => ['required', 'string', 'exists:users,id'],
            'search' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:direct,group,team'],
            'sortBy' => ['nullable', 'string', 'in:updated_at,created_at'],
            'sortDirection' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['integer', 'min:1'],
            'perPage' => ['integer', 'min:1', 'max:100'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Messages;

use App\Queries\Messages\ListConversationsQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Conversation;
use App\Models\ConversationParticipant;

class ListConversationsQueryHandler extends QueryHandler
{
    public function handle(ListConversationsQuery $query)
    {
        // Get conversation IDs where the user is a participant
        $conversationIds = ConversationParticipant::where('user_id', $query->userId)
            ->whereNull('removed_at')
            ->pluck('conversation_id');

        $conversations = Conversation::with(['participants.user'])
            ->whereIn('id', $conversationIds);

        // Apply search filter
        if ($query->search) {
            $conversations->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query->search}%");
            });
        }

        // Apply type filter
        if ($query->type) {
            $conversations->where('type', $query->type);
        }

        // Apply sorting
        $conversations->orderBy($query->sortBy, $query->sortDirection);

        // Apply pagination
        return $conversations->paginate($query->perPage, ['*'], 'page', $query->page);
    }
}
```php
```php
<?php

namespace App\Queries\Messages;

use Hirethunk\Verbs\Query;

class GetMessagesQuery extends Query
{
    public function __construct(
        public string $conversationId,
        public ?string $sortBy = 'created_at',
        public ?string $sortDirection = 'asc',
        public int $page = 1,
        public int $perPage = 50
    ) {}

    public function rules(): array
    {
        return [
            'conversationId' => ['required', 'string', 'exists:conversations,id'],
            'sortBy' => ['nullable', 'string', 'in:created_at'],
            'sortDirection' => ['nullable', 'string', 'in:asc,desc'],
            'page' => ['integer', 'min:1'],
            'perPage' => ['integer', 'min:1', 'max:100'],
        ];
    }
}
```text

```php
<?php

namespace App\QueryHandlers\Messages;

use App\Queries\Messages\GetMessagesQuery;
use Hirethunk\Verbs\QueryHandler;
use App\Models\Message;

class GetMessagesQueryHandler extends QueryHandler
{
    public function handle(GetMessagesQuery $query)
    {
        $messages = Message::with(['sender', 'readReceipts'])
            ->where('conversation_id', $query->conversationId)
            ->whereNull('deleted_at');

        // Apply sorting
        $messages->orderBy($query->sortBy, $query->sortDirection);

        // Apply pagination
        return $messages->paginate($query->perPage, ['*'], 'page', $query->page);
    }
}
```php
## Common Patterns and Best Practices

### Single Responsibility

Each query should focus on a specific use case:

- **GetUserQuery**: Retrieve a single user by ID
- **ListUsersQuery**: Retrieve a list of users with filtering, sorting, and pagination
- **SearchUsersQuery**: Search for users based on search criteria

### Performance Optimization

Optimize queries for performance:

1. **Eager Loading**: Use eager loading to avoid N+1 queries
2. **Indexing**: Ensure read models have appropriate indexes
3. **Caching**: Use caching for frequently accessed data
4. **Pagination**: Always paginate large result sets

```php
// Example: Eager loading relationships
public function handle(GetPostQuery $query)
{
    return Post::with(['author', 'tags', 'comments.user'])->findOrFail($query->postId);
}
```text

### Error Handling

Implement robust error handling in query handlers:

```php
public function handle(GetUserQuery $query)
{
    try {
        return User::findOrFail($query->userId);
    } catch (ModelNotFoundException $e) {
        throw new UserNotFoundException("User not found: {$query->userId}");
    } catch (\Exception $e) {
        // Log the error
        Log::error('Error in GetUserQueryHandler', [
            'query' => $query,
            'error' => $e->getMessage(),
        ]);

        throw $e;
    }
}
```php
### Pagination

Always paginate large result sets:

```php
public function handle(ListUsersQuery $query)
{
    $users = User::query();

    // Apply filters and sorting

    // Apply pagination
    return $users->paginate($query->perPage, ['*'], 'page', $query->page);
}
```text

## Benefits and Challenges

### Benefits

1. **Optimized Read Models**: Read models can be optimized for specific queries
2. **Separation of Concerns**: Clear separation between read and write operations
3. **Performance**: Queries can be optimized for specific use cases
4. **Maintainability**: Queries are easy to understand and maintain

### Challenges

1. **Eventual Consistency**: Read models may lag behind the event store
2. **Complexity**: Managing multiple read models adds complexity
3. **Duplication**: Some code duplication between query handlers
4. **Maintenance**: Keeping read models up to date with changing requirements

### Mitigation Strategies

1. **Caching**: Use caching to improve performance
2. **Standardization**: Standardize query and query handler implementations
3. **Testing**: Thoroughly test queries and query handlers
4. **Documentation**: Document queries and their use cases

## Troubleshooting

### Common Issues

<details>
<summary>Slow query performance</summary>

**Symptoms:**
- Queries take a long time to execute
- Database server CPU or memory usage is high

**Possible Causes:**
- Missing indexes on frequently queried columns
- Inefficient query implementation
- Large result sets without pagination

**Solutions:**
1. Add appropriate indexes to frequently queried columns
2. Optimize query implementation
3. Implement pagination for large result sets
4. Use eager loading to avoid N+1 queries
</details>

<details>
<summary>Inconsistent query results</summary>

**Symptoms:**
- Query results don't match expected values
- Query results are inconsistent with write operations

**Possible Causes:**
- Eventual consistency between write and read models
- Bugs in projectors that build read models
- Caching issues

**Solutions:**
1. Understand and account for eventual consistency
2. Fix bugs in projectors
3. Implement proper cache invalidation
4. Consider using snapshots for aggregates with many events
</details>

<details>
<summary>N+1 query problems</summary>

**Symptoms:**
- Large number of database queries for a single request
- Slow query performance

**Possible Causes:**
- Missing eager loading for relationships
- Inefficient query implementation

**Solutions:**
1. Use eager loading for relationships
2. Optimize query implementation
3. Use database profiling tools to identify N+1 queries
</details>

### Solutions

For detailed solutions to common issues, refer to the [Event Sourcing Troubleshooting Guide](070-testing.md#troubleshooting).

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing Reactors](040-reactors.md) - Detailed documentation on reactor implementation
- [Event Sourcing Testing](070-testing.md) - Detailed documentation on testing event-sourced applications

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added query flow diagram, query architecture diagram, read model optimization diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
