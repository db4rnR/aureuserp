# Implementation Recommendations

**Version:** 1.0.0
**Date:** 2025-06-05
**Author:** Junie
**Status:** Initial Draft

---

## 1. Introduction

This document provides recommendations for implementing the architectural patterns and packages identified in the research and development materials. The recommendations focus on:

1. Installing and configuring all required packages
   - Prioritizing `hirethunk/verbs` for event sourcing, with `spatie/laravel-event-sourcing` for extended capabilities
   - Prioritizing `spatie/laravel-model-states` and `spatie/laravel-model-status` for state management
   - Using a single event-store for complete consistency and audit trail
2. Enhancing the User model with Single Table Inheritance (STI)
3. Creating an Organisation model with STI and self-referential capabilities
4. Ensuring all types and statuses are backed by enhanced PHP-native ENUMs with labels and colors
5. Outlining initial business capabilities for future implementation

## 2. Package Installation and Configuration

### 2.1. Core Packages Installation

First, install the core Laravel packages:

```bash
composer require laravel/framework:^12.0 laravel/tinker:^2.10 laravel/ui:^4.5
```

### 2.2. Package Installation by Category

#### 2.2.1. Admin Panel and UI

```bash
# Install FilamentPHP core
composer require filament/filament:^3.3

# Install FilamentPHP plugins
composer require filament/spatie-laravel-media-library-plugin:^3.3
composer require filament/spatie-laravel-settings-plugin:^3.3
composer require filament/spatie-laravel-tags-plugin:^3.3
composer require filament/spatie-laravel-translatable-plugin:^3.3
composer require awcodes/filament-tiptap-editor:^3.5
composer require awcodes/filament-curator:^3.7
composer require bezhansalleh/filament-shield:^3.3
composer require dotswan/filament-laravel-pulse:^1.1
composer require mvenghaus/filament-plugin-schedule-monitor:^3.0
composer require shuvroroy/filament-spatie-laravel-backup:^2.2
composer require shuvroroy/filament-spatie-laravel-health:^2.3
composer require rmsramos/activitylog:^1.0
composer require saade/filament-adjacency-list:^3.2
composer require pxlrbt/filament-spotlight:^1.3
composer require z3d0x/filament-fabricator:^2.5
```

#### 2.2.2. Event Sourcing and State Management

```bash
# Install Event Sourcing packages (prioritizing hirethunk/verbs)
composer require hirethunk/verbs:^0.7  # Primary event sourcing library
composer require spatie/laravel-event-sourcing:^7.0  # Supporting package to extend capabilities

# Install State Management packages (both prioritized)
composer require spatie/laravel-model-states:^2.11  # Primary for complex state workflows
composer require spatie/laravel-model-status:^1.18  # Primary for simple status tracking
```

#### 2.2.3. Frontend and UI

```bash
# Install Livewire and related packages
composer require livewire/livewire:^3.0
composer require livewire/volt:^1.7
composer require livewire/flux:^2.1
composer require livewire/flux-pro:^2.1

# Install Alpine.js plugins via NPM
npm install @alpinejs/anchor @alpinejs/collapse @alpinejs/focus @alpinejs/intersect @alpinejs/mask @alpinejs/morph @alpinejs/persist @alpinejs/resize @alpinejs/sort @fylgja/alpinejs-dialog @imacrayon/alpine-ajax
```

#### 2.2.4. Performance Optimization

```bash
# Install performance packages
composer require laravel/octane:^2.0
composer require laravel/scout:^10.15
composer require typesense/typesense-php:^5.1
composer require runtime/frankenphp-symfony:^0.2
```

#### 2.2.5. Data Management and Structure

```bash
# Install data management packages
composer require spatie/laravel-data:^4.15
composer require spatie/laravel-query-builder:^6.3
composer require staudenmeir/laravel-adjacency-list:^1.25
composer require glhd/bits:^0.6
```

#### 2.2.6. Authentication and Authorization

```bash
# Install auth packages
composer require devdojo/auth:^1.1
composer require spatie/laravel-permission:^6.19
composer require lab404/laravel-impersonate:^1.7
```

#### 2.2.7. Monitoring and Debugging

```bash
# Install monitoring packages
composer require laravel/pulse:^1.4
composer require laravel/telescope:^5.8
composer require spatie/laravel-schedule-monitor:^3.10
composer require spatie/laravel-health:^1.34
```

### 2.3. Package Configuration

After installing the packages, publish their configuration files and run migrations:

```bash
# Publish configurations
php artisan vendor:publish --tag=filament-config
php artisan vendor:publish --tag=filament-shield-config
php artisan vendor:publish --tag=laravel-event-sourcing-config
php artisan vendor:publish --tag=model-states-config
php artisan vendor:publish --tag=laravel-permission-config
php artisan vendor:publish --tag=telescope-config
php artisan vendor:publish --tag=pulse-config

# Run migrations
php artisan migrate
```

#### 2.3.1. Event Sourcing Configuration

Configure hirethunk/verbs and spatie/laravel-event-sourcing to use a single event-store:

```php
// config/event-sourcing.php
return [
    // ... other configuration options

    'event_store' => [
        'table' => 'stored_events',
        'use_snapshot_store' => true,
        'snapshot_table' => 'snapshots',
    ],

    // Configure to work with hirethunk/verbs
    'shared_event_store' => true,
    'hirethunk_verbs_integration' => [
        'enabled' => true,
        'map_event_classes' => true,
    ],
];

// config/verbs.php
return [
    // ... other configuration options

    'event_store' => [
        'use_spatie_event_store' => true,
        'table' => 'stored_events',
    ],

    'snowflake' => [
        'node_id' => env('VERBS_NODE_ID', 1),
        'epoch' => env('VERBS_EPOCH', 1577836800000), // 2020-01-01 00:00:00
    ],
];
```

#### 2.3.2. State Management Configuration

Configure the state management packages to work with PHP 8.4 Native Enums:

```php
// config/model-states.php
return [
    // ... other configuration options

    'enum_integration' => [
        'enabled' => true,
        'default_enum_namespace' => 'App\\Enums',
    ],
];
```

### 2.4. Frontend Configuration

#### 2.4.1. Alpine.js Configuration

Register all Alpine.js plugins in your JavaScript entry point (e.g., `resources/js/app.js`):

```javascript
import Alpine from 'alpinejs';
import Anchor from '@alpinejs/anchor';
import Collapse from '@alpinejs/collapse';
import Focus from '@alpinejs/focus';
import Intersect from '@alpinejs/intersect';
import Mask from '@alpinejs/mask';
import Morph from '@alpinejs/morph';
import Persist from '@alpinejs/persist';
import Resize from '@alpinejs/resize';
import Sort from '@alpinejs/sort';
import Dialog from '@fylgja/alpinejs-dialog';
import Ajax from '@imacrayon/alpine-ajax';

// Register Alpine.js plugins
Alpine.plugin(Anchor);
Alpine.plugin(Collapse);
Alpine.plugin(Focus);
Alpine.plugin(Intersect);
Alpine.plugin(Mask);
Alpine.plugin(Morph);
Alpine.plugin(Persist);
Alpine.plugin(Resize);
Alpine.plugin(Sort);
Alpine.plugin(Dialog);
Alpine.plugin(Ajax);

// Start Alpine.js
window.Alpine = Alpine;
Alpine.start();
```

#### 2.4.2. Filament SPA Mode Configuration

Configure Filament to run in SPA mode by updating your `config/filament.php` file:

```php
'spa' => true,
```

This enables a Single Page Application experience for the admin panel, with smoother transitions between pages and improved performance.

#### 2.4.3. Livewire/Flux Integration with Filament

Create a service provider to integrate Livewire/Flux components with Filament:

```php
<?php

namespace App\Providers;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class FluxServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register Flux assets
        FilamentAsset::register([
            // Register Flux CSS
            Css::make('flux-styles', 'vendor/flux/dist/flux.css'),

            // Register Flux JS
            Js::make('flux-scripts', 'vendor/flux/dist/flux.js'),
        ]);

        // Register Flux Pro assets if available
        if (class_exists(\Livewire\FluxPro\FluxProServiceProvider::class)) {
            FilamentAsset::register([
                Css::make('flux-pro-styles', 'vendor/flux-pro/dist/flux-pro.css'),
                Js::make('flux-pro-scripts', 'vendor/flux-pro/dist/flux-pro.js'),
            ]);
        }
    }
}
```

Register this service provider in your `config/app.php` file:

```php
'providers' => [
    // Other service providers...
    App\Providers\FluxServiceProvider::class,
],
```

#### 2.4.4. Maximizing Livewire/Volt SFC for Non-Admin UI

For non-admin UI components, use Livewire/Volt Single File Components (SFC) to maximize developer productivity and code organization:

1. Create a Volt component directory structure:

```bash
mkdir -p resources/views/livewire/components
```

2. Create a sample Volt component:

```php
<?php

// resources/views/livewire/components/user-profile.blade.php

use App\Models\User;
use function Livewire\Volt\state;
use function Livewire\Volt\computed;

state(['user' => fn() => auth()->user()]);

computed('fullName', fn() => $this->user->name);

$updateProfile = function() {
    $this->validate([
        'user.name' => 'required|string|max:255',
        'user.email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
    ]);

    $this->user->save();

    $this->dispatch('profile-updated');
};

?>

<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">{{ $fullName }}'s Profile</h2>

    <form wire:submit="updateProfile">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" wire:model="user.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('user.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" wire:model="user.email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            @error('user.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
            Update Profile
        </button>
    </form>
</div>
```

3. Register Volt components in your `AppServiceProvider`:

```php
use Livewire\Volt\Volt;

public function boot(): void
{
    Volt::mount([
        'resources/views/livewire/components',
    ]);
}
```

4. Use the Volt component in your Blade templates:

```blade
<livewire:user-profile />
```

## 3. User Model Enhancement with STI

### 3.1. Database Schema

Create a migration to add the necessary columns to the users table:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add type column for STI
            $table->string('type')->default('App\\Models\\RegularUser');

            // Add user tracking columns
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by_id')->nullable()->constrained('users')->nullOnDelete();

            // Add status column
            $table->string('status')->default('active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('created_by_id');
            $table->dropColumn('updated_by_id');
            $table->dropColumn('deleted_by_id');
            $table->dropColumn('status');
        });
    }
};
```

### 3.2. User Status Enum

Create an enhanced PHP 8.4 Native Enum for user statuses:

```php
<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Arrayable;

enum UserStatus: string implements HasColor, HasLabel, Arrayable
{
    case Invited = 'invited';
    case PendingActivation = 'pending_activation';
    case Active = 'active';
    case Suspended = 'suspended';
    case Deactivated = 'deactivated';

    /**
     * Get the human-readable label for the enum value.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Invited => 'Invited',
            self::PendingActivation => 'Pending Activation',
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Deactivated => 'Deactivated',
        };
    }

    /**
     * Get the color for the enum value.
     * Uses Filament color system: primary, secondary, success, warning, danger, info, gray
     */
    public function getColor(): string
    {
        return match($this) {
            self::Invited => 'gray',
            self::PendingActivation => 'info',
            self::Active => 'success',
            self::Suspended => 'warning',
            self::Deactivated => 'danger',
        };
    }

    /**
     * Get the description for the enum value.
     */
    public function getDescription(): string
    {
        return match($this) {
            self::Invited => 'User has been invited but has not yet registered',
            self::PendingActivation => 'User has registered but has not yet activated their account',
            self::Active => 'User has an active account',
            self::Suspended => 'User account has been temporarily suspended',
            self::Deactivated => 'User account has been deactivated',
        };
    }

    /**
     * Check if the status allows login.
     */
    public function canLogin(): bool
    {
        return match($this) {
            self::Active => true,
            default => false,
        };
    }

    /**
     * Get the next possible statuses from this status.
     * 
     * @return array<UserStatus>
     */
    public function getNextPossibleStatuses(): array
    {
        return match($this) {
            self::Invited => [self::PendingActivation, self::Deactivated],
            self::PendingActivation => [self::Active, self::Deactivated],
            self::Active => [self::Suspended, self::Deactivated],
            self::Suspended => [self::Active, self::Deactivated],
            self::Deactivated => [self::Active],
        };
    }

    /**
     * Get all enum values as an array for select inputs.
     */
    public static function getSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->getLabel()])
            ->toArray();
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->getLabel(),
            'color' => $this->getColor(),
            'description' => $this->getDescription(),
        ];
    }
}
```

### 3.3. Base User Model

Update the base User model:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserStatus;
use App\Models\Traits\HasAdditionalFeatures;
use App\Models\Traits\HasUserTracking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tightenco\Parental\HasChildren;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    use HasChildren;
    use HasUserTracking;
    use HasAdditionalFeatures;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
        ];
    }
}
```

### 3.4. Child User Models

Create the child user models:

```php
<?php

declare(strict_types=1);

namespace App\Models;

class AdminUser extends User
{
    // Admin-specific methods and properties
}
```

```php
<?php

declare(strict_types=1);

namespace App\Models;

class RegularUser extends User
{
    // Regular user-specific methods and properties
}
```

```php
<?php

declare(strict_types=1);

namespace App\Models;

class GuestUser extends User
{
    // Guest-specific methods and properties
}
```

## 4. Organisation Model with STI and Self-Referential Capabilities

### 4.1. Database Schema

Create a migration for the organisations table:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('App\\Models\\Tenant');
            $table->string('status')->default('active');
            $table->text('description')->nullable();

            // Self-referential relationship
            $table->foreignId('parent_id')->nullable()->constrained('organisations')->nullOnDelete();

            // Path for efficient hierarchical queries
            $table->string('path')->nullable();

            // User tracking
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
```

### 4.2. Organisation Status Enum

Create an enum for organisation statuses:

```php
<?php

declare(strict_types=1);

namespace App\Enums;

enum OrganisationStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Suspended = 'suspended';
    case Archived = 'archived';

    /**
     * Get the human-readable label for the enum value.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Archived => 'Archived',
        };
    }

    /**
     * Get the color for the enum value.
     * Uses Filament color system: primary, secondary, success, warning, danger, info, gray
     */
    public function getColor(): string
    {
        return match($this) {
            self::Pending => 'info',
            self::Active => 'success',
            self::Suspended => 'warning',
            self::Archived => 'gray',
        };
    }

    /**
     * Get all enum values as an array for select inputs.
     */
    public static function getSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->getLabel()])
            ->toArray();
    }
}
```

### 4.3. Organisation Type Enum

Create an enum for organisation types:

```php
<?php

declare(strict_types=1);

namespace App\Enums;

enum OrganisationType: string
{
    case Tenant = 'tenant';
    case Division = 'division';
    case Department = 'department';
    case Team = 'team';
    case Project = 'project';
    case Other = 'other';

    /**
     * Get the human-readable label for the enum value.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::Tenant => 'Tenant',
            self::Division => 'Division',
            self::Department => 'Department',
            self::Team => 'Team',
            self::Project => 'Project',
            self::Other => 'Other',
        };
    }

    /**
     * Get the color for the enum value.
     * Uses Filament color system: primary, secondary, success, warning, danger, info, gray
     */
    public function getColor(): string
    {
        return match($this) {
            self::Tenant => 'primary',
            self::Division => 'secondary',
            self::Department => 'success',
            self::Team => 'info',
            self::Project => 'warning',
            self::Other => 'gray',
        };
    }

    /**
     * Get the model class for this type.
     */
    public function getModelClass(): string
    {
        return match($this) {
            self::Tenant => 'App\\Models\\Tenant',
            self::Division => 'App\\Models\\Division',
            self::Department => 'App\\Models\\Department',
            self::Team => 'App\\Models\\Team',
            self::Project => 'App\\Models\\Project',
            self::Other => 'App\\Models\\OtherOrganisation',
        };
    }

    /**
     * Get all enum values as an array for select inputs.
     */
    public static function getSelectOptions(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type) => [$type->value => $type->getLabel()])
            ->toArray();
    }
}
```

### 4.4. Base Organisation Model

Create the base Organisation model:

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrganisationStatus;
use App\Enums\OrganisationType;
use App\Models\Traits\HasAdditionalFeatures;
use App\Models\Traits\HasUserTracking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Tightenco\Parental\HasChildren;

class Organisation extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasChildren;
    use HasUserTracking;
    use HasAdditionalFeatures;
    use HasRecursiveRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'type',
        'status',
        'description',
        'parent_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OrganisationStatus::class,
        ];
    }

    /**
     * Get the parent organisation.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Organisation::class, 'parent_id');
    }

    /**
     * Get the child organisations.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Organisation::class, 'parent_id');
    }

    /**
     * Get the parent key for the recursive relationship.
     */
    public function getParentKeyName(): string
    {
        return 'parent_id';
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Organisation $organisation) {
            // Generate path for efficient hierarchical queries
            if ($organisation->parent_id) {
                $parent = Organisation::find($organisation->parent_id);
                $organisation->path = $parent->path ? $parent->path . '.' . $parent->id : $parent->id;
            }
        });

        static::updating(function (Organisation $organisation) {
            // Update path if parent_id changes
            if ($organisation->isDirty('parent_id')) {
                if ($organisation->parent_id) {
                    $parent = Organisation::find($organisation->parent_id);
                    $organisation->path = $parent->path ? $parent->path . '.' . $parent->id : $parent->id;
                } else {
                    $organisation->path = null;
                }

                // Update paths of all descendants
                $descendants = $organisation->descendants()->get();
                foreach ($descendants as $descendant) {
                    $ancestor = $descendant->parent;
                    $descendant->path = $ancestor->path ? $ancestor->path . '.' . $ancestor->id : $ancestor->id;
                    $descendant->save();
                }
            }
        });
    }
}
```

### 4.5. Child Organisation Models

Create the child organisation models:

```php
<?php

declare(strict_types=1);

namespace App\Models;

class Tenant extends Organisation
{
    // Tenant-specific methods and properties
}
```

```php
<?php

declare(strict_types=1);

namespace App\Models;

class Division extends Organisation
{
    // Division-specific methods and properties
}
```

```php
<?php

declare(strict_types=1);

namespace App\Models;

class Department extends Organisation
{
    // Department-specific methods and properties
}
```

```php
<?php

declare(strict_types=1);

namespace App\Models;

class Team extends Organisation
{
    // Team-specific methods and properties
}
```

```php
<?php

declare(strict_types=1);

namespace App\Models;

class Project extends Organisation
{
    // Project-specific methods and properties
}
```

```php
<?php

declare(strict_types=1);

namespace App\Models;

class OtherOrganisation extends Organisation
{
    // Other organisation-specific methods and properties
}
```

## 5. Implementation of Traits

### 5.1. HasUserTracking Trait

Create the HasUserTracking trait:

```php
<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait HasUserTracking
{
    /**
     * Boot the trait.
     */
    public static function bootHasUserTracking(): void
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by_id = Auth::id();
                $model->updated_by_id = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by_id = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting() && Auth::check()) {
                $model->deleted_by_id = Auth::id();
                $model->save();
            }
        });

        static::restoring(function ($model) {
            if (Auth::check()) {
                $model->updated_by_id = Auth::id();
                $model->deleted_by_id = null;
            }
        });
    }

    /**
     * Get the user who created the model.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Get the user who last updated the model.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    /**
     * Get the user who deleted the model.
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by_id');
    }

    /**
     * Scope a query to only include models created by a specific user.
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by_id', $userId);
    }

    /**
     * Scope a query to only include models updated by a specific user.
     */
    public function scopeUpdatedBy($query, $userId)
    {
        return $query->where('updated_by_id', $userId);
    }

    /**
     * Scope a query to only include models deleted by a specific user.
     */
    public function scopeDeletedBy($query, $userId)
    {
        return $query->where('deleted_by_id', $userId);
    }
}
```

### 5.2. HasAdditionalFeatures Trait

Create the HasAdditionalFeatures trait:

```php
<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

trait HasAdditionalFeatures
{
    use HasSlug;
    use HasTags;
    use HasTranslations;

    /**
     * Boot the trait.
     */
    public static function bootHasAdditionalFeatures(): void
    {
        static::creating(function ($model) {
            if (empty($model->ulid) && method_exists($model, 'generateUlid')) {
                $model->ulid = $model->generateUlid();
            }
        });
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Generate a ULID for the model.
     */
    public function generateUlid(): string
    {
        return (string) \Illuminate\Support\Str::ulid();
    }

    /**
     * Scope a query to only include published models.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /**
     * Scope a query to only include draft models.
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('published', false);
    }

    /**
     * Get the display name for the model.
     */
    public function getDisplayName(): string
    {
        return $this->name ?? $this->title ?? $this->slug ?? $this->id;
    }
}
```

## 6. Filament Admin Panel Integration

### 6.1. User Resource

Create a Filament resource for the User model:

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\UserStatus;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'App\\Models\\AdminUser' => 'Admin',
                        'App\\Models\\RegularUser' => 'Regular',
                        'App\\Models\\GuestUser' => 'Guest',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(UserStatus::getSelectOptions())
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => bcrypt($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (UserStatus $state): string => $state->getColor()),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'App\\Models\\AdminUser' => 'Admin',
                        'App\\Models\\RegularUser' => 'Regular',
                        'App\\Models\\GuestUser' => 'Guest',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options(UserStatus::getSelectOptions()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
```

### 6.2. Organisation Resource

Create a Filament resource for the Organisation model:

```php
<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\OrganisationStatus;
use App\Enums\OrganisationType;
use App\Filament\Resources\OrganisationResource\Pages;
use App\Models\Organisation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganisationResource extends Resource
{
    protected static ?string $model = Organisation::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options(OrganisationType::getSelectOptions())
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(OrganisationStatus::getSelectOptions())
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('parent_id')
                    ->label('Parent Organisation')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->color(fn (string $state): string => OrganisationType::tryFrom($state)?->getColor() ?? 'gray'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (OrganisationStatus $state): string => $state->getColor()),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(OrganisationType::getSelectOptions()),
                Tables\Filters\SelectFilter::make('status')
                    ->options(OrganisationStatus::getSelectOptions()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganisations::route('/'),
            'create' => Pages\CreateOrganisation::route('/create'),
            'edit' => Pages\EditOrganisation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
```

## 7. Implementation Phases

To ensure a smooth implementation, follow these phases:

### 7.1. Phase 1: Foundation Setup

1. Install Laravel 12.x with PHP 8.4
2. Configure the database connection
3. Install and configure core packages
4. Set up the development environment

### 7.2. Phase 2: User Model Enhancement

1. Create the User model migration
2. Implement the UserStatus enum
3. Update the base User model
4. Create the child User models
5. Implement the HasUserTracking trait
6. Implement the HasAdditionalFeatures trait

### 7.3. Phase 3: Organisation Model Implementation

1. Create the Organisation model migration
2. Implement the OrganisationStatus and OrganisationType enums
3. Create the base Organisation model
4. Create the child Organisation models
5. Configure the self-referential relationship

### 7.4. Phase 4: Admin Panel Integration

1. Install and configure FilamentPHP
2. Create the User resource
3. Create the Organisation resource
4. Configure permissions and roles

### 7.5. Phase 5: Testing and Validation

1. Write tests for the User model
2. Write tests for the Organisation model
3. Test the admin panel functionality
4. Validate the STI implementation

## 8. Potential Challenges and Solutions

### 8.1. PHP 8.4 Compatibility

**Challenge**: Some packages may not be fully compatible with PHP 8.4.

**Solution**: 
- Use the latest versions of all packages
- Fork and update packages that are not compatible
- Use polyfills where necessary

### 8.2. Laravel 12.x Compatibility

**Challenge**: Some packages may not be fully compatible with Laravel 12.x.

**Solution**:
- Use the latest versions of all packages
- Check for Laravel 12.x specific versions
- Be prepared to contribute fixes to package repositories

### 8.3. STI Implementation Challenges

**Challenge**: STI can lead to complex queries and potential performance issues.

**Solution**:
- Use eager loading to reduce query count
- Implement caching for frequently accessed data
- Consider using query scopes to simplify common queries

### 8.4. Self-Referential Relationship Challenges

**Challenge**: Self-referential relationships can be complex to query and maintain.

**Solution**:
- Use the `staudenmeir/laravel-adjacency-list` package for efficient querying
- Implement materialized paths for fast hierarchical queries
- Add validation to prevent circular references

## 9. Initial Business Capabilities

The following business capabilities are planned for future implementation. These capabilities will build upon the architectural foundation established in the previous sections.

### 9.1. Content Management System (CMS)

#### 9.1.1. Categories/Taxonomies

- **Self-referential, polymorphic structure**
- **Implementation approach**:
  - Use `staudenmeir/laravel-adjacency-list` for hierarchical relationships
  - Implement polymorphic relationships for attaching to various content types
  - Use materialized paths for efficient querying
  - Support for custom fields and metadata

#### 9.1.2. Long-form Posts (Blog)

- **With lifecycle management**
- **Implementation approach**:
  - Use `spatie/laravel-model-states` for post status workflow (Draft, Review, Published, Archived)
  - Implement versioning with event sourcing
  - Support for rich text editing with `awcodes/filament-tiptap-editor`
  - SEO optimization with metadata

#### 9.1.3. Newsletter

- **With subscription management**
- **Implementation approach**:
  - Implement subscription model with status tracking
  - Support for email templates
  - Scheduled sending with `spatie/laravel-schedule-monitor`
  - Analytics and tracking

#### 9.1.4. Forums

- **Implementation approach**:
  - Threaded discussions with hierarchical comments
  - User reputation system
  - Moderation tools
  - Search and filtering

### 9.2. Social Features

#### 9.2.1. Presence

- **Implementation approach**:
  - Real-time user presence tracking with `laravel/reverb`
  - Online status indicators
  - "Currently typing" indicators

#### 9.2.2. Short-form Posts

- **Implementation approach**:
  - Character-limited posts
  - Support for mentions, hashtags
  - Timeline aggregation

#### 9.2.3. Real-time Chat

- **Implementation approach**:
  - WebSocket-based chat with `laravel/reverb`
  - Message persistence
  - Read receipts
  - Typing indicators

#### 9.2.4. Comments and Reactions

- **Implementation approach**:
  - Polymorphic comments attachable to any model
  - Reaction system with customizable emoji
  - Threaded comments

#### 9.2.5. Mentions and Notifications

- **Implementation approach**:
  - @mention system with user lookup
  - Multi-channel notifications (in-app, email, push)
  - Notification preferences

#### 9.2.6. Follow/Followers

- **Implementation approach**:
  - Many-to-many relationships between users
  - Activity feeds based on followed users
  - Follow suggestions

#### 9.2.7. Chat Rooms

- **Implementation approach**:
  - Group chat functionality
  - Room permissions and moderation
  - File sharing
  - Video/audio integration options

### 9.3. Project Management

#### 9.3.1. Kanban Board

- **Implementation approach**:
  - Drag-and-drop interface with Alpine.js
  - Board, list, and card models
  - Custom fields and labels
  - Filtering and search

#### 9.3.2. Calendars

- **Implementation approach**:
  - Event scheduling and management
  - Recurring events
  - Calendar sharing and permissions
  - Integration with external calendars

#### 9.3.3. Tasks

- **With lifecycle management**
- **Implementation approach**:
  - Task status workflow using `spatie/laravel-model-states`
  - Due dates and reminders
  - Assignment and reassignment
  - Time tracking

### 9.4. Media Management

#### 9.4.1. Sharing

- **Implementation approach**:
  - Secure file sharing with permissions
  - Version control
  - Preview generation
  - Expiring links

#### 9.4.2. Avatars

- **For users and organisations**
- **Implementation approach**:
  - Image upload and cropping
  - Default avatars based on initials
  - Avatar caching and optimization
  - Integration with `spatie/laravel-media-library`

### 9.5. eCommerce

#### 9.5.1. Products

- **Implementation approach**:
  - Product catalog with variants
  - Inventory management
  - Pricing strategies
  - Product reviews and ratings

#### 9.5.2. Services

- **Implementation approach**:
  - Service catalog
  - Booking and scheduling
  - Service provider management
  - Availability calendar

#### 9.5.3. Carts

- **Implementation approach**:
  - Shopping cart functionality
  - Abandoned cart recovery
  - Guest carts with conversion
  - Discount application

#### 9.5.4. Orders

- **Implementation approach**:
  - Order processing workflow using `spatie/laravel-model-states`
  - Payment integration
  - Shipping and fulfillment
  - Order history and tracking

#### 9.5.5. Subscriptions

- **Implementation approach**:
  - Recurring billing
  - Subscription management
  - Plan changes and upgrades
  - Trial periods and grace periods

## 10. Conclusion

By following these implementation recommendations, you can successfully implement the architectural patterns and packages identified in the research and development materials. The result will be a robust, scalable application with enhanced User and Organisation models that leverage Single Table Inheritance and self-referential capabilities.

The prioritization of `hirethunk/verbs` and `spatie/laravel-event-sourcing` for event sourcing, along with `spatie/laravel-model-states` and `spatie/laravel-model-status` for state management, provides a solid foundation for implementing the initial business capabilities outlined in this document.

Remember to test thoroughly at each phase of implementation and be prepared to address compatibility issues as they arise. With careful planning and execution, you can create a powerful Laravel application that meets all the requirements specified in the research and development materials.
