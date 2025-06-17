# Phase 1: Phase 0.12: Security Setup

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
- [Step 1: Configure Basic Security Settings](#step-1-configure-basic-security-settings)
- [Step 2: Set Up CSRF Protection](#step-2-set-up-csrf-protection)
- [Step 3: Configure Session Security](#step-3-configure-session-security)
- [Step 4: Set Up HTTP Headers](#step-4-set-up-http-headers)
- [Step 5: Configure Content Security Policy](#step-5-configure-content-security-policy)
- [Step 6: Set Up Rate Limiting](#step-6-set-up-rate-limiting)
- [Step 7: Configure Sanctum](#step-7-configure-sanctum)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides instructions for setting up basic security features for the Enhanced Laravel Application (ELA). It covers configuring basic security settings, CSRF protection, and session security.

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Database Migrations](040-database/030-database-migrations.md) completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed
- Redis PHP extension installed
- Laravel Sanctum (`laravel/sanctum`) installed

### Required Knowledge
- Basic understanding of web security concepts
- Familiarity with Laravel's security features
- Understanding of CSRF, XSS, and session security

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x
- Redis server installed and running

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Configure Basic Security Settings | 15 minutes |
| Set Up CSRF Protection | 10 minutes |
| Configure Session Security | 15 minutes |
| Set Up HTTP Headers | 10 minutes |
| Configure Content Security Policy | 15 minutes |
| Set Up Rate Limiting | 10 minutes |
| Configure Sanctum | 15 minutes |
| **Total** | **90 minutes** |

> **Note:** These time estimates assume familiarity with Laravel security features. Actual time may vary based on experience level and the complexity of your security requirements.

## Step 1: Configure Basic Security Settings

1. Update the security settings in the `.env` file:
   ```
   APP_KEY=base64:your_app_key
   APP_DEBUG=true
   APP_URL=http://ela.test

   SESSION_DRIVER=redis
   SESSION_LIFETIME=120
   SESSION_SECURE_COOKIE=false

   SANCTUM_STATEFUL_DOMAINS=ela.test
   ```

   Note: In production, you would set `APP_DEBUG=false` and `SESSION_SECURE_COOKIE=true`.

2. Configure the security headers in `app/Http/Middleware/TrustProxies.php`:
   ```php
   <?php

   namespace App\Http\Middleware;

   use Illuminate\Http\Middleware\TrustProxies as Middleware;
   use Illuminate\Http\Request;

   class TrustProxies extends Middleware
   {
       /**
        * The trusted proxies for this application.
        *
        * @var array<int, string>|string|null
        */
       protected $proxies;

       /**
        * The headers that should be used to detect proxies.
        *
        * @var int
        */
       protected $headers =
           Request::HEADER_X_FORWARDED_FOR |
           Request::HEADER_X_FORWARDED_HOST |
           Request::HEADER_X_FORWARDED_PORT |
           Request::HEADER_X_FORWARDED_PROTO |
           Request::HEADER_X_FORWARDED_AWS_ELB;
   }
   ```

3. Create a security headers middleware:
   ```bash
   php artisan make:middleware SecurityHeaders
   ```

4. Configure the security headers middleware in `app/Http/Middleware/SecurityHeaders.php`:
   ```php
   <?php

   namespace App\Http\Middleware;

   use Closure;
   use Illuminate\Http\Request;
   use Symfony\Component\HttpFoundation\Response;

   class SecurityHeaders
   {
       /**
        * Handle an incoming request.
        *
        * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
        */
       public function handle(Request $request, Closure $next): Response
       {
           $response = $next($request);

           $response->headers->set('X-Content-Type-Options', 'nosniff');
           $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
           $response->headers->set('X-XSS-Protection', '1; mode=block');
           $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

           // Only in production
           if (app()->environment('production')) {
               $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
               $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self' data:; connect-src 'self'");
           }

           return $response;
       }
   }
   ```

5. Register the security headers middleware in `app/Http/Kernel.php`:
   ```php
   protected $middleware = [
       // \App\Http\Middleware\TrustHosts::class,
       \App\Http\Middleware\TrustProxies::class,
       \Illuminate\Http\Middleware\HandleCors::class,
       \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
       \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
       \App\Http\Middleware\TrimStrings::class,
       \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
       \App\Http\Middleware\SecurityHeaders::class,
   ];
   ```

## Step 2: Set Up CSRF Protection

1. Configure CSRF protection in `app/Http/Middleware/VerifyCsrfToken.php`:
   ```php
   <?php

   namespace App\Http\Middleware;

   use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

   class VerifyCsrfToken extends Middleware
   {
       /**
        * The URIs that should be excluded from CSRF verification.
        *
        * @var array<int, string>
        */
       protected $except = [
           // Add any routes that should be excluded from CSRF protection
           // For example: 'api/*'
       ];
   }
   ```

2. Include the CSRF token in your layout file `resources/views/layouts/app.blade.php`:
   ```blade
   <meta name="csrf-token" content="{{ csrf_token() }}">
   ```

3. Configure JavaScript to include the CSRF token in AJAX requests in `resources/js/bootstrap.js`:
   ```javascript
   /**
    * Echo exposes an expressive API for subscribing to channels and listening
    * for events that are broadcast by Laravel. Echo and event broadcasting
    * allows your team to easily build robust real-time web applications.
    */

   import axios from 'axios';
   window.axios = axios;

   window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
   window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
   ```

## Step 3: Configure Session Security

1. Configure session security in `config/session.php`:
   ```php
   return [
       'driver' => env('SESSION_DRIVER', 'redis'),
       'lifetime' => env('SESSION_LIFETIME', 120),
       'expire_on_close' => false,
       'encrypt' => true,
       'files' => storage_path('framework/sessions'),
       'connection' => env('SESSION_CONNECTION'),
       'table' => 'sessions',
       'store' => env('SESSION_STORE'),
       'lottery' => [2, 100],
       'cookie' => env(
           'SESSION_COOKIE',
           Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
       ),
       'path' => '/',
       'domain' => env('SESSION_DOMAIN'),
       'secure' => env('SESSION_SECURE_COOKIE', false),
       'http_only' => true,
       'same_site' => 'lax',
   ];
   ```

2. Configure cookie security in `config/sanctum.php`:
   ```php
   use Laravel\Sanctum\Sanctum;
   return [
       'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
           '%s%s',
           'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
           Sanctum::currentApplicationUrlWithPort(),
       ))),
       'guard' => ['web'],
       'expiration' => null,
       'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),
       'middleware' => [
           'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
           'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
           'validate_csrf_token' => Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
       ],
   ];
   ```

   > **Note:** For detailed Sanctum setup instructions, see [Laravel Sanctum Setup](060-configuration/050-sanctum-setup.md)

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: CSRF token mismatch errors

**Symptoms:**
- Form submissions fail with CSRF token mismatch errors
- AJAX requests fail with 419 status code

**Possible Causes:**
- CSRF token not included in the request
- Session expired
- Incorrect CSRF token handling in JavaScript

**Solutions:**
1. Ensure all forms include the `@csrf` directive
2. For AJAX requests, include the CSRF token in the headers
3. Check session configuration and lifetime

### Issue: Session data not persisting

**Symptoms:**
- User sessions expire unexpectedly
- Session data disappears between requests

**Possible Causes:**
- Redis connection issues
- Incorrect session driver configuration
- Session cookie settings issues

**Solutions:**
1. Verify Redis connection settings
2. Check session driver configuration in `.env` and `config/session.php`
3. Ensure session cookie settings are correct

### Issue: Content Security Policy blocking resources

**Symptoms:**
- JavaScript or CSS resources not loading
- Console errors about CSP violations

**Possible Causes:**
- Overly restrictive CSP rules
- Missing directives for required resources
- Inline scripts or styles blocked by CSP

**Solutions:**
1. Review CSP rules and add necessary directives
2. Consider using nonces for inline scripts
3. Use the `report-only` mode to debug CSP issues

</details>

## Related Documents

- [Database Migrations](040-database/030-database-migrations.md) - For database setup and migrations
- [Laravel Sanctum Setup](060-configuration/050-sanctum-setup.md) - For detailed Sanctum setup instructions
- [Security Configuration Details](050-security-testing/040-security-configuration.md) - For advanced security configuration

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [Database Migrations](040-database/030-database-migrations.md) | **Next Step:** [Testing Environment Setup](050-security-testing/020-testing-setup.md)
