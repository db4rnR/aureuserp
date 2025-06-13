# Development Environment Packages

This directory contains documentation for all development environment related packages used in the project.

## 1. Overview

Development environment packages help create a consistent, efficient, and reliable environment for development. These tools streamline setup, configuration, and workflow processes.

## 2. Development Environment Packages

| Package | Description | Documentation |
|---------|-------------|---------------|
| [Laravel Sail](010-sail.md) | Docker development environment | [010-sail.md](010-sail.md) |
| [Peck PHP](020-peck.md) | PHP development server | [020-peck.md](020-peck.md) |
| [Solo](030-solo.md) | Development environment tool | [030-solo.md](030-solo.md) |
| [Composer Normalize](040-composer-normalize.md) | Composer file normalizer | [040-composer-normalize.md](040-composer-normalize.md) |

## 3. Development Workflow

The typical development environment workflow in this project includes:

1. Setting up the environment with Laravel Sail or Peck
2. Using Solo for development tasks
3. Normalizing composer.json with Composer Normalize

## 4. Composer Commands

This project includes several Composer scripts related to the development environment:

```bash
# Start development server
composer dev

# Validate dependencies
composer validate:deps
```

## 5. Configuration

Development environment tools are configured through:

- `docker-compose.yml` - Docker configuration for Sail
- `.env` - Environment-specific settings
- `composer.json` - Composer scripts and configuration

## 6. Best Practices

- Use a consistent development environment across the team
- Document environment setup procedures
- Keep development environments as close to production as possible
- Regularly update development tools
- Use version control for configuration files
