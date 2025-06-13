# Phase 1: Event Sourcing Snapshots

**Version:** 1.0.0
**Date:** 2025-05-19
**Author:** AI Assistant
**Status:** New
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Why Use Snapshots](#why-use-snapshots)
- [Implementation](#implementation)
  - [Snapshot Repository](#snapshot-repository)
  - [Snapshot Strategy](#snapshot-strategy)
  - [Snapshot Configuration](#snapshot-configuration)
- [Usage](#usage)
  - [Creating Snapshots](#creating-snapshots)
  - [Loading from Snapshots](#loading-from-snapshots)
- [Performance Considerations](#performance-considerations)
- [Testing Snapshots](#testing-snapshots)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document describes the implementation of snapshots in the event sourcing system of the Enhanced Laravel Application (ELA). Snapshots provide a performance optimization for aggregates with a large number of events by storing the aggregate state at a specific point in time.

## Why Use Snapshots

When an aggregate has a large number of events, reconstructing its state by replaying all events can become a performance bottleneck. Snapshots solve this problem by:

1. Storing the aggregate state at a specific point in time
2. Allowing the aggregate to be reconstituted from the snapshot plus any newer events
3. Significantly reducing the number of events that need to be replayed

## Implementation

### Snapshot Repository

We'll implement a snapshot repository that will be responsible for storing and retrieving snapshots:

```php
namespace App\EventSourcing\Snapshots;

use Spatie\EventSourcing\Snapshots\SnapshotRepository as BaseSnapshotRepository;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class SnapshotRepository extends BaseSnapshotRepository
{
    public function persist(AggregateRoot $aggregateRoot): void
    {
        // Store the snapshot in the database
        $snapshot = new Snapshot([
            'aggregate_uuid' => $aggregateRoot->uuid(),
            'aggregate_version' => $aggregateRoot->aggregateVersion(),
            'state' => serialize($aggregateRoot->getState()),
        ]);
        
        $snapshot->save();
    }
    
    public function retrieve(string $aggregateUuid): ?AggregateRoot
    {
        // Retrieve the latest snapshot for the aggregate
        $snapshot = Snapshot::where('aggregate_uuid', $aggregateUuid)
            ->orderBy('aggregate_version', 'desc')
            ->first();
            
        if (!$snapshot) {
            return null;
        }
        
        // Reconstitute the aggregate from the snapshot
        $aggregateRoot = $this->getAggregateRoot($aggregateUuid);
        $aggregateRoot->setState(unserialize($snapshot->state));
        $aggregateRoot->setAggregateVersion($snapshot->aggregate_version);
        
        return $aggregateRoot;
    }
}
```php
### Snapshot Strategy

We'll implement a snapshot strategy that determines when to create a snapshot:

```php
namespace App\EventSourcing\Snapshots;

use Spatie\EventSourcing\Snapshots\SnapshotStrategy;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class CountBasedSnapshotStrategy implements SnapshotStrategy
{
    private int $threshold;
    
    public function __construct(int $threshold = 50)
    {
        $this->threshold = $threshold;
    }
    
    public function shouldSnapshot(AggregateRoot $aggregateRoot): bool
    {
        // Create a snapshot when the number of events exceeds the threshold
        return $aggregateRoot->getAppliedEvents()->count() >= $this->threshold;
    }
}
```php
### Snapshot Configuration

We'll configure the snapshot repository and strategy in the service provider:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\EventSourcing\Snapshots\SnapshotRepository;
use App\EventSourcing\Snapshots\CountBasedSnapshotStrategy;

class EventSourcingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SnapshotRepository::class, function () {
            return new SnapshotRepository();
        });
        
        $this->app->singleton(CountBasedSnapshotStrategy::class, function () {
            return new CountBasedSnapshotStrategy(
                config('event-sourcing.snapshots.threshold', 50)
            );
        });
    }
}
```php
## Usage

### Creating Snapshots

Snapshots will be created automatically based on the snapshot strategy. However, you can also create snapshots manually:

```php
use App\EventSourcing\Snapshots\SnapshotRepository;

// Get the aggregate
$team = TeamAggregate::retrieve($teamUuid);

// Create a snapshot
app(SnapshotRepository::class)->persist($team);
```php
### Loading from Snapshots

When retrieving an aggregate, the system will automatically check for a snapshot and use it if available:

```php
use App\EventSourcing\Snapshots\SnapshotRepository;

// Try to load from snapshot first
$team = app(SnapshotRepository::class)->retrieve($teamUuid);

// If no snapshot is available, load from events
if (!$team) {
    $team = TeamAggregate::retrieve($teamUuid);
}

// Apply any events that occurred after the snapshot
$team->reconstituteFromEvents();
```php
## Performance Considerations

- Snapshots should be created asynchronously to avoid impacting user-facing operations
- Consider using a queue to handle snapshot creation
- Monitor the size of snapshots and adjust the threshold accordingly
- For very large aggregates, consider using incremental snapshots

## Testing Snapshots

Here's an example of how to test snapshots:

```php
public function test_aggregate_can_be_reconstituted_from_snapshot()
{
    // Create an aggregate with multiple events
    $team = TeamAggregate::retrieve($this->teamUuid)
        ->createTeam('Engineering')
        ->addMember($this->userId)
        ->addMember($this->userId2)
        ->addMember($this->userId3);
        
    // Create a snapshot
    app(SnapshotRepository::class)->persist($team);
    
    // Clear the event store cache
    TeamAggregate::clearStaticCache();
    
    // Reconstitute from snapshot
    $reconstitutedTeam = app(SnapshotRepository::class)->retrieve($this->teamUuid);
    
    // Verify the state is correct
    $this->assertEquals('Engineering', $reconstitutedTeam->name());
    $this->assertCount(3, $reconstitutedTeam->members());
}
```

## Related Documents

- [Event Sourcing Implementation](050-implementation.md)
- [Aggregates](020-000-aggregates.md)
- [Testing](070-testing.md)

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-19 | Initial version | AI Assistant |
