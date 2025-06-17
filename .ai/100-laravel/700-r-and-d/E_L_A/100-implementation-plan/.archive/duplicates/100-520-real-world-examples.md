# Real-World Examples and Use Cases

**Version:** 1.0.1
**Date:** 2025-05-17
**Author:** AI Assistant
**Status:** Updated
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [User Management Examples](#user-management-examples)
- [Team Management Examples](#team-management-examples)
- [Content Management Examples](#content-management-examples)
- [Task Management Examples](#task-management-examples)
- [Messaging System Examples](#messaging-system-examples)
</details>

## Overview

This document provides practical, real-world examples for each major feature of the Enhanced Laravel Application. These examples demonstrate how components interact in real scenarios and provide concrete implementations that developers can reference.

## User Management Examples

### User Registration and Profile Management

This example demonstrates a complete user registration flow with email verification and profile management.

#### Controller Implementation

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function show(): View
    {
        return view('profile.show', [
            'user' => auth()->user(),
        ]);
    }

    public function edit(): View
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $this->userService->updateProfile(
            auth()->user(),
            $request->validated()
        );

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
```

#### Service Implementation

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function updateProfile(User $user, array $data): User
    {
        // Handle profile image upload if provided
        if (isset($data['profile_image'])) {
            $path = $data['profile_image']->store('profile-images', 'public');

            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $data['profile_image'] = $path;
        }

        $user->update($data);

        return $user;
    }
}
```

#### Volt Component for Profile Edit Form

```php
<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use function Livewire\Volt\{state, mount, uses, rules};

uses([WithFileUploads::class]);

state([
    'name' => '',
    'email' => '',
    'bio' => '',
    'profileImage' => null,
    'user' => null,
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'max:255'],
    'bio' => ['nullable', 'string', 'max:1000'],
    'profileImage' => ['nullable', 'image', 'max:1024'],
]);

mount(function () {
    $this->user = Auth::user();
    $this->name = $this->user->name;
    $this->email = $this->user->email;
    $this->bio = $this->user->bio;
});

function updateProfile() {
    $this->validate();

    $data = [
        'name' => $this->name,
        'email' => $this->email,
        'bio' => $this->bio,
    ];

    if ($this->profileImage) {
        $data['profile_image'] = $this->profileImage;
    }

    app(App\Services\UserService::class)->updateProfile($this->user, $data);

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => 'Profile updated successfully.',
    ]);
}
?>

<div>
    <form wire:submit="updateProfile" class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
            <textarea id="bio" wire:model="bio" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            @error('bio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="profileImage" class="block text-sm font-medium text-gray-700">Profile Image</label>
            <input type="file" id="profileImage" wire:model="profileImage" class="mt-1 block w-full">
            @error('profileImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            @if ($profileImage)
                <div class="mt-2">
                    <img src="{{ $profileImage->temporaryUrl() }}" alt="Profile Preview" class="h-20 w-20 rounded-full object-cover">
                </div>
            @elseif ($user->profile_image)
                <div class="mt-2">
                    <img src="{{ Storage::url($user->profile_image) }}" alt="Current Profile" class="h-20 w-20 rounded-full object-cover">
                </div>
            @endif
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Update Profile
            </button>
        </div>
    </form>
</div>
```

## Team Management Examples

### Hierarchical Team Structure Implementation

This example demonstrates how to implement and manage hierarchical teams with parent-child relationships.

#### Team Model

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\States\Team\TeamState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStates\HasStates;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Team extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;
    use HasStates;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'status',
    ];

    protected $casts = [
        'status' => TeamState::class,
    ];

    public function getParentKeyName(): string
    {
        return 'parent_id';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Team::class, 'parent_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }
}
```

#### Team Service

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use App\States\Team\Active;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TeamService
{
    public function createTeam(array $data, User $creator): Team
    {
        $team = new Team([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'status' => new Active(),
        ]);

        $team->save();

        // Add creator as team admin
        $team->users()->attach($creator->id, ['role' => 'admin']);

        // Update path and depth for hierarchical structure
        $this->updateTeamPath($team);

        return $team;
    }

    public function updateTeam(Team $team, array $data): Team
    {
        $team->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        // Update path and depth for hierarchical structure
        $this->updateTeamPath($team);

        return $team;
    }

    public function addMember(Team $team, User $user, string $role = 'member'): void
    {
        $team->users()->attach($user->id, ['role' => $role]);
    }

    public function removeMember(Team $team, User $user): void
    {
        $team->users()->detach($user->id);
    }

    public function updateMemberRole(Team $team, User $user, string $role): void
    {
        $team->users()->updateExistingPivot($user->id, ['role' => $role]);
    }

    public function getTeamHierarchy(?Team $parent = null): Collection
    {
        $query = Team::query();

        if ($parent) {
            $query->where('parent_id', $parent->id);
        } else {
            $query->whereNull('parent_id');
        }

        return $query->with('children')->get();
    }

    private function updateTeamPath(Team $team): void
    {
        if ($team->parent_id) {
            $parent = Team::find($team->parent_id);
            $team->path = $parent->path . '.' . $team->id;
            $team->depth = $parent->depth + 1;
        } else {
            $team->path = (string) $team->id;
            $team->depth = 0;
        }

        $team->save();

        // Update all children paths recursively
        foreach ($team->children as $child) {
            $this->updateTeamPath($child);
        }
    }
}
```

#### Team Management Volt Component

```php
<?php

use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Support\Collection;
use function Livewire\Volt\{state, mount, computed, rules};

state([
    'teams' => [],
    'expandedTeams' => [],
    'newTeamName' => '',
    'newTeamDescription' => '',
    'newTeamParentId' => null,
    'editingTeam' => null,
]);

rules([
    'newTeamName' => ['required', 'string', 'max:255'],
    'newTeamDescription' => ['nullable', 'string', 'max:1000'],
    'newTeamParentId' => ['nullable', 'exists:teams,id'],
]);

mount(function () {
    $this->loadTeams();
});

function loadTeams() {
    $teamService = app(TeamService::class);
    $this->teams = $teamService->getTeamHierarchy();
}

function toggleExpand($teamId) {
    if (in_array($teamId, $this->expandedTeams)) {
        $this->expandedTeams = array_diff($this->expandedTeams, [$teamId]);
    } else {
        $this->expandedTeams[] = $teamId;
    }
}

function createTeam() {
    $this->validate();

    $teamService = app(TeamService::class);
    $teamService->createTeam([
        'name' => $this->newTeamName,
        'description' => $this->newTeamDescription,
        'parent_id' => $this->newTeamParentId,
    ], auth()->user());

    $this->reset(['newTeamName', 'newTeamDescription', 'newTeamParentId']);
    $this->loadTeams();

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => 'Team created successfully.',
    ]);
}

function editTeam(Team $team) {
    $this->editingTeam = $team;
    $this->newTeamName = $team->name;
    $this->newTeamDescription = $team->description;
    $this->newTeamParentId = $team->parent_id;
}

function updateTeam() {
    $this->validate();

    $teamService = app(TeamService::class);
    $teamService->updateTeam($this->editingTeam, [
        'name' => $this->newTeamName,
        'description' => $this->newTeamDescription,
        'parent_id' => $this->newTeamParentId,
    ]);

    $this->reset(['newTeamName', 'newTeamDescription', 'newTeamParentId', 'editingTeam']);
    $this->loadTeams();

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => 'Team updated successfully.',
    ]);
}

function cancelEdit() {
    $this->reset(['newTeamName', 'newTeamDescription', 'newTeamParentId', 'editingTeam']);
}
?>

<div class="space-y-6">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $editingTeam ? 'Edit Team' : 'Create New Team' }}</h3>

            <form wire:submit="{{ $editingTeam ? 'updateTeam' : 'createTeam' }}" class="mt-5 space-y-4">
                <div>
                    <label for="newTeamName" class="block text-sm font-medium text-gray-700">Team Name</label>
                    <input type="text" id="newTeamName" wire:model="newTeamName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('newTeamName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="newTeamDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="newTeamDescription" wire:model="newTeamDescription" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('newTeamDescription') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="newTeamParentId" class="block text-sm font-medium text-gray-700">Parent Team (Optional)</label>
                    <select id="newTeamParentId" wire:model="newTeamParentId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">No Parent</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $editingTeam && $editingTeam->id === $team->id ? 'disabled' : '' }}>{{ $team->name }}</option>
                            @foreach($team->children as $child)
                                <option value="{{ $child->id }}" {{ $editingTeam && $editingTeam->id === $child->id ? 'disabled' : '' }}>&nbsp;&nbsp;└ {{ $child->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('newTeamParentId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    @if($editingTeam)
                        <button type="button" wire:click="cancelEdit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                    @endif

                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ $editingTeam ? 'Update Team' : 'Create Team' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Team Hierarchy</h3>

            <div class="mt-5">
                @if($teams->isEmpty())
                    <p class="text-gray-500">No teams found. Create your first team above.</p>
                @else
                    <ul class="space-y-2">
                        @foreach($teams as $team)
                            <li class="border rounded-md p-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium">{{ $team->name }}</span>
                                        @if($team->children->isNotEmpty())
                                            <button wire:click="toggleExpand({{ $team->id }})" class="ml-2 text-gray-500 hover:text-gray-700">
                                                <span class="text-xs">[{{ in_array($team->id, $expandedTeams) ? '-' : '+' }}]</span>
                                            </button>
                                        @endif
                                    </div>
                                    <button wire:click="editTeam({{ $team->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                </div>

                                @if($team->children->isNotEmpty() && in_array($team->id, $expandedTeams))
                                    <ul class="mt-2 pl-6 space-y-2 border-l-2 border-gray-200">
                                        @foreach($team->children as $child)
                                            <li class="border rounded-md p-2">
                                                <div class="flex items-center justify-between">
                                                    <span>{{ $child->name }}</span>
                                                    <button wire:click="editTeam({{ $child->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
```

## Content Management Examples

### Post Creation and Publishing Workflow

This example demonstrates a complete post creation and publishing workflow with categories, tags, and media attachments.

#### Post Model with State Machine

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\States\Post\Draft;
use App\States\Post\PostState;
use App\States\Post\Published;
use App\States\Post\Scheduled;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;
use Spatie\Tags\HasTags;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use HasStates;
    use HasTags;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'status',
        'published_at',
        'scheduled_for',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_for' => 'datetime',
        'status' => PostState::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile();

        $this->addMediaCollection('gallery');
    }

    public function publish(): self
    {
        $this->status->transitionTo(Published::class);
        $this->published_at = Carbon::now();
        $this->save();

        return $this;
    }

    public function schedule(Carbon $scheduledFor): self
    {
        $this->status->transitionTo(Scheduled::class);
        $this->scheduled_for = $scheduledFor;
        $this->save();

        return $this;
    }

    public function draft(): self
    {
        $this->status->transitionTo(Draft::class);
        $this->published_at = null;
        $this->save();

        return $this;
    }

    public function scopePublished($query)
    {
        return $query->whereState('status', Published::class)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', Carbon::now());
    }

    public function scopeScheduled($query)
    {
        return $query->whereState('status', Scheduled::class)
            ->whereNotNull('scheduled_for');
    }

    public function scopeDraft($query)
    {
        return $query->whereState('status', Draft::class);
    }
}
```

#### Post Service

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\States\Post\Draft;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostService
{
    public function createPost(array $data, User $author): Post
    {
        $post = new Post([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] ?? Str::limit(strip_tags($data['content']), 150),
            'status' => new Draft(),
        ]);

        $post->user()->associate($author);

        if (isset($data['team_id'])) {
            $post->team()->associate($data['team_id']);
        }

        $post->save();

        // Handle categories
        if (isset($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }

        // Handle tags
        if (isset($data['tags'])) {
            $post->syncTags($data['tags']);
        }

        // Handle featured image
        if (isset($data['featured_image'])) {
            $post->addMedia($data['featured_image'])
                ->toMediaCollection('featured_image');
        }

        // Handle gallery images
        if (isset($data['gallery'])) {
            foreach ($data['gallery'] as $image) {
                $post->addMedia($image)
                    ->toMediaCollection('gallery');
            }
        }

        return $post;
    }

    public function updatePost(Post $post, array $data): Post
    {
        $post->update([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] ?? Str::limit(strip_tags($data['content']), 150),
        ]);

        if (isset($data['team_id'])) {
            $post->team()->associate($data['team_id']);
            $post->save();
        }

        // Handle categories
        if (isset($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }

        // Handle tags
        if (isset($data['tags'])) {
            $post->syncTags($data['tags']);
        }

        // Handle featured image
        if (isset($data['featured_image'])) {
            // Remove existing featured image
            $post->clearMediaCollection('featured_image');

            // Add new featured image
            $post->addMedia($data['featured_image'])
                ->toMediaCollection('featured_image');
        }

        // Handle gallery images
        if (isset($data['gallery'])) {
            // If gallery_action is 'replace', clear the collection first
            if (isset($data['gallery_action']) && $data['gallery_action'] === 'replace') {
                $post->clearMediaCollection('gallery');
            }

            // Add new gallery images
            foreach ($data['gallery'] as $image) {
                $post->addMedia($image)
                    ->toMediaCollection('gallery');
            }
        }

        return $post;
    }

    public function publishPost(Post $post): Post
    {
        return $post->publish();
    }

    public function schedulePost(Post $post, Carbon $scheduledFor): Post
    {
        return $post->schedule($scheduledFor);
    }

    public function draftPost(Post $post): Post
    {
        return $post->draft();
    }

    public function deletePost(Post $post): bool
    {
        return $post->delete();
    }

    public function getPostsByStatus(string $status, ?int $teamId = null): Collection
    {
        $query = Post::query();

        switch ($status) {
            case 'published':
                $query->published();
                break;
            case 'scheduled':
                $query->scheduled();
                break;
            case 'draft':
                $query->draft();
                break;
            default:
                // No filter
                break;
        }

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->with(['user', 'team', 'categories', 'tags'])
            ->latest('updated_at')
            ->get();
    }

    public function removeMedia(Media $media): bool
    {
        return $media->delete();
    }
}
```

#### Post Editor Volt Component

```php
<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Team;
use App\Services\PostService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use function Livewire\Volt\{state, mount, computed, uses, rules};

uses([WithFileUploads::class]);

state([
    'post' => null,
    'title' => '',
    'content' => '',
    'excerpt' => '',
    'teamId' => null,
    'categories' => [],
    'tags' => '',
    'featuredImage' => null,
    'gallery' => [],
    'existingGallery' => [],
    'publishAction' => 'draft',
    'scheduledDate' => null,
    'scheduledTime' => null,
    'teams' => [],
    'availableCategories' => [],
    'isSubmitting' => false,
]);

rules([
    'title' => ['required', 'string', 'max:255'],
    'content' => ['required', 'string'],
    'excerpt' => ['nullable', 'string', 'max:500'],
    'teamId' => ['nullable', 'exists:teams,id'],
    'categories' => ['nullable', 'array'],
    'categories.*' => ['exists:categories,id'],
    'tags' => ['nullable', 'string'],
    'featuredImage' => ['nullable', 'image', 'max:2048'],
    'gallery.*' => ['image', 'max:2048'],
    'scheduledDate' => ['nullable', 'date', 'required_if:publishAction,schedule'],
    'scheduledTime' => ['nullable', 'string', 'required_if:publishAction,schedule'],
]);

mount(function (?Post $post = null) {
    $this->post = $post;
    $this->teams = Team::where('user_id', auth()->id())
        ->orWhereHas('users', function ($query) {
            $query->where('users.id', auth()->id());
        })
        ->get();

    $this->loadCategories();

    if ($post) {
        $this->title = $post->title;
        $this->content = $post->content;
        $this->excerpt = $post->excerpt;
        $this->teamId = $post->team_id;
        $this->categories = $post->categories->pluck('id')->toArray();
        $this->tags = $post->tags->pluck('name')->implode(', ');

        // Load existing media
        $this->existingGallery = $post->getMedia('gallery');

        // Set publish action based on post status
        if ($post->status->equals(\App\States\Post\Published::class)) {
            $this->publishAction = 'publish';
        } elseif ($post->status->equals(\App\States\Post\Scheduled::class)) {
            $this->publishAction = 'schedule';
            $this->scheduledDate = $post->scheduled_for->format('Y-m-d');
            $this->scheduledTime = $post->scheduled_for->format('H:i');
        } else {
            $this->publishAction = 'draft';
        }
    }
});

function loadCategories() {
    if ($this->teamId) {
        $this->availableCategories = Category::where('team_id', $this->teamId)->get();
    } else {
        $this->availableCategories = Collection::make();
    }

    // Filter out categories that don't belong to the selected team
    if ($this->categories) {
        $this->categories = array_filter($this->categories, function ($categoryId) {
            return $this->availableCategories->contains('id', $categoryId);
        });
    }
}

function updatedTeamId() {
    $this->loadCategories();
}

function removeExistingMedia($mediaId) {
    $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);

    if ($media) {
        app(PostService::class)->removeMedia($media);
        $this->existingGallery = $this->existingGallery->filter(function ($item) use ($mediaId) {
            return $item->id !== $mediaId;
        });
    }
}

function save() {
    $this->isSubmitting = true;
    $this->validate();

    $postService = app(PostService::class);

    $data = [
        'title' => $this->title,
        'content' => $this->content,
        'excerpt' => $this->excerpt,
        'team_id' => $this->teamId,
        'categories' => $this->categories,
    ];

    // Handle tags
    if ($this->tags) {
        $data['tags'] = array_map('trim', explode(',', $this->tags));
    }

    // Handle featured image
    if ($this->featuredImage) {
        $data['featured_image'] = $this->featuredImage;
    }

    // Handle gallery
    if (!empty($this->gallery)) {
        $data['gallery'] = $this->gallery;
    }

    if ($this->post) {
        // Update existing post
        $post = $postService->updatePost($this->post, $data);
    } else {
        // Create new post
        $post = $postService->createPost($data, auth()->user());
    }

    // Handle publishing status
    switch ($this->publishAction) {
        case 'publish':
            $postService->publishPost($post);
            break;
        case 'schedule':
            $scheduledDateTime = Carbon::parse("{$this->scheduledDate} {$this->scheduledTime}");
            $postService->schedulePost($post, $scheduledDateTime);
            break;
        default:
            $postService->draftPost($post);
            break;
    }

    $this->isSubmitting = false;

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => $this->post ? 'Post updated successfully.' : 'Post created successfully.',
    ]);

    // Redirect to post list
    $this->redirect(route('posts.index'));
}
?>

<div>
    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="md:col-span-2 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="title" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea id="content" wire:model="content" rows="15" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700">Excerpt</label>
                    <textarea id="excerpt" wire:model="excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <p class="mt-1 text-sm text-gray-500">A short summary of the post. If left empty, it will be generated from the content.</p>
                    @error('excerpt') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Featured Image -->
                <div>
                    <label for="featuredImage" class="block text-sm font-medium text-gray-700">Featured Image</label>
                    <input type="file" id="featuredImage" wire:model="featuredImage" class="mt-1 block w-full">
                    @error('featuredImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    @if ($featuredImage)
                        <div class="mt-2">
                            <img src="{{ $featuredImage->temporaryUrl() }}" alt="Featured Image Preview" class="h-40 w-auto object-cover rounded">
                        </div>
                    @elseif ($post && $post->hasMedia('featured_image'))
                        <div class="mt-2">
                            <img src="{{ $post->getFirstMediaUrl('featured_image') }}" alt="Current Featured Image" class="h-40 w-auto object-cover rounded">
                        </div>
                    @endif
                </div>

                <!-- Gallery -->
                <div>
                    <label for="gallery" class="block text-sm font-medium text-gray-700">Gallery Images</label>
                    <input type="file" id="gallery" wire:model="gallery" multiple class="mt-1 block w-full">
                    @error('gallery.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    @if (!empty($gallery))
                        <div class="mt-2 grid grid-cols-3 gap-4">
                            @foreach($gallery as $image)
                                <div class="relative">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Gallery Image Preview" class="h-24 w-full object-cover rounded">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($existingGallery && $existingGallery->isNotEmpty())
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700">Existing Gallery Images</h4>
                            <div class="mt-2 grid grid-cols-3 gap-4">
                                @foreach($existingGallery as $media)
                                    <div class="relative group">
                                        <img src="{{ $media->getUrl() }}" alt="Gallery Image" class="h-24 w-full object-cover rounded">
                                        <button type="button" wire:click="removeExistingMedia({{ $media->id }})" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <!-- Publishing Options -->
                <div class="bg-white shadow rounded-md p-4">
                    <h3 class="text-lg font-medium text-gray-900">Publishing Options</h3>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" id="draft" wire:model="publishAction" value="draft" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <label for="draft" class="ml-2 text-sm text-gray-700">Save as Draft</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="publish" wire:model="publishAction" value="publish" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <label for="publish" class="ml-2 text-sm text-gray-700">Publish Immediately</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="schedule" wire:model="publishAction" value="schedule" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <label for="schedule" class="ml-2 text-sm text-gray-700">Schedule for Later</label>
                                </div>
                            </div>
                        </div>

                        @if ($publishAction === 'schedule')
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="scheduledDate" class="block text-sm font-medium text-gray-700">Date</label>
                                    <input type="date" id="scheduledDate" wire:model="scheduledDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('scheduledDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="scheduledTime" class="block text-sm font-medium text-gray-700">Time</label>
                                    <input type="time" id="scheduledTime" wire:model="scheduledTime" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('scheduledTime') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Team & Categories -->
                <div class="bg-white shadow rounded-md p-4">
                    <h3 class="text-lg font-medium text-gray-900">Organization</h3>

                    <div class="mt-4 space-y-4">
                        <!-- Team -->
                        <div>
                            <label for="teamId" class="block text-sm font-medium text-gray-700">Team (Optional)</label>
                            <select id="teamId" wire:model.live="teamId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">No Team</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                            @error('teamId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Categories -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categories</label>
                            @if ($availableCategories->isEmpty())
                                <p class="mt-1 text-sm text-gray-500">Select a team to see available categories.</p>
                            @else
                                <div class="mt-2 space-y-2 max-h-40 overflow-y-auto">
                                    @foreach($availableCategories as $category)
                                        <div class="flex items-center">
                                            <input type="checkbox" id="category-{{ $category->id }}" wire:model="categories" value="{{ $category->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                            <label for="category-{{ $category->id }}" class="ml-2 text-sm text-gray-700">{{ $category->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('categories') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tags -->
                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                            <input type="text" id="tags" wire:model="tags" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-sm text-gray-500">Separate tags with commas.</p>
                            @error('tags') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{%20route('posts.index'">
                Cancel
            </a>%20}})
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                <span wire:loading.remove wire:target="save">{{ $post ? 'Update' : 'Create' }} Post</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </form>
</div>
```

## Task Management Examples

### Hierarchical Todo Management

This example demonstrates how to implement hierarchical todos with parent-child relationships, assignments, and status tracking.

#### Todo Model

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\States\Todo\Completed;
use App\States\Todo\InProgress;
use App\States\Todo\TodoState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;
use Spatie\Tags\HasTags;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Todo extends Model implements HasMedia
{
    use HasFactory;
    use HasRecursiveRelationships;
    use HasStates;
    use HasTags;
    use InteractsWithMedia;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'user_id',
        'team_id',
        'parent_id',
        'status',
        'due_date',
        'completed_at',
        'priority',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'status' => TodoState::class,
    ];

    public function getParentKeyName(): string
    {
        return 'parent_id';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Todo::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Todo::class, 'parent_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    public function complete(): self
    {
        $this->status->transitionTo(Completed::class);
        $this->completed_at = now();
        $this->save();

        return $this;
    }

    public function start(): self
    {
        $this->status->transitionTo(InProgress::class);
        $this->save();

        return $this;
    }

    public function scopeIncomplete($query)
    {
        return $query->whereNot('status', Completed::class)
            ->whereNull('completed_at');
    }

    public function scopeCompleted($query)
    {
        return $query->whereState('status', Completed::class)
            ->whereNotNull('completed_at');
    }

    public function scopeOverdue($query)
    {
        return $query->whereNot('status', Completed::class)
            ->whereNull('completed_at')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now());
    }
}
```

#### Todo Service

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Todo;
use App\Models\User;
use App\States\Todo\Pending;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TodoService
{
    public function createTodo(array $data, User $creator): Todo
    {
        $todo = new Todo([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'description' => $data['description'] ?? null,
            'status' => new Pending(),
            'parent_id' => $data['parent_id'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'priority' => $data['priority'] ?? 'medium',
        ]);

        // Associate with user
        if (isset($data['user_id'])) {
            $todo->user()->associate($data['user_id']);
        } else {
            $todo->user()->associate($creator);
        }

        // Associate with team
        if (isset($data['team_id'])) {
            $todo->team()->associate($data['team_id']);
        }

        $todo->save();

        // Handle categories
        if (isset($data['categories'])) {
            $todo->categories()->sync($data['categories']);
        }

        // Handle tags
        if (isset($data['tags'])) {
            $todo->syncTags($data['tags']);
        }

        // Handle attachments
        if (isset($data['attachments'])) {
            foreach ($data['attachments'] as $attachment) {
                $todo->addMedia($attachment)
                    ->toMediaCollection('attachments');
            }
        }

        // Update path and depth for hierarchical structure
        $this->updateTodoPath($todo);

        return $todo;
    }

    public function updateTodo(Todo $todo, array $data): Todo
    {
        $todo->update([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'description' => $data['description'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'priority' => $data['priority'] ?? 'medium',
        ]);

        // Associate with user
        if (isset($data['user_id'])) {
            $todo->user()->associate($data['user_id']);
            $todo->save();
        }

        // Associate with team
        if (isset($data['team_id'])) {
            $todo->team()->associate($data['team_id']);
            $todo->save();
        }

        // Handle categories
        if (isset($data['categories'])) {
            $todo->categories()->sync($data['categories']);
        }

        // Handle tags
        if (isset($data['tags'])) {
            $todo->syncTags($data['tags']);
        }

        // Handle attachments
        if (isset($data['attachments'])) {
            foreach ($data['attachments'] as $attachment) {
                $todo->addMedia($attachment)
                    ->toMediaCollection('attachments');
            }
        }

        // Update path and depth for hierarchical structure
        $this->updateTodoPath($todo);

        return $todo;
    }

    public function completeTodo(Todo $todo): Todo
    {
        $todo = $todo->complete();

        // Optionally complete all child todos
        foreach ($todo->children as $child) {
            $this->completeTodo($child);
        }

        return $todo;
    }

    public function startTodo(Todo $todo): Todo
    {
        return $todo->start();
    }

    public function deleteTodo(Todo $todo): bool
    {
        return $todo->delete();
    }

    public function getTodosByStatus(string $status, ?int $userId = null, ?int $teamId = null): Collection
    {
        $query = Todo::query();

        switch ($status) {
            case 'completed':
                $query->completed();
                break;
            case 'incomplete':
                $query->incomplete();
                break;
            case 'overdue':
                $query->overdue();
                break;
            default:
                // No filter
                break;
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->with(['user', 'team', 'categories', 'tags', 'parent'])
            ->latest('updated_at')
            ->get();
    }

    public function getTodoHierarchy(?int $parentId = null, ?int $teamId = null): Collection
    {
        $query = Todo::query();

        if ($parentId) {
            $query->where('parent_id', $parentId);
        } else {
            $query->whereNull('parent_id');
        }

        if ($teamId) {
            $query->where('team_id', $teamId);
        }

        return $query->with(['children', 'user'])->get();
    }

    private function updateTodoPath(Todo $todo): void
    {
        if ($todo->parent_id) {
            $parent = Todo::find($todo->parent_id);
            $todo->path = $parent->path . '.' . $todo->id;
            $todo->depth = $parent->depth + 1;
        } else {
            $todo->path = (string) $todo->id;
            $todo->depth = 0;
        }

        $todo->save();

        // Update all children paths recursively
        foreach ($todo->children as $child) {
            $this->updateTodoPath($child);
        }
    }
}
```

#### Todo Management Volt Component

```php
<?php

use App\Models\Category;
use App\Models\Team;
use App\Models\Todo;
use App\Models\User;
use App\Services\TodoService;
use Illuminate\Support\Collection;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use function Livewire\Volt\{state, mount, computed, uses, rules};

uses([WithFileUploads::class]);

state([
    'todos' => [],
    'expandedTodos' => [],
    'editingTodo' => null,
    'title' => '',
    'description' => '',
    'parentId' => null,
    'userId' => null,
    'teamId' => null,
    'dueDate' => null,
    'priority' => 'medium',
    'attachments' => [],
    'categories' => [],
    'tags' => '',
    'statusFilter' => 'incomplete',
    'users' => [],
    'teams' => [],
    'availableCategories' => [],
]);

rules([
    'title' => ['required', 'string', 'max:255'],
    'description' => ['nullable', 'string'],
    'parentId' => ['nullable', 'exists:todos,id'],
    'userId' => ['nullable', 'exists:users,id'],
    'teamId' => ['nullable', 'exists:teams,id'],
    'dueDate' => ['nullable', 'date'],
    'priority' => ['required', 'in:low,medium,high'],
    'attachments.*' => ['file', 'max:10240'],
    'categories' => ['nullable', 'array'],
    'categories.*' => ['exists:categories,id'],
    'tags' => ['nullable', 'string'],
]);

mount(function () {
    $this->loadTodos();
    $this->users = User::all();
    $this->teams = Team::all();
    $this->userId = auth()->id();
});

function loadTodos() {
    $todoService = app(TodoService::class);

    if ($this->statusFilter === 'hierarchy') {
        $this->todos = $todoService->getTodoHierarchy(null, $this->teamId);
    } else {
        $this->todos = $todoService->getTodosByStatus(
            $this->statusFilter,
            $this->userId,
            $this->teamId
        );
    }
}

function loadCategories() {
    if ($this->teamId) {
        $this->availableCategories = Category::where('team_id', $this->teamId)->get();
    } else {
        $this->availableCategories = Collection::make();
    }
}

function updatedTeamId() {
    $this->loadCategories();
}

function updatedStatusFilter() {
    $this->loadTodos();
}

function toggleExpand($todoId) {
    if (in_array($todoId, $this->expandedTodos)) {
        $this->expandedTodos = array_diff($this->expandedTodos, [$todoId]);
    } else {
        $this->expandedTodos[] = $todoId;
    }
}

function createTodo() {
    $this->validate();

    $todoService = app(TodoService::class);

    $data = [
        'title' => $this->title,
        'description' => $this->description,
        'parent_id' => $this->parentId,
        'user_id' => $this->userId,
        'team_id' => $this->teamId,
        'due_date' => $this->dueDate,
        'priority' => $this->priority,
        'categories' => $this->categories,
    ];

    // Handle tags
    if ($this->tags) {
        $data['tags'] = array_map('trim', explode(',', $this->tags));
    }

    // Handle attachments
    if (!empty($this->attachments)) {
        $data['attachments'] = $this->attachments;
    }

    $todoService->createTodo($data, auth()->user());

    $this->reset([
        'title', 'description', 'parentId', 'dueDate',
        'priority', 'attachments', 'categories', 'tags'
    ]);

    $this->loadTodos();

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => 'Todo created successfully.',
    ]);
}

function editTodo(Todo $todo) {
    $this->editingTodo = $todo;
    $this->title = $todo->title;
    $this->description = $todo->description;
    $this->parentId = $todo->parent_id;
    $this->userId = $todo->user_id;
    $this->teamId = $todo->team_id;
    $this->dueDate = $todo->due_date ? $todo->due_date->format('Y-m-d') : null;
    $this->priority = $todo->priority;
    $this->categories = $todo->categories->pluck('id')->toArray();
    $this->tags = $todo->tags->pluck('name')->implode(', ');

    $this->loadCategories();
}

function updateTodo() {
    $this->validate();

    $todoService = app(TodoService::class);

    $data = [
        'title' => $this->title,
        'description' => $this->description,
        'parent_id' => $this->parentId,
        'user_id' => $this->userId,
        'team_id' => $this->teamId,
        'due_date' => $this->dueDate,
        'priority' => $this->priority,
        'categories' => $this->categories,
    ];

    // Handle tags
    if ($this->tags) {
        $data['tags'] = array_map('trim', explode(',', $this->tags));
    }

    // Handle attachments
    if (!empty($this->attachments)) {
        $data['attachments'] = $this->attachments;
    }

    $todoService->updateTodo($this->editingTodo, $data);

    $this->reset([
        'editingTodo', 'title', 'description', 'parentId', 'dueDate',
        'priority', 'attachments', 'categories', 'tags'
    ]);

    $this->loadTodos();

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => 'Todo updated successfully.',
    ]);
}

function cancelEdit() {
    $this->reset([
        'editingTodo', 'title', 'description', 'parentId', 'dueDate',
        'priority', 'attachments', 'categories', 'tags'
    ]);
}

function completeTodo(Todo $todo) {
    $todoService = app(TodoService::class);
    $todoService->completeTodo($todo);

    $this->loadTodos();

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => 'Todo marked as completed.',
    ]);
}

function startTodo(Todo $todo) {
    $todoService = app(TodoService::class);
    $todoService->startTodo($todo);

    $this->loadTodos();

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => 'Todo marked as in progress.',
    ]);
}

function deleteTodo(Todo $todo) {
    $todoService = app(TodoService::class);
    $todoService->deleteTodo($todo);

    $this->loadTodos();

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => 'Todo deleted successfully.',
    ]);
}
?>

<div class="space-y-6">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $editingTodo ? 'Edit Todo' : 'Create New Todo' }}</h3>

            <form wire:submit="{{ $editingTodo ? 'updateTodo' : 'createTodo' }}" class="mt-5 space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" id="title" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="parentId" class="block text-sm font-medium text-gray-700">Parent Todo (Optional)</label>
                        <select id="parentId" wire:model="parentId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">No Parent</option>
                            @foreach($todos as $todo)
                                @if(!$editingTodo || $todo->id !== $editingTodo->id)
                                    <option value="{{ $todo->id }}">{{ $todo->title }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('parentId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="userId" class="block text-sm font-medium text-gray-700">Assigned To</label>
                        <select id="userId" wire:model="userId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('userId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="teamId" class="block text-sm font-medium text-gray-700">Team (Optional)</label>
                        <select id="teamId" wire:model.live="teamId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">No Team</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                        @error('teamId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="dueDate" class="block text-sm font-medium text-gray-700">Due Date (Optional)</label>
                        <input type="date" id="dueDate" wire:model="dueDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('dueDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                        <select id="priority" wire:model="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                        @error('priority') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="attachments" class="block text-sm font-medium text-gray-700">Attachments (Optional)</label>
                        <input type="file" id="attachments" wire:model="attachments" multiple class="mt-1 block w-full">
                        @error('attachments.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags (Optional)</label>
                        <input type="text" id="tags" wire:model="tags" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-sm text-gray-500">Separate tags with commas.</p>
                        @error('tags') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($availableCategories->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Categories (Optional)</label>
                        <div class="mt-2 space-y-2 max-h-40 overflow-y-auto">
                            @foreach($availableCategories as $category)
                                <div class="flex items-center">
                                    <input type="checkbox" id="category-{{ $category->id }}" wire:model="categories" value="{{ $category->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                    <label for="category-{{ $category->id }}" class="ml-2 text-sm text-gray-700">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('categories') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div class="flex justify-end space-x-3">
                    @if($editingTodo)
                        <button type="button" wire:click="cancelEdit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                    @endif

                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ $editingTodo ? 'Update Todo' : 'Create Todo' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Todo List</h3>

                <div class="flex space-x-4">
                    <select wire:model.live="statusFilter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="incomplete">Incomplete</option>
                        <option value="completed">Completed</option>
                        <option value="overdue">Overdue</option>
                        <option value="hierarchy">Hierarchy View</option>
                    </select>
                </div>
            </div>

            <div class="mt-5">
                @if($todos->isEmpty())
                    <p class="text-gray-500">No todos found. Create your first todo above.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($todos as $todo)
                            <li class="border rounded-md p-3 {{ $todo->priority === 'high' ? 'border-red-300' : ($todo->priority === 'medium' ? 'border-yellow-300' : 'border-gray-300') }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        @if($statusFilter !== 'completed')
                                            <button wire:click="completeTodo({{ $todo->id }})" class="text-green-600 hover:text-green-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        @endif

                                        <span class="font-medium {{ $todo->status->equals(\App\States\Todo\Completed::class) ? 'line-through text-gray-500' : '' }}">
                                            {{ $todo->title }}
                                        </span>

                                        @if($todo->children->isNotEmpty() && $statusFilter === 'hierarchy')
                                            <button wire:click="toggleExpand({{ $todo->id }})" class="text-gray-500 hover:text-gray-700">
                                                <span class="text-xs">[{{ in_array($todo->id, $expandedTodos) ? '-' : '+' }}]</span>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        @if($todo->due_date)
                                            <span class="text-sm {{ $todo->due_date->isPast() && !$todo->status->equals(\App\States\Todo\Completed::class) ? 'text-red-600' : 'text-gray-500' }}">
                                                Due: {{ $todo->due_date->format('M d, Y') }}
                                            </span>
                                        @endif

                                        @if($todo->user)
                                            <span class="text-sm text-gray-500">Assigned to: {{ $todo->user->name }}</span>
                                        @endif

                                        <div class="flex space-x-1">
                                            <button wire:click="editTodo({{ $todo->id }})" class="text-indigo-600 hover:text-indigo-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>

                                            <button wire:click="deleteTodo({{ $todo->id }})" class="text-red-600 hover:text-red-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @if($todo->description)
                                    <div class="mt-2 text-sm text-gray-600">
                                        {{ $todo->description }}
                                    </div>
                                @endif

                                @if($todo->children->isNotEmpty() && $statusFilter === 'hierarchy' && in_array($todo->id, $expandedTodos))
                                    <ul class="mt-3 pl-6 space-y-2 border-l-2 border-gray-200">
                                        @foreach($todo->children as $child)
                                            <li class="border rounded-md p-2 {{ $child->priority === 'high' ? 'border-red-300' : ($child->priority === 'medium' ? 'border-yellow-300' : 'border-gray-300') }}">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        @if(!$child->status->equals(\App\States\Todo\Completed::class))
                                                            <button wire:click="completeTodo({{ $child->id }})" class="text-green-600 hover:text-green-900">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </button>
                                                        @endif

                                                        <span class="{{ $child->status->equals(\App\States\Todo\Completed::class) ? 'line-through text-gray-500' : '' }}">
                                                            {{ $child->title }}
                                                        </span>
                                                    </div>

                                                    <div class="flex items-center space-x-1">
                                                        <button wire:click="editTodo({{ $child->id }})" class="text-indigo-600 hover:text-indigo-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </button>

                                                        <button wire:click="deleteTodo({{ $child->id }})" class="text-red-600 hover:text-red-900">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
```

## Messaging System Examples

### Conversation and Messaging Implementation

This example demonstrates a simple messaging system with conversations and real-time updates.

#### Conversation Model

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Conversation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($conversation) {
            $conversation->uuid = (string) Str::uuid();
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }
}
```

#### Message Model

```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'conversation_id',
        'user_id',
        'body',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($message) {
            $message->uuid = (string) Str::uuid();
        });
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): self
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }

        return $this;
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
```

#### Messaging Service

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

class MessagingService
{
    public function createConversation(array $userIds, ?string $name = null, string $type = 'private'): Conversation
    {
        $conversation = Conversation::create([
            'name' => $name,
            'type' => $type,
        ]);

        $conversation->users()->attach($userIds);

        return $conversation;
    }

    public function sendMessage(Conversation $conversation, User $sender, string $body): Message
    {
        $message = new Message([
            'user_id' => $sender->id,
            'body' => $body,
        ]);

        $conversation->messages()->save($message);

        // Broadcast the message to all participants
        broadcast(new MessageSent($message))->toOthers();

        return $message;
    }

    public function getConversationsForUser(User $user): Collection
    {
        return $user->conversations()
            ->with(['users', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get();
    }

    public function getMessagesForConversation(Conversation $conversation, int $limit = 50, int $page = 1): Collection
    {
        return $conversation->messages()
            ->with('user')
            ->latest()
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get()
            ->reverse();
    }

    public function markConversationAsRead(Conversation $conversation, User $user): void
    {
        $conversation->messages()
            ->whereNull('read_at')
            ->where('user_id', '!=', $user->id)
            ->update(['read_at' => now()]);
    }

    public function getUnreadMessageCount(User $user): int
    {
        return Message::whereHas('conversation', function ($query) use ($user) {
            $query->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            });
        })
        ->where('user_id', '!=', $user->id)
        ->whereNull('read_at')
        ->count();
    }
}
```

#### Messaging Volt Component

```php
<?php

use App\Models\Conversation;
use App\Models\User;
use App\Services\MessagingService;
use Illuminate\Support\Collection;
use function Livewire\Volt\{state, mount, computed};

state([
    'conversation' => null,
    'messages' => [],
    'messageBody' => '',
    'page' => 1,
    'hasMoreMessages' => false,
    'loading' => false,
    'users' => [],
]);

mount(function (Conversation $conversation) {
    $this->conversation = $conversation;
    $this->loadMessages();
    $this->users = $conversation->users->keyBy('id');

    // Mark conversation as read
    app(MessagingService::class)->markConversationAsRead($conversation, auth()->user());

    // Listen for new messages
    $this->on("echo:private-conversation.{$conversation->id},MessageSent", function ($event) {
        $this->messages->push($event['message']);
    });
});

function loadMessages() {
    $this->loading = true;

    $messagingService = app(MessagingService::class);
    $messages = $messagingService->getMessagesForConversation($this->conversation, 15, $this->page);

    if ($this->page === 1) {
        $this->messages = $messages;
    } else {
        // Prepend older messages
        $this->messages = $messages->concat($this->messages);
    }

    $this->hasMoreMessages = $messages->count() === 15;
    $this->loading = false;
}

function loadMoreMessages() {
    $this->page++;
    $this->loadMessages();
}

function sendMessage() {
    if (empty(trim($this->messageBody))) {
        return;
    }

    $messagingService = app(MessagingService::class);
    $message = $messagingService->sendMessage(
        $this->conversation,
        auth()->user(),
        $this->messageBody
    );

    // Add the message to the local collection
    $message->load('user');
    $this->messages->push($message);

    $this->messageBody = '';
}
?>

<div class="flex flex-col h-full">
    <!-- Conversation Header -->
    <div class="bg-white border-b px-4 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <h2 class="text-lg font-medium text-gray-900">
                {{ $conversation->name ?: $users->except(auth()->id())->pluck('name')->join(', ') }}
            </h2>
            <span class="text-sm text-gray-500">
                {{ $users->count() }} participants
            </span>
        </div>
    </div>

    <!-- Messages -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="message-container">
        @if($hasMoreMessages)
            <div class="flex justify-center">
                <button
                    wire:click="loadMoreMessages"
                    class="text-indigo-600 hover:text-indigo-900 text-sm"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50"
                >
                    <span wire:loading.remove wire:target="loadMoreMessages">Load more messages</span>
                    <span wire:loading wire:target="loadMoreMessages">Loading...</span>
                </button>
            </div>
        @endif

        @foreach($messages as $message)
            <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-lg {{ $message->user_id === auth()->id() ? 'bg-indigo-100' : 'bg-gray-100' }} rounded-lg px-4 py-2 shadow">
                    @if($message->user_id !== auth()->id())
                        <div class="font-medium text-sm text-gray-900">
                            {{ $message->user->name }}
                        </div>
                    @endif
                    <div class="text-gray-800">
                        {{ $message->body }}
                    </div>
                    <div class="text-xs text-gray-500 text-right mt-1">
                        {{ $message->created_at->format('g:i A') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Message Input -->
    <div class="bg-white border-t p-4">
        <form wire:submit="sendMessage" class="flex space-x-2">
            <input
                type="text"
                wire:model="messageBody"
                placeholder="Type your message..."
                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            <button
                type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75"
            >
                <span wire:loading.remove>Send</span>
                <span wire:loading>Sending...</span>
            </button>
        </form>
    </div>
</div>
```

These real-world examples demonstrate how the various components of the Enhanced Laravel Application work together to provide a robust, feature-rich platform for team collaboration, content management, task organization, and communication. The examples showcase the use of Laravel's features and the integration of various packages to create a cohesive application.
