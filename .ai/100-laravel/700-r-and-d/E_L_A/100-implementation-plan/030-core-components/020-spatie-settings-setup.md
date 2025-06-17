# Phase 0: Phase 0.5: Spatie Laravel Settings Setup

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
- [Step 1: Install the Package](#step-1-install-the-package)
- [Step 2: Publish and Run Migrations](#step-2-publish-and-run-migrations)
- [Step 3: Create Settings Directory](#step-3-create-settings-directory)
- [Step 4: Create Settings Classes](#step-4-create-settings-classes)
- [Step 5: Create Settings Migrations](#step-5-create-settings-migrations)
- [Step 6: Register Settings Classes](#step-6-register-settings-classes)
- [Step 7: Using Settings in Your Application](#step-7-using-settings-in-your-application)
- [Advanced Usage](#advanced-usage)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides detailed instructions for setting up and using the Spatie Laravel Settings package in the Enhanced Laravel Application (ELA). The package allows you to store strongly typed application settings in a repository (database, Redis, etc.) and use them throughout your application.

> **Reference:** [Spatie Laravel Settings GitHub Repository](https:/github.com/spatie/laravel-settings)

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](./020-environment-setup/020-laravel-installation.md) completed
- [Package Installation](./030-core-components/010-package-installation.md) completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed
- Spatie Laravel Settings (`spatie/laravel-settings`) installed

### Required Knowledge
- Basic understanding of Laravel configuration
- Familiarity with database migrations
- Understanding of PHP typed properties

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x
- Database connection configured

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Install the Package | 5 minutes |
| Publish and Run Migrations | 5 minutes |
| Create Settings Directory | 2 minutes |
| Create Settings Classes | 15 minutes |
| Create Settings Migrations | 10 minutes |
| Register Settings Classes | 5 minutes |
| Test Settings Usage | 10 minutes |
| **Total** | **52 minutes** |

> **Note:** These time estimates assume familiarity with Laravel and Spatie packages. Actual time may vary based on experience level and the complexity of your settings.

## Step 1: Install the Package

1. Install the package via Composer:
   ```bash
   composer require spatie/laravel-settings:"^3.4"
   ```

## Step 2: Publish and Run Migrations

1. Publish the migrations:
   ```bash
   php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
   ```

   This will create a migration file in your `database/migrations` directory that creates a `settings` table.

3. Run the migrations:
   ```bash
   php artisan migrate
   ```

## Step 3: Create Settings Directory

1. Create a directory for your settings classes:
   ```bash
   mkdir -p app/Settings
   ```

   This directory will store all your settings classes.

## Step 4: Create Settings Classes

1. Create a settings class for general application settings:
   ```bash
   php artisan make:setting GeneralSettings --group=general
   ```

   Alternatively, you can manually create a settings class:

   ```php
   <?php

   namespace App\Settings;

   use Spatie\LaravelSettings\Settings;

   class GeneralSettings extends Settings
   {
       public string $site_name;

       public bool $site_active;

       public string $timezone;

       public static function group(): string
       {
           return 'general';
       }
   }
   ```

   Each settings class should:
   - Extend `Spatie\LaravelSettings\Settings`
   - Define public properties with their types
   - Implement a `group()` method that returns a string identifier for the settings group

## Step 5: Create Settings Migrations

1. Create a settings migration:
   ```bash
   php artisan make:settings-migration CreateGeneralSettings
   ```

   This will create a migration file in the `database/settings` directory.

2. Edit the migration file to define default values for your settings:
   ```php
   <?php

   use Spatie\LaravelSettings\Migrations\SettingsMigration;

   return new class extends SettingsMigration
   {
       public function up(): void
       {
           $this->migrator->add('general.site_name', 'Enhanced Laravel Application');
           $this->migrator->add('general.site_active', true);
           $this->migrator->add('general.timezone', 'UTC');
       }
   };
   ```

3. Run the migrations:
   ```bash
   php artisan migrate
   ```

## Step 6: Register Settings Classes

1. Publish the configuration file:
   ```bash
   php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="config"
   ```

2. Register your settings classes in the `config/settings.php` file:
   ```php
   'settings' => [
       App\Settings\GeneralSettings::class,
       // Add other settings classes here
   ],
   ```

## Step 7: Using Settings in Your Application

### Dependency Injection

You can inject settings classes into your controllers, services, or other classes:

```php
<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(GeneralSettings $settings)
    {
        return view('settings.index', [
            'site_name' => $settings->site_name,
            'site_active' => $settings->site_active,
            'timezone' => $settings->timezone,
        ]);
    }

    public function update(Request $request, GeneralSettings $settings)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_active' => 'required|boolean',
            'timezone' => 'required|string',
        ]);

        $settings->site_name = $validated['site_name'];
        $settings->site_active = $validated['site_active'];
        $settings->timezone = $validated['timezone'];

        $settings->save();

        return redirect()->back()->with('status', 'Settings updated successfully.');
    }
}
```

### Resolving from the Container

You can also resolve settings from the Laravel container:

```php
$settings = app(App\Settings\GeneralSettings::class);
echo $settings->site_name;
```

## Advanced Usage

### Locking Properties

You can lock properties to prevent them from being updated:

```php
$settings->lock('site_name');
$settings->save(); // site_name will not be updated
```

### Encrypting Properties

For sensitive data, you can encrypt properties:

```php
class ApiSettings extends Settings
{
    public string $api_key;

    public static function group(): string
    {
        return 'api';
    }

    public static function encrypted(): array
    {
        return [
            'api_key'
        ];
    }
}
```

In your migration:

```php
$this->migrator->addEncrypted('api.api_key', 'your-default-api-key');
```

### Caching Settings

Enable caching in your `.env` file:

```php
SETTINGS_CACHE_ENABLED=true
```

Clear the settings cache:

```bash
php artisan settings:clear-cache
```

## Troubleshooting

### Missing Settings Directory

If you encounter an error about missing settings migrations, ensure the `database/settings` directory exists:

```bash
mkdir -p database/settings
```

### Incorrect Provider Name

When publishing migrations or configuration, ensure you use the correct provider name:

```bash
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
```

Not:

```bash
php artisan vendor:publish --provider="Spatie\Settings\SettingsServiceProvider" --tag="settings-migrations"
```

### Settings Not Found

If your settings are not found, check that:
1. The settings class is registered in `config/settings.php`
2. The settings migration has been run
3. The group name in the settings class matches the group name in the migration

## Related Documents

- [Package Installation](030-core-components/010-package-installation.md) - For installing required packages
- [CQRS Configuration](030-core-components/030-cqrs-configuration.md) - For configuring CQRS with hirethunk/verbs
- [Database Setup](040-database/010-database-setup.md) - For detailed database configuration

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Standardized document title and metadata | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, and version history | AI Assistant |

---

**Previous Step:** [Package Installation](030-core-components/010-package-installation.md) | **Next Step:** [CQRS Configuration](030-core-components/030-cqrs-configuration.md)
