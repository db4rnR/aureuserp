# Multi-Tenancy Phase 4: Filament Integration (Part 1)

This document provides the first part of the implementation steps for Phase 4 of the multi-tenancy implementation, focusing on installing and configuring Filament.

## Step 1: Install and Configure Filament

### 1.1 Install Filament

```bash
composer require filament/filament:"^3.0"
```

### 1.2 Install Filament Panels

```bash
php artisan filament:install --panels
```

### 1.3 Configure Filament for Multi-Tenancy

Edit the `config/filament.php` file to configure multi-tenancy:

```php
'tenant_model' => App\Models\Tenant::class,

'tenant_route_prefix' => 'tenant',

'tenant_slug' => 'domain',

'tenant_middleware' => [
    'web',
    'auth',
    'verified',
],
```

### 1.4 Create a Filament Service Provider

Create a new service provider for Filament configuration:

```bash
php artisan make:provider FilamentServiceProvider
```

Edit the service provider at `app/Providers/FilamentServiceProvider.php`:

```php
<?php

namespace App\Providers;

use App\Models\Tenant;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
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
        // Configure the admin panel
        Filament::serving(function () {
            // Set the brand name
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Tenant Management')
                    ->icon('heroicon-o-building-office'),
                NavigationGroup::make()
                    ->label('User Management')
                    ->icon('heroicon-o-users'),
                NavigationGroup::make()
                    ->label('Settings')
                    ->icon('heroicon-o-cog'),
            ]);
        });
    }
}
```

Register the service provider in `config/app.php`:

```php
'providers' => [
    // Other service providers...
    App\Providers\FilamentServiceProvider::class,
],
```
