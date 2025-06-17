# Phase 1: Phase 0.10: In-Memory SQLite Database Configuration

**Version:** 1.0.3
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Complete

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
- [Step 1: Configure In-Memory Database](#step-1-configure-in-memory-database)
- [Step 2: Understanding Connection Strings](#step-2-understanding-connection-strings)
- [Step 3: Accessing In-Memory Databases](#step-3-accessing-in-memory-databases)
- [Step 4: Performance Considerations](#step-4-performance-considerations)
- [Step 5: Octane Compatibility](#step-5-octane-compatibility)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides instructions for configuring and using a single SQLite in-memory database for all persistence, caching, and queueing operations in the Enhanced Laravel Application (ELA). This configuration is ideal for development and testing environments where speed is prioritized over data persistence.

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Database Setup](040-database/010-database-setup.md) completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed

### Required Knowledge
- Basic understanding of Laravel's database configuration
- Familiarity with SQLite databases

### Required Environment
- PHP 8.2 or higher with SQLite support enabled
- Laravel 12.x
- Laravel Octane (optional, for performance considerations)

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Configure In-Memory Database | 10 minutes |
| Understanding Connection Strings | 5 minutes |
| Accessing In-Memory Databases | 15 minutes |
| Performance Considerations | 5 minutes |
| Octane Compatibility | 10 minutes |
| Testing and Verification | 15 minutes |
| **Total** | **60 minutes** |

> **Note:** These time estimates assume familiarity with Laravel and SQLite. Actual time may vary based on experience level and environment setup.

## Step 1: Configure In-Memory Database

1. Create a `.env.memory` file in your project root with the following configuration:

```ini
APP_NAME=lsk
APP_ENV=local
APP_KEY=base64:s/fmY+oRgs/6vJBaWiN9n3SC982X8jqH67t+Ur57Alc=
APP_DEBUG=true

APP_TIMEZONE=UTC
APP_ID=lsk
APP_SLUG=lsk
APP_DOMAIN="${APP_SLUG}.test"
APP_URL="http://${APP_DOMAIN}"

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_GB

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=1

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# SQLite In-Memory Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
DB_FOREIGN_KEYS=true

# Use database for sessions (same SQLite in-memory database)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

# Use log for broadcasting in memory mode
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local

# Use database for queue (same SQLite in-memory database)
QUEUE_CONNECTION=database

# Use database for cache (same SQLite in-memory database)
CACHE_STORE=database
CACHE_PREFIX=lsk_cache

# Disable Redis since we're using SQLite for everything
REDIS_CLIENT=null

# Use array mailer for local development
MAIL_MAILER=array
MAIL_FROM_ADDRESS="${APP_SLUG}@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# --- Octane ---
# Note: When using in-memory SQLite with Octane, you may need
# to configure Octane to reset the database connection between requests
OCTANE_SERVER=frankenphp
OCTANE_RESET_DATABASE_CONNECTIONS=true

# --- Media-Library
# For in-memory setup, use a temporary disk for media
MEDIA_DISK=local
```text

2. To use this configuration, copy it to your `.env` file:

```bash
cp .env.memory .env
```php
3. Run migrations to set up the database schema:

```bash
php artisan migrate
```text

4. Seed the database with initial data:

```bash
php artisan db:seed
```php
## Step 2: Understanding Connection Strings

The SQLite in-memory database uses the following connection string:

```text
sqlite::memory:
```php
In Laravel's `.env` file, this is configured as:

```text
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```php
### Important Considerations

1. **Data Persistence**: In-memory databases exist only for the duration of the database connection. When the application stops, **all data is lost**. This makes it ideal for development and testing but not for production use.

2. **Multiple Connections**: By default, each new connection to an in-memory SQLite database creates a separate database instance. This means tools connecting to `:memory:` won't see the same data as your application.

3. **Shared Cache Mode**: To make an in-memory database accessible to external tools, you can use SQLite's shared cache mode:

```text
DB_DATABASE=file::memory:?cache=shared
```php
This allows multiple connections to access the same in-memory database, but all connections must be from the same process.

## Step 3: Accessing In-Memory Databases

### Using Laravel Tinker

The most reliable way to access your in-memory database is through Laravel Tinker:

```bash
php artisan tinker
```text

Then you can run queries:

```php
DB::select('SELECT * FROM users');
```php
### Using TablePlus

1. Create a new SQLite connection
2. For "Database File" select "Connect to a running SQLite database"
3. Use the connection string: `file::memory:?cache=shared`

Note: This only works if:
- Your Laravel app is running
- You've configured it to use `file::memory:?cache=shared` instead of just `:memory:`
- The connection is maintained by the application

### Using PhpStorm/DataGrip

1. Create a new SQLite data source
2. For "File" enter `:memory:`

Note: This creates a separate in-memory database, not connected to your application's database.

### Using SQLite CLI

```bash
sqlite3
.open :memory:
```text

Note: This creates a separate in-memory database, not connected to your application's database.

## Step 4: Performance Considerations

### Benefits of In-Memory Databases

1. **Speed**: In-memory databases are extremely fast since all operations occur in RAM
2. **Simplicity**: No need to set up and maintain separate database servers
3. **Fresh state**: Each application restart gives you a clean database
4. **Isolation**: Each developer works with their own isolated database

### Alternative Approaches

For a balance between performance and persistence, consider using a memory-mapped file:

```php
DB_DATABASE=/tmp/laravel_db.sqlite
```text

This provides:
- Fast performance (the file is cached in memory)
- Data persistence between application restarts
- Ability to connect with external tools

## Step 5: Octane Compatibility

When using in-memory SQLite with Laravel Octane, you need to be aware that:

1. Octane keeps the application in memory between requests
2. This can cause the in-memory database to persist between requests
3. You may need to configure Octane to reset the database connection:

```sql
OCTANE_RESET_DATABASE_CONNECTIONS=true
```text

This ensures that each request gets a fresh database connection, which is important for testing scenarios where you want to start with a clean database for each test.

## Troubleshooting

### "Unable to open database" Error

If you see this error, it means the connection to the in-memory database couldn't be established. This could be because:

1. The database connection was closed
2. You're trying to connect from an external tool to a non-shared in-memory database
3. There's a configuration issue with your SQLite setup

### Data Disappearing Between Requests

With Octane, if you're seeing data disappear between requests despite using an in-memory database, check:

1. `OCTANE_RESET_DATABASE_CONNECTIONS` is set to `false`
2. You're using the same database connection throughout your application

### External Tool Connection Issues

If you're having trouble connecting to the in-memory database with external tools:

1. Use a file-based SQLite database for development instead
2. Configure your application to use `file::memory:?cache=shared` and ensure it stays running
3. Use Laravel Tinker for database access, which shares the same connection as your application

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: Database connection fails with "unable to open database file" error

**Symptoms:**
- Laravel returns a database connection error
- Error message includes "unable to open database file"

**Possible Causes:**
- SQLite extension not enabled in PHP
- Incorrect connection string format
- Permissions issues with the database directory

**Solutions:**
1. Verify SQLite is enabled in PHP with `php -m | grep sqlite`
2. Check your connection string format in the `.env` file
3. Ensure the database directory has proper permissions

### Issue: Data disappears between requests

**Symptoms:**
- Data saved in one request is not available in subsequent requests
- Database appears empty after restarting the application

**Possible Causes:**
- Using `:memory:` database without proper configuration
- Not using a persistent connection
- Octane configuration issues

**Solutions:**
1. Use the shared memory approach with a specific filename
2. Configure Laravel Octane to use the same database connection
3. Implement the database provider as shown in Step 3

### Issue: Performance degradation over time

**Symptoms:**
- Application becomes slower as more data is added
- Memory usage increases significantly

**Possible Causes:**
- Not using database indexes
- Accumulating too much data without cleanup
- Missing vacuum operations

**Solutions:**
1. Add appropriate indexes to frequently queried columns
2. Implement a cleanup routine for old/temporary data
3. Run `VACUUM` periodically to optimize the database

</details>

## Related Documents

- [Database Setup](040-database/010-database-setup.md) - For general database setup and configuration
- [Database Migrations](040-database/030-database-migrations.md) - For setting up database migrations
- [Final Configuration](060-configuration/020-final-configuration.md) - For final configuration steps

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-17 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Standardized document title and metadata | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites section and navigation links | AI Assistant |
| 1.0.3 | 2025-05-17 | Added estimated time requirements and troubleshooting section | AI Assistant |

---

**Previous Step:** [Database Setup](040-database/010-database-setup.md) | **Next Step:** [Database Migrations Setup](040-database/030-database-migrations.md)
