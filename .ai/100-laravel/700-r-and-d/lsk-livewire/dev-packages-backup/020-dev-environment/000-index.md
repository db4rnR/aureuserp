# Development Environment Packages
# Development Environment Packages Documentation

## 1. Overview

This section documents all development environment packages available in the project's require-dev dependencies. These tools help create consistent, efficient, and reliable development environments.

## 2. Local Development Environments

### 2.1. Docker-based Environments
- [Laravel Sail](010-sail.md) - Docker environment for Laravel
- [Docker Compose Configuration](015-docker-compose.md)

### 2.2. Virtual Machines
- [Laravel Homestead](020-homestead.md) - Pre-packaged Vagrant box

## 3. Environment Management

### 3.1. Configuration Management
- [PHP dotenv](025-dotenv.md) - Environment variable management
- [Environment Scripts](030-env-scripts.md) - Scripts for environment management

### 3.2. Database Management
- [Database Seeders](035-seeders.md) - Populating databases with test data
- [Database Migrations](040-migrations.md) - Managing database schema

## 4. Workflow Automation

### 4.1. Task Runners
- [Composer Scripts](045-composer-scripts.md) - Custom commands defined in composer.json
- [Task Automation](050-task-automation.md) - Automated development tasks

### 4.2. File Watchers
- [File Watchers](055-file-watchers.md) - Automatic actions on file changes
- [Hot Reloading](060-hot-reloading.md) - Immediate feedback during development

## 5. Local Services

### 5.1. Cache and Queue
- [Redis Configuration](065-redis.md) - Local Redis setup
- [Queue Configuration](070-queue.md) - Working with queues locally

### 5.2. Search and Storage
- [Meilisearch](075-meilisearch.md) - Local search engine
- [MinIO](080-minio.md) - S3-compatible local storage

## 6. Collaborative Development

### 6.1. Team Consistency
- [EditorConfig](085-editorconfig.md) - Maintaining consistent coding styles
- [Git Hooks](090-git-hooks.md) - Enforcing standards pre-commit

### 6.2. Documentation
- [API Documentation](095-api-docs.md) - Documenting APIs for team use
- [Inline Documentation](100-inline-docs.md) - Code documentation practices

## 7. Best Practices

- [Environment Parity](105-env-parity.md) - Matching production closely
- [Development Workflow](110-workflow.md) - Efficient development processes
- [Common Issues](115-common-issues.md) - Troubleshooting environment problems
This documentation covers all development environment tools and utilities.

## 1. Package List

The following development environment packages are used in this project:

| Package | Version | Description |
|---------|---------|-------------|
| laravel/sail | ^1.41 | Docker development environment |
| soloterm/solo | ^0.5.0 | Terminal tool |
| peckphp/peck | ^0.1.3 | PHP runner |
| ergebnis/composer-normalize | ^2.45.0 | Normalize composer.json |
| symfony/polyfill-php84 | ^1.31.0 | PHP 8.4 polyfill |

## 2. Development Workflow

Our development workflow leverages these tools to streamline the process:

### 2.1. Local Setup with Sail

Laravel Sail provides a Docker-based development environment.

### 2.2. Code Organization

Use Composer Normalize to keep the composer.json file consistently formatted.

### 2.3. PHP Runner

Peckphp/peck allows for easy running of PHP scripts for quick testing.

## 3. Common Commands

```sh
// Start the development environment
./vendor/bin/sail up

// Run artisan commands
./vendor/bin/sail artisan migrate

// Normalize composer.json
composer normalize

// Run PHP scripts
./vendor/bin/peck script.php
```
