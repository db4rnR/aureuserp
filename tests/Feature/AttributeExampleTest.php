<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Pest\Attributes\DataProvider;
use Pest\Attributes\Description;
use Pest\Attributes\Group;
use Pest\Attributes\Test;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

/**
 * Example of feature tests using attributes instead of PHPDoc comments.
 */
#[Test]
#[Group('feature')]
#[Description('Test that the home page loads successfully')]
function home_page_loads_successfully(): void
{
    $response = get('/');

    $response->assertStatus(200);
}

#[Test]
#[Group('feature')]
#[Description('Test that the login page loads successfully')]
function login_page_loads_successfully(): void
{
    $response = get('/login');

    $response->assertStatus(200);
    $response->assertSee('Login');
}

#[Test]
#[Group('feature')]
#[Description('Test user registration process')]
function user_can_register(): void
{
    $userData = [
        'name' => 'Test User',
        'email' => 'test_register@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    // This is just an example. Adjust according to your actual registration route and process.
    $response = post('/register', $userData);

    // Assuming successful registration redirects to the dashboard
    $response->assertRedirect('/dashboard');

    // Check that the user was created in the database
    expect(User::where('email', 'test_register@example.com')->exists())->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Description('Test user login process')]
function user_can_login(): void
{
    // Create a user
    User::factory()->create([
        'email' => 'test_login@example.com',
        'password' => Hash::make('password'),
    ]);

    // Attempt to log in
    $response = post('/login', [
        'email' => 'test_login@example.com',
        'password' => 'password',
    ]);

    // Assuming successful login redirects to the dashboard
    $response->assertRedirect('/dashboard');

    // Check that the user is authenticated
    expect(auth()->check())->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Description('Test authenticated user can access protected routes')]
function authenticated_user_can_access_protected_routes(): void
{
    // Create a user
    $user = User::factory()->create();

    // Act as the authenticated user
    actingAs($user);

    // Try to access a protected route
    $response = get('/dashboard');

    // Should be successful
    $response->assertStatus(200);
}

#[Test]
#[Group('feature')]
#[Description('Test unauthenticated user cannot access protected routes')]
function unauthenticated_user_cannot_access_protected_routes(): void
{
    // Try to access a protected route without authentication
    $response = get('/dashboard');

    // Should redirect to login
    $response->assertRedirect('/login');
}

#[Test]
#[Group('feature')]
#[DataProvider('user_roles_provider')]
#[Description('Test user access based on roles')]
function user_access_based_on_role($role, $route, $expectedStatus): void
{
    // Create a user with the given role
    // This is just an example. Adjust according to your actual user and role structure.
    $user = User::factory()->create();
    $user->assignRole($role);

    // Act as the authenticated user
    actingAs($user);

    // Try to access the route
    $response = get($route);

    // Check the response status
    $response->assertStatus($expectedStatus);
}

function user_roles_provider(): array
{
    return [
        'admin can access admin dashboard' => ['admin', '/admin/dashboard', 200],
        'user cannot access admin dashboard' => ['user', '/admin/dashboard', 403],
        'admin can access user dashboard' => ['admin', '/dashboard', 200],
        'user can access user dashboard' => ['user', '/dashboard', 200],
    ];
}
