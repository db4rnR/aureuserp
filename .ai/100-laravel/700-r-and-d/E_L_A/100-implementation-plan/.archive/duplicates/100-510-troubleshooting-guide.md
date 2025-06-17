# Troubleshooting Guide

**Version:** 1.0.1
**Date:** 2025-05-17
**Author:** AI Assistant
**Status:** Updated
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Installation Issues](#installation-issues)
- [Database Issues](#database-issues)
- [Authentication Issues](#authentication-issues)
- [Frontend Issues](#frontend-issues)
- [Performance Issues](#performance-issues)
- [Deployment Issues](#deployment-issues)
- [Common Error Codes](#common-error-codes)
- [Debugging Techniques](#debugging-techniques)
</details>

## Overview

This troubleshooting guide provides solutions for common issues that may arise during the development, deployment, and operation of the Enhanced Laravel Application. Each section covers a specific area of the application and includes problem descriptions, potential causes, and step-by-step solutions.

## Installation Issues

<details>
<summary><strong>Composer Dependencies Fail to Install</strong></summary>

**Problem:** When running `composer install`, you encounter errors about package conflicts or PHP version requirements.

**Potential Causes:**
- Incompatible PHP version
- Conflicting package requirements
- Memory limitations

**Solutions:**

1. **Verify PHP Version:**
   ```bash
   php -v
   ```
   Ensure you're using PHP 8.4.x as required by Laravel 12.

2. **Update Composer:**
   ```bash
   composer self-update
   ```

3. **Increase Memory Limit:**
   ```bash
   COMPOSER_MEMORY_LIMIT=-1 composer install
   ```

4. **Clear Composer Cache:**
   ```bash
   composer clear-cache
   ```

5. **Install with Verbose Output for Debugging:**
   ```bash
   composer install -v
   ```
</details>

<details>
<summary><strong>Node.js Dependencies Fail to Install</strong></summary>

**Problem:** When running `npm install`, you encounter errors about package conflicts or version requirements.

**Potential Causes:**
- Incompatible Node.js version
- Conflicting package requirements
- Network issues

**Solutions:**

1. **Verify Node.js Version:**
   ```bash
   node -v
   ```
   Ensure you're using Node.js 20.x as recommended.

2. **Clear npm Cache:**
   ```bash
   npm cache clean --force
   ```

3. **Use Clean Install:**
   ```bash
   npm ci
   ```

4. **Update npm:**
   ```bash
   npm install -g npm@latest
   ```

5. **Check for Network Issues:**
   ```bash
   npm config set registry https://registry.npmjs.org/
   ```
</details>

<details>
<summary><strong>Laravel Sail Issues</strong></summary>

**Problem:** Laravel Sail fails to start or encounters Docker-related errors.

**Potential Causes:**
- Docker not running
- Port conflicts
- Permission issues

**Solutions:**

1. **Verify Docker is Running:**
   ```bash
   docker info
   ```

2. **Check for Port Conflicts:**
   ```bash
   sudo lsof -i :80
   sudo lsof -i :3306
   ```

3. **Fix Permission Issues:**
   ```bash
   chmod -R 777 storage bootstrap/cache
   ```

4. **Rebuild Sail Containers:**
   ```bash
   ./vendor/bin/sail down --rmi all -v
   ./vendor/bin/sail up -d
   ```

5. **Check Sail Logs:**
   ```bash
   ./vendor/bin/sail logs
   ```
</details>

## Database Issues

<details>
<summary><strong>Migration Failures</strong></summary>

**Problem:** Database migrations fail to run with errors.

**Potential Causes:**
- Database connection issues
- Syntax errors in migration files
- Conflicts with existing tables

**Solutions:**

1. **Verify Database Connection:**
   ```bash
   php artisan db:monitor
   ```

2. **Check Migration Status:**
   ```bash
   php artisan migrate:status
   ```

3. **Reset Migrations (Development Only):**
   ```bash
   php artisan migrate:fresh
   ```

4. **Run Specific Migration:**
   ```bash
   php artisan migrate --path=/database/migrations/specific_migration.php
   ```

5. **Debug Migration with SQL Output:**
   ```bash
   php artisan migrate --pretend
   ```
</details>

<details>
<summary><strong>PostgreSQL Schema Issues</strong></summary>

**Problem:** Application can't find tables in the PostgreSQL schema.

**Potential Causes:**
- Incorrect schema configuration
- Missing schema creation
- Permission issues

**Solutions:**

1. **Verify Schema Configuration in database.php:**
   ```php
   'pgsql' => [
       // ...
       'search_path' => env('DB_SCHEMA', 'public'),
       // ...
   ],
   ```

2. **Create Schema if Missing:**
   ```sql
   CREATE SCHEMA IF NOT EXISTS your_schema_name;
   ```

3. **Grant Permissions to Schema:**
   ```sql
   GRANT ALL ON SCHEMA your_schema_name TO your_db_user;
   ```

4. **Set Default Schema for Session:**
   ```sql
   SET search_path TO your_schema_name;
   ```

5. **Check Current Schema:**
   ```sql
   SHOW search_path;
   ```
</details>

<details>
<summary><strong>Query Performance Issues</strong></summary>

**Problem:** Database queries are slow or causing timeouts.

**Potential Causes:**
- Missing indexes
- Inefficient queries
- Large data sets

**Solutions:**

1. **Enable Query Logging:**
   ```php
   DB::enableQueryLog();
   // Run your code
   dd(DB::getQueryLog());
   ```

2. **Add Missing Indexes:**
   ```php
   // In migration
   $table->index('column_name');
   ```

3. **Optimize Eager Loading:**
   ```php
   $users = User::with('posts', 'teams')->get();
   ```

4. **Use Chunking for Large Datasets:**
   ```php
   User::chunk(100, function ($users) {
       foreach ($users as $user) {
           // Process user
       }
   });
   ```

5. **Analyze Query Performance:**
   ```sql
   EXPLAIN ANALYZE SELECT * FROM users WHERE email LIKE '%example%';
   ```
</details>

## Authentication Issues

<details>
<summary><strong>Login Failures</strong></summary>

**Problem:** Users cannot log in despite correct credentials.

**Potential Causes:**
- Session configuration issues
- Cookie problems
- Fortify configuration

**Solutions:**

1. **Check Session Configuration:**
   ```php
   // config/session.php
   'driver' => env('SESSION_DRIVER', 'file'),
   'domain' => env('SESSION_DOMAIN', null),
   'secure' => env('SESSION_SECURE_COOKIE', true),
   ```

2. **Clear Session Cache:**
   ```bash
   php artisan session:clear
   ```

3. **Verify Fortify Configuration:**
   ```php
   // config/fortify.php
   'views' => true,
   'features' => [
       Features::registration(),
       Features::resetPasswords(),
       Features::emailVerification(),
       Features::updateProfileInformation(),
       Features::updatePasswords(),
       Features::twoFactorAuthentication(),
   ],
   ```

4. **Check CSRF Protection:**
   ```php
   // Ensure forms include CSRF token
   @csrf
   ```

5. **Debug Authentication Attempts:**
   ```php
   // Add logging in AuthenticatesUsers trait
   protected function attemptLogin(Request $request)
   {
       Log::info('Login attempt', ['email' => $request->email]);
       return $this->guard()->attempt(
           $this->credentials($request), $request->filled('remember')
       );
   }
   ```
</details>

<details>
<summary><strong>Two-Factor Authentication Issues</strong></summary>

**Problem:** Two-factor authentication not working correctly.

**Potential Causes:**
- Incorrect configuration
- Session issues
- Time synchronization problems

**Solutions:**

1. **Verify Fortify 2FA Configuration:**
   ```php
   // config/fortify.php
   'features' => [
       // ...
       Features::twoFactorAuthentication([
           'confirmPassword' => true,
       ]),
   ],
   ```

2. **Check Server Time Synchronization:**
   ```bash
   date
   ```
   Ensure server time is accurate for TOTP codes.

3. **Reset User's 2FA (Admin Only):**
   ```php
   $user->forceFill([
       'two_factor_secret' => null,
       'two_factor_recovery_codes' => null,
   ])->save();
   ```

4. **Verify QR Code Generation:**
   ```php
   // Check SVG generation in TwoFactorQrCodeController
   ```

5. **Debug 2FA Confirmation Process:**
   ```php
   // Add logging in TwoFactorAuthenticationController
   ```
</details>

<details>
<summary><strong>Password Reset Issues</strong></summary>

**Problem:** Password reset emails not being received or reset process failing.

**Potential Causes:**
- Email configuration issues
- Token expiration
- Route configuration

**Solutions:**

1. **Verify Mail Configuration:**
   ```php
   // config/mail.php
   'mailers' => [
       'smtp' => [
           'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
           'port' => env('MAIL_PORT', 587),
           // ...
       ],
   ],
   ```

2. **Test Email Sending:**
   ```bash
   php artisan mail:send TestMail user@example.com
   ```

3. **Check Password Reset Tokens Table:**
   ```sql
   SELECT * FROM password_reset_tokens WHERE email = 'user@example.com';
   ```

4. **Verify Password Reset Routes:**
   ```php
   // routes/web.php or config/fortify.php
   ```

5. **Increase Token Lifetime (if needed):**
   ```php
   // config/auth.php
   'passwords' => [
       'users' => [
           // ...
           'expire' => 60, // minutes
       ],
   ],
   ```
</details>

## Frontend Issues

<details>
<summary><strong>Livewire Component Errors</strong></summary>

**Problem:** Livewire components fail to load or update.

**Potential Causes:**
- JavaScript errors
- Component class issues
- Rendering problems

**Solutions:**

1. **Check Browser Console for Errors:**
   ```javascript
   // Look for JavaScript errors in browser console
   ```

2. **Enable Livewire Debugging:**
   ```php
   // config/livewire.php
   'debug' => env('APP_DEBUG', false),
   ```

3. **Clear Livewire Cache:**
   ```bash
   php artisan livewire:discover
   ```

4. **Verify Component Registration:**
   ```php
   // Check if component is properly registered
   ```

5. **Test Component in Isolation:**
   ```php
   // Create a test page with only the problematic component
   ```
</details>

<details>
<summary><strong>Volt Component Issues</strong></summary>

**Problem:** Volt components not rendering or updating correctly.

**Potential Causes:**
- Syntax errors in Volt files
- Compilation issues
- State management problems

**Solutions:**

1. **Check Volt Syntax:**
   ```php
   // Ensure Volt file follows correct syntax
   ```

2. **Clear View Cache:**
   ```bash
   php artisan view:clear
   ```

3. **Verify Volt Configuration:**
   ```php
   // config/volt.php
   ```

4. **Debug Component State:**
   ```php
   // Add debugging in component
   <?php

   use function Livewire\Volt\state;

   state(['count' => 0]);

   $increment = function () {
       dump($this->count); // Debug current state
       $this->count++;
   };
   ?>

   <div>
       <p>{{ $count }}</p>
       <button wire:click="increment">+</button>
   </div>
   ```

5. **Check for JavaScript Conflicts:**
   ```javascript
   // Look for conflicts in browser console
   ```
</details>

<details>
<summary><strong>Tailwind CSS Issues</strong></summary>

**Problem:** Tailwind styles not applying correctly.

**Potential Causes:**
- Configuration issues
- Build process problems
- Class conflicts

**Solutions:**

1. **Verify Tailwind Configuration:**
   ```javascript
   // tailwind.config.js
   export default {
       content: [
           './resources/**/*.blade.php',
           './resources/**/*.js',
           './resources/**/*.vue',
       ],
       // ...
   }
   ```

2. **Rebuild Assets:**
   ```bash
   npm run build
   ```

3. **Check for Purging Issues:**
   ```javascript
   // Ensure classes are not being purged
   ```

4. **Use Tailwind Inspector in Browser:**
   ```
   // Install Tailwind CSS Inspector browser extension
   ```

5. **Debug with Explicit Classes:**
   ```html
   <!-- Add !important to debug -->
   <div class="bg-red-500 !important">Test</div>
   ```
</details>

## Performance Issues

<details>
<summary><strong>Slow Page Load Times</strong></summary>

**Problem:** Pages take too long to load.

**Potential Causes:**
- Inefficient queries
- Missing caching
- Large asset files
- N+1 query problems

**Solutions:**

1. **Enable Query Debugging:**
   ```php
   // AppServiceProvider.php
   DB::listen(function ($query) {
       Log::info($query->sql, [
           'bindings' => $query->bindings,
           'time' => $query->time
       ]);
   });
   ```

2. **Implement Caching:**
   ```php
   $value = Cache::remember('users', 3600, function () {
       return User::all();
   });
   ```

3. **Fix N+1 Query Problems:**
   ```php
   // Instead of:
   $posts = Post::all();
   foreach ($posts as $post) {
       echo $post->user->name;
   }

   // Use:
   $posts = Post::with('user')->get();
   foreach ($posts as $post) {
       echo $post->user->name;
   }
   ```

4. **Optimize Asset Loading:**
   ```php
   // Use asset bundling and minification
   npm run build
   ```

5. **Enable Route Caching:**
   ```bash
   php artisan route:cache
   php artisan view:cache
   php artisan config:cache
   ```
</details>

<details>
<summary><strong>Memory Exhaustion</strong></summary>

**Problem:** Application crashes with memory exhaustion errors.

**Potential Causes:**
- Large data processing
- Memory leaks
- Inefficient algorithms

**Solutions:**

1. **Increase PHP Memory Limit (Temporary Solution):**
   ```php
   // php.ini
   memory_limit = 512M
   ```

2. **Use Chunking for Large Datasets:**
   ```php
   User::chunk(100, function ($users) {
       foreach ($users as $user) {
           // Process user
       }
   });
   ```

3. **Implement Queued Jobs for Heavy Processing:**
   ```php
   ProcessLargeDataJob::dispatch($data);
   ```

4. **Profile Memory Usage:**
   ```php
   $initialMemory = memory_get_usage();
   // Your code
   $peakMemory = memory_get_peak_usage();
   Log::info("Memory used: " . ($peakMemory - $initialMemory) / 1024 / 1024 . " MB");
   ```

5. **Check for Memory Leaks:**
   ```php
   // Use tools like Blackfire.io for profiling
   ```
</details>

<details>
<summary><strong>Redis Connection Issues</strong></summary>

**Problem:** Application fails to connect to Redis for caching or queues.

**Potential Causes:**
- Incorrect configuration
- Redis server down
- Network issues

**Solutions:**

1. **Verify Redis Configuration:**
   ```php
   // config/database.php
   'redis' => [
       'client' => env('REDIS_CLIENT', 'phpredis'),
       'default' => [
           'host' => env('REDIS_HOST', '127.0.0.1'),
           'password' => env('REDIS_PASSWORD', null),
           'port' => env('REDIS_PORT', 6379),
           'database' => env('REDIS_DB', 0),
       ],
   ],
   ```

2. **Test Redis Connection:**
   ```bash
   redis-cli ping
   ```

3. **Check Redis Service Status:**
   ```bash
   systemctl status redis
   ```

4. **Implement Connection Retry Logic:**
   ```php
   try {
       Redis::connection()->ping();
   } catch (\Exception $e) {
       Log::error('Redis connection failed: ' . $e->getMessage());
       // Fallback to file cache
       config(['cache.default' => 'file']);
   }
   ```

5. **Monitor Redis Performance:**
   ```bash
   redis-cli info
   ```
</details>

## Deployment Issues

<details>
<summary><strong>FrankenPHP Configuration Issues</strong></summary>

**Problem:** FrankenPHP server not starting or serving the application correctly.

**Potential Causes:**
- Configuration errors
- Permission issues
- PHP extension problems

**Solutions:**

1. **Verify FrankenPHP Configuration:**
   ```toml
   # Caddy file or equivalent
   {
       frankenphp
   }

   your-domain.com {
       root * /var/www/enhanced-laravel-application/public
       php_server
   }
   ```

2. **Check FrankenPHP Logs:**
   ```bash
   journalctl -u frankenphp
   ```

3. **Verify PHP Extensions:**
   ```bash
   php -m
   ```
   Ensure all required extensions are installed.

4. **Check File Permissions:**
   ```bash
   chown -R www-data:www-data /var/www/enhanced-laravel-application
   chmod -R 755 /var/www/enhanced-laravel-application
   chmod -R 777 /var/www/enhanced-laravel-application/storage
   chmod -R 777 /var/www/enhanced-laravel-application/bootstrap/cache
   ```

5. **Test with Built-in PHP Server (Temporary):**
   ```bash
   php -S localhost:8000 -t public
   ```
</details>

<details>
<summary><strong>Environment Configuration Issues</strong></summary>

**Problem:** Application behaves differently in production than in development.

**Potential Causes:**
- Missing environment variables
- Incorrect .env configuration
- Cache issues

**Solutions:**

1. **Verify Environment Variables:**
   ```bash
   # On server
   printenv | grep APP_
   ```

2. **Check .env File:**
   ```
   APP_ENV=production
   APP_DEBUG=false
   ```

3. **Clear Configuration Cache:**
   ```bash
   php artisan config:clear
   ```

4. **Regenerate Application Key (if needed):**
   ```bash
   php artisan key:generate
   ```

5. **Verify Configuration Values:**
   ```php
   // Add temporary debug code
   dd(config('app.debug'), config('app.env'));
   ```
</details>

<details>
<summary><strong>CI/CD Pipeline Failures</strong></summary>

**Problem:** GitHub Actions or other CI/CD pipelines failing during deployment.

**Potential Causes:**
- Test failures
- Build errors
- Environment issues

**Solutions:**

1. **Check GitHub Actions Logs:**
   ```
   // Review detailed logs in GitHub Actions UI
   ```

2. **Run Tests Locally:**
   ```bash
   php artisan test
   ```

3. **Verify Deployment Script:**
   ```yaml
   # .github/workflows/deploy.yml
   # Check for syntax errors or missing steps
   ```

4. **Test Deployment Manually:**
   ```bash
   # Run deployment steps manually to identify issues
   ```

5. **Add Debugging to CI/CD Pipeline:**
   ```yaml
   - name: Debug Environment
     run: |
       php -v
       composer --version
       printenv
   ```
</details>

## Common Error Codes

<details>
<summary><strong>HTTP 500 - Internal Server Error</strong></summary>

**Problem:** Server returns a 500 error.

**Potential Causes:**
- PHP errors
- Database connection issues
- Permission problems

**Solutions:**

1. **Check Laravel Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check Web Server Logs:**
   ```bash
   tail -f /var/log/nginx/error.log
   ```

3. **Enable Detailed Error Reporting (Development Only):**
   ```php
   // .env
   APP_DEBUG=true
   ```

4. **Verify File Permissions:**
   ```bash
   chmod -R 755 /var/www/enhanced-laravel-application
   chmod -R 777 /var/www/enhanced-laravel-application/storage
   ```

5. **Check for Syntax Errors:**
   ```bash
   php -l path/to/suspected/file.php
   ```
</details>

<details>
<summary><strong>HTTP 403 - Forbidden</strong></summary>

**Problem:** Server returns a 403 error.

**Potential Causes:**
- Permission issues
- Authorization failures
- Web server configuration

**Solutions:**

1. **Check File Permissions:**
   ```bash
   ls -la /var/www/enhanced-laravel-application/public
   ```

2. **Verify Web Server Configuration:**
   ```
   # Nginx config
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```

3. **Check Authorization Logic:**
   ```php
   // Review policies and middleware
   ```

4. **Test with Different User:**
   ```php
   // Try accessing with a user that has different permissions
   ```

5. **Check .htaccess (if using Apache):**
   ```
   # Ensure .htaccess is properly configured
   ```
</details>

<details>
<summary><strong>HTTP 404 - Not Found</strong></summary>

**Problem:** Server returns a 404 error for existing routes.

**Potential Causes:**
- Route not defined
- Web server configuration
- Cached routes

**Solutions:**

1. **List All Routes:**
   ```bash
   php artisan route:list
   ```

2. **Clear Route Cache:**
   ```bash
   php artisan route:clear
   ```

3. **Verify Web Server Configuration:**
   ```
   # Check if requests are properly forwarded to index.php
   ```

4. **Check Route Definition:**
   ```php
   // routes/web.php or routes/api.php
   // Ensure route is defined with correct HTTP method
   ```

5. **Test with Explicit Route:**
   ```php
   Route::get('/test-route', function () {
       return 'Route works!';
   });
   ```
</details>

## Debugging Techniques

<details>
<summary><strong>Using Laravel Telescope</strong></summary>

**Problem:** Need detailed insights into application behavior.

**Solution:**

1. **Install Laravel Telescope:**
   ```bash
   composer require laravel/telescope:"^5.0" --dev
   php artisan telescope:install
   php artisan migrate
   ```

2. **Access Telescope Dashboard:**
   ```
   https://your-app.test/telescope
   ```

3. **Monitor Specific Areas:**
   - Requests
   - Commands
   - Queries
   - Cache operations
   - Redis operations
   - Jobs & queued jobs
   - Logs

4. **Filter Telescope Data:**
   ```php
   // Filter by tag, user, etc.
   ```

5. **Configure Telescope for Production (if needed):**
   ```php
   // config/telescope.php
   'enabled' => env('TELESCOPE_ENABLED', true),
   ```
</details>

<details>
<summary><strong>Using Laravel Debugbar</strong></summary>

**Problem:** Need real-time debugging information in the browser.

**Solution:**

1. **Install Laravel Debugbar:**
   ```bash
   composer require barryvdh/laravel-debugbar:"^3.9" --dev
   ```

2. **Configure Debugbar:**
   ```php
   // config/debugbar.php
   'enabled' => env('DEBUGBAR_ENABLED', true),
   ```

3. **Use Custom Messages:**
   ```php
   Debugbar::info('Info message');
   Debugbar::error('Error message');
   Debugbar::warning('Warning message');
   Debugbar::addMeasure('My measure', LARAVEL_START, microtime(true));
   ```

4. **Monitor Specific Areas:**
   - Queries
   - Request data
   - Session data
   - Views
   - Events

5. **Disable in Production:**
   ```php
   // .env
   DEBUGBAR_ENABLED=false
   ```
</details>

<details>
<summary><strong>Using Laravel Tinker</strong></summary>

**Problem:** Need to interactively test code and queries.

**Solution:**

1. **Launch Tinker:**
   ```bash
   php artisan tinker
   ```

2. **Query Models:**
   ```php
   User::find(1);
   User::where('email', 'like', '%example%')->get();
   ```

3. **Test Relationships:**
   ```php
   $user = User::find(1);
   $user->posts;
   ```

4. **Create Records:**
   ```php
   $user = new User;
   $user->name = 'Test User';
   $user->email = 'test@example.com';
   $user->password = Hash::make('password');
   $user->save();
   ```

5. **Test Helper Functions:**
   ```php
   Str::slug('Test String');
   ```
</details>

This troubleshooting guide will be regularly updated as new issues and solutions are identified. If you encounter an issue not covered in this guide, please document the problem and solution to help other team members.
