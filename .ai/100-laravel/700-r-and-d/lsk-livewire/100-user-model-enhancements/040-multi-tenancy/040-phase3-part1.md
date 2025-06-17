# Multi-Tenancy Phase 3: Advanced Features (Part 1)

This document provides the first part of the implementation steps for Phase 3 of the multi-tenancy implementation, focusing on tenant-specific configurations.

## Step 1: Tenant-Specific Configurations

### 1.1 Create a Tenant Settings Model

Create a new model for storing tenant-specific settings:

```bash
php artisan make:model TenantSetting --migration
```

Edit the migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
            
            // Ensure each tenant can only have one setting with a specific key
            $table->unique(['tenant_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_settings');
    }
};
```

Edit the model file at `app/Models/TenantSetting.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class TenantSetting extends Model
{
    use UsesTenantConnection;
    
    protected $fillable = [
        'tenant_id',
        'key',
        'value',
    ];
    
    /**
     * Get the tenant that owns the setting.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
    
    /**
     * Get a setting value for the current tenant.
     */
    public static function get(string $key, $default = null)
    {
        $tenant = Tenant::current();
        
        if (!$tenant) {
            return $default;
        }
        
        $setting = static::where('tenant_id', $tenant->id)
            ->where('key', $key)
            ->first();
        
        return $setting ? $setting->value : $default;
    }
    
    /**
     * Set a setting value for the current tenant.
     */
    public static function set(string $key, $value): void
    {
        $tenant = Tenant::current();
        
        if (!$tenant) {
            return;
        }
        
        static::updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'key' => $key,
            ],
            [
                'value' => $value,
            ]
        );
    }
}
```

### 1.2 Update the Tenant Model

Add a relationship to the Tenant model in `app/Models/Tenant.php`:

```php
/**
 * Get the settings for this tenant.
 */
public function settings()
{
    return $this->hasMany(TenantSetting::class);
}

/**
 * Get a setting value.
 */
public function getSetting(string $key, $default = null)
{
    $setting = $this->settings()->where('key', $key)->first();
    
    return $setting ? $setting->value : $default;
}

/**
 * Set a setting value.
 */
public function setSetting(string $key, $value): void
{
    $this->settings()->updateOrCreate(
        ['key' => $key],
        ['value' => $value]
    );
}
```

### 1.3 Create a Tenant Settings Manager Service

Create a new service for managing tenant settings with caching:

```php
<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\TenantSetting;
use Illuminate\Support\Facades\Cache;

class TenantSettingsManager
{
    /**
     * Get a setting value for the current tenant.
     */
    public function get(string $key, $default = null)
    {
        $tenant = Tenant::current();
        
        if (!$tenant) {
            return $default;
        }
        
        // Try to get from cache first
        $cacheKey = "tenant_{$tenant->id}_setting_{$key}";
        
        return Cache::remember($cacheKey, now()->addHour(), function () use ($tenant, $key, $default) {
            return $tenant->getSetting($key, $default);
        });
    }
    
    /**
     * Set a setting value for the current tenant.
     */
    public function set(string $key, $value): void
    {
        $tenant = Tenant::current();
        
        if (!$tenant) {
            return;
        }
        
        $tenant->setSetting($key, $value);
        
        // Clear the cache for this setting
        $cacheKey = "tenant_{$tenant->id}_setting_{$key}";
        Cache::forget($cacheKey);
    }
    
    /**
     * Get all settings for the current tenant.
     */
    public function all(): array
    {
        $tenant = Tenant::current();
        
        if (!$tenant) {
            return [];
        }
        
        $cacheKey = "tenant_{$tenant->id}_all_settings";
        
        return Cache::remember($cacheKey, now()->addHour(), function () use ($tenant) {
            return $tenant->settings()
                ->pluck('value', 'key')
                ->toArray();
        });
    }
    
    /**
     * Clear all settings cache for the current tenant.
     */
    public function clearCache(): void
    {
        $tenant = Tenant::current();
        
        if (!$tenant) {
            return;
        }
        
        Cache::forget("tenant_{$tenant->id}_all_settings");
        
        // Clear individual setting caches
        foreach ($tenant->settings()->pluck('key') as $key) {
            Cache::forget("tenant_{$tenant->id}_setting_{$key}");
        }
    }
}
```

### 1.4 Register the Service in the Service Provider

Create a new service provider:

```bash
php artisan make:provider TenantServiceProvider
```

Edit the service provider at `app/Providers/TenantServiceProvider.php`:

```php
<?php

namespace App\Providers;

use App\Services\TenantSettingsManager;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantSettingsManager::class, function ($app) {
            return new TenantSettingsManager();
        });
        
        $this->app->alias(TenantSettingsManager::class, 'tenant.settings');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
```

Register the service provider in `config/app.php`:

```php
'providers' => [
    // Other service providers...
    App\Providers\TenantServiceProvider::class,
],
```

### 1.5 Create a Tenant Settings Component

Create a new Volt component for managing tenant settings:

```php
<?php

use App\Services\TenantSettingsManager;
use Illuminate\Support\Facades\App;
use Livewire\Volt\Component;
use Spatie\Multitenancy\Models\Tenant;

new class extends Component {
    public $settings = [];
    public $newKey = '';
    public $newValue = '';
    
    public function mount(): void
    {
        $this->loadSettings();
    }
    
    public function loadSettings(): void
    {
        $tenant = Tenant::current();
        
        if (!$tenant) {
            return;
        }
        
        $settingsManager = App::make(TenantSettingsManager::class);
        $this->settings = $tenant->settings()->get()->map(function ($setting) {
            return [
                'id' => $setting->id,
                'key' => $setting->key,
                'value' => $setting->value,
            ];
        })->toArray();
    }
    
    public function saveSetting(): void
    {
        $this->validate([
            'newKey' => 'required|string|max:255',
            'newValue' => 'required|string',
        ]);
        
        $settingsManager = App::make(TenantSettingsManager::class);
        $settingsManager->set($this->newKey, $this->newValue);
        
        $this->newKey = '';
        $this->newValue = '';
        $this->loadSettings();
    }
    
    public function deleteSetting($id): void
    {
        $tenant = Tenant::current();
        
        if (!$tenant) {
            return;
        }
        
        $tenant->settings()->where('id', $id)->delete();
        
        $settingsManager = App::make(TenantSettingsManager::class);
        $settingsManager->clearCache();
        
        $this->loadSettings();
    }
}; ?>

<div>
    <h2 class="text-2xl font-semibold mb-6">{{ __('Tenant Settings') }}</h2>
    
    @if(!Tenant::checkCurrent())
        <div class="bg-yellow-100 p-4 rounded-lg mb-6">
            <p class="text-yellow-700">{{ __('You are not currently in a tenant context.') }}</p>
        </div>
    @else
        <div class="mb-8">
            <h3 class="text-lg font-medium mb-4">{{ __('Current Settings') }}</h3>
            
            @if(count($settings) === 0)
                <p class="text-gray-500">{{ __('No settings defined yet.') }}</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Key') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Value') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($settings as $setting)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $setting['key'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $setting['value'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button wire:click="deleteSetting({{ $setting['id'] }})" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        <div>
            <h3 class="text-lg font-medium mb-4">{{ __('Add New Setting') }}</h3>
            
            <form wire:submit.prevent="saveSetting" class="space-y-4">
                <div>
                    <label for="newKey" class="block text-sm font-medium text-gray-700">{{ __('Key') }}</label>
                    <input type="text" wire:model="newKey" id="newKey" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('newKey') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label for="newValue" class="block text-sm font-medium text-gray-700">{{ __('Value') }}</label>
                    <textarea wire:model="newValue" id="newValue" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    @error('newValue') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ __('Save Setting') }}
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
```

Add a route for the tenant settings component in `routes/tenant.php`:

```php
Route::middleware(['web', 'tenant'])->group(function () {
    // Existing routes...
    
    Route::get('/settings', function () {
        return view('livewire.tenants.settings');
    })->name('tenant.settings');
});
```
