# 4. Architecture Roadmap

## 4.1. Document Overview

**Purpose**: Define near-term (1-9 months) and medium-term (6-24 months) technical evolution plans for the R&D architecture.

**Target Audience**: Technical leads, senior developers, and product managers planning feature development cycles.

**Confidence**: 78% - Based on current technical capabilities analysis and industry best practices, with some assumptions about resource availability.

---

## 4.2. Current Architecture State (June 2025)

### 4.2.1. Technical Maturity Assessment

| Component                | Maturity Level          | Confidence |
| ------------------------ | ----------------------- | ---------- |
| Event Sourcing           | 🟡 **Developing (60%)** | 85%        |
| CQRS Implementation      | 🟡 **Developing (45%)** | 80%        |
| STI Patterns             | 🟡 **Developing (55%)** | 75%        |
| Admin Panels             | 🟡 **Developing (40%)** | 70%        |
| Multi-stream Integration | 🔴 **Early (25%)**      | 65%        |

### 4.2.2. Technical Debt Inventory

**Critical Issues** (🔴 High Priority):

-   Inconsistent event sourcing implementations across streams
-   Mixed identifier strategies (UUIDs vs auto-increment)
-   CQRS principle violations in command handlers

**Important Issues** (🟡 Medium Priority):

-   STI implementation variations
-   Filament-CQRS integration gaps
-   Cross-stream data consistency challenges

**Minor Issues** (🟢 Low Priority):

-   Code style inconsistencies
-   Documentation gaps
-   Testing coverage variations

---

## 4.3. Near-Term Roadmap (July 2025 - March 2026)

### 4.3.1. Phase 1: Foundation Stabilisation (July - September 2025)

**Objective**: Establish consistent architectural foundations across all R&D streams.

**Key Deliverables**:

#### Month 1-2: Event Sourcing Standardisation

```php
// Target architecture
namespace App\Events\Foundation;

abstract class BaseEvent extends Verb
{
    use ValidatesState, AuthorizesAction, TracksMetadata;

    protected function applyToState(EventState $state): EventState
    {
        // Standard event application pattern
        return $this->mutateState($state);
    }

    abstract protected function mutateState(EventState $state): EventState;
}

// Implementation across streams
class UserRegisteredEvent extends BaseEvent
{
    protected function mutateState(EventState $state): EventState
    {
        return $state->withUser($this->userData);
    }
}
```

**Success Criteria**:

-   ✅ All streams use `hirethunk/verbs` consistently
-   ✅ Common base event classes implemented
-   ✅ Event versioning strategy established
-   ✅ Migration from custom implementations completed

**Risk Level**: 🟡 **Medium (45%)** - Known library, migration complexity

**Resource Requirements**: 2 senior developers, 3-4 weeks

---

#### Month 2-3: Identifier Strategy Unification

```php
// Target identifier architecture
trait HasUnifiedIdentifiers
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = self::generateId();
            }
        });
    }

    protected static function generateId(): string
    {
        return Str::uuid()->toString();
    }
}
```

**Success Criteria**:

-   ✅ All new tables use UUID primary keys
-   ✅ Migration strategy for existing auto-increment IDs
-   ✅ Foreign key relationships updated
-   ✅ Performance impact assessment completed

**Risk Level**: 🔴 **High (75%)** - Database migration complexity

**Resource Requirements**: 3 senior developers, 4-5 weeks

---

### 4.3.2. Phase 2: CQRS Implementation (October - December 2025)

**Objective**: Implement strict command/query separation with performance optimisation.

#### Month 4-5: Command/Query Infrastructure

```php
// Target CQRS architecture
namespace App\CQRS;

interface CommandInterface
{
    public function handle(): void;
}

interface QueryInterface
{
    public function handle(): mixed;
}

class CommandBus
{
    public function dispatch(CommandInterface $command): void
    {
        $command->handle();

        // Trigger side effects via events
        $this->dispatchEvents($command);
    }
}

class QueryBus
{
    public function dispatch(QueryInterface $query): mixed
    {
        return $query->handle();
    }
}
```

**Success Criteria**:

-   ✅ Command bus implementation completed
-   ✅ Query bus with caching layer
-   ✅ Existing code refactored to use buses
-   ✅ Performance benchmarks established

**Risk Level**: 🟡 **Medium (40%)** - Gradual implementation possible

**Resource Requirements**: 2 senior developers, 1 junior developer, 6 weeks

---

#### Month 5-6: Admin Panel Integration

```php
// Target admin panel architecture
abstract class CQRSResource extends Resource
{
    protected function executeCommand(string $commandClass, array $data): void
    {
        $command = app($commandClass, $data);
        app(CommandBus::class)->dispatch($command);
    }

    protected function executeQuery(string $queryClass, array $params = []): mixed
    {
        $query = app($queryClass, $params);
        return app(QueryBus::class)->dispatch($query);
    }
}
```

**Success Criteria**:

-   ✅ Filament resources use CQRS backend
-   ✅ Traditional CRUD interface maintained
-   ✅ Full audit trail for admin actions
-   ✅ Performance optimisation completed

**Risk Level**: 🟡 **Medium (50%)** - Custom integration complexity

**Resource Requirements**: 2 senior developers, 4 weeks

---

### 4.3.3. Phase 3: STI Standardisation (January - March 2026)

**Objective**: Implement consistent Single Table Inheritance patterns across user and organization models.

#### Month 7-8: User Model Hierarchy

```php
// Target STI architecture
abstract class BaseUser extends Model
{
    use HasUnifiedIdentifiers, SoftDeletes;

    protected $table = 'users';
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('type', function ($query) {
            return $query->where('type', static::getType());
        });
    }

    abstract public static function getType(): string;
    abstract public function getCapabilities(): array;
}

class AdminUser extends BaseUser
{
    public static function getType(): string
    {
        return 'admin';
    }

    public function getCapabilities(): array
    {
        return ['user_management', 'system_configuration'];
    }
}
```

**Success Criteria**:

-   ✅ User hierarchy standardised across streams
-   ✅ Organization hierarchy implemented
-   ✅ Type-specific capabilities defined
-   ✅ Migration scripts for existing data

**Risk Level**: 🟡 **Medium (55%)** - Data migration complexity

**Resource Requirements**: 2 senior developers, 1 junior developer, 5 weeks

---

## 4.4. Medium-Term Roadmap (April 2026 - June 2027)

### 4.4.1. Phase 4: Advanced Event Sourcing (April - August 2026)

**Objective**: Implement sophisticated event sourcing patterns with event store optimisation.

#### Advanced Event Patterns

```php
// Target advanced architecture
class EventStore
{
    public function store(EventInterface $event): void
    {
        // Implement event store with snapshots
        $this->storeEvent($event);
        $this->updateSnapshots($event);
        $this->triggerProjections($event);
    }

    public function replay(string $aggregateId, ?DateTime $pointInTime = null): array
    {
        // Replay events from snapshots
        return $this->getEventsFromSnapshot($aggregateId, $pointInTime);
    }
}

class EventProjectionManager
{
    public function project(EventInterface $event): void
    {
        // Update read models
        foreach ($this->getProjectors($event) as $projector) {
            $projector->handle($event);
        }
    }
}
```

**Key Features**:

-   Event store with snapshot support
-   Event projection system for read models
-   Event replay capabilities for debugging
-   Performance-optimised event querying

**Success Criteria**:

-   ✅ Event store with snapshot functionality
-   ✅ Automated projection updates
-   ✅ Event replay system operational
-   ✅ Performance improvements documented

**Risk Level**: 🟡 **Medium (60%)** - Complex event sourcing patterns

**Resource Requirements**: 3 senior developers, 12 weeks

---

### 4.4.2. Phase 5: Multi-Stream Integration (September 2026 - January 2027)

**Objective**: Create seamless integration layer between R&D streams with shared event bus.

#### Cross-Stream Event Bus

```php
// Target integration architecture
class CrossStreamEventBus
{
    public function publishToStream(string $stream, EventInterface $event): void
    {
        // Publish events across streams
        $this->validateStreamAccess($stream, $event);
        $this->routeEvent($stream, $event);
        $this->trackCrossStreamMetrics($stream, $event);
    }

    public function subscribeToStream(string $stream, callable $handler): void
    {
        // Subscribe to events from other streams
        $this->registerSubscription($stream, $handler);
    }
}
```

**Key Features**:

-   Shared event bus between streams
-   Cross-stream data consistency
-   Unified user identity across streams
-   Shared administrative interfaces

**Success Criteria**:

-   ✅ Cross-stream event publishing
-   ✅ Unified user authentication
-   ✅ Shared administrative dashboard
-   ✅ Data consistency guarantees

**Risk Level**: 🔴 **High (70%)** - Complex integration challenges

**Resource Requirements**: 4 senior developers, 16 weeks

---

### 4.4.3. Phase 6: Performance Optimisation (February - June 2027)

**Objective**: Optimise system performance with advanced caching and query optimisation.

#### Performance Architecture

```php
// Target performance architecture
class CachedQueryBus extends QueryBus
{
    public function dispatch(QueryInterface $query): mixed
    {
        $cacheKey = $this->generateCacheKey($query);

        return Cache::tags($this->getCacheTags($query))
            ->remember($cacheKey, $this->getCacheTtl($query), function () use ($query) {
                return parent::dispatch($query);
            });
    }

    public function invalidateCache(EventInterface $event): void
    {
        $tags = $this->getInvalidationTags($event);
        Cache::tags($tags)->flush();
    }
}
```

**Key Features**:

-   Intelligent query caching
-   Event-driven cache invalidation
-   Database query optimisation
-   Real-time performance monitoring

**Success Criteria**:

-   ✅ 50% reduction in average response time
-   ✅ Intelligent caching implementation
-   ✅ Query optimisation completed
-   ✅ Performance monitoring dashboard

**Risk Level**: 🟡 **Medium (45%)** - Well-understood optimisation techniques

**Resource Requirements**: 2 senior developers, 1 performance specialist, 12 weeks

---

## 4.5. Technology Evolution Plan

### 4.5.1. Laravel Framework Evolution

**Current**: Laravel 11.x
**Target (Medium-term)**: Laravel 12.x with enhanced event sourcing support

**Migration Strategy**:

-   Gradual upgrade approach during Phase 4
-   Leverage new Laravel features for event sourcing
-   Maintain backward compatibility during transition

**Risk Assessment**: 🟡 **Medium (35%)** - Regular Laravel upgrade process

---

### 4.5.2. Database Technology Evolution

**Current**: MySQL/PostgreSQL with traditional schema
**Target**: Event store-optimised database with read replicas

**Evolution Path**:

1. **Phase 1-3**: Optimise current schema for event sourcing
2. **Phase 4**: Implement dedicated event store
3. **Phase 5**: Add read replicas for query optimisation
4. **Phase 6**: Consider event store-specific databases (EventStore DB)

**Risk Assessment**: 🔴 **High (65%)** - Significant infrastructure changes

---

### 4.5.3. Frontend Technology Integration

**Current**: Livewire with limited real-time features
**Target**: Real-time updates via WebSocket integration

**Evolution Path**:

1. **Phase 2**: Implement Livewire polling for admin panels
2. **Phase 4**: Add WebSocket support for real-time updates
3. **Phase 5**: Cross-stream real-time notifications
4. **Phase 6**: Advanced real-time collaboration features

**Risk Assessment**: 🟡 **Medium (40%)** - Well-established WebSocket patterns

---

## 4.6. Resource Planning

### 4.6.1. Team Structure Requirements

**Near-Term (Phases 1-3)**:

-   3 Senior Laravel Developers
-   1 Junior Developer
-   1 Database Specialist
-   1 DevOps Engineer (part-time)

**Medium-Term (Phases 4-6)**:

-   4 Senior Laravel Developers
-   2 Junior Developers
-   1 Performance Specialist
-   1 Database Specialist
-   1 DevOps Engineer (full-time)

### 4.6.2. Budget Considerations

**Development Costs**:

-   Near-term: ~40 developer-weeks
-   Medium-term: ~52 developer-weeks
-   Infrastructure upgrades: ~£15-25k
-   Training and certifications: ~£5-8k

**Risk Mitigation Budget**: 15% contingency for unforeseen complications

---

## 4.7. Success Metrics and KPIs

### 4.7.1. Technical Metrics

| Metric                   | Current | Phase 3 Target | Phase 6 Target |
| ------------------------ | ------- | -------------- | -------------- |
| Code Consistency         | 45%     | 85%            | 95%            |
| Test Coverage            | 60%     | 80%            | 90%            |
| Event Sourcing Adoption  | 30%     | 90%            | 100%           |
| CQRS Compliance          | 25%     | 85%            | 95%            |
| Cross-Stream Integration | 10%     | 60%            | 90%            |

### 4.7.2. Performance Metrics

| Metric                   | Current    | Phase 3 Target | Phase 6 Target |
| ------------------------ | ---------- | -------------- | -------------- |
| Average Response Time    | 250ms      | 200ms          | 125ms          |
| Database Query Count     | 15/request | 12/request     | 8/request      |
| Cache Hit Rate           | 45%        | 70%            | 85%            |
| Event Processing Latency | 50ms       | 30ms           | 15ms           |

### 4.7.3. Business Metrics

| Metric                | Current  | Phase 3 Target | Phase 6 Target |
| --------------------- | -------- | -------------- | -------------- |
| Developer Velocity    | Baseline | +10%           | +25%           |
| Bug Resolution Time   | 2.5 days | 1.5 days       | 1 day          |
| Feature Delivery Time | 3 weeks  | 2.5 weeks      | 2 weeks        |
| Technical Debt Ratio  | 35%      | 20%            | 10%            |

---

## 4.8. Risk Management

### 4.8.1. Critical Risk Factors

**Technical Risks**:

-   Database migration complications (75% probability, high impact)
-   Performance degradation during transitions (45% probability, medium impact)
-   Cross-stream integration challenges (60% probability, high impact)

**Resource Risks**:

-   Key developer availability (40% probability, high impact)
-   Budget overruns (30% probability, medium impact)
-   Timeline compression pressure (55% probability, medium impact)

### 4.8.2. Mitigation Strategies

**Technical Mitigations**:

-   Comprehensive testing environments for database migrations
-   Performance monitoring during all phases
-   Proof-of-concept implementations before major changes

**Resource Mitigations**:

-   Cross-training team members on critical components
-   15% budget contingency for unforeseen issues
-   Flexible timeline with optional features identified

---

## 4.9. Decision Points and Gates

### 4.9.1. Phase Gate Criteria

**Phase 1 Gate (September 2025)**:

-   ✅ Event sourcing standardisation complete
-   ✅ Identifier strategy unified
-   ✅ No critical bugs in production
-   ✅ Team velocity maintained

**Phase 3 Gate (March 2026)**:

-   ✅ CQRS implementation complete
-   ✅ Admin panels integrated with CQRS
-   ✅ STI patterns standardised
-   ✅ Performance metrics achieved

**Phase 6 Gate (June 2027)**:

-   ✅ All technical metrics achieved
-   ✅ Cross-stream integration operational
-   ✅ Performance optimisations complete
-   ✅ Documentation and training complete

### 4.9.2. Go/No-Go Decisions

**Critical Decision Points**:

1. **Database Migration Approach** (Month 2): Blue-green vs rolling deployment
2. **Event Store Technology** (Month 10): Custom vs third-party solution
3. **Cross-Stream Architecture** (Month 16): Monolith vs microservices approach

---

## 4.10. Cross-References

-   See [Inconsistencies Analysis](040-inconsistencies-and-decisions.md) for current state details
-   See [Business Capabilities Roadmap](060-business-capabilities-roadmap.md) for business alignment
-   See [Risk Assessment](080-risk-assessment.md) for detailed risk analysis
-   See [Implementation Guides](110-sti-implementation-guide.md) for technical implementation details

---

**Document Confidence**: 78% - Based on technical analysis and industry best practices

**Last Updated**: June 2025
**Next Review**: September 2025
