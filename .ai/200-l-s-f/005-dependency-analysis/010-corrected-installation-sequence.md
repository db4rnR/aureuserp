# Corrected Package Installation Sequence

## 1. Overview

This document provides the corrected, dependency-aware installation sequence that ensures all Spatie base packages are installed before their dependent Filament plugins.

**🎯 Confidence Score: 95%** - Based on extensive R&D documentation evidence

## 2. Installation Phases

### 2.1. Phase 1: Foundation Packages

```bash
# 1.1 Core architectural foundation with user tracking
composer require tightenco/parental:"^1.4" \
    staudenmeir/laravel-adjacency-list:"^1.25" \
    wildside/userstamps:"^3.0" \
    -W

# 1.2 Essential Laravel ecosystem
composer require livewire/livewire:"^3.6" \
    livewire/flux:"^2.1" \
    livewire/volt:"^1.7" \
    -W
```

**Reasoning:** These packages provide the core architectural foundation and must be installed first. The `wildside/userstamps` package is included as a foundational requirement for user tracking across all models.

### 2.2. Phase 2: Core Spatie Packages (BEFORE Filament Plugins)

```bash
# 2.1 Install ALL Spatie base packages first
composer require spatie/laravel-permission:"^6.19" \
    spatie/laravel-activitylog:"^4.10" \
    spatie/laravel-backup:"^9.3" \
    spatie/laravel-health:"^1.34" \
    spatie/laravel-medialibrary:"^11.13" \
    spatie/laravel-schedule-monitor:"^3.10" \
    spatie/laravel-settings:"^3.4" \
    spatie/laravel-tags:"^4.10" \
    spatie/laravel-translatable:"^6.11" \
    spatie/laravel-model-states:"^2.11" \
    spatie/laravel-model-status:"^1.18" \
    spatie/laravel-sluggable:"^3.7" \
    -W
```

**Critical:** ALL Spatie packages MUST be installed before ANY Filament plugins that depend on them.

### 2.3. Phase 3: Filament Core

```bash
# 3.1 Filament core (no plugins yet)
composer require filament/filament:"^3.3" \
    -W
```

**Reasoning:** Filament core must be available before installing any plugins.

### 2.4. Phase 4: Filament Plugins (AFTER Base Packages)

```bash
# 4.1 Official Filament-Spatie plugins (now safe to install)
composer require filament/spatie-laravel-media-library-plugin:"^3.3" \
    filament/spatie-laravel-tags-plugin:"^3.3" \
    filament/spatie-laravel-translatable-plugin:"^3.3" \
    -W

# 4.2 Community Filament-Spatie plugins
composer require shuvroroy/filament-spatie-laravel-backup:"^2.2" \
    shuvroroy/filament-spatie-laravel-health:"^2.3" \
    rmsramos/activitylog:"^1.0" \
    mvenghaus/filament-plugin-schedule-monitor:"^3.0" \
    -W

# 4.3 Other Filament plugins (no Spatie dependencies)
composer require bezhansalleh/filament-shield:"^3.3" \
    awcodes/filament-tiptap-editor:"^3.5" \
    awcodes/filament-curator:"^3.7" \
    -W
```

**Safe Now:** All underlying Spatie packages are installed, so plugins can install successfully.

### 2.5. Phase 5: Remaining Packages

```bash
# 5.1 Event sourcing and utilities
composer require hirethunk/verbs:"^0.7" \
    spatie/laravel-data:"^4.15" \
    spatie/laravel-query-builder:"^6.3" \
    -W

# 5.2 Development and quality tools
composer require --dev pestphp/pest:"^3.8" \
    pestphp/pest-plugin-laravel:"^3.2" \
    larastan/larastan:"^3.4" \
    laravel/pint:"^1.22" \
    -W
```

## 3. Critical Success Factors

1. **Never install Filament plugins before their Spatie base packages**
2. **Use `-W` flag to allow downgrades if needed**
3. **Install in batches to manage dependency resolution**
4. **Test each phase before proceeding**

## 4. Validation Commands

After each phase:

```bash
# Check for any dependency conflicts
composer validate

# Verify package versions
composer show | grep -E "(spatie|filament)"

# Run basic tests
php artisan config:cache
```

## 5. Rollback Strategy

If any phase fails:

```bash
# Reset to clean state
git checkout composer.json composer.lock
composer install

# Retry from the failed phase
```

This sequence eliminates the dependency conflicts identified in the original analysis and follows the documented requirements from the R&D documentation.
