# Phase 0: Phase 0.9: Database Setup

**Version:** 1.0.2 **Date:** 2023-11-13 **Author:** AI Assistant **Status:** Updated **Progress:** Complete

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
- [Step 1: Configure Database Connection](#step-1-configure-database-connection)
- [Step 2: Configure PostgreSQL Schema](#step-2-configure-postgresql-schema)
- [Step 3: Set Up PostgreSQL for Development](#step-3-set-up-postgresql-for-development)
- [Step 4: Configure SQLite for Testing](#step-4-configure-sqlite-for-testing)
- [Step 5: Test Database Connections](#step-5-test-database-connections)
- [Related Documents](#related-documents)
- [Troubleshooting](#troubleshooting)
- [Version History](#version-history)
</details>

## Overview

This document provides instructions for setting up the database connections for the Enhanced Laravel Application (ELA).
It covers configuring PostgreSQL for development and SQLite for testing.

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps

- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Package Installation](030-core-components/010-package-installation.md) completed
- [Filament Configuration](030-core-components/040-filament-configuration.md) completed

### Required Packages

- Laravel Framework (`laravel/framework`) installed
- Laravel Octane (`laravel/octane`) installed

### Required Knowledge

- Basic understanding of database management systems
- Familiarity with PostgreSQL and SQLite
- Understanding of Laravel database configuration

### Required Environment

- PHP 8.2 or higher with PDO drivers for PostgreSQL and SQLite
- PostgreSQL 15.x or higher installed and running
- SQLite 3.x installed
- Laravel Herd configured

## Estimated Time Requirements

| Task                              | Estimated Time |
| --------------------------------- | -------------- |
| Configure Database Connection     | 10 minutes     |
| Configure PostgreSQL Schema       | 15 minutes     |
| Set Up PostgreSQL for Development | 20 minutes     |
| Configure SQLite for Testing      | 15 minutes     |
| Test Database Connections         | 10 minutes     |
| **Total**                         | **70 minutes** |

> **Note:** These time estimates assume familiarity with Laravel and database management systems. Actual time may vary
> based on experience level and the complexity of your database setup.

## Step 1: Configure Database Connection

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

   Replace `your_username` and `your_password` with your PostgreSQL credentials. The `DB_SCHEMA` variable allows you to
   configure which schema to use, with `public` as the default.

2. Create a testing environment file:

   ```bash
   cp .env .env.testing
   ```

3. Update the testing database settings in `.env.testing`:

   ```
   APP_ENV=testing

   DB_CONNECTION=sqlite
   DB_DATABASE=:memory:
   ```

## Step 2: Configure PostgreSQL Schema

1. Update the PostgreSQL configuration in `config/database.php` to use a configurable schema:

   ```php
   'pgsql' => [
       'driver' => 'pgsql',
       'url' => env('DATABASE_URL'),
       'host' => env('DB_HOST', '127.0.0.1'),
       'port' => env('DB_PORT', '5432'),
       'database' => env('DB_DATABASE', 'forge'),
       'username' => env('DB_USERNAME', 'forge'),
       'password' => env('DB_PASSWORD', ''),
       'charset' => 'utf8',
       'prefix' => '',
       'prefix_indexes' => true,
       'search_path' => env('DB_SCHEMA', 'public'),
       'sslmode' => 'prefer',
   ],
   ```

   The `search_path` configuration option allows Laravel to automatically set the PostgreSQL search path. By using
   `env('DB_SCHEMA', 'public')`, we make it configurable with 'public' as the default/fallback.

2. Create a migration to ensure the schema exists if needed:

   ```bash
   php artisan make:migration create_schema
   ```

3. Configure the migration in `database/migrations/yyyy_mm_dd_hhmmss_create_schema.php`:

   ```php
   <?php

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Support\Facades\DB;
   use Illuminate\Support\Facades\Schema;

   return new class extends Migration
   {
       /**
        * Run the migrations.
        */
       public function up(): void
       {
           $schema = config('database.connections.pgsql.search_path', 'public');

           // Only create schema if it's not 'public' (which exists by default)
           if ($schema !== 'public') {
               DB::statement("CREATE SCHEMA IF NOT EXISTS {$schema}");
           }
       }

       /**
        * Reverse the migrations.
        */
       public function down(): void
       {
           $schema = config('database.connections.pgsql.search_path', 'public');

           // Only drop schema if it's not 'public'
           if ($schema !== 'public') {
               DB::statement("DROP SCHEMA IF EXISTS {$schema} CASCADE");
           }
       }
   };
   ```

## Step 3: Set Up PostgreSQL for Development

1. Create the PostgreSQL database for development:

   ```bash
   createdb ela_development
   ```

2. If using a custom schema, ensure the user has the necessary permissions:

   ```sql
   GRANT ALL PRIVILEGES ON DATABASE ela_development TO your_username;
   GRANT ALL PRIVILEGES ON SCHEMA your_schema TO your_username;
   ALTER USER your_username SET search_path TO your_schema, public;
   ```

3. Verify the database connection:
   ```bash
   php artisan db:monitor
   ```

## Step 4: Configure SQLite for Testing

1. Ensure SQLite is installed and configured for testing:

   ```bash
   touch database/database.sqlite
   ```

2. Update the testing database configuration in `config/database.php` to use SQLite:

   ```php
   'sqlite' => [
       'driver' => 'sqlite',
       'url' => env('DATABASE_URL'),
       'database' => env('DB_DATABASE', database_path('database.sqlite')),
       'prefix' => '',
       'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
   ],
   ```

3. Create a test to verify the SQLite connection:

   ```bash
   php artisan make:test DatabaseConnectionTest
   ```

4. Configure the test in `tests/Feature/DatabaseConnectionTest.php`:

   ```php
   <?php

   namespace Tests\Feature;

   use Illuminate\Foundation\Testing\RefreshDatabase;
   use Illuminate\Support\Facades\DB;
   use Tests\TestCase;

   class DatabaseConnectionTest extends TestCase
   {
       use RefreshDatabase;

       /**
        * Test database connection.
        */
       public function test_database_connection(): void
       {
           $this->assertTrue(DB::connection()->getDatabaseName() !== null);
       }
   }
   ```

5. Run the test to verify the SQLite connection:
   ```bash
   php artisan test --filter=DatabaseConnectionTest
   ```

## Related Documents

- [Filament Configuration](030-core-components/040-filament-configuration.md) - For setting up the frontend components
- [In-Memory SQLite Database](040-database/020-in-memory-database.md) - For configuring a single SQLite in-memory database for
  all persistence, caching, and queueing
- [Database Migrations](040-database/030-database-migrations.md) - For setting up database migrations
- [Final Configuration](060-configuration/020-final-configuration.md) - For final configuration steps

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: Unable to connect to PostgreSQL database

**Symptoms:**

- Laravel returns a database connection error
- Error message includes "could not connect to server"

**Possible Causes:**

- PostgreSQL service is not running
- Incorrect database credentials in .env file
- PostgreSQL is not listening on the specified port

**Solutions:**

1. Verify PostgreSQL is running with `pg_isready` or equivalent command
2. Check your database credentials in the .env file
3. Ensure PostgreSQL is configured to listen on the specified port

### Issue: Schema not found in PostgreSQL

**Symptoms:**

- Laravel returns a "schema does not exist" error
- Tables are not created in the expected schema

**Possible Causes:**

- Schema has not been created in PostgreSQL
- Search path is not configured correctly
- User does not have permissions to access the schema

**Solutions:**

1. Create the schema manually with `CREATE SCHEMA IF NOT EXISTS your_schema_name;`
2. Verify the search_path configuration in database.php
3. Grant necessary permissions to the database user

### Issue: SQLite database not found

**Symptoms:**

- Laravel returns a "database file not found" error
- Tests fail with database connection errors

**Possible Causes:**

- SQLite database file does not exist
- Directory permissions prevent Laravel from creating the file
- Incorrect database path in phpunit.xml or .env.testing

**Solutions:**

1. Create the database directory and ensure it's writable
2. Check the database path in your configuration files
3. For in-memory databases, ensure the connection string is correct

</details>

## Version History

| Version | Date       | Changes                                                                                             | Author       |
| ------- | ---------- | --------------------------------------------------------------------------------------------------- | ------------ |
| 1.0.0   | 2025-05-15 | Initial version                                                                                     | AI Assistant |
| 1.0.1   | 2025-05-17 | Standardized document title and metadata                                                            | AI Assistant |
| 1.0.2   | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [Filament Configuration](030-core-components/040-filament-configuration.md) | **Next Step:**
[In-Memory SQLite Database](040-database/020-in-memory-database.md)
