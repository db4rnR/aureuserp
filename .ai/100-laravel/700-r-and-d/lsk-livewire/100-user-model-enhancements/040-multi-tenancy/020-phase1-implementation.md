# Multi-Tenancy Phase 1: Foundation (MVP)

This document provides detailed implementation steps for Phase 1 of the multi-tenancy implementation, which focuses on establishing the foundation for multi-tenancy using Spatie's Laravel Multi-Tenancy package.

## Step 1: Install and Configure Spatie Laravel Multi-Tenancy

### 1.1 Install the Package

```bash
composer require spatie/laravel-multitenancy
```

### 1.2 Publish and Run Migrations

```bash
php artisan vendor:publish --provider="Spatie\Multitenancy\MultitenancyServiceProvider" --tag="migrations"
php artisan migrate
```

This will create the `tenants` table with the following structure:
- `id`: Primary key
- `name`: Name of the tenant
- `domain`: Domain associated with the tenant
- `database`: Database name (not used with prefixing)
- `created_at` and `updated_at`: Timestamps

### 1.3 Publish the Configuration

```bash
php artisan vendor:publish --provider="Spatie\Multitenancy\MultitenancyServiceProvider" --tag="config"
```

### 1.4 Configure Multitenancy Settings

Edit the `config/multitenancy.php` file:

```php
return [
    // Tenant model
    'tenant_model' => \App\Models\Tenant::class,

    // Database connection settings
    'tenant_database_connection_name' => 'tenant',
    'landlord_database_connection_name' => 'landlord',

    // Tenant finder
    'tenant_finder' => \Spatie\Multitenancy\TenantFinder\DomainTenantFinder::class,

    // Tenant switching tasks
    'switch_tenant_tasks' => [
        \Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask::class,
        \Spatie\Multitenancy\Tasks\PrefixCacheTask::class,
    ],

    // Forget current tenant tasks
    'forget_current_tenant_tasks' => [
        \Spatie\Multitenancy\Tasks\ForgetCurrentTenantTask::class,
    ],
];
```

### 1.5 Configure Database Connections

Edit the `config/database.php` file to add tenant and landlord connections:

```php
'connections' => [
    // Default connection (landlord)
    'mysql' => [
        'driver' => 'mysql',
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],

    // Landlord connection (same as default but explicitly named)
    'landlord' => [
        'driver' => 'mysql',
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],

    // Tenant connection (uses prefix for isolation)
    'tenant' => [
        'driver' => 'mysql',
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'forge'),
        'username' => env('DB_USERNAME', 'forge'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '', // This will be dynamically set based on the tenant
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
],
```

## Step 2: Create Tenant Model and Integration with Team

### 2.1 Create the Tenant Model

Create a new file at `app/Models/Tenant.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    protected $fillable = [
        'name',
        'domain',
        'team_id',
    ];

    /**
     * Get the team associated with the tenant.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Check if this tenant is the landlord.
     */
    public function isLandlord(): bool
    {
        return $this->team_id === 0;
    }

    /**
     * Get the database prefix for this tenant.
     */
    public function getDatabasePrefix(): string
    {
        return 'tenant' . $this->id . '_';
    }

    /**
     * Make the current tenant active.
     */
    public function makeCurrent(): static
    {
        // Set the database prefix for the tenant connection
        $this->configureDatabasePrefix();

        return parent::makeCurrent();
    }

    /**
     * Configure the database prefix for this tenant.
     */
    protected function configureDatabasePrefix(): void
    {
        $prefix = $this->getDatabasePrefix();
        
        config([
            'database.connections.tenant.prefix' => $prefix,
        ]);
    }
}
```

### 2.2 Add Team ID to Tenants Table

Create a migration to add the team_id column to the tenants table:

```bash
php artisan make:migration add_team_id_to_tenants_table
```

Edit the migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('team_id')->after('domain')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('team_id');
        });
    }
};
```

Run the migration:

```bash
php artisan migrate
```

### 2.3 Update Team Model

Update the Team model to add the tenant relationship:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends Model
{
    // Existing code...

    /**
     * Get the tenant associated with the team.
     */
    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }

    /**
     * Check if this team is a tenant (root-level team).
     */
    public function isTenant(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Create a tenant for this team.
     */
    public function createTenant(string $domain): ?Tenant
    {
        if (!$this->isTenant()) {
            return null;
        }

        return Tenant::create([
            'name' => $this->name,
            'domain' => $domain,
            'team_id' => $this->id,
        ]);
    }
}
```

## Step 3: Create Tenant-Aware Models

### 3.1 Create a Base Tenant-Aware Model

Create a new file at `app/Models/Traits/UsesTenantConnection.php`:

```php
<?php

namespace App\Models\Traits;

trait UsesTenantConnection
{
    /**
     * Get the current connection name for the model.
     */
    public function getConnectionName()
    {
        return config('multitenancy.tenant_database_connection_name');
    }
}
```

### 3.2 Create a Base Tenant-Aware Model

Create a new file at `app/Models/TenantAwareModel.php`:

```php
<?php

namespace App\Models;

use App\Models\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

abstract class TenantAwareModel extends Model
{
    use UsesTenantConnection;
}
```

### 3.3 Create Tenant Middleware

Create a new file at `app/Http/Middleware/NeedsTenant.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;

class NeedsTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Tenant::checkCurrent()) {
            abort(404, 'Not found.');
        }

        return $next($request);
    }
}
```

Register the middleware in `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // Other middleware...
    'tenant' => \App\Http\Middleware\NeedsTenant::class,
];
```

## Step 4: Configure Tenant Routes

### 4.1 Create Tenant Routes File

Create a new file at `routes/tenant.php`:

```php
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here is where you can register tenant-specific routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['web', 'tenant'])->group(function () {
    Route::get('/', function () {
        return view('tenant.dashboard');
    })->name('tenant.dashboard');
});
```

### 4.2 Update RouteServiceProvider

Update the `app/Providers/RouteServiceProvider.php` file:

```php
public function boot(): void
{
    // Existing code...

    $this->routes(function () {
        // Existing routes...

        Route::middleware('web')
            ->group(base_path('routes/tenant.php'));
    });
}
```

## Step 5: Create Tenant Service Provider

### 5.1 Create Tenant Service Provider

Create a new file at `app/Providers/TenantServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Multitenancy\Models\Tenant;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Tenant::booted(function (Tenant $tenant) {
            // Configure the database prefix for the tenant
            if (method_exists($tenant, 'configureDatabasePrefix')) {
                $tenant->configureDatabasePrefix();
            }
        });
    }
}
```

### 5.2 Register the Service Provider

Add the service provider to the `providers` array in `config/app.php`:

```php
'providers' => [
    // Other service providers...
    App\Providers\TenantServiceProvider::class,
],
```

## Step 6: Testing the Implementation

### 6.1 Create a Test Tenant

Create a test tenant in the database:

```php
$team = Team::create([
    'name' => 'Test Tenant',
    'parent_id' => null,
]);

$tenant = Tenant::create([
    'name' => 'Test Tenant',
    'domain' => 'test.localhost',
    'team_id' => $team->id,
]);
```

### 6.2 Configure Local Development

For local development, you can use wildcard DNS services like Dnsmasq or add entries to your hosts file:

```
127.0.0.1 test.localhost
```

### 6.3 Test Tenant Isolation

Create a tenant-aware model:

```php
<?php

namespace App\Models;

class Post extends TenantAwareModel
{
    protected $fillable = [
        'title',
        'content',
    ];
}
```

Create a migration for the posts table:

```bash
php artisan make:migration create_posts_table
```

Edit the migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

Run the migration for the tenant:

```php
Tenant::current($tenant);
Artisan::call('migrate');
```

Create a post in the tenant context:

```php
Post::create([
    'title' => 'Test Post',
    'content' => 'This is a test post',
]);
```

Verify that the post was created with the correct prefix:

```sql
SELECT * FROM tenant1_posts;
```
