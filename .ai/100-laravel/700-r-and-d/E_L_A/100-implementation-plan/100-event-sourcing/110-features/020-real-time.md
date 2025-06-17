# Phase 1: Event Sourcing Real-time

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
- [Real-time Concept](#real-time-concept)
  - [What is Real-time?](#what-is-real-time)
  - [Real-time Responsibilities](#real-time-responsibilities)
  - [Real-time Types](#real-time-types)
- [Integration with Laravel Reverb](#integration-with-laravel-reverb)
  - [Reverb Configuration](#reverb-configuration)
  - [Reverb Server](#reverb-server)
  - [Reverb Client](#reverb-client)
- [Implementing Real-time](#implementing-real-time)
  - [Base Real-time Structure](#base-real-time-structure)
  - [Broadcasting Events](#broadcasting-events)
  - [Subscribing to Events](#subscribing-to-events)
- [Integration with Event Sourcing](#integration-with-event-sourcing)
  - [Real-time in Reactors](#real-time-in-reactors)
  - [Real-time in Projectors](#real-time-in-projectors)
  - [Real-time in Commands](#real-time-in-commands)
- [Real-time Examples](#real-time-examples)
  - [Message Real-time](#message-real-time)
  - [Comment Real-time](#comment-real-time)
  - [Todo Real-time](#todo-real-time)
- [Common Patterns and Best Practices](#common-patterns-and-best-practices)
  - [Channel Naming](#channel-naming)
  - [Event Naming](#event-naming)
  - [Authorization](#authorization)
  - [Performance](#performance)
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

This document provides a comprehensive guide to implementing real-time functionality in event sourcing for the Enhanced Laravel Application (ELA). Real-time functionality allows users to receive updates as they happen, without having to refresh the page. This document covers the concept of real-time, its implementation using Laravel Reverb, and integration with event sourcing.

## Prerequisites

- **Required Prior Steps:**
  - [Event Sourcing Aggregates](020-000-aggregates.md)
  - [Event Sourcing Projectors](030-projectors.md)
  - [Event Sourcing Reactors](040-reactors.md)
  - [Package Installation](../030-core-components/010-package-installation.md) (specifically `laravel/reverb`)

- **Required Packages:**
  - `spatie/laravel-event-sourcing`: ^7.0
  - `laravel/reverb`: ^1.0

- **Required Knowledge:**
  - Understanding of event sourcing principles
  - Familiarity with WebSockets
  - Understanding of Laravel's broadcasting system
  - Understanding of JavaScript and frontend frameworks

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher
  - Node.js 18.x or higher

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Understanding real-time concepts | 2 hours |
| Setting up Laravel Reverb | 2 hours |
| Implementing real-time broadcasting | 3 hours |
| Implementing real-time subscriptions | 3 hours |
| Testing real-time functionality | 2 hours |
| **Total** | **12 hours** |
</details>

## Real-time Concept

<details>
<summary>Real-time Architecture Diagram</summary>

This diagram illustrates the real-time architecture in the Enhanced Laravel Application (ELA), showing how event sourcing integrates with WebSockets to provide real-time updates to clients.

![Real-time Architecture](../../illustrations/thumbnails/mermaid/light/realtime-architecture-light-thumb.svg)

For the full diagram, see:
- [Real-time Architecture (Light Mode)](../../illustrations/mermaid/light/realtime-architecture-light.mmd)
- [Real-time Architecture (Dark Mode)](../../illustrations/mermaid/dark/realtime-architecture-dark.mmd)
</details>

### What is Real-time?

Real-time functionality allows users to receive updates as they happen, without having to refresh the page. In the context of event sourcing, real-time functionality is used to:

1. Broadcast events to interested clients
2. Update the UI in response to events
3. Provide a more interactive and engaging user experience
4. Enable collaborative features

Real-time functionality is implemented using WebSockets, which provide a persistent connection between the client and the server, allowing for bidirectional communication.

### Real-time Responsibilities

Real-time functionality has several key responsibilities:

1. **Event Broadcasting**: Broadcasting events to interested clients
2. **Client Notification**: Notifying clients of changes
3. **UI Updates**: Updating the UI in response to events
4. **Presence**: Tracking which users are online and active

### Real-time Types

There are several types of real-time functionality that can be implemented:

1. **Public Channels**: Channels that anyone can subscribe to
2. **Private Channels**: Channels that require authentication to subscribe to
3. **Presence Channels**: Channels that track which users are subscribed
4. **Direct Messages**: Messages sent directly to specific users

In the ELA, we use a combination of these types to provide a flexible and powerful real-time system.

## Integration with Laravel Reverb

### Reverb Configuration

Laravel Reverb is configured in the `config/broadcasting.php` file:

```php
<?php

return [
    'default' => env('BROADCAST_DRIVER', 'reverb'),

    'connections' => [
        'reverb' => [
            'driver' => 'reverb',
            'app_id' => env('REVERB_APP_ID', 'ela'),
            'key' => env('REVERB_APP_KEY'),
            'secret' => env('REVERB_APP_SECRET'),
            'app_host' => env('REVERB_HOST', 'localhost'),
            'app_port' => env('REVERB_PORT', 8080),
            'options' => [
                'cluster' => env('REVERB_CLUSTER', 'mt1'),
                'encrypted' => true,
                'host' => env('REVERB_HOST', 'localhost'),
                'port' => env('REVERB_PORT', 8080),
                'scheme' => env('REVERB_SCHEME', 'http'),
                'curl_options' => [
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ],
            ],
        ],
    ],
];
```text

### Reverb Server

The Reverb server is started using the `reverb:start` Artisan command:

```bash
php artisan reverb:start
```php
For production, the Reverb server should be run as a daemon using a process manager like Supervisor:

```ini
[program:reverb]
command=php /path/to/artisan reverb:start
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/reverb.log
```text

### Reverb Client

The Reverb client is configured in the frontend:

```javascript
// resources/js/bootstrap.js
import Echo from 'laravel-echo';
import Reverb from 'laravel-reverb';

window.Reverb = Reverb;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME || 'http') === 'https',
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});
```text
## Implementing Real-time

<details>
<summary>WebSocket Communication Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
sequenceDiagram
    participant Client
    participant WebSocket
    participant Server
    participant EventStore

    Client->>WebSocket: Connect
    WebSocket->>Server: Authenticate
    Server->>WebSocket: Authentication Success
    WebSocket->>Client: Connection Established

    Client->>WebSocket: Subscribe to Channel
    WebSocket->>Server: Channel Authorization
    Server->>WebSocket: Channel Authorized
    WebSocket->>Client: Subscription Confirmed

    Server->>EventStore: Store Event
    EventStore->>Server: Event Stored
    Server->>WebSocket: Broadcast Event
    WebSocket->>Client: Event Received
    Client->>Client: Update UI
```text

For dark mode, see [WebSocket Communication Flow (Dark Mode)](../../illustrations/mermaid/dark/websocket-communication-dark.mmd)
</details>

### Base Real-time Structure

In the ELA, real-time functionality is implemented using a base structure:

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $conversationId,
        public string $messageId,
        public string $senderId,
        public string $senderName,
        public string $content,
        public string $sentAt
    ) {}

    public function broadcastOn()
    {
        return new PresenceChannel('conversation.' . $this->conversationId);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->messageId,
            'sender_id' => $this->senderId,
            'sender_name' => $this->senderName,
            'content' => $this->content,
            'sent_at' => $this->sentAt,
        ];
    }
}
```php
### Broadcasting Events

Events are broadcast using the `broadcast` function:

```php
broadcast(new MessageSentEvent(
    $conversation->id,
    $message->id,
    $sender->id,
    $sender->name,
    $message->content,
    $message->created_at->toIso8601String()
))->toPresence('conversation.' . $conversation->id);
```text

### Subscribing to Events

<details>
<summary>Presence Channels Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
sequenceDiagram
    participant User1 as User 1
    participant User2 as User 2
    participant User3 as User 3
    participant Channel as Presence Channel
    participant Server as Server

    User1->>Channel: Join
    Channel->>Server: Authorize User 1
    Server->>Channel: User 1 Authorized
    Channel->>User1: Joined
    Channel->>User1: Here: []

    User2->>Channel: Join
    Channel->>Server: Authorize User 2
    Server->>Channel: User 2 Authorized
    Channel->>User2: Joined
    Channel->>User2: Here: [User 1]
    Channel->>User1: User 2 Joining

    User3->>Channel: Join
    Channel->>Server: Authorize User 3
    Server->>Channel: User 3 Authorized
    Channel->>User3: Joined
    Channel->>User3: Here: [User 1, User 2]
    Channel->>User1: User 3 Joining
    Channel->>User2: User 3 Joining

    User2->>Channel: Leave
    Channel->>User1: User 2 Leaving
    Channel->>User3: User 2 Leaving
```javascript
For dark mode, see [Presence Channels (Dark Mode)](../../illustrations/mermaid/dark/presence-channels-dark.mmd)
</details>

Clients subscribe to events using the Echo client:

```javascript
// Subscribe to a presence channel
Echo.join(`conversation.${conversationId}`)
    .here((users) => {
        // Users already in the channel
        console.log(users);
    })
    .joining((user) => {
        // User joined the channel
        console.log(`${user.name} joined`);
    })
    .leaving((user) => {
        // User left the channel
        console.log(`${user.name} left`);
    })
    .listen('.message.sent', (e) => {
        // Message sent event
        console.log(e);
        // Update UI
        addMessage(e);
    });
```text

## Integration with Event Sourcing

<details>
<summary>Event Sourcing Integration Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[Command] --> B[Aggregate]
    B --> C[Event]
    C --> D[Event Store]
    D --> E[Projector]
    D --> F[Reactor]
    E --> G[Read Model]
    F --> H[Side Effect]
    F --> I[Broadcast Event]
    I --> J[WebSocket Server]
    J --> K[Client]
```php
For dark mode, see [Event Sourcing Integration (Dark Mode)](../../illustrations/mermaid/dark/event-sourcing-realtime-dark.mmd)
</details>

### Real-time in Reactors

Real-time functionality is integrated with event sourcing through reactors:

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use App\Events\Messages\MessageSent;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageReactor extends Reactor implements ShouldQueue
{
    public $queue = 'reactors';

    public function onMessageSent(MessageSent $event, string $aggregateUuid)
    {
        $conversation = Conversation::findOrFail($aggregateUuid);
        $sender = User::findOrFail($event->payload['sender_id']);

        // Broadcast the message to the conversation channel
        broadcast(new \App\Events\MessageSentEvent(
            $conversation->id,
            $event->payload['message_id'],
            $sender->id,
            $sender->name,
            $event->payload['content'],
            $event->payload['sent_at']
        ))->toPresence('conversation.' . $conversation->id);
    }
}
```text

### Real-time in Projectors

Real-time functionality can also be integrated with projectors:

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\Comments\CommentCreated;
use App\Models\Comment;
use App\Models\User;

class CommentProjector extends Projector
{
    public function onCommentCreated(CommentCreated $event, string $aggregateUuid)
    {
        $comment = Comment::create([
            'id' => $aggregateUuid,
            'content' => $event->payload['content'],
            'user_id' => $event->payload['user_id'],
            'commentable_type' => $event->payload['commentable_type'],
            'commentable_id' => $event->payload['commentable_id'],
            'parent_id' => $event->payload['parent_id'],
        ]);

        $user = User::findOrFail($event->payload['user_id']);

        // Broadcast the comment to the commentable channel
        broadcast(new \App\Events\CommentCreatedEvent(
            $comment->id,
            $comment->commentable_type,
            $comment->commentable_id,
            $user->id,
            $user->name,
            $comment->content,
            $comment->created_at->toIso8601String()
        ))->toPrivate('commentable.' . $comment->commentable_type . '.' . $comment->commentable_id);
    }
}
```php
### Real-time in Commands

Real-time functionality can be triggered directly from command handlers:

```php
<?php

namespace App\CommandHandlers\Todos;

use App\Commands\Todos\CompleteTodoCommand;
use App\Aggregates\TodoAggregateRoot;
use Hirethunk\Verbs\CommandHandler;

class CompleteTodoCommandHandler extends CommandHandler
{
    public function handle(CompleteTodoCommand $command)
    {
        // Retrieve the todo aggregate
        $aggregate = TodoAggregateRoot::retrieve($command->todoId);

        // Complete the todo
        $aggregate->completeTodo();

        // Persist the events
        $aggregate->persist();

        // Broadcast the todo completion
        broadcast(new \App\Events\TodoCompletedEvent(
            $command->todoId,
            auth()->id(),
            auth()->user()->name,
            now()->toIso8601String()
        ))->toPrivate('team.' . $command->teamId);

        return $this->success();
    }
}
```text

## Real-time Examples

### Message Real-time

Real-time messaging is implemented using presence channels:

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $conversationId,
        public string $messageId,
        public string $senderId,
        public string $senderName,
        public string $content,
        public string $sentAt
    ) {}

    public function broadcastOn()
    {
        return new PresenceChannel('conversation.' . $this->conversationId);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->messageId,
            'sender_id' => $this->senderId,
            'sender_name' => $this->senderName,
            'content' => $this->content,
            'sent_at' => $this->sentAt,
        ];
    }
}
```javascript
Frontend subscription:

```javascript
// Subscribe to a conversation
Echo.join(`conversation.${conversationId}`)
    .here((users) => {
        // Users already in the conversation
        setOnlineUsers(users);
    })
    .joining((user) => {
        // User joined the conversation
        addOnlineUser(user);
    })
    .leaving((user) => {
        // User left the conversation
        removeOnlineUser(user);
    })
    .listen('.message.sent', (e) => {
        // Message sent event
        addMessage(e);
    });
```text

### Comment Real-time

Real-time comments are implemented using private channels:

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $commentId,
        public string $commentableType,
        public string $commentableId,
        public string $userId,
        public string $userName,
        public string $content,
        public string $createdAt
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel('commentable.' . $this->commentableType . '.' . $this->commentableId);
    }

    public function broadcastAs()
    {
        return 'comment.created';
    }

    public function broadcastWith()
    {
        return [
            'comment_id' => $this->commentId,
            'user_id' => $this->userId,
            'user_name' => $this->userName,
            'content' => $this->content,
            'created_at' => $this->createdAt,
        ];
    }
}
```javascript
Frontend subscription:

```javascript
// Subscribe to comments on a post
Echo.private(`commentable.App\\Models\\Post.${postId}`)
    .listen('.comment.created', (e) => {
        // Comment created event
        addComment(e);
    });
```text

### Todo Real-time

Real-time todo updates are implemented using private channels:

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TodoCompletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $todoId,
        public string $userId,
        public string $userName,
        public string $completedAt
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel('team.' . $this->teamId);
    }

    public function broadcastAs()
    {
        return 'todo.completed';
    }

    public function broadcastWith()
    {
        return [
            'todo_id' => $this->todoId,
            'user_id' => $this->userId,
            'user_name' => $this->userName,
            'completed_at' => $this->completedAt,
        ];
    }
}
```javascript
Frontend subscription:

```javascript
// Subscribe to todo updates for a team
Echo.private(`team.${teamId}`)
    .listen('.todo.completed', (e) => {
        // Todo completed event
        markTodoAsCompleted(e.todo_id);
    });
```text

## Common Patterns and Best Practices

### Channel Naming

Use a consistent naming convention for channels:

```php
// Private channels
'private-resource.{id}'

// Presence channels
'presence-resource.{id}'

// Examples
'private-team.123'
'presence-conversation.456'
```php
### Event Naming

Use a consistent naming convention for events:

```php
// Event naming
'resource.action'

// Examples
'message.sent'
'comment.created'
'todo.completed'
```text

### Authorization

Implement channel authorization to control access:

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Broadcast::routes();

        Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
            $conversation = \App\Models\Conversation::findOrFail($conversationId);

            // Check if user is a participant in the conversation
            return $conversation->participants()->where('user_id', $user->id)->exists();
        });

        Broadcast::channel('commentable.App\\Models\\Post.{postId}', function ($user, $postId) {
            $post = \App\Models\Post::findOrFail($postId);

            // Check if user has permission to view the post
            return $user->can('view', $post);
        });

        Broadcast::channel('team.{teamId}', function ($user, $teamId) {
            $team = \App\Models\Team::findOrFail($teamId);

            // Check if user is a member of the team
            return $team->members()->where('user_id', $user->id)->exists();
        });
    }
}
```php
### Performance

Optimize real-time performance:

1. **Queue Broadcasts**: Use queued broadcasting to avoid blocking the request
2. **Limit Payload Size**: Keep broadcast payloads small
3. **Use Presence Channels Sparingly**: Presence channels have higher overhead
4. **Implement Pagination**: Paginate large datasets

```php
// Queue broadcasts
broadcast(new MessageSentEvent(...))->toPresence('conversation.' . $conversationId)->onQueue('broadcasts');
```text

## Benefits and Challenges

### Benefits

1. **Improved User Experience**: Real-time updates provide a more interactive and engaging user experience
2. **Reduced Server Load**: Real-time updates reduce the need for polling
3. **Collaborative Features**: Real-time updates enable collaborative features
4. **Immediate Feedback**: Users receive immediate feedback on their actions

### Challenges

1. **Complexity**: Real-time functionality adds complexity to the application
2. **Performance**: Real-time functionality can impact performance
3. **Scaling**: Real-time functionality can be challenging to scale
4. **Fallbacks**: Real-time functionality requires fallbacks for when WebSockets are not available

### Mitigation Strategies

1. **Queue Broadcasts**: Use queued broadcasting to avoid blocking the request
2. **Optimize Payloads**: Keep broadcast payloads small
3. **Implement Fallbacks**: Implement fallbacks for when WebSockets are not available
4. **Use Horizontal Scaling**: Scale WebSocket servers horizontally

## Troubleshooting

### Common Issues

<details>
<summary>WebSocket connection failing</summary>

**Symptoms:**
- WebSocket connection fails to establish
- Error messages in the browser console

**Possible Causes:**
- Reverb server not running
- Incorrect configuration
- Firewall blocking WebSocket connections
- CORS issues

**Solutions:**
1. Ensure the Reverb server is running
2. Verify the configuration in `config/broadcasting.php`
3. Check firewall settings
4. Configure CORS correctly
</details>

<details>
<summary>Events not being broadcast</summary>

**Symptoms:**
- Events are not being received by clients
- No errors in the logs

**Possible Causes:**
- Event not implementing `ShouldBroadcast`
- Incorrect channel name
- Authorization issues
- Queue not processing broadcast jobs

**Solutions:**
1. Ensure the event implements `ShouldBroadcast`
2. Verify the channel name
3. Check authorization rules
4. Ensure the queue worker is running
</details>

<details>
<summary>Presence channel issues</summary>

**Symptoms:**
- Users not appearing in the presence channel
- Users not being removed from the presence channel

**Possible Causes:**
- Incorrect channel authorization
- User model not serializing correctly
- WebSocket disconnections not being handled

**Solutions:**
1. Verify channel authorization rules
2. Ensure the user model serializes correctly
3. Implement proper handling of WebSocket disconnections
</details>

### Solutions

For detailed solutions to common issues, refer to the following resources:

- [Laravel Broadcasting Documentation](https:/laravel.com/docs/broadcasting)
- [Laravel Reverb Documentation](https:/laravel.com/docs/reverb)
- [WebSocket Troubleshooting](https:/developer.mozilla.org/en-US/docs/Web/API/WebSockets_API/Writing_WebSocket_client_applications)

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing Reactors](040-reactors.md) - Detailed documentation on reactor implementation
- [Event Sourcing Comments and Reactions](100-comments-reactions.md) - Detailed documentation on comments and reactions implementation

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added real-time architecture diagram, WebSocket communication flow diagram, event sourcing integration diagram, presence channels diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
