# Enhanced Laravel Application Coding Standards

**Version:** 1.0.0
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Complete
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [General PHP Standards](#general-php-standards)
- [Laravel-Specific Standards](#laravel-specific-standards)
- [Database and Eloquent](#database-and-eloquent)
- [Frontend Standards](#frontend-standards)
- [Testing Standards](#testing-standards)
</details>

## Overview

This document defines the coding standards and best practices for the Enhanced Laravel Application. Following these standards ensures consistency, maintainability, and readability across the codebase. All contributors should adhere to these standards when writing or modifying code.

## General PHP Standards

### File Structure

- All PHP files MUST use the UTF-8 encoding without BOM.
- All PHP files MUST use Unix LF (linefeed) line endings.
- All PHP files MUST end with a single blank line.
- PHP code MUST use the long `<?php ?>` tags; short tags are NOT allowed.
- Files containing only PHP code SHOULD NOT have the closing `?>` tag.
- Each PHP file SHOULD declare strict types.

**Example:**

```php
<?php

declare(strict_types=1);

namespace App\Models;

// Code goes here
```text

### Naming Conventions

- Class names MUST be declared in `PascalCase`.
- Method names MUST be declared in `camelCase`.
- Property names MUST be declared in `camelCase`.
- Constant names MUST be declared in `UPPER_CASE` with underscore separators.
- Function names SHOULD be declared in `snake_case`.
- Variable names SHOULD be declared in `camelCase`.
- Boolean variables SHOULD be prefixed with `is`, `has`, `can`, etc.

**Examples:**

```php
class UserRepository
{
    private const MAX_ATTEMPTS = 5;

    private bool $isActive = true;

    public function findByEmail(string $email): ?User
    {
        $hashedPassword = hash('sha256', 'password');

        // Method implementation
    }
}

function get_config_value(string $key): mixed
{
    // Function implementation
}
```php
### Code Style

- Code MUST follow PSR-1, PSR-4, and PSR-12 standards.
- Indentation MUST use 4 spaces (not tabs).
- Line length SHOULD be kept under 120 characters.
- Import statements SHOULD be grouped and ordered alphabetically.
- Use type hints for parameters and return types whenever possible.
- Use nullable types (`?string`) instead of union types with null (`string|null`).

**Example:**

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function getActiveUsers(): Collection
    {
        return $this->userRepository->findByStatus('active');
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }
}
```text

## Laravel-Specific Standards

### Controllers

- Controllers SHOULD be focused on HTTP concerns.
- Controllers SHOULD delegate business logic to services or actions.
- Controller methods SHOULD follow the 7 standard resource actions (index, create, store, show, edit, update, destroy).
- Controller method names for API controllers SHOULD follow REST conventions (index, store, show, update, destroy).

**Example:**

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        $users = $this->userService->getAllUsers();

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request): UserResource
    {
        $user = $this->userService->createUser($request->validated());

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $user = $this->userService->updateUser($user, $request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user): Response
    {
        $this->userService->deleteUser($user);

        return response()->noContent();
    }
}
```php
### Requests

- Form requests SHOULD be used for validation.
- Validation rules SHOULD be defined in the `rules()` method.
- Custom error messages SHOULD be defined in the `messages()` method.
- Authorization logic SHOULD be defined in the `authorize()` method.

**Example:**

```php
<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('user'));
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'status' => ['sometimes', 'string', 'in:active,inactive,suspended'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already in use.',
            'status.in' => 'The status must be one of: active, inactive, suspended.',
        ];
    }
}
```text

## Database and Eloquent

### Migrations

- Migration class names SHOULD be prefixed with a timestamp and describe the action being performed.
- Table names SHOULD be plural and snake_case.
- Foreign key column names SHOULD be singular model name followed by `_id` (e.g., `user_id`).
- Primary keys SHOULD be named `id` and be unsigned big integers.
- Timestamps SHOULD be included on all tables using `$table->timestamps()`.
- Soft deletes SHOULD be included on most tables using `$table->softDeletes()`.
- Foreign keys SHOULD be defined explicitly with appropriate cascade actions.

**Example:**

```php
<?php

declare(strict_types=1);

use App\Database\Migrations\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends BaseMigration
{
    public function up(): void
    {
        $this->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $this->snowflakePrimaryKey($table);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $this->timestampsWithUserstamps($table);
        });
    }

    public function down(): void
    {
        $this->getSchemaBuilder()->dropIfExists('posts');
    }
};
```php
### Models

- Models SHOULD use appropriate traits for common functionality.
- Models SHOULD define relationships using method names that clearly describe the relation.
- Models SHOULD define fillable or guarded properties.
- Models SHOULD define casts for non-string attributes.
- Models SHOULD use custom collections when additional collection methods are needed.

**Example:**

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\States\Post\PostState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    ];

    protected $casts = [
        'published_at' => 'datetime',
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

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('status', 'published');
    }
}
```text

## Frontend Standards

### Blade Templates

- Blade templates SHOULD use 4 spaces for indentation.
- Blade directives SHOULD be lowercase (e.g., `@if`, `@foreach`).
- Blade templates SHOULD use component-based architecture where possible.
- Long conditionals SHOULD be extracted to PHP methods or Blade components.
- Inline PHP SHOULD be avoided in favor of Blade directives.

**Example:**

```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($posts->isEmpty())
                        <p class="text-gray-500">{{ __('No posts found.') }}</p>
                    @else
                        <ul class="space-y-4">
                            @foreach ($posts as $post)
                                <x-post-card :post="$post" />
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```php
### Livewire Components

- Livewire components SHOULD follow single responsibility principle.
- Livewire properties SHOULD be typed and documented.
- Livewire methods SHOULD be named descriptively.
- Livewire validation SHOULD use the `rules()` method.
- Livewire components SHOULD use lifecycle hooks appropriately.

**Example:**

```php
<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PostEditor extends Component
{
    public Post $post;

    public string $title = '';
    public string $content = '';
    public ?string $excerpt = null;

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
            'excerpt' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->excerpt = $post->excerpt;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->post->update($validated);

        $this->dispatch('post-saved', postId: $this->post->id);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => __('Post updated successfully.'),
        ]);
    }

    public function render(): View
    {
        return view('livewire.post-editor');
    }
}
```text

### Volt Components

- Volt components SHOULD use the functional paradigm.
- Volt components SHOULD declare state at the top of the file.
- Volt components SHOULD use typed properties.
- Volt components SHOULD use arrow functions for simple methods.
- Volt components SHOULD use regular functions for complex methods.

**Example:**

```php
<?php

use App\Models\Post;
use function Livewire\Volt\{state, mount, computed, rules};

state([
    'post' => fn() => null,
    'title' => '',
    'content' => '',
    'excerpt' => null,
]);

rules([
    'title' => ['required', 'string', 'min:3', 'max:255'],
    'content' => ['required', 'string', 'min:10'],
    'excerpt' => ['nullable', 'string', 'max:500'],
]);

mount(function (Post $post) {
    $this->post = $post;
    $this->title = $post->title;
    $this->content = $post->content;
    $this->excerpt = $post->excerpt;
});

$wordCount = computed(function () {
    return str_word_count($this->content);
});

function save() {
    $this->validate();

    $this->post->update([
        'title' => $this->title,
        'content' => $this->content,
        'excerpt' => $this->excerpt,
    ]);

    $this->dispatch('post-saved', postId: $this->post->id);

    $this->dispatch('notify', [
        'type' => 'success',
        'message' => __('Post updated successfully.'),
    ]);
}
?>

<div>
    <form wire:submit="save" class="space-y-4">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Title') }}</label>
            <input type="text" id="title" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="content" class="block text-sm font-medium text-gray-700">{{ __('Content') }}</label>
            <textarea id="content" wire:model="content" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            <p class="text-sm text-gray-500">{{ __('Word count:') }} {{ $wordCount }}</p>
            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="excerpt" class="block text-sm font-medium text-gray-700">{{ __('Excerpt') }}</label>
            <textarea id="excerpt" wire:model="excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            @error('excerpt') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Save') }}
            </button>
        </div>
    </form>
</div>
```php
## Testing Standards

### Unit Tests

- Tests SHOULD be written using Pest PHP.
- Test files SHOULD be named with the suffix `Test.php`.
- Test methods SHOULD be descriptive and follow the `it_does_something` pattern.
- Tests SHOULD use the Arrange-Act-Assert pattern.
- Tests SHOULD be isolated and not depend on external services.

**Example:**

```php
<?php

use App\Models\Post;
use App\Models\User;

it('can create a post', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    $post = Post::factory()
        ->for($user)
        ->create([
            'title' => 'Test Post',
            'content' => 'This is a test post content.',
        ]);

    // Assert
    expect($post)
        ->toBeInstanceOf(Post::class)
        ->title->toBe('Test Post')
        ->content->toBe('This is a test post content')
        ->user_id->toBe($user->id);
});

it('marks post as published when publishing', function () {
    // Arrange
    $post = Post::factory()->create([
        'status' => 'draft',
        'published_at' => null,
    ]);

    // Act
    $post->publish();

    // Assert
    expect($post->refresh())
        ->status->toBe('published')
        ->published_at->not->toBeNull();
});
```text

### Feature Tests

- Feature tests SHOULD test the application as a whole.
- Feature tests SHOULD use the HTTP testing methods.
- Feature tests SHOULD assert the response status and content.
- Feature tests SHOULD use database transactions to isolate tests.

**Example:**

```php
<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows authorized users to create posts', function () {
    // Arrange
    $user = User::factory()->create();

    $postData = [
        'title' => 'New Test Post',
        'content' => 'This is the content of the test post.',
        'excerpt' => 'A brief excerpt.',
    ];

    // Act
    $response = $this->actingAs($user)
        ->post(route('posts.store'), $postData);

    // Assert
    $response->assertRedirect(route('posts.index'));
    $response->assertSessionHas('success', 'Post created successfully.');

    $this->assertDatabaseHas('posts', [
        'title' => 'New Test Post',
        'user_id' => $user->id,
    ]);
});

it('prevents unauthorized users from creating posts', function () {
    // Arrange
    $user = User::factory()->create(['status' => 'inactive']);

    $postData = [
        'title' => 'New Test Post',
        'content' => 'This is the content of the test post.',
    ];

    // Act
    $response = $this->actingAs($user)
        ->post(route('posts.store'), $postData);

    // Assert
    $response->assertStatus(403);

    $this->assertDatabaseMissing('posts', [
        'title' => 'New Test Post',
    ]);
});
```php
### Browser Tests

- Browser tests SHOULD be used for testing complex UI interactions.
- Browser tests SHOULD use Laravel Dusk.
- Browser tests SHOULD be isolated and not depend on external services.
- Browser tests SHOULD use page objects for complex pages.

**Example:**

```php
<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PostTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_create_post()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/posts/create')
                ->type('title', 'My New Post')
                ->type('content', 'This is the content of my new post.')
                ->type('excerpt', 'A brief excerpt.')
                ->press('Create Post')
                ->assertPathIs('/posts')
                ->assertSee('Post created successfully.')
                ->assertSee('My New Post');
        });
    }

    public function test_user_can_edit_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->browse(function (Browser $browser) use ($user, $post) {
            $browser->loginAs($user)
                ->visit("/posts/{$post->id}/edit")
                ->type('title', 'Updated Post Title')
                ->press('Update Post')
                ->assertPathIs('/posts')
                ->assertSee('Post updated successfully.')
                ->assertSee('Updated Post Title');
        });
    }
}
```text

---

These coding standards should be followed by all developers working on the Enhanced Laravel Application. Consistent adherence to these standards will ensure code quality, maintainability, and readability across the codebase.
