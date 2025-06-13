# PHPStan Configuration
# PHPStan Configuration

## 1. Configuration Files

Our PHPStan setup consists of the following configuration files:

- `phpstan.neon` - Main configuration file
- `phpstan-baseline.neon` - Generated baseline of accepted errors
- `phpstan.dist.neon` - Distribution/template configuration

## 2. Basic Configuration

Our main `phpstan.neon` file includes:
This document describes the configuration approach for PHPStan in our Laravel 12 project running on PHP 8.4.

## Configuration Files

The project uses the following PHPStan configuration files:

* **phpstan.neon.dist** - Primary distributed configuration committed to the repository
* **phpstan.neon** - Local developer configuration overrides (not committed)
* **phpstan-baseline.neon** - Generated baseline of current errors (committed)
* **phpstan-minimal.neon** - Temporary file created during baseline generation

## Configuration Structure

### Core Settings

Our phpstan.neon.dist includes these key settings:

```yaml
# Maximum strictness level
level: 10

# PHP version target
phpVersion: 80400

# Paths to analyze
paths:
    - app
    - config
    - database
    - routes
    - tests
    - bootstrap
    - resources/views

# Performance optimization
parallel:
    jobSize: 20
    maximumNumberOfProcesses: 16
    minimumNumberOfJobsPerProcess: 2
    processTimeout: 300.0
```

### Laravel-Specific Configuration

We use Larastan to add Laravel-specific type rules:

```yaml
includes:
    - vendor/larastan/larastan/extension.neon
```
# PHPStan Configuration

This guide covers how to configure PHPStan for our Laravel 12 project to achieve optimal static analysis.

## 1. Configuration File Structure

PHPStan uses a NEON configuration file (`phpstan.neon`) at the project root. The file is structured as follows:
### Dynamic Features Support

Special handling for Laravel's dynamic behavior:

```yaml
universalObjectCratesClasses:
    - Illuminate\Database\Eloquent\Model
    - Illuminate\Support\Collection
    - Livewire\Component
    # ... more classes

dynamicConstantNames:
    - view
    - auth
    - app
    # ... more helpers
```

### Ignored Errors

We've configured pattern-based error ignoring for Laravel-specific edge cases:

```yaml
ignoreErrors:
    # Livewire/Volt specific patterns
    - '#Expression "new.*#\[\\Livewire\\Attributes\\Layout\(.*\).*class extends \\Livewire\\Volt\\Component.*" on a separate line does not do anything\.#'
    
    # Auth-related checks (for nullable user)
    - '#Cannot access property .* on App\\Models\\User\|null\.#'
    
    # ... more patterns
```

## Customizing Configuration

To override settings for your local environment without affecting the repository:

1. Create a local `phpstan.neon` file
2. Include the distributed config and override parameters:

```yaml
includes:
    - phpstan.neon.dist

parameters:
    # Your custom overrides
    level: 5  # Less strict for local development
    excludePaths:
        - app/Experimental/*  # Exclude your experimental code
```

## Advanced Configuration

For specialized analysis needs, you can create purpose-specific configuration files:

```yaml
# Example specialized config: phpstan-models.neon
includes:
    - vendor/larastan/larastan/extension.neon

parameters:
    level: 10
    paths:
        - app/Models
```

Then run with: `vendor/bin/phpstan analyse --configuration=phpstan-models.neon`
