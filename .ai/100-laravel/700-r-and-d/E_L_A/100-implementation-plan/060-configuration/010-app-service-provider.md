# Phase 1: Phase 0.15: Custom AppServiceProvider Configuration

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
- [Step 1: Create Custom AppServiceProvider](#step-1-create-custom-appserviceprovider)
- [Step 2: Understanding the AppServiceProvider](#step-2-understanding-the-appserviceprovider)
- [Step 3: Implement the AppServiceProvider](#step-3-implement-the-appserviceprovider)
- [Step 4: Test the Implementation](#step-4-test-the-implementation)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides instructions for implementing a custom AppServiceProvider for the Enhanced Laravel Application (ELA). The AppServiceProvider is a central place to configure various aspects of the Laravel application, including models, database, URL handling, and more.

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Logging Setup](050-security-testing/030-logging-setup.md) completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed
- Carbon (`nesbot/carbon`) installed

### Required Knowledge
- Basic understanding of Laravel service providers
- Familiarity with dependency injection
- Understanding of model configuration

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Create Custom AppServiceProvider | 10 minutes |
| Understand the AppServiceProvider | 15 minutes |
| Implement the AppServiceProvider | 20 minutes |
| Test the Implementation | 15 minutes |
| **Total** | **60 minutes** |

> **Note:** These time estimates assume familiarity with Laravel service providers. Actual time may vary based on experience level and the complexity of your application.

## Step 1: Create Custom AppServiceProvider

1. The AppServiceProvider is automatically created when you install Laravel, but we'll replace it with our custom implementation. Open the file at `app/Providers/AppServiceProvider.php`.

2. Replace the contents of the file with the following custom implementation:

```php
<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /*Gate::before(function (User $user, string $ability) {
            return $user->id === 1;});*/

        Number::useLocale('en');
        URL::defaults(['domain' => '']);
        if (! $this->app->isLocal()) {
            URL::forceScheme('https');
        }

        Model::unguard();

        /**
         * Force correct Typesense API key very early
         */
        // config(['scout.typesense.client-settings.api_key' => 'LARAVEL_HERD']);

        $this->configureCarbon();
        $this->configureCommands();
        $this->configureDatabase();
        $this->configureModels();
        $this->configureUrl();
        $this->configureVite();
    }

    /**
     * Configure the application's carbon.
     */
    private function configureCarbon(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        Artisan::command('inspire', function (): void {
            $this->comment(Inspiring::quote());
        })->purpose('Display an inspiring quote');
    }

    /**
     * Configure the application's database.
     */
    private function configureDatabase(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction()
            && ! $this->app->runningInConsole()
            && ! $this->app->runningUnitTests()
            && ! $this->app->isDownForMaintenance(),
        );
    }

    /**
     * Configure the application's models.
     */
    private function configureModels(): void
    {
        Model::automaticallyEagerLoadRelationships();
        Model::preventAccessingMissingAttributes(! $this->app->isProduction());
        Model::preventLazyLoading(! $this->app->isProduction());
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());
        Model::shouldBeStrict(! $this->app->isProduction());
        Model::unguard(! $this->app->isProduction());
    }

    /**
     * Configure the application's url.
     */
    private function configureUrl(): void
    {

        URL::forceScheme('https');
    }

    /**
     * Configure the application's vite.
     */
    private function configureVite(): void
    {

        Vite::useBuildDirectory('build')
            ->withEntryPoints([
                'resources/js/app.js',
            ]);
    }
}
```text

## Step 2: Understanding the AppServiceProvider

The custom AppServiceProvider implements several important configurations for the application:

### 1. Strict Type Declarations

```php
declare(strict_types=1);
```php
This enables strict type checking for the file, which helps catch type-related errors early.

### 2. Initial Configuration in boot()

```php
Number::useLocale('en');
```text
Sets the default locale for number formatting to English.

```php
URL::defaults(['domain' => '']);
```php
Sets default URL parameters.

```php
if (! $this->app->isLocal()) {
    URL::forceScheme('https');
}
```text
Forces HTTPS in non-local environments.

```php
Model::unguard();
```php
Disables mass assignment protection globally. This is later refined in the configureModels() method.

### 3. Carbon Configuration

```php
private function configureCarbon(): void
{
    Date::use(CarbonImmutable::class);
}
```text

Uses CarbonImmutable instead of the default Carbon. This means that date objects are immutable, preventing unexpected side effects when manipulating dates.

### 4. Commands Configuration

```php
private function configureCommands(): void
{
    Artisan::command('inspire', function (): void {
        $this->comment(Inspiring::quote());
    })->purpose('Display an inspiring quote');
}
```php
Registers a simple 'inspire' Artisan command that displays an inspiring quote.

### 5. Database Configuration

```php
private function configureDatabase(): void
{
    DB::prohibitDestructiveCommands(
        $this->app->isProduction()
        && ! $this->app->runningInConsole()
        && ! $this->app->runningUnitTests()
        && ! $this->app->isDownForMaintenance(),
    );
}
```text

Prohibits destructive database commands (like DROP TABLE) in production environments, except when running in console, running unit tests, or when the application is in maintenance mode.

### 6. Models Configuration

```php
private function configureModels(): void
{
    Model::automaticallyEagerLoadRelationships();
    Model::preventAccessingMissingAttributes(! $this->app->isProduction());
    Model::preventLazyLoading(! $this->app->isProduction());
    Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());
    Model::shouldBeStrict(! $this->app->isProduction());
    Model::unguard(! $this->app->isProduction());
}
```php
- `automaticallyEagerLoadRelationships()`: Automatically eager loads relationships defined in the `$with` property of models.
- `preventAccessingMissingAttributes()`: Throws an exception when trying to access a non-existent attribute (in non-production environments).
- `preventLazyLoading()`: Prevents lazy loading of relationships (in non-production environments) to avoid N+1 query problems.
- `preventSilentlyDiscardingAttributes()`: Throws an exception when trying to set a non-existent attribute (in non-production environments).
- `shouldBeStrict()`: Enables strict mode for models (in non-production environments).
- `unguard()`: Disables mass assignment protection (in non-production environments).

### 7. URL Configuration

```php
private function configureUrl(): void
{
    URL::forceScheme('https');
}
```text

Forces HTTPS for all URLs generated by the application.

### 8. Vite Configuration

```php
private function configureVite(): void
{
    Vite::useBuildDirectory('build')
        ->withEntryPoints([
            'resources/js/app.js',
        ]);
}
```

Configures Vite to use the 'build' directory and specifies the entry point for the JavaScript application.

## Step 3: Implement the AppServiceProvider

1. Save the custom AppServiceProvider implementation to `app/Providers/AppServiceProvider.php`.

2. Ensure that the AppServiceProvider is registered in `config/app.php`:
   ```php
   'providers' => [
       // Other service providers...
       App\Providers\AppServiceProvider::class,
   ],
   ```

3. Update the verification script to check for the custom AppServiceProvider:
   ```php
   // In app/Console/Commands/VerifyConfiguration.php

   // Add to the handle() method
   $this->info('Verifying AppServiceProvider...');

   $appServiceProvider = file_get_contents(app_path('Providers/AppServiceProvider.php'));

   if (strpos($appServiceProvider, 'configureCarbon()') === false ||
       strpos($appServiceProvider, 'configureCommands()') === false ||
       strpos($appServiceProvider, 'configureDatabase()') === false ||
       strpos($appServiceProvider, 'configureModels()') === false ||
       strpos($appServiceProvider, 'configureUrl()') === false ||
       strpos($appServiceProvider, 'configureVite()') === false) {
       $this->error('Custom AppServiceProvider is not properly configured!');
       return 1;
   }

   $this->info('Custom AppServiceProvider is properly configured!');
   ```

4. Run the verification command:
   ```bash
   php artisan verify:configuration
   ```

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: Carbon configuration not working

**Symptoms:**
- Carbon is not using CarbonImmutable
- Date formatting is inconsistent

**Possible Causes:**
- `configureCarbon()` method not called in the `boot()` method
- Carbon package not installed
- Conflicting Carbon configuration elsewhere

**Solutions:**
1. Ensure the `configureCarbon()` method is called in the `boot()` method
2. Verify Carbon is installed with `composer require nesbot/carbon`
3. Check for conflicting Carbon configuration in other service providers

### Issue: Strict model behavior not enforced

**Symptoms:**
- Models allow mass assignment without explicit fillable properties
- Models don't throw exceptions for missing attributes

**Possible Causes:**
- `configureModels()` method not called in the `boot()` method
- Configuration overridden elsewhere

**Solutions:**
1. Ensure the `configureModels()` method is called in the `boot()` method
2. Check for conflicting model configuration in other service providers
3. Verify that no models are explicitly disabling these protections

### Issue: URL handling not working correctly

**Symptoms:**
- URLs are not being forced to HTTPS in production
- Asset URLs are incorrect

**Possible Causes:**
- `configureUrl()` method not called in the `boot()` method
- Environment configuration issues

**Solutions:**
1. Ensure the `configureUrl()` method is called in the `boot()` method
2. Check the `APP_ENV` and `APP_URL` in your `.env` file
3. Verify that your web server is properly configured for HTTPS

### Issue: Vite configuration not working

**Symptoms:**
- Assets not loading correctly
- Vite manifest not found

**Possible Causes:**
- `configureVite()` method not called in the `boot()` method
- Vite not properly installed or configured

**Solutions:**
1. Ensure the `configureVite()` method is called in the `boot()` method
2. Verify Vite is installed and configured correctly
3. Check that the Vite manifest file exists

</details>

## Related Documents

- [Logging Setup](050-security-testing/030-logging-setup.md) - For logging configuration
- [Security Configuration](050-security-testing/040-security-configuration.md) - For security configuration
- [Final Configuration](060-configuration/020-final-configuration.md) - For final configuration steps

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [Logging Setup](050-security-testing/030-logging-setup.md) | **Next Step:** [Security Configuration Details](050-security-testing/040-security-configuration.md)
