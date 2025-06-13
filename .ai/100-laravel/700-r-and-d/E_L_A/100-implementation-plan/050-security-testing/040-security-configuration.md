# Phase 1: Phase 0.16: Security Configuration Details

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
- [Step 1: Configure CORS](#step-1-configure-cors)
- [Step 2: Configure CSRF Protection](#step-2-configure-csrf-protection)
- [Step 3: Configure Cookie Settings](#step-3-configure-cookie-settings)
- [Step 4: Configure Content Security Policy](#step-4-configure-content-security-policy)
- [Step 5: Configure Security Headers](#step-5-configure-security-headers)
- [Step 6: Test Security Configuration](#step-6-test-security-configuration)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides detailed instructions for configuring security settings for the Enhanced Laravel Application (ELA). It covers CORS, CSRF protection, cookie settings, content security policy, and security headers.

> **Reference:** [Laravel 12.x Security Documentation](https:/laravel.com/docs/12.x/csrf) and [Laravel 12.x CORS Documentation](https:/laravel.com/docs/12.x/routing#cors)

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Security Setup](050-security-testing/010-security-setup.md) completed
- [Custom AppServiceProvider](060-configuration/010-app-service-provider.md) completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed

### Required Knowledge
- Basic understanding of web security concepts
- Familiarity with CORS, CSRF, and CSP
- Understanding of HTTP security headers

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x
- Access to the application's configuration files

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Configure CORS | 10 minutes |
| Configure CSRF Protection | 10 minutes |
| Configure Cookie Settings | 10 minutes |
| Configure Content Security Policy | 15 minutes |
| Configure Security Headers | 15 minutes |
| Test Security Configuration | 15 minutes |
| **Total** | **75 minutes** |

> **Note:** These time estimates assume familiarity with web security concepts. Actual time may vary based on experience level and the complexity of your security requirements.

## Step 1: Configure CORS

1. Update the CORS configuration in `config/cors.php`:
   ```php
   <?php

   return [
       /*
       |--------------------------------------------------------------------------
       | Cross-Origin Resource Sharing (CORS) Configuration
       |--------------------------------------------------------------------------
       |
       | Here you may configure your settings for cross-origin resource sharing
       | or "CORS". This determines what cross-origin operations may execute
       | in web browsers. You are free to adjust these settings as needed.
       |
       | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
       |
       */

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

2. Update the `.env` file with CORS configuration:
   ```
   FRONTEND_URL=http://localhost:3000
   ```

   > **Reference:** [Laravel 12.x CORS Documentation](https:/laravel.com/docs/12.x/routing#cors)

## Step 2: Configure CSRF Protection

1. Update the CSRF configuration in `app/Http/Middleware/VerifyCsrfToken.php`:
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
           // 'api/*',
           // 'webhook/*',
       ];
   }
   ```

2. Configure CSRF token handling in JavaScript:
   ```javascript
   // resources/js/bootstrap.js
   import axios from 'axios';

   window.axios = axios;
   window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
   window.axios.defaults.withCredentials = true;

   // Get CSRF token from meta tag
   const token = document.head.querySelector('meta[name="csrf-token"]');

   if (token) {
       window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
   } else {
       console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
   }
   ```

3. Include the CSRF token in all forms:
   ```php
   <form method="POST" action="/profile">
       @csrf
       ...
   </form>
   ```

   > **Reference:** [Laravel 12.x CSRF Protection Documentation](https:/laravel.com/docs/12.x/csrf)

## Step 3: Configure Cookie Settings

1. Update the session configuration in `config/session.php`:
   ```php
   'cookie' => env(
       'SESSION_COOKIE',
       Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
   ),

   'secure' => env('SESSION_SECURE_COOKIE', true),

   'http_only' => true,

   'same_site' => env('SESSION_SAME_SITE', 'lax'),
   ```

2. Update the cookie configuration in `app/Http/Middleware/EncryptCookies.php`:
   ```php
   <?php

   namespace App\Http\Middleware;

   use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

   class EncryptCookies extends Middleware
   {
       /**
        * The names of the cookies that should not be encrypted.
        *
        * @var array<int, string>
        */
       protected $except = [
           //
       ];
   }
   ```

3. Update the `.env` file with cookie configuration:
   ```
   SESSION_SECURE_COOKIE=true
   SESSION_SAME_SITE=lax
   ```

   > **Reference:** [Laravel 12.x Session Configuration Documentation](https:/laravel.com/docs/12.x/session#configuration)

## Step 4: Configure Content Security Policy

1. Create a Content Security Policy middleware:
   ```bash
   php artisan make:middleware ContentSecurityPolicy
   ```

2. Configure the middleware in `app/Http/Middleware/ContentSecurityPolicy.php`:
   ```php
   <?php

   namespace App\Http\Middleware;

   use Closure;
   use Illuminate\Http\Request;
   use Symfony\Component\HttpFoundation\Response;

   class ContentSecurityPolicy
   {
       /**
        * Handle an incoming request.
        */
       public function handle(Request $request, Closure $next): Response
       {
           $response = $next($request);

           $response->headers->set(
               'Content-Security-Policy',
               "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self' data:; connect-src 'self';"
           );

           return $response;
       }
   }
   ```

3. Register the middleware in `app/Http/Kernel.php`:
   ```php
   protected $middlewareGroups = [
       'web' => [
           // Other middleware...
           \App\Http\Middleware\ContentSecurityPolicy::class,
       ],
   ];
   ```

   > **Reference:** [Content Security Policy MDN Documentation](https:/developer.mozilla.org/en-US/docs/Web/HTTP/CSP)

## Step 5: Configure Security Headers

1. Create a Security Headers middleware:
   ```bash
   php artisan make:middleware SecurityHeaders
   ```

2. Configure the middleware in `app/Http/Middleware/SecurityHeaders.php`:
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
        */
       public function handle(Request $request, Closure $next): Response
       {
           $response = $next($request);

           $response->headers->set('X-XSS-Protection', '1; mode=block');
           $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
           $response->headers->set('X-Content-Type-Options', 'nosniff');
           $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
           $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');

           return $response;
       }
   }
   ```

3. Register the middleware in `app/Http/Kernel.php`:
   ```php
   protected $middlewareGroups = [
       'web' => [
           // Other middleware...
           \App\Http\Middleware\SecurityHeaders::class,
       ],
   ];
   ```

4. Configure HTTPS in production by updating the `AppServiceProvider`:
   ```php
   public function boot(): void
   {
       if ($this->app->environment('production')) {
           \URL::forceScheme('https');
       }
   }
   ```

   > **Reference:** [OWASP Secure Headers Project](https:/owasp.org/www-project-secure-headers)

## Step 6: Test Security Configuration

1. Use online security scanners to test your security configuration:
   - [Mozilla Observatory](https:/observatory.mozilla.org)
   - [Security Headers](https:/securityheaders.com)
   - [CSP Evaluator](https:/csp-evaluator.withgoogle.com)

2. Run the following command to check your security configuration:
   ```bash
   php artisan verify:security
   ```

3. Test CORS configuration by making cross-origin requests to your API endpoints.

4. Test CSRF protection by submitting forms without CSRF tokens.

5. Test cookie settings by inspecting cookies in the browser developer tools.

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: CORS errors in browser console

**Symptoms:**
- Browser console shows CORS errors
- API requests from different origins fail

**Possible Causes:**
- CORS configuration is too restrictive
- Missing required CORS headers
- Incorrect allowed origins

**Solutions:**
1. Check the `allowed_origins` setting in `config/cors.php`
2. Ensure all required headers are included in `allowed_headers`
3. Verify that the CORS middleware is properly registered

### Issue: CSRF token mismatch errors

**Symptoms:**
- Form submissions fail with CSRF token mismatch errors
- AJAX requests fail with 419 status code

**Possible Causes:**
- Missing CSRF token in forms
- Expired CSRF token
- Incorrect CSRF token handling in JavaScript

**Solutions:**
1. Ensure all forms include the `@csrf` directive
2. For AJAX requests, include the CSRF token in the headers
3. Check session configuration and lifetime

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

### Issue: Security headers not being applied

**Symptoms:**
- Security headers missing in HTTP responses
- Low scores on security header scanners

**Possible Causes:**
- Middleware not registered correctly
- Headers being overridden elsewhere
- Web server configuration issues

**Solutions:**
1. Verify the `SecurityHeaders` middleware is registered in `Kernel.php`
2. Check for conflicting headers in web server configuration
3. Ensure the middleware is in the correct order

</details>

## Related Documents

- [Security Setup](050-security-testing/010-security-setup.md) - For basic security setup
- [Custom AppServiceProvider](060-configuration/010-app-service-provider.md) - For AppServiceProvider configuration
- [Final Configuration](060-configuration/020-final-configuration.md) - For final configuration steps
- [Laravel Sanctum Setup](060-configuration/050-sanctum-setup.md) - For API authentication

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [Custom AppServiceProvider Configuration](060-configuration/010-app-service-provider.md) | **Next Step:** [Final Configuration and Verification](060-configuration/020-final-configuration.md)
