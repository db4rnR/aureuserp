# Phase 1.7: Status Implementation for Models

**Version:** 1.0.1
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Updated
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Step 1: User Status Implementation](#step-1-user-status-implementation)
  - [Account Status](#account-status)
  - [Presence Status](#presence-status)
- [Step 2: Team Status Implementation](#step-2-team-status-implementation)
  - [Team State](#team-state)
  - [Team Activity Status](#team-activity-status)
- [Step 3: Post Status Implementation](#step-3-post-status-implementation)
- [Step 4: Todo Status Implementation](#step-4-todo-status-implementation)
- [Step 5: Status History Tracking](#step-5-status-history-tracking)
- [Step 6: Status Display in Filament](#step-6-status-display-in-filament)
</details>

## Overview

This document provides detailed instructions for implementing various status types for Users, Teams, Posts, and Todos in the Enhanced Laravel Application (ELA). It covers both state machines using `spatie/laravel-model-states` and status history tracking using `spatie/laravel-model-status`.

> **Reference:**
> - [Spatie Laravel Model States Documentation](https:/spatie.be/docs/laravel-model-states/v2/introduction)
> - [Spatie Laravel Model Status Documentation](https:/github.com/spatie/laravel-model-status)

## Prerequisites

Before starting, ensure you have:
- Completed the [Package Installation & Configuration](./.archive/100-030-package-installation.md)
- Installed `spatie/laravel-model-states` and `spatie/laravel-model-status`
- Published the migrations for both packages
- Run the migrations

## Step 1: User Status Implementation

### Account Status

1. Create the User State classes in `app/States/User/UserState.php`:

```php
<?php

namespace App\States\User;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class UserState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(PendingActivation::class)
            ->allowTransition(Invited::class, PendingActivation::class)
            ->allowTransition(PendingActivation::class, Active::class)
            ->allowTransition(Active::class, Suspended::class)
            ->allowTransition(Suspended::class, Active::class)
            ->allowTransition(Active::class, Deactivated::class)
            ->allowTransition(Suspended::class, Deactivated::class);
    }

    abstract public function getColor(): string;

    abstract public function getIcon(): ?string;

    abstract public function getLabel(): string;
}
```text

2. Create the concrete state classes:

```php
<?php

namespace App\States\User;

class Invited extends UserState
{
    public function getColor(): string
    {
        return 'gray';
    }

    public function getIcon(): ?string
    {
        return 'heroicon-o-envelope';
    }

    public function getLabel(): string
    {
        return 'Invited';
    }
}
```php
```php
<?php

namespace App\States\User;

class PendingActivation extends UserState
{
    public function getColor(): string
    {
        return 'info';
    }

    public function getIcon(): ?string
    {
        return 'heroicon-o-clock';
    }

    public function getLabel(): string
    {
        return 'Pending Activation';
    }
}
```text

```php
<?php

namespace App\States\User;

class Active extends UserState
{
    public function getColor(): string
    {
        return 'success';
    }

    public function getIcon(): ?string
    {
        return 'heroicon-o-check-circle';
    }

    public function getLabel(): string
    {
        return 'Active';
    }
}
```php
```php
<?php

namespace App\States\User;

class Suspended extends UserState
{
    public function getColor(): string
    {
        return 'warning';
    }

    public function getIcon(): ?string
    {
        return 'heroicon-o-exclamation-circle';
    }

    public function getLabel(): string
    {
        return 'Suspended';
    }
}
```text

```php
<?php

namespace App\States\User;

class Deactivated extends UserState
{
    public function getColor(): string
    {
        return 'danger';
    }

    public function getIcon(): ?string
    {
        return 'heroicon-o-x-circle';
    }

    public function getLabel(): string
    {
        return 'Deactivated';
    }
}
```php
3. Update the User model to use the state machine:

```php
<?php

namespace App\Models;

use App\States\User\UserState;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\ModelStates\HasStates;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasStates, HasRoles, Notifiable;

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => UserState::class,
    ];

    // Rest of the model...
}
```text

### Presence Status

1. Create a migration for adding the presence status column:

```bash
php artisan make:migration add_presence_status_to_users_table
```php
2. Update the migration file:

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
            $table->string('presence_status')->default('offline');
            $table->timestamp('last_activity_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['presence_status', 'last_activity_at']);
        });
    }
};
```text

3. Create a presence status enum:

```php
<?php

namespace App\Enums;

enum PresenceStatus: string
{
    case Online = 'online';
    case Away = 'away';
    case Busy = 'busy';
    case Offline = 'offline';

    public function getLabel(): string
    {
        return match($this) {
            self::Online => 'Online',
            self::Away => 'Away',
            self::Busy => 'Busy',
            self::Offline => 'Offline',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::Online => 'success',
            self::Away => 'warning',
            self::Busy => 'danger',
            self::Offline => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::Online => 'heroicon-o-check-circle',
            self::Away => 'heroicon-o-clock',
            self::Busy => 'heroicon-o-no-symbol',
            self::Offline => 'heroicon-o-minus-circle',
        };
    }
}
```php
4. Update the User model to use the presence status enum:

```php
<?php

namespace App\Models;

use App\Enums\PresenceStatus;
use App\States\User\UserState;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStatus\HasStatuses;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasStates, HasStatuses, Notifiable;

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => UserState::class,
        'presence_status' => PresenceStatus::class,
        'last_activity_at' => 'datetime',
    ];

    // Rest of the model...

    public function updatePresence(PresenceStatus $status): void
    {
        $this->presence_status = $status;
        $this->last_activity_at = now();
        $this->save();

        // Also record in the statuses table
        $this->setStatus('presence_' . $status->value);
    }
}
```text

5. Create a middleware to track user activity:

```php
<?php

namespace App\Http\Middleware;

use App\Enums\PresenceStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();

            // Only update if the last activity was more than 5 minutes ago
            if (!$user->last_activity_at || $user->last_activity_at->diffInMinutes(now()) > 5) {
                $user->last_activity_at = now();

                // If user is offline, set to online
                if ($user->presence_status === PresenceStatus::Offline) {
                    $user->presence_status = PresenceStatus::Online;
                }

                $user->save();
            }
        }

        return $response;
    }
}
```php
6. Register the middleware in `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // Other middleware...
        \App\Http\Middleware\TrackUserActivity::class,
    ],
];
```text

## Step 2: Team Status Implementation

### Team State

1. Create the Team State classes in `app/States/Team/TeamState.php`:

```php
<?php

namespace App\States\Team;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class TeamState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Forming::class)
            ->allowTransition(Forming::class, Active::class)
            ->allowTransition(Active::class, Archived::class)
            ->allowTransition(Archived::class, Active::class);
    }

    abstract public function getColor(): string;

    abstract public function getIcon(): ?string;

    abstract public function getLabel(): string;
}
```php
2. Create the concrete state classes:

```php
<?php

namespace App\States\Team;

class Forming extends TeamState
{
    public function getColor(): string
    {
        return 'info';
    }

    public function getIcon(): ?string
    {
        return 'heroicon-o-cog';
    }

    public function getLabel(): string
    {
        return 'Forming';
    }
}
```text

```php
<?php

namespace App\States\Team;

class Active extends TeamState
{
    public function getColor(): string
    {
        return 'success';
    }

    public function getIcon(): ?string
    {
        return 'heroicon-o-check-circle';
    }

    public function getLabel(): string
    {
        return 'Active';
    }
}
```php
```php
<?php

namespace App\States\Team;

class Archived extends TeamState
{
    public function getColor(): string
    {
        return 'gray';
    }

    public function getIcon(): ?string
    {
        return 'heroicon-o-archive-box';
    }

    public function getLabel(): string
    {
        return 'Archived';
    }
}
```text

3. Update the Team model to use the state machine:

```php
<?php

namespace App\Models;

use App\States\Team\TeamState;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStatus\HasStatuses;

class Team extends Model
{
    use HasStates, HasStatuses;

    protected $casts = [
        'status' => TeamState::class,
    ];

    // Rest of the model...
}
```php
### Team Activity Status

1. Create a migration for adding the activity status column:

```bash
php artisan make:migration add_activity_status_to_teams_table
```text

2. Update the migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('activity_status')->default('inactive');
            $table->timestamp('last_activity_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['activity_status', 'last_activity_at']);
        });
    }
};
```php
3. Create a team activity status enum:

```php
<?php

namespace App\Enums;

enum TeamActivityStatus: string
{
    case Active = 'active';
    case Moderate = 'moderate';
    case Inactive = 'inactive';

    public function getLabel(): string
    {
        return match($this) {
            self::Active => 'Active',
            self::Moderate => 'Moderate',
            self::Inactive => 'Inactive',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::Active => 'success',
            self::Moderate => 'warning',
            self::Inactive => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::Active => 'heroicon-o-bolt',
            self::Moderate => 'heroicon-o-arrow-trending-up',
            self::Inactive => 'heroicon-o-arrow-trending-down',
        };
    }
}
```text

4. Update the Team model to use the activity status enum:

```php
<?php

namespace App\Models;

use App\Enums\TeamActivityStatus;
use App\States\Team\TeamState;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStatus\HasStatuses;

class Team extends Model
{
    use HasStates, HasStatuses;

    protected $casts = [
        'status' => TeamState::class,
        'activity_status' => TeamActivityStatus::class,
        'last_activity_at' => 'datetime',
    ];

    // Rest of the model...

    public function updateActivityStatus(): void
    {
        // Calculate activity based on recent posts, todos, etc.
        $recentActivity = $this->posts()
            ->where('created_at', '>', now()->subDays(30))
            ->count();

        $recentActivity += $this->todos()
            ->where('created_at', '>', now()->subDays(30))
            ->count();

        if ($recentActivity > 50) {
            $this->activity_status = TeamActivityStatus::Active;
        } elseif ($recentActivity > 10) {
            $this->activity_status = TeamActivityStatus::Moderate;
        } else {
            $this->activity_status = TeamActivityStatus::Inactive;
        }

        $this->last_activity_at = now();
        $this->save();

        // Also record in the statuses table
        $this->setStatus('activity_' . $this->activity_status->value);
    }
}
```php
## Step 3: Post Status Implementation

1. Create the Post State classes in `app/States/Post/PostState.php`:

```php
<?php

namespace App\States\Post;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class PostState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition(Draft::class, PendingReview::class)
            ->allowTransition(Draft::class, Published::class)
            ->allowTransition(Draft::class, Scheduled::class)
            ->allowTransition(PendingReview::class, Draft::class)
            ->allowTransition(PendingReview::class, Published::class)
            ->allowTransition(PendingReview::class, Scheduled::class)
            ->allowTransition(Scheduled::class, Published::class)
            ->allowTransition(Published::class, Archived::class)
            ->allowTransition(Archived::class, Draft::class);
    }

    abstract public function getColor(): string;

    abstract public function getIcon(): ?string;

    abstract public function getLabel(): string;
}
```text

2. Create the concrete state classes for Post (Draft, PendingReview, Published, Scheduled, Archived)

3. Update the Post model to use the state machine and status history:

```php
<?php

namespace App\Models;

use App\States\Post\PostState;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStatus\HasStatuses;

class Post extends Model
{
    use HasStates, HasStatuses;

    protected $casts = [
        'status' => PostState::class,
        'published_at' => 'datetime',
        'scheduled_for' => 'datetime',
    ];

    // Rest of the model...

    public function transitionStatus(PostState $newState, string $reason = null): void
    {
        $oldState = $this->status;
        $this->status = $newState;
        $this->save();

        // Record the transition in the statuses table
        $this->setStatus('state_' . $newState->getValue(), $reason);
    }
}
```php
## Step 4: Todo Status Implementation

1. Create the Todo State classes in `app/States/Todo/TodoState.php`:

```php
<?php

namespace App\States\Todo;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class TodoState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, InProgress::class)
            ->allowTransition(Pending::class, Cancelled::class)
            ->allowTransition(InProgress::class, Completed::class)
            ->allowTransition(InProgress::class, Pending::class)
            ->allowTransition(InProgress::class, Cancelled::class);
    }

    abstract public function getColor(): string;

    abstract public function getIcon(): ?string;

    abstract public function getLabel(): string;
}
```text

2. Create the concrete state classes for Todo (Pending, InProgress, Completed, Cancelled)

3. Update the Todo model to use the state machine and status history:

```php
<?php

namespace App\Models;

use App\States\Todo\TodoState;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStatus\HasStatuses;

class Todo extends Model
{
    use HasStates, HasStatuses;

    protected $casts = [
        'status' => TodoState::class,
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    // Rest of the model...

    public function transitionStatus(TodoState $newState, string $reason = null): void
    {
        $oldState = $this->status;
        $this->status = $newState;

        // Set completed_at if transitioning to Completed
        if ($newState instanceof Completed && !$this->completed_at) {
            $this->completed_at = now();
        }

        $this->save();

        // Record the transition in the statuses table
        $this->setStatus('state_' . $newState->getValue(), $reason);
    }
}
```php
## Step 5: Status History Tracking

1. Create a custom Status model to track additional information:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStatus\Status as BaseStatus;

class Status extends BaseStatus
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($status) {
            // Set the current user if authenticated
            if (auth()->check() && !$status->user_id) {
                $status->user_id = auth()->id();
            }
        });
    }
}
```text

2. Update the models to use the custom Status model:

```php
<?php

namespace App\Models;

use App\States\User\UserState;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStatus\HasStatuses;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasStates, HasStatuses, Notifiable;

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => UserState::class,
        'presence_status' => PresenceStatus::class,
        'last_activity_at' => 'datetime',
    ];

    // Use custom status model
    protected $statusModel = Status::class;

    // Rest of the model...
}
```php
3. Apply the same change to Team, Post, and Todo models.

## Step 6: Status Display in Filament

1. Create a Filament component to display status history:

```php
<?php

namespace App\Filament\Components;

use Filament\Tables\Columns\Column;
use Illuminate\Database\Eloquent\Model;

class StatusHistoryColumn extends Column
{
    protected string $view = 'filament.tables.columns.status-history-column';

    public function getStateIcon(): ?string
    {
        $model = $this->getRecord();

        if (method_exists($model, 'status') && $model->status && method_exists($model->status, 'getIcon')) {
            return $model->status->getIcon();
        }

        return null;
    }

    public function getStateColor(): ?string
    {
        $model = $this->getRecord();

        if (method_exists($model, 'status') && $model->status && method_exists($model->status, 'getColor')) {
            return $model->status->getColor();
        }

        return null;
    }

    public function getStatusHistory(): array
    {
        $model = $this->getRecord();

        if (!method_exists($model, 'statuses')) {
            return [];
        }

        return $model->statuses()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($status) {
                return [
                    'name' => $status->name,
                    'reason' => $status->reason,
                    'created_at' => $status->created_at->diffForHumans(),
                    'user' => $status->user ? $status->user->name : 'System',
                ];
            })
            ->toArray();
    }
}
```text

2. Create the view for the status history column:

```blade
{{-- resources/views/filament/tables/columns/status-history-column.blade.php --}}
<div
    x-data="{ open: false }"
    class="relative"
>
    <button
        type="button"
        @click="open = !open"
        class="inline-flex items-center justify-center gap-1 rounded-full px-2 py-1 text-sm font-medium"
        :class="{
            'bg-success-500/10 text-success-700': '{{ $getStateColor() }}' === 'success',
            'bg-danger-500/10 text-danger-700': '{{ $getStateColor() }}' === 'danger',
            'bg-warning-500/10 text-warning-700': '{{ $getStateColor() }}' === 'warning',
            'bg-info-500/10 text-info-700': '{{ $getStateColor() }}' === 'info',
            'bg-gray-500/10 text-gray-700': '{{ $getStateColor() }}' === 'gray',
        }"
    >
        @if ($getStateIcon())
            <x-dynamic-component :component="$getStateIcon()" class="h-4 w-4" />
        @endif

        {{ $getState() }}
    </button>

    <div
        x-show="open"
        @click.away="open = false"
        x-transition
        class="absolute z-10 mt-2 w-72 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5"
    >
        <div class="p-4">
            <h3 class="text-sm font-medium text-gray-900">Status History</h3>

            <div class="mt-2 space-y-3">
                @foreach ($getStatusHistory() as $status)
                    <div class="rounded-md bg-gray-50 p-2">
                        <div class="flex justify-between">
                            <span class="text-xs font-medium text-gray-900">{{ $status['name'] }}</span>
                            <span class="text-xs text-gray-500">{{ $status['created_at'] }}</span>
                        </div>

                        @if ($status['reason'])
                            <p class="mt-1 text-xs text-gray-700">{{ $status['reason'] }}</p>
                        @endif

                        <div class="mt-1 text-xs text-gray-500">By: {{ $status['user'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
```php
3. Use the status history column in Filament resources:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Components\StatusHistoryColumn;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // ... other resource configuration

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ... other columns

                StatusHistoryColumn::make('status')
                    ->label('Account Status'),

                Tables\Columns\TextColumn::make('presence_status')
                    ->formatStateUsing(fn ($state) => $state->getLabel())
                    ->icon(fn ($state) => $state->getIcon())
                    ->color(fn ($state) => $state->getColor()),
            ]);
    }
}
```text

4. Apply similar changes to Team, Post, and Todo resources.
