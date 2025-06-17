# Phase 1: Event Sourcing CQRS Integration

**Version:** 1.0.0
**Date:** 2025-05-19
**Author:** AI Assistant
**Status:** New
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [CQRS Architecture](#cqrs-architecture)
  - [Command Side](#command-side)
  - [Query Side](#query-side)
- [Integration with Event Sourcing](#integration-with-event-sourcing)
  - [Commands and Aggregates](#commands-and-aggregates)
  - [Events and Projectors](#events-and-projectors)
  - [Queries and Read Models](#queries-and-read-models)
- [Implementation](#implementation)
  - [Command Bus](#command-bus)
  - [Query Bus](#query-bus)
  - [Command Handlers](#command-handlers)
  - [Query Handlers](#query-handlers)
- [Example: Team Management](#example-team-management)
  - [Command Side Implementation](#command-side-implementation)
  - [Query Side Implementation](#query-side-implementation)
- [Testing](#testing)
- [Performance Considerations](#performance-considerations)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document describes the integration of Command Query Responsibility Segregation (CQRS) with Event Sourcing in the Enhanced Laravel Application (ELA). This integration provides a clear separation of concerns between write operations (commands) and read operations (queries), while leveraging the benefits of event sourcing for data consistency and auditability.

## CQRS Architecture

CQRS separates the application into two parts:

### Command Side

The command side handles write operations:
- Receives commands from the user interface
- Validates commands
- Processes commands through command handlers
- Updates the write model (aggregates)
- Emits events

### Query Side

The query side handles read operations:
- Receives queries from the user interface
- Processes queries through query handlers
- Returns data from the read model
- Does not modify data

## Integration with Event Sourcing

### Commands and Aggregates

Commands are processed by command handlers, which load the appropriate aggregate, apply the command, and persist the resulting events:

1. Command is dispatched through the command bus
2. Command handler retrieves the aggregate
3. Aggregate processes the command and emits events
4. Events are stored in the event store
5. Events are dispatched to projectors and reactors

### Events and Projectors

Events emitted by aggregates are processed by projectors to update the read model:

1. Events are dispatched from the event store
2. Projectors handle events and update the read model
3. Read model is optimized for querying

### Queries and Read Models

Queries are processed by query handlers, which retrieve data from the read model:

1. Query is dispatched through the query bus
2. Query handler retrieves data from the read model
3. Data is returned to the caller

## Implementation

### Command Bus

We'll use Laravel's built-in bus for dispatching commands:

```php
namespace App\CQRS\Commands;

use Illuminate\Bus\Dispatcher;

class CommandBus
{
    private Dispatcher $dispatcher;
    
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    
    public function dispatch($command)
    {
        return $this->dispatcher->dispatch($command);
    }
}
```php
### Query Bus

Similarly, we'll implement a query bus:

```php
namespace App\CQRS\Queries;

use Illuminate\Bus\Dispatcher;

class QueryBus
{
    private Dispatcher $dispatcher;
    
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    
    public function dispatch($query)
    {
        return $this->dispatcher->dispatch($query);
    }
}
```php
### Command Handlers

Command handlers will interact with aggregates:

```php
namespace App\CQRS\Commands\Teams;

use App\CQRS\Commands\CommandHandler;
use App\EventSourcing\Aggregates\TeamAggregate;

class CreateTeamCommandHandler implements CommandHandler
{
    public function handle(CreateTeamCommand $command)
    {
        TeamAggregate::retrieve($command->teamUuid)
            ->createTeam($command->name, $command->description)
            ->persist();
            
        return $command->teamUuid;
    }
}
```php
### Query Handlers

Query handlers will interact with read models:

```php
namespace App\CQRS\Queries\Teams;

use App\CQRS\Queries\QueryHandler;
use App\ReadModels\TeamReadModel;

class GetTeamQueryHandler implements QueryHandler
{
    private TeamReadModel $teamReadModel;
    
    public function __construct(TeamReadModel $teamReadModel)
    {
        $this->teamReadModel = $teamReadModel;
    }
    
    public function handle(GetTeamQuery $query)
    {
        return $this->teamReadModel->find($query->teamUuid);
    }
}
```php
## Example: Team Management

### Command Side Implementation

1. Create a command:

```php
namespace App\CQRS\Commands\Teams;

class CreateTeamCommand
{
    public string $teamUuid;
    public string $name;
    public ?string $description;
    
    public function __construct(string $teamUuid, string $name, ?string $description = null)
    {
        $this->teamUuid = $teamUuid;
        $this->name = $name;
        $this->description = $description;
    }
}
```php
2. Create a command handler:

```php
namespace App\CQRS\Commands\Teams;

use App\CQRS\Commands\CommandHandler;
use App\EventSourcing\Aggregates\TeamAggregate;

class CreateTeamCommandHandler implements CommandHandler
{
    public function handle(CreateTeamCommand $command)
    {
        TeamAggregate::retrieve($command->teamUuid)
            ->createTeam($command->name, $command->description)
            ->persist();
            
        return $command->teamUuid;
    }
}
```php
### Query Side Implementation

1. Create a query:

```php
namespace App\CQRS\Queries\Teams;

class GetTeamQuery
{
    public string $teamUuid;
    
    public function __construct(string $teamUuid)
    {
        $this->teamUuid = $teamUuid;
    }
}
```php
2. Create a query handler:

```php
namespace App\CQRS\Queries\Teams;

use App\CQRS\Queries\QueryHandler;
use App\ReadModels\TeamReadModel;

class GetTeamQueryHandler implements QueryHandler
{
    private TeamReadModel $teamReadModel;
    
    public function __construct(TeamReadModel $teamReadModel)
    {
        $this->teamReadModel = $teamReadModel;
    }
    
    public function handle(GetTeamQuery $query)
    {
        return $this->teamReadModel->find($query->teamUuid);
    }
}
```php
## Testing

Testing CQRS components involves testing commands, queries, and their handlers:

```php
public function test_create_team_command()
{
    // Arrange
    $command = new CreateTeamCommand(
        Str::uuid()->toString(),
        'Engineering'
    );
    
    // Act
    $teamUuid = $this->app->make(CommandBus::class)->dispatch($command);
    
    // Assert
    $this->assertDatabaseHas('teams', [
        'uuid' => $teamUuid,
        'name' => 'Engineering'
    ]);
}

public function test_get_team_query()
{
    // Arrange
    $teamUuid = Str::uuid()->toString();
    TeamAggregate::retrieve($teamUuid)
        ->createTeam('Engineering')
        ->persist();
        
    $query = new GetTeamQuery($teamUuid);
    
    // Act
    $team = $this->app->make(QueryBus::class)->dispatch($query);
    
    // Assert
    $this->assertEquals('Engineering', $team->name);
}
```

## Performance Considerations

- Use read models optimized for specific query patterns
- Consider caching frequently accessed read models
- Use database indexes on read models to optimize queries
- Monitor query performance and optimize as needed

## Related Documents

- [Event Sourcing Implementation](050-implementation.md)
- [Aggregates](020-000-aggregates.md)
- [Projectors](030-projectors.md)
- [Queries](060-queries.md)

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-19 | Initial version | AI Assistant |
