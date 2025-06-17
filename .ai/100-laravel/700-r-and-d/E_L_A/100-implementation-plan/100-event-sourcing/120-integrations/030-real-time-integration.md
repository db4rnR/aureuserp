# Phase 1: Event Sourcing Real-Time Integration

**Version:** 1.0.0
**Date:** 2025-05-19
**Author:** AI Assistant
**Status:** New
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Real-Time Architecture](#real-time-architecture)
  - [WebSockets with Laravel Echo](#websockets-with-laravel-echo)
  - [Event Broadcasting](#event-broadcasting)
- [Integration with Event Sourcing](#integration-with-event-sourcing)
  - [Broadcasting Domain Events](#broadcasting-domain-events)
  - [Real-Time Projectors](#real-time-projectors)
- [Implementation](#implementation)
  - [Server-Side Setup](#server-side-setup)
  - [Client-Side Setup](#client-side-setup)
  - [Broadcasting Events](#broadcasting-events)
  - [Subscribing to Events](#subscribing-to-events)
- [Example: Real-Time Todo Updates](#example-real-time-todo-updates)
  - [Server-Side Implementation](#server-side-implementation)
  - [Client-Side Implementation](#client-side-implementation)
- [Security Considerations](#security-considerations)
  - [Authentication](#authentication)
  - [Authorization](#authorization)
  - [Data Sanitization](#data-sanitization)
- [Performance Considerations](#performance-considerations)
- [Testing Real-Time Features](#testing-real-time-features)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document describes the integration of real-time features with event sourcing in the Enhanced Laravel Application (ELA). This integration enables the application to push updates to connected clients in real-time when domain events occur, providing a responsive and interactive user experience.

## Real-Time Architecture

### WebSockets with Laravel Echo

We'll use Laravel Echo and Laravel WebSockets to implement real-time features:

- **Laravel Echo**: A JavaScript library that makes it easy to subscribe to channels and listen for events broadcast by the server.
- **Laravel WebSockets**: A WebSocket server implementation for Laravel that is compatible with Pusher.

### Event Broadcasting

Laravel's event broadcasting system allows us to broadcast events over WebSockets:

1. Events are tagged with the `ShouldBroadcast` interface
2. Laravel automatically broadcasts these events over WebSockets
3. Clients subscribed to the appropriate channels receive the events

## Integration with Event Sourcing

### Broadcasting Domain Events

We'll integrate event sourcing with real-time features by:

1. Creating a reactor that listens for domain events
2. Broadcasting these events to appropriate channels
3. Ensuring only relevant data is broadcast

### Real-Time Projectors

We'll implement real-time projectors that:

1. Update the read model when domain events occur
2. Broadcast the updated read model to clients
3. Ensure consistency between the read model and the real-time updates

## Implementation

### Server-Side Setup

1. Install required packages:

```bash
composer require laravel/echo-server pusher/pusher-php-server
npm install laravel-echo pusher-js
```php
2. Configure broadcasting in `config/broadcasting.php`:

```php
'default' => env('BROADCAST_DRIVER', 'pusher'),

'connections' => [
    'pusher' => [
        'driver' => 'pusher',
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
        'options' => [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true,
            'host' => env('PUSHER_HOST', '127.0.0.1'),
            'port' => env('PUSHER_PORT', 6001),
            'scheme' => env('PUSHER_SCHEME', 'http'),
        ],
    ],
],
```php
3. Set up Laravel WebSockets:

```bash
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
```php
### Client-Side Setup

1. Configure Laravel Echo in `resources/js/bootstrap.js`:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});
```php
### Broadcasting Events

Create a broadcast event for domain events:

```php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DomainEventBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventData;
    public $eventType;
    private $channelName;

    public function __construct(string $eventType, array $eventData, string $channelName)
    {
        $this->eventType = $eventType;
        $this->eventData = $eventData;
        $this->channelName = $channelName;
    }

    public function broadcastOn()
    {
        return new Channel($this->channelName);
    }

    public function broadcastAs()
    {
        return $this->eventType;
    }
}
```javascript
### Subscribing to Events

Subscribe to events on the client side:

```javascript
// Subscribe to a specific todo's events
Echo.channel(`todo.${todoId}`)
    .listen('TodoUpdated', (e) => {
        console.log('Todo updated:', e);
        // Update the UI with the new data
        updateTodoUI(e.todo);
    })
    .listen('TodoCompleted', (e) => {
        console.log('Todo completed:', e);
        // Update the UI to show the todo as completed
        markTodoAsCompleted(e.todoId);
    });

// Subscribe to all todos for a team
Echo.channel(`team.${teamId}.todos`)
    .listen('TodoCreated', (e) => {
        console.log('New todo created:', e);
        // Add the new todo to the UI
        addTodoToUI(e.todo);
    });
```php
## Example: Real-Time Todo Updates

### Server-Side Implementation

1. Create a reactor for broadcasting todo events:

```php
namespace App\EventSourcing\Reactors;

use App\Events\DomainEventBroadcast;
use App\EventSourcing\Events\TodoCreated;
use App\EventSourcing\Events\TodoUpdated;
use App\EventSourcing\Events\TodoCompleted;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class TodoBroadcastReactor extends Reactor
{
    public function onTodoCreated(TodoCreated $event)
    {
        // Broadcast to the team channel
        broadcast(new DomainEventBroadcast(
            'TodoCreated',
            [
                'todoId' => $event->todoUuid,
                'todo' => [
                    'id' => $event->todoUuid,
                    'title' => $event->title,
                    'description' => $event->description,
                    'status' => 'draft',
                ],
            ],
            "team.{$event->teamUuid}.todos"
        ));
    }

    public function onTodoUpdated(TodoUpdated $event)
    {
        // Broadcast to the specific todo channel
        broadcast(new DomainEventBroadcast(
            'TodoUpdated',
            [
                'todoId' => $event->todoUuid,
                'todo' => [
                    'id' => $event->todoUuid,
                    'title' => $event->title,
                    'description' => $event->description,
                ],
            ],
            "todo.{$event->todoUuid}"
        ));
    }

    public function onTodoCompleted(TodoCompleted $event)
    {
        // Broadcast to both the specific todo channel and the team channel
        broadcast(new DomainEventBroadcast(
            'TodoCompleted',
            [
                'todoId' => $event->todoUuid,
            ],
            "todo.{$event->todoUuid}"
        ));

        broadcast(new DomainEventBroadcast(
            'TodoCompleted',
            [
                'todoId' => $event->todoUuid,
            ],
            "team.{$event->teamUuid}.todos"
        ));
    }
}
```php
2. Register the reactor in the event sourcing configuration:

```php
// config/event-sourcing.php
'reactors' => [
    \App\EventSourcing\Reactors\TodoBroadcastReactor::class,
],
```php
### Client-Side Implementation

1. Set up a Vue component for real-time todo updates:

```javascript
<template>
    <div>
        <h2>{{ todo.title }}</h2>
        <p>{{ todo.description }}</p>
        <span :class="{ 'completed': todo.status === 'completed' }">
            Status: {{ todo.status }}
        </span>
        <button @click="completeTodo" :disabled="todo.status === 'completed'">
            Mark as Completed
        </button>
    </div>
</template>

<script>
export default {
    props: ['initialTodo'],
    
    data() {
        return {
            todo: this.initialTodo,
        };
    },
    
    mounted() {
        // Subscribe to updates for this specific todo
        Echo.channel(`todo.${this.todo.id}`)
            .listen('TodoUpdated', (e) => {
                this.todo = { ...this.todo, ...e.todo };
            })
            .listen('TodoCompleted', () => {
                this.todo.status = 'completed';
            });
    },
    
    beforeUnmount() {
        // Clean up the subscription
        Echo.leave(`todo.${this.todo.id}`);
    },
    
    methods: {
        completeTodo() {
            axios.post(`/api/todos/${this.todo.id}/complete`)
                .then(response => {
                    // The UI will be updated via the WebSocket event
                })
                .catch(error => {
                    console.error('Error completing todo:', error);
                });
        }
    }
};
</script>
```php
## Security Considerations

### Authentication

Ensure that only authenticated users can subscribe to channels:

```php
// routes/channels.php
Broadcast::channel('todo.{todoId}', function ($user, $todoId) {
    return $user->can('view', Todo::findOrFail($todoId));
});

Broadcast::channel('team.{teamId}.todos', function ($user, $teamId) {
    return $user->teams->contains($teamId);
});
```php
### Authorization

Implement proper authorization checks before broadcasting events:

```php
public function onTodoCreated(TodoCreated $event)
{
    // Get the todo from the read model
    $todo = Todo::findOrFail($event->todoUuid);
    
    // Get all users who have access to this todo
    $authorizedUsers = User::whereHas('teams', function ($query) use ($todo) {
        $query->where('id', $todo->team_id);
    })->get();
    
    // Broadcast to each authorized user
    foreach ($authorizedUsers as $user) {
        broadcast(new DomainEventBroadcast(
            'TodoCreated',
            [
                'todoId' => $event->todoUuid,
                'todo' => $todo->toArray(),
            ],
            "private-user.{$user->id}.todos"
        ));
    }
}
```php
### Data Sanitization

Ensure that sensitive data is not broadcast:

```php
public function onTodoCreated(TodoCreated $event)
{
    // Get the todo from the read model
    $todo = Todo::findOrFail($event->todoUuid);
    
    // Sanitize the data
    $sanitizedTodo = [
        'id' => $todo->id,
        'title' => $todo->title,
        'description' => $todo->description,
        'status' => $todo->status,
        // Do not include sensitive fields like internal notes
    ];
    
    broadcast(new DomainEventBroadcast(
        'TodoCreated',
        [
            'todoId' => $event->todoUuid,
            'todo' => $sanitizedTodo,
        ],
        "team.{$event->teamUuid}.todos"
    ));
}
```php
## Performance Considerations

- Use a dedicated queue for broadcasting events to avoid blocking other jobs
- Consider batching events to reduce the number of WebSocket messages
- Implement rate limiting to prevent abuse
- Monitor WebSocket connections and server resources

## Testing Real-Time Features

```php
public function test_todo_completed_event_is_broadcast()
{
    Event::fake([DomainEventBroadcast::class]);
    
    // Create a todo
    $todoUuid = Str::uuid()->toString();
    $teamUuid = Str::uuid()->toString();
    
    // Complete the todo
    TodoAggregate::retrieve($todoUuid)
        ->createTodo('Test Todo', 'Test Description', $teamUuid)
        ->activate()
        ->markAsCompleted()
        ->persist();
    
    // Assert that the event was broadcast
    Event::assertDispatched(DomainEventBroadcast::class, function ($event) use ($todoUuid) {
        return $event->eventType === 'TodoCompleted' &&
               $event->eventData['todoId'] === $todoUuid;
    });
}
```

## Related Documents

- [Event Sourcing Implementation](050-implementation.md)
- [Reactors](040-reactors.md)
- [Queue Integration](080-state-machines.md)

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-19 | Initial version | AI Assistant |
