# Phase 0: Package Installation & Configuration

**Version:** 1.3.0 **Date:** 2023-11-13 **Author:** AI Assistant **Status:** Updated **Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

1. [Phase 0: Package Installation \& Configuration](#phase-0-package-installation--configuration)
   1. [Overview](#overview)
   2. [Prerequisites](#prerequisites)
      1. [Required Prior Steps](#required-prior-steps)
      2. [Required Packages](#required-packages)
      3. [Required Knowledge](#required-knowledge)
      4. [Required Environment](#required-environment)
   3. [Estimated Time Requirements](#estimated-time-requirements)
   4. [Core Dependencies](#core-dependencies)
   5. [PHP Packages](#php-packages)
      1. [Production Dependencies](#production-dependencies)
         1. [Core Laravel Packages](#core-laravel-packages)
         2. [Frontend Packages](#frontend-packages)
         3. [Filament Admin Panel](#filament-admin-panel)
         4. [Spatie Packages](#spatie-packages)
         5. [Other Packages](#other-packages)
      2. [Development Dependencies](#development-dependencies)
         1. [Testing Packages](#testing-packages)
         2. [Code Quality Packages](#code-quality-packages)
         3. [Development Tools](#development-tools)
   6. [JavaScript Packages](#javascript-packages)
      1. [Main Dependencies](#main-dependencies)
         1. [Core Packages](#core-packages)
         2. [UI Packages](#ui-packages)
         3. [Build Tools](#build-tools)
      2. [Development Dependencies](#development-dependencies-1)
         1. [Testing Packages](#testing-packages-1)
         2. [Code Quality Packages](#code-quality-packages-1)
         3. [Development Tools](#development-tools-1)
   7. [Configuration Files](#configuration-files)
      1. [PHP Code Quality Tools](#php-code-quality-tools)
      2. [JavaScript Code Quality Tools](#javascript-code-quality-tools)
      3. [Testing Configuration](#testing-configuration)
   8. [Installation Steps](#installation-steps)
      1. [1. Install PHP Dependencies](#1-install-php-dependencies)
      2. [2. Install JavaScript Dependencies](#2-install-javascript-dependencies)
      3. [3. Publish Package Configurations](#3-publish-package-configurations)
   9. [Verification](#verification)
   10. [Troubleshooting](#troubleshooting)
       1. [Common Issues and Solutions](#common-issues-and-solutions)
   11. [Related Documents](#related-documents)
   12. [Version History](#version-history)
</details>

## Overview

This document provides detailed instructions for installing and configuring all required packages for the Enhanced
Laravel Application (ELA). It reflects the actual packages and configurations used in the project as defined in
`composer.json`, `package.json`, and various configuration files.

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps

- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed

### Required Packages

- Composer installed and configured
- Node.js (v22+) and pnpm (v10+) installed

### Required Knowledge

- Basic understanding of PHP and Laravel
- Familiarity with Composer and npm/pnpm package management
- Understanding of Laravel package configuration

### Required Environment

- PHP 8.4 or higher
- Laravel 12.x installed
- Git configured

## Estimated Time Requirements

| Task                             | Estimated Time  |
| -------------------------------- | --------------- |
| Install Core Dependencies        | 10 minutes      |
| Install PHP Production Packages  | 20 minutes      |
| Install PHP Development Packages | 15 minutes      |
| Install JavaScript Packages      | 15 minutes      |
| Configure Packages               | 30 minutes      |
| Verify Installation              | 10 minutes      |
| **Total**                        | **100 minutes** |

> **Note:** These time estimates assume a good internet connection and familiarity with Laravel package management.
> Actual time may vary based on download speeds and experience level.

## Core Dependencies

The project has the following core dependencies:

| Dependency   | Version | Purpose                                     |
| ------------ | ------- | ------------------------------------------- |
| PHP          | ^8.4    | Server-side scripting language              |
| Laravel      | ^12.0   | PHP web application framework               |
| Livewire     | ^3.6.1  | Full-stack framework for dynamic interfaces |
| Tailwind CSS | ^4.0.7  | Utility-first CSS framework                 |
| Vite         | ^6.0    | Frontend build tool                         |

## PHP Packages

### Production Dependencies

The following PHP packages are required for production:

#### Core Laravel Packages

| Package                    | Version | Purpose                                   |
| -------------------------- | ------- | ----------------------------------------- |
| laravel/framework          | ^12.0   | Core Laravel framework                    |
| laravel/tinker             | ^2.10.1 | REPL for Laravel                          |
| laravel/octane             | ^2.9    | High-performance application server       |
| runtime/frankenphp-symfony | ^0.2.0  | FrankenPHP integration for Laravel Octane |
| laravel/horizon            | ^5.32   | Queue monitoring dashboard                |
| laravel/pulse              | ^1.0    | Application monitoring                    |
| laravel/pennant            | ^1.16   | Feature flags management                  |
| laravel/sanctum            | ^4.0    | API token authentication                  |
| laravel/scout              | ^10.15  | Full-text search                          |
| laravel/fortify            | ^1.25   | Authentication scaffolding                |
| laravel/reverb             | ^1.5    | WebSockets server                         |

#### Frontend Packages

| Package           | Version | Purpose                             |
| ----------------- | ------- | ----------------------------------- |
| livewire/flux     | ^2.1.1  | UI component library for Livewire   |
| livewire/flux-pro | ^2.1    | Premium UI components for Livewire  |
| livewire/volt     | ^1.7.0  | Single-file components for Livewire |

#### Filament Admin Panel

| Package                                      | Version | Purpose                                |
| -------------------------------------------- | ------- | -------------------------------------- |
| filament/filament                            | ^3.3    | Admin panel framework                  |
| filament/spatie-laravel-media-library-plugin | ^3.3    | Media library integration for Filament |
| filament/spatie-laravel-tags-plugin          | ^3.3    | Tags integration for Filament          |
| filament/spatie-laravel-translatable-plugin  | ^3.3    | Translations integration for Filament  |
| bezhansalleh/filament-shield                 | ^3.3    | Permissions management for Filament    |
| shuvroroy/filament-spatie-laravel-backup     | ^2.2    | Backup management for Filament         |
| shuvroroy/filament-spatie-laravel-health     | ^2.3    | Health checks for Filament             |
| rmsramos/activitylog                         | ^1.0    | Activity log for Filament              |
| mvenghaus/filament-plugin-schedule-monitor   | ^3.0    | Schedule monitoring for Filament       |

#### Spatie Packages

| Package                          | Version | Purpose                          |
| -------------------------------- | ------- | -------------------------------- |
| spatie/laravel-permission        | ^6.17   | Role and permission management   |
| spatie/laravel-activitylog       | ^4.7    | Activity logging                 |
| spatie/laravel-backup            | ^9.3    | Application and database backups |
| spatie/laravel-comments          | ^2.2    | Comments system                  |
| spatie/laravel-comments-livewire | ^3.0    | Livewire components for comments |
| spatie/laravel-event-sourcing    | ^7.11   | Event sourcing implementation    |
| spatie/laravel-health            | ^1.34   | Application health monitoring    |
| spatie/laravel-medialibrary      | ^11.0   | Media management                 |
| spatie/laravel-model-states      | ^2.11   | State machine for models         |
| spatie/laravel-model-status      | ^1.18   | Status tracking for models       |
| spatie/laravel-schedule-monitor  | ^3.0    | Schedule monitoring              |
| spatie/laravel-settings          | ^3.4    | Application settings management  |
| spatie/laravel-sluggable         | ^3.7    | Slug generation for models       |
| spatie/laravel-tags              | ^4.5    | Tags management                  |

#### Other Packages

| Package                            | Version | Purpose                        |
| ---------------------------------- | ------- | ------------------------------ |
| hirethunk/verbs                    | ^0.7.0  | CQRS implementation            |
| godruoyi/php-snowflake             | ^3.2    | Snowflake ID generation        |
| intervention/image                 | ^3.11   | Image manipulation             |
| lab404/laravel-impersonate         | ^1.7    | User impersonation             |
| nnjeim/world                       | ^1.1    | Countries, states, cities data |
| php-http/curl-client               | ^2.3    | HTTP client                    |
| statikbe/laravel-cookie-consent    | ^1.10   | Cookie consent management      |
| staudenmeir/laravel-adjacency-list | ^1.25   | Hierarchical data structures   |
| tightenco/parental                 | ^1.4    | Single table inheritance       |
| typesense/typesense-php            | ^5.1    | Typesense search client        |
| wildside/userstamps                | ^3.0    | User tracking for models       |

### Development Dependencies

The following PHP packages are required for development:

#### Testing Packages

| Package                      | Version | Purpose                              |
| ---------------------------- | ------- | ------------------------------------ |
| pestphp/pest                 | ^3.8    | Testing framework built on PHPUnit   |
| pestphp/pest-plugin-laravel  | ^3.2    | Laravel plugin for Pest              |
| pestphp/pest-plugin-arch     | ^3.1    | Architecture testing plugin for Pest |
| brianium/paratest            | ^7.8    | Parallel testing for PHPUnit         |
| mockery/mockery              | ^1.6    | Mocking framework for testing        |
| laravel/dusk                 | ^8.3    | Browser testing for Laravel          |
| spatie/pest-plugin-snapshots | ^2.2    | Snapshot testing for Pest            |

#### Code Quality Packages

| Package                             | Version  | Purpose                     |
| ----------------------------------- | -------- | --------------------------- |
| laravel/pint                        | ^1.18    | PHP code style fixer        |
| larastan/larastan                   | ^3.4     | Static analysis for Laravel |
| nunomaduro/phpinsights              | ^2.13    | PHP quality checks          |
| rector/rector                       | ^2.0     | PHP code refactoring        |
| rector/type-perfect                 | ^2.1     | Type safety for Rector      |
| driftingly/rector-laravel           | ^2.0     | Laravel rules for Rector    |
| php-parallel-lint/php-parallel-lint | ^1.4     | PHP syntax checker          |
| infection/infection                 | ^0.29.14 | Mutation testing            |

#### Development Tools

| Package                                | Version    | Purpose                              |
| -------------------------------------- | ---------- | ------------------------------------ |
| laravel/pail                           | ^1.2.2     | Log viewer for Laravel               |
| laravel/sail                           | ^1.41      | Docker development environment       |
| laravel/telescope                      | ^5.7       | Application debugging and monitoring |
| barryvdh/laravel-debugbar              | ^3.15      | Debug bar for Laravel                |
| barryvdh/laravel-ide-helper            | ^3.5       | IDE helper for Laravel               |
| itsgoingd/clockwork                    | ^5.3       | PHP debugging tool                   |
| fakerphp/faker                         | ^1.23      | Library for generating fake data     |
| nunomaduro/collision                   | ^8.6       | Error handling for console and tests |
| spatie/laravel-ray                     | ^1.40      | Debugging tool                       |
| spatie/laravel-web-tinker              | ^1.10      | Web-based REPL for Laravel           |
| spatie/laravel-blade-comments          | ^1.4       | Comments in Blade templates          |
| spatie/laravel-horizon-watcher         | ^1.1       | Horizon monitoring                   |
| spatie/laravel-login-link              | ^1.6       | Login links for testing              |
| spatie/laravel-missing-page-redirector | ^2.11      | Redirect missing pages               |
| spatie/laravel-queueable-action        | ^2.16      | Queueable actions                    |
| laravel-shift/blueprint                | ^2.12      | Laravel application generator        |
| ergebnis/composer-normalize            | ^2.47      | Composer file normalizer             |
| roave/security-advisories              | dev-latest | Security advisories                  |
| symfony/polyfill-php84                 | ^1.32      | PHP 8.4 polyfill                     |
| symfony/var-dumper                     | ^7.2       | Variable dumper                      |
| jasonmccreary/laravel-test-assertions  | ^2.8       | Test assertions for Laravel          |
| peckphp/peck                           | ^0.1.3     | PHP testing tool                     |

## JavaScript Packages

### Main Dependencies

The following JavaScript packages are required:

#### Core Packages

| Package             | Version  | Purpose                                             |
| ------------------- | -------- | --------------------------------------------------- |
| @tailwindcss/vite   | ^4.1.6   | Tailwind CSS integration for Vite                   |
| autoprefixer        | ^10.4.21 | PostCSS plugin to parse CSS and add vendor prefixes |
| axios               | ^1.9.0   | Promise-based HTTP client                           |
| laravel-vite-plugin | ^1.2.0   | Laravel integration for Vite                        |
| tailwindcss         | ^4.1.6   | Utility-first CSS framework                         |
| vite                | ^6.3.5   | Frontend build tool                                 |

#### UI Packages

| Package                  | Version | Purpose                                    |
| ------------------------ | ------- | ------------------------------------------ |
| alpinejs                 | ^3.14.9 | Lightweight JavaScript framework           |
| @alpinejs/focus          | ^3.14.9 | Focus management for Alpine.js             |
| class-variance-authority | ^0.7.1  | Utility for creating variant components    |
| clsx                     | ^2.1.1  | Utility for constructing className strings |
| tailwind-merge           | ^3.3.0  | Utility for merging Tailwind CSS classes   |
| tailwindcss-animate      | ^1.0.7  | Animation utilities for Tailwind CSS       |

#### Build Tools

| Package                    | Version | Purpose                            |
| -------------------------- | ------- | ---------------------------------- |
| concurrently               | ^9.1.2  | Run multiple commands concurrently |
| vite-plugin-compression    | ^0.5.1  | Compression plugin for Vite        |
| vite-plugin-dynamic-import | ^1.6.0  | Dynamic import plugin for Vite     |
| vite-plugin-eslint         | ^1.8.1  | ESLint plugin for Vite             |
| vite-plugin-inspector      | ^1.0.4  | Inspector plugin for Vite          |
| typescript                 | ^5.8.3  | TypeScript language                |
| shiki                      | ^3.4.1  | Syntax highlighter                 |
| puppeteer                  | ^24.8.2 | Headless Chrome Node.js API        |

### Development Dependencies

The following JavaScript packages are required for development:

#### Testing Packages

| Package          | Version | Purpose                      |
| ---------------- | ------- | ---------------------------- |
| @playwright/test | ^1.52.0 | End-to-end testing framework |
| vitest           | ^3.1.3  | Testing framework for Vite   |

#### Code Quality Packages

| Package                          | Version | Purpose                          |
| -------------------------------- | ------- | -------------------------------- |
| @eslint/js                       | ^9.26.0 | ESLint JavaScript linting        |
| eslint                           | ^9.26.0 | JavaScript linter                |
| eslint-config-prettier           | ^10.1.5 | Prettier integration for ESLint  |
| eslint-plugin-prettier           | ^5.4.0  | Prettier plugin for ESLint       |
| eslint-plugin-react              | ^7.37.5 | React plugin for ESLint          |
| eslint-plugin-react-hooks        | ^5.2.0  | React Hooks plugin for ESLint    |
| prettier                         | ^3.5.3  | Code formatter                   |
| prettier-plugin-organize-imports | ^4.1.0  | Import organizer for Prettier    |
| prettier-plugin-tailwindcss      | ^0.6.11 | Tailwind CSS plugin for Prettier |
| typescript-eslint                | ^8.32.1 | TypeScript plugin for ESLint     |

#### Development Tools

| Package                  | Version   | Purpose                            |
| ------------------------ | --------- | ---------------------------------- |
| @types/node              | ^22.15.18 | TypeScript definitions for Node.js |
| chokidar                 | ^4.0.3    | File watcher                       |
| laravel-echo             | ^2.1.3    | Laravel Echo for WebSockets        |
| lint-staged              | ^16.0.0   | Run linters on staged files        |
| pusher-js                | ^8.4.0    | Pusher client for JavaScript       |
| rimraf                   | ^6.0.1    | Deep deletion module               |
| rollup-plugin-visualizer | ^5.14.0   | Visualize bundle size              |
| simple-git-hooks         | ^2.13.0   | Git hooks                          |

## Configuration Files

The project includes various configuration files for PHP and JavaScript tools:

### PHP Code Quality Tools

| File            | Purpose                    |
| --------------- | -------------------------- |
| pint.json       | Laravel Pint configuration |
| phpstan.neon    | PHPStan configuration      |
| rector.php      | Rector configuration       |
| phpinsights.php | PHP Insights configuration |
| infection.json5 | Infection configuration    |

### JavaScript Code Quality Tools

| File           | Purpose                  |
| -------------- | ------------------------ |
| .eslintrc.js   | ESLint configuration     |
| .prettierrc.js | Prettier configuration   |
| tsconfig.json  | TypeScript configuration |

### Testing Configuration

| File                 | Purpose                  |
| -------------------- | ------------------------ |
| phpunit.xml          | PHPUnit configuration    |
| pest.config.php      | Pest configuration       |
| playwright.config.js | Playwright configuration |
| vitest.config.js     | Vitest configuration     |

## Installation Steps

Follow these steps to install all required packages:

### 1. Install PHP Dependencies

```bash
## Install production dependencies
composer require laravel/framework:"^12.0" \
    laravel/tinker:"^2.10.1" \
    laravel/octane:"^2.9" \
    runtime/frankenphp-symfony:"^0.2.0" \
    laravel/horizon:"^5.32" \
    laravel/pulse:"^1.0" \
    laravel/pennant:"^1.16" \
    laravel/sanctum:"^4.0" \
    laravel/scout:"^10.15" \
    laravel/fortify:"^1.25" \
    laravel/reverb:"^1.5" \
    livewire/flux:"^2.1.1" \
    livewire/flux-pro:"^2.1" \
    livewire/volt:"^1.7.0" \
    -Wo

## Install Filament and plugins
composer require filament/filament:"^3.3" \
    filament/spatie-laravel-media-library-plugin:"^3.3" \
    filament/spatie-laravel-tags-plugin:"^3.3" \
    filament/spatie-laravel-translatable-plugin:"^3.3" \
    bezhansalleh/filament-shield:"^3.3" \
    shuvroroy/filament-spatie-laravel-backup:"^2.2" \
    shuvroroy/filament-spatie-laravel-health:"^2.3" \
    rmsramos/activitylog:"^1.0" \
    mvenghaus/filament-plugin-schedule-monitor:"^3.0" \
    -Wo

## Install Spatie packages
composer require spatie/laravel-permission:"^6.17" \
    spatie/laravel-activitylog:"^4.7" \
    spatie/laravel-backup:"^9.3" \
    spatie/laravel-comments:"^2.2" \
    spatie/laravel-comments-livewire:"^3.0" \
    spatie/laravel-event-sourcing:"^7.11" \
    spatie/laravel-health:"^1.34" \
    spatie/laravel-medialibrary:"^11.0" \
    spatie/laravel-model-states:"^2.11" \
    spatie/laravel-model-status:"^1.18" \
    spatie/laravel-schedule-monitor:"^3.0" \
    spatie/laravel-settings:"^3.4" \
    spatie/laravel-sluggable:"^3.7" \
    spatie/laravel-tags:"^4.5" \
    -Wo

## Install other packages
composer require hirethunk/verbs:"^0.7.0" \
    godruoyi/php-snowflake:"^3.2" \
    intervention/image:"^3.11" \
    lab404/laravel-impersonate:"^1.7" \
    nnjeim/world:"^1.1" \
    php-http/curl-client:"^2.3" \
    statikbe/laravel-cookie-consent:"^1.10" \
    staudenmeir/laravel-adjacency-list:"^1.25" \
    tightenco/parental:"^1.4" \
    typesense/typesense-php:"^5.1" \
    wildside/userstamps:"^3.0" \
    -Wo

## Install development dependencies
composer require --dev laravel/pint:"^1.18" \
    larastan/larastan:"^3.4" \
    nunomaduro/phpinsights:"^2.13" \
    rector/rector:"^2.0" \
    rector/type-perfect:"^2.1" \
    driftingly/rector-laravel:"^2.0" \
    php-parallel-lint/php-parallel-lint:"^1.4" \
    infection/infection:"^0.29.14" \
    laravel/pail:"^1.2.2" \
    laravel/sail:"^1.41" \
    laravel/telescope:"^5.7" \
    barryvdh/laravel-debugbar:"^3.15" \
    barryvdh/laravel-ide-helper:"^3.5" \
    itsgoingd/clockwork:"^5.3" \
    fakerphp/faker:"^1.23" \
    nunomaduro/collision:"^8.6" \
    pestphp/pest:"^3.8" \
    pestphp/pest-plugin-laravel:"^3.2" \
    pestphp/pest-plugin-arch:"^3.1" \
    brianium/paratest:"^7.8" \
    mockery/mockery:"^1.6" \
    laravel/dusk:"^8.3" \
    spatie/pest-plugin-snapshots:"^2.2" \
    -Wo
```

### 2. Install JavaScript Dependencies

```bash
## Install 010-ddl dependencies
pnpm install @tailwindcss/vite@"^4.1.6" \
    autoprefixer@"^10.4.21" \
    axios@"^1.9.0" \
    laravel-vite-plugin@"^1.2.0" \
    tailwindcss@"^4.1.6" \
    vite@"^6.3.5" \
    alpinejs@"^3.14.9" \
    @alpinejs/focus@"^3.14.9" \
    class-variance-authority@"^0.7.1" \
    clsx@"^2.1.1" \
    tailwind-merge@"^3.3.0" \
    tailwindcss-animate@"^1.0.7" \
    concurrently@"^9.1.2" \
    vite-plugin-compression@"^0.5.1" \
    vite-plugin-dynamic-import@"^1.6.0" \
    vite-plugin-eslint@"^1.8.1" \
    vite-plugin-inspector@"^1.0.4" \
    typescript@"^5.8.3" \
    shiki@"^3.4.1" \
    puppeteer@"^24.8.2"

## Install development dependencies
pnpm install -D @playwright/test@"^1.52.0" \
    vitest@"^3.1.3" \
    @eslint/js@"^9.26.0" \
    eslint@"^9.26.0" \
    eslint-config-prettier@"^10.1.5" \
    eslint-plugin-prettier@"^5.4.0" \
    eslint-plugin-react@"^7.37.5" \
    eslint-plugin-react-hooks@"^5.2.0" \
    prettier@"^3.5.3" \
    prettier-plugin-organize-imports@"^4.1.0" \
    prettier-plugin-tailwindcss@"^0.6.11" \
    typescript-eslint@"^8.32.1" \
    @types/node@"^22.15.18" \
    chokidar@"^4.0.3" \
    laravel-echo@"^2.1.3" \
    lint-staged@"^16.0.0" \
    pusher-js@"^8.4.0" \
    rimraf@"^6.0.1" \
    rollup-plugin-visualizer@"^5.14.0" \
    simple-git-hooks@"^2.13.0"
```
### 3. Publish Package Configurations

```bash
## Publish Laravel package configurations
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan vendor:publish --provider="Laravel\Pennant\PennantServiceProvider"
php artisan vendor:publish --tag="pulse-config"
php artisan vendor:publish --tag="pulse-migrations"
php artisan vendor:publish --provider="Laravel\Octane\OctaneServiceProvider"
php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"
php artisan vendor:publish --provider="Laravel\Telescope\TelescopeServiceProvider"

## Publish Spatie package configurations
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider" --tag="backup-config"
php artisan vendor:publish --provider="Spatie\Comments\CommentsServiceProvider" --tag="comments-migrations"
php artisan vendor:publish --provider="Spatie\Comments\CommentsServiceProvider" --tag="comments-config"
php artisan vendor:publish --provider="Spatie\Comments\CommentsServiceProvider" --tag="comments-views"
php artisan vendor:publish --provider="Spatie\EventSourcing\EventSourcingServiceProvider"
php artisan vendor:publish --provider="Spatie\Health\HealthServiceProvider" --tag="health-config"
php artisan vendor:publish --tag="health-migrations"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-config"
php artisan vendor:publish --provider="Spatie\ModelStates\ModelStatesServiceProvider" --tag="model-states-config"
php artisan vendor:publish --provider="Spatie\ModelStatus\ModelStatusServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Spatie\ScheduleMonitor\ScheduleMonitorServiceProvider" --tag="schedule-monitor-config"
php artisan vendor:publish --provider="Spatie\ScheduleMonitor\ScheduleMonitorServiceProvider" --tag="schedule-monitor-migrations"
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-migrations"
php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-config"

## Publish Filament configurations
php artisan filament:install --panels
php artisan vendor:publish --tag="filament-config"

## Configure Filament Shield
php artisan shield:setup
php artisan shield:install admin
php artisan shield:generate --all
php artisan shield:super-admin

## Run migrations
php artisan migrate
```

## Verification

After installing all packages, verify the installation:

1. Check that all packages are installed:
   ```bash
   composer show | grep -E 'filament|spatie|livewire|laravel|typesense|intervention|hirethunk'
````

2. Check that all JavaScript packages are installed:

   ```bash
   pnpm list
   ```

3. Check that all migrations have been run:

   ```bash
   php artisan migrate:status
   ```

4. Start the development server:

   ```bash
   php artisan serve
   ```

5. Start Vite for frontend assets:

   ```bash
   pnpm run dev
   ```

6. Check that Filament is working by visiting `http://localhost:8000/admin` in your browser.

## Troubleshooting

If you encounter any issues during the installation process, try the following:

1. Clear the cache:

   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. Regenerate the autoload files:

   ```bash
   composer dump-autoload
   ```

3. Check the Laravel logs:

   ```bash
   php artisan pail
   ```

4. If you encounter issues with Filament Shield, try reinstalling it:

   ```bash
   php artisan shield:install admin --fresh
   ```

5. If you encounter issues with database migrations, try refreshing the database:

   ```bash
   php artisan migrate:fresh --seed
   ```

   > **Note:** Be careful with the `migrate:fresh` command as it will drop all tables and recreate them.

### Common Issues and Solutions

1. **Composer Memory Limit**

   - Problem: Composer runs out of memory during package installation
   - Solution: Increase PHP memory limit in php.ini or use the `COMPOSER_MEMORY_LIMIT=-1` environment variable

2. **Package Compatibility Issues**

   - Problem: Packages have conflicting dependencies
   - Solution:
     - Check package versions and requirements
     - Update composer.json to specify compatible versions
     - Run `composer update` to resolve dependencies

3. **Filament Admin Panel Issues**

   - Problem: Cannot access Filament admin panel
   - Solution:
     - Ensure Filament is properly installed and configured
     - Check for any errors in the Laravel log
     - Verify that the admin panel route is correct

4. **Laravel Octane Issues**

   - Problem: Laravel Octane fails to start
   - Solution:
     - Ensure FrankenPHP is properly installed
     - Check for any errors in the Laravel log
     - Verify that the Octane configuration is correct

5. **Vite Issues**
   - Problem: Vite fails to compile assets
   - Solution:
     - Ensure Node.js and pnpm are properly installed
     - Check for any errors in the Vite log
     - Verify that the Vite configuration is correct

## Related Documents

- [Laravel Installation](020-environment-setup/020-laravel-installation.md) - For setting up the Laravel framework
- [Spatie Settings Setup](030-core-components/020-spatie-settings-setup.md) - For configuring Spatie Laravel Settings
- [CQRS Configuration](030-core-components/030-cqrs-configuration.md) - For configuring CQRS with hirethunk/verbs
- [Filament Configuration](030-core-components/040-filament-configuration.md) - For configuring Filament admin panel

## Version History

| Version | Date       | Changes                                                                            | Author       |
| ------- | ---------- | ---------------------------------------------------------------------------------- | ------------ |
| 1.0.0   | 2025-05-15 | Initial version                                                                    | AI Assistant |
| 1.1.0   | 2025-05-16 | Updated package versions and added missing packages                                | AI Assistant |
| 1.2.0   | 2025-05-17 | Standardized document title and metadata                                           | AI Assistant |
| 1.3.0   | 2025-05-17 | Added standardized prerequisites, estimated time requirements, and version history | AI Assistant |

---

**Previous Step:** [Laravel Installation](020-environment-setup/020-laravel-installation.md) | **Next Step:**
[Spatie Settings Setup](030-core-components/020-spatie-settings-setup.md)
