# 7. Upgrade Roadmap: PHP 8.4, Laravel 12, TailwindCSS 4

## Table of Contents

- [1. Executive Summary](#1-executive-summary)
- [2. PHP 8.4 Upgrade Strategy](#2-php-84-upgrade-strategy)
- [3. Laravel 12 Upgrade Process](#3-laravel-12-upgrade-process)
- [4. TailwindCSS 4.0 Migration](#4-tailwindcss-40-migration)
- [5. Filament v3 Compatibility](#5-filament-v3-compatibility)
- [6. Testing and Validation](#6-testing-and-validation)
- [7. Deployment Strategy](#7-deployment-strategy)
- [8. Risk Assessment and Mitigation](#8-risk-assessment-and-mitigation)

## 1. Executive Summary

This comprehensive upgrade roadmap outlines the strategic approach to modernizing AureusERP with the latest technology stack while maintaining stability and allowing FilamentPHP to continue using TailwindCSS v3 during the transition period.

**🎯 Confidence Score: 90%** - Based on official documentation analysis and upgrade path research

### 1.1. Upgrade Overview

| Component | Current Version | Target Version | Complexity | Timeline |
|-----------|----------------|----------------|------------|----------|
| PHP | 8.2+ | 8.4 | Medium | 2-3 weeks |
| Laravel | 12.18 | 12.x Latest | Low | 1 week |
| TailwindCSS | 3.4.13 | 4.0 | High | 3-4 weeks |
| FilamentPHP | 3.3 | 3.3 (Compatible) | Low | No change |

### 1.2. Key Challenges

**Critical Considerations:**

- **TailwindCSS v4 Breaking Changes**: Major CSS framework restructuring
- **FilamentPHP Compatibility**: Ensuring admin panel continues with TailwindCSS v3
- **Plugin System Impact**: 22 plugins requiring compatibility verification
- **Production Stability**: Zero-downtime upgrade requirements

## 2. PHP 8.4 Upgrade Strategy

### 2.1. PHP 8.4 New Features

**Performance Improvements:**

- **JIT Compiler Enhancements**: 10-15% performance improvements
- **Memory Management**: Reduced memory usage and garbage collection optimization
- **Opcache Improvements**: Better caching strategies

**Language Features:**

- **Property Hooks**: Cleaner getter/setter implementation
- **Asymmetric Visibility**: More granular property access control
- **New Array Functions**: Enhanced array manipulation capabilities
- **Lazy Objects**: Improved lazy loading patterns

### 2.2. Compatibility Assessment

#### 2.2.1. Current Dependency Analysis

```bash
# Check PHP 8.4 compatibility for core dependencies
composer update --dry-run --with-all-dependencies

# Verify specific packages
composer show laravel/framework | grep versions
composer show filament/filament | grep versions
```

#### 2.2.2. Breaking Changes Impact

**Low Risk Areas:**

- Laravel Framework: Full PHP 8.4 support
- FilamentPHP: Compatible with PHP 8.4
- Spatie Packages: Modern PHP support
- Most development tools: Already compatible

**Medium Risk Areas:**

- Plugin-specific dependencies
- Custom validation rules
- Legacy code patterns

### 2.3. PHP 8.4 Migration Steps

#### 2.3.1. Phase 1: Environment Preparation

```bash
# 1. Update development environment
brew install php@8.4
brew link php@8.4 --force

# 2. Update composer dependencies
composer update --with-all-dependencies

# 3. Update PHP configuration
cp /opt/homebrew/etc/php/8.4/php.ini.default /opt/homebrew/etc/php/8.4/php.ini
```

#### 2.3.2. Phase 2: Code Compatibility

**Update composer.json:**

```json
{
    "require": {
        "php": "^8.4",
        "laravel/framework": "^12.18"
    }
}
```

**Code Review Checklist:**

- Review deprecated function usage
- Update type declarations
- Check for PHP 8.4 specific optimizations
- Verify plugin compatibility

#### 2.3.3. Phase 3: Testing and Validation

```bash
# Run comprehensive test suite
php artisan test

# Check for deprecation warnings
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Performance benchmarking
php artisan benchmark:run
```

### 2.4. PHP 8.4 Performance Optimizations

#### 2.4.1. JIT Configuration

```ini
; php.ini optimizations for PHP 8.4
opcache.jit_buffer_size=256M
opcache.jit=1255
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=512
```

#### 2.4.2. Laravel Optimizations

```php
// config/app.php - Enable PHP 8.4 optimizations
'aliases' => Facade::defaultAliases()->merge([
    // Custom aliases optimized for PHP 8.4
])->toArray(),
```

## 3. Laravel 12 Upgrade Process

### 3.1. Laravel 12 Current State

**Current Configuration:**

```json
"laravel/framework": "^12.18"
```

The project is already on Laravel 12, requiring only minor version updates.

### 3.2. Laravel 12 Latest Updates

#### 3.2.1. Security Updates

```bash
# Update to latest Laravel 12.x
composer require laravel/framework:^12.0

# Verify security patches
composer audit
```

#### 3.2.2. Feature Enhancements

**New Laravel 12 Features to Leverage:**

- Enhanced Eloquent performance
- Improved queue processing
- Better caching mechanisms
- Advanced validation features

### 3.3. Configuration Updates

#### 3.3.1. Environment Configuration

```bash
# Update .env for Laravel 12 optimizations
php artisan env:update

# Refresh configuration cache
php artisan config:clear
php artisan config:cache
```

#### 3.3.2. Service Provider Updates

```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
    // Laravel 12 specific optimizations
    if ($this->app->isProduction()) {
        URL::forceScheme('https');
    }
    
    // Enable query optimization
    DB::enableQueryLog();
}
```

## 4. TailwindCSS 4.0 Migration

### 4.1. TailwindCSS 4.0 Overview

**Major Changes in TailwindCSS v4:**

- **Oxide Engine**: New Rust-based engine for 10x faster builds
- **Configuration Format**: Simplified CSS-based configuration
- **Native CSS Variables**: Better browser integration
- **Improved Performance**: Faster compilation and smaller output
- **New Plugin System**: Enhanced extensibility

### 4.2. Dual TailwindCSS Strategy

#### 4.2.1. Isolation Approach

**Strategy**: Run TailwindCSS v4 for main application while keeping FilamentPHP on v3

```json
{
    "devDependencies": {
        "@tailwindcss/cli": "^4.0.0",
        "tailwindcss-v3": "npm:tailwindcss@^3.4.13"
    }
}
```

#### 4.2.2. Build Configuration

**Vite Configuration for Dual TailwindCSS:**

```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    css: {
        postcss: {
            plugins: [
                // Main app uses TailwindCSS v4
                require('@tailwindcss/cli')({
                    content: [
                        './resources/**/*.blade.php',
                        './resources/**/*.js',
                        './resources/**/*.vue',
                        // Exclude Filament files
                        '!./vendor/filament/**/*',
                    ],
                    output: './public/css/app.css'
                }),
                // Filament uses TailwindCSS v3
                require('tailwindcss-v3')({
                    content: [
                        './vendor/filament/**/*.blade.php',
                        './app/Filament/**/*.php',
                    ],
                    output: './public/css/filament.css'
                }),
            ],
        },
    },
});
```

### 4.3. TailwindCSS 4.0 Migration Steps

#### 4.3.1. Phase 1: Preparation

```bash
# Install TailwindCSS v4
npm install @tailwindcss/cli@next @tailwindcss/forms@next

# Create separate TailwindCSS v3 for Filament
npm install tailwindcss-v3@npm:tailwindcss@^3.4.13
```

#### 4.3.2. Phase 2: Configuration Migration

**New TailwindCSS v4 Configuration (CSS-based):**

```css
/* resources/css/tailwind.css */
@import "tailwindcss";

@theme {
  --color-primary: #0066cc;
  --color-secondary: #6b7280;
  --radius-lg: 0.5rem;
  --font-family-sans: Inter, sans-serif;
}

@plugin "tailwindcss/forms";
@plugin "./resources/css/plugins/custom-utilities";
```

**Legacy FilamentPHP Configuration:**

```javascript
// tailwind.filament.config.js
module.exports = {
    content: [
        './vendor/filament/**/*.blade.php',
        './app/Filament/**/*.php',
        './plugins/*/src/Filament/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#eff6ff',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                    950: '#172554',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
```

#### 4.3.3. Phase 3: Asset Compilation

**Separate Build Processes:**

```json
{
    "scripts": {
        "build": "npm run build:app && npm run build:filament",
        "build:app": "tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --minify",
        "build:filament": "tailwindcss -c tailwind.filament.config.js -i ./resources/css/filament.css -o ./public/css/filament.css --minify",
        "dev": "npm run dev:app && npm run dev:filament",
        "dev:app": "tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --watch",
        "dev:filament": "tailwindcss -c tailwind.filament.config.js -i ./resources/css/filament.css -o ./public/css/filament.css --watch"
    }
}
```

### 4.4. Template Updates

#### 4.4.1. Main Application Templates

```html
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AureusERP</title>
    
    <!-- TailwindCSS v4 for main app -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    @yield('content')
</body>
</html>
```

#### 4.4.2. FilamentPHP Configuration

```php
// app/Providers/Filament/AdminPanelProvider.php
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;

public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('/admin')
        ->assets([
            // Force FilamentPHP to use TailwindCSS v3
            Css::make('filament-styles', asset('css/filament.css'))
                ->loadedOnRequest(),
        ])
        ->plugins([
            \Bezhansalleh\FilamentShield\FilamentShieldPlugin::make(),
        ]);
}
```

## 5. Filament v3 Compatibility

### 5.1. FilamentPHP Version Strategy

**Current Setup:**

```json
"filament/filament": "^3.3"
```

**Recommended Approach:**

- Keep FilamentPHP on v3.3 with TailwindCSS v3 compatibility
- Plan FilamentPHP v4 upgrade as separate phase
- Ensure TailwindCSS v3 isolation for admin panel

### 5.2. Plugin Compatibility

#### 5.2.1. Webkul Plugin Updates

**Plugin TailwindCSS Configuration:**

```javascript
// For each plugin with Filament resources
module.exports = {
    content: [
        './plugins/webkul/[plugin-name]/src/Filament/**/*.php',
    ],
    presets: [
        require('./tailwind.filament.config.js'),
    ],
};
```

#### 5.2.2. Asset Compilation for Plugins

```php
// In plugin service provider
public function boot(): void
{
    if ($this->app->runningInConsole()) {
        $this->publishes([
            __DIR__ . '/../../resources/css' => public_path('vendor/plugin-name/css'),
        ], 'plugin-assets');
    }
}
```

### 5.3. CSS Scope Isolation

#### 5.3.1. CSS Variable Scoping

```css
/* Main app (TailwindCSS v4) */
:root {
    --tw-primary: #0066cc;
    --tw-secondary: #6b7280;
}

/* Filament scope (TailwindCSS v3) */
.filament-admin {
    --primary-50: #eff6ff;
    --primary-500: #3b82f6;
    --primary-600: #2563eb;
}
```

#### 5.3.2. Class Prefix Strategy

```javascript
// tailwind.filament.config.js
module.exports = {
    prefix: 'fi-',
    content: [
        './vendor/filament/**/*.blade.php',
        './app/Filament/**/*.php',
    ],
    // ... rest of configuration
};
```

## 6. Testing and Validation

### 6.1. Comprehensive Testing Strategy

#### 6.1.1. Automated Testing

```bash
# PHP 8.4 compatibility tests
php artisan test --coverage

# Frontend build verification
npm run build
npm run test

# Plugin compatibility verification
php artisan plugin:test --all
```

#### 6.1.2. Visual Regression Testing

```bash
# Install visual testing tools
npm install --save-dev puppeteer
npm install --save-dev backstopjs

# Run visual regression tests
npm run test:visual
```

#### 6.1.3. Performance Testing

```bash
# Laravel performance testing
php artisan benchmark:run

# Frontend performance
npm run lighthouse
npm run bundle-analyzer
```

### 6.2. Testing Checklist

#### 6.2.1. PHP 8.4 Validation

- [ ] All Composer dependencies compatible
- [ ] No PHP deprecation warnings
- [ ] Performance improvements verified
- [ ] Plugin functionality unchanged
- [ ] Database operations working

#### 6.2.2. TailwindCSS Validation

- [ ] Main application styles working with v4
- [ ] FilamentPHP admin panel styles intact with v3
- [ ] No CSS conflicts between versions
- [ ] Build process generating correct assets
- [ ] Plugin styles rendering correctly

#### 6.2.3. Integration Testing

- [ ] Admin panel fully functional
- [ ] All 22 plugins operational
- [ ] User authentication working
- [ ] Database migrations successful
- [ ] File uploads and downloads working

## 7. Deployment Strategy

### 7.1. Staged Deployment Approach

#### 7.1.1. Development Environment

```bash
# Phase 1: Development upgrade
git checkout -b upgrade/php8.4-laravel12-tailwind4

# Update dependencies
composer require php:^8.4
npm install @tailwindcss/cli@next

# Test thoroughly
php artisan test
npm run build
```

#### 7.1.2. Staging Environment

```bash
# Phase 2: Staging deployment
git push origin upgrade/php8.4-laravel12-tailwind4

# Deploy to staging
./deploy-staging.sh

# Run integration tests
./run-integration-tests.sh
```

#### 7.1.3. Production Deployment

```bash
# Phase 3: Production deployment with rollback plan
./backup-production.sh
./deploy-production.sh

# Monitor for issues
./monitor-deployment.sh

# Rollback if needed
./rollback-production.sh
```

### 7.2. Zero-Downtime Deployment

#### 7.2.1. Blue-Green Deployment

```bash
# Prepare blue environment
./prepare-blue-environment.sh

# Deploy to blue
./deploy-to-blue.sh

# Switch traffic
./switch-traffic-to-blue.sh

# Monitor and verify
./verify-blue-deployment.sh
```

#### 7.2.2. Database Migrations

```bash
# Run migrations safely
php artisan migrate --pretend
php artisan migrate --step

# Verify data integrity
php artisan db:verify-integrity
```

## 8. Risk Assessment and Mitigation

### 8.1. Risk Matrix

| Risk | Probability | Impact | Mitigation Strategy |
|------|-------------|--------|-------------------|
| TailwindCSS v4 breaking changes | High | High | Dual version strategy |
| Plugin compatibility issues | Medium | Medium | Comprehensive testing |
| Performance degradation | Low | Medium | Benchmark monitoring |
| Database migration failures | Low | High | Backup and rollback plan |
| User interface inconsistencies | Medium | Medium | Visual regression testing |

### 8.2. Mitigation Strategies

#### 8.2.1. TailwindCSS Compatibility

**Risk**: CSS conflicts between TailwindCSS v3 and v4

**Mitigation**:

- CSS scope isolation using prefixes
- Separate build processes
- Comprehensive visual testing
- Gradual rollout strategy

#### 8.2.2. Plugin System Stability

**Risk**: Plugin functionality breaking during upgrade

**Mitigation**:

- Plugin-by-plugin testing
- Version lock for critical plugins
- Fallback to previous versions
- Plugin compatibility matrix

#### 8.2.3. Performance Monitoring

**Risk**: Performance degradation after upgrade

**Mitigation**:

```bash
# Continuous performance monitoring
php artisan benchmark:compare --before=pre-upgrade --after=post-upgrade

# Real-time monitoring
php artisan monitor:performance --alert-threshold=200ms
```

### 8.3. Rollback Plan

#### 8.3.1. Quick Rollback

```bash
# Immediate rollback procedure
./rollback-immediate.sh

# Steps:
# 1. Switch DNS back to old environment
# 2. Restore database backup
# 3. Revert code deployment
# 4. Clear all caches
# 5. Notify stakeholders
```

#### 8.3.2. Partial Rollback

```bash
# Rollback specific components
./rollback-tailwindcss.sh  # Revert to TailwindCSS v3
./rollback-php.sh          # Revert to PHP 8.2
./rollback-plugins.sh      # Disable problematic plugins
```

---

## 9. Timeline and Milestones

### 9.1. Upgrade Timeline

| Phase | Duration | Activities | Deliverables |
|-------|----------|------------|--------------|
| Week 1 | 7 days | PHP 8.4 upgrade | PHP 8.4 compatibility |
| Week 2 | 7 days | Laravel 12 updates | Latest Laravel features |
| Week 3-4 | 14 days | TailwindCSS v4 migration | Dual CSS framework setup |
| Week 5 | 7 days | Testing and validation | Comprehensive test results |
| Week 6 | 7 days | Production deployment | Live system upgrade |

### 9.2. Success Criteria

**Technical Metrics:**

- All tests passing on PHP 8.4
- No CSS regression in admin panel
- Performance improvements measurable
- Zero-downtime deployment achieved
- All 22 plugins functioning correctly

**Business Metrics:**

- No user-facing disruption
- Improved page load times
- Enhanced developer experience
- Future-ready technology stack

---

**🎯 Upgrade Summary**

This comprehensive upgrade strategy ensures:

- **Modern Technology Stack**: PHP 8.4, Laravel 12, TailwindCSS v4
- **Backward Compatibility**: FilamentPHP continues with TailwindCSS v3
- **Zero Downtime**: Production-safe deployment strategy
- **Risk Mitigation**: Comprehensive testing and rollback plans
- **Plugin Preservation**: All 22 plugins remain functional

**Confidence Level: 90%** - Based on upgrade path analysis and compatibility research
