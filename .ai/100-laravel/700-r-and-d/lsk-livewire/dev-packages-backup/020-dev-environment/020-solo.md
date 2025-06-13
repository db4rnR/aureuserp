# SoloTerm

## 1. Overview

SoloTerm (solo) is a command-line utility that helps manage terminal sessions, allowing you to run commands in the background, monitor their output, and manage their lifecycle.

### 1.1. Package Information

- **Package Name**: soloterm/solo
- **Version**: ^0.5.0
- **GitHub**: [https://github.com/soloterm/solo](https://github.com/soloterm/solo)
- **Documentation**: [https://github.com/soloterm/solo#readme](https://github.com/soloterm/solo#readme)

## 2. Key Features

- Run commands in background sessions
- View output from background processes
- Manage multiple terminal sessions
- Restart processes
- Monitor process status
- Improve development workflow

## 3. Usage Examples

### 3.1. Basic Usage

```sh
## Start a new background process
solo run "php artisan serve" --name=server

## List running processes
solo list

## View output from a process
solo output server

## Stop a process
solo stop server

## Stop all processes
solo stop --all
```

### 3.2. Laravel Development

```sh
## Start Laravel development environment
solo run "php artisan serve" --name=server
solo run "npm run dev" --name=vite
solo run "php artisan queue:work" --name=queue

## View all outputs in one window
solo output --all

## Restart a specific process
solo restart queue
```

## 4. Configuration

SoloTerm doesn't require specific configuration files:

```php
<?php

declare(strict_types=1);

// No special configuration needed
// The tool is ready to use out of the box
```

## 5. Best Practices

### 5.1. Standardized Process Names

Use standardized names for processes:

```sh
## Use consistent naming
solo run "php artisan serve" --name=server
solo run "pnpm run dev" --name=assets
solo run "php artisan queue:work" --name=queue
solo run "php artisan schedule:work" --name=scheduler
```

### 5.2. Integration with Composer Scripts

Add Solo commands to composer scripts:

```json
"scripts": {
    "dev:start": [
        "solo run \"php artisan serve\" --name=server",
        "solo run \"npm run dev\" --name=assets",
        "solo run \"php artisan queue:work\" --name=queue"
    ],
    "dev:stop": "solo stop --all"
}
```

### 5.3. Process Monitoring

Use Solo for monitoring long-running processes:

```sh
## Start with detailed logging
solo run "php artisan queue:work --verbose" --name=queue-verbose

## Monitor the output
solo output queue-verbose --follow

## In another terminal, generate some jobs
php artisan dispatch-test-jobs
```
