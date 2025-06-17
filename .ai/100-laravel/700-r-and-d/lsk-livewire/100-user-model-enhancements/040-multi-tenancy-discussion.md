# Multi-Tenancy Implementation Following User Model Enhancements

## Introduction

This document explores options for implementing multi-tenancy capabilities following the user model enhancements. Multi-tenancy refers to a software architecture where a single instance of an application serves multiple customers (tenants) while keeping their data separate. Each tenant's data is isolated and remains invisible to other tenants.

The recently implemented user model enhancements provide a solid foundation for multi-tenancy with:
- Enhanced user profiles with structured name components
- Team functionality with hierarchical structures
- Role-based permissions
- User tracking and auditing

This document evaluates three primary approaches to implementing multi-tenancy:
1. Using Spatie's Laravel Multi-Tenancy package
2. Using Filament's multi-tenancy capabilities
3. Building a custom multi-tenancy solution

## Option 1: Spatie Laravel Multi-Tenancy

[Spatie Laravel Multi-Tenancy](https://spatie.be/docs/laravel-multitenancy/v4/introduction) is a dedicated package for implementing multi-tenancy in Laravel applications.

### Overview

Spatie's package offers two primary ways to determine the current tenant:
1. **Domain-based tenancy**: Each tenant has their own domain (e.g., tenant1.example.com, tenant2.example.com)
2. **Path-based tenancy**: Tenants are identified by a path segment (e.g., example.com/tenant1, example.com/tenant2)

The package provides automatic tenant data isolation through database prefixing or separate databases per tenant.

### Implementation Approach

1. Install the package:
   ```bash
   composer require spatie/laravel-multitenancy
   ```

2. Publish and run migrations:
   ```bash
   php artisan vendor:publish --provider="Spatie\Multitenancy\MultitenancyServiceProvider" --tag="migrations"
   php artisan migrate
   ```

3. Create a Tenant model that extends Spatie's base Tenant model:
   ```php
   namespace App\Models;

   use Illuminate\Database\Eloquent\Relations\BelongsTo;
   use Spatie\Multitenancy\Models\Tenant as BaseTenant;

   class Tenant extends BaseTenant
   {
       protected $fillable = [
           'name',
           'domain',
           'database',
           'owner_id',
       ];

       public function owner(): BelongsTo
       {
           return $this->belongsTo(User::class, 'owner_id');
       }
   }
   ```

4. Configure the tenant connection in `config/multitenancy.php`:
   ```php
   'tenant_database_connection_name' => 'tenant',
   'landlord_database_connection_name' => 'landlord',
   ```

5. Integrate with the existing Team model:
   ```php
   // In App\Models\Team
   public function tenant()
   {
       return $this->hasOne(Tenant::class);
   }
   ```

### Top 5 Pros

1. **Dedicated Multi-Tenancy Solution**: Purpose-built for multi-tenancy with robust features for tenant isolation.
2. **Multiple Tenancy Strategies**: Supports both domain and path-based tenancy, as well as multiple database isolation strategies.
3. **Automatic Data Isolation**: Handles database switching and query scoping automatically.
4. **Tenant-Aware Jobs and Cache**: Provides tools for making queued jobs and cache tenant-aware.
5. **Active Maintenance**: Actively maintained by Spatie with regular updates and good documentation.

### Top 5 Cons

1. **Integration Complexity**: Requires significant changes to existing code to make it tenant-aware.
2. **Learning Curve**: Introduces new concepts and patterns that the team needs to learn.
3. **Limited UI Components**: Doesn't provide UI components for tenant management.
4. **Performance Overhead**: Adds some performance overhead due to tenant switching and middleware.
5. **Database Migration Challenges**: Managing migrations across multiple tenant databases can be complex.

### Confidence Score: 85%

**Reasoning**: Spatie's multi-tenancy package is a mature, well-documented solution specifically designed for Laravel. It aligns well with our existing user model enhancements, particularly the team functionality. The high confidence score reflects the package's proven track record, active maintenance, and comprehensive features. However, it's not 100% because integration with existing team structures requires careful planning, and there's some complexity in managing database migrations across tenants.

## Option 2: Filament Multi-Tenancy

[Filament](https://filamentphp.com/docs) is an admin panel and application starter kit for Laravel with built-in multi-tenancy support.

### Overview

Filament's multi-tenancy is primarily designed for admin panel access control but can be extended to the entire application. It uses a tenant-aware middleware approach and integrates well with Laravel's authentication system.

### Implementation Approach

1. Install Filament:
   ```bash
   composer require filament/filament
   ```

2. Publish Filament configuration:
   ```bash
   php artisan vendor:publish --tag=filament-config
   ```

3. Configure the Team model as a tenant:
   ```php
   // config/filament.php
   'tenant_model' => App\Models\Team::class,
   ```

4. Implement the HasTenants interface on the User model:
   ```php
   use Filament\Models\Contracts\HasTenants;
   use Illuminate\Database\Eloquent\Relations\BelongsToMany;

   class User extends Authenticatable implements HasTenants
   {
       // Existing traits and methods...

       public function getTenants(): Collection
       {
           return $this->teams;
       }

       public function canAccessTenant(Model $tenant): bool
       {
           return $this->teams->contains($tenant);
       }
   }
   ```

5. Create tenant-aware resources in Filament:
   ```php
   use Filament\Resources\Resource;

   class UserResource extends Resource
   {
       protected static ?string $tenant = 'team';
       
       // Resource configuration...
   }
   ```

### Top 5 Pros

1. **Integrated Admin Panel**: Provides a complete admin panel with multi-tenancy built-in.
2. **UI Components Included**: Offers pre-built UI components for tenant management and switching.
3. **Seamless Authentication Integration**: Works well with Laravel's authentication system.
4. **Low Learning Curve**: If already using Filament, adding multi-tenancy is straightforward.
5. **Active Development**: Actively developed with a growing community and regular updates.

### Top 5 Cons

1. **Limited to Filament Ecosystem**: Multi-tenancy features are primarily designed for the Filament admin panel.
2. **Less Flexible Database Isolation**: Doesn't provide as many options for database isolation as Spatie's package.
3. **Opinionated Structure**: Imposes Filament's structure and conventions on your application.
4. **Potential Overhead**: Including the full Filament package adds overhead if you only need multi-tenancy.
5. **Less Documentation on Multi-Tenancy**: Documentation specifically for multi-tenancy is less comprehensive than Spatie's.

### Confidence Score: 70%

**Reasoning**: Filament provides a solid multi-tenancy solution, especially if you're already using or planning to use Filament for your admin panel. The 70% confidence reflects its strengths in UI components and integration with Laravel's authentication, but acknowledges limitations in database isolation strategies and the fact that it's more focused on admin panel multi-tenancy rather than application-wide multi-tenancy. The score is lower than Spatie's because Filament's multi-tenancy is a feature of a larger package rather than a dedicated solution.

## Option 3: Custom Multi-Tenancy Solution

Building a custom multi-tenancy solution tailored to the existing user model enhancements and team structure.

### Overview

A custom solution would leverage the existing team functionality as the foundation for tenancy, with additional middleware and scoping to ensure proper data isolation.

### Implementation Approach

1. Create a Tenant model that extends or relates to the Team model:
   ```php
   namespace App\Models;

   use Illuminate\Database\Eloquent\Model;
   use Illuminate\Database\Eloquent\Relations\BelongsTo;

   class Tenant extends Model
   {
       protected $fillable = [
           'name',
           'domain',
           'database_prefix',
           'team_id',
       ];

       public function team(): BelongsTo
       {
           return $this->belongsTo(Team::class);
       }
   }
   ```

2. Implement a TenantManager service:
   ```php
   namespace App\Services;

   use App\Models\Tenant;
   use Illuminate\Support\Facades\DB;

   class TenantManager
   {
       protected ?Tenant $currentTenant = null;

       public function setCurrentTenant(?Tenant $tenant): void
       {
           $this->currentTenant = $tenant;
           
           if ($tenant) {
               // Set database prefix or connection
               config(['database.connections.tenant.prefix' => $tenant->database_prefix]);
               DB::purge('tenant');
           }
       }

       public function getCurrentTenant(): ?Tenant
       {
           return $this->currentTenant;
       }

       public function isInTenantContext(): bool
       {
           return $this->currentTenant !== null;
       }
   }
   ```

3. Create tenant middleware:
   ```php
   namespace App\Http\Middleware;

   use App\Services\TenantManager;
   use Closure;
   use Illuminate\Http\Request;

   class TenantMiddleware
   {
       protected TenantManager $tenantManager;

       public function __construct(TenantManager $tenantManager)
       {
           $this->tenantManager = $tenantManager;
       }

       public function handle(Request $request, Closure $next)
       {
           // Determine tenant from domain, path, or session
           $tenant = $this->resolveTenant($request);
           
           if ($tenant) {
               $this->tenantManager->setCurrentTenant($tenant);
           }

           return $next($request);
       }

       protected function resolveTenant(Request $request)
       {
           // Implementation depends on your tenancy strategy
           // (domain-based, path-based, etc.)
       }
   }
   ```

4. Implement a global scope for tenant isolation:
   ```php
   namespace App\Scopes;

   use App\Services\TenantManager;
   use Illuminate\Database\Eloquent\Builder;
   use Illuminate\Database\Eloquent\Model;
   use Illuminate\Database\Eloquent\Scope;

   class TenantScope implements Scope
   {
       protected TenantManager $tenantManager;

       public function __construct(TenantManager $tenantManager)
       {
           $this->tenantManager = $tenantManager;
       }

       public function apply(Builder $builder, Model $model)
       {
           if ($this->tenantManager->isInTenantContext()) {
               $builder->where('team_id', $this->tenantManager->getCurrentTenant()->team_id);
           }
       }
   }
   ```

5. Apply the scope to tenant-aware models:
   ```php
   namespace App\Models;

   use App\Scopes\TenantScope;
   use Illuminate\Database\Eloquent\Model;

   abstract class TenantAwareModel extends Model
   {
       protected static function booted()
       {
           static::addGlobalScope(app(TenantScope::class));
       }
   }
   ```

### Top 5 Pros

1. **Tailored to Existing Architecture**: Can be designed to perfectly fit the existing user model and team structure.
2. **Full Control**: Complete control over implementation details and behavior.
3. **No External Dependencies**: No reliance on third-party packages that might change or become unsupported.
4. **Performance Optimization**: Can be optimized specifically for your application's needs.
5. **Incremental Implementation**: Can be implemented gradually, starting with the most critical models.

### Top 5 Cons

1. **Development Time**: Requires significant development effort to build and test.
2. **Maintenance Burden**: Ongoing maintenance responsibility falls entirely on your team.
3. **Potential for Bugs**: Higher risk of bugs and security issues compared to established packages.
4. **Documentation Effort**: Requires creating and maintaining internal documentation.
5. **Feature Completeness**: May lack features that come standard in dedicated packages.

### Confidence Score: 65%

**Reasoning**: A custom solution offers the highest degree of flexibility and integration with the existing user model enhancements. The 65% confidence score reflects the potential for a perfect fit with the application's specific needs, but acknowledges the significant development and maintenance burden. The score is lower than the package options because of the increased risk of bugs, security issues, and the need for comprehensive testing. However, for applications with unique requirements or where tight integration with existing team structures is paramount, a custom solution might be the best approach despite these challenges.

## Recommendation: Spatie Laravel Multi-Tenancy

**Confidence Score: 80%**

### Reasoning

After evaluating all three options, I recommend implementing multi-tenancy using Spatie's Laravel Multi-Tenancy package for the following reasons:

1. **Dedicated Solution**: Spatie's package is purpose-built for multi-tenancy in Laravel applications, offering a comprehensive set of features specifically designed for tenant isolation.

2. **Flexible Isolation Strategies**: The package supports multiple approaches to tenant isolation (domain-based, path-based, separate databases, database prefixing), allowing for flexibility in implementation.

3. **Integration Potential**: While requiring some adaptation, the package can be integrated with the existing team structure by treating teams as tenants or creating a one-to-one relationship between teams and tenants.

4. **Reduced Development Time**: Using a proven package significantly reduces development time compared to building a custom solution, while still offering more flexibility than Filament's approach.

5. **Active Maintenance and Community**: Spatie actively maintains the package, and it has a large community of users, reducing the risk of it becoming unsupported.

The 80% confidence score (slightly lower than the individual score for Spatie) reflects that while Spatie's package is the best overall option, the integration with the existing team structure will require careful planning and some customization. The recommendation acknowledges that there's no perfect one-size-fits-all solution for multi-tenancy, but Spatie's package offers the best balance of features, flexibility, and maintenance burden.

## Implementation Roadmap

If proceeding with Spatie Laravel Multi-Tenancy, here's a high-level implementation roadmap:

1. **Planning Phase**:
   - Decide on a tenant isolation strategy (domain vs. path-based)
   - Determine database isolation approach (separate databases vs. prefixing)
   - Map existing team structure to tenant concepts

2. **Setup Phase**:
   - Install and configure the package
   - Create and configure tenant models
   - Set up tenant middleware and database connections

3. **Integration Phase**:
   - Integrate with existing team functionality
   - Implement tenant switching in the UI
   - Make models tenant-aware

4. **Testing Phase**:
   - Test tenant isolation
   - Verify data integrity across tenants
   - Performance testing

5. **Deployment Phase**:
   - Staged rollout to production
   - Monitoring and optimization
   - Documentation and training

## Conclusion

Implementing multi-tenancy following the user model enhancements is a significant undertaking that will add powerful capabilities to the application. Spatie's Laravel Multi-Tenancy package offers the best balance of features, flexibility, and maintenance burden, making it the recommended approach. However, each option has its strengths and may be more suitable depending on specific project requirements and constraints.

The existing user model enhancements, particularly the team functionality, provide a solid foundation for multi-tenancy regardless of the approach chosen. With careful planning and implementation, multi-tenancy can be successfully added to the application, enabling it to serve multiple customers while maintaining proper data isolation.
