# Phase 0.20: Laravel Sanctum Setup

**Version:** 1.0.2
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Updated
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
  - [Required Prior Steps](#required-prior-steps)
  - [Required Packages](#required-packages)
  - [Required Knowledge](#required-knowledge)
  - [Required Environment](#required-environment)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Step 1: Install Laravel Sanctum](#step-1-install-laravel-sanctum)
- [Step 2: Configure Sanctum](#step-2-configure-sanctum)
  - [Configure Stateful Domains](#configure-stateful-domains)
  - [Configure Middleware](#configure-middleware)
  - [Configure CORS](#configure-cors)
- [Step 3: API Token Authentication](#step-3-api-token-authentication)
  - [Prepare User Model](#prepare-user-model)
  - [Create Token Issuance Endpoint](#create-token-issuance-endpoint)
  - [Token Abilities](#token-abilities)
  - [Protecting Routes](#protecting-routes)
  - [Revoking Tokens](#revoking-tokens)
  - [Token Expiration](#token-expiration)
- [Step 4: SPA Authentication](#step-4-spa-authentication)
  - [CSRF Protection](#csrf-protection)
  - [Authentication Flow](#authentication-flow)
- [Step 5: Mobile Application Authentication](#step-5-mobile-application-authentication)
- [Step 6: Testing Sanctum Authentication](#step-6-testing-sanctum-authentication)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides detailed instructions for setting up Laravel Sanctum in the Enhanced Laravel Application (ELA). Sanctum provides a featherweight authentication system for SPAs (single page applications), mobile applications, and simple, token-based APIs.

> **Reference:** [Laravel Sanctum Documentation](https:/laravel.com/docs/12.x/sanctum)

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Database Setup](040-database/010-database-setup.md) completed
- [Security Setup](050-security-testing/010-security-setup.md) completed
- [Code Quality Tools](060-configuration/040-code-quality-tools.md) completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed
- Laravel Sanctum (`laravel/sanctum`) installed

### Required Knowledge
- Basic understanding of authentication concepts
- Familiarity with API development
- Understanding of token-based authentication
- Knowledge of SPA and mobile authentication flows

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x
- Database connection configured
- Redis for token caching (optional)

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Install Laravel Sanctum | 5 minutes |
| Configure Sanctum | 15 minutes |
| Set Up API Token Authentication | 20 minutes |
| Configure SPA Authentication | 15 minutes |
| Set Up Mobile Application Authentication | 15 minutes |
| Test Sanctum Authentication | 20 minutes |
| **Total** | **90 minutes** |

> **Note:** These time estimates assume familiarity with Laravel authentication. Actual time may vary based on experience level and the complexity of your authentication requirements.

## Step 1: Install Laravel Sanctum

1. Install Laravel Sanctum using the `install:api` Artisan command:
   ```bash
   php artisan install:api
   ```php
   This command will:
   - Install the Sanctum package
   - Publish the Sanctum configuration file
   - Register the Sanctum service provider
   - Add the Sanctum middleware to your application
   - Create the necessary database migrations

2. Run the migrations to create the `personal_access_tokens` table:
   ```bash
   php artisan migrate
   ```markdown
## Step 2: Configure Sanctum

### Configure Stateful Domains

1. Update the `stateful` configuration in `config/sanctum.php`:
   ```php
   'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
       '%s%s',
       'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
       Sanctum::currentApplicationUrlWithPort(),
   ))),
   ```markdown
2. Add the `SANCTUM_STATEFUL_DOMAINS` variable to your `.env` file:
   ```
   SANCTUM_STATEFUL_DOMAINS=ela.test,localhost,127.0.0.1,localhost:3000
   ```

   > **Note:** Include any domains that your SPA will be making requests from. If you're accessing your application via a URL that includes a port (e.g., `127.0.0.1:8000`), include the port number with the domain.

### Configure Middleware

1. Configure the Sanctum middleware in your `bootstrap/app.php` file:
   ```php
   ->withMiddleware(function (Middleware $middleware) {
       $middleware->statefulApi();
   })
   ```

   This enables Sanctum to authenticate requests using Laravel's session cookies for SPA requests, while still allowing API token authentication for third-party requests.

### Configure CORS

1. Publish the CORS configuration file if it doesn't exist:
   ```bash
   php artisan config:publish cors
   ```

2. Update the CORS configuration in `config/cors.php`:
   ```php
   return [
       'paths' => ['api/*', 'sanctum/csrf-cookie'],
       'allowed_methods' => ['*'],
       'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
       'allowed_origins_patterns' => [],
       'allowed_headers' => ['*'],
       'exposed_headers' => [],
       'max_age' => 0,
       'supports_credentials' => true,
   ];
   ```

3. Add the `FRONTEND_URL` variable to your `.env` file:
   ```
   FRONTEND_URL=http://localhost:3000
   ```

4. Configure your frontend JavaScript HTTP client to include credentials. For Axios:
   ```javascript
   // resources/js/bootstrap.js
   axios.defaults.withCredentials = true;
   axios.defaults.withXSRFToken = true;
   ```

5. Configure the session cookie domain in `config/session.php` if your SPA is on a different subdomain:
   ```php
   'domain' => env('SESSION_DOMAIN', null),
   ```

   For subdomains of the same root domain, use:
   ```php
   'domain' => '.domain.com',
   ```

## Step 3: API Token Authentication

### Prepare User Model

1. Update your `User` model to use the `HasApiTokens` trait:
   ```php
   <?php

   namespace App\Models;

   use Illuminate\Database\Eloquent\Factories\HasFactory;
   use Illuminate\Foundation\Auth\User as Authenticatable;
   use Illuminate\Notifications\Notifiable;
   use Laravel\Sanctum\HasApiTokens;

   class User extends Authenticatable
   {
       use HasApiTokens, HasFactory, Notifiable;

       // ...
   }
   ```

### Create Token Issuance Endpoint

1. Create a controller for API token management:
   ```bash
   php artisan make:controller API/TokenController
   ```

2. Implement the token creation method in the controller:
   ```php
   <?php

   namespace App\Http\Controllers\API;

   use App\Http\Controllers\Controller;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Validation\ValidationException;

   class TokenController extends Controller
   {
       public function create(Request $request)
       {
           $request->validate([
               'email' => 'required|email',
               'password' => 'required',
               'device_name' => 'required',
           ]);

           $user = \App\Models\User::where('email', $request->email)->first();

           if (! $user || ! Hash::check($request->password, $user->password)) {
               throw ValidationException::withMessages([
                   'email' => ['The provided credentials are incorrect.'],
               ]);
           }

           return ['token' => $user->createToken($request->device_name)->plainTextToken];
       }
   }
   ```

3. Add the route to `routes/api.php`:
   ```php
   use App\Http\Controllers\API\TokenController;

   Route::post('/tokens/create', [TokenController::class, 'create']);
   ```

### Token Abilities

1. To issue tokens with specific abilities, modify the token creation method:
   ```php
   // Issue a token with specific abilities
   return ['token' => $user->createToken($request->device_name, ['read', 'create'])->plainTextToken];
   ```

2. Add the ability middleware aliases to `bootstrap/app.php`:
   ```php
   use Laravel\Sanctum\Http\Middleware\CheckAbilities;
   use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

   ->withMiddleware(function (Middleware $middleware) {
       $middleware->alias([
           'abilities' => CheckAbilities::class,
           'ability' => CheckForAnyAbility::class,
       ]);
   })
   ```

3. Use the ability middleware to protect routes:
   ```php
   // Route requires all listed abilities
   Route::get('/orders', function () {
       // ...
   })->middleware(['auth:sanctum', 'abilities:read,create']);

   // Route requires at least one of the listed abilities
   Route::get('/products', function () {
       // ...
   })->middleware(['auth:sanctum', 'ability:read,create']);
   ```

### Protecting Routes

1. Protect API routes using the Sanctum authentication guard in `routes/api.php`:
   ```php
   Route::middleware('auth:sanctum')->group(function () {
       Route::get('/user', function (Request $request) {
           return $request->user();
       });

       // Other protected routes...
   });
   ```

### Revoking Tokens

1. Add methods to revoke tokens in your `TokenController`:
   ```php
   public function destroy(Request $request)
   {
       // Revoke the token that was used to authenticate the current request
       $request->user()->currentAccessToken()->delete();

       return response()->noContent();
   }

   public function destroyAll(Request $request)
   {
       // Revoke all tokens for the current user
       $request->user()->tokens()->delete();

       return response()->noContent();
   }
   ```

2. Add routes for token revocation:
   ```php
   Route::middleware('auth:sanctum')->group(function () {
       Route::delete('/tokens/revoke', [TokenController::class, 'destroy']);
       Route::delete('/tokens/revoke-all', [TokenController::class, 'destroyAll']);
   });
   ```

### Token Expiration

1. Configure token expiration in `config/sanctum.php`:
   ```php
   'expiration' => 60 * 24 * 7, // 1 week in minutes
   ```

2. For individual token expiration, specify the expiration time when creating the token:
   ```php
   return ['token' => $user->createToken(
       $request->device_name,
       ['read', 'create'],
       now()->addDays(7)
   )->plainTextToken];
   ```

3. Set up a scheduled task to prune expired tokens in `app/Console/Kernel.php`:
   ```php
   protected function schedule(Schedule $schedule)
   {
       $schedule->command('sanctum:prune-expired --hours=24')->daily();
   }
   ```

## Step 4: SPA Authentication

### CSRF Protection

1. Ensure your SPA makes a request to the CSRF cookie endpoint before attempting to log in:
   ```javascript
   // Initialize CSRF protection
   await axios.get('/sanctum/csrf-cookie');

   // Then proceed with login
   const response = await axios.post('/login', {
       email: 'user@example.com',
       password: 'password'
   });
   ```

### Authentication Flow

1. Create login and logout endpoints in your application. If using Laravel Fortify, these are already provided.

2. After successful login, subsequent requests to your API will be automatically authenticated.

3. To check if a user is authenticated, make a request to a protected route:
   ```javascript
   try {
       const response = await axios.get('/api/user');
       // User is authenticated, response.data contains user information
   } catch (error) {
       // User is not authenticated
       if (error.response && (error.response.status === 401 || error.response.status === 419)) {
           // Redirect to login page
       }
   }
   ```

## Step 5: Mobile Application Authentication

For mobile applications, use the token issuance endpoint created in Step 3. Mobile apps should:

1. Send credentials to the token issuance endpoint
2. Store the returned token securely
3. Include the token in the `Authorization` header as a `Bearer` token for subsequent requests

Example mobile authentication flow:
```php
Route::post('/api/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return ['token' => $user->createToken($request->device_name)->plainTextToken];
});
```text

## Step 6: Testing Sanctum Authentication

1. Use the `Sanctum::actingAs` method in your tests:
   ```php
   use Laravel\Sanctum\Sanctum;
   use App\Models\User;

   public function test_api_requires_authentication()
   {
       // Test unauthenticated access
       $response = $this->getJson('/api/user');
       $response->assertStatus(401);

       // Test authenticated access
       Sanctum::actingAs(
           User::factory()->create(),
           ['read']
       );

       $response = $this->getJson('/api/user');
       $response->assertStatus(200);
   }
   ```

2. To grant all abilities to a token in tests:
   ```php
   Sanctum::actingAs(
       User::factory()->create(),
       ['*']
   );
   ```

## Troubleshooting

### Common Issues

1. **CORS Issues**
   - Ensure `supports_credentials` is set to `true` in `config/cors.php`
   - Verify that your frontend is sending credentials with requests
   - Check that the `SANCTUM_STATEFUL_DOMAINS` includes all domains your SPA uses

2. **Authentication Failures**
   - Verify that the `auth:sanctum` middleware is applied to protected routes
   - Check that tokens are being properly stored and sent with requests
   - Ensure tokens haven't expired

3. **Session Issues**
   - Verify that the session cookie domain is properly configured
   - Check that the session driver is properly configured
   - Ensure that the `statefulApi` middleware is registered

4. **Token Abilities**
   - Verify that tokens are being created with the correct abilities
   - Check that routes are protected with the correct ability middleware

## Related Documents

- [Security Setup](050-security-testing/010-security-setup.md) - For basic security setup
- [Security Configuration](050-security-testing/040-security-configuration.md) - For security configuration details
- [Code Quality Tools](060-configuration/040-code-quality-tools.md) - For code quality tools configuration
- [Phase 0 Summary](070-phase-summaries/010-phase0-summary.md) - For a summary of Phase 0 implementation

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, related documents, and version history | AI Assistant |

---

**Previous Step:** [Code Quality Tools Configuration](060-configuration/040-code-quality-tools.md) | **Next Step:** [Phase 0 Summary](070-phase-summaries/010-phase0-summary.md)
