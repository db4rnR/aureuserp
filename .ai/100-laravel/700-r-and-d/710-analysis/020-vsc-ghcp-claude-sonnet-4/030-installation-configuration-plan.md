~~~markdown
# 3. Installation and Configuration Implementation Plan

## 3.1. Pre-Installation Assessment

### 3.1.1. System Requirements

**Minimum Requirements**:
- **PHP**: 8.4+ (currently 8.2)
- **Node.js**: 18+ LTS
- **Database**: PostgreSQL 15+ (currently SQLite)
- **Redis**: 7+ for caching and sessions
- **Elasticsearch/Typesense**: For search capabilities

**Development Environment**:
- **Memory**: 8GB+ RAM
- **Storage**: 50GB+ available space
- **Docker**: For containerized services (Redis, PostgreSQL, Typesense)

### 3.1.2. Current Environment Audit

**PHP Version Check**:
```bash
php -v
# Expected: PHP 8.4.x (currently shows 8.2.x - UPGRADE NEEDED)
```

**Laravel Version Verification**:
```bash
php artisan --version
# Expected: Laravel Framework 12.x
```

**Database Migration Required**:
- **Current**: SQLite (file-based)
- **Target**: PostgreSQL (enterprise-grade)
- **Migration Strategy**: Export existing data, reconfigure connections

## 3.2. Phase 1: Foundation Infrastructure (Week 1)

### 3.2.1. PHP 8.4 Upgrade

**Step 1: Environment Preparation**
```bash
# Using Homebrew (macOS)
brew tap shivammathur/php
brew install php@8.4
brew link php@8.4 --force

# Verify PHP version
php -v
# Should show: PHP 8.4.x

# Update PATH if needed
echo 'export PATH="/opt/homebrew/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

**Step 2: Composer Update**
```bash
# Update composer.json PHP requirement
composer config platform.php 8.4.0

# Update composer itself
composer self-update

# Clear caches
composer clear-cache
php artisan config:clear
php artisan cache:clear
```

### 3.2.2. Database Migration: SQLite → PostgreSQL

**Step 1: PostgreSQL Setup**
```bash
# Install PostgreSQL via Docker (recommended for development)
docker run --name l-s-f-postgres \
  -e POSTGRES_DB=l_s_f \
  -e POSTGRES_USER=l_s_f_user \
  -e POSTGRES_PASSWORD=secure_password \
  -p 5432:5432 \
  -d postgres:15
```

**Step 2: Laravel Database Configuration**
```php
// config/database.php
'default' => env('DB_CONNECTION', 'pgsql'),

'connections' => [
    'pgsql' => [
        'driver' => 'pgsql',
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '5432'),
        'database' => env('DB_DATABASE', 'l_s_f'),
        'username' => env('DB_USERNAME', 'l_s_f_user'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => 'public',
        'sslmode' => 'prefer',
    ],
    // Keep SQLite for testing
    'sqlite_testing' => [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ],
]
```

**Step 3: Environment Configuration**
```bash
# Update .env file
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=l_s_f
DB_USERNAME=l_s_f_user
DB_PASSWORD=secure_password
```

### 3.2.3. Redis Setup for Caching

**Step 1: Redis Installation**
```bash
# Docker setup (recommended)
docker run --name l-s-f-redis \
  -p 6379:6379 \
  -d redis:7-alpine redis-server --appendonly yes
```

**Step 2: Laravel Cache Configuration**
```php
// config/cache.php
'default' => env('CACHE_DRIVER', 'redis'),

// config/session.php
'driver' => env('SESSION_DRIVER', 'redis'),

// config/queue.php
'default' => env('QUEUE_CONNECTION', 'redis'),
```

**Step 3: Environment Updates**
```bash
# Add to .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 3.3. Phase 2: Core Package Installation (Week 1-2)

### 3.3.1. Event Sourcing Foundation

**Step 1: Install Core Event Sourcing Packages**
```bash
# Modern event sourcing with Verbs
composer require hirethunk/verbs:^0.7

# Mature ecosystem with Spatie
composer require spatie/laravel-event-sourcing:^7.0

# Publish configurations
php artisan vendor:publish --provider="Thunk\Verbs\VerbsServiceProvider"
php artisan vendor:publish --provider="Spatie\EventSourcing\EventSourcingServiceProvider"
```

**Step 2: Configure Event Store Strategy**
```php
// config/verbs.php
<?php

return [
    'event_store' => [
        'driver' => env('VERBS_EVENT_STORE_DRIVER', 'database'),
        'table' => 'verb_events',
        'connection' => env('VERBS_EVENT_STORE_CONNECTION', 'pgsql'),
    ],
    
    'snapshots' => [
        'enabled' => true,
        'table' => 'verb_snapshots',
        'every' => 100, // Take snapshot every 100 events
    ],
    
    'identifiers' => [
        'snowflake' => [
            'datacenter_id' => env('VERBS_DATACENTER_ID', 1),
            'worker_id' => env('VERBS_WORKER_ID', 1),
        ],
    ],
];
```

**Step 3: Snowflake ID Configuration**
```bash
# Install Snowflake ID package
composer require glhd/bits:^0.6

# Publish configuration
php artisan vendor:publish --provider="Glhd\Bits\BitsServiceProvider"
```

```php
// config/bits.php
<?php

return [
    'snowflake' => [
        'datacenter_id' => env('BITS_DATACENTER_ID', 1),
        'worker_id' => env('BITS_WORKER_ID', 1),
        'epoch' => env('BITS_EPOCH', '2024-01-01 00:00:00'),
    ],
];
```

### 3.3.2. State Management Installation

**Step 1: Install Spatie State Management**
```bash
# Model states for complex workflows
composer require spatie/laravel-model-states:^2.11

# Model status for simple flags
composer require spatie/laravel-model-status:^1.18

# Only Model Status needs migrations, Model States doesn't have migrations
php artisan vendor:publish --provider="Spatie\ModelStatus\ModelStatusServiceProvider" --tag="migrations"

# If you need the config file for Model States (optional)
# php artisan vendor:publish --provider="Spatie\ModelStates\ModelStatesServiceProvider" --tag="model-states-config"
```

**Step 2: Configure State Management**
```php
// config/model-states.php
<?php

return [
    'state_machines' => [
        // Will be populated with specific state machines
    ],
    
    'default_state_field' => 'state',
];
```

### 3.3.3. Single Table Inheritance Setup

**Step 1: Install Parental Package**
```bash
composer require tightenco/parental:^1.4
```

**Step 2: Configure Hierarchical Data**
```bash
# For organization hierarchies
composer require staudenmeir/laravel-adjacency-list:^1.25
```

### 3.3.4. Data Transfer Objects

**Step 1: Install Spatie Data**
```bash
composer require spatie/laravel-data:^4.15

# Publish configuration
php artisan vendor:publish --provider="Spatie\LaravelData\LaravelDataServiceProvider"
```

**Step 2: Configure Data Objects**
```php
// config/data.php
<?php

return [
    'date_format' => 'Y-m-d H:i:s',
    'max_transformation_depth' => 512,
    'throw_when_max_transformation_depth_reached' => true,
];
```

## 3.4. Phase 3: Enhanced User & Organization Models (Week 2-3)

### 3.4.1. Enhanced User Model with STI

**Step 1: Create Base User Model**
```php
// app/Models/User.php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tightenco\Parental\HasParent;
use Spatie\Permission\Traits\HasRoles;
use Spatie\ModelStates\HasStates;

abstract class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasStates;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'type' => UserType::class,
        'status' => UserStatus::class,
    ];

    protected $states = [
        'status' => UserStatus::class,
    ];

    public function getChildType(): string
    {
        return $this->type->value;
    }
}
```

**Step 2: Create User Type Enum**
```php
// app/Enums/UserType.php
<?php

declare(strict_types=1);

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case REGULAR = 'regular';
    case GUEST = 'guest';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::REGULAR => 'Regular User',
            self::GUEST => 'Guest User',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ADMIN => 'red',
            self::REGULAR => 'blue',
            self::GUEST => 'gray',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::ADMIN => 'Full system access with administrative privileges',
            self::REGULAR => 'Standard user with limited access',
            self::GUEST => 'Temporary access with minimal privileges',
        };
    }
}
```

**Step 3: Create User Status Enum**
```php
// app/Enums/UserStatus.php
<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class UserStatus extends State
{
    abstract public function color(): string;
    abstract public function label(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Active::class)
            ->allowTransition(Active::class, Suspended::class)
            ->allowTransition(Suspended::class, Active::class)
            ->allowTransition([Active::class, Suspended::class], Banned::class)
            ->allowTransition(Banned::class, Active::class);
    }
}

class Active extends UserStatus
{
    public function color(): string { return 'green'; }
    public function label(): string { return 'Active'; }
}

class Suspended extends UserStatus  
{
    public function color(): string { return 'yellow'; }
    public function label(): string { return 'Suspended'; }
}

class Banned extends UserStatus
{
    public function color(): string { return 'red'; }
    public function label(): string { return 'Banned'; }
}
```

**Step 4: Create STI User Classes**
```php
// app/Models/AdminUser.php
<?php

declare(strict_types=1);

namespace App\Models;

use Tightenco\Parental\HasChildren;

class AdminUser extends User
{
    use HasChildren;

    protected $childTypes = [
        'admin' => AdminUser::class,
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->type = UserType::ADMIN;
        });
    }

    public function canAccessFilament(): bool
    {
        return true;
    }
}

// app/Models/RegularUser.php  
<?php

declare(strict_types=1);

namespace App\Models;

use Tightenco\Parental\HasParent;

class RegularUser extends User
{
    use HasParent;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->type = UserType::REGULAR;
        });
    }
}

// app/Models/GuestUser.php
<?php

declare(strict_types=1);

namespace App\Models;

use Tightenco\Parental\HasParent;

class GuestUser extends User  
{
    use HasParent;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->type = UserType::GUEST;
        });
    }
}
```

### 3.4.2. Self-Referential Organization Model

**Step 1: Install Additional Dependencies**
```bash
# For adjacency list functionality
composer require staudenmeir/laravel-adjacency-list:^1.25
```

**Step 2: Create Organization Type Enum**
```php
// app/Enums/OrganizationType.php
<?php

declare(strict_types=1);

namespace App\Enums;

enum OrganizationType: string
{
    case TENANT = 'tenant';
    case DIVISION = 'division';
    case DEPARTMENT = 'department';
    case TEAM = 'team';
    case PROJECT = 'project';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::TENANT => 'Tenant',
            self::DIVISION => 'Division',
            self::DEPARTMENT => 'Department',
            self::TEAM => 'Team',
            self::PROJECT => 'Project',
            self::OTHER => 'Other',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::TENANT => 'purple',
            self::DIVISION => 'blue',
            self::DEPARTMENT => 'green',
            self::TEAM => 'yellow',
            self::PROJECT => 'orange',
            self::OTHER => 'gray',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::TENANT => 'building-office',
            self::DIVISION => 'building-office-2',
            self::DEPARTMENT => 'users',
            self::TEAM => 'user-group',
            self::PROJECT => 'folder',
            self::OTHER => 'question-mark-circle',
        };
    }
}
```

**Step 3: Create Organization Model**
```php
// app/Models/Organization.php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Tightenco\Parental\HasChildren;
use Spatie\ModelStates\HasStates;
use App\Enums\OrganizationType;
use App\Enums\OrganizationStatus;

class Organization extends Model
{
    use HasFactory, SoftDeletes, HasRecursiveRelationships, HasChildren, HasStates;

    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
        'parent_id',
        'settings',
        'metadata',
    ];

    protected $casts = [
        'type' => OrganizationType::class,
        'status' => OrganizationStatus::class,
        'settings' => 'array',
        'metadata' => 'array',
    ];

    protected $childTypes = [
        'tenant' => TenantOrganization::class,
        'division' => DivisionOrganization::class,
        'department' => DepartmentOrganization::class,
        'team' => TeamOrganization::class,
        'project' => ProjectOrganization::class,
        'other' => OtherOrganization::class,
    ];

    protected $states = [
        'status' => OrganizationStatus::class,
    ];

    public function getChildType(): string
    {
        return $this->type->value;
    }

    // Hierarchical relationships
    public function parent()
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    // User relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_user')
                    ->withPivot(['role', 'joined_at', 'is_primary'])
                    ->withTimestamps();
    }

    public function primaryUsers()
    {
        return $this->users()->wherePivot('is_primary', true);
    }

    // Scope methods
    public function scopeOfType($query, OrganizationType $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->whereState('status', OrganizationStatus\Active::class);
    }
}
```

## 3.5. Phase 4: FilamentPHP Admin Interface (Week 3-4)

### 3.5.1. Core Filament Installation

**Step 1: Install Filament Core**
```bash
# Core Filament package
composer require filament/filament:^3.2

# Install Filament
php artisan filament:install --panels

# Create admin user
php artisan make:filament-user
```

**Step 2: Configure SPA Mode**
```php
// app/Providers/Filament/AdminPanelProvider.php
<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->spa() // Enable SPA mode
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
```

### 3.5.2. Essential Filament Plugins Installation

**Step 1: Media and Content Management**
```bash
# Media curator for file management
composer require awcodes/filament-curator:^3.7

# Rich text editor
composer require awcodes/filament-tiptap-editor:^3.5

# Spatie media library integration
composer require filament/spatie-laravel-media-library-plugin:^3.3

# Publish configurations
php artisan vendor:publish --provider="Awcodes\Curator\CuratorServiceProvider"
php artisan vendor:publish --provider="Awcodes\FilamentTiptapEditor\FilamentTiptapEditorServiceProvider"
```

**Step 2: Authorization and Security**
```bash
# Filament Shield for role-based permissions
composer require bezhansalleh/filament-shield:^3.3

# Install and configure Shield
php artisan vendor:publish --tag="filament-shield-config"
php artisan shield:install
```

**Step 3: Monitoring and Management**
```bash
# Spotlight search
composer require pxlrbt/filament-spotlight:^1.3

# Activity logging
composer require rmsramos/activitylog:^1.0

# Health monitoring
composer require shuvroroy/filament-spatie-laravel-health:^2.3

# Backup management
composer require shuvroroy/filament-spatie-laravel-backup:^2.2
```

### 3.5.3. Livewire Flux Integration

**Step 1: Install Flux Pro**
```bash
# Add Flux Pro repository (requires license)
composer config repositories.flux-pro composer https://composer.fluxui.dev
composer require livewire/flux-pro:^2.1

# Publish Flux assets
php artisan flux:publish
```

**Step 2: Configure Flux with Filament**
```php
// app/Providers/AppServiceProvider.php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Flux\Flux;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Flux::theme('custom-admin')
            ->primary('amber')
            ->accent('blue')
            ->supportsDarkMode();
    }
}
```

## 3.6. Phase 5: Frontend Enhancement (Week 4-5)

### 3.6.1. Alpine.js Ecosystem Installation

**Step 1: Install All Alpine.js Plugins**
```bash
# Core Alpine.js plugins
npm install @alpinejs/anchor@^3.14.9
npm install @alpinejs/collapse@^3.14.9  
npm install @alpinejs/focus@^3.14.9
npm install @alpinejs/intersect@^3.14.9
npm install @alpinejs/mask@^3.14.9
npm install @alpinejs/morph@^3.14.9
npm install @alpinejs/persist@^3.14.9
npm install @alpinejs/resize@^3.14.9
npm install @alpinejs/sort@^3.14.9

# Extended Alpine.js functionality
npm install @fylgja/alpinejs-dialog@^2.1.1
npm install @imacrayon/alpine-ajax@^0.12.2
```

**Step 2: Configure Alpine.js**
```javascript
// resources/js/app.js
import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Alpine from 'alpinejs';

// Import Alpine.js plugins
import anchor from '@alpinejs/anchor';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import intersect from '@alpinejs/intersect';
import mask from '@alpinejs/mask';
import morph from '@alpinejs/morph';
import persist from '@alpinejs/persist';
import resize from '@alpinejs/resize';
import sort from '@alpinejs/sort';
import ajax from '@imacrayon/alpine-ajax';

// Register Alpine.js plugins
Alpine.plugin(anchor);
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(intersect);
Alpine.plugin(mask);
Alpine.plugin(morph);
Alpine.plugin(persist);
Alpine.plugin(resize);
Alpine.plugin(sort);
Alpine.plugin(ajax);

// Start Alpine.js and Livewire
Alpine.start();
Livewire.start();
```

### 3.6.2. Vue.js and Inertia.js Setup

**Step 1: Install Vue.js Ecosystem**
```bash
# Core Vue.js packages
npm install @inertiajs/vue3@^2.0.0
npm install @vitejs/plugin-vue@^5.2.1
npm install @vueuse/core@^13.3.0
npm install vue@^3.5.13

# Development tools
npm install vue-tsc@^2.2.4
npm install @vue/eslint-config-typescript@^14.3.0
```

**Step 2: Configure Vite for Vue.js**
```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
```

## 3.7. Configuration Validation & Testing

### 3.7.1. Post-Installation Verification

**Step 1: Run Comprehensive Tests**
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run migrations
php artisan migrate:fresh --seed

# Test suite
composer test:all
npm run test:all
```

**Step 2: Verify Package Integration**
```bash
# Check Filament installation
php artisan filament:check

# Verify event sourcing setup
php artisan verbs:snapshot:all

# Test Alpine.js compilation
npm run build
```

### 3.7.2. Performance Baseline

**Step 1: Establish Metrics**
```bash
# Install performance monitoring
composer require laravel/pulse:^1.4

# Configure Pulse
php artisan vendor:publish --provider="Laravel\Pulse\PulseServiceProvider"
php artisan pulse:install

# Start performance monitoring
php artisan pulse:work
```

**Step 2: Frontend Performance**
```bash
# Bundle analysis
npm run build
npx vite-bundle-analyzer

# Performance auditing
npm install -D lighthouse
npx lighthouse http://localhost:8000 --view
```

## 3.8. Conclusion

This implementation plan provides a structured approach to transforming the basic Laravel starter kit into a comprehensive enterprise-grade application. The phased approach ensures:

1. **Minimal Disruption**: Each phase builds on the previous one
2. **Testing at Each Stage**: Comprehensive validation prevents regression
3. **Performance Monitoring**: Early establishment of performance baselines
4. **Risk Mitigation**: Incremental changes reduce complexity

**Total Implementation Time**: 5 weeks with a dedicated developer
**Confidence Level**: 90% with proper testing and validation at each phase

The key to success is **not rushing the foundation phases** - event sourcing and STI implementations must be solid before building business features on top.
~~~
