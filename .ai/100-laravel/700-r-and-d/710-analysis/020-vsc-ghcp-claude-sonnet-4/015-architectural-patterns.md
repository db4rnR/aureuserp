# 1. Architectural Patterns Analysis

## 1.1. Event Sourcing Architecture

### 1.1.1. Current State Assessment

The project currently has **zero event sourcing implementation**. This represents a fundamental architectural gap that requires complete foundation-level changes.

**Confidence: 95%** - Clear from package analysis and lack of event store infrastructure.

### 1.1.2. Target Architecture Components

~~~markdown
**Core Event Sourcing Stack**:
- Event Store: `spatie/laravel-event-sourcing` (recommended over hirethunk/verbs)
- Projections: Custom Laravel models with event replay capability  
- Snapshots: Periodic state captures for performance
- Command Bus: Laravel's built-in command handling
- Query Handlers: Read model optimization layer
~~~

### 1.1.3. Implementation Phases

**Phase 1: Foundation (Months 1-3)**
- Install core event sourcing package
- Create basic event store schema
- Implement first aggregate (User events)
- Basic projection system

**Phase 2: Business Logic (Months 4-8)**  
- Organisation aggregate with STI patterns
- CMS content events and projections
- Social interaction events
- Project management events

**Phase 3: Advanced Patterns (Months 9-12)**
- Event versioning and migration
- Saga patterns for complex workflows
- Performance optimization with snapshots
- Cross-aggregate event coordination

## 1.2. Single Table Inheritance (STI) Patterns

### 1.2.1. User Model Hierarchy

~~~php
// Target STI structure for User entities
abstract class User extends Model
{
    protected $fillable = ['type', 'name', 'email', 'status'];
    
    public function newQuery()
    {
        return parent::newQuery()->where('type', static::class);
    }
}

class AdminUser extends User
{
    protected static $singleTable = true;
    
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}

class ClientUser extends User
{
    protected static $singleTable = true;
    
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
~~~

**Implementation Status**: Not implemented (0%)
**Complexity**: Medium - requires careful database design
**Confidence: 82%** - Standard Laravel pattern with some custom implementation needed

### 1.2.2. Organisation Model Hierarchy

~~~php
abstract class Organisation extends Model
{
    protected $fillable = ['type', 'name', 'settings', 'status'];
    
    protected $casts = [
        'settings' => 'array',
        'status' => OrganisationStatus::class, // Enhanced enum
    ];
}

class Agency extends Organisation
{
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
    
    public function projects()
    {
        return $this->hasManyThrough(Project::class, Client::class);
    }
}

class Client extends Organisation  
{
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
    
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
~~~

**Benefits of STI Approach**:
- Reduced database complexity
- Shared behavior inheritance
- Polymorphic relationships
- Event sourcing compatibility
