# Project Overview

This document provides a high-level overview of the AureusERP project architecture and its key components.

## Core System

The project is built upon a modern PHP stack:

*   **Laravel Framework:** Version 12.x (as indicated by `composer.json`). Laravel serves as the foundational backend framework, providing robust features for routing, ORM (Eloquent), service container, and more.
*   **PHP:** Version 8.4 (as indicated by `composer.json`).

The project originated as a clone of the `aureuserp/aureuserp` repository.

## Administrative Interface & UI

*   **Filament (v4):** The administrative panel and core UI are built using Filament version 4.x (specifically `^4.0` in `composer.json`, noted by the user as a beta version). Filament provides a comprehensive suite of tools for rapidly building interactive and data-driven interfaces.
*   **Tailwind CSS (v4):** Styling is handled by Tailwind CSS version 4.x. This utility-first CSS framework allows for rapid UI development and customization. It's integrated into the project via PostCSS and compiled using Vite.

## Modular Architecture

The application features a highly modular architecture, composed of two main types of extensions:

### 1. Local Packages (`packages/` directory)

*   The `packages/` directory contains local forks of various third-party Filament plugins and other PHP packages.
*   These packages have been included directly in the project's repository, likely to allow for specific modifications or to manage dependencies that might not yet be fully compatible with Filament v4 or other project requirements.
*   A key modification to these packages, as per user information, was updating their dependencies from Filament v3 to Filament v4.

### 2. Webkul Plugins (`plugins/webkul/` directory)

*   A significant portion of the ERP's domain-specific functionality (e.g., Accounts, Sales, Products, Inventory) is encapsulated in a suite of plugins under the `plugins/webkul/` namespace.
*   These plugins appear to be first-party extensions or heavily customized modules tailored for AureusERP.
*   **Plugin Integration:** These Webkul plugins are discovered and registered with Filament through a dedicated `Webkul\Support\PluginManager`. This manager reads a manifest file located at `bootstrap/plugins.php`, which lists all active Webkul plugin classes. Each plugin class is then responsible for registering its own Filament resources, pages, navigation items, and other components.

This layered and modular approach allows for separation of concerns, easier maintenance, and scalability of the ERP system.
