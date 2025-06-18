<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Webkul\Security\Models\User;

trait AuthenticationTestingTrait
{
    /**
     * The currently authenticated user.
     */
    protected ?Authenticatable $user = null;

    /**
     * Act as a user.
     *
     * @return $this
     */
    protected function actingAs(?Authenticatable $user = null, ?string $guard = null): self
    {
        $this->user = $user instanceof Authenticatable ? $user : User::factory()->create();

        $this->be($this->user, $guard);

        return $this;
    }

    /**
     * Act as an admin user.
     *
     * @return $this
     */
    protected function actingAsAdmin(array $attributes = [], ?string $guard = null): self
    {
        $adminAttributes = array_merge([
            'is_admin' => true,
        ], $attributes);

        $admin = User::factory()->create($adminAttributes);

        return $this->actingAs($admin, $guard);
    }

    /**
     * Act as a guest user.
     *
     * @return $this
     */
    protected function actingAsGuest(): self
    {
        Auth::logout();

        $this->user = null;

        return $this;
    }

    /**
     * Get a bearer token for the current user.
     */
    protected function getBearerToken(): string
    {
        if (! $this->user) {
            $this->actingAs();
        }

        $token = $this->user->createToken('test-token')->plainTextToken;

        return "Bearer {$token}";
    }

    /**
     * Get authorization headers for the current user.
     */
    protected function getAuthHeaders(): array
    {
        return [
            'Authorization' => $this->getBearerToken(),
            'Accept' => 'application/json',
        ];
    }

    /**
     * Assert that the user is authenticated.
     */
    protected function assertAuthenticated(?string $guard = null): void
    {
        $this->assertTrue(Auth::guard($guard)->check(), 'User is not authenticated.');
    }

    /**
     * Assert that the user is not authenticated.
     */
    protected function assertGuest(?string $guard = null): void
    {
        $this->assertFalse(Auth::guard($guard)->check(), 'User is authenticated.');
    }

    /**
     * Assert that the current user is the given user.
     */
    protected function assertAuthenticatedAs(Authenticatable $user, ?string $guard = null): void
    {
        $this->assertTrue(
            Auth::guard($guard)->user()->is($user),
            'User is not authenticated as the expected user.'
        );
    }

    /**
     * Assert that the user has the given ability.
     *
     * @param  array|mixed  $arguments
     */
    protected function assertCan(string $ability, $arguments = []): void
    {
        $this->assertTrue(
            Auth::user()->can($ability, $arguments),
            "User does not have the ability to [{$ability}]."
        );
    }

    /**
     * Assert that the user does not have the given ability.
     *
     * @param  array|mixed  $arguments
     */
    protected function assertCannot(string $ability, $arguments = []): void
    {
        $this->assertFalse(
            Auth::user()->can($ability, $arguments),
            "User has the ability to [{$ability}]."
        );
    }

    /**
     * Assert that the response requires authentication.
     *
     * @param  \Illuminate\Testing\TestResponse  $response
     */
    protected function assertResponseRequiresAuthentication($response): void
    {
        $response->assertStatus(401);
    }

    /**
     * Assert that the response requires authorization.
     *
     * @param  \Illuminate\Testing\TestResponse  $response
     */
    protected function assertResponseRequiresAuthorization($response): void
    {
        $response->assertStatus(403);
    }
}
