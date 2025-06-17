# Multi-Tenancy Phase 2: Tenant Management UI (Part 1)

This document provides the first part of the implementation steps for Phase 2 of the multi-tenancy implementation, which focuses on building the tenant management UI using Livewire/Volt components.

## Step 1: Create Tenant Dashboard Component

### 1.1 Create the Tenant Dashboard Volt Component

Create a new file at `resources/views/livewire/tenants/dashboard.blade.php`:

```php
<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public function with(): array
    {
        return [
            'tenants' => Auth::user()->teams()
                ->where('parent_id', null) // Root-level teams only
                ->with('tenant')
                ->get(),
            'currentTenant' => Tenant::current(),
        ];
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">{{ __('Tenant Dashboard') }}</h2>
                
                @if($currentTenant && !$currentTenant->isLandlord())
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-blue-700">
                            {{ __('You are currently in the :tenant tenant.', ['tenant' => $currentTenant->name]) }}
                        </p>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tenants as $team)
                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-medium">{{ $team->name }}</h3>
                            <p class="text-sm text-gray-500 mb-4">{{ $team->description }}</p>
                            
                            @if($team->tenant)
                                <div class="text-sm mb-2">
                                    <span class="font-medium">{{ __('Domain') }}:</span> 
                                    <a href="https://{{ $team->tenant->domain }}" class="text-blue-600 hover:underline" target="_blank">
                                        {{ $team->tenant->domain }}
                                    </a>
                                </div>
                            @else
                                <div class="text-sm text-amber-600 mb-2">
                                    {{ __('No tenant configured') }}
                                </div>
                            @endif
                            
                            <div class="mt-4 flex space-x-2">
                                @if($team->tenant)
                                    <a href="https://{{ $team->tenant->domain }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Visit') }}
                                    </a>
                                @else
                                    <a href="{{ route('tenants.create', ['team' => $team->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Configure Tenant') }}
                                    </a>
                                @endif
                                
                                <a href="{{ route('teams.show', $team) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Manage Team') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8">
                    <a href="{{ route('teams.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Create New Team') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 1.2 Create the Tenant Dashboard Route

Add the following route to `routes/web.php`:

```php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Tenant dashboard
    Route::get('/tenants', function () {
        return view('livewire.tenants.dashboard');
    })->name('tenants.dashboard');
});
```

### 1.3 Add a Link to the Navigation Menu

Update the navigation menu in `resources/views/layouts/navigation.blade.php` to include a link to the tenant dashboard:

```php
<x-nav-link :href="route('tenants.dashboard')" :active="request()->routeIs('tenants.dashboard')">
    {{ __('Tenants') }}
</x-nav-link>
```
