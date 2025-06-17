# Phase 1: Phase 0.11: Database Migrations Setup

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
- [Step 1: Create Migration Structure](#step-1-create-migration-structure)
- [Step 2: Configure Migration Settings](#step-2-configure-migration-settings)
- [Step 3: Implement Laravel 12 Migration Features](#step-3-implement-laravel-12-migration-features)
- [Step 4: Set Up Migration Testing](#step-4-set-up-migration-testing)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides instructions for setting up the database migration structure for the Enhanced Laravel Application (ELA). It covers creating the migration directory structure, configuring migration settings, and implementing Laravel 12's new migration features.

> **Reference:** [Laravel 12.x Migrations Documentation](https:/laravel.com/docs/12.x/migrations)

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Database Setup](040-database/010-database-setup.md) completed
- [In-Memory SQLite Database](040-database/020-in-memory-database.md) completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed

### Required Knowledge
- Basic understanding of database migrations
- Familiarity with Laravel's migration system
- Understanding of database schema design

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x
- Database connection configured (PostgreSQL for production, SQLite for testing)

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Create Migration Structure | 10 minutes |
| Configure Migration Settings | 15 minutes |
| Implement Laravel 12 Migration Features | 30 minutes |
| Set Up Migration Testing | 20 minutes |
| **Total** | **75 minutes** |

> **Note:** These time estimates assume familiarity with Laravel migrations. Actual time may vary based on experience level and the complexity of your database schema.

## Step 1: Create Migration Structure

1. Create a directory structure for organizing migrations:
   ```bash
   mkdir -p database/migrations/core
   mkdir -p database/migrations/features
   mkdir -p database/migrations/indexes
   mkdir -p database/migrations/foreign_keys
   ```

2. Create a README file in each directory to explain its purpose:
   ```bash
   echo "# Core Migrations\n\nThis directory contains migrations for core tables." > database/migrations/core/README.md
   echo "# Feature Migrations\n\nThis directory contains migrations for feature-specific tables." > database/migrations/features/README.md
   echo "# Index Migrations\n\nThis directory contains migrations for adding indexes to tables." > database/migrations/indexes/README.md
   echo "# Foreign Key Migrations\n\nThis directory contains migrations for adding foreign key constraints." > database/migrations/foreign_keys/README.md
   ```

## Step 2: Configure Migration Settings

1. Create a migration service provider:
   ```bash
   php artisan make:provider MigrationServiceProvider
   ```

2. Configure the migration service provider in `app/Providers/MigrationServiceProvider.php`:
   ```php
   <?php

   namespace App\Providers;

   use Illuminate\Database\Migrations\Migrator;
   use Illuminate\Support\ServiceProvider;

   class MigrationServiceProvider extends ServiceProvider
   {
       /**
        * Register services.
        */
       public function register(): void
       {
           //
       }

       /**
        * Bootstrap services.
        */
       public function boot(): void
       {
           // Register migration paths
           $this->loadMigrationsFrom([
               database_path('migrations/core'),
               database_path('migrations/features'),
               database_path('migrations/indexes'),
               database_path('migrations/foreign_keys'),
           ]);
       }
   }
   ```

3. Register the migration service provider in `config/app.php`:
   ```php
   'providers' => [
       // Other service providers...
       App\Providers\MigrationServiceProvider::class,
   ],
   ```

## Step 3: Implement Laravel 12 Migration Features

1. Create a base migration class to leverage Laravel 12's new migration features:
   ```bash
   php artisan make:class BaseMigration --type=migration
   ```

2. Configure the base migration class in `app/Database/Migrations/BaseMigration.php`:
   ```php
   <?php

   namespace App\Database\Migrations;

   use Illuminate\Database\Migrations\Migration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   abstract class BaseMigration extends Migration
   {
       /**
        * The database connection that should be used by the migration.
        *
        * @var string|null
        */
       protected $connection = null;

       /**
        * The database schema.
        *
        * @var string|null
        */
       protected $schema = null;

       /**
        * Get the migration connection name.
        *
        * @return string|null
        */
       public function getConnection()
       {
           return $this->connection;
       }

       /**
        * Get the schema builder instance.
        *
        * @return \Illuminate\Database\Schema\Builder
        */
       protected function getSchemaBuilder()
       {
           $schema = Schema::connection($this->getConnection());

           if ($this->schema) {
               $schema->defaultStringLength(191);
               if (method_exists($schema, 'withSchema')) {
                   $schema = $schema->withSchema($this->schema);
               }
           }

           return $schema;
       }

       /**
        * Create a standard timestamps columns with userstamps.
        *
        * @param  \Illuminate\Database\Schema\Blueprint  $table
        * @return void
        */
       protected function timestampsWithUserstamps(Blueprint $table)
       {
           $table->timestamps();
           $table->userstamps();
           $table->softDeletes();
           $table->softUserstamps();
       }

       /**
        * Create a standard UUID primary key.
        *
        * @param  \Illuminate\Database\Schema\Blueprint  $table
        * @return void
        */
       protected function uuidPrimaryKey(Blueprint $table)
       {
           $table->uuid('id')->primary();
       }

       /**
        * Create a standard Snowflake ID primary key.
        *
        * @param  \Illuminate\Database\Schema\Blueprint  $table
        * @return void
        */
       protected function snowflakePrimaryKey(Blueprint $table)
       {
           $table->snowflake('id')->primary();
       }
   }
   ```

3. Create an example migration using the base migration class:
   ```bash
   php artisan make:migration create_example_table
   ```

4. Modify the generated migration to use the base migration class:
   ```php
   <?php

   use App\Database\Migrations\BaseMigration;
   use Illuminate\Database\Schema\Blueprint;
   use Illuminate\Support\Facades\Schema;

   return new class extends BaseMigration
   {
       /**
        * Run the migrations.
        */
       public function up(): void
       {
           $this->getSchemaBuilder()->create('examples', function (Blueprint $table) {
               $this->snowflakePrimaryKey($table);
               $table->string('name');
               $table->text('description')->nullable();
               $this->timestampsWithUserstamps($table);
           });
       }

       /**
        * Reverse the migrations.
        */
       public function down(): void
       {
           $this->getSchemaBuilder()->dropIfExists('examples');
       }
   };
   ```

> **Reference:** [Laravel 12.x Schema Builder Documentation](https:/laravel.com/docs/12.x/migrations#creating-tables)

## Step 4: Set Up Migration Testing

1. Create a migration test command:
   ```bash
   php artisan make:command TestMigrations
   ```

2. Configure the migration test command in `app/Console/Commands/TestMigrations.php`:
   ```php
   <?php

   namespace App\Console\Commands;

   use Illuminate\Console\Command;
   use Illuminate\Support\Facades\Artisan;

   class TestMigrations extends Command
   {
       /**
        * The name and signature of the console command.
        *
        * @var string
        */
       protected $signature = 'migrations:test';

       /**
        * The console command description.
        *
        * @var string
        */
       protected $description = 'Test migrations by running them against an in-memory SQLite database';

       /**
        * Execute the console command.
        */
       public function handle()
       {
           $this->info('Testing migrations...');

           // Set the environment to testing
           $this->call('config:clear');

           // Run the migrations
           $result = $this->call('migrate:fresh', [
               '--env' => 'testing',
               '--database' => 'sqlite',
           ]);

           if ($result === 0) {
               $this->info('Migrations test passed!');
           } else {
               $this->error('Migrations test failed!');
           }

           return $result;
       }
   }
   ```

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: Migration fails with "table already exists" error

**Symptoms:**
- Migration fails with an error about a table already existing
- Error occurs even after running `migrate:fresh`

**Possible Causes:**
- Multiple migrations creating the same table
- Schema dumping not working correctly
- Database connection issues

**Solutions:**
1. Check for duplicate table creation in your migrations
2. Run `php artisan schema:dump --prune` to reset the schema cache
3. Verify your database connection settings

### Issue: Foreign key constraints failing

**Symptoms:**
- Migration fails with foreign key constraint errors
- Error mentions a referenced table or column not existing

**Possible Causes:**
- Migrations running in the wrong order
- Referenced table not created yet
- Column names or types don't match

**Solutions:**
1. Ensure migrations are running in the correct order
2. Check that the referenced table is created before the foreign key
3. Verify column names and types match between tables

### Issue: Schema dumping not working

**Symptoms:**
- Schema dump command fails
- Migrations don't use the schema cache

**Possible Causes:**
- Missing database permissions
- Incorrect database driver configuration
- Schema directory not writable

**Solutions:**
1. Ensure the database user has sufficient permissions
2. Verify the database driver supports schema dumping
3. Check that the schema directory is writable

</details>

## Related Documents

- [Database Setup](040-database/010-database-setup.md) - For general database setup and configuration
- [In-Memory SQLite Database](040-database/020-in-memory-database.md) - For configuring in-memory databases
- [Security Setup](050-security-testing/010-security-setup.md) - For security considerations in database design

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [In-Memory SQLite Database](040-database/020-in-memory-database.md) | **Next Step:** [Security Setup](050-security-testing/010-security-setup.md)
