# Phase 0.8: Frontend Setup with Livewire, Volt, and Flux

**Version:** 1.0.2
**Date:** 2025-05-17
**Author:** AI Assistant
**Status:** Updated
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
  - [Required Prior Steps](#required-prior-steps)
  - [Required Packages](#required-packages)
  - [Required Knowledge](#required-knowledge)
  - [Required Environment](#required-environment)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Frontend Stack](#frontend-stack)
- [Package Installation](#package-installation)
  - [PHP Packages](#php-packages)
  - [JavaScript Packages](#javascript-packages)
- [Configuration](#configuration)
  - [Vite Configuration](#vite-configuration)
  - [Tailwind CSS Configuration](#tailwind-css-configuration)
  - [Livewire Configuration](#livewire-configuration)
- [Directory Structure](#directory-structure)
- [Development Workflow](#development-workflow)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides detailed instructions for setting up the frontend of the Enhanced Laravel Application (ELA) using Livewire, Volt, and Flux. It reflects the actual configuration used in the project as defined in `composer.json`, `package.json`, and related configuration files.

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Package Installation](030-core-components/010-package-installation.md) completed
- [Filament Configuration](030-core-components/040-filament-configuration.md) completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed
- Livewire (`livewire/livewire`) installed
- Livewire Volt (`livewire/volt`) installed
- Livewire Flux (`livewire/flux`) installed
- Tailwind CSS (`tailwindcss`) installed

### Required Knowledge
- Basic understanding of Laravel
- Familiarity with Livewire components
- Understanding of TALL stack (Tailwind, Alpine.js, Laravel, Livewire)
- Knowledge of modern JavaScript and CSS

### Required Environment
- PHP 8.2 or higher
- Node.js 22.x or higher
- pnpm 10.x or higher (preferred over npm)
- Laravel 12.x

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Install PHP Packages | 10 minutes |
| Install JavaScript Packages | 10 minutes |
| Configure Vite | 15 minutes |
| Configure Tailwind CSS | 10 minutes |
| Configure Livewire | 15 minutes |
| Set Up Directory Structure | 10 minutes |
| Configure Development Workflow | 15 minutes |
| Test Frontend Setup | 15 minutes |
| **Total** | **100 minutes** |

> **Note:** These time estimates assume familiarity with Laravel and frontend development. Actual time may vary based on experience level and the complexity of your application.

## Frontend Stack

The ELA uses the following frontend stack:

| Technology | Version | Purpose |
|------------|---------|---------|
| Livewire | ^3.6.1 | Full-stack framework for dynamic interfaces |
| Volt | ^1.7.0 | Single-file components for Livewire |
| Flux | ^2.1.1 | UI component library for Livewire |
| Tailwind CSS | ^4.0.7 | Utility-first CSS framework |
| Vite | ^6.0 | Frontend build tool |

## Package Installation

### PHP Packages

Install the required PHP packages:

```bash
# Install Livewire components
composer require livewire/flux:"^2.1.1"
composer require livewire/flux-pro:"^2.1"
composer require livewire/volt:"^1.7.0"
```

### JavaScript Packages

Install the required JavaScript packages:

```bash
# Install frontend dependencies
npm install @tailwindcss/vite:"^4.0.7" \
            autoprefixer:"^10.4.20" \
            axios:"^1.7.4" \
            tailwindcss:"^4.0.7" \
            vite:"^6.0" \
            laravel-vite-plugin:"^1.0"
```

## Configuration

### Vite Configuration

Create or update `vite.config.js`:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

### Tailwind CSS Configuration

Create or update `tailwind.config.js`:

```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

### Livewire Configuration

Livewire is configured automatically when installed. You can publish the configuration if needed:

```bash
php artisan vendor:publish --tag=livewire:config
```

## Directory Structure

The frontend code follows this directory structure:

```
resources/
├── css/
│   └── app.css          # Main CSS file (imports Tailwind)
├── js/
│   └── app.js           # Main JavaScript file
└── views/
    ├── components/      # Blade components
    ├── layouts/         # Layout templates
    └── livewire/        # Livewire components
```

## Development Workflow

1. **Start the development server**:

   ```bash
   # Using the composer script
   composer dev

   # Or manually
   php artisan serve
   npm run dev
   ```

   The `composer dev` script runs multiple services concurrently:

   ```json
   "dev": [
       "Composer\\Config::disableProcessTimeout",
       "pnpm concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"php artisan pail --timeout=0\" \"pnpm run dev\" --names=server,queue,logs,vite"
   ]
   ```

2. **Creating Livewire Components**:

   ```bash
   # Create a Livewire component
   php artisan make:livewire Counter

   # Create a Volt component
   php artisan make:volt Counter
   ```

3. **Building for Production**:

   ```bash
   npm run build
   ```

## Best Practices

1. **Use Volt for Single-File Components**:

   ```php
   <?php

   use function Livewire\Volt\{state};

   state(['count' => 0]);

   $increment = fn () => $this->count++;
   $decrement = fn () => $this->count--;
   ?>

   <div>
       <h1>{{ $count }}</h1>
       <button wire:click="increment">+</button>
       <button wire:click="decrement">-</button>
   </div>
   ```

2. **Use Flux for UI Components**:

   ```php
   <x-flux-button>Click Me</x-flux-button>
   ```

3. **Organize CSS with Tailwind**:

   ```html
   <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow">
       <h2 class="text-xl font-semibold text-gray-800">Title</h2>
       <button class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
           Action
       </button>
   </div>
   ```

4. **Code Formatting**:

   The project uses Prettier for JavaScript and CSS formatting with the following configuration:

   ```javascript
   // .prettierrc.js
   export default {
     singleQuote: true,
     trailingComma: 'all',
     printWidth: 120,
     tabWidth: 2,
     // Additional configuration...
   };
   ```

## Troubleshooting

### Common Issues

1. **Vite Not Connecting**:
   - Problem: Vite development server not connecting
   - Solution: Check that the Vite server is running and the `@vite` directive is in your layout

2. **Livewire Components Not Updating**:
   - Problem: Livewire components not updating when changed
   - Solution: Clear the view cache with `php artisan view:clear`

3. **JavaScript Errors**:
   - Problem: JavaScript errors in the console
   - Solution: Check browser console for specific errors and ensure all dependencies are installed

4. **Tailwind Classes Not Applied**:
   - Problem: Tailwind classes not being applied
   - Solution: Ensure the class is included in the `content` section of `tailwind.config.js`

5. **Build Performance Issues**:
   - Problem: Slow build times
   - Solution: Use the `.npmrc` configuration with `engine-strict=true auto-install-peers=true strict-peer-dependencies=false`

## Related Documents

- [Package Installation](030-core-components/010-package-installation.md) - For installing required packages
- [Filament Configuration](030-core-components/040-filament-configuration.md) - For configuring Filament admin panel
- [Database Setup](040-database/010-database-setup.md) - For configuring the database for your application

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Standardized document title and metadata | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, and version history | AI Assistant |

---

**Previous Step:** [Filament Configuration](030-core-components/040-filament-configuration.md) | **Next Step:** [Database Setup](040-database/010-database-setup.md)
