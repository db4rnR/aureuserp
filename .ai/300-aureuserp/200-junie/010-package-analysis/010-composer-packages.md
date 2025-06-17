# Composer Packages Configuration

## 3.1 Overview

This document provides detailed information about the Composer (PHP) packages used in the AureusERP project. It includes each package's primary purpose, configuration requirements, and the principles, patterns, and practices relevant to their usage.

AureusERP uses a wide range of PHP packages to provide functionality for:
- Core Laravel framework and extensions
- Admin panel and UI components (Filament)
- Media management
- Backup and monitoring
- Activity logging
- Health checks
- Development and testing tools

## 3.2 Core Packages

### 3.2.1 Laravel Framework

**Package:** `laravel/framework`

**Primary Purpose:**
Laravel is the core PHP framework that AureusERP is built upon. It provides the foundation for routing, middleware, dependency injection, database access, and many other essential features.

**Configuration Requirements:**
- Configuration files in the `config/` directory
- Environment variables in `.env` file
- Service providers registered in `config/app.php`

**Principles and Patterns:**
- MVC (Model-View-Controller) architecture
- Service Container for dependency injection
- Facades for static-like interfaces to services
- Middleware for HTTP request filtering
- Eloquent ORM for database interactions

### 3.2.2 Laravel Octane

**Package:** `laravel/octane`

**Primary Purpose:**
Octane supercharges Laravel's performance by serving the application using high-powered application servers like Swoole or RoadRunner, offering significant performance improvements.

**Configuration Requirements:**
- Configuration file: `config/octane.php`
- Server requirements: Swoole PHP extension or RoadRunner
- Published using: `php artisan octane:install`

**Principles and Patterns:**
- Long-running process model (vs traditional PHP request lifecycle)
- Request isolation to prevent state leakage
- Worker management for handling concurrent requests
- Careful management of global state and static properties

### 3.2.3 Laravel Horizon

**Package:** `laravel/horizon`

**Primary Purpose:**
Horizon provides a beautiful dashboard and code-driven configuration for Laravel's Redis queue system, allowing for easy monitoring and management of queue workers.

**Configuration Requirements:**
- Configuration file: `config/horizon.php`
- Redis connection configured in `config/database.php`
- Published using: `php artisan horizon:install`

**Principles and Patterns:**
- Queue worker management and auto-balancing
- Job monitoring and metrics collection
- Failed job handling and retry mechanisms
- Process supervision and automatic restarts

### 3.2.4 Laravel Pulse

**Package:** `laravel/pulse`

**Primary Purpose:**
Pulse provides real-time application performance monitoring and metrics for Laravel applications, helping to identify bottlenecks and issues.

**Configuration Requirements:**
- Configuration file: `config/pulse.php`
- Database migrations: `php artisan pulse:install`
- Scheduled commands in `app/Console/Kernel.php`

**Principles and Patterns:**
- Real-time performance monitoring
- Metric collection and aggregation
- Dashboard visualization of application health
- Sampling of slow database queries, jobs, and requests

### 3.2.5 Laravel Scout

**Package:** `laravel/scout`

**Primary Purpose:**
Scout provides a driver-based solution for adding full-text search to Eloquent models, making it easy to implement search functionality in the application.

**Configuration Requirements:**
- Configuration file: `config/scout.php`
- Search driver configuration (Typesense in this project)
- Model preparation with the `Searchable` trait

**Principles and Patterns:**
- Driver-based search abstraction
- Indexing and searching of Eloquent models
- Queueable indexing operations
- Custom search algorithms and filtering

## 3.3 Filament Packages

### 3.3.1 Filament Core

**Package:** `filament/filament`

**Primary Purpose:**
Filament is a TALL stack (Tailwind, Alpine, Laravel, Livewire) admin panel for Laravel that provides a beautiful, responsive interface for managing application data.

**Configuration Requirements:**
- Configuration file: `config/filament.php`
- Published assets: `php artisan filament:install`
- Resources defined in `app/Filament/Resources/`

**Principles and Patterns:**
- Resource-based CRUD operations
- Form and table builders
- Panel and page organization
- Widget system for dashboard customization

### 3.3.2 Filament Shield

**Package:** `bezhansalleh/filament-shield`

**Primary Purpose:**
Shield provides role and permission management for Filament, allowing for fine-grained access control to resources and pages.

**Configuration Requirements:**
- Configuration file: `config/filament-shield.php`
- Published using: `php artisan shield:install`
- Database migrations for roles and permissions tables

**Principles and Patterns:**
- Role-based access control (RBAC)
- Permission generation from Filament resources
- Policy registration and enforcement
- Super admin role configuration

### 3.3.3 Filament Curator

**Package:** `awcodes/filament-curator`

**Primary Purpose:**
Curator is a media library manager for Filament that provides a user-friendly interface for uploading, organizing, and selecting media files.

**Configuration Requirements:**
- Configuration file: `config/filament-curator.php`
- Published using: `php artisan curator:install`
- Database migrations for media tables

**Principles and Patterns:**
- Media organization with collections
- Image manipulation and optimization
- File type validation and security
- Integration with Spatie Media Library

### 3.3.4 Filament TipTap Editor

**Package:** `awcodes/filament-tiptap-editor`

**Primary Purpose:**
TipTap Editor provides a rich text editor for Filament forms, allowing for advanced content editing with formatting options.

**Configuration Requirements:**
- Configuration file: `config/filament-tiptap-editor.php`
- Published using: `php artisan vendor:publish --tag=filament-tiptap-editor-config`
- Custom profile configuration for editor features

**Principles and Patterns:**
- WYSIWYG editing experience
- Content sanitization and validation
- Extensible editor with plugins
- Customizable toolbar and features

## 3.4 Spatie Packages

### 3.4.1 Laravel Media Library

**Package:** `spatie/laravel-medialibrary`

**Primary Purpose:**
Media Library provides a file management system for Laravel applications, making it easy to associate files with Eloquent models and perform conversions.

**Configuration Requirements:**
- Configuration file: `config/media-library.php`
- Database migrations: `php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"`
- Model preparation with the `HasMedia` trait

**Principles and Patterns:**
- Model association with media files
- Automatic file conversions and optimizations
- Responsive images generation
- Disk management and CDN integration

### 3.4.2 Laravel Backup

**Package:** `spatie/laravel-backup`

**Primary Purpose:**
Backup provides an easy way to backup Laravel applications, including the database and files, to various storage systems.

**Configuration Requirements:**
- Configuration file: `config/backup.php`
- Scheduled commands in `app/Console/Kernel.php`
- Storage configuration in `config/filesystems.php`

**Principles and Patterns:**
- Database dumping strategies
- File selection and exclusion
- Backup rotation and cleanup
- Notification system for backup status

### 3.4.3 Laravel Activity Log

**Package:** `spatie/laravel-activitylog`

**Primary Purpose:**
Activity Log provides an easy way to log the activities of users and models in Laravel applications, making it easy to track changes.

**Configuration Requirements:**
- Configuration file: `config/activitylog.php`
- Database migrations: `php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="migrations"`
- Model preparation with the `LogsActivity` trait

**Principles and Patterns:**
- Automatic logging of model changes
- Custom activity descriptions
- Log cleanup and retention policies
- Activity attribution to users or systems

### 3.4.4 Laravel Health

**Package:** `spatie/laravel-health`

**Primary Purpose:**
Health provides a way to monitor the health of Laravel applications by running checks on various components like the database, cache, and storage.

**Configuration Requirements:**
- Configuration file: `config/health.php`
- Custom health checks in `app/Health/`
- Scheduled commands in `app/Console/Kernel.php`

**Principles and Patterns:**
- Health check registration and execution
- Result caching and notification
- Integration with monitoring systems
- Custom check implementation

## 3.5 Development Packages

### 3.5.1 Laravel Debugbar

**Package:** `barryvdh/laravel-debugbar`

**Primary Purpose:**
Debugbar provides a debug bar for Laravel applications, showing information about queries, views, routes, and more during development.

**Configuration Requirements:**
- Configuration file: `config/debugbar.php`
- Enabled only in development environment
- Published using: `php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"`

**Principles and Patterns:**
- Performance profiling and monitoring
- Query analysis and optimization
- Request and response inspection
- Memory usage tracking

### 3.5.2 Laravel Pint

**Package:** `laravel/pint`

**Primary Purpose:**
Pint is an opinionated PHP code style fixer for Laravel, helping to maintain consistent code style across the project.

**Configuration Requirements:**
- Configuration file: `pint.json`
- Run using: `./vendor/bin/pint` or `composer pint`
- CI integration for automated style checking

**Principles and Patterns:**
- PSR-12 code style enforcement
- Automated code formatting
- Customizable rules and presets
- Incremental fixing with specific paths

### 3.5.3 Pest PHP

**Package:** `pestphp/pest`

**Primary Purpose:**
Pest is a testing framework with a focus on simplicity and elegance, making it easy to write and maintain tests for Laravel applications.

**Configuration Requirements:**
- Configuration file: `phpunit.xml`
- Pest configuration: `pest.config.php`
- Test organization in `tests/` directory
- Run using: `./vendor/bin/pest` or `composer test`

**Principles and Patterns:**
- Expressive test syntax and expectations
- Higher-order testing with datasets
- Plugin system for extended functionality
- Architecture testing with pest-plugin-arch

## 3.6 Configuration Principles

When configuring Composer packages in AureusERP, follow these principles:

### 3.6.1 Configuration Management

- Store sensitive configuration in environment variables, not in code
- Use configuration files for package-specific settings
- Publish vendor assets and configurations when required
- Document configuration changes and requirements

### 3.6.2 Service Registration

- Register service providers in `config/app.php` when required
- Use auto-discovery when available
- Register facades for convenient access to services
- Use middleware in appropriate stacks (global, web, api)

### 3.6.3 Database Considerations

- Run migrations after installing packages that require database tables
- Consider foreign key constraints and relationships
- Use database transactions for data integrity
- Implement proper indexing for performance

### 3.6.4 Integration Patterns

- Use Laravel's service container for dependency injection
- Implement interfaces for swappable implementations
- Use events and listeners for loose coupling
- Follow Laravel's conventions for seamless integration
