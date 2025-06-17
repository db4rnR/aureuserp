# 3. Inconsistencies and Decisions Analysis

## 3.1. Document Overview

**Purpose**: Identify architectural inconsistencies across R&D streams, propose solutions, and document key architectural decisions with confidence scores and risk assessments.

**Target Audience**: Junior developers implementing features and senior developers making architectural decisions.

**Confidence**: 82% - Based on code analysis and pattern recognition across multiple streams, with some assumptions about implementation intent.

---

## 3.2. Critical Inconsistencies

### 3.2.1. Event Sourcing Implementation Variations

**Issue**: Multiple approaches to event sourcing across streams with varying levels of sophistication.

**Streams Affected**: E_L_A, StandAloneComplex, ume

**Current State**:

-   E_L_A: Uses `hirethunk/verbs` with sophisticated event handling
-   StandAloneComplex: Custom event implementation with basic event storage
-   ume: Mixed approach with some direct database updates bypassing events

**Risk Level**: 🔴 **High (85%)** - Data consistency and audit trail issues

**Proposed Solution**:

```php
// Standardise on hirethunk/verbs across all streams
// 1. Create base event classes
abstract class BaseEvent extends Verb
{
    protected function validateState(): void
    {
        // Common validation logic
    }

    protected function authorize(): bool
    {
        // Common authorization logic
        return true;
    }
}

// 2. Implement consistent event naming
class UserRegisteredEvent extends BaseEvent
{
    // Standard naming: [Entity][Action]Event
}

// 3. Establish event versioning strategy
class UserRegisteredEventV2 extends BaseEvent
{
    public static function getVersion(): string
    {
        return '2.0.0';
    }
}
```

**Implementation Priority**: 🔴 **Immediate** - Data integrity risks

**Confidence**: 88% - Clear pattern established, known library capabilities

---

### 3.2.2. STI Implementation Inconsistencies

**Issue**: Different approaches to Single Table Inheritance across user and organization models.

**Streams Affected**: All streams

**Current State**:

-   Some streams use `type` column, others use `role` or `kind`
-   Inconsistent discriminator mapping
-   Mixed inheritance hierarchies

**Risk Level**: 🟡 **Medium (65%)** - Code maintainability and query complexity

**Proposed Solution**:

```php
// Standardise STI implementation
abstract class BaseUser extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'type'];

    // Standard discriminator column
    public function getTable()
    {
        return 'users';
    }

    // Consistent type mapping
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->type)) {
                $model->type = static::getType();
            }
        });
    }

    abstract public static function getType(): string;
}

class AdminUser extends BaseUser
{
    public static function getType(): string
    {
        return 'admin';
    }

    protected static function booted()
    {
        static::addGlobalScope('type', function ($query) {
            return $query->where('type', static::getType());
        });
    }
}
```

**Implementation Priority**: 🟡 **Medium** - Refactor during feature development

**Confidence**: 75% - Well-established Laravel pattern, but requires significant refactoring

---

### 3.2.3. CQRS Command/Query Separation

**Issue**: Inconsistent separation of commands and queries, with some read operations mixed into command handlers.

**Streams Affected**: E_L_A, lsk-livewire

**Current State**:

-   Some command handlers return data (violating CQRS principles)
-   Query classes sometimes trigger side effects
-   Mixed responsibility in some handlers

**Risk Level**: 🟡 **Medium (70%)** - Performance and maintainability issues

**Proposed Solution**:

```php
// Clear command/query separation
abstract class BaseCommand
{
    abstract public function handle(): void; // No return value
}

abstract class BaseQuery
{
    abstract public function handle(): mixed; // Returns data
}

// Example implementation
class CreateUserCommand extends BaseCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $type
    ) {}

    public function handle(): void
    {
        // Only create, no return
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
        ]);

        // Dispatch events for side effects
        event(new UserCreatedEvent($user));
    }
}

class GetUserQuery extends BaseQuery
{
    public function __construct(
        public readonly int $userId
    ) {}

    public function handle(): ?User
    {
        return User::find($this->userId);
    }
}
```

**Implementation Priority**: 🟡 **Medium** - Improve during code reviews

**Confidence**: 80% - Clear architectural principle, gradual implementation possible

---

## 3.3. Administrative Panel Inconsistencies

### 3.3.1. Filament Integration Patterns

**Issue**: Varying approaches to integrating Filament with CQRS backend.

**Streams Affected**: lsk-livewire, ume

**Current State**:

-   Some panels bypass CQRS and go directly to Eloquent
-   Inconsistent validation between admin and API endpoints
-   Mixed error handling approaches

**Risk Level**: 🟡 **Medium (60%)** - Data consistency and user experience issues

**Proposed Solution**:

```php
// Standardised Filament resource with CQRS integration
abstract class BaseCQRSResource extends Resource
{
    protected function dispatchCommand($commandClass, array $data): void
    {
        $command = new $commandClass(...array_values($data));
        $command->handle();
    }

    protected function dispatchQuery($queryClass, array $parameters = []): mixed
    {
        $query = new $queryClass(...array_values($parameters));
        return $query->handle();
    }
}

class UserResource extends BaseCQRSResource
{
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Form fields
        ])->using(function (array $data) {
            // Use CQRS command instead of direct Eloquent
            $this->dispatchCommand(CreateUserCommand::class, $data);
        });
    }

    public static function table(Table $table): Table
    {
        return $table->query(
            // Use CQRS query instead of direct Eloquent
            fn() => $this->dispatchQuery(GetUsersQuery::class)
        );
    }
}
```

**Implementation Priority**: 🟡 **Medium** - Implement with new admin features

**Confidence**: 70% - Requires custom Filament integration, some unknowns

---

## 3.4. Database Design Inconsistencies

### 3.4.1. Identifier Strategy Variations

**Issue**: Mixed use of UUIDs, sequential IDs, and composite keys across streams.

**Streams Affected**: All streams

**Current State**:

-   Some tables use UUIDs, others use auto-incrementing integers
-   Inconsistent foreign key relationships
-   Mixed approach to soft deletes and timestamps

**Risk Level**: 🔴 **High (80%)** - Data integrity and performance implications

**Proposed Solution**:

```php
// Standardised identifier strategy
trait HasStandardIdentifiers
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid();
            }
        });
    }
}

// Multi-tier identifier strategy
abstract class BaseModel extends Model
{
    use HasStandardIdentifiers, SoftDeletes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Standard scopes
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
```

**Implementation Priority**: 🔴 **High** - Requires database migration strategy

**Confidence**: 85% - Well-understood pattern, significant implementation effort

---

## 3.5. Architectural Decisions Record

### 3.5.1. Decision: Event Sourcing Library Choice

**Date**: Current assessment
**Status**: Proposed
**Decision**: Standardise on `hirethunk/verbs` for all event sourcing

**Context**: Multiple event sourcing implementations causing maintenance overhead

**Consequences**:

-   ✅ Consistent event handling across streams
-   ✅ Better testing and debugging capabilities
-   ❌ Requires migration of existing custom implementations
-   ❌ Learning curve for team members

**Risk Assessment**: 🟡 **Medium (40%)** - Known library, migration complexity

---

### 3.5.2. Decision: CQRS Implementation Strategy

**Date**: Current assessment
**Status**: Proposed
**Decision**: Implement strict command/query separation with Laravel service container

**Context**: Current mixed approach causing performance and maintainability issues

**Consequences**:

-   ✅ Clear separation of concerns
-   ✅ Better performance optimisation opportunities
-   ✅ Easier testing and debugging
-   ❌ Requires refactoring existing code
-   ❌ More complex initial setup

**Risk Assessment**: 🟡 **Medium (35%)** - Gradual implementation possible

---

### 3.5.3. Decision: Administrative Panel Architecture

**Date**: Current assessment
**Status**: Proposed
**Decision**: Custom Filament integration with CQRS backend

**Context**: Need for admin panels that appear traditional but use event sourcing

**Consequences**:

-   ✅ Consistent data flow through CQRS
-   ✅ Full audit trail for admin actions
-   ✅ Familiar CRUD interface for administrators
-   ❌ Custom development required
-   ❌ Potential performance overhead

**Risk Assessment**: 🟡 **Medium (45%)** - Custom integration complexity

---

## 3.6. Implementation Recommendations

### 3.6.1. Priority Matrix

| Issue                          | Risk Level | Implementation Effort | Priority  |
| ------------------------------ | ---------- | --------------------- | --------- |
| Event Sourcing Standardisation | High       | High                  | 🔴 **P1** |
| Identifier Strategy            | High       | Very High             | 🔴 **P1** |
| CQRS Separation                | Medium     | Medium                | 🟡 **P2** |
| STI Implementation             | Medium     | High                  | 🟡 **P2** |
| Filament Integration           | Medium     | Medium                | 🟡 **P3** |

### 3.6.2. Risk Mitigation Strategies

**High-Risk Items**:

1. **Database Migrations**: Implement blue-green deployment strategy
2. **Event Store Migration**: Use parallel event streams during transition
3. **Data Consistency**: Implement comprehensive testing suite

**Medium-Risk Items**:

1. **CQRS Refactoring**: Gradual implementation per feature
2. **Team Training**: Dedicated workshops and documentation
3. **Performance Testing**: Continuous monitoring during implementation

---

## 3.7. Success Metrics

-   **Code Consistency**: 90% adherence to architectural patterns
-   **Technical Debt**: Reduce inconsistencies by 75% over 6 months
-   **Developer Velocity**: Maintain current sprint velocity during refactoring
-   **System Reliability**: Zero data consistency issues post-implementation

---

## 3.8. Cross-References

-   See [Architecture Roadmap](050-architecture-roadmap.md) for implementation timeline
-   See [Quick Reference Guides](110-sti-implementation-guide.md) for implementation details
-   See [Risk Assessment](080-risk-assessment.md) for detailed risk analysis

---

**Document Confidence**: 82% - Based on code analysis and established architectural patterns

**Last Updated**: June 2025
**Next Review**: August 2025
