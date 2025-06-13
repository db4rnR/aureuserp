# Phase 1: Message Aggregate

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
- [Message Aggregate Structure](#message-aggregate-structure)
  - [State Properties](#state-properties)
  - [Message States](#message-states)
- [Message Commands](#message-commands)
  - [CreateConversation Command](#createconversation-command)
  - [SendMessage Command](#sendmessage-command)
  - [EditMessage Command](#editmessage-command)
  - [DeleteMessage Command](#deletemessage-command)
  - [AddParticipant Command](#addparticipant-command)
  - [RemoveParticipant Command](#removeparticipant-command)
  - [MarkMessageAsRead Command](#markmessageasread-command)
  - [StartTyping Command](#starttyping-command)
  - [StopTyping Command](#stoptyping-command)
- [Message Events](#message-events)
  - [ConversationCreated Event](#conversationcreated-event)
  - [MessageSent Event](#messagesent-event)
  - [MessageEdited Event](#messageedited-event)
  - [MessageDeleted Event](#messagedeleted-event)
  - [ParticipantAdded Event](#participantadded-event)
  - [ParticipantRemoved Event](#participantremoved-event)
  - [MessageRead Event](#messageread-event)
  - [TypingStarted Event](#typingstarted-event)
  - [TypingStopped Event](#typingstopped-event)
- [Message Aggregate Implementation](#message-aggregate-implementation)
  - [Command Methods](#command-methods)
  - [Apply Methods](#apply-methods)
  - [Business Rules](#business-rules)
- [Integration with Real-time Functionality](#integration-with-real-time-functionality)
  - [Real-time Updates](#real-time-updates)
  - [Typing Indicators](#typing-indicators)
  - [Read Receipts](#read-receipts)
- [Chat Room Management](#chat-room-management)
  - [Conversation Types](#conversation-types)
  - [Participant Management](#participant-management)
- [Command Handlers](#command-handlers)
  - [CreateConversationCommandHandler](#createconversationcommandhandler)
  - [SendMessageCommandHandler](#sendmessagecommandhandler)
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

This document provides detailed documentation on the Message aggregate in the event sourcing implementation for the Enhanced Laravel Application (ELA). The Message aggregate is responsible for managing conversations, messages, participants, and real-time messaging features. This document covers the commands, events, business rules, and real-time integration for the Message aggregate.

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
  - `laravel/reverb`: ^1.0

- **Required Knowledge:**
  - Understanding of event sourcing principles
  - Familiarity with real-time messaging systems
  - Understanding of WebSockets

- **Required Environment:**
  - Laravel 10.x or higher
  - PHP 8.2 or higher
  - Laravel Reverb for WebSocket communication

## Estimated Time Requirements

<details>
<summary>Time Requirements Table</summary>

| Task | Estimated Time |
|------|----------------|
| Setting up Message aggregate structure | 1 hour |
| Implementing Message commands | 2 hours |
| Implementing Message events | 1 hour |
| Implementing command methods | 2 hours |
| Implementing apply methods | 1 hour |
| Integrating with real-time functionality | 3 hours |
| Testing Message aggregate | 2 hours |
| **Total** | **12 hours** |
</details>

## Message Aggregate Structure

### State Properties

The Message aggregate maintains the following state properties:

```php
protected string $conversationType;
protected string $name;
protected array $participants = [];
protected array $messages = [];
protected array $typingUsers = [];
```text

### Message States

Unlike other aggregates, the Message aggregate does not have explicit states. Instead, it manages the state of individual messages and the conversation as a whole through its properties.

## Message Commands

<details>
<summary>Message Command Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
flowchart TD
    A[SendMessage Command] --> B[SendMessageCommandHandler]
    B --> C[MessageAggregateRoot]
    C --> D{Valid?}
    D -->|Yes| E[Record MessageSent Event]
    D -->|No| F[Throw Exception]
    E --> G[Apply MessageSent Event]
    G --> H[Update Message State]
    E --> I[Event Store]
    I --> J[MessageProjector]
    J --> K[Message Model]
    I --> L[MessageReactor]
    L --> M[Broadcast to WebSocket]
```php
For dark mode, see [Message Command Flow (Dark Mode)](../../illustrations/mermaid/dark/message-command-flow-dark.mmd)
</details>

### CreateConversation Command

Creates a new conversation.

```php
<?php

namespace App\Commands\Messages;

use Hirethunk\Verbs\Command;

class CreateConversationCommand extends Command
{
    public function __construct(
        public string $name,
        public string $type,
        public array $participantIds,
        public string $creatorId
    ) {}

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:direct,group,team'],
            'participantIds' => ['required', 'array', 'min:1'],
            'participantIds.*' => ['required', 'string', 'exists:users,id'],
            'creatorId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```text

### SendMessage Command

Sends a message in a conversation.

```php
<?php

namespace App\Commands\Messages;

use Hirethunk\Verbs\Command;

class SendMessageCommand extends Command
{
    public function __construct(
        public string $conversationId,
        public string $content,
        public string $senderId,
        public ?array $attachments = null
    ) {}

    public function rules(): array
    {
        return [
            'conversationId' => ['required', 'string', 'exists:conversations,id'],
            'content' => ['required', 'string'],
            'senderId' => ['required', 'string', 'exists:users,id'],
            'attachments' => ['nullable', 'array'],
        ];
    }
}
```php
### EditMessage Command

Edits a message.

```php
<?php

namespace App\Commands\Messages;

use Hirethunk\Verbs\Command;

class EditMessageCommand extends Command
{
    public function __construct(
        public string $conversationId,
        public string $messageId,
        public string $content,
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'conversationId' => ['required', 'string', 'exists:conversations,id'],
            'messageId' => ['required', 'string'],
            'content' => ['required', 'string'],
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```text

### DeleteMessage Command

Deletes a message.

```php
<?php

namespace App\Commands\Messages;

use Hirethunk\Verbs\Command;

class DeleteMessageCommand extends Command
{
    public function __construct(
        public string $conversationId,
        public string $messageId,
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'conversationId' => ['required', 'string', 'exists:conversations,id'],
            'messageId' => ['required', 'string'],
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```php
### AddParticipant Command

Adds a participant to a conversation.

```php
<?php

namespace App\Commands\Messages;

use Hirethunk\Verbs\Command;

class AddParticipantCommand extends Command
{
    public function __construct(
        public string $conversationId,
        public string $userId,
        public string $addedBy
    ) {}

    public function rules(): array
    {
        return [
            'conversationId' => ['required', 'string', 'exists:conversations,id'],
            'userId' => ['required', 'string', 'exists:users,id'],
            'addedBy' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```text

### RemoveParticipant Command

Removes a participant from a conversation.

```php
<?php

namespace App\Commands\Messages;

use Hirethunk\Verbs\Command;

class RemoveParticipantCommand extends Command
{
    public function __construct(
        public string $conversationId,
        public string $userId,
        public string $removedBy
    ) {}

    public function rules(): array
    {
        return [
            'conversationId' => ['required', 'string', 'exists:conversations,id'],
            'userId' => ['required', 'string', 'exists:users,id'],
            'removedBy' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```php
### MarkMessageAsRead Command

Marks a message as read by a user.

```php
<?php

namespace App\Commands\Messages;

use Hirethunk\Verbs\Command;

class MarkMessageAsReadCommand extends Command
{
    public function __construct(
        public string $conversationId,
        public string $messageId,
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'conversationId' => ['required', 'string', 'exists:conversations,id'],
            'messageId' => ['required', 'string'],
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```text

### StartTyping Command

Indicates that a user has started typing in a conversation.

```php
<?php

namespace App\Commands\Messages;

use Hirethunk\Verbs\Command;

class StartTypingCommand extends Command
{
    public function __construct(
        public string $conversationId,
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'conversationId' => ['required', 'string', 'exists:conversations,id'],
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```php
### StopTyping Command

Indicates that a user has stopped typing in a conversation.

```php
<?php

namespace App\Commands\Messages;

use Hirethunk\Verbs\Command;

class StopTypingCommand extends Command
{
    public function __construct(
        public string $conversationId,
        public string $userId
    ) {}

    public function rules(): array
    {
        return [
            'conversationId' => ['required', 'string', 'exists:conversations,id'],
            'userId' => ['required', 'string', 'exists:users,id'],
        ];
    }
}
```text

## Message Events

### ConversationCreated Event

Represents a conversation creation event.

```php
<?php

namespace App\Events\Messages;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ConversationCreated extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `name`: Conversation name
- `type`: Conversation type (direct, group, team)
- `participant_ids`: IDs of the initial participants
- `creator_id`: ID of the conversation creator
- `created_at`: Creation timestamp

### MessageSent Event

Represents a message sending event.

```php
<?php

namespace App\Events\Messages;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MessageSent extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `content`: Message content
- `sender_id`: ID of the message sender
- `attachments`: Message attachments (if any)
- `sent_at`: Sending timestamp

### MessageEdited Event

Represents a message editing event.

```php
<?php

namespace App\Events\Messages;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MessageEdited extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `message_id`: ID of the edited message
- `content`: Updated message content
- `edited_at`: Editing timestamp

### MessageDeleted Event

Represents a message deletion event.

```php
<?php

namespace App\Events\Messages;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MessageDeleted extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `message_id`: ID of the deleted message
- `deleted_at`: Deletion timestamp

### ParticipantAdded Event

Represents a participant addition event.

```php
<?php

namespace App\Events\Messages;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ParticipantAdded extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `user_id`: ID of the added participant
- `added_by`: ID of the user who added the participant
- `added_at`: Addition timestamp

### ParticipantRemoved Event

Represents a participant removal event.

```php
<?php

namespace App\Events\Messages;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ParticipantRemoved extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `user_id`: ID of the removed participant
- `removed_by`: ID of the user who removed the participant
- `removed_at`: Removal timestamp

### MessageRead Event

Represents a message read event.

```php
<?php

namespace App\Events\Messages;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MessageRead extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `message_id`: ID of the read message
- `user_id`: ID of the user who read the message
- `read_at`: Reading timestamp

### TypingStarted Event

Represents a typing start event.

```php
<?php

namespace App\Events\Messages;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TypingStarted extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```text

The payload includes:
- `user_id`: ID of the user who started typing
- `started_at`: Start timestamp

### TypingStopped Event

Represents a typing stop event.

```php
<?php

namespace App\Events\Messages;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TypingStopped extends ShouldBeStored
{
    public function __construct(
        public array $payload
    ) {}
}
```php
The payload includes:
- `user_id`: ID of the user who stopped typing
- `stopped_at`: Stop timestamp

## Message Aggregate Implementation

### Command Methods

The Message aggregate implements methods to handle various commands:

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Events\Messages\ConversationCreated;
use App\Events\Messages\MessageSent;
use App\Events\Messages\MessageEdited;
use App\Events\Messages\MessageDeleted;
use App\Events\Messages\ParticipantAdded;
use App\Events\Messages\ParticipantRemoved;
use App\Events\Messages\MessageRead;
use App\Events\Messages\TypingStarted;
use App\Events\Messages\TypingStopped;
use App\Exceptions\Messages\MessageNotFoundException;
use App\Exceptions\Messages\UnauthorizedMessageActionException;
use App\Exceptions\Messages\ParticipantNotFoundException;
use Illuminate\Support\Str;

class MessageAggregateRoot extends AggregateRoot
{
    protected string $conversationType;
    protected string $name;
    protected array $participants = [];
    protected array $messages = [];
    protected array $typingUsers = [];

    public function createConversation(string $name, string $type, array $participantIds, string $creatorId): self
    {
        $this->recordThat(new ConversationCreated([
            'name' => $name,
            'type' => $type,
            'participant_ids' => $participantIds,
            'creator_id' => $creatorId,
            'created_at' => now(),
        ]));

        return $this;
    }

    public function sendMessage(string $content, string $senderId, ?array $attachments = null): self
    {
        // Check if sender is a participant
        $this->ensureParticipant($senderId);

        $this->recordThat(new MessageSent([
            'content' => $content,
            'sender_id' => $senderId,
            'attachments' => $attachments,
            'sent_at' => now(),
        ]));

        return $this;
    }

    public function editMessage(string $messageId, string $content, string $userId): self
    {
        // Check if user is a participant
        $this->ensureParticipant($userId);

        // Find the message
        $message = $this->findMessage($messageId);

        // Check if user is the sender of the message
        if ($message['sender_id'] !== $userId) {
            throw new UnauthorizedMessageActionException("Only the sender can edit a message");
        }

        $this->recordThat(new MessageEdited([
            'message_id' => $messageId,
            'content' => $content,
            'edited_at' => now(),
        ]));

        return $this;
    }

    public function deleteMessage(string $messageId, string $userId): self
    {
        // Check if user is a participant
        $this->ensureParticipant($userId);

        // Find the message
        $message = $this->findMessage($messageId);

        // Check if user is the sender of the message
        if ($message['sender_id'] !== $userId) {
            throw new UnauthorizedMessageActionException("Only the sender can delete a message");
        }

        $this->recordThat(new MessageDeleted([
            'message_id' => $messageId,
            'deleted_at' => now(),
        ]));

        return $this;
    }

    public function addParticipant(string $userId, string $addedBy): self
    {
        // Check if the user adding the participant is a participant
        $this->ensureParticipant($addedBy);

        // Check if user is already a participant
        foreach ($this->participants as $participant) {
            if ($participant['user_id'] === $userId) {
                // User is already a participant, do nothing
                return $this;
            }
        }

        $this->recordThat(new ParticipantAdded([
            'user_id' => $userId,
            'added_by' => $addedBy,
            'added_at' => now(),
        ]));

        return $this;
    }

    public function removeParticipant(string $userId, string $removedBy): self
    {
        // Check if the user removing the participant is a participant
        $this->ensureParticipant($removedBy);

        // Check if user is a participant
        $found = false;
        foreach ($this->participants as $participant) {
            if ($participant['user_id'] === $userId) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new ParticipantNotFoundException("User is not a participant in this conversation");
        }

        $this->recordThat(new ParticipantRemoved([
            'user_id' => $userId,
            'removed_by' => $removedBy,
            'removed_at' => now(),
        ]));

        return $this;
    }

    public function markMessageAsRead(string $messageId, string $userId): self
    {
        // Check if user is a participant
        $this->ensureParticipant($userId);

        // Find the message
        $this->findMessage($messageId);

        $this->recordThat(new MessageRead([
            'message_id' => $messageId,
            'user_id' => $userId,
            'read_at' => now(),
        ]));

        return $this;
    }

    public function startTyping(string $userId): self
    {
        // Check if user is a participant
        $this->ensureParticipant($userId);

        $this->recordThat(new TypingStarted([
            'user_id' => $userId,
            'started_at' => now(),
        ]));

        return $this;
    }

    public function stopTyping(string $userId): self
    {
        // Check if user is a participant
        $this->ensureParticipant($userId);

        $this->recordThat(new TypingStopped([
            'user_id' => $userId,
            'stopped_at' => now(),
        ]));

        return $this;
    }

    protected function ensureParticipant(string $userId): void
    {
        foreach ($this->participants as $participant) {
            if ($participant['user_id'] === $userId) {
                return;
            }
        }

        throw new ParticipantNotFoundException("User is not a participant in this conversation");
    }

    protected function findMessage(string $messageId): array
    {
        foreach ($this->messages as $message) {
            if ($message['id'] === $messageId) {
                return $message;
            }
        }

        throw new MessageNotFoundException("Message not found");
    }
}
```text

### Apply Methods

The Message aggregate implements apply methods to update its state based on events:

```php
protected function applyConversationCreated(ConversationCreated $event): void
{
    $this->name = $event->payload['name'];
    $this->conversationType = $event->payload['type'];

    // Add all participants
    foreach ($event->payload['participant_ids'] as $participantId) {
        $this->participants[] = [
            'user_id' => $participantId,
            'added_at' => $event->payload['created_at'],
            'added_by' => $event->payload['creator_id'],
        ];
    }
}

protected function applyMessageSent(MessageSent $event): void
{
    $this->messages[] = [
        'id' => (string) Str::uuid(),
        'content' => $event->payload['content'],
        'sender_id' => $event->payload['sender_id'],
        'attachments' => $event->payload['attachments'],
        'sent_at' => $event->payload['sent_at'],
        'read_by' => [],
        'edited' => false,
        'deleted' => false,
    ];
}

protected function applyMessageEdited(MessageEdited $event): void
{
    foreach ($this->messages as $index => $message) {
        if ($message['id'] === $event->payload['message_id']) {
            $this->messages[$index]['content'] = $event->payload['content'];
            $this->messages[$index]['edited'] = true;
            break;
        }
    }
}

protected function applyMessageDeleted(MessageDeleted $event): void
{
    foreach ($this->messages as $index => $message) {
        if ($message['id'] === $event->payload['message_id']) {
            $this->messages[$index]['deleted'] = true;
            break;
        }
    }
}

protected function applyParticipantAdded(ParticipantAdded $event): void
{
    $this->participants[] = [
        'user_id' => $event->payload['user_id'],
        'added_at' => $event->payload['added_at'],
        'added_by' => $event->payload['added_by'],
    ];
}

protected function applyParticipantRemoved(ParticipantRemoved $event): void
{
    foreach ($this->participants as $index => $participant) {
        if ($participant['user_id'] === $event->payload['user_id']) {
            unset($this->participants[$index]);
            break;
        }
    }

    // Reindex the array
    $this->participants = array_values($this->participants);

    // Remove from typing users if they were typing
    if (isset($this->typingUsers[$event->payload['user_id']])) {
        unset($this->typingUsers[$event->payload['user_id']]);
    }
}

protected function applyMessageRead(MessageRead $event): void
{
    foreach ($this->messages as $index => $message) {
        if ($message['id'] === $event->payload['message_id']) {
            // Check if user has already read the message
            if (!in_array($event->payload['user_id'], $this->messages[$index]['read_by'])) {
                $this->messages[$index]['read_by'][] = $event->payload['user_id'];
            }
            break;
        }
    }
}

protected function applyTypingStarted(TypingStarted $event): void
{
    $this->typingUsers[$event->payload['user_id']] = $event->payload['started_at'];
}

protected function applyTypingStopped(TypingStopped $event): void
{
    if (isset($this->typingUsers[$event->payload['user_id']])) {
        unset($this->typingUsers[$event->payload['user_id']]);
    }
}
```text
### Business Rules

The Message aggregate enforces several business rules:

1. **Participant Validation**: Only participants can send, edit, or delete messages

2. **Message Ownership**: Only the sender of a message can edit or delete it

3. **Participant Management**: Only participants can add or remove other participants

4. **Message Existence**: Operations on messages require the message to exist

5. **Typing Indicators**: Only participants can send typing indicators

## Integration with Real-time Functionality

<details>
<summary>Real-time Messaging Flow Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
sequenceDiagram
    participant User1 as User 1
    participant Frontend1 as Frontend (User 1)
    participant Backend as Backend
    participant EventStore as Event Store
    participant WebSocket as WebSocket Server
    participant Frontend2 as Frontend (User 2)
    participant User2 as User 2

    User1->>Frontend1: Type message
    Frontend1->>Backend: SendMessageCommand
    Backend->>EventStore: Store MessageSent event
    Backend->>WebSocket: Broadcast message.sent event
    WebSocket->>Frontend2: Push message.sent event
    Frontend2->>User2: Display new message

    User2->>Frontend2: Mark as read
    Frontend2->>Backend: MarkMessageAsReadCommand
    Backend->>EventStore: Store MessageRead event
    Backend->>WebSocket: Broadcast message.read event
    WebSocket->>Frontend1: Push message.read event
    Frontend1->>User1: Display read receipt
```text

For dark mode, see [Real-time Messaging Flow (Dark Mode)](../../illustrations/mermaid/dark/realtime-messaging-flow-dark.mmd)
</details>

The Message aggregate integrates with Laravel Reverb for real-time functionality.

### Real-time Updates

Real-time updates are implemented using Laravel Reverb and event broadcasting:

```php
<?php

namespace App\Reactors;

use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;
use App\Events\Messages\MessageSent;
use App\Events\Messages\MessageEdited;
use App\Events\Messages\MessageDeleted;
use App\Events\Messages\ParticipantAdded;
use App\Events\Messages\ParticipantRemoved;
use App\Events\Messages\MessageRead;
use App\Events\Messages\TypingStarted;
use App\Events\Messages\TypingStopped;
use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

class MessageReactor extends Reactor
{
    public function onMessageSent(MessageSent $event, string $aggregateUuid)
    {
        $conversation = Conversation::with('participants')->findOrFail($aggregateUuid);

        // Broadcast to all participants
        Broadcast::to('conversation.' . $aggregateUuid)
            ->event('message.sent', [
                'conversation_id' => $aggregateUuid,
                'content' => $event->payload['content'],
                'sender_id' => $event->payload['sender_id'],
                'attachments' => $event->payload['attachments'],
                'sent_at' => $event->payload['sent_at'],
            ]);

        // Send notifications to participants
        foreach ($conversation->participants as $participant) {
            if ($participant->user_id !== $event->payload['sender_id']) {
                $participant->user->notify(new NewMessageNotification($conversation, $event->payload));
            }
        }
    }

    public function onMessageEdited(MessageEdited $event, string $aggregateUuid)
    {
        // Broadcast to all participants
        Broadcast::to('conversation.' . $aggregateUuid)
            ->event('message.edited', [
                'conversation_id' => $aggregateUuid,
                'message_id' => $event->payload['message_id'],
                'content' => $event->payload['content'],
                'edited_at' => $event->payload['edited_at'],
            ]);
    }

    public function onMessageDeleted(MessageDeleted $event, string $aggregateUuid)
    {
        // Broadcast to all participants
        Broadcast::to('conversation.' . $aggregateUuid)
            ->event('message.deleted', [
                'conversation_id' => $aggregateUuid,
                'message_id' => $event->payload['message_id'],
                'deleted_at' => $event->payload['deleted_at'],
            ]);
    }

    public function onMessageRead(MessageRead $event, string $aggregateUuid)
    {
        // Broadcast to all participants
        Broadcast::to('conversation.' . $aggregateUuid)
            ->event('message.read', [
                'conversation_id' => $aggregateUuid,
                'message_id' => $event->payload['message_id'],
                'user_id' => $event->payload['user_id'],
                'read_at' => $event->payload['read_at'],
            ]);
    }

    public function onTypingStarted(TypingStarted $event, string $aggregateUuid)
    {
        // Broadcast typing indicator
        Broadcast::to('conversation.' . $aggregateUuid)
            ->event('typing.started', [
                'conversation_id' => $aggregateUuid,
                'user_id' => $event->payload['user_id'],
                'started_at' => $event->payload['started_at'],
            ]);
    }

    public function onTypingStopped(TypingStopped $event, string $aggregateUuid)
    {
        // Broadcast typing stopped
        Broadcast::to('conversation.' . $aggregateUuid)
            ->event('typing.stopped', [
                'conversation_id' => $aggregateUuid,
                'user_id' => $event->payload['user_id'],
                'stopped_at' => $event->payload['stopped_at'],
            ]);
    }
}
```javascript
### Typing Indicators

Typing indicators are implemented using the `TypingStarted` and `TypingStopped` events. The frontend can listen for these events and display typing indicators accordingly:

```javascript
// In a Livewire component
Echo.private(`conversation.${this.conversationId}`)
    .listen('typing.started', (event) => {
        if (event.user_id !== this.currentUserId) {
            this.typingUsers[event.user_id] = true;
        }
    })
    .listen('typing.stopped', (event) => {
        if (event.user_id !== this.currentUserId) {
            delete this.typingUsers[event.user_id];
        }
    });
```text

### Read Receipts

Read receipts are implemented using the `MessageRead` event. The frontend can listen for these events and display read receipts accordingly:

```javascript
// In a Livewire component
Echo.private(`conversation.${this.conversationId}`)
    .listen('message.read', (event) => {
        const messageIndex = this.messages.findIndex(m => m.id === event.message_id);
        if (messageIndex !== -1) {
            if (!this.messages[messageIndex].read_by.includes(event.user_id)) {
                this.messages[messageIndex].read_by.push(event.user_id);
            }
        }
    });
```text
## Chat Room Management

<details>
<summary>Conversation Types Diagram</summary>

```mermaid
%%{init: {'theme': 'default', 'themeVariables': { 'primaryColor': '#f5f5f5', 'primaryTextColor': '#333333', 'primaryBorderColor': '#cccccc', 'lineColor': '#666666', 'secondaryColor': '#f0f0f0', 'tertiaryColor': '#ffffff' }}}%%
classDiagram
    class Conversation {
        +string id
        +string name
        +string type
        +array participants
        +array messages
    }

    class DirectConversation {
        +limit participants to 2
        +auto-name from participants
    }

    class GroupConversation {
        +custom name
        +multiple participants
        +participant management
    }

    class TeamConversation {
        +linked to team
        +team-based permissions
        +channels support
    }

    Conversation <|-- DirectConversation
    Conversation <|-- GroupConversation
    Conversation <|-- TeamConversation
```text

For dark mode, see [Conversation Types (Dark Mode)](../../illustrations/mermaid/dark/conversation-types-dark.mmd)
</details>

### Conversation Types

The Message aggregate supports different types of conversations:

1. **Direct**: One-on-one conversations between two users
2. **Group**: Group conversations with multiple participants
3. **Team**: Team-based conversations associated with a team

Each type has different rules for participant management:

```php
public function addParticipant(string $userId, string $addedBy): self
{
    // Check if the user adding the participant is a participant
    $this->ensureParticipant($addedBy);

    // Direct conversations cannot have more than 2 participants
    if ($this->conversationType === 'direct' && count($this->participants) >= 2) {
        throw new InvalidConversationOperationException("Cannot add more participants to a direct conversation");
    }

    // Rest of the method...
}
```php
### Participant Management

Participant management is handled through the `addParticipant` and `removeParticipant` methods. These methods enforce rules based on the conversation type and ensure that only participants can manage other participants.

For team conversations, there are additional rules:

```php
public function addParticipant(string $userId, string $addedBy): self
{
    // Check if the user adding the participant is a participant
    $this->ensureParticipant($addedBy);

    // For team conversations, check if the user is a member of the team
    if ($this->conversationType === 'team') {
        $team = Team::findOrFail($this->teamId);
        if (!$team->hasMember($userId)) {
            throw new InvalidConversationOperationException("Cannot add a user who is not a member of the team");
        }
    }

    // Rest of the method...
}
```text

## Command Handlers

### CreateConversationCommandHandler

Handles conversation creation:

```php
<?php

namespace App\CommandHandlers\Messages;

use App\Commands\Messages\CreateConversationCommand;
use App\Aggregates\MessageAggregateRoot;
use Hirethunk\Verbs\CommandHandler;
use Illuminate\Support\Str;

class CreateConversationCommandHandler extends CommandHandler
{
    public function handle(CreateConversationCommand $command)
    {
        // Generate a UUID for the conversation
        $conversationId = (string) Str::uuid();

        // Create the conversation
        MessageAggregateRoot::retrieve($conversationId)
            ->createConversation(
                $command->name,
                $command->type,
                $command->participantIds,
                $command->creatorId
            )
            ->persist();

        return $conversationId;
    }
}
```php
### SendMessageCommandHandler

Handles message sending:

```php
<?php

namespace App\CommandHandlers\Messages;

use App\Commands\Messages\SendMessageCommand;
use App\Aggregates\MessageAggregateRoot;
use Hirethunk\Verbs\CommandHandler;

class SendMessageCommandHandler extends CommandHandler
{
    public function handle(SendMessageCommand $command)
    {
        // Authorize the command
        $this->authorize('sendMessage', ['App\Models\Conversation', $command->conversationId]);

        // Send the message
        MessageAggregateRoot::retrieve($command->conversationId)
            ->sendMessage(
                $command->content,
                $command->senderId,
                $command->attachments
            )
            ->persist();

        return $this->success();
    }
}
```text

### Other Command Handlers

Similar command handlers exist for other message commands:

- `EditMessageCommandHandler`
- `DeleteMessageCommandHandler`
- `AddParticipantCommandHandler`
- `RemoveParticipantCommandHandler`
- `MarkMessageAsReadCommandHandler`
- `StartTypingCommandHandler`
- `StopTypingCommandHandler`

## Benefits and Challenges

### Benefits

1. **Complete Message History**: Every message action is recorded as an event, providing a complete history of conversations

2. **Real-time Updates**: Integration with Laravel Reverb provides real-time updates to all participants

3. **Typing Indicators**: Real-time typing indicators enhance the user experience

4. **Read Receipts**: Read receipts provide feedback on message delivery and reading

5. **Conversation Types**: Support for different conversation types (direct, group, team) provides flexibility

### Challenges

1. **Complexity**: Event sourcing adds complexity to the messaging system

2. **Performance**: Reconstructing conversation state from events can be slow for conversations with many messages

3. **Real-time Integration**: Integrating with real-time functionality requires careful planning

4. **Offline Support**: Handling offline messaging and synchronization can be challenging

### Mitigation Strategies

1. **Snapshots**: Use snapshots to improve performance for conversations with many messages

2. **Caching**: Cache conversation projections to improve read performance

3. **Clear Documentation**: Document the message aggregate thoroughly to help developers understand the system

4. **Offline Queue**: Implement an offline queue for messages sent while offline

## Troubleshooting

### Common Issues

<details>
<summary>Real-time updates not working</summary>

**Symptoms:**
- Messages are sent but not received in real-time
- Typing indicators are not displayed

**Possible Causes:**
- Laravel Reverb not configured correctly
- WebSocket connection issues
- Event broadcasting not set up correctly

**Solutions:**
1. Verify Laravel Reverb configuration
2. Check WebSocket connection in browser console
3. Ensure event broadcasting is configured correctly
4. Check that the reactor is properly registered
</details>

<details>
<summary>Message history not loading correctly</summary>

**Symptoms:**
- Messages are missing from the conversation
- Messages are displayed in the wrong order

**Possible Causes:**
- Projector not updating the conversation model correctly
- Missing apply methods for message events
- Incorrect ordering of messages in the projector

**Solutions:**
1. Ensure projectors update the conversation model correctly
2. Add apply methods for all message events
3. Verify message ordering in the projector
</details>

### Solutions

For detailed solutions to common issues, refer to the [Event Sourcing Troubleshooting Guide](070-testing.md#troubleshooting).

## Related Documents

- [Event Sourcing Aggregates](020-000-aggregates.md) - Overview of aggregate implementation in event sourcing
- [User Aggregate](020-010-user-aggregate.md) - Detailed documentation on User aggregate
- [Team Aggregate](020-020-team-aggregate.md) - Detailed documentation on Team aggregate
- [Event Sourcing Projectors](030-projectors.md) - Detailed documentation on projector implementation
- [Event Sourcing Real-time](110-real-time.md) - Integration of event sourcing with real-time functionality

## Version History

<details>
<summary>Version History Table</summary>

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.1.0 | 2025-05-18 | Added message command flow diagram, real-time messaging flow diagram, conversation types diagram, wrapped tables in collapsible sections | AI Assistant |
| 1.0.0 | 2025-05-18 | Initial version | AI Assistant |
</details>
