# Debugging Tools
# Debugging Packages Documentation

## 1. Overview

This section documents all debugging packages available in the project's require-dev dependencies. These tools help identify, trace, and resolve issues during development.

## 2. Debugging Tools

### 2.1. Core Debugging
- [Xdebug](010-xdebug.md) - Debugging and profiling PHP applications
- [Ray](015-ray.md) - Debug with Ray to fix problems faster

### 2.2. Laravel-Specific Debugging
- [Laravel Telescope](020-telescope.md) - Debug assistant for Laravel applications
- [Laravel Debugbar](025-debugbar.md) - Display a debug bar in your Laravel application

## 3. Error Handling and Reporting

### 3.1. Error Tracking
- [Ignition](030-ignition.md) - Beautiful error page for Laravel applications
- [Flare Client](035-flare.md) - Error reporting to Flare

### 3.2. Log Enhancement
- [Laravel Log Viewer](040-log-viewer.md) - View Laravel logs in a browser
- [Log Enhancers](045-log-enhancers.md) - Tools to improve logging

## 4. Performance Analysis

### 4.1. Profiling
- [Blackfire](050-blackfire.md) - Performance testing and profiling
- [XHProf](055-xhprof.md) - Hierarchical profiler for PHP

### 4.2. Database Query Analysis
- [Laravel Query Detector](060-query-detector.md) - Detect N+1 and duplicated queries
- [Database Profiling](065-db-profiling.md) - Database performance monitoring

## 5. Debugging Workflows

### 5.1. Common Debugging Scenarios
- [API Debugging](070-api-debugging.md)
- [Frontend Integration Debugging](075-frontend-debugging.md)
- [Queue and Job Debugging](080-queue-debugging.md)

### 5.2. Setup and Configuration
- [IDE Integration](085-ide-integration.md)
- [Docker Setup](090-docker-debugging.md)
- [Remote Debugging](095-remote-debugging.md)

## 6. Best Practices

- [Debugging Strategy](100-debugging-strategy.md)
- [Performance Optimization](105-performance-optimization.md)
- [Memory Management](110-memory-management.md)
This documentation covers all debugging and monitoring tools used in development.

## 1. Package List

The following debugging packages are used in this project:

| Package | Version | Description |
|---------|---------|-------------|
| barryvdh/laravel-debugbar | ^3.15.2 | Debug bar for Laravel |
| spatie/laravel-ray | ^1.40.2 | Ray debugging tool |
| laravel/pail | ^1.2.2 | Laravel log viewer |
| laravel/telescope | ^5.7.0 | Laravel app debugging and introspection |
| laravel/pulse | ^1.4.1 | Laravel server monitoring |
| spatie/laravel-horizon-watcher | ^1.1 | Monitor Horizon queues |
| spatie/laravel-web-tinker | ^1.10.1 | Web-based tinker |
| symfony/var-dumper | ^7.2.3 | Enhanced variable dumping |
| barryvdh/laravel-ide-helper | ^3.5.5 | IDE autocompletion and integration |

## 2. Common Debugging Techniques

### 2.1. Debugging with Debugbar

The Laravel Debugbar shows debugging information in the browser.

### 2.2. Debugging with Ray

Ray provides a desktop app to view debugging information.

### 2.3. Using Laravel Telescope

Telescope provides insights into requests, exceptions, logs, and more.

### 2.4. Monitoring with Laravel Pulse

Pulse provides server and application metrics.

## 3. Enabling Debugging Tools

```env
// In .env file
DEBUGBAR_ENABLED=true
TELESCOPE_ENABLED=true
PULSE_ENABLED=true
```
