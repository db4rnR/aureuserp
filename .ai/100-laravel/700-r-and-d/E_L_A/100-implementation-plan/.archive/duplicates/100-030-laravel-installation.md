# Phase 0: Phase 0.3: Laravel Installation & Configuration

**Version:** 1.0.3
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
- [Step 1: Create New Laravel Project](#step-1-create-new-laravel-project)
- [Step 2: Configure Environment Variables](#step-2-configure-environment-variables)
- [Step 3: Configure Database Connection](#step-3-configure-database-connection)
- [Step 4: Configure Redis Connection](#step-4-configure-redis-connection)
- [Step 5: Configure Mail Settings](#step-5-configure-mail-settings)
- [Step 6: Configure Queue Settings](#step-6-configure-queue-settings)
- [Step 7: Configure Session Settings](#step-7-configure-session-settings)
- [Step 8: Configure Cache Settings](#step-8-configure-cache-settings)
- [Step 9: Configure Filesystem Settings](#step-9-configure-filesystem-settings)
- [Step 10: Configure Application Settings](#step-10-configure-application-settings)
- [Step 11: Run Initial Migrations](#step-11-run-initial-migrations)
- [Step 12: Set Up Version Control](#step-12-set-up-version-control)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides detailed instructions for installing and configuring Laravel 12 for the Enhanced Laravel Application (ELA). This includes creating a new Laravel project, configuring environment variables, setting up database connections, and preparing the application for development.

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Development Environment Setup](020-environment-setup/010-dev-environment-setup.md) completed

### Required Packages
- Composer installed and configured

### Required Knowledge
- Basic understanding of PHP and Laravel
- Familiarity with command-line operations

### Required Environment
- PHP 8.2 or higher
- Laravel Herd installed and configured
- MySQL or PostgreSQL database server

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Create New Laravel Project | 10 minutes |
| Configure Environment Variables | 15 minutes |
| Configure Database Connection | 10 minutes |
| Configure Redis Connection | 5 minutes |
| Configure Mail Settings | 5 minutes |
| Configure Queue Settings | 5 minutes |
| Configure Session Settings | 5 minutes |
| Configure Cache Settings | 5 minutes |
| Configure Filesystem Settings | 5 minutes |
| Configure Application Settings | 10 minutes |
| Run Initial Migrations | 5 minutes |
| Set Up Version Control | 10 minutes |
| **Total** | **90 minutes** |

> **Note:** These time estimates assume familiarity with Laravel and the required packages. Actual time may vary based on experience level and environment setup.

## Step 1: Create New Laravel Project

1. Open Terminal and navigate to your Herd projects directory:
   ```bash
   cd ~/Herd
   ```

2. Create a new Laravel 12 project:
   ```bash
   composer create-project laravel/laravel ela "12.*"
   ```

3. Navigate to the project directory:
   ```bash
   cd ela
   ```

4. Verify the Laravel installation:
   ```bash
   php artisan --version
   ```
   You should see output indicating Laravel 12.x

## Step 2: Configure Environment Variables

1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```

2. Generate an application key:
   ```bash
   php artisan key:generate
   ```

3. Open the `.env` file in your editor and update the following settings:
   ```
   APP_NAME="Enhanced Laravel Application"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://ela.test
   APP_TIMEZONE=UTC
   APP_LOCALE=en

   LOG_CHANNEL=stack
   LOG_DEPRECATIONS_CHANNEL=null
   LOG_LEVEL=debug
   ```

4. Create a detailed `.env.example` file that documents all environment variables:
   ```bash
   cp .env .env.example
   ```

5. Add comprehensive comments to the `.env.example` file to explain each variable:
   ```
   # Application Settings
   APP_NAME="Enhanced Laravel Application"  # The name of your application
   APP_ENV=local                            # The environment (local, staging, production)
   APP_KEY=                                 # The application encryption key (auto-generated)
   APP_DEBUG=true                           # Whether to show debug information (true/false)
   APP_URL=http://ela.test                  # The base URL of your application
   APP_TIMEZONE=UTC                         # The default timezone for your application
   APP_LOCALE=en                            # The default locale for your application

   # Logging Configuration
   LOG_CHANNEL=stack                        # The default log channel (stack, daily, slack, etc.)
   LOG_DEPRECATIONS_CHANNEL=null            # Channel for logging deprecation warnings
   LOG_LEVEL=debug                          # Minimum log level (debug, info, warning, error, critical)

   # Database Configuration
   DB_CONNECTION=pgsql                      # Database driver (pgsql, mysql, sqlite, sqlsrv)
   DB_HOST=127.0.0.1                        # Database host
   DB_PORT=5432                             # Database port
   DB_DATABASE=ela_development              # Database name
   DB_USERNAME=postgres                     # Database username
   DB_PASSWORD=                             # Database password
   DB_SCHEMA=public                         # PostgreSQL schema

   # Redis Configuration
   REDIS_HOST=127.0.0.1                     # Redis host
   REDIS_PASSWORD=null                      # Redis password
   REDIS_PORT=6379                          # Redis port
   REDIS_CLIENT=phpredis                    # Redis client (phpredis, predis)

   # Mail Configuration
   MAIL_MAILER=smtp                         # Mail driver (smtp, sendmail, mailgun, ses, etc.)
   MAIL_HOST=127.0.0.1                      # Mail host
   MAIL_PORT=1025                           # Mail port
   MAIL_USERNAME=null                       # Mail username
   MAIL_PASSWORD=null                       # Mail password
   MAIL_ENCRYPTION=null                     # Mail encryption (tls, ssl, null)
   MAIL_FROM_ADDRESS="noreply@example.com"  # Default from address
   MAIL_FROM_NAME="${APP_NAME}"             # Default from name

   # Queue Configuration
   QUEUE_CONNECTION=redis                   # Queue driver (sync, database, redis, etc.)
   REDIS_QUEUE=default                      # Default queue name for Redis

   # Session Configuration
   SESSION_DRIVER=redis                     # Session driver (file, cookie, database, redis, etc.)
   SESSION_LIFETIME=120                     # Session lifetime in minutes

   # Cache Configuration
   CACHE_DRIVER=redis                       # Cache driver (file, database, redis, etc.)

   # Filesystem Configuration
   FILESYSTEM_DISK=local                    # Default filesystem disk (local, public, s3, etc.)

   # Broadcasting Configuration
   BROADCAST_DRIVER=reverb                  # Broadcasting driver (reverb, pusher, redis, etc.)
   REVERB_APP_ID=ela                        # Reverb application ID
   REVERB_APP_KEY=                          # Reverb application key
   REVERB_APP_SECRET=                       # Reverb application secret
   ```

   > **Reference:** [Laravel 12.x Configuration Documentation](https:/laravel.com/docs/12.x/configuration#environment-configuration)

## Step 3: Configure Database Connection

1. Update the database settings in the `.env` file:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=ela_development
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   DB_SCHEMA=public
   ```

   Replace `your_username` and `your_password` with your PostgreSQL credentials. The `DB_SCHEMA` variable allows you to configure which schema to use, with `public` as the default.

2. Configure the testing database in the `.env.testing` file:
   ```bash
   cp .env .env.testing
   ```

3. Update the testing database settings in the `.env.testing` file. For testing, we'll use SQLite in-memory database:
   ```
   APP_ENV=testing

   DB_CONNECTION=sqlite
   DB_DATABASE=:memory:
   ```

   Alternatively, if you prefer to use PostgreSQL for testing as well:
   ```
   APP_ENV=testing

   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=ela_testing
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   DB_SCHEMA=public
   ```

4. Update the `config/database.php` file to ensure PostgreSQL is properly configured with a configurable schema:
   ```php
   // PostgreSQL configuration with schema support
   // This allows for multi-tenant applications or organization of database objects
   'pgsql' => [
       'driver' => 'pgsql',                           // Database driver
       'url' => env('DATABASE_URL'),                  // Database URL (takes precedence if specified)
       'host' => env('DB_HOST', '127.0.0.1'),         // Database host
       'port' => env('DB_PORT', '5432'),              // PostgreSQL default port
       'database' => env('DB_DATABASE', 'forge'),     // Database name
       'username' => env('DB_USERNAME', 'forge'),     // Database username
       'password' => env('DB_PASSWORD', ''),          // Database password
       'charset' => 'utf8',                           // Character set
       'prefix' => '',                                // Table prefix (empty for most applications)
       'prefix_indexes' => true,                      // Whether to prefix indexes
       'search_path' => env('DB_SCHEMA', 'public'),   // PostgreSQL schema (configurable via .env)
       'sslmode' => 'prefer',                         // SSL connection preference
   ],
   ```

   > **Reference:** [Laravel 12.x Database Configuration Documentation](https:/laravel.com/docs/12.x/database#configuration)

## Step 4: Configure Redis Connection

1. Update the Redis settings in the `.env` file:
   ```
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379
   REDIS_CLIENT=phpredis
   ```

2. Update the `config/database.php` file to ensure Redis is properly configured:
   ```php
   /**
    * Redis Database Configuration
    *
    * This configuration sets up Redis connections for different purposes:
    * - default: Used for general Redis operations and queues
    * - cache: Used specifically for the cache system
    */
   'redis' => [
       // Redis client (phpredis is recommended for performance)
       'client' => env('REDIS_CLIENT', 'phpredis'),

       // Global Redis options
       'options' => [
           // Cluster configuration for Redis Cluster setups
           'cluster' => env('REDIS_CLUSTER', 'redis'),

           // Key prefix to avoid collisions in shared Redis instances
           'prefix' => env('REDIS_PREFIX', 'ela_database_'),
       ],

       // Default connection used for general purposes
       'default' => [
           'url' => env('REDIS_URL'),                    // Redis connection URL (if provided)
           'host' => env('REDIS_HOST', '127.0.0.1'),      // Redis server host
           'username' => env('REDIS_USERNAME'),           // Redis username (Redis 6.0+)
           'password' => env('REDIS_PASSWORD'),           // Redis password
           'port' => env('REDIS_PORT', '6379'),           // Redis server port
           'database' => env('REDIS_DB', '0'),            // Redis database index
       ],

       // Cache connection used specifically for Laravel's cache system
       'cache' => [
           'url' => env('REDIS_URL'),                    // Redis connection URL (if provided)
           'host' => env('REDIS_HOST', '127.0.0.1'),      // Redis server host
           'username' => env('REDIS_USERNAME'),           // Redis username (Redis 6.0+)
           'password' => env('REDIS_PASSWORD'),           // Redis password
           'port' => env('REDIS_PORT', '6379'),           // Redis server port
           'database' => env('REDIS_CACHE_DB', '1'),      // Separate database index for cache
       ],
   ],
   ```

   > **Reference:** [Laravel 12.x Redis Documentation](https:/laravel.com/docs/12.x/redis#configuration)

## Step 5: Configure Mail Settings

1. Update the mail settings in the `.env` file for local development:
   ```
   MAIL_MAILER=log
   MAIL_HOST=127.0.0.1
   MAIL_PORT=1025
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS="noreply@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```

   > **Reference:** [Laravel 12.x Mail Documentation](https:/laravel.com/docs/12.x/mail#configuration)

2. For local email testing, you can use Mailpit. Install it if not already installed:
   ```bash
   brew install mailpit
   ```

3. Start Mailpit:
   ```bash
   mailpit
   ```

4. Update the mail settings in the `.env` file to use Mailpit:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=127.0.0.1
   MAIL_PORT=1025
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   ```

   > **Reference:** [Laravel 12.x Mail Local Development Documentation](https:/laravel.com/docs/12.x/mail#mail-and-local-development)

## Step 6: Configure Queue Settings

1. Update the queue settings in the `.env` file:
   ```
   QUEUE_CONNECTION=redis
   ```

2. Update the `config/queue.php` file to ensure Redis queues are properly configured:
   ```php
   'default' => env('QUEUE_CONNECTION', 'redis'),
   'connections' => [
       'redis' => [
           'driver' => 'redis',
           'connection' => 'default',
           'queue' => env('REDIS_QUEUE', 'default'),
           'retry_after' => 90,
           'block_for' => null,
           'after_commit' => false,
       ],
   ],
   ```

   > **Reference:** [Laravel 12.x Queue Documentation](https:/laravel.com/docs/12.x/queues#configuration)

## Step 7: Configure Session Settings

1. Update the session settings in the `.env` file:
   ```
   SESSION_DRIVER=redis
   SESSION_LIFETIME=120
   ```

2. Update the `config/session.php` file to ensure Redis sessions are properly configured:
   ```php
   'driver' => env('SESSION_DRIVER', 'redis'),
   'lifetime' => env('SESSION_LIFETIME', 120),
   'encrypt' => true,
   'cookie' => env(
       'SESSION_COOKIE',
       Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
   ),
   ```

   > **Reference:** [Laravel 12.x Session Documentation](https:/laravel.com/docs/12.x/session#configuration)

## Step 8: Configure Cache Settings

1. Update the cache settings in the `.env` file:
   ```
   CACHE_DRIVER=redis
   ```

2. Update the `config/cache.php` file to ensure Redis cache is properly configured:
   ```php
   'default' => env('CACHE_DRIVER', 'redis'),
   'stores' => [
       'redis' => [
           'driver' => 'redis',
           'connection' => 'cache',
           'lock_connection' => 'default',
       ],
       'database' => [
           'driver' => 'database',
           'table' => 'cache',
           'connection' => null,
           'lock_connection' => null,
       ],
       'file' => [
           'driver' => 'file',
           'path' => storage_path('framework/cache/data'),
           'lock_path' => storage_path('framework/cache/data'),
       ],
       'memcached' => [
           'driver' => 'memcached',
           'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
           'sasl' => [
               env('MEMCACHED_USERNAME'),
               env('MEMCACHED_PASSWORD'),
           ],
           'options' => [
               // Memcached::OPT_CONNECT_TIMEOUT => 2000,
           ],
           'servers' => [
               [
                   'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                   'port' => env('MEMCACHED_PORT', 11211),
                   'weight' => 100,
               ],
           ],
       ],
   ],
   ```

   > **Reference:** [Laravel 12.x Cache Documentation](https:/laravel.com/docs/12.x/cache#configuration)

## Step 9: Configure Filesystem Settings

1. Update the filesystem settings in the `.env` file:
   ```
   FILESYSTEM_DISK=local
   ```

2. Update the `config/filesystems.php` file to configure the disks:
   ```php
   'disks' => [
       'local' => [
           'driver' => 'local',
           'root' => storage_path('app/private'),
           'throw' => false,
       ],
       'public' => [
           'driver' => 'local',
           'root' => storage_path('app/public'),
           'url' => env('APP_URL').'/storage',
           'visibility' => 'public',
           'throw' => false,
       ],
       'media' => [
           'driver' => 'local',
           'root' => storage_path('app/media'),
           'url' => env('APP_URL').'/media',
           'visibility' => 'public',
           'throw' => false,
       ],
   ],
   'links' => [
       public_path('storage') => storage_path('app/public'),
       public_path('media') => storage_path('app/media'),
   ],
   ```

   > **Reference:** [Laravel 12.x File Storage Documentation](https:/laravel.com/docs/12.x/filesystem#configuration)

3. Create the symbolic link for the public disk:
   ```bash
   php artisan storage:link
   ```

## Step 10: Configure Application Settings

1. Update the application settings in the `config/app.php` file:
   ```php
   'name' => env('APP_NAME', 'Enhanced Laravel Application'),
   'env' => env('APP_ENV', 'production'),
   'debug' => (bool) env('APP_DEBUG', false),
   'url' => env('APP_URL', 'http://localhost'),
   'timezone' => 'UTC',
   'locale' => 'en',
   'fallback_locale' => 'en',
   'faker_locale' => 'en_US',
   'key' => env('APP_KEY'),
   'cipher' => 'AES-256-CBC',
   ```

   > **Reference:** [Laravel 12.x Configuration Documentation](https:/laravel.com/docs/12.x/configuration)

2. Configure the application URL in the `.env` file:
   ```
   APP_URL=http://ela.test
   ```

3. Add the site to your hosts file:
   ```bash
   sudo sh -c 'echo "127.0.0.1 ela.test" >> /etc/hosts'
   ```

4. Configure Laravel Herd to serve the site:
   - Open Laravel Herd
   - Click on "Sites"
   - Click "Add Site"
   - Enter "ela.test" as the domain
   - Select the project directory
   - Click "Add Site"

## Step 11: Run Initial Migrations

1. Run the initial migrations to set up the database:
   ```bash
   php artisan migrate
   ```

2. Verify the migrations were successful:
   ```bash
   php artisan migrate:status
   ```

## Step 12: Set Up Version Control

1. Initialize a Git repository:
   ```bash
   git init
   ```

2. Create a `.gitignore` file (Laravel should have created one already, but verify it includes the following):
   ```
   /node_modules
   /public/hot
   /public/storage
   /storage/*.key
   /vendor
   .env
   .env.backup
   .env.testing
   .phpunit.result.cache
   Homestead.json
   Homestead.yaml
   npm-debug.log
   yarn-error.log
   /.idea
   /.vscode
   ```

3. Add all files to the repository:
   ```bash
   git add .
   ```

4. Create the initial commit:
   ```bash
   git commit -m "Initial Laravel 12 installation"
   ```

5. If you have a remote repository, add it and push:
   ```bash
   git remote add origin <repository-url>
   git push -u origin main
   ```

## Troubleshooting

### Common Issues and Solutions

1. **Database Connection Issues**
   - Problem: Cannot connect to PostgreSQL database
   - Solution:
     - Verify PostgreSQL is running
     - Check database credentials in `.env` file
     - Ensure the database exists

2. **Redis Connection Issues**
   - Problem: Cannot connect to Redis
   - Solution:
     - Verify Redis is running
     - Check Redis configuration in `.env` file

3. **Migration Issues**
   - Problem: Migrations fail to run
   - Solution:
     - Check database connection
     - Ensure database user has proper permissions
     - Check for syntax errors in migration files

4. **Symbolic Link Issues**
   - Problem: Storage symbolic link not created
   - Solution:
     - Run `php artisan storage:link` with proper permissions
     - Check if the public directory is writable

5. **Artisan Command Issues**
   - Problem: Artisan commands fail to run
   - Solution:
     - Check PHP version compatibility
     - Ensure all required PHP extensions are enabled
     - Clear configuration cache with `php artisan config:clear`

## Related Documents

- [Development Environment Setup](020-environment-setup/010-dev-environment-setup.md) - For setting up the development environment
- [Package Installation](030-core-components/010-package-installation.md) - For installing required packages
- [Database Setup](040-database/010-database-setup.md) - For detailed database configuration

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Standardized document title and metadata | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, and version history | AI Assistant |
| 1.0.3 | 2025-05-17 | Enhanced code examples with detailed comments and documentation | AI Assistant |

---

**Previous Step:** [Development Environment Setup](020-environment-setup/010-dev-environment-setup.md) | **Next Step:** [Package Installation](030-core-components/010-package-installation.md)
