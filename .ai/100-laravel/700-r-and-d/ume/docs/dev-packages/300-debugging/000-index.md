# Debugging Packages

This directory contains documentation for all debugging-related packages used in the project.

## 1. Overview

Debugging tools help identify and fix issues in the application by providing insights into code execution, performance, and behavior. This project uses a variety of debugging tools to facilitate efficient troubleshooting and development.

## 2. Debugging Packages

| Package | Description | Documentation |
|---------|-------------|---------------|
| [Laravel Debugbar](010-debugbar.md) | Performance and debugging toolbar | [010-debugbar.md](010-debugbar.md) |
| [Ray](020-ray.md) | Debug with Ray app | [020-ray.md](020-ray.md) |
| [Laravel Pail](030-pail.md) | Log viewer | [030-pail.md](030-pail.md) |
| [Laravel Telescope](040-telescope.md) | Debug assistant | [040-telescope.md](040-telescope.md) |
| [Horizon Watcher](050-horizon-watcher.md) | Queue monitoring | [050-horizon-watcher.md](050-horizon-watcher.md) |
| [Web Tinker](060-web-tinker.md) | In-browser REPL | [060-web-tinker.md](060-web-tinker.md) |

## 3. Debugging Workflow

The typical debugging workflow in this project includes:

1. Using Laravel Debugbar for performance monitoring
2. Using Ray for detailed debugging during development
3. Monitoring logs with Laravel Pail
4. Using Telescope for request and event monitoring
5. Monitoring queues with Horizon Watcher
6. Experimenting with code using Web Tinker

## 4. Composer Commands

This project includes several Composer scripts related to debugging:

```bash
# Start development server with Pail log viewer
composer dev

# Monitor Laravel Pulse
composer monitor:start
composer monitor:check
```

## 5. Configuration

Debugging tools are configured through:

- `.env` - Environment-specific settings
- `config/debugbar.php` - Debugbar configuration
- `config/telescope.php` - Telescope configuration
- `config/ray.php` - Ray configuration

## 6. Best Practices

- Only enable debugging tools in development environments
- Use the appropriate tool for the task at hand
- Configure tools to exclude sensitive information
- Regularly clear debug data to prevent performance issues
- Use debugging tools to identify performance bottlenecks
