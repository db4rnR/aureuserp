# Phase 4: Tooling

## 1. Objectives

- Develop management tools for the Traits Management System
- Create Artisan commands for common tasks
- Implement a web interface for trait configuration
- Create debugging and troubleshooting tools

## 2. Create Artisan Commands

### 2.1. Create a Trait Configuration Command

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class TraitConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'traits:config
                            {trait? : The trait to configure}
                            {--list : List all traits and their configurations}
                            {--enable= : Enable a feature for a trait}
                            {--disable= : Disable a feature for a trait}
                            {--set= : Set a configuration value (format: key=value)}
                            {--model= : Apply configuration to a specific model}
                            {--tenant= : Apply configuration to a specific tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure traits in the Traits Management System';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('list')) {
            return $this->listTraits();
        }
        
        $trait = $this->argument('trait');
        
        if (!$trait) {
            $this->error('Please specify a trait to configure or use --list to see all traits');
            return 1;
        }
        
        if ($this->option('enable')) {
            return $this->enableFeature($trait, $this->option('enable'));
        }
        
        if ($this->option('disable')) {
            return $this->disableFeature($trait, $this->option('disable'));
        }
        
        if ($this->option('set')) {
            return $this->setConfig($trait, $this->option('set'));
        }
        
        // If no action specified, show the current configuration
        return $this->showTraitConfig($trait);
    }
    
    /**
     * List all traits and their configurations.
     */
    protected function listTraits()
    {
        $traits = Config::get('traits');
        
        // Remove non-trait configurations
        unset($traits['enabled']);
        unset($traits['telemetry_enabled']);
        unset($traits['cache_ttl']);
        unset($traits['model_overrides']);
        unset($traits['tenant_overrides']);
        unset($traits['multi_tenancy']);
        
        $rows = [];
        
        foreach ($traits as $trait => $config) {
            $enabled = $config['enabled'] ?? true;
            $features = $config['features'] ?? [];
            
            $featureStatus = [];
            foreach ($features as $feature => $status) {
                $featureStatus[] = "{$feature}: " . ($status ? 'enabled' : 'disabled');
            }
            
            $rows[] = [
                $trait,
                $enabled ? 'Enabled' : 'Disabled',
                implode(', ', $featureStatus),
            ];
        }
        
        $this->table(['Trait', 'Status', 'Features'], $rows);
        
        return 0;
    }
    
    /**
     * Show the configuration for a specific trait.
     */
    protected function showTraitConfig(string $trait)
    {
        $config = Config::get("traits.{$trait}");
        
        if (!$config) {
            $this->error("Trait not found: {$trait}");
            return 1;
        }
        
        $this->info("Configuration for {$trait}:");
        $this->line(json_encode($config, JSON_PRETTY_PRINT));
        
        return 0;
    }
    
    /**
     * Enable a feature for a trait.
     */
    protected function enableFeature(string $trait, string $feature)
    {
        $model = $this->option('model');
        $tenant = $this->option('tenant');
        
        if ($model) {
            Config::set("traits.model_overrides.{$model}.{$trait}.features.{$feature}", true);
            $this->info("Enabled {$feature} for {$trait} on model {$model}");
        } elseif ($tenant) {
            Config::set("traits.tenant_overrides.{$tenant}.{$trait}.features.{$feature}", true);
            $this->info("Enabled {$feature} for {$trait} on tenant {$tenant}");
        } else {
            Config::set("traits.{$trait}.features.{$feature}", true);
            $this->info("Enabled {$feature} for {$trait}");
        }
        
        // Save the configuration
        $this->call('config:cache');
        
        return 0;
    }
    
    /**
     * Disable a feature for a trait.
     */
    protected function disableFeature(string $trait, string $feature)
    {
        $model = $this->option('model');
        $tenant = $this->option('tenant');
        
        if ($model) {
            Config::set("traits.model_overrides.{$model}.{$trait}.features.{$feature}", false);
            $this->info("Disabled {$feature} for {$trait} on model {$model}");
        } elseif ($tenant) {
            Config::set("traits.tenant_overrides.{$tenant}.{$trait}.features.{$feature}", false);
            $this->info("Disabled {$feature} for {$trait} on tenant {$tenant}");
        } else {
            Config::set("traits.{$trait}.features.{$feature}", false);
            $this->info("Disabled {$feature} for {$trait}");
        }
        
        // Save the configuration
        $this->call('config:cache');
        
        return 0;
    }
    
    /**
     * Set a configuration value for a trait.
     */
    protected function setConfig(string $trait, string $keyValue)
    {
        $parts = explode('=', $keyValue, 2);
        
        if (count($parts) !== 2) {
            $this->error('Invalid format for --set. Use key=value');
            return 1;
        }
        
        [$key, $value] = $parts;
        
        $model = $this->option('model');
        $tenant = $this->option('tenant');
        
        if ($model) {
            Config::set("traits.model_overrides.{$model}.{$trait}.{$key}", $value);
            $this->info("Set {$key}={$value} for {$trait} on model {$model}");
        } elseif ($tenant) {
            Config::set("traits.tenant_overrides.{$tenant}.{$trait}.{$key}", $value);
            $this->info("Set {$key}={$value} for {$trait} on tenant {$tenant}");
        } else {
            Config::set("traits.{$trait}.{$key}", $value);
            $this->info("Set {$key}={$value} for {$trait}");
        }
        
        // Save the configuration
        $this->call('config:cache');
        
        return 0;
    }
}
```

### 2.2. Create a Trait Diagnostics Command

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

class TraitDiagnosticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'traits:diagnose
                            {model? : The model to diagnose}
                            {--trait= : The trait to diagnose}
                            {--all : Diagnose all models and traits}
                            {--fix : Attempt to fix issues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run diagnostics on the Traits Management System';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            return $this->diagnoseAll();
        }
        
        $model = $this->argument('model');
        $trait = $this->option('trait');
        
        if (!$model && !$trait) {
            $this->error('Please specify a model or trait to diagnose, or use --all');
            return 1;
        }
        
        if ($model) {
            return $this->diagnoseModel($model);
        }
        
        if ($trait) {
            return $this->diagnoseTrait($trait);
        }
        
        return 0;
    }
    
    /**
     * Diagnose all models and traits.
     */
    protected function diagnoseAll()
    {
        $this->info('Running diagnostics on all models and traits...');
        
        // Diagnose all traits
        $traits = $this->getTraits();
        foreach ($traits as $trait) {
            $this->diagnoseTrait($trait);
        }
        
        // Diagnose all models
        $models = $this->getModels();
        foreach ($models as $model) {
            $this->diagnoseModel($model);
        }
        
        $this->info('Diagnostics completed.');
        
        return 0;
    }
    
    /**
     * Diagnose a specific model.
     */
    protected function diagnoseModel(string $model)
    {
        $this->info("Diagnosing model: {$model}");
        
        if (!class_exists($model)) {
            $this->error("Model not found: {$model}");
            return 1;
        }
        
        // Check if the model uses any traits from the Traits Management System
        $traits = $this->getModelTraits($model);
        $managedTraits = array_intersect($traits, $this->getTraits());
        
        if (empty($managedTraits)) {
            $this->warn("Model does not use any traits from the Traits Management System");
            return 0;
        }
        
        $this->info("Model uses the following managed traits: " . implode(', ', $managedTraits));
        
        // Check for common issues
        $issues = [];
        
        // Check for missing database columns
        $issues = array_merge($issues, $this->checkMissingColumns($model, $managedTraits));
        
        // Check for configuration issues
        $issues = array_merge($issues, $this->checkConfigurationIssues($model, $managedTraits));
        
        // Display issues
        if (empty($issues)) {
            $this->info("No issues found with model {$model}");
        } else {
            $this->warn("Found " . count($issues) . " issues with model {$model}:");
            
            foreach ($issues as $issue) {
                $this->line("- {$issue['description']}");
                
                if ($this->option('fix') && isset($issue['fix'])) {
                    $this->info("  Fixing: {$issue['fix']}");
                    // Execute the fix
                    if (is_callable($issue['fix_callback'])) {
                        $issue['fix_callback']();
                    }
                }
            }
        }
        
        return 0;
    }
    
    /**
     * Diagnose a specific trait.
     */
    protected function diagnoseTrait(string $trait)
    {
        $this->info("Diagnosing trait: {$trait}");
        
        // Implementation for trait diagnostics
        
        return 0;
    }
    
    /**
     * Get all traits in the Traits Management System.
     */
    protected function getTraits(): array
    {
        // Implementation to get all traits
        return [];
    }
    
    /**
     * Get all models in the application.
     */
    protected function getModels(): array
    {
        // Implementation to get all models
        return [];
    }
    
    /**
     * Get all traits used by a model.
     */
    protected function getModelTraits(string $model): array
    {
        // Implementation to get model traits
        return [];
    }
    
    /**
     * Check for missing database columns.
     */
    protected function checkMissingColumns(string $model, array $traits): array
    {
        // Implementation to check for missing columns
        return [];
    }
    
    /**
     * Check for configuration issues.
     */
    protected function checkConfigurationIssues(string $model, array $traits): array
    {
        // Implementation to check for configuration issues
        return [];
    }
}
```

## 3. Create a Web Interface

### 3.1. Create a Trait Management Controller

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class TraitManagementController extends Controller
{
    /**
     * Display the trait management dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $traits = Config::get('traits');
        
        // Remove non-trait configurations
        unset($traits['enabled']);
        unset($traits['telemetry_enabled']);
        unset($traits['cache_ttl']);
        unset($traits['model_overrides']);
        unset($traits['tenant_overrides']);
        unset($traits['multi_tenancy']);
        
        return view('admin.traits.index', [
            'traits' => $traits,
            'globalEnabled' => Config::get('traits.enabled', true),
        ]);
    }
    
    /**
     * Show the configuration for a specific trait.
     *
     * @param string $trait
     * @return \Illuminate\View\View
     */
    public function show(string $trait)
    {
        $config = Config::get("traits.{$trait}");
        
        if (!$config) {
            return redirect()->route('admin.traits.index')
                ->with('error', "Trait not found: {$trait}");
        }
        
        // Get models using this trait
        $models = $this->getModelsUsingTrait($trait);
        
        return view('admin.traits.show', [
            'trait' => $trait,
            'config' => $config,
            'models' => $models,
        ]);
    }
    
    /**
     * Update the configuration for a specific trait.
     *
     * @param Request $request
     * @param string $trait
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $trait)
    {
        $validated = $request->validate([
            'enabled' => 'boolean',
            'features' => 'array',
            'features.*' => 'boolean',
            // Add validation for other configuration options
        ]);
        
        // Update the configuration
        Config::set("traits.{$trait}.enabled", $validated['enabled']);
        
        foreach ($validated['features'] as $feature => $enabled) {
            Config::set("traits.{$trait}.features.{$feature}", (bool) $enabled);
        }
        
        // Save the configuration
        Artisan::call('config:cache');
        
        return redirect()->route('admin.traits.show', $trait)
            ->with('success', "Configuration for {$trait} has been updated");
    }
    
    /**
     * Get all models using a specific trait.
     *
     * @param string $trait
     * @return array
     */
    protected function getModelsUsingTrait(string $trait): array
    {
        // Implementation to get models using a trait
        return [];
    }
}
```

### 3.2. Create Blade Views for Trait Management

```blade
<!-- resources/views/admin/traits/index.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Trait Management</h1>
        
        <div class="card mb-4">
            <div class="card-header">
                Global Settings
            </div>
            <div class="card-body">
                <form action="{{ route('admin.traits.global') }}" method="POST">
                    @csrf
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="enabled" id="enabled" {{ $globalEnabled ? 'checked' : '' }}>
                        <label class="form-check-label" for="enabled">
                            Enable Traits Management System
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                Available Traits
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Trait</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($traits as $traitName => $traitConfig)
                            <tr>
                                <td>{{ $traitName }}</td>
                                <td>
                                    @if($traitConfig['enabled'] ?? true)
                                        <span class="badge bg-success">Enabled</span>
                                    @else
                                        <span class="badge bg-danger">Disabled</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.traits.show', $traitName) }}" class="btn btn-sm btn-primary">Configure</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
```

```blade
<!-- resources/views/admin/traits/show.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Configure {{ $trait }}</h1>
        
        <div class="card">
            <div class="card-header">
                Configuration
            </div>
            <div class="card-body">
                <form action="{{ route('admin.traits.update', $trait) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="enabled" id="enabled" {{ ($config['enabled'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="enabled">
                            Enable {{ $trait }}
                        </label>
                    </div>
                    
                    <h5>Features</h5>
                    
                    @foreach($config['features'] ?? [] as $feature => $enabled)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="features[{{ $feature }}]" id="feature_{{ $feature }}" {{ $enabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="feature_{{ $feature }}">
                                {{ ucfirst(str_replace('_', ' ', $feature)) }}
                            </label>
                        </div>
                    @endforeach
                    
                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </form>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                Models Using {{ $trait }}
            </div>
            <div class="card-body">
                @if(count($models) > 0)
                    <ul>
                        @foreach($models as $model)
                            <li>{{ $model }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No models are using this trait.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
```

## 4. Create Debugging Tools

### 4.1. Create a Trait Debug Middleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class TraitDebugMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only enable for admins and when debug mode is on
        if (Auth::check() && Auth::user()->isAdmin() && config('app.debug') && $request->has('trait_debug')) {
            // Enable trait debugging
            $this->enableTraitDebugging();
        }
        
        return $next($request);
    }
    
    /**
     * Enable trait debugging.
     *
     * @return void
     */
    protected function enableTraitDebugging(): void
    {
        // Listen for all trait events
        Event::listen('trait.*.*', function ($eventName, $payload) {
            // Add debug information to the response
            if (!isset($GLOBALS['trait_debug'])) {
                $GLOBALS['trait_debug'] = [];
            }
            
            $GLOBALS['trait_debug'][] = [
                'event' => $eventName,
                'payload' => $payload,
                'time' => microtime(true),
            ];
        });
        
        // Add debug information to the response
        app()->terminating(function () {
            if (isset($GLOBALS['trait_debug'])) {
                $response = app('response');
                
                if (method_exists($response, 'getContent')) {
                    $content = $response->getContent();
                    
                    // Only inject debug info into HTML responses
                    if (strpos($content, '</body>') !== false) {
                        $debugHtml = '<div id="trait-debug" style="position: fixed; bottom: 0; right: 0; width: 400px; height: 300px; background: #fff; border: 1px solid #ccc; overflow: auto; padding: 10px; z-index: 9999;">';
                        $debugHtml .= '<h3>Trait Debug</h3>';
                        $debugHtml .= '<pre>' . json_encode($GLOBALS['trait_debug'], JSON_PRETTY_PRINT) . '</pre>';
                        $debugHtml .= '</div>';
                        
                        $content = str_replace('</body>', $debugHtml . '</body>', $content);
                        $response->setContent($content);
                    }
                }
            }
        });
    }
}
```

## 5. Next Steps

After completing the Tooling phase, we will move on to the Documentation phase, where we'll create comprehensive documentation for the Traits Management System. See [Documentation Phase](025-documentation-phase.md) for details.
