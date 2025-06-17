
# User Model Enhancements (UME) - Implementation Plan

**Document Version:** 1.0
**Date:** 2024-07-26
**Target Stack:** Laravel 12, PHP 8.4, PostgreSQL/MySQL (via Eloquent), Livewire/Volt, Inertia/React, Inertia/Vue, Reverb, Typesense

**[NOTE] Audience & Purpose:** This document provides a detailed, step-by-step implementation plan for the User Model Enhancements (UME) Product Requirements Document (PRD). It is targeted at experienced developers who are *new* to PHP and the Laravel framework. It includes comprehensive code examples, explanations of core concepts, and rationale for implementation decisions. The plan follows a phased approach, starting with a Minimum Viable Product (MVP) and progressively adding features. UI components are presented in three variants: Livewire/Volt, Inertia/React, and Inertia/Vue.

## Table of Contents

-   [Introduction](#introduction)
-   [Prerequisites](#prerequisites)
-   [Core Concepts Glossary](#core-concepts-glossary)
-   [Implementation Timeline (Estimated)](#implementation-timeline-estimated)
-   [Progress Tracker](#progress-tracker)
-   [Phased Implementation Plan](#phased-implementation-plan)
    -   [Phase 0: Project Setup & Foundation (Est. 1-2 Days)](#phase-0-project-setup--foundation-est-1-2-days)
    -   [Phase 1: Core User Model & Architecture (Est. 3-5 Days)](#phase-1-core-user-model--architecture-est-3-5-days)
    -   [Phase 2: Authentication, Profile Basics & State Machine (Est. 5-7 Days)](#phase-2-authentication-profile-basics--state-machine-est-5-7-days)
    -   [Phase 3: Teams & Permissions (Est. 4-6 Days)](#phase-3-teams--permissions-est-4-6-days)
    -   [Phase 4: Real-time Foundation & Activity Logging (Est. 3-4 Days)](#phase-4-real-time-foundation--activity-logging-est-3-4-days)
    -   [Phase 5: Advanced Features & Real-time Implementation (Est. 7-10 Days)](#phase-5-advanced-features--real-time-implementation-est-7-10-days)
    -   [Phase 6: Polish, Testing & Deployment Prep (Est. 5-7 Days)](#phase-6-polish-testing--deployment-prep-est-5-7-days)
-   [Summary](#summary)

## Introduction

This plan translates the UME PRD (010-ume-prd.md) into actionable development steps. We will build a robust user management system by integrating various Laravel packages and adhering to specific architectural patterns like Services, Events/Listeners, State Machines, mandatory ULIDs, and Enums. The goal is to create a maintainable, scalable, and feature-rich foundation. We will prioritize an MVP and iteratively add functionality, deploying features using feature flags where appropriate.

## Prerequisites

1.  **PHP 8.4:** Installed locally and on deployment servers.
2.  **Composer:** PHP dependency manager installed globally. [https://getcomposer.org/](https://getcomposer.org/)
3.  **Node.js & npm/yarn:** For frontend asset compilation. [https://nodejs.org/](https://nodejs.org/)
4.  **Database:** PostgreSQL (preferred) or MySQL server running.
5.  **Web Server:** Nginx or Apache configured for Laravel.
6.  **Laravel CLI Installer:** `composer global require laravel/installer`
7.  **Git:** For version control.
8.  **Code Editor:** VS Code with relevant extensions (PHP Intelephense, Laravel Extension Pack, etc.) recommended.
9.  **(Optional but Recommended):** Docker Desktop for consistent development environments.
10. **(Optional):** Typesense server running for search functionality.
11. **(Optional):** Redis server running for Queues, Caching, Reverb state.

## Core Concepts Glossary

*(For developers new to PHP/Laravel)*

-   **Composer:** PHP's dependency manager, similar to npm or pip. Used to install packages specified in `composer.json`.
-   **Laravel:** A popular PHP web application framework following the Model-View-Controller (MVC) pattern (though we adapt it with Services). Known for its elegant syntax, extensive features, and strong community support.
-   **Eloquent ORM:** Laravel's Object-Relational Mapper. Provides an expressive, object-oriented way to interact with your database. Each database table has a corresponding "Model" class.
-   **Model:** An Eloquent class representing a database table (e.g., `App\Models\User` for the `users` table). Handles data retrieval, insertion, updating, and relationships.
-   **Migration:** A PHP class that defines changes to your database schema (creating tables, adding columns). Allows version control for your database structure. Run via `php artisan migrate`.
-   **Seeder:** A PHP class used to populate database tables with sample or default data. Run via `php artisan db:seed`.
-   **Factory:** A PHP class defining how to generate fake data for a specific Model, typically used for testing and seeding.
-   **Artisan:** Laravel's command-line interface. Provides helpful commands for tasks like running migrations (`migrate`), generating code (`make:model`, `make:controller`), clearing caches (`cache:clear`), etc.
-   **Routing:** Defines how incoming HTTP requests are mapped to specific application logic (usually Controller methods). Defined in `routes/web.php` (for web UI) and `routes/api.php` (for APIs).
-   **Controller:** A PHP class responsible for handling incoming requests, retrieving data (often via Models or Services), preparing data, and returning a response (often a View or JSON). We aim to keep Controllers thin.
-   **Service Layer:** A design pattern where business logic is encapsulated in dedicated PHP classes (`App\Services\*`). Controllers delegate complex tasks to Services, promoting reusability and testability.
-   **Service Container:** Laravel's powerful dependency injection container. Automatically resolves and injects dependencies (like Services or Repositories) into classes (Controllers, Listeners, etc.) via their constructors or methods.
-   **Facade:** Provides a static-like interface to services available in Laravel's service container (e.g., `Log::info()`, `Cache::get()`). Offers convenient access but should be used judiciously (prefer dependency injection where possible for better testability).
-   **Middleware:** Code that runs *before* or *after* a request reaches its final destination (Controller). Used for tasks like authentication, authorization, logging, modifying requests/responses.
-   **Blade:** Laravel's simple, yet powerful templating engine used for rendering HTML in traditional web routes. We will use it minimally, primarily for the base layout hosting our SPAs or Livewire components.
-   **Livewire:** A full-stack framework for Laravel that allows building dynamic interfaces using primarily PHP (Blade templates + PHP component classes), minimizing the need for custom JavaScript.
-   **Volt:** A functional API for Livewire, allowing single-file components (combining template and logic in one `.blade.php` file).
-   **Inertia.js:** Allows building modern single-page apps (SPAs) using classic server-side routing and controllers, but with JavaScript-based frontends (React, Vue, Svelte). Laravel acts as the API backend, serving data to the frontend framework which handles rendering.
-   **React/Vue:** Popular JavaScript libraries/frameworks for building user interfaces. Used with Inertia for the SPA variants.
-   **SFC (Single File Component):** A pattern (common in Vue, also possible in React with certain setups) where a component's template, logic, and styles are kept within a single file. Volt enables this for Livewire.
-   **Event:** A simple PHP class representing a significant occurrence in the application (e.g., `UserRegistered`, `OrderShipped`). Used for decoupling actions from their side effects. Dispatched using `event(new UserRegistered($user))`.
-   **Listener:** A PHP class that "listens" for specific Events and performs actions in response (e.g., sending an email when `UserRegistered` occurs). Registered in `App\Providers\EventServiceProvider`.
-   **Queue:** A system for deferring time-consuming tasks (like sending emails, processing images, broadcasting) to run in the background, improving application responsiveness. Laravel integrates with drivers like Redis, database, SQS. Listeners can be queued by implementing `ShouldQueue`.
-   **Horizon:** A dashboard and configuration system for Laravel's Redis queues. Provides monitoring and management capabilities.
-   **ULID (Universally Unique Lexicographically Sortable Identifier):** An alternative to UUIDs. They are time-sortable and generally more URL-friendly. We will use them as the primary public identifier for models.
-   **Enum (Enumeration):** A PHP 8.1+ feature defining a type with a fixed set of possible named values (e.g., `Status::Active`, `Status::Pending`). Improves code clarity and type safety compared to using strings or integers directly. Laravel allows casting model attributes to Enums.
-   **State Machine:** A behavioral design pattern that allows an object to alter its behavior when its internal state changes. Enforces valid transitions between states (e.g., a User account can go from `PendingValidation` to `Active`, but maybe not directly to `Suspended`). We use `spatie/laravel-model-states`.
-   **Trait:** A mechanism for code reuse in PHP. Allows defining methods and properties that can be included ("used") within multiple classes. Useful for adding common functionality (like ULID generation) to Models.
-   **Package:** A reusable set of PHP code, typically installed via Composer. The Laravel ecosystem relies heavily on packages (both first-party and third-party like Spatie's) to add functionality.
-   **Fortify:** A backend authentication scaffolding package for Laravel. Provides routes and logic for Login, Registration, Password Reset, Email Verification, and Two-Factor Authentication (2FA). It's frontend-agnostic.
-   **Sanctum:** Provides lightweight authentication for Single Page Applications (SPAs), mobile apps, and simple token-based APIs. Ideal for authenticating our Inertia SPAs.
-   **Passport:** A full OAuth2 server implementation for Laravel. Used for more complex API authentication scenarios, issuing various grant types (tokens, API keys). We'll use it as the default driver for external API consumers.
-   **Reverb:** Laravel's first-party, scalable WebSocket server. Used for handling real-time communication between the server and connected clients.
-   **Echo:** Laravel's JavaScript library that makes it easy to subscribe to WebSocket channels and listen for events broadcast by the Laravel backend (via Reverb). Used in our frontend components.
-   **Typesense:** An open-source, typo-tolerant search engine known for its speed and ease of use. We'll use it via Laravel Scout.
-   **Scout:** Laravel package providing a simple, driver-based solution for adding full-text search to Eloquent models. We'll use the Typesense driver.
-   **Pennant:** Laravel package for managing feature flags, allowing gradual rollout or A/B testing of features.
-   **TailwindCSS:** A utility-first CSS framework used for styling the application.

## Implementation Timeline (Estimated)

*Note: Timelines are rough estimates and may vary based on developer familiarity and unforeseen challenges. Assumes 1-2 dedicated developers.*

| Phase | Description                                          | Est. Duration |
| :---- | :--------------------------------------------------- | :------------ |
| 0     | Project Setup & Foundation                           | 1-2 Days      |
| 1     | Core User Model & Architecture                       | 3-5 Days      |
| 2     | Authentication, Profile Basics & State Machine (MVP) | 5-7 Days      |
| 3     | Teams & Permissions                                  | 4-6 Days      |
| 4     | Real-time Foundation & Activity Logging              | 3-4 Days      |
| 5     | Advanced Features & Real-time Implementation         | 7-10 Days     |
| 6     | Polish, Testing & Deployment Prep                    | 5-7 Days      |
| **Total** |                                                      | **~5-7 Weeks** |

## Progress Tracker

*(Update % and Status as work progresses)*

| Phase | Status                                   | Progress | Key Deliverables                                                              |
| :---- | :--------------------------------------- | :------- | :---------------------------------------------------------------------------- |
| 0     | <font color="gray">Not Started</font>    | 0%       | Project created, basic config, core packages installed.                       |
| 1     | <font color="gray">Not Started</font>    | 0%       | Migrations (User/Team), Models (User/Team), Traits (ULID/Slug/Tracking).      |
| 2     | <font color="gray">Not Started</font>    | 0%       | Auth (Fortify), 2FA, State Machine (User), Profile UI (3x), Avatar Upload.    |
| 3     | <font color="gray">Not Started</font>    | 0%       | Team Hierarchy, Services (Team), Permissions (Spatie), Team UI (3x).          |
| 4     | <font color="gray">Not Started</font>    | 0%       | Reverb/Echo setup, Presence Status (Enum/Spatie), Activity Log Listeners.     |
| 5     | <font color="gray">Not Started</font>    | 0%       | Impersonate, Comments, Settings, Search, Real-time Presence/Chat (Backend/UI). |
| 6     | <font color="gray">Not Started</font>    | 0%       | I18n, Pennant, Tests, Optimizations, Docs, Backup, Deployment Plan.           |

**Status Key:** <font color="gray">Not Started</font> | <font color="blue">In Progress</font> | <font color="orange">Blocked</font> | <font color="green">Completed</font>

---

## Phased Implementation Plan

---

### Phase 0: Project Setup & Foundation (Est. 1-2 Days)

**Goal:** Create the Laravel project, set up the database, install essential packages, and configure the basic environment.

**Steps:**

1.  **Create Laravel Project:**
    *   **Action:** Use the Laravel installer to create a new project.
        ~~~bash
        laravel new ume-app --git --pest
        cd ume-app
        ~~~
    *   **Concept:** `laravel new` bootstraps a new Laravel application with common directories and files. `--git` initializes a Git repository. `--pest` sets up PestPHP for testing (an alternative to PHPUnit, often preferred for its expressive syntax).
    *   **Verification:** A `ume-app` directory is created containing the Laravel project structure.

2.  **Configure Environment (`.env`):**
    *   **Action:** Copy `.env.example` to `.env` and configure database connection details (PostgreSQL preferred), application URL, session driver, queue connection, etc.
        ~~~bash
        cp .env.example .env
        nano .env # Or use your preferred editor
        ~~~
    *   **File (`.env` - Snippet):**
        ~~~dotenv
        APP_NAME="UME App"
        APP_ENV=local
        APP_KEY= # Will be generated later if empty
        APP_DEBUG=true
        APP_URL=http://ume-app.test # Use Laravel Herd, Valet, or configure hosts

        LOG_CHANNEL=stack
        LOG_LEVEL=debug

        # Database (PostgreSQL Example)
        DB_CONNECTION=pgsql
        DB_HOST=127.0.0.1
        DB_PORT=5432
        DB_DATABASE=ume_app_db
        DB_USERNAME=sail # or your local username
        DB_PASSWORD=password # or your local password

        BROADCAST_DRIVER=reverb # Set for later
        CACHE_DRIVER=redis     # Recommended for performance
        FILESYSTEM_DISK=public
        QUEUE_CONNECTION=redis   # Recommended for performance
        SESSION_DRIVER=redis   # Recommended for performance, ensure Redis is running
        SESSION_LIFETIME=120

        # Redis (Ensure Redis server is running)
        REDIS_HOST=127.0.0.1
        REDIS_PASSWORD=null
        REDIS_PORT=6379

        # Reverb (Set app ID, key, secret - generate random secure strings)
        REVERB_APP_ID=your_reverb_app_id
        REVERB_APP_KEY=your_reverb_app_key
        REVERB_SECRET=your_reverb_app_secret
        REVERB_HOST="localhost"
        REVERB_PORT=8080
        REVERB_SCHEME=http

        # Mail (Configure a driver like Mailtrap, Mailgun, SES for testing/production)
        MAIL_MAILER=log # Log emails initially
        MAIL_HOST=127.0.0.1
        MAIL_PORT=1025
        MAIL_USERNAME=null
        MAIL_PASSWORD=null
        MAIL_ENCRYPTION=null
        MAIL_FROM_ADDRESS="hello@example.com"
        MAIL_FROM_NAME="${APP_NAME}"

        # Typesense (Configure later in Phase 5 if used)
        TYPESENSE_HOST=localhost
        TYPESENSE_PORT=8108
        TYPESENSE_PROTOCOL=http
        TYPESENSE_API_KEY=your_typesense_api_key
        TYPESENSE_COLLECTION_PREFIX=ume_
        SCOUT_DRIVER=typesense
        ~~~
    *   **Concept:** `.env` files store environment-specific configuration (database credentials, API keys, etc.). Laravel loads these variables at runtime. Never commit `.env` to version control.
    *   **Action:** Generate application key.
        ~~~bash
        php artisan key:generate
        ~~~
    *   **Verification:** The `APP_KEY` in `.env` is populated. Database connection details are correct.

3.  **Install Core Backend Packages:**
    *   **Action:** Use Composer to require essential packages identified in the PRD.
        ~~~bash
        # Core Laravel & Utility
        composer require laravel/breeze --dev # For initial auth scaffolding (Blade/Livewire/React/Vue)
        composer require laravel/fortify # Backend auth logic (used by Breeze)
        composer require laravel/sanctum # SPA Auth
        composer require laravel/passport # OAuth2 API Auth
        composer require laravel/reverb # WebSocket Server
        composer require laravel/pennant # Feature Flags
        composer require laravel/horizon # Queue Dashboard
        composer require laravel/pulse # Performance Monitoring (requires DB migration)
        composer require laravel/telescope --dev # Debug Assistant
        composer require laravel/scout # Search Abstraction
        composer require typesense/typesense-php # Typesense driver for Scout

        # Spatie Packages
        composer require spatie/laravel-permission # Roles & Permissions
        composer require spatie/laravel-medialibrary # File/Avatar Management
        composer require spatie/laravel-activitylog # Audit Trails
        composer require spatie/laravel-model-states # State Machine
        composer require spatie/laravel-model-status # Simple Status Tracking
        composer require spatie/laravel-sluggable # Slugs
        composer require spatie/laravel-tags # Tagging
        composer require spatie/laravel-translatable # Model Translation
        composer require spatie/laravel-translation-loader # DB Translation Loading
        composer require spatie/laravel-settings # User/App Settings
        composer require spatie/laravel-comments # Commenting
        composer require spatie/laravel-backup # Backups

        # Other Utilities
        composer require lab404/laravel-impersonate # User Impersonation
        composer require laravel/socialite # Social Logins
        ~~~
    *   **Concept:** `composer require` downloads the specified package and its dependencies into the `vendor` directory and updates `composer.json` and `composer.lock`. `--dev` installs packages only needed for development (like Breeze scaffolding).
    *   **Verification:** Packages are listed in `composer.json` and the `vendor` directory is populated.

4.  **Install Frontend Scaffolding (Breeze):**
    *   **Action:** Install Laravel Breeze to get basic auth views/components. We'll run this *three times* to get scaffoldings for all UI variants, copying files out between runs.
        *   **Step 4a: Livewire/Volt Stack**
            ~~~bash
            # Install Livewire stack first
            php artisan breeze:install vue --ssr # Placeholder stack first (Vue with SSR) to get basic structure
            # Run install first to set up basic files Breeze needs

            # NOW install Volt/Livewire correctly over it
            composer require livewire/livewire livewire/volt
            php artisan volt:install
            php artisan breeze:install blade --volt # Installs Blade + Volt stack

            # **IMPORTANT**: Copy relevant files *now* before installing the next stack
            # e.g., copy resources/views/*, app/Livewire/*, routes/web.php, routes/auth.php
            # to temporary directories like `_scaffolding/livewire-volt`
            mkdir -p _scaffolding/livewire-volt/resources/views
            mkdir -p _scaffolding/livewire-volt/app/Livewire
            mkdir -p _scaffolding/livewire-volt/routes
            cp -R resources/views/* _scaffolding/livewire-volt/resources/views/
            cp -R app/Livewire/* _scaffolding/livewire-volt/app/Livewire/
            cp routes/web.php routes/auth.php _scaffolding/livewire-volt/routes/
            cp tailwind.config.js postcss.config.js vite.config.js package.json _scaffolding/livewire-volt/
            git add . && git commit -m "Scaffold Livewire/Volt stack" # Commit changes
            ~~~
        *   **Step 4b: Inertia/React Stack**
            ~~~bash
            # Clean up previous breeze install changes before installing next
            git stash # Stash any uncommitted changes if needed
            git checkout HEAD -- . # Reset working directory to last commit (removes previous stack)
            php artisan config:clear && php artisan view:clear && php artisan route:clear # Clear caches

            # Install React stack
            php artisan breeze:install react --ssr # Install React + SSR
            npm install && npm run build

            # **IMPORTANT**: Copy relevant files *now*
            # e.g., copy resources/js/*, routes/web.php, routes/auth.php
            # to temporary directories like `_scaffolding/react`
            mkdir -p _scaffolding/react/resources/js
            mkdir -p _scaffolding/react/routes
            cp -R resources/js/* _scaffolding/react/resources/js/
            cp routes/web.php routes/auth.php _scaffolding/react/routes/
            cp tailwind.config.js postcss.config.js vite.config.js package.json _scaffolding/react/
            git add . && git commit -m "Scaffold Inertia/React stack" # Commit changes
            ~~~
        *   **Step 4c: Inertia/Vue Stack**
            ~~~bash
            # Clean up previous breeze install changes
            git stash
            git checkout HEAD -- .
            php artisan config:clear && php artisan view:clear && php artisan route:clear

            # Install Vue stack
            php artisan breeze:install vue --ssr # Install Vue + SSR
            npm install && npm run build

            # **IMPORTANT**: Copy relevant files *now*
            # e.g., copy resources/js/*, routes/web.php, routes/auth.php
            # to temporary directories like `_scaffolding/vue`
            mkdir -p _scaffolding/vue/resources/js
            mkdir -p _scaffolding/vue/routes
            cp -R resources/js/* _scaffolding/vue/resources/js/
            cp routes/web.php routes/auth.php _scaffolding/vue/routes/
            cp tailwind.config.js postcss.config.js vite.config.js package.json _scaffolding/vue/
            git add . && git commit -m "Scaffold Inertia/Vue stack" # Commit changes
            ~~~
        *   **Step 4d: Restore Preferred Default (e.g., React) and Integrate**
            ~~~bash
            # Decide which stack will be the "default" served by standard routes. Let's assume React.
            # Checkout the React commit or copy files back from `_scaffolding/react`
            git checkout <commit_hash_for_react_scaffold> -- . # Or copy manually

            # Now, structure the project to hold all three variants.
            # We might need different route files or conditional loading later.
            # For now, keep the React setup as the active one.
            # We will manually create/adapt components for Livewire/Volt and Vue later based on the scaffoldings saved in `_scaffolding`.
            npm install && npm run build # Rebuild assets for the chosen default stack
            ~~~
    *   **Concept:** `laravel/breeze` provides a starting point for authentication UIs and routes, integrating with Fortify. We install it multiple times to capture the boilerplate for each desired frontend stack (Blade/Volt, React, Vue) and store them for reference. We then choose one as the primary stack for initial development. Handling multiple frontend stacks simultaneously in one project requires careful routing and asset management, which we will address later.
    *   **Rationale:** Getting the boilerplate helps accelerate UI development for common auth flows in all three frameworks. Saving the scaffolds allows us to reference them when building components later.
    *   **Verification:** Basic login/registration routes work (`/login`, `/register`). Frontend assets compile without errors. Files are saved in `_scaffolding`.

5.  **Publish Package Configurations & Run Initial Migrations:**
    *   **Action:** Publish configuration files for installed packages and run migrations provided by Laravel and the packages.
        ~~~bash
        # Publish config files (select relevant packages when prompted, or publish all)
        php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
        php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
        php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
        # php artisan vendor:publish --provider="Spatie\ModelStates\ModelStatesServiceProvider" # Usually no config needed
        # php artisan vendor:publish --provider="Spatie\ModelStatus\ModelStatusServiceProvider" # Configurable if needed
        php artisan vendor:publish --provider="Spatie\Sluggable\SluggableServiceProvider"
        php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider"
        # php artisan vendor:publish --provider="Spatie\LaravelTranslatable\TranslatableServiceProvider" # Configurable if needed
        php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationLoaderServiceProvider"
        php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider"
        php artisan vendor:publish --provider="Spatie\Comments\CommentsServiceProvider"
        php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
        php artisan vendor:publish --provider="Lab404\Impersonate\ImpersonateServiceProvider"
        php artisan vendor:publish --provider="Laravel\Socialite\SocialiteServiceProvider" # If config needed
        php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
        php artisan vendor:publish --provider="Laravel\Passport\PassportServiceProvider"
        php artisan vendor:publish --provider="Laravel\Pennant\PennantServiceProvider"
        php artisan vendor:publish --provider="Laravel\Pulse\PulseServiceProvider" # Pulse requires this
        php artisan vendor:publish --provider="Laravel\Telescope\TelescopeServiceProvider"
        php artisan vendor:publish --tag=telescope-migrations
        php artisan vendor:publish --tag=horizon-config

        # Configure Fortify features (optional, can do later)
        # nano config/fortify.php # Enable features like 2FA, profile updates etc.

        # Run initial migrations (Laravel defaults + packages like Passport, Pulse, Telescope, Spatie Permissions etc.)
        php artisan migrate
        ~~~
    *   **Concept:** `vendor:publish` copies default configuration/view/migration files from a package's `vendor` directory into your application's `config`, `resources`, or `database/migrations` directories, allowing customization. `php artisan migrate` executes all pending migration files to update the database schema.
    *   **Verification:** Config files appear in `config/`. Default Laravel tables (`users`, `password_reset_tokens`, etc.) and package tables (e.g., `oauth_clients`, `personal_access_tokens`, `pulse_*`, `telescope_*`, `permissions`, `roles`, `media`) are created in the database.

6.  **Configure Pulse:**
    *   **Action:** Ensure Pulse dashboard authorization is set up.
    *   **File (`app/Providers/PulseServiceProvider.php` - Snippet):**
        ~~~php
        <?php

        namespace App\Providers;

        use App\Models\User;
        use Illuminate\Support\Facades\Gate;
        use Laravel\Pulse\PulseApplicationServiceProvider;

        class PulseServiceProvider extends PulseApplicationServiceProvider
        {
            /**
             * Register the Pulse gate.
             *
             * This gate determines who can access Pulse in non-local environments.
             */
            protected function gate(): void
            {
                Gate::define('viewPulse', function (User $user) {
                    // Example: Allow access only to users with a specific email
                    // Replace with your actual authorization logic (e.g., check for an 'Admin' role)
                    return in_array($user->email, [
                        'admin@example.com', // Replace with your admin user email(s)
                    ]);
                });
            }
        }
        ~~~
    *   **Concept:** Service Providers are central places to bootstrap application services, register bindings, event listeners, middleware, and gates. The `PulseServiceProvider` configures access to the Pulse dashboard. `Gate::define` sets up authorization rules.
    *   **Rationale:** Restricts access to the potentially sensitive performance data shown in Pulse.
    *   **Verification:** Accessing `/pulse` in the browser (when logged in as an authorized user) should display the dashboard.

7.  **Initial Commit:**
    *   **Action:** Commit the foundational setup.
        ~~~bash
        git add .
        git commit -m "Phase 0: Initial project setup and package installation"
        ~~~
    *   **Verification:** Changes are committed to Git history.

---

### Phase 1: Core User Model & Architecture (Est. 3-5 Days)

**Goal:** Enhance the `User` model, create the `Team` model, implement mandatory ULIDs/Slugs, set up user tracking, and establish foundational Traits and Service structures.

**Steps:**

1.  **Create `HasUlid` Trait:**
    *   **Action:** Create a reusable trait to automatically generate ULIDs for models.
    *   **File (`app/Models/Traits/HasUlid.php`):**
        ~~~php
        <?php

        namespace App\Models\Traits;

        use Illuminate\Database\Eloquent\Model;
        use Illuminate\Support\Str;

        trait HasUlid
        {
            /**
             * Boot the trait.
             *
             * Automatically set the ULID field when creating a new model.
             * Overrides the default route key name.
             */
            protected static function bootHasUlid(): void
            {
                static::creating(function (Model $model) {
                    if (empty($model->{static::ulidFieldName()})) {
                        $model->{static::ulidFieldName()} = static::generateUlid();
                    }
                });
            }

            /**
             * Generate a new ULID.
             *
             * @return string
             */
            public static function generateUlid(): string
            {
                return (string) Str::ulid();
            }

            /**
             * Get the name of the ULID field. Defaults to 'ulid'.
             * Override in the model if using a different field name.
             *
             * @return string
             */
            public static function ulidFieldName(): string
            {
                return defined(static::class.'::ULID_FIELD_NAME') ? static::ULID_FIELD_NAME : 'ulid';
            }

            /**
             * Initialize the trait.
             * Ensure the ULID field is not mass assignable if it's the primary key.
             */
             public function initializeHasUlid(): void
             {
                 // If ulid is primary key, prevent mass assignment
                 if ($this->primaryKey === static::ulidFieldName()) {
                      $this->guard([static::ulidFieldName()]);
                 }
                 // Use ULID for route model binding by default
                 $this->setRouteKeyName(static::ulidFieldName());
             }

            /**
             * Get the route key for the model.
             * Ensures ULID is used for route model binding.
             *
             * @return string
             */
            public function getRouteKeyName(): string
            {
                 // If overridden in model, use that, otherwise default to ULID field.
                return $this->getKeyName() === $this->primaryKey
                    ? (static::ulidFieldName() ?: 'ulid')
                    : $this->getKeyName();
            }

             /**
              * Retrieve the model for a bound value.
              * Override default implicit binding to use the ULID field.
              *
              * @param  mixed  $value
              * @param  string|null  $field
              * @return \Illuminate\Database\Eloquent\Model|null
              */
             public function resolveRouteBinding($value, $field = null)
             {
                 $field = $field ?? $this->getRouteKeyName(); // Use ULID field by default
                 return $this->where($field, $value)->first();
             }
        }
        ~~~
    *   **Concept:** Traits allow reusing methods across different classes. This trait uses the `creating` Eloquent model event (a lifecycle hook) to set the `ulid` field automatically when a new model instance is being saved. It also configures route model binding to use the `ulid`.
    *   **Rationale:** Enforces mandatory ULIDs consistently across models without repeating code. Provides a central place for ULID logic.

2.  **Create `HasUserTracking` Trait:**
    *   **Action:** Create a trait to automatically populate `created_by_id` and `updated_by_id`.
    *   **File (`app/Models/Traits/HasUserTracking.php`):**
        ~~~php
        <?php

        namespace App\Models\Traits;

        use App\Models\User;
        use Illuminate\Database\Eloquent\Model;
        use Illuminate\Database\Eloquent\Relations\BelongsTo;
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\Schema;

        trait HasUserTracking
        {
            /**
             * Boot the trait.
             *
             * Set creator/updater on model events.
             */
            protected static function bootHasUserTracking(): void
            {
                static::creating(function (Model $model) {
                    if (Auth::check()) {
                        if (Schema::hasColumn($model->getTable(), 'created_by_id') && is_null($model->created_by_id)) {
                            $model->created_by_id = Auth::id();
                        }
                        if (Schema::hasColumn($model->getTable(), 'updated_by_id') && is_null($model->updated_by_id)) {
                            $model->updated_by_id = Auth::id();
                        }
                    }
                });

                static::updating(function (Model $model) {
                    if (Auth::check()) {
                        if (Schema::hasColumn($model->getTable(), 'updated_by_id')) {
                             // Prevent accidental overwrite if already set by another process
                             if ($model->isDirty('updated_by_id')) {
                                 return;
                             }
                            $model->updated_by_id = Auth::id();
                        }
                    }
                });
            }

            /**
             * Get the user who created the record.
             */
            public function creator(): BelongsTo
            {
                return $this->belongsTo(User::class, 'created_by_id');
            }

            /**
             * Get the user who last updated the record.
             */
            public function updater(): BelongsTo
            {
                return $this->belongsTo(User::class, 'updated_by_id');
            }
        }
        ~~~
    *   **Concept:** Uses `creating` and `updating` model events. `Auth::check()` verifies if a user is logged in, and `Auth::id()` retrieves their ID. `Schema::hasColumn` prevents errors if the columns don't exist on a particular model using the trait. `BelongsTo` defines an inverse one-to-many relationship in Eloquent.
    *   **Rationale:** Provides basic audit tracking automatically for models that use this trait.

3.  **Enhance User Migration:**
    *   **Action:** Modify the existing `users` table migration to add ULID, slug, name components, Fortify columns, state field, and user tracking fields.
    *   **File (`database/migrations/YYYY_MM_DD_HHMMSS_create_users_table.php` - Modify existing):**
        ~~~php
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;
        use Illuminate\Support\Facades\DB; // Needed for data population helper
        use Illuminate\Support\Str; // Needed for ULID generation

        return new class extends Migration
        {
            /**
             * Run the migrations.
             */
            public function up(): void
            {
                Schema::create('users', function (Blueprint $table) {
                    $table->id(); // Keep standard auto-incrementing ID as primary key internally
                    $table->ulid('ulid')->unique()->after('id'); // Public identifier, indexed automatically by unique()

                    // User Name Components
                    $table->string('given_name')->nullable()->after('ulid'); // First name
                    $table->string('family_name')->nullable()->after('given_name'); // Last name
                    $table->string('other_names')->nullable()->after('family_name'); // Middle names, etc.

                    // Keep original name for potential data migration ease, make nullable
                    $table->string('name')->nullable()->after('other_names');

                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');

                    // Account State (using string for flexibility with Spatie\ModelStates)
                    // Nullable initially to allow adding to existing table without default
                    $table->string('account_state')->nullable()->index()->after('password');

                    // Presence Status (Simple Enum Example - can be added later if preferred)
                    // $table->string('presence_status')->nullable()->index()->after('account_state');
                    // $table->timestamp('last_seen_at')->nullable()->after('presence_status');

                    // Spatie Sluggable field
                    $table->string('slug')->unique()->nullable()->after('email'); // Nullable initially

                    // Fortify 2FA Columns
                    $table->text('two_factor_secret')->nullable()->after('password');
                    $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
                    $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');

                    // User Tracking (Nullable foreign keys, constraint added later if desired)
                    $table->foreignId('created_by_id')->nullable()->index()->constrained('users')->nullOnDelete();
                    $table->foreignId('updated_by_id')->nullable()->index()->constrained('users')->nullOnDelete();

                    // Team Context (Nullable foreign key, constraint added after teams table exists)
                    $table->foreignId('current_team_id')->nullable()->index(); // Add constraint later

                    $table->rememberToken();
                    $table->timestamps(); // Adds created_at and updated_at
                    $table->softDeletes(); // Adds deleted_at for soft deletes
                });

                // Populate ULID/Slug/Name for existing users (if any, unlikely on fresh install)
                // This part is more relevant if applying to an existing project.
                // For a new project, this might not be needed, but good practice.
                $this->populateExistingUsers();

                // Now make ULID, Slug, and Account State non-nullable AFTER potential population
                Schema::table('users', function (Blueprint $table) {
                    // If running on existing data, ensure a default state is set first
                    // DB::table('users')->whereNull('account_state')->update(['account_state' => 'pending_validation']); // Set a default string key

                    $table->string('account_state')->nullable(false)->change();
                    $table->string('slug')->nullable(false)->change();
                    // ULID is already set via default or population, should be safe to change
                    // Note: Changing column type might require DBAL package: composer require doctrine/dbal
                    // $table->ulid('ulid')->nullable(false)->change(); // Already unique, nullable change might not be needed if populated correctly
                });
            }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                 Schema::table('users', function (Blueprint $table) {
                     // Drop foreign keys before columns if they exist
                     $table->dropForeign(['created_by_id']);
                     $table->dropForeign(['updated_by_id']);
                     $table->dropForeign(['current_team_id']); // Assuming constraint was added
                 });
                Schema::dropIfExists('users');
            }

             /**
              * Helper to populate essential fields for existing users during migration.
              * Adapt logic based on how existing 'name' should be split.
              */
             protected function populateExistingUsers(): void
             {
                 if (!Schema::hasColumn('users', 'ulid')) return; // Avoid errors if run multiple times or on fresh DB

                 DB::table('users')->whereNull('ulid')->orderBy('id')->chunk(100, function ($users) {
                     foreach ($users as $user) {
                         $ulid = (string) Str::ulid();
                         $slug = Str::slug($user->name ?? ('user ' . $ulid)); // Basic slug from name or fallback

                         // Basic name splitting heuristic (customize as needed)
                         $nameParts = explode(' ', $user->name ?? '', 2);
                         $givenName = $nameParts[0] ?? null;
                         $familyName = $nameParts[1] ?? null;

                         // Ensure slug uniqueness
                         $originalSlug = $slug;
                         $counter = 1;
                         while (DB::table('users')->where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
                             $slug = $originalSlug . '-' . $counter++;
                         }

                         DB::table('users')
                             ->where('id', $user->id)
                             ->update([
                                 'ulid' => $ulid,
                                 'slug' => $slug,
                                 'given_name' => $givenName,
                                 'family_name' => $familyName,
                                 // Set a default state if migrating existing data
                                 'account_state' => DB::raw("COALESCE(account_state, 'pending_validation')"), // Set default if null
                             ]);
                     }
                 });
             }
        };
        ~~~
    *   **Concept:** Migrations modify the database schema. `up()` defines changes to apply, `down()` defines how to reverse them. `Blueprint` provides methods to define table columns and indexes. `Schema::table` modifies an existing table. `change()` modifies column properties.
    *   **Rationale:** Adds all required fields for the enhanced User model as per the PRD. ULID/Slug are mandatory. Name components provide structure. State field is crucial for the state machine. User tracking adds auditability. `softDeletes` allows users to be "deleted" without removing data permanently. Making fields nullable initially and then non-nullable after populating data avoids errors during migration on tables with existing data.
    *   **Verification:** Run `php artisan migrate:fresh` (drops all tables and re-migrates - **USE WITH CAUTION ON EXISTING DATA**) or `php artisan migrate:refresh` (rolls back and re-migrates). Check the `users` table structure in your database client.

4.  **Create `Team` Model and Migration:**
    *   **Action:** Generate the `Team` model and its migration file. Define the schema including hierarchy (`parent_id`).
        ~~~bash
        php artisan make:model Team -mfs # -m creates migration, -f factory, -s seeder
        ~~~
    *   **File (`database/migrations/YYYY_MM_DD_HHMMSS_create_teams_table.php`):**
        ~~~php
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            /**
             * Run the migrations.
             */
            public function up(): void
            {
                Schema::create('teams', function (Blueprint $table) {
                    $table->id();
                    $table->ulid('ulid')->unique(); // Mandatory Public ID
                    $table->string('slug')->unique(); // Mandatory Slug

                    $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete(); // Team owner
                    $table->foreignId('parent_id')->nullable()->constrained('teams')->nullOnDelete(); // For hierarchy

                    $table->string('name');
                    $table->text('description')->nullable();
                    // Add other team-specific fields as needed

                    // User Tracking
                    $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
                    $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

                    $table->timestamps();
                    $table->softDeletes();
                });

                 // Now add the foreign key constraint to the users table
                 Schema::table('users', function (Blueprint $table) {
                    if (Schema::hasColumn('users', 'current_team_id')) {
                         $table->foreign('current_team_id')
                               ->references('id')
                               ->on('teams')
                               ->nullOnDelete();
                    }
                 });
            }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                 Schema::table('users', function (Blueprint $table) {
                     if (Schema::hasColumn('users', 'current_team_id')) {
                         // Ensure foreign key exists before trying to drop
                         $foreignKeys = collect(DB::select("SELECT conname FROM pg_constraint WHERE conrelid = 'users'::regclass AND confrelid = 'teams'::regclass;"))->pluck('conname')->toArray();
                         if (in_array('users_current_team_id_foreign', $foreignKeys)) { // Adjust constraint name if needed
                            $table->dropForeign(['current_team_id']);
                         }
                     }
                 });
                Schema::dropIfExists('teams');
            }
        };
        ~~~
    *   **File (`app/Models/Team.php`):**
        ~~~php
        <?php

        namespace App\Models;

        use App\Models\Traits\HasUlid;
        use App\Models\Traits\HasUserTracking;
        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;
        use Illuminate\Database\Eloquent\Relations\BelongsTo;
        use Illuminate\Database\Eloquent\Relations\BelongsToMany;
        use Illuminate\Database\Eloquent\Relations\HasMany;
        use Illuminate\Database\Eloquent\SoftDeletes;
        use Spatie\Activitylog\LogOptions;
        use Spatie\Activitylog\Traits\LogsActivity;
        use Spatie\Sluggable\HasSlug;
        use Spatie\Sluggable\SlugOptions;

        class Team extends Model
        {
            use HasFactory, SoftDeletes, HasUlid, HasSlug, HasUserTracking, LogsActivity;

            protected $fillable = [
                'name',
                'description',
                'owner_id',
                'parent_id',
                // Add other fillable fields
            ];

            protected $casts = [
                // Cast attributes if needed, e.g., 'settings' => 'array'
            ];

            // --- Relationships ---

            public function owner(): BelongsTo
            {
                return $this->belongsTo(User::class, 'owner_id');
            }

            public function parent(): BelongsTo
            {
                return $this->belongsTo(Team::class, 'parent_id');
            }

            public function children(): HasMany
            {
                return $this->hasMany(Team::class, 'parent_id');
            }

            // Recursive relationship to get all descendants
            public function allChildren(): HasMany
            {
                 return $this->children()->with('allChildren');
            }

            public function users(): BelongsToMany
            {
                // We will define the pivot table migration in the next step
                return $this->belongsToMany(User::class, 'team_user')
                            ->withTimestamps()
                            ->withPivot('role'); // Example pivot data (role within team)
            }

            // Spatie Permissions integration (if using team-specific permissions)
            // This requires configuration in config/permission.php
            // public function roles(): MorphToMany { ... } // See Spatie docs

            // --- Spatie Sluggable ---

            public function getSlugOptions(): SlugOptions
            {
                return SlugOptions::create()
                    ->generateSlugsFrom('name')
                    ->saveSlugsTo('slug')
                    ->doNotGenerateSlugsOnUpdate() // Or true if you want slugs to update
                    ->preventOverwrite();
            }

             // --- Spatie Activity Log ---

             public function getActivitylogOptions(): LogOptions
             {
                 return LogOptions::defaults()
                     ->logOnly(['name', 'description', 'owner_id', 'parent_id']) // Log changes to these fields
                     ->logOnlyDirty() // Log only when attributes change
                     ->dontSubmitEmptyLogs() // Don't log if nothing changed
                     ->useLogName('team'); // Custom log name
             }

             // --- Helpers ---

             /**
              * Check if the team is a top-level team (has no parent).
              */
             public function isTopLevel(): bool
             {
                 return is_null($this->parent_id);
             }
        }
        ~~~
    *   **Concept:** `make:model Team -mfs` generates the Model, Migration, Factory, and Seeder classes. The migration defines the `teams` table schema. The Model defines relationships (`owner`, `parent`, `children`, `users`), uses Traits (`HasUlid`, `HasSlug`, `HasUserTracking`, `LogsActivity`, `SoftDeletes`), and configures `spatie/laravel-sluggable` and `spatie/laravel-activitylog`.
    *   **Rationale:** Establishes the core data structure for teams, including hierarchy and ownership, following PRD requirements and architectural patterns.
    *   **Verification:** Run `php artisan migrate`. Check the `teams` table structure.

5.  **Create `team_user` Pivot Table Migration:**
    *   **Action:** Create a migration for the pivot table connecting users and teams.
        ~~~bash
        php artisan make:migration create_team_user_table
        ~~~
    *   **File (`database/migrations/YYYY_MM_DD_HHMMSS_create_team_user_table.php`):**
        ~~~php
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            /**
             * Run the migrations.
             */
            public function up(): void
            {
                Schema::create('team_user', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                    $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
                    $table->string('role')->nullable(); // Example: Store user's role within this specific team
                    $table->timestamps();

                    // Ensure a user can only be in a team once
                    $table->unique(['user_id', 'team_id']);
                });
            }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                Schema::dropIfExists('team_user');
            }
        };
        ~~~
    *   **Concept:** A pivot table is used to manage many-to-many relationships (a user can belong to multiple teams, a team can have multiple users). `cascadeOnDelete()` ensures that if a user or team is deleted, the corresponding membership records are also removed.
    *   **Rationale:** Defines the link between users and teams as required by the PRD.
    *   **Verification:** Run `php artisan migrate`. Check the `team_user` table structure.

6.  **Update `User` Model:**
    *   **Action:** Apply traits, define accessors/mutators for name components, add relationships, configure packages.
    *   **File (`app/Models/User.php` - Update existing):**
        ~~~php
        <?php

        namespace App\Models;

        use App\Models\Traits\HasUlid;
        // Remove HasApiTokens if using Passport/Sanctum exclusively via their traits
        // use Illuminate\Foundation\Auth\User as Authenticatable; // Keep this line
        use Illuminate\Notifications\Notifiable;
        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\SoftDeletes;
        use Illuminate\Database\Eloquent\Casts\Attribute;
        use Illuminate\Database\Eloquent\Relations\BelongsTo;
        use Illuminate\Database\Eloquent\Relations\BelongsToMany;
        use Illuminate\Database\Eloquent\Relations\HasMany;
        use Lab404\Impersonate\Models\Impersonate; // Impersonation trait
        use Laravel\Fortify\TwoFactorAuthenticatable; // Fortify 2FA trait
        use Laravel\Passport\HasApiTokens; // Passport trait for API tokens
        use Laravel\Sanctum\HasApiTokens as HasSanctumTokens; // Sanctum trait (alias needed)
        use Spatie\Permission\Traits\HasRoles; // Spatie Permissions trait
        use Spatie\MediaLibrary\HasMedia; // Spatie Media Library trait
        use Spatie\MediaLibrary\InteractsWithMedia; // Spatie Media Library trait
        use Spatie\Sluggable\HasSlug; // Spatie Sluggable trait
        use Spatie\Sluggable\SlugOptions; // Spatie Sluggable trait
        use Spatie\Activitylog\Traits\LogsActivity; // Spatie Activity Log trait
        use Spatie\Activitylog\LogOptions; // Spatie Activity Log trait
        use Spatie\ModelStates\HasStates; // Spatie State Machine trait
        use App\States\User\AccountState; // Base State Class
        use App\States\User\Active; // Example Concrete State
        use App\States\User\PendingValidation; // Example Concrete State
        use Spatie\Comments\Models\Concerns\InteractsWithComments; // Comments Trait
        use Spatie\Comments\Models\Concerns\CanComment; // Comments Trait
        use Spatie\Settings\Traits\HasSettings; // Settings Trait
        use App\Settings\UserSettings; // Settings Class
        use Illuminate\Contracts\Auth\MustVerifyEmail; // Email Verification Interface
        use Illuminate\Foundation\Auth\User as Authenticatable; // Base User class

        // Implement MustVerifyEmail for email verification flow
        class User extends Authenticatable implements MustVerifyEmail, HasMedia
        {
            // Order traits for potential dependencies or clarity
            use HasFactory,
                Notifiable,
                SoftDeletes,
                HasUlid, // Add ULID trait
                HasSlug, // Add Slug trait
                HasRoles, // Spatie Permissions
                InteractsWithMedia, // Spatie Media Library (AFTER HasRoles potentially)
                LogsActivity, // Spatie Activity Log
                HasStates, // Spatie State Machine
                HasSanctumTokens, // Use Sanctum tokens (e.g., for SPA)
                HasApiTokens, // Use Passport tokens (e.g., for external APIs)
                TwoFactorAuthenticatable, // Fortify 2FA
                Impersonate, // Lab404 Impersonation
                InteractsWithComments, // Can receive comments
                CanComment, // Can post comments
                HasSettings; // Spatie Settings

            /**
             * The attributes that are mass assignable.
             *
             * @var array<int, string>
             */
            protected $fillable = [
                'given_name',
                'family_name',
                'other_names',
                // 'name', // No longer primary way to set name, manage via components
                'email',
                'password',
                'current_team_id', // Allow setting current team
                'account_state', // Allow setting initial state if needed directly (usually done via service)
            ];

            /**
             * The attributes that should be hidden for serialization.
             *
             * @var array<int, string>
             */
            protected $hidden = [
                'password',
                'remember_token',
                'two_factor_recovery_codes',
                'two_factor_secret',
            ];

            /**
             * The attributes that should be cast.
             *
             * @var array<string, string>
             */
            protected $casts = [
                'email_verified_at' => 'datetime',
                'password' => 'hashed',
                'two_factor_confirmed_at' => 'datetime',
                // --- State Machine Cast ---
                'account_state' => AccountState::class, // Cast state field
                // --- Settings Cast ---
                'settings' => Settings::class . ':' . UserSettings::class,
                // --- Simple Status Enum Cast (Example if added later) ---
                // 'presence_status' => \App\Enums\PresenceStatus::class,
                // 'last_seen_at' => 'datetime',
            ];

             /**
              * The attributes that should be appended to the model's array form.
              * Useful for including accessors in JSON responses automatically.
              *
              * @var array
              */
             protected $appends = [
                 'full_name', // Append the full name accessor
                 'initials', // Append initials
                 'avatar_url', // Append avatar URL
             ];

            // --- User Tracking Relationships ---
            // (Handled by HasUserTracking trait if used directly on User,
            // otherwise define here if needed)
            // public function creator(): BelongsTo { ... }
            // public function updater(): BelongsTo { ... }

            // --- Accessors & Mutators for Name ---

            /**
             * Get the user's full name.
             * Prioritizes components, falls back to 'name' field for compatibility.
             */
            protected function fullName(): Attribute
            {
                return Attribute::make(
                    get: function ($value, $attributes) {
                        $parts = array_filter([
                            $attributes['given_name'] ?? null,
                            $attributes['other_names'] ?? null,
                            $attributes['family_name'] ?? null,
                        ]);
                        if (!empty($parts)) {
                            return implode(' ', $parts);
                        }
                        // Fallback to the original 'name' field if components are empty
                        return $attributes['name'] ?? null;
                    }
                    // We don't define a 'set' mutator here, encourage setting components directly
                );
            }

            /**
             * Get the user's initials.
             */
             protected function initials(): Attribute
             {
                 return Attribute::make(
                     get: function ($value, $attributes) {
                         $initials = '';
                         if (!empty($attributes['given_name'])) {
                             $initials .= strtoupper(substr($attributes['given_name'], 0, 1));
                         }
                         if (!empty($attributes['family_name'])) {
                             $initials .= strtoupper(substr($attributes['family_name'], 0, 1));
                         }

                         // Fallback if components are missing but 'name' exists
                         if (empty($initials) && !empty($attributes['name'])) {
                             $words = explode(' ', $attributes['name']);
                             $initials .= strtoupper(substr($words[0], 0, 1));
                             if (count($words) > 1) {
                                 $initials .= strtoupper(substr(end($words), 0, 1));
                             }
                         }
                         return $initials ?: '??'; // Fallback for completely empty names
                     }
                 );
             }

             // --- Accessor for Avatar URL (Spatie Media Library) ---
             protected function avatarUrl(): Attribute
             {
                 return Attribute::make(
                     get: fn () => $this->getFirstMediaUrl('avatar', 'thumb') ?: $this->defaultAvatarUrl(),
                 );
             }

             public function defaultAvatarUrl(): string
             {
                  // Simple fallback using ui-avatars.com
                  $name = urlencode($this->full_name ?: $this->email); // Use full_name accessor
                  return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
             }

            // --- Relationships ---

            /**
             * Teams this user is a member of.
             */
            public function teams(): BelongsToMany
            {
                return $this->belongsToMany(Team::class, 'team_user')
                            ->withPivot('role') // Include pivot data if needed
                            ->withTimestamps();
            }

            /**
             * Teams owned by this user.
             */
            public function ownedTeams(): HasMany
            {
                return $this->hasMany(Team::class, 'owner_id');
            }

            /**
             * The user's currently selected team.
             */
            public function currentTeam(): BelongsTo
            {
                return $this->belongsTo(Team::class, 'current_team_id');
            }

            /**
             * Chat messages sent by this user.
             */
            public function chatMessages(): HasMany
            {
                return $this->hasMany(ChatMessage::class); // Assumes ChatMessage model exists
            }

            // --- Package Configurations ---

            /**
             * Spatie Sluggable configuration.
             */
            public function getSlugOptions(): SlugOptions
            {
                return SlugOptions::create()
                    ->generateSlugsFrom(['given_name', 'family_name']) // Generate from name components
                    ->saveSlugsTo('slug')
                    ->doNotGenerateSlugsOnUpdate()
                    ->preventOverwrite();
            }

            /**
             * Spatie Media Library configuration.
             */
             public function registerMediaCollections(): void
             {
                 $this->addMediaCollection('avatar')
                      ->singleFile() // Only allow one file in this collection
                      ->useFallbackUrl($this->defaultAvatarUrl())
                      ->useFallbackPath(public_path('/images/default-avatar.png')); // Optional: local fallback image

                 // Define conversions for the avatar
                 $this->addMediaConversion('thumb')
                       ->width(100)
                       ->height(100)
                       ->sharpen(10)
                       ->nonQueued(); // Generate immediately for profile display
             }

            /**
             * Spatie Activity Log configuration.
             */
            public function getActivitylogOptions(): LogOptions
            {
                return LogOptions::defaults()
                    ->logOnly([ // Log changes to these fields
                        'given_name',
                        'family_name',
                        'other_names',
                        'email',
                        'account_state',
                        // 'presence_status', // If added
                        'current_team_id',
                    ])
                    ->logOnlyDirty()
                    ->dontSubmitEmptyLogs()
                    ->useLogName('user');
            }

             /**
              * Spatie Settings configuration.
              * Specify the settings class associated with this model.
              *
              * @var string
              */
             public string $settingsClass = UserSettings::class;


            // --- Team Helpers ---

            /**
             * Check if the user belongs to the given team.
             */
            public function belongsToTeam(Team $team): bool
            {
                return $this->teams()->where('team_id', $team->id)->exists() || $this->ownsTeam($team);
            }

            /**
             * Check if the user owns the given team.
             */
            public function ownsTeam(Team $team): bool
            {
                return $this->id === $team->owner_id;
            }

            /**
             * Switch the user's current team context.
             * IMPORTANT: This should likely trigger events or other logic,
             * consider moving core logic to a UserService or TeamService.
             */
            public function switchTeam(Team $team): bool
            {
                if (! $this->belongsToTeam($team)) {
                    return false;
                }

                $this->forceFill([
                    'current_team_id' => $team->id,
                ])->save();

                $this->setRelation('currentTeam', $team);

                // Dispatch an event (handled by a listener maybe)
                // event(new CurrentTeamSwitched($this, $team));

                return true;
            }

            // --- Impersonation Helpers (Lab404) ---

            /**
             * Define who this user can impersonate.
             */
            public function canImpersonate(): bool
            {
                // Example: Only admins can impersonate
                // return $this->hasRole('Admin'); // Requires Spatie Roles setup
                 return $this->email === 'admin@example.com'; // Simple example
            }

            /**
             * Define who can impersonate this user.
             */
            public function canBeImpersonated(): bool
            {
                // Example: Admins cannot be impersonated
                // return !$this->hasRole('Admin');
                 return $this->email !== 'admin@example.com'; // Simple example
            }

             // Add a default state configuration if needed, e.g., for new users
             protected static function booted(): void
             {
                 static::creating(function ($user) {
                     if (empty($user->account_state)) {
                         // Set default state if not already set (e.g., by a service)
                         $user->account_state = PendingValidation::class;
                     }
                 });
             }
        }
        ~~~
    *   **Concept:** This updates the `User` model to:
        *   Use all the necessary traits for added functionality.
        *   Define `$fillable`, `$hidden`, `$casts` (including state machine), and `$appends`.
        *   Implement `Attribute` based accessors for `fullName`, `initials`, `avatarUrl`. Accessors provide computed properties.
        *   Define Eloquent relationships (`teams`, `ownedTeams`, `currentTeam`, `chatMessages`).
        *   Configure Spatie packages (`Sluggable`, `MediaLibrary`, `ActivityLog`, `Settings`).
        *   Add helper methods (`belongsToTeam`, `ownsTeam`, `switchTeam`).
        *   Add Impersonation permission methods (`canImpersonate`, `canBeImpersonated`).
        *   Implement `MustVerifyEmail` interface (required by Fortify's email verification).
        *   Set a default `account_state` during the `creating` event if none is provided.
    *   **Rationale:** Centralizes user-related data structure, relationships, and package configurations as defined in the PRD. Uses modern PHP/Laravel features like Attributes for accessors. Ensures mandatory fields and traits are applied.
    *   **Verification:** Code compiles. Relationships can be queried later. Accessors work when serializing the model.

7.  **Update `UserFactory`:**
    *   **Action:** Modify the `UserFactory` to reflect the new structure (name components, default state).
    *   **File (`database/factories/UserFactory.php` - Update existing):**
        ~~~php
        <?php

        namespace Database\Factories;

        use Illuminate\Database\Eloquent\Factories\Factory;
        use Illuminate\Support\Facades\Hash;
        use Illuminate\Support\Str;
        use App\States\User\Active; // Import default state
        use App\States\User\PendingValidation; // Import pending state

        /**
         * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
         */
        class UserFactory extends Factory
        {
            /**
             * The current password being used by the factory.
             */
            protected static ?string $password;

            /**
             * Define the model's default state.
             *
             * @return array<string, mixed>
             */
            public function definition(): array
            {
                $firstName = fake()->firstName();
                $lastName = fake()->lastName();

                return [
                    // ULID and Slug will be set by Traits/Model Events
                    'given_name' => $firstName,
                    'family_name' => $lastName,
                    'other_names' => null, // Or add fake middle names if desired
                    // 'name' => $firstName . ' ' . $lastName, // Can remove or keep for fallback testing
                    'email' => fake()->unique()->safeEmail(),
                    'email_verified_at' => now(),
                    'password' => static::$password ??= Hash::make('password'),
                    'remember_token' => Str::random(10),
                    'account_state' => Active::class, // Default to Active for factory creation
                    'created_by_id' => null, // Can be set manually in tests/seeders if needed
                    'updated_by_id' => null,
                    'current_team_id' => null, // Set in specific scenarios/tests
                    'two_factor_secret' => null,
                    'two_factor_recovery_codes' => null,
                    'two_factor_confirmed_at' => null,
                ];
            }

            /**
             * Indicate that the model's email address should be unverified.
             */
            public function unverified(): static
            {
                return $this->state(fn (array $attributes) => [
                    'email_verified_at' => null,
                    'account_state' => PendingValidation::class, // Set appropriate state for unverified
                ]);
            }

            /**
             * Indicate that the user should have 2FA enabled but not confirmed.
             */
            public function withTwoFactor(): static
            {
                 // Requires vendor/bin/phpunit bootstrap/app.php
                 // Need to resolve Fortify service, usually done in tests.
                 // For basic factory state, just setting dummy values might suffice,
                 // but proper testing requires service interaction.
                 // Example dummy values:
                return $this->state(function (array $attributes) {
                    return [
                        'two_factor_secret' => encrypt('SECRET'),
                        'two_factor_recovery_codes' => encrypt(json_encode(['code1','code2'])),
                        'two_factor_confirmed_at' => null,
                    ];
                });
            }

             /**
              * Indicate that the user should have 2FA confirmed.
              */
             public function twoFactorConfirmed(): static
             {
                 return $this->withTwoFactor()->state(fn (array $attributes) => [
                     'two_factor_confirmed_at' => now(),
                 ]);
             }
        }
        ~~~
    *   **Concept:** Factories define blueprints for creating model instances, primarily for testing and seeding. `fake()` provides various types of fake data. `state()` methods allow defining variations of the default state.
    *   **Rationale:** Ensures test data aligns with the new User model structure and default state requirements. Adds states for unverified and 2FA scenarios.
    *   **Verification:** Running `User::factory()->create()` in `php artisan tinker` or tests generates users with the correct structure.

8.  **Create Seeders (`UserSeeder`, `TeamSeeder`):**
    *   **Action:** Create seeders to populate the database with initial/test data. Update `DatabaseSeeder`.
    *   **File (`database/seeders/UserSeeder.php` - Create or Modify existing):**
        ~~~php
        <?php

        namespace Database\Seeders;

        use App\Models\User;
        use App\States\User\Active;
        use Illuminate\Database\Seeder;
        use Illuminate\Support\Facades\Hash;

        class UserSeeder extends Seeder
        {
            /**
             * Run the database seeds.
             */
            public function run(): void
            {
                // Create a default Admin user
                User::factory()->create([
                    'given_name' => 'Admin',
                    'family_name' => 'User',
                    'email' => 'admin@example.com',
                    'password' => Hash::make('password'), // Use a secure password in real scenarios
                    'email_verified_at' => now(),
                    'account_state' => Active::class, // Ensure admin is active
                ]);

                // Create some regular users
                User::factory(10)->create();

                // Create an unverified user
                User::factory()->unverified()->create([
                     'given_name' => 'Test',
                     'family_name' => 'Unverified',
                     'email' => 'unverified@example.com',
                ]);
            }
        }
        ~~~
    *   **File (`database/seeders/TeamSeeder.php` - Create new):**
        ~~~php
        <?php

        namespace Database\Seeders;

        use App\Models\Team;
        use App\Models\User;
        use Illuminate\Database\Seeder;

        class TeamSeeder extends Seeder
        {
            /**
             * Run the database seeds.
             */
            public function run(): void
            {
                $adminUser = User::where('email', 'admin@example.com')->first();
                $otherUsers = User::where('email', '!=', 'admin@example.com')->take(5)->get();

                if (!$adminUser) {
                    $this->command->warn('Admin user not found. Skipping team creation.');
                    return;
                }

                // Create a top-level team owned by Admin
                $topTeam = Team::factory()->create([
                    'name' => 'Acme Corporation',
                    'owner_id' => $adminUser->id,
                    'created_by_id' => $adminUser->id, // Set tracker
                    'updated_by_id' => $adminUser->id, // Set tracker
                ]);

                // Add Admin and other users to the team
                $topTeam->users()->attach($adminUser->id, ['role' => 'Owner']); // Example role pivot data
                foreach ($otherUsers as $user) {
                     $topTeam->users()->attach($user->id, ['role' => 'Member']);
                     // Set user's current team
                     $user->current_team_id = $topTeam->id;
                     $user->saveQuietly(); // Avoid triggering update events if not needed here
                }

                // Create a sub-team
                $subTeam = Team::factory()->create([
                    'name' => 'Marketing Department',
                    'owner_id' => $adminUser->id, // Or another user
                    'parent_id' => $topTeam->id, // Link to parent
                    'created_by_id' => $adminUser->id,
                    'updated_by_id' => $adminUser->id,
                ]);

                 // Add some users to the sub-team
                 $subTeamUsers = $otherUsers->take(2);
                 foreach ($subTeamUsers as $user) {
                      $subTeam->users()->attach($user->id, ['role' => 'Contributor']);
                 }

                 $this->command->info('Teams seeded successfully.');
            }
        }
        ~~~
    *   **File (`database/seeders/DatabaseSeeder.php` - Update existing):**
        ~~~php
        <?php

        namespace Database\Seeders;

        use Illuminate\Database\Seeder;

        class DatabaseSeeder extends Seeder
        {
            /**
             * Seed the application's database.
             */
            public function run(): void
            {
                 // Call individual seeders
                 $this->call([
                     UserSeeder::class,
                     TeamSeeder::class,
                     // Add other seeders here as they are created
                     // E.g., PermissionSeeder::class
                 ]);
            }
        }
        ~~~
    *   **Concept:** Seeders populate the database. `DatabaseSeeder` is the main entry point that calls other specific seeders. We create users and teams, establishing relationships between them.
    *   **Rationale:** Provides initial data for development and testing. Demonstrates how to create related models and populate pivot tables.
    *   **Verification:** Run `php artisan db:seed`. Check the `users`, `teams`, and `team_user` tables for data.

9.  **Define Base Service Class (Optional but Recommended):**
    *   **Action:** Create a base service class for common functionality if needed.
    *   **File (`app/Services/BaseService.php`):**
        ~~~php
        <?php

        namespace App\Services;

        use Illuminate\Support\Facades\Log;

        /**
         * Base Service Class
         * Provides common functionality or structure for other services.
         */
        abstract class BaseService
        {
            // Example: Common logging method
            protected function logInfo(string $message, array $context = []): void
            {
                Log::info(get_class($this) . ': ' . $message, $context);
            }

            protected function logError(string $message, array $context = [], ?\Throwable $exception = null): void
            {
                 if ($exception) {
                     Log::error(get_class($this) . ': ' . $message, array_merge($context, ['exception' => $exception]));
                 } else {
                     Log::error(get_class($this) . ': ' . $message, $context);
                 }
            }

             // Add other common methods like error handling, event dispatching helpers etc.
        }

        ~~~
    *   **Concept:** An abstract base class provides a common structure or utility methods that concrete service classes can inherit.
    *   **Rationale:** Promotes DRY (Don't Repeat Yourself) principle for common service tasks like logging or error handling.

10. **Commit Changes:**
    *   **Action:** Commit the completed Phase 1 work.
        ~~~bash
        git add .
        git commit -m "Phase 1: Implement core User/Team models, ULID/Slug/Tracking traits, migrations, factory, seeders"
        ~~~

---

### Phase 2: Authentication, Profile Basics & State Machine (Est. 5-7 Days)

**Goal:** Implement user registration, login, email verification (using the state machine), Two-Factor Authentication (2FA), basic profile editing (name, email), and avatar uploads. Implement UI components for these features in all three stacks (Livewire/Volt, React, Vue).

**Steps:**

1.  **Configure Fortify:**
    *   **Action:** Enable desired features in `config/fortify.php` and ensure required views/components exist (Breeze provided the basics).
    *   **File (`config/fortify.php` - Snippet):**
        ~~~php
        <?php

        use Laravel\Fortify\Features;

        return [
            // ... other config
            'guard' => 'web',
            'passwords' => 'users',
            'username' => 'email',
            'email' => 'email',
            'home' => '/dashboard', // Default redirect after login
            'prefix' => '', // Default route prefix
            'middleware' => ['web'], // Apply web middleware group
            'limiters' => [
                'login' => 'login',
                'two-factor' => 'two-factor',
            ],
            'views' => true, // Use built-in views/routes (Breeze overrides these)

            // --- Enable Features ---
            'features' => [
                Features::registration(),
                Features::resetPasswords(),
                Features::emailVerification(), // Enable email verification
                Features::updateProfileInformation(),
                Features::updatePasswords(),
                Features::twoFactorAuthentication([ // Enable 2FA
                    'confirm' => true, // Require confirmation step
                    'confirmPassword' => true, // Require password confirmation for managing 2FA
                ]),
            ],
             // Optional: Specify custom actions if needed (we'll use defaults for now)
             // 'actions' => [
             //     // \Laravel\Fortify\Contracts\RegisterUser::class => \App\Actions\Fortify\RegisterUserAction::class,
             // ],
        ];
        ~~~
    *   **Concept:** Fortify is configured via its config file. Features are enabled/disabled here. It relies on the `web` middleware group and session-based authentication by default.
    *   **Rationale:** Activates the backend logic for registration, password reset, email verification, profile updates, and 2FA as required.
    *   **Verification:** Routes defined by Fortify (e.g., `/login`, `/register`, `/two-factor-challenge`) should be active (`php artisan route:list`).

2.  **Define Account State Enum & State Machine Classes:**
    *   **Action:** Create the Enum for account statuses and the State Machine classes using `spatie/laravel-model-states`.
    *   **File (`app/Enums/AccountStatus.php`):**
        ~~~php
        <?php

        namespace App\Enums;

        enum AccountStatus: string
        {
            case PendingValidation = 'pending_validation';
            case Active = 'active';
            case Suspended = 'suspended';
            case Deactivated = 'deactivated';
            // case Deleted = 'deleted'; // Maybe not a state, but a final action

            // Optional: Helper methods for labels, colors etc.
            public function label(): string
            {
                return match($this) {
                    self::PendingValidation => __('Pending Validation'), // Use Laravel localization helper __()
                    self::Active => __('Active'),
                    self::Suspended => __('Suspended'),
                    self::Deactivated => __('Deactivated'),
                };
            }

            public function color(): string // Example TailwindCSS color classes
            {
                 return match($this) {
                     self::PendingValidation => 'text-yellow-800 bg-yellow-100 dark:text-yellow-300 dark:bg-yellow-900/50',
                     self::Active => 'text-green-800 bg-green-100 dark:text-green-300 dark:bg-green-900/50',
                     self::Suspended => 'text-red-800 bg-red-100 dark:text-red-300 dark:bg-red-900/50',
                     self::Deactivated => 'text-gray-800 bg-gray-100 dark:text-gray-300 dark:bg-gray-900/50',
                 };
            }
        }
        ~~~
    *   **File (`app/States/User/AccountState.php` - Base State):**
        ~~~php
        <?php

        namespace App\States\User;

        use App\Enums\AccountStatus; // Import the Enum
        use Spatie\ModelStates\State;
        use Spatie\ModelStates\StateConfig;

        // Abstract base class for all account states
        abstract class AccountState extends State
        {
            // Associate state classes with Enum values for consistency and potential mapping
            abstract public static function status(): AccountStatus;

            // Configure transitions globally or per state
            public static function config(): StateConfig
            {
                return parent::config()
                    ->default(PendingValidation::class) // Default state for new users
                    // Define allowed transitions (can also be done in concrete state classes)
                    ->allowTransition(PendingValidation::class, Active::class, Actions\Users\ValidateEmailTransition::class) // Example using transition action class
                    ->allowTransition(Active::class, Suspended::class)
                    ->allowTransition(Active::class, Deactivated::class)
                    ->allowTransition(Suspended::class, Active::class)
                    ->allowTransition(Suspended::class, Deactivated::class)
                    ->allowTransition(Deactivated::class, Active::class); // Allow reactivation?
                    // ->allowTransition([Active::class, Suspended::class, Deactivated::class], Deleted::class); // Transition to a final 'Deleted' state/action
            }

             // Optional: Add common methods or properties for all account states
             public function label(): string
             {
                 return static::status()->label(); // Delegate to Enum helper
             }

             public function color(): string
             {
                 return static::status()->color(); // Delegate to Enum helper
             }
        }
        ~~~
    *   **File (`app/States/User/PendingValidation.php`):**
        ~~~php
        <?php

        namespace App\States\User;

        use App\Enums\AccountStatus;

        class PendingValidation extends AccountState
        {
            public static string $name = 'pending_validation'; // String representation stored in DB

            public static function status(): AccountStatus
            {
                return AccountStatus::PendingValidation;
            }

            // Can override allowed transitions here if needed
            // public static function config(): StateConfig { ... }
        }

        ~~~
    *   **File (`app/States/User/Active.php`):**
        ~~~php
        <?php

        namespace App\States\User;

        use App\Enums\AccountStatus;

        class Active extends AccountState
        {
            public static string $name = 'active'; // String representation stored in DB

            public static function status(): AccountStatus
            {
                return AccountStatus::Active;
            }
        }
        ~~~
    *   **File (`app/States/User/Suspended.php`):**
        ~~~php
        <?php

        namespace App\States\User;

        use App\Enums\AccountStatus;

        class Suspended extends AccountState
        {
            public static string $name = 'suspended'; // String representation stored in DB

            public static function status(): AccountStatus
            {
                return AccountStatus::Suspended;
            }
        }
        ~~~
    *   **File (`app/States/User/Deactivated.php`):**
        ~~~php
        <?php

        namespace App\States\User;

        use App\Enums\AccountStatus;

        class Deactivated extends AccountState
        {
            public static string $name = 'deactivated'; // String representation stored in DB

            public static function status(): AccountStatus
            {
                return AccountStatus::Deactivated;
            }
        }
        ~~~
     * **File (`app/Actions/Users/ValidateEmailTransition.php` - Example Transition Action):**
        ~~~php
        <?php

        namespace App\Actions\Users;

        use App\Models\User;
        use App\Events\User\AccountActivated; // Example event
        use Spatie\ModelStates\Transition;

        class ValidateEmailTransition extends Transition
        {
            private User $user;

            public function __construct(User $user)
            {
                $this->user = $user;
            }

            public function handle(): User
            {
                // Perform actions *during* the transition
                $this->user->email_verified_at = now();
                $this->user->save(); // Save changes made during transition

                // Dispatch event *after* successful transition
                // Note: The state change itself happens automatically after this handle method returns the model
                event(new AccountActivated($this->user));

                return $this->user;
            }
        }
        ~~~
    *   **Concept:** Enums provide type-safe constant values. `spatie/laravel-model-states` implements the State pattern. We define an abstract base `AccountState` and concrete classes for each state (`PendingValidation`, `Active`, etc.). The `config()` method defines the default state and allowed transitions. `static::$name` defines the string stored in the database. We link states to the Enum for helper methods (`label`, `color`). Transition classes encapsulate logic performed *during* a state change.
    *   **Rationale:** Implements the required sophisticated account lifecycle management using a robust pattern. Enforces valid state changes. Connects states to Enums for clarity and UI helpers.
    *   **Verification:** Code compiles. The `User` model's `account_state` cast points to `AccountState::class`. Default state is set correctly when creating users via factory or registration.

3.  **Integrate State Machine with Email Verification:**
    *   **Action:** Modify the registration process and email verification controller/action to use the state machine.
    *   **Concept:** Fortify handles sending the verification email. When the user clicks the link, a Laravel signed route verifies the request. We hook into the successful verification process to transition the user's state.
    *   **Rationale:** Links the standard email verification flow directly to the user's account status lifecycle.
    *   **Step 3a: Ensure User is created in `PendingValidation` state.**
        *   The `User` model's `booted` method already sets `PendingValidation::class` as the default if `account_state` is empty during creation. Fortify's default registration action doesn't explicitly set state, so this default mechanism should work. Alternatively, publish and customize Fortify's `CreateNewUser` action to set the state explicitly.
    *   **Step 3b: Modify Verification Controller (if using Breeze/custom):**
        *   Laravel Breeze typically publishes an `EmailVerificationNotificationController`. If you customized it or are using a different approach, find the controller handling the `verification.verify` route.
        *   Inside the method that handles successful verification (usually after checking the hash and marking email as verified), add the state transition logic.
    *   **File (`app/Http/Controllers/Auth/VerifyEmailController.php` - Example, assuming a custom controller or modifying Breeze's logic):**
        ~~~php
        <?php

        namespace App\Http\Controllers\Auth;

        use App\Http\Controllers\Controller;
        use App\Providers\RouteServiceProvider;
        use Illuminate\Foundation\Auth\EmailVerificationRequest;
        use App\States\User\Active; // Import Active state
        use App\Events\User\AccountActivated; // Import custom event

        class VerifyEmailController extends Controller
        {
            /**
             * Mark the authenticated user's email address as verified.
             */
            public function __invoke(EmailVerificationRequest $request): \Illuminate\Http\RedirectResponse
            {
                $user = $request->user();

                if ($user->hasVerifiedEmail()) {
                    return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
                }

                // Mark email as verified (Fortify might handle this implicitly, check behavior)
                if ($user->markEmailAsVerified()) {
                    // --- STATE MACHINE INTEGRATION ---
                    // Transition user to Active state ONLY if they are currently PendingValidation
                    if ($user->account_state instanceof \App\States\User\PendingValidation) {
                         try {
                              // Transition using the state machine
                              $user->transitionTo(Active::class);

                              // Dispatch specific domain event (optional, if ValidateEmailTransition doesn't already)
                              // event(new AccountActivated($user));

                         } catch (\Spatie\ModelStates\Exceptions\TransitionNotFound | \Spatie\ModelStates\Exceptions\CouldNotPerformTransition $e) {
                              // Log error if transition fails (shouldn't happen based on config)
                              \Illuminate\Support\Facades\Log::error("Failed state transition for user {$user->id} during email verification.", ['exception' => $e]);
                              // Decide how to handle this - maybe redirect with error?
                         }
                    }
                    // --- END STATE MACHINE INTEGRATION ---

                     // Original event dispatched by Laravel after marking verified
                     event(new \Illuminate\Auth\Events\Verified($user));
                }


                return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
            }
        }
        ~~~
    *   **Verification:** Register a new user. They should start in `PendingValidation` state (check DB). Click the verification link in the email. The user's `email_verified_at` should be set, and `account_state` should transition to `active`.

4.  **Implement Two-Factor Authentication (2FA) UI:**
    *   **Action:** Create frontend components for enabling, confirming, disabling 2FA, viewing recovery codes, and handling the 2FA challenge during login. These components will interact with Fortify's backend routes.
    *   **Concept:** Fortify provides the backend logic and routes (`/user/two-factor-authentication`, `/user/confirmed-two-factor-authentication`, `/two-factor-challenge`, etc.). The frontend needs to make requests to these routes and display information (QR code, recovery codes) returned by Fortify.
    *   **Rationale:** Provides the user interface for the 2FA security feature enabled in Fortify.
    *   **Implementation (Conceptual - requires detailed components):**
        *   **Location:** Typically within user profile/settings pages.
        *   **Enable:** Button calls `POST /user/two-factor-authentication`. On success, backend returns QR code (`svg`) and recovery codes (`string[]`). Display QR code and codes. Add input for confirmation code.
        *   **Confirm:** Input field + button calls `POST /user/confirmed-two-factor-authentication` with the code from authenticator app.
        *   **Disable:** Button calls `DELETE /user/two-factor-authentication`. Requires password confirmation if enabled in `fortify.php`.
        *   **Show Recovery Codes:** Button calls `GET /user/two-factor-recovery-codes`. Display returned codes.
        *   **Login Challenge:** After correct password login for 2FA-enabled user, Fortify redirects to `/two-factor-challenge`. Create a view/component for this route with an input for the code or a recovery code. Submit calls `POST /two-factor-challenge`.

    *   **UI Component Examples (Placeholders - requires full implementation):**
        *   **Directory Structure:**
            ~~~
            resources/
            ├── js/ # Inertia (React/Vue)
            │   ├── Pages/
            │   │   ├── Profile/
            │   │   │   ├── Partials/
            │   │   │   │   ├── TwoFactorAuthenticationForm.jsx | .vue
            │   │   │   │   └── ...
            │   │   ├── Auth/
            │   │   │   ├── TwoFactorChallenge.jsx | .vue
            │   │   └── ...
            ├── views/ # Livewire/Volt
            │   ├── profile/
            │   │   ├── partials/
            │   │   │   ├── two-factor-authentication-form.blade.php (Volt SFC)
            │   │   │   └── ...
            │   ├── auth/
            │   │   ├── two-factor-challenge.blade.php (Volt SFC or regular Livewire)
            │   └── ...
            ~~~
        *   **`TwoFactorAuthenticationForm` (Conceptual Logic):**
            *   Check if 2FA is enabled (`user.two_factor_enabled` provided by backend).
            *   **If Disabled:** Show "Enable" button. On click: POST to enable endpoint. On success: Show QR code, recovery codes, confirmation input. Handle confirmation POST.
            *   **If Enabled:** Show recovery codes button (GET codes), disable button (DELETE endpoint, handle password confirmation if required).
        *   **`TwoFactorChallenge` (Conceptual Logic):**
            *   Form with input for 'code' or 'recovery_code'.
            *   Submit button POSTs to `/two-factor-challenge`.
            *   Handle validation errors from backend.

    *   **Verification:** Log in as a user. Navigate to profile/settings. Enable 2FA, scan QR code, confirm. Log out. Log back in, encounter the challenge page, enter code, successfully log in. View recovery codes. Disable 2FA.

5.  **Implement Basic Profile Information UI (Name, Email):**
    *   **Action:** Create frontend components to display and update the user's name (given, family, other) and email address.
    *   **Concept:** Interact with Fortify's `/user/profile-information` endpoint (PUT request). Breeze scaffolding usually provides a starting point. Adapt it for separate name fields.
    *   **Rationale:** Allows users to manage their basic profile data.
    *   **UI Component Examples (Adapting Breeze Partials):**
        *   **File (`.../Profile/Partials/UpdateProfileInformationForm.jsx|vue` or `.../profile/partials/update-profile-information-form.blade.php`):**
            *   Modify the existing form provided by Breeze.
            *   Replace the single `name` input with separate inputs for `given_name`, `family_name`, `other_names`.
            *   Ensure the `email` input remains.
            *   Update the form submission logic to send the new name components along with the email to the `PUT /user/profile-information` endpoint. Fortify's default action handles updating the user model.
            *   Display success messages and validation errors. Handle email verification notice if the email is changed and requires re-verification.

    *   **Verification:** Navigate to the profile page. Update name components and email. Save changes. Verify the data is updated in the database and displayed correctly. If email changed, check if verification email is sent and status changes appropriately.

6.  **Implement Avatar Upload UI & Logic:**
    *   **Action:** Create a UI component for avatar display and upload. Create a backend controller/route to handle the upload using `spatie/laravel-medialibrary`.
    *   **Rationale:** Allows users to personalize their profile with an avatar. Leverages `spatie/laravel-medialibrary` for robust file handling.
    *   **Step 6a: Backend Route & Controller:**
        ~~~bash
        php artisan make:controller Profile/AvatarController --invokable
        ~~~
        *   **File (`routes/web.php` - Add inside `auth` middleware group):**
            ~~~php
            use App\Http\Controllers\Profile\AvatarController;

            // ... other routes inside middleware('auth') ...
            Route::post('/user/avatar', AvatarController::class)->name('user.avatar.update');
            ~~~
        *   **File (`app/Http/Controllers/Profile/AvatarController.php`):**
            ~~~php
            <?php

            namespace App\Http\Controllers\Profile;

            use App\Http\Controllers\Controller;
            use Illuminate\Http\Request;
            use Illuminate\Support\Facades\Redirect;
            use Illuminate\Validation\Rules\File; // Use Illuminate File rule

            class AvatarController extends Controller
            {
                /**
                 * Update the user's profile avatar.
                 */
                public function __invoke(Request $request): \Illuminate\Http\RedirectResponse
                {
                    $request->validate([
                        'avatar' => [
                            'required',
                             File::image() // Use Laravel's built-in image rule
                                 ->max(2 * 1024) // Max 2MB example
                                 ->dimensions(Rule::dimensions()->minWidth(100)->minHeight(100)), // Example dimensions
                         ],
                    ]);

                    $user = $request->user();

                    // Clear existing avatar if it exists in the collection
                    $user->clearMediaCollection('avatar');

                    // Add the new avatar
                    $user->addMediaFromRequest('avatar')
                         ->toMediaCollection('avatar'); // 'avatar' matches collection name in User model

                    // Redirect back with success message
                    // For Inertia, use inertia()->back() or specific redirect with flash message
                    return Redirect::back()->with('status', 'avatar-updated');
                    // For Livewire, flash message is often handled within component $this->dispatch(...)
                }
            }
            ~~~
    *   **Step 6b: Frontend Component:**
        *   **File (`.../Profile/Partials/UpdateAvatarForm.jsx|vue` or `.../profile/partials/update-avatar-form.blade.php` - New):**
            *   Display the current avatar using the `avatar_url` accessor (provided by `User` model).
            *   Include a file input (`<input type="file" accept="image/*">`).
            *   On file selection, optionally show a preview.
            *   On form submission:
                *   **Inertia:** Use Inertia's form helper (`useForm`) to POST the file to `/user/avatar`. Handle progress, errors, success. Remember `FormData` is needed for file uploads.
                *   **Livewire/Volt:** Use Livewire's file upload features (`WithFileUploads` trait). Wire the file input (`wire:model="avatar"`). Handle validation and the upload process within the Livewire component's save method, calling `$this->avatar->store(...)` or interacting directly with the media library after storing temporarily.
            *   Update the displayed avatar on successful upload without a full page reload.

    *   **Verification:** Go to profile page. Upload a valid image file. The new avatar should be displayed. Check the `media` table and storage directory for the uploaded file and its conversions (e.g., 'thumb'). Upload an invalid file (too large, wrong type) and verify validation errors are shown.

7.  **Create `UserService` (Initial Version):**
    *   **Action:** Create a service class to start encapsulating user-related business logic, like creation or state transitions (though simple transitions might remain in controllers for now).
    *   **File (`app/Services/UserService.php`):**
        ~~~php
        <?php

        namespace App\Services;

        use App\Models\User;
        use App\States\User\PendingValidation; // Import state
        use Illuminate\Support\Facades\Hash;
        use Illuminate\Support\Facades\Log;
        use App\Events\User\UserRegistered; // Assuming this event exists or will be created

        class UserService extends BaseService
        {
            /**
             * Create a new user.
             * Centralizes user creation logic, including setting default state.
             *
             * @param array $validatedData Data from registration request
             * @return User
             * @throws \Throwable
             */
            public function createUser(array $validatedData): User
            {
                $this->logInfo('Attempting to create new user.', ['email' => $validatedData['email']]);

                try {
                    // Ensure password is hashed
                    $validatedData['password'] = Hash::make($validatedData['password']);

                    // Explicitly set the initial state
                    $validatedData['account_state'] = PendingValidation::class;

                    // Create the user (ULID/Slug handled by model/traits)
                    $user = User::create($validatedData);

                    // Dispatch an event
                    event(new UserRegistered($user)); // Make sure this event exists

                    $this->logInfo('User created successfully.', ['user_ulid' => $user->ulid]);

                    return $user;

                } catch (\Throwable $e) {
                    $this->logError('Failed to create user.', ['email' => $validatedData['email']], $e);
                    // Re-throw the exception to be handled by the controller or global exception handler
                    throw $e;
                }
            }

            // Add other user-related methods here later, e.g.:
            // - suspendUser(User $user, string $reason)
            // - reactivateUser(User $user)
            // - findUserByUlid(string $ulid)
            // - updateUserProfile(User $user, array $data) // Could replace Fortify action if needed
        }
        ~~~
    *   **Action:** Optionally update Fortify's registration action to use this service.
        ~~~bash
        php artisan make:action Fortify/CreateNewUser --invokable
        ~~~
    *   **File (`app/Actions/Fortify/CreateNewUser.php`):**
        ~~~php
        <?php

        namespace App\Actions\Fortify;

        use App\Models\User;
        use App\Services\UserService; // Import UserService
        use Illuminate\Support\Facades\Validator;
        use Illuminate\Validation\Rule;
        use Laravel\Fortify\Contracts\CreatesNewUsers;

        class CreateNewUser implements CreatesNewUsers
        {
            use PasswordValidationRules;

            public function __construct(protected UserService $userService) {} // Inject UserService

            /**
             * Validate and create a newly registered user.
             *
             * @param  array<string, string>  $input
             */
            public function create(array $input): User
            {
                Validator::make($input, [
                    // Use name components for validation
                    'given_name' => ['required', 'string', 'max:255'],
                    'family_name' => ['required', 'string', 'max:255'],
                    'other_names' => ['nullable', 'string', 'max:255'],
                    // 'name' => ['required', 'string', 'max:255'], // Remove original name validation
                    'email' => [
                        'required',
                        'string',
                        'email',
                        'max:255',
                        Rule::unique(User::class),
                    ],
                    'password' => $this->passwordRules(),
                ])->validate();

                 // Prepare data for the service (ensure keys match service expectation)
                 $userData = [
                     'given_name' => $input['given_name'],
                     'family_name' => $input['family_name'],
                     'other_names' => $input['other_names'] ?? null,
                     'email' => $input['email'],
                     'password' => $input['password'],
                 ];

                // Delegate user creation to the UserService
                return $this->userService->createUser($userData);
            }
        }

        ~~~
    *   **Action:** Register the custom action in a service provider (e.g., `app/Providers/FortifyServiceProvider.php` or `AppServiceProvider`).
    *   **File (`app/Providers/AppServiceProvider.php` - or dedicated Fortify provider):**
        ~~~php
        <?php
        namespace App\Providers;

        use Illuminate\Support\ServiceProvider;
        use Laravel\Fortify\Contracts\CreatesNewUsers; // Import contract
        use App\Actions\Fortify\CreateNewUser; // Import custom action

        class AppServiceProvider extends ServiceProvider
        {
            // ... boot method ...

            public function register(): void
            {
                 // Bind the custom action to the Fortify contract
                 $this->app->singleton(
                     CreatesNewUsers::class,
                     CreateNewUser::class
                 );

                 // You might bind your services here as well
                 // $this->app->singleton(\App\Services\UserService::class, \App\Services\UserService::class);
            }
        }
        ~~~
    *   **Concept:** Services encapsulate business logic. The `UserService` now handles the specifics of creating a user, including setting the initial state and dispatching an event. Fortify Actions allow customizing specific authentication processes. We replace the default user creation logic with our service call. Dependency Injection automatically provides the `UserService` instance to the action.
    *   **Rationale:** Centralizes user creation logic, making controllers/actions thinner and logic more reusable and testable. Ensures consistent state setting.
    *   **Verification:** Registration process still works, users are created in `PendingValidation` state, and the `UserRegistered` event is dispatched (can check logs or Telescope).

8.  **Define Required Events & Listeners (Initial):**
    *   **Action:** Create basic events and listeners related to registration and state changes.
        ~~~bash
        php artisan make:event User/UserRegistered
        php artisan make:event User/AccountActivated
        php artisan make:event User/AccountSuspended # Example for later
        # Create listeners (make them queued)
        php artisan make:listener Listeners/User/SendWelcomeEmail --queued --event=User/UserRegistered
        php artisan make:listener Listeners/User/LogRegistrationActivity --queued --event=User/UserRegistered
        php artisan make:listener Listeners/User/NotifyAdminOnActivation --queued --event=User/AccountActivated
        ~~~
    *   **File (`app/Events/User/UserRegistered.php`):**
        ~~~php
        <?php
        namespace App\Events\User;

        use App\Models\User;
        use Illuminate\Broadcasting\InteractsWithSockets;
        use Illuminate\Foundation\Events\Dispatchable;
        use Illuminate\Queue\SerializesModels;

        class UserRegistered
        {
            use Dispatchable, InteractsWithSockets, SerializesModels;

            /**
             * Create a new event instance.
             */
            public function __construct(public User $user) // PHP 8+ Constructor Property Promotion
            {
                // The user model will be serialized
            }
        }
        ~~~
    *   **File (`app/Events/User/AccountActivated.php`):**
        ~~~php
        <?php
        namespace App\Events\User;

        use App\Models\User;
        use Illuminate\Broadcasting\InteractsWithSockets;
        use Illuminate\Foundation\Events\Dispatchable;
        use Illuminate\Queue\SerializesModels;

        class AccountActivated
        {
            use Dispatchable, InteractsWithSockets, SerializesModels;

            /**
             * Create a new event instance.
             */
            public function __construct(public User $user)
            {
            }
        }
        ~~~
    *   **File (`app/Listeners/User/SendWelcomeEmail.php`):**
        ~~~php
        <?php
        namespace App\Listeners\User;

        use App\Events\User\UserRegistered;
        use Illuminate\Contracts\Queue\ShouldQueue; // Implement ShouldQueue
        use Illuminate\Queue\InteractsWithQueue;
        use Illuminate\Support\Facades\Mail; // Use Mail facade
        use App\Mail\User\WelcomeEmail; // Assume this Mailable exists

        class SendWelcomeEmail implements ShouldQueue // Mark as queued
        {
            use InteractsWithQueue;

            public $queue = 'emails'; // Assign to a specific queue (configure in Horizon)

            /**
             * Handle the event.
             */
            public function handle(UserRegistered $event): void
            {
                 // Ensure email verification isn't required *before* welcome, or adjust logic
                 // Maybe only send welcome *after* AccountActivated event? Depends on desired flow.
                 // For now, assume we send on registration.
                 try {
                     // Create a Mailable class: php artisan make:mail User/WelcomeEmail --markdown=emails.user.welcome
                     Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
                 } catch (\Throwable $e) {
                     \Illuminate\Support\Facades\Log::error('Failed to send welcome email', ['user_id' => $event->user->id, 'exception' => $e]);
                     // Optional: Release job back to queue with delay
                     // $this->release(60);
                 }
            }
        }

        ~~~
    *   **File (`app/Listeners/User/LogRegistrationActivity.php`):**
        ~~~php
        <?php
        namespace App\Listeners\User;

        use App\Events\User\UserRegistered;
        use Illuminate\Contracts\Queue\ShouldQueue;
        use Illuminate\Queue\InteractsWithQueue;
        use Spatie\Activitylog\Facades\Activity; // Use ActivityLog facade

        class LogRegistrationActivity implements ShouldQueue
        {
            use InteractsWithQueue;

            public $queue = 'logging'; // Assign to logging queue

            /**
             * Handle the event.
             */
            public function handle(UserRegistered $event): void
            {
                 try {
                     activity()
                        ->performedOn($event->user) // Subject of the activity
                        // ->causedBy($event->user) // User caused their own registration
                        ->log('User registered'); // Description
                 } catch (\Throwable $e) {
                     \Illuminate\Support\Facades\Log::error('Failed to log registration activity', ['user_id' => $event->user->id, 'exception' => $e]);
                 }
            }
        }
        ~~~
    *   **Action:** Register events and listeners.
    *   **File (`app/Providers/EventServiceProvider.php` - Update):**
        ~~~php
        <?php
        namespace App\Providers;

        use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
        use Illuminate\Support\Facades\Event;

        // Import Events & Listeners
        use App\Events\User\UserRegistered;
        use App\Events\User\AccountActivated;
        use App\Listeners\User\SendWelcomeEmail;
        use App\Listeners\User\LogRegistrationActivity;
        // use App\Listeners\User\NotifyAdminOnActivation; // Add when created

        class EventServiceProvider extends ServiceProvider
        {
            /**
             * The event to listener mappings for the application.
             *
             * @var array<class-string, array<int, class-string>>
             */
            protected $listen = [
                // User Events
                UserRegistered::class => [
                    SendWelcomeEmail::class,
                    LogRegistrationActivity::class,
                ],
                AccountActivated::class => [
                    // NotifyAdminOnActivation::class,
                    // Other actions on activation...
                ],

                // Other Application Events...
                \Illuminate\Auth\Events\Login::class => [
                    // Listeners for successful login...
                ],
                 \Illuminate\Auth\Events\Logout::class => [
                     // Listeners for logout...
                 ],
            ];

            // ... boot method ...

            /**
             * Determine if events and listeners should be automatically discovered.
             * Set to true if you prefer discovery over explicit mapping in $listen array.
             */
            public function shouldDiscoverEvents(): bool
            {
                return false; // Explicit mapping preferred for clarity
            }
        }
        ~~~
    *   **Concept:** Events decouple actions (like registration) from side effects (sending email, logging). Listeners handle these side effects. Marking listeners with `ShouldQueue` runs them in the background via the queue system for better performance. The `EventServiceProvider` registers which listeners should handle which events.
    *   **Rationale:** Implements the required decoupled architecture using Events & Listeners, improving maintainability and performance (via queues).
    *   **Verification:** Register a user. Check logs for listener execution (or Horizon dashboard if configured). Check activity log table (`activity_log`) for registration entry. Check mail log/Mailtrap for welcome email (requires Mailable creation). Check queue worker output.

9.  **Commit Changes:**
    *   **Action:** Commit the completed Phase 2 work.
        ~~~bash
        git add .
        git commit -m "Phase 2: Implement Auth (Fortify), 2FA, State Machine (User), Profile/Avatar UI (3x), UserService v1, Events/Listeners"
        ~~~

---

*(Continue with Phases 3-6, following the same detailed structure: Action, Concept, Rationale, File Creation/Modification with full code, UI Component Placeholders (3x), Verification)*

---

### Phase 3: Teams & Permissions (Est. 4-6 Days)

**Goal:** Implement team hierarchy, team management UI, role-based access control within teams using `spatie/laravel-permission`.

**Steps:**

1.  **Configure Spatie Permission for Teams:**
    *   **Action:** Modify `config/permission.php` to enable team support.
    *   **File (`config/permission.php` - Snippet):**
        ~~~php
        <?php
        return [
            // ... other config
            'models' => [
                'permission' => Spatie\Permission\Models\Permission::class,
                'role' => Spatie\Permission\Models\Role::class,
            ],
            'table_names' => [
                'roles' => 'roles',
                'permissions' => 'permissions',
                'model_has_permissions' => 'model_has_permissions',
                'model_has_roles' => 'model_has_roles',
                'role_has_permissions' => 'role_has_permissions',
            ],
            'column_names' => [
                'role_pivot_key' => null, // Use 'role_id'
                'permission_pivot_key' => null, // Use 'permission_id'
                'model_morph_key' => 'model_id',
                'team_foreign_key' => 'team_id', // **Enable Teams Feature**
            ],
            // ... other config (display_permission_in_exception, cache)
             'teams' => true, // **Explicitly Enable Teams Feature**
             'team_model' => \App\Models\Team::class, // **Specify the Team model**
        ];
        ~~~
    *   **Concept:** This configures the package to add a `team_id` column to the `model_has_roles` and `model_has_permissions` tables, allowing roles and permissions to be scoped to specific teams.
    *   **Rationale:** Enables the core requirement of team-specific roles and permissions.
    *   **Verification:** Run `php artisan migrate` again. Check if the `team_id` column has been added to the `model_has_roles` and `model_has_permissions` tables (Note: You might need to manually create a migration to add this column if the initial migration from the package didn't include it based on this config change).

2.  **Create `PermissionSeeder`:**
    *   **Action:** Create roles and permissions, potentially scoped to teams.
        ~~~bash
        php artisan make:seeder PermissionSeeder
        ~~~
    *   **File (`database/seeders/PermissionSeeder.php`):**
        ~~~php
        <?php

        namespace Database\Seeders;

        use Illuminate\Database\Seeder;
        use Spatie\Permission\Models\Role;
        use Spatie\Permission\Models\Permission;
        use Spatie\Permission\PermissionRegistrar;
        use App\Models\Team; // Import Team model
        use App\Models\User; // Import User model

        class PermissionSeeder extends Seeder
        {
            /**
             * Run the database seeds.
             */
            public function run(): void
            {
                // Reset cached roles and permissions
                app()[PermissionRegistrar::class]->forgetCachedPermissions();

                // Define Permissions (can be global or assigned within teams)
                Permission::findOrCreate('view team dashboard', 'web');
                Permission::findOrCreate('manage team members', 'web');
                Permission::findOrCreate('edit team settings', 'web');
                Permission::findOrCreate('delete team', 'web');
                Permission::findOrCreate('create chat messages', 'web');
                Permission::findOrCreate('view presence', 'web');
                Permission::findOrCreate('impersonate users', 'web'); // Global permission maybe?

                // Define Roles (can be global or team-specific)
                $adminRole = Role::findOrCreate('Admin', 'web'); // Global Admin?
                $teamOwnerRole = Role::findOrCreate('Team Owner', 'web'); // Role concept
                $editorRole = Role::findOrCreate('Editor', 'web'); // Role concept
                $memberRole = Role::findOrCreate('Member', 'web'); // Role concept

                // Assign permissions to roles (can be done globally)
                $teamOwnerRole->givePermissionTo(['view team dashboard', 'manage team members', 'edit team settings', 'create chat messages', 'view presence']);
                $editorRole->givePermissionTo(['view team dashboard', 'create chat messages', 'view presence']);
                $memberRole->givePermissionTo(['view team dashboard', 'create chat messages', 'view presence']);
                 // Global admin gets all (or specific high-level permissions)
                 $adminRole->givePermissionTo(Permission::all());


                // Assign roles/permissions to users within specific teams
                $adminUser = User::where('email', 'admin@example.com')->first();
                $firstTeam = Team::where('name', 'Acme Corporation')->first();

                if ($adminUser && $firstTeam) {
                    // Assign global Admin role to the admin user
                    $adminUser->assignRole($adminRole);

                    // Assign Team Owner role specifically within the first team
                    // NOTE: assignRole() on the user model scopes to the team when teams are enabled!
                    $adminUser->assignRole($teamOwnerRole, $firstTeam);

                    // Assign roles to other members in the team
                    $members = $firstTeam->users()->where('users.id', '!=', $adminUser->id)->get();
                    foreach ($members as $member) {
                         // Assign a role (e.g., Member) within this specific team
                         $member->assignRole($memberRole, $firstTeam);

                         // Example: Assign a direct permission within the team
                         // $member->givePermissionTo('create chat messages', $firstTeam);
                    }
                     $this->command->info('Team roles assigned.');
                } else {
                     $this->command->warn('Admin user or first team not found. Skipping team role assignment.');
                }
            }
        }
        ~~~
    *   **Action:** Add `PermissionSeeder` to `DatabaseSeeder`.
    *   **File (`database/seeders/DatabaseSeeder.php` - Update):**
        ~~~php
        public function run(): void
        {
             $this->call([
                 UserSeeder::class,
                 TeamSeeder::class,
                 PermissionSeeder::class, // Add this line
             ]);
        }
        ~~~
    *   **Concept:** Seeders populate initial roles and permissions. `findOrCreate` avoids creating duplicates. When the `teams` feature is enabled in the config, calling `$user->assignRole($role, $team)` or `$user->givePermissionTo($permission, $team)` associates that role/permission specifically with that user *within that team*. The `team_id` is automatically stored in the pivot table.
    *   **Rationale:** Sets up the initial RBAC structure required by the application. Demonstrates team-scoped role assignment.
    *   **Verification:** Run `php artisan db:seed`. Check the `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, and `role_has_permissions` tables. Verify `team_id` is populated correctly in `model_has_roles` for team-specific assignments.

3.  **Create `TeamService`:**
    *   **Action:** Create a service for managing team-related operations (creation, member management, switching).
        ~~~bash
        php artisan make:service TeamService
        ~~~
    *   **File (`app/Services/TeamService.php`):**
        ~~~php
        <?php

        namespace App\Services;

        use App\Models\Team;
        use App\Models\User;
        use Illuminate\Support\Facades\DB;
        use Illuminate\Support\Facades\Log;
        use Spatie\Permission\Models\Role; // Import Role
        use App\Events\Team\TeamCreated; // Assume events exist
        use App\Events\Team\UserAddedToTeam;
        use App\Events\Team\UserRemovedFromTeam;
        use App\Events\Team\CurrentTeamSwitched;

        class TeamService extends BaseService
        {
            /**
             * Create a new team.
             *
             * @param User $owner The user creating the team
             * @param array $validatedData Validated team data (name, description, parent_id?)
             * @return Team
             * @throws \Throwable
             */
            public function createTeam(User $owner, array $validatedData): Team
            {
                $this->logInfo('Attempting to create new team.', ['owner_id' => $owner->id, 'name' => $validatedData['name']]);

                DB::beginTransaction();
                try {
                    // Ensure owner_id and tracking is set
                    $validatedData['owner_id'] = $owner->id;
                    $validatedData['created_by_id'] = $owner->id;
                    $validatedData['updated_by_id'] = $owner->id;

                    $team = Team::create($validatedData);

                    // Add owner to the team automatically
                    // Assign a specific role (e.g., 'Team Owner') within this team
                    $ownerRole = Role::findByName('Team Owner', 'web'); // Ensure this role exists
                    if ($ownerRole) {
                        $team->users()->attach($owner->id, ['role' => $ownerRole->name]); // Store role name in pivot? Or use assignRole?
                        // Alternatively, using Spatie's method:
                        $owner->assignRole($ownerRole, $team);
                    } else {
                         // Fallback or log warning if role not found
                         $team->users()->attach($owner->id); // Just attach without role
                         $this->logError('Team Owner role not found during team creation.', ['team_id' => $team->id]);
                    }


                    // Make the new team the owner's current team
                    $owner->switchTeam($team); // Use the helper on the User model

                    DB::commit();

                    event(new TeamCreated($team, $owner));
                    $this->logInfo('Team created successfully.', ['team_ulid' => $team->ulid]);

                    return $team;

                } catch (\Throwable $e) {
                    DB::rollBack();
                    $this->logError('Failed to create team.', ['owner_id' => $owner->id, 'name' => $validatedData['name']], $e);
                    throw $e;
                }
            }

            /**
             * Add a user to a team with a specific role.
             *
             * @param User $actor User performing the action
             * @param Team $team Team to add user to
             * @param User $userToAdd User being added
             * @param string $roleName Role name (e.g., 'Member', 'Editor')
             * @return bool Success status
             */
            public function addUserToTeam(User $actor, Team $team, User $userToAdd, string $roleName): bool
            {
                 // Authorization check: Ensure $actor has permission to manage members
                 // if ($actor->cannot('manage team members', $team)) {
                 //     throw new \Illuminate\Auth\Access\AuthorizationException('Unauthorized to add members.');
                 // }

                 $this->logInfo('Attempting to add user to team.', ['team_id' => $team->id, 'user_id' => $userToAdd->id, 'role' => $roleName]);

                 try {
                     $role = Role::findByName($roleName, 'web');
                     if (!$role) {
                         $this->logError('Role not found when adding user to team.', ['role_name' => $roleName]);
                         return false;
                     }

                     // Check if user is already in the team (optional, assignRole handles it)
                     // if ($team->users()->where('user_id', $userToAdd->id)->exists()) {
                     //     // Maybe update role instead? Or return false?
                     //     $this->logInfo('User already in team.', ['team_id' => $team->id, 'user_id' => $userToAdd->id]);
                     //     // Update role if needed: $userToAdd->syncRoles([$role], $team);
                     //     return true; // Or false depending on desired behavior
                     // }

                     // Use Spatie's assignRole for team context
                     $userToAdd->assignRole($role, $team);

                     // If user doesn't have a current team, set this one?
                     if (is_null($userToAdd->current_team_id)) {
                         $userToAdd->switchTeam($team);
                     }

                     event(new UserAddedToTeam($team, $userToAdd, $actor));
                     $this->logInfo('User added to team successfully.', ['team_id' => $team->id, 'user_id' => $userToAdd->id]);
                     return true;

                 } catch (\Throwable $e) {
                     $this->logError('Failed to add user to team.', ['team_id' => $team->id, 'user_id' => $userToAdd->id], $e);
                     return false;
                 }
            }

            /**
             * Remove a user from a team.
             *
             * @param User $actor User performing the action
             * @param Team $team Team to remove user from
             * @param User $userToRemove User being removed
             * @return bool Success status
             */
             public function removeUserFromTeam(User $actor, Team $team, User $userToRemove): bool
             {
                  // Authorization check: Ensure $actor can manage members, and not removing the owner?
                  // if ($actor->cannot('manage team members', $team)) { ... }
                  // if ($team->owner_id === $userToRemove->id) { throw new \Exception('Cannot remove team owner.'); }

                  $this->logInfo('Attempting to remove user from team.', ['team_id' => $team->id, 'user_id' => $userToRemove->id]);

                  try {
                      // Remove all roles for this user within this specific team
                      $userToRemove->syncRoles([], $team);

                      // Detach user from team pivot table (alternative if not using syncRoles)
                      // $team->users()->detach($userToRemove->id);

                      // If this was the user's current team, clear it? Or switch to another?
                      if ($userToRemove->current_team_id === $team->id) {
                           // Find another team the user belongs to, or set to null
                           $anotherTeam = $userToRemove->teams()->where('teams.id', '!=', $team->id)->first();
                           $userToRemove->forceFill(['current_team_id' => $anotherTeam?->id])->save();
                      }

                      event(new UserRemovedFromTeam($team, $userToRemove, $actor));
                      $this->logInfo('User removed from team successfully.', ['team_id' => $team->id, 'user_id' => $userToRemove->id]);
                      return true;

                  } catch (\Throwable $e) {
                      $this->logError('Failed to remove user from team.', ['team_id' => $team->id, 'user_id' => $userToRemove->id], $e);
                      return false;
                  }
             }


            /**
             * Switch the user's active team context.
             *
             * @param User $user
             * @param Team $team
             * @return bool
             */
            public function switchUserTeam(User $user, Team $team): bool
            {
                 if ($user->switchTeam($team)) { // Use the helper method on User model
                     event(new CurrentTeamSwitched($user, $team));
                     $this->logInfo('User switched team context.', ['user_id' => $user->id, 'team_id' => $team->id]);
                     return true;
                 }
                 $this->logInfo('User failed to switch team context (not a member?).', ['user_id' => $user->id, 'team_id' => $team->id]);
                 return false;
            }

             // Add methods for updating team settings, deleting teams (with checks), etc.
        }
        ~~~
    *   **Concept:** The `TeamService` encapsulates the business logic for managing teams. It interacts with `Team` and `User` models, assigns roles using Spatie Permission methods within the team context, handles database transactions, dispatches events, and performs logging.
    *   **Rationale:** Adheres to the Service Layer pattern, keeping controllers thin and logic organized, reusable, and testable. Handles complexities like assigning owner roles and switching contexts.
    *   **Verification:** Can be tested via unit tests (mocking models/events) or feature tests (calling service methods via controllers).

4.  **Implement Team Management UI:**
    *   **Action:** Create UI components for viewing, creating, updating teams, managing members (add/remove/update role), and switching the current team.
    *   **Concept:** These UIs will interact with backend API endpoints or Livewire component actions, which in turn will call the `TeamService`. Authorization (using Policies or Gates) is crucial to control who can perform which actions.
    *   **Rationale:** Provides the user interface for team functionality.
    *   **Step 4a: Backend Routes & Controllers (Example Structure):**
        ~~~bash
        # Controllers
        php artisan make:controller TeamController --model=Team --resource
        php artisan make:controller TeamMemberController --model=Team
        php artisan make:controller CurrentTeamController --invokable

        # Policies
        php artisan make:policy TeamPolicy --model=Team
        ~~~
        *   **File (`routes/web.php` - Snippet inside `auth` middleware):**
            ~~~php
            use App\Http\Controllers\TeamController;
            use App\Http\Controllers\TeamMemberController;
            use App\Http\Controllers\CurrentTeamController;

            // Team Resource (CRUD for teams) - Apply middleware/policies
            Route::resource('teams', TeamController::class); // index, create, store, show, edit, update, destroy

            // Team Member Management (Scoped to a team)
            Route::get('/teams/{team}/members', [TeamMemberController::class, 'index'])->name('teams.members.index');
            Route::post('/teams/{team}/members', [TeamMemberController::class, 'store'])->name('teams.members.store'); // Add member
            Route::put('/teams/{team}/members/{user}', [TeamMemberController::class, 'update'])->name('teams.members.update'); // Update role
            Route::delete('/teams/{team}/members/{user}', [TeamMemberController::class, 'destroy'])->name('teams.members.destroy'); // Remove member

            // Switch Current Team
            Route::put('/user/current-team', CurrentTeamController::class)->name('current-team.update');
            ~~~
        *   **File (`app/Policies/TeamPolicy.php` - Example Structure):**
            ~~~php
            <?php
            namespace App\Policies;

            use App\Models\Team;
            use App\Models\User;
            use Illuminate\Auth\Access\HandlesAuthorization;

            class TeamPolicy
            {
                use HandlesAuthorization;

                // Allow admins to do anything (example)
                public function before(User $user, string $ability): bool|null
                {
                    // return $user->hasRole('Admin') ? true : null; // Check for global admin role
                    return $user->email === 'admin@example.com' ? true : null; // Simple check
                }

                public function viewAny(User $user): bool { return true; } // Anyone can see list of teams? Adjust.
                public function view(User $user, Team $team): bool { return $user->belongsToTeam($team); }
                public function create(User $user): bool { return true; } // Anyone can create a team? Adjust.
                public function update(User $user, Team $team): bool { return $user->ownsTeam($team) || $user->hasTeamPermission($team, 'edit team settings'); } // Owner or specific permission
                public function delete(User $user, Team $team): bool { return $user->ownsTeam($team); } // Only owner can delete? Adjust.
                public function addMember(User $user, Team $team): bool { return $user->ownsTeam($team) || $user->hasTeamPermission($team, 'manage team members'); }
                public function updateMemberRole(User $user, Team $team): bool { return $user->ownsTeam($team) || $user->hasTeamPermission($team, 'manage team members'); }
                public function removeMember(User $user, Team $team): bool { return ($user->ownsTeam($team) || $user->hasTeamPermission($team, 'manage team members')) && $team->owner_id !== $user->id; } // Cannot remove owner
            }
            ~~~
        *   **Action:** Register the policy in `AuthServiceProvider`.
        *   **File (`app/Providers/AuthServiceProvider.php`):**
            ~~~php
             protected $policies = [
                 \App\Models\Team::class => \App\Policies\TeamPolicy::class, // Register policy
             ];
            ~~~
        *   **Controllers (`TeamController`, `TeamMemberController`, `CurrentTeamController`):** Implement methods, validate requests, authorize using the policy (`$this->authorize('update', $team)`), call the appropriate `TeamService` methods, and return responses (Inertia views or redirects/JSON).

    *   **Step 4b: Frontend Components (Placeholders):**
        *   **Team List/Creation:** UI to show teams the user belongs to, button to navigate to a "Create Team" form.
        *   **Create Team Form:** Inputs for name, description. Submit calls `POST /teams`.
        *   **Team Settings/Dashboard:** View team details. If authorized, show forms/buttons to:
            *   Update team info (name, description) - calls `PUT /teams/{team}`.
            *   Manage members (list members, add member form, remove member button, update role dropdown/button) - interacts with `/teams/{team}/members` endpoints.
            *   Delete team (if owner) - calls `DELETE /teams/{team}`.
        *   **Team Switcher:** Dropdown or list displaying user's teams. Selecting a team calls `PUT /user/current-team` with the selected `team_id`. Update UI to reflect the current team context.

    *   **Verification:** Test CRUD operations for teams. Test adding, updating roles, and removing members. Verify authorization prevents unauthorized actions. Test switching teams and ensure the user's `current_team_id` updates and UI reflects the change.

5.  **Implement Middleware/Guards (Optional but recommended):**
    *   **Action:** Create middleware to check for team roles/permissions if needed for route groups.
        ~~~bash
        php artisan make:middleware EnsureUserHasTeamRole
        ~~~
    *   **File (`app/Http/Middleware/EnsureUserHasTeamRole.php` - Example):**
        ~~~php
        <?php
        namespace App\Http\Middleware;

        use Closure;
        use Illuminate\Http\Request;
        use Symfony\Component\HttpFoundation\Response;
        use Illuminate\Support\Facades\Auth;

        class EnsureUserHasTeamRole
        {
            public function handle(Request $request, Closure $next, string $role): Response
            {
                $user = Auth::user();
                $currentTeam = $user?->currentTeam; // Assumes currentTeam relationship is loaded

                if (!$user || !$currentTeam || !$user->hasTeamRole($currentTeam, $role)) {
                    // Redirect or abort if user doesn't have the required role in the current team
                    abort(403, "Unauthorized action. Required role: {$role}");
                }

                return $next($request);
            }
        }
        ~~~
    *   **Action:** Register middleware in `app/Http/Kernel.php`'s `$middlewareAliases`.
    *   **Usage:** `Route::middleware(['auth', 'team_role:Team Owner'])`
    *   **Concept:** Middleware intercepts requests to perform checks. This example checks if the logged-in user has a specific role within their currently selected team.
    *   **Rationale:** Provides a convenient way to protect route groups based on team roles without repeating checks in controllers.
    *   **Verification:** Apply middleware to a route. Access the route as a user with and without the required role in their current team. Verify access is granted/denied appropriately.

6.  **Commit Changes:**
    *   **Action:** Commit the completed Phase 3 work.
        ~~~bash
        git add .
        git commit -m "Phase 3: Implement Teams, Spatie Permissions (team-scoped), TeamService, Team/Member UI (3x), Policies"
        ~~~

---

### Phase 4: Real-time Foundation & Activity Logging (Est. 3-4 Days)

**Goal:** Set up Laravel Reverb and Echo, implement basic presence status tracking, and integrate `spatie/laravel-activitylog` via Listeners for key events.

**Steps:**

1.  **Configure Laravel Reverb:**
    *   **Action:** Install and configure Reverb. Ensure `.env` variables are set (Phase 0).
        ~~~bash
        php artisan reverb:install
        # Follow prompts - likely choose default app, install NPM deps if needed
        npm install # If not already run
        ~~~
    *   **Action:** Start the Reverb server.
        ~~~bash
        php artisan reverb:start --host=0.0.0.0 --port=8080 # Adjust host/port if needed
        ~~~
    *   **Concept:** Reverb is Laravel's WebSocket server. `reverb:install` sets up configuration and potentially installs `laravel-echo` and `pusher-js` frontend libraries. `reverb:start` runs the server process.
    *   **Rationale:** Provides the necessary backend infrastructure for real-time communication.
    *   **Verification:** Reverb server starts without errors. Check the Reverb dashboard (usually accessible via a route like `/laravel-reverb` if configured) or logs.

2.  **Configure Laravel Echo & Broadcasting:**
    *   **Action:** Ensure `config/broadcasting.php` is set up for Reverb and frontend assets include Echo.
    *   **File (`config/broadcasting.php` - Snippet):**
        ~~~php
        <?php
        return [
            'default' => env('BROADCAST_DRIVER', 'reverb'), // Set default driver
             'connections' => [
                 'reverb' => [ // Ensure Reverb connection is defined
                     'driver' => 'reverb',
                     'app_id' => env('REVERB_APP_ID'),
                     'key' => env('REVERB_APP_KEY'),
                     'secret' => env('REVERB_SECRET'),
                     'host' => env('REVERB_HOST'),
                     'port' => env('REVERB_PORT', 8080),
                     'scheme' => env('REVERB_SCHEME', 'http'),
                     'useTLS' => env('REVERB_SCHEME') === 'https', // Use TLS if scheme is https
                 ],
                // ... other connections like pusher, redis, log ...
                'log' => ['driver' => 'log'],
                'null' => ['driver' => 'null'],
            ],
        ];
        ~~~
    *   **Action:** Configure Echo in your main frontend JavaScript file. Breeze often does this.
    *   **File (`resources/js/bootstrap.js` - Example, check Breeze scaffold):**
        ~~~javascript
        import Echo from 'laravel-echo';
        import Pusher from 'pusher-js'; // Echo uses the Pusher protocol

        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'reverb', // Use 'reverb' broadcaster
            key: import.meta.env.VITE_REVERB_APP_KEY, // Use Vite env variables
            wsHost: import.meta.env.VITE_REVERB_HOST,
            wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
            wssPort: import.meta.env.VITE_REVERB_PORT ?? 443, // Usually same as wsPort for Reverb default setup
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
            enabledTransports: ['ws', 'wss'], // Enable WebSocket transports
            // cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // Not needed for Reverb usually
        });

        // Expose Echo instance globally or inject into framework (Vue/React)
        // Vue example: app.config.globalProperties.$echo = window.Echo;
        ~~~
    *   **Action:** Ensure Vite environment variables are set in `.env` and prefixed with `VITE_`.
    *   **File (`.env` - Add/Verify):**
        ~~~dotenv
        VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
        VITE_REVERB_HOST="${REVERB_HOST}"
        VITE_REVERB_PORT="${REVERB_PORT}"
        VITE_REVERB_SCHEME="${REVERB_SCHEME}"
        ~~~
    *   **Action:** Compile frontend assets.
        ~~~bash
        npm run dev # Or npm run build
        ~~~
    *   **Concept:** Laravel's broadcasting system allows sending server-side events over WebSockets. Echo is the JavaScript library used on the frontend to connect to the WebSocket server (Reverb) and listen on specific channels for broadcast events. `bootstrap.js` is typically where frontend libraries are initialized. Vite exposes `.env` variables prefixed with `VITE_` to the frontend code.
    *   **Rationale:** Connects the frontend to the backend real-time infrastructure.
    *   **Verification:** Open browser developer tools. On page load, check the Network tab for WebSocket connections. No console errors related to Echo/Pusher.

3.  **Implement Presence Status Tracking:**
    *   **Action:** Add presence status field (if not already done) and Enum. Create listeners for Login/Logout events to update status and broadcast changes.
    *   **File (`app/Enums/PresenceStatus.php`):**
        ~~~php
        <?php

        namespace App\Enums;

        enum PresenceStatus: string
        {
            case Online = 'online';
            case Offline = 'offline';
            case Away = 'away'; // Example additional status

            public function label(): string
            {
                return match($this) {
                    self::Online => __('Online'),
                    self::Offline => __('Offline'),
                    self::Away => __('Away'),
                };
            }
             public function color(): string // Example TailwindCSS / symbolic color
             {
                 return match($this) {
                     self::Online => 'green', // Conceptual color
                     self::Offline => 'gray',
                     self::Away => 'yellow',
                 };
             }
             public function indicatorClass(): string // Example specific CSS class
             {
                  return match($this) {
                      self::Online => 'bg-green-500',
                      self::Offline => 'bg-gray-400',
                      self::Away => 'bg-yellow-400',
                  };
             }
        }
        ~~~
    *   **Action:** Add migration if `presence_status` column wasn't added earlier.
        ~~~bash
        php artisan make:migration add_presence_status_to_users_table
        ~~~
    *   **File (`database/migrations/YYYY_MM_DD_HHMMSS_add_presence_status_to_users_table.php`):**
        ~~~php
         // In up() method:
         Schema::table('users', function (Blueprint $table) {
             if (!Schema::hasColumn('users', 'presence_status')) {
                $table->string('presence_status')->nullable()->index()->after('account_state')->default(\App\Enums\PresenceStatus::Offline->value);
             }
             if (!Schema::hasColumn('users', 'last_seen_at')) {
                $table->timestamp('last_seen_at')->nullable()->after('presence_status');
             }
         });
         // In down() method:
         Schema::table('users', function (Blueprint $table) {
              if (Schema::hasColumn('users', 'presence_status')) { $table->dropColumn('presence_status'); }
              if (Schema::hasColumn('users', 'last_seen_at')) { $table->dropColumn('last_seen_at'); }
         });
        ~~~
    *   **Action:** Run migration: `php artisan migrate`
    *   **Action:** Add cast to `User` model.
    *   **File (`app/Models/User.php` - Snippet in `$casts`):**
        ~~~php
         protected $casts = [
             // ... other casts
             'presence_status' => \App\Enums\PresenceStatus::class,
             'last_seen_at' => 'datetime',
             // ... state machine cast etc...
         ];
        ~~~
    *   **Action:** Create Event for presence change.
        ~~~bash
        php artisan make:event User/PresenceChanged
        ~~~
    *   **File (`app/Events/User/PresenceChanged.php`):**
        ~~~php
        <?php
        namespace App\Events\User;

        use App\Models\User;
        use App\Enums\PresenceStatus;
        use Illuminate\Broadcasting\Channel;
        use Illuminate\Broadcasting\InteractsWithSockets;
        use Illuminate\Broadcasting\PresenceChannel; // Use PresenceChannel for presence events
        use Illuminate\Broadcasting\PrivateChannel;
        use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // Implement ShouldBroadcast
        use Illuminate\Foundation\Events\Dispatchable;
        use Illuminate\Queue\SerializesModels;

        class PresenceChanged implements ShouldBroadcast // Make event broadcast directly (or use listener)
        {
            use Dispatchable, InteractsWithSockets, SerializesModels;

            public $queue = 'broadcasts'; // Send broadcasting to specific queue

            public User $user;
            public PresenceStatus $status;

            /**
             * Create a new event instance.
             * Only serialize necessary data for broadcast.
             */
            public function __construct(User $user, PresenceStatus $status)
            {
                 // Don't serialize the whole user model if not needed for broadcast
                 // $this->user = $user->only(['ulid', 'given_name', 'family_name']); // Example selective serialization
                 $this->user = $user; // Keep whole model if needed by listeners, customize broadcast payload below
                 $this->status = $status;
            }

            /**
             * Get the channels the event should broadcast on.
             * Broadcast on team-specific presence channels.
             *
             * @return array<int, \Illuminate\Broadcasting\Channel>
             */
            public function broadcastOn(): array
            {
                $channels = [];
                // Only broadcast presence for top-level teams the user belongs to
                foreach ($this->user->teams()->whereNull('parent_id')->get() as $team) {
                    // Use PresenceChannel for presence events
                    $channels[] = new PresenceChannel('presence-team.'.$team->ulid);
                }
                // Also broadcast on user's private channel if needed elsewhere
                // $channels[] = new PrivateChannel('user.'.$this->user->ulid);
                return $channels;
            }

            /**
             * The event's broadcast name.
             */
            public function broadcastAs(): string
            {
                return 'user.presence.changed'; // Custom event name for frontend
            }

            /**
             * Get the data to broadcast.
             * Send only necessary info.
             */
             public function broadcastWith(): array
             {
                 return [
                     'user_ulid' => $this->user->ulid,
                     'status' => $this->status->value, // Send enum value
                     'status_label' => $this->status->label(), // Send label
                     'status_color' => $this->status->color(), // Send color hint
                 ];
             }
        }

        ~~~
    *   **Action:** Create Listeners for Login/Logout.
        ~~~bash
        php artisan make:listener Listeners/User/UpdatePresenceOnLogin --event=\Illuminate\Auth\Events\Login
        php artisan make:listener Listeners/User/UpdatePresenceOnLogout --event=\Illuminate\Auth\Events\Logout
        ~~~
    *   **File (`app/Listeners/User/UpdatePresenceOnLogin.php`):**
        ~~~php
        <?php
        namespace App\Listeners\User;

        use Illuminate\Auth\Events\Login;
        use App\Enums\PresenceStatus;
        use App\Events\User\PresenceChanged; // Import the event to dispatch
        use Illuminate\Support\Facades\Log;

        class UpdatePresenceOnLogin
        {
            /**
             * Handle the event.
             */
            public function handle(Login $event): void
            {
                if ($event->user instanceof \App\Models\User) { // Type check just in case
                    try {
                        $user = $event->user;
                        $newStatus = PresenceStatus::Online;

                        // Only update and dispatch if status actually changed
                        if ($user->presence_status !== $newStatus || is_null($user->last_seen_at)) {
                             $user->forceFill([
                                 'presence_status' => $newStatus,
                                 'last_seen_at' => now(),
                             ])->saveQuietly(); // Use saveQuietly to avoid triggering 'updated' events if not desired here

                             // Dispatch event to broadcast the change
                             event(new PresenceChanged($user, $newStatus));
                             Log::info("User presence updated on login.", ['user_id' => $user->id, 'status' => $newStatus->value]);
                        }
                    } catch (\Throwable $e) {
                        Log::error("Failed to update presence on login.", ['user_id' => $event->user->id, 'exception' => $e]);
                    }
                }
            }
        }

        ~~~
    *   **File (`app/Listeners/User/UpdatePresenceOnLogout.php`):**
        ~~~php
        <?php
        namespace App\Listeners\User;

        use Illuminate\Auth\Events\Logout;
        use App\Enums\PresenceStatus;
        use App\Events\User\PresenceChanged;
        use Illuminate\Support\Facades\Log;

        class UpdatePresenceOnLogout
        {
             /**
              * Handle the event.
              */
             public function handle(Logout $event): void
             {
                 if ($event->user instanceof \App\Models\User) {
                     try {
                         $user = $event->user;
                         $newStatus = PresenceStatus::Offline;

                         if ($user->presence_status !== $newStatus) {
                              $user->forceFill([
                                  'presence_status' => $newStatus,
                                  // last_seen_at is already set from previous activity
                              ])->saveQuietly();

                              event(new PresenceChanged($user, $newStatus));
                              Log::info("User presence updated on logout.", ['user_id' => $user->id, 'status' => $newStatus->value]);
                         }
                     } catch (\Throwable $e) {
                         Log::error("Failed to update presence on logout.", ['user_id' => $event->user->id, 'exception' => $e]);
                     }
                 }
             }
        }

        ~~~
    *   **Action:** Ensure listeners are registered in `EventServiceProvider` (should be automatic if discovery is off and class names match convention, but check the `$listen` array).
    *   **Concept:** We add a `presence_status` field (using the Enum) to the `User` model. Listeners attached to Laravel's built-in `Login` and `Logout` auth events update this status. An event `PresenceChanged` is dispatched when the status changes, and this event itself implements `ShouldBroadcast` to send the update over Reverb. The event broadcasts on team-specific `PresenceChannel`s.
    *   **Rationale:** Implements basic online/offline tracking based on user authentication state, providing the foundation for real-time presence display. Broadcasting is handled efficiently via queued events.
    *   **Verification:** Log in and out. Check the `users` table `presence_status` and `last_seen_at` fields update. Check Reverb dashboard/logs or browser dev tools for the `user.presence.changed` event being broadcast on the relevant channels.

4.  **Configure Activity Logging via Listeners:**
    *   **Action:** Use listeners for domain events (e.g., `UserRegistered`, `AccountActivated`, `TeamCreated`) to log activity instead of relying solely on the `LogsActivity` trait's automatic logging for simple model changes.
    *   **Concept:** While the `LogsActivity` trait is useful for simple CRUD changes, logging significant domain events explicitly via Listeners provides more context and control over what gets logged and how.
    *   **Rationale:** Creates a more meaningful and context-rich audit trail focused on business events rather than just raw model attribute changes. Decouples logging logic from models/services.
    *   **Implementation Example (Modify existing listener):**
        *   **File (`app/Listeners/User/LogRegistrationActivity.php` - Already created in Phase 2):**
            *   The existing implementation using `activity()->performedOn($user)->log('User registered')` is already a good example of listener-based logging for a domain event.
        *   **Create listener for Team Creation:**
            ~~~bash
            php artisan make:listener Listeners/Team/LogTeamCreationActivity --queued --event=Team/TeamCreated
            ~~~
        *   **File (`app/Listeners/Team/LogTeamCreationActivity.php`):**
            ~~~php
            <?php
            namespace App\Listeners\Team;

            use App\Events\Team\TeamCreated; // Assuming this event exists
            use Illuminate\Contracts\Queue\ShouldQueue;
            use Illuminate\Queue\InteractsWithQueue;
            use Spatie\Activitylog\Facades\Activity;

            class LogTeamCreationActivity implements ShouldQueue
            {
                use InteractsWithQueue;
                public $queue = 'logging';

                public function handle(TeamCreated $event): void
                {
                    try {
                        activity()
                           ->performedOn($event->team) // Subject is the team
                           ->causedBy($event->creator) // User who triggered action
                           ->withProperties(['name' => $event->team->name]) // Add relevant context
                           ->log('Team created');
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::error('Failed to log team creation activity', ['team_id' => $event->team->id, 'exception' => $e]);
                    }
                }
            }
            ~~~
        *   **Action:** Register the listener in `EventServiceProvider`. Define the `TeamCreated` event if it doesn't exist.
    *   **Verification:** Trigger events (e.g., register user, create team). Check the `activity_log` table. Entries should have the correct subject (`performedOn`), actor (`causedBy`), description (`log`), and properties (`withProperties`).

5.  **Commit Changes:**
    *   **Action:** Commit the completed Phase 4 work.
        ~~~bash
        git add .
        git commit -m "Phase 4: Setup Reverb/Echo, Implement Presence Status (Enum, Events, Listeners, Broadcast), Activity Logging via Listeners"
        ~~~

---

### Phase 5: Advanced Features & Real-time Implementation (Est. 7-10 Days)

**Goal:** Implement Impersonation, Comments, User Settings, Search, and the full real-time Presence and Chat features (backend and frontend). Set up API authentication (Passport/Sanctum).

**Steps:**

1.  **Implement Impersonation (lab404/laravel-impersonate):**
    *   **Action:** Ensure Trait is on User model (Phase 1), configure permissions (`canImpersonate`, `canBeImpersonated` - Phase 1). Add UI elements.
    *   **Concept:** Allows authorized users (e.g., admins) to log in and operate as another user for debugging or support.
    *   **Rationale:** Useful admin tool as requested.
    *   **UI Implementation:**
        *   **User List (Admin):** Add an "Impersonate" button next to eligible users. This button should link to a route that triggers the impersonation.
        *   **Backend Route:** Define routes to start and stop impersonation.
            ~~~php
            // routes/web.php (or admin routes) - inside appropriate middleware
            Route::get('/users/{user}/impersonate', [UserImpersonationController::class, 'start'])->name('impersonate.start'); // Needs Controller
            Route::get('/users/impersonate/leave', [UserImpersonationController::class, 'leave'])->name('impersonate.leave'); // Needs Controller
            ~~~
        *   **Controller (`UserImpersonationController`):** Create this controller. Use `$manager->findUserById($userId)` and `$manager->impersonate($userToImpersonate)` to start. Use `$manager->leave()` to stop. Inject `Lab404\Impersonate\Services\ImpersonateManager` via constructor. Add authorization checks.
        *   **UI Indicator:** When impersonating, display a persistent banner (e.g., "You are impersonating [User Name]. [Leave Impersonation Link]"). The package often provides Blade directives (`@impersonating`, `@stopimpersonating`) or helpers (`auth()->user()->isImpersonated()`) for this. Adapt for your frontend stack (pass state from backend).

    *   **Verification:** As an admin, impersonate a user. Verify you see the site as that user and the indicator banner appears. Leave impersonation and verify you return to your original session.

2.  **Implement Comments (spatie/laravel-comments):**
    *   **Action:** Ensure Traits are on User model (Phase 1). Add UI for creating and displaying comments on relevant models (e.g., Tasks, Posts - assuming another model exists to comment on).
        ~~~bash
        # Example: Assume commenting on a hypothetical 'Post' model
        php artisan make:model Post -mf # Add migration, factory
        # Add HasUlid trait etc. to Post model
        # Add Spatie\Comments\Models\Concerns\InteractsWithComments trait to Post model
        ~~~
    *   **Concept:** Provides a pre-built commenting system. Users need `CanComment` trait, commentable models need `InteractsWithComments`.
    *   **Rationale:** Adds user interaction feature.
    *   **UI Implementation (Conceptual - on a Post show page):**
        *   **Display:** Fetch comments for the post (`$post->comments()->with('commentator')->latest()->get()`) and display them, showing commentator name, avatar, timestamp, content.
        *   **Create:** Form with a textarea. Submit POSTs to a controller action.
        *   **Backend:** Controller action validates input, uses `$user->comment($post, $validated['comment'])`. Package handles saving.
        *   **Real-time (Optional Enhancement):** Broadcast an event when a comment is created. Listen on the frontend to append new comments without page reload.

    *   **Verification:** Create a commentable model instance (e.g., a Post). View it, add a comment, verify it appears.

3.  **Implement User Settings (spatie/laravel-settings):**
    *   **Action:** Define `UserSettings` class, ensure Trait/Cast are on User model (Phase 1). Create UI.
    *   **File (`app/Settings/UserSettings.php`):**
        ~~~php
        <?php
        namespace App\Settings;

        use Spatie\LaravelSettings\Settings;

        class UserSettings extends Settings
        {
            // Define properties for user settings
            public string $locale; // e.g., 'en', 'es'
            public string $timezone; // e.g., 'UTC', 'America/New_York'
            public bool $enable_email_notifications;
            // Add other settings as needed

            // Define the settings group name (used for storage key/table name)
            public static function group(): string
            {
                // Use 'user' for general user settings, maybe scoped by user automatically? Check docs.
                // If needs per-user storage, package handles this when used with HasSettings trait on User model.
                // The trait likely scopes the settings group per model instance.
                return 'user';
            }

            // Optional: Define default values (might need custom loader or manual setting)
            // protected function setDefaultValues(): void { ... }
        }
        ~~~
    *   **Concept:** Stores structured settings data associated with a model (User) or globally.
    *   **Rationale:** Provides a clean way to manage user preferences.
    *   **UI Implementation (Profile/Settings Page):**
        *   Form with inputs/selects/checkboxes corresponding to the properties in `UserSettings` (e.g., Language dropdown, Timezone select, Notification toggle).
        *   Load current settings: `$user->settings->locale`, `$user->settings->timezone`, etc.
        *   Submit PUT/POST request to a controller action.
        *   **Backend:** Controller validates input, updates settings using `$user->settings->locale = $validated['locale']; ... $user->settings->save();`.

    *   **Verification:** View settings page, change values, save. Verify changes persist by reloading the page or checking the underlying storage (database table `settings` or JSON file, depending on config).

4.  **Implement Search (Laravel Scout + Typesense):**
    *   **Action:** Configure Scout driver, ensure `Searchable` trait is on `User` (and maybe `Team`) model, define `toSearchableArray`, set up Typesense schema, index data.
    *   **File (`config/scout.php` - Snippet):**
        ~~~php
        <?php
        return [
            'driver' => env('SCOUT_DRIVER', 'typesense'), // Default driver
            'prefix' => env('SCOUT_PREFIX', env('APP_NAME', 'laravel').'_'), // Index prefix
            'queue' => env('SCOUT_QUEUE', 'scout'), // Use specific queue
            'after_commit' => false,
            'chunk' => ['searchable' => 500, 'unsearchable' => 500],
            'soft_delete' => true, // Keep soft-deleted records in index? Check Typesense needs.
            'identify' => true, // Use scout key for identification
            'algolia' => [ ... ], // Keep or remove if not used
            'meilisearch' => [ ... ], // Keep or remove if not used
            'typesense' => [ // Typesense specific config
                'host' => env('TYPESENSE_HOST', 'localhost'),
                'port' => env('TYPESENSE_PORT', '8108'),
                'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
                'api_key' => env('TYPESENSE_API_KEY', ''),
                'prefix' => env('TYPESENSE_COLLECTION_PREFIX', 'ume_'), // Collection prefix
                 // Optional: Define collection schema directly here or manage externally
                 // 'collections' => [
                 //     'users' => [
                 //         'schema' => [
                 //              'fields' => [ ... ] // Define fields as per Typesense docs
                 //          ],
                 //         'searchable_fields' => ['full_name', 'email', 'slug'], // Fields to search
                 //         'facetable_fields' => ['account_state'], // Fields for filtering/faceting
                 //     ]
                 // ],
            ],
        ];
        ~~~
    *   **Action:** Add `toSearchableArray` to models.
    *   **File (`app/Models/User.php` - Add method):**
        ~~~php
        <?php
        namespace App\Models;

        use Laravel\Scout\Searchable; // Import Searchable trait

        class User extends Authenticatable /* ... */ {
            use Searchable; // Add Searchable trait
             // ... other traits and model code ...

             /**
              * Get the indexable data array for the model.
              *
              * @return array<string, mixed>
              */
             public function toSearchableArray(): array
             {
                 // Ensure related data needed for indexing is loaded if necessary
                 // $this->loadMissing('currentTeam');

                 // Return only the data you want indexed
                 return [
                     'objectID' => $this->ulid, // Use ULID as the unique object ID for Scout
                     'ulid' => $this->ulid,
                     'given_name' => $this->given_name,
                     'family_name' => $this->family_name,
                     'full_name' => $this->full_name, // Use accessor
                     'email' => $this->email,
                     'slug' => $this->slug,
                     'account_state' => $this->account_state->value, // Index the state value
                     // 'team_name' => $this->currentTeam?->name, // Example related data
                     'created_at_timestamp' => $this->created_at->timestamp, // Index timestamp for sorting
                 ];
             }

              /**
               * Get the Scout key for the model.
               * Overrides default to use ULID.
               */
              public function getScoutKey(): mixed
              {
                  return $this->ulid;
              }

              /**
               * Get the key name for the Scout search index.
               * Overrides default to use ULID.
               */
              public function getScoutKeyName(): mixed
              {
                  return 'ulid';
              }
        }
        ~~~
    *   **Action:** Configure Typesense server and schema (can be done manually via Typesense API/UI or potentially automated). Ensure API key is set in `.env`.
    *   **Action:** Import initial data into Typesense.
        ~~~bash
        php artisan scout:import "App\Models\User"
        php artisan scout:import "App\Models\Team" # If Team is searchable
        ~~~
    *   **Action:** Set up queue worker for `scout` queue.
        ~~~bash
        php artisan horizon # Or queue:work --queue=scout
        ~~~
    *   **Concept:** Scout provides an abstraction layer for search. Typesense is the chosen search engine. The `Searchable` trait automatically syncs model changes (create, update, delete) to the search index (usually via queues). `toSearchableArray` defines *what* data gets indexed.
    *   **Rationale:** Provides fast, typo-tolerant full-text search for users (and potentially teams). Offloads search queries from the primary database.
    *   **UI Implementation:**
        *   Add a search input field (e.g., in the header or a dedicated search page).
        *   As the user types (debounce recommended), send search queries to a backend endpoint.
        *   **Backend:** Controller action receives the search term, uses `User::search($term)->query(fn ($q) => $q->select('ulid', 'given_name', 'family_name', 'email', 'slug'))->paginate(10)` (or similar Scout query), and returns results. Add filtering/sorting as needed.
        *   **Frontend:** Display results dynamically.

    *   **Verification:** Import data using `scout:import`. Run the queue worker. Search for users via the UI, verify relevant results are returned quickly. Create/update/delete a user and verify the search index updates automatically (after queue job processes).

5.  **Implement Real-time Presence (Frontend):**
    *   **Action:** Use Echo to join the `presence-team.{teamId}` channel and display presence information for users within **top-level teams**.
    *   **Concept:** Presence Channels in Echo/Pusher protocol automatically provide information about who is currently subscribed (`here`, `joining`, `leaving` events).
    *   **Rationale:** Provides the real-time presence display required by the PRD, restricted to top-level teams.
    *   **Backend Channel Authorization:**
        *   **File (`routes/channels.php`):**
            ~~~php
            <?php

            use Illuminate\Support\Facades\Broadcast;
            use App\Models\Team; // Import Team

            /*
            |--------------------------------------------------------------------------
            | Broadcast Channels
            |--------------------------------------------------------------------------
            | Here you may register all the event broadcasting channels that your
            | application supports. The given channel authorization callbacks are
            | used to check if an authenticated user can listen to the channel.
            */

            // Private channel example (replace 'App.Models.User.{id}' with your specific needs)
            // Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
            //     return (int) $user->id === (int) $id;
            // });

            // --- Presence Channel for Top-Level Teams ---
            // Channel name format: presence-team.{teamUlid}
            Broadcast::channel('presence-team.{teamUlid}', function ($user, $teamUlid) {
                // Find the team by ULID
                 $team = Team::where('ulid', $teamUlid)->first();

                 // 1. Check if team exists
                 // 2. Check if team is top-level (no parent)
                 // 3. Check if the authenticated user is a member of this team
                 if ($team && $team->isTopLevel() && $user->belongsToTeam($team)) {
                     // If authorized, return user data needed by presence channel subscribers
                     return [
                         'ulid' => $user->ulid,
                         'name' => $user->full_name, // Use accessor
                         'avatar_url' => $user->avatar_url, // Use accessor
                         'initials' => $user->initials, // Use accessor
                         // Add current presence status if available/needed immediately on join
                         'presence_status' => $user->presence_status?->value ?? 'offline',
                     ];
                 }

                 // Deny authorization if any check fails
                 return false;
            }, ['guards' => ['web', 'api']]); // Specify guards if needed

            // --- Private Channel for Team Chat ---
            // Channel name format: chat.team.{teamUlid}
             Broadcast::channel('chat.team.{teamUlid}', function ($user, $teamUlid) {
                 $team = Team::where('ulid', $teamUlid)->first();
                 // Authorize only if team exists, is top-level, and user is a member
                 return ($team && $team->isTopLevel() && $user->belongsToTeam($team))
                     ? true // Allow subscription (no user data needed here typically)
                     : false;
             }, ['guards' => ['web', 'api']]);

            // --- Private Channel for User Specific Notifications ---
            // Channel name format: user.{userUlid}
            Broadcast::channel('user.{userUlid}', function ($user, $userUlid) {
                 // Authorize if the authenticated user's ULID matches the channel ULID
                 return $user->ulid === $userUlid;
            }, ['guards' => ['web', 'api']]);

            ~~~
    *   **UI Implementation (Conceptual - e.g., in a Team Member List component within a top-level team view):**
        *   **JavaScript (React/Vue/Alpine in Volt):**
            *   Get the current top-level team's ULID (`team.ulid`).
            *   Check if Echo is initialized.
            *   Join the presence channel: `Echo.join(\`presence-team.${team.ulid}\`)`.
            *   `.here((users) => { ... })`: Called when initially joining. `users` is an array of currently present users (with data returned by auth callback). Populate initial presence status display.
            *   `.joining((user) => { ... })`: Called when a *new* user joins the channel. Update that user's status to 'online'.
            *   `.leaving((user) => { ... })`: Called when a user leaves the channel. Update that user's status to 'offline'.
            *   `.listen('.user.presence.changed', (event) => { ... })`: Listen for our custom broadcast event (from Phase 4 `PresenceChanged` event). Update the specific user's status based on `event.status`.
            *   Store presence state locally (e.g., React state, Vue reactive object, Alpine data).
            *   Leave the channel when the component unmounts or the user navigates away: `Echo.leave(\`presence-team.${team.ulid}\`)`.
        *   **HTML/Template:**
            *   In the user list, display a presence indicator next to each user's name/avatar.
            *   Bind the indicator's style (color, shape - use `indicatorClass` from Enum) or displayed status text to the presence state stored in the JavaScript.
            *   **Accessibility:** Use ARIA attributes (`aria-label="Status: Online"`) or visually hidden text alongside the visual indicator. Don't rely solely on color.

    *   **Verification:** Open the app in two browsers, log in as different users belonging to the *same top-level team*. Navigate to a view within that team showing the member list. Verify both users appear online. Log out one user, verify their status changes to offline in the other browser. Log back in, verify status becomes online again. Test joining/leaving events. Ensure users in *different* top-level teams or sub-teams do *not* see each other's presence.

6.  **Implement Real-time Chat (Backend & Frontend):**
    *   **Action:** Create `ChatMessage` model/migration, `ChatMessageService`, API endpoints, Event/Listener for broadcasting, and frontend UI for **top-level teams**.
    *   **Step 6a: `ChatMessage` Model & Migration:**
        ~~~bash
        php artisan make:model ChatMessage -m
        ~~~
        *   **File (`database/migrations/YYYY_MM_DD_HHMMSS_create_chat_messages_table.php`):**
            ~~~php
            <?php // ... imports
            return new class extends Migration {
                public function up(): void {
                    Schema::create('chat_messages', function (Blueprint $table) {
                        $table->id();
                        $table->ulid('ulid')->unique(); // Mandatory Public ID
                        $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete(); // Link to TOP-LEVEL team
                        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Sender
                        $table->text('content');
                        $table->timestamps();

                        $table->index(['team_id', 'created_at']); // Index for fetching history
                    });
                }
                public function down(): void { Schema::dropIfExists('chat_messages'); }
            };
            ~~~
        *   **File (`app/Models/ChatMessage.php`):**
            ~~~php
            <?php
            namespace App\Models;

            use App\Models\Traits\HasUlid;
            use Illuminate\Database\Eloquent\Factories\HasFactory;
            use Illuminate\Database\Eloquent\Model;
            use Illuminate\Database\Eloquent\Relations\BelongsTo;

            class ChatMessage extends Model
            {
                use HasFactory, HasUlid;

                protected $fillable = ['team_id', 'user_id', 'content'];

                protected $casts = [
                    // 'created_at' => 'datetime:Y-m-d H:i:s', // Customize format if needed
                ];

                 protected $touches = ['team']; // Optional: Update team timestamp when message added

                 // Append sender info for convenience in API responses
                 protected $appends = ['sender'];

                 public function getSenderAttribute(): ?array
                 {
                     // Eager load 'user' relation before accessing this if needed in loops
                     if (!$this->relationLoaded('user')) {
                          // Avoid N+1 in serialization if relation not loaded
                          return null;
                     }
                     return $this->user ? [
                          'ulid' => $this->user->ulid,
                          'name' => $this->user->full_name,
                          'avatar_url' => $this->user->avatar_url,
                          'initials' => $this->user->initials,
                     ] : null;
                 }


                public function team(): BelongsTo { return $this->belongsTo(Team::class); }
                public function user(): BelongsTo { return $this->belongsTo(User::class); }
            }
            ~~~
        *   **Action:** Run migration: `php artisan migrate`.
    *   **Step 6b: `ChatMessageService` & API:**
        ~~~bash
        php artisan make:service ChatMessageService
        php artisan make:controller Api/TeamChatController # API Controller
        php artisan make:event Chat/ChatMessageSent
        php artisan make:listener Listeners/Chat/BroadcastChatMessage --queued --event=Chat/ChatMessageSent
        php artisan make:resource ChatMessageResource
        ~~~
        *   **File (`app/Services/ChatMessageService.php`):**
            ~~~php
            <?php
            namespace App\Services;

            use App\Models\Team;
            use App\Models\User;
            use App\Models\ChatMessage;
            use App\Events\Chat\ChatMessageSent; // Import event
            use Illuminate\Support\Facades\Log;
            use Illuminate\Contracts\Pagination\LengthAwarePaginator;

            class ChatMessageService extends BaseService
            {
                /**
                 * Send a chat message within a team.
                 *
                 * @param User $sender
                 * @param Team $team Must be a top-level team
                 * @param string $content
                 * @return ChatMessage
                 * @throws \Exception If team is not top-level or save fails
                 */
                public function sendMessage(User $sender, Team $team, string $content): ChatMessage
                {
                     // **Critical Check: Ensure team is top-level**
                     if (!$team->isTopLevel()) {
                         throw new \InvalidArgumentException('Chat is only allowed in top-level teams.');
                     }

                     // Authorization check (ensure user is member) - can also be done in controller/policy
                     if (!$sender->belongsToTeam($team)) {
                          throw new \Illuminate\Auth\Access\AuthorizationException('User does not belong to this team.');
                     }

                     $this->logInfo('Attempting to send chat message.', ['team_id' => $team->id, 'user_id' => $sender->id]);

                     try {
                         $message = ChatMessage::create([
                             'team_id' => $team->id,
                             'user_id' => $sender->id,
                             'content' => trim($content), // Trim whitespace
                         ]);

                         // Load relation for event listener/broadcast payload
                         $message->load('user');

                         // Dispatch event to trigger broadcast listener
                         event(new ChatMessageSent($message));

                         $this->logInfo('Chat message sent successfully.', ['message_ulid' => $message->ulid]);
                         return $message;

                     } catch (\Throwable $e) {
                         $this->logError('Failed to send chat message.', ['team_id' => $team->id, 'user_id' => $sender->id], $e);
                         throw new \RuntimeException('Failed to send chat message.', 0, $e);
                     }
                }

                /**
                 * Get chat history for a team (paginated).
                 *
                 * @param Team $team Must be a top-level team
                 * @param User $requester User requesting history (for auth check)
                 * @param int $perPage
                 * @return LengthAwarePaginator
                 */
                public function getMessages(Team $team, User $requester, int $perPage = 50): LengthAwarePaginator
                {
                     if (!$team->isTopLevel()) {
                         throw new \InvalidArgumentException('Chat is only allowed in top-level teams.');
                     }
                     if (!$requester->belongsToTeam($team)) {
                          throw new \Illuminate\Auth\Access\AuthorizationException('User does not belong to this team.');
                     }

                     return ChatMessage::where('team_id', $team->id)
                                       ->with('user:id,ulid,given_name,family_name') // Eager load only necessary sender fields
                                       ->latest() // Order by newest first
                                       ->paginate($perPage);
                }
            }
            ~~~
        *   **File (`app/Events/Chat/ChatMessageSent.php`):**
            ~~~php
             <?php
             namespace App\Events\Chat;
             // ... imports ...
             use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
             use Illuminate\Broadcasting\PrivateChannel;
             use App\Models\ChatMessage;

             class ChatMessageSent implements ShouldBroadcast
             {
                 use Dispatchable, InteractsWithSockets, SerializesModels;

                 public $queue = 'broadcasts'; // Use broadcast queue
                 public ChatMessage $message;

                 public function __construct(ChatMessage $message) { $this->message = $message; }

                 public function broadcastOn(): array {
                     // Broadcast only on the specific top-level team's private chat channel
                     if ($this->message->team->isTopLevel()) {
                          return [new PrivateChannel('chat.team.' . $this->message->team->ulid)];
                     }
                     return []; // Don't broadcast if not top-level (shouldn't happen due to service check)
                 }

                 public function broadcastAs(): string { return 'chat.message.new'; }

                 // Customize broadcast data - use the resource?
                 public function broadcastWith(): array {
                      // Load sender info if not already loaded by service
                      $this->message->loadMissing('user:id,ulid,given_name,family_name');
                      return (new \App\Http\Resources\ChatMessageResource($this->message))->resolve();
                      // Or manually:
                      // return [
                      //     'ulid' => $this->message->ulid,
                      //     'content' => $this->message->content,
                      //     'created_at_human' => $this->message->created_at->diffForHumans(),
                      //     'created_at' => $this->message->created_at->toIso8601String(),
                      //     'sender' => $this->message->user ? [ // Access relationship directly
                      //          'ulid' => $this->message->user->ulid,
                      //          'name' => $this->message->user->full_name,
                      //          'avatar_url' => $this->message->user->avatar_url,
                      //      ] : null,
                      // ];
                 }
             }
            ~~~
        *   **File (`app/Listeners/Chat/BroadcastChatMessage.php`):**
            *   *No longer needed!* The `ChatMessageSent` event now implements `ShouldBroadcast` directly. This simplifies the flow. If complex logic was needed before broadcasting, a listener would be used. Delete this listener file if created.
        *   **File (`app/Http/Resources/ChatMessageResource.php`):**
             ~~~php
             <?php
             namespace App\Http\Resources;

             use Illuminate\Http\Request;
             use Illuminate\Http\Resources\Json\JsonResource;

             class ChatMessageResource extends JsonResource
             {
                 /**
                  * Transform the resource into an array.
                  *
                  * @return array<string, mixed>
                  */
                 public function toArray(Request $request): array
                 {
                     return [
                         'ulid' => $this->ulid,
                         'content' => $this->content,
                         'created_at' => $this->created_at, //->toDateTimeString(), // Or format as needed
                         'created_at_human' => $this->created_at->diffForHumans(),
                         // Use the appended 'sender' attribute from the model
                         'sender' => $this->sender,
                         // Or load relationship conditionally:
                         // 'sender' => new UserBasicResource($this->whenLoaded('user')),
                     ];
                 }
             }
             ~~~
        *   **File (`routes/api.php` - For external API access maybe, or internal SPA calls):**
            ~~~php
            use App\Http\Controllers\Api\TeamChatController;

            Route::middleware(['auth:sanctum', 'EnsureTeamContextIsTopLevel']) // Use Sanctum/Passport + custom middleware
                 ->prefix('teams/{team:ulid}/chat') // Scope to team ULID
                 ->name('api.teams.chat.')
                 ->group(function () {
                     Route::get('messages', [TeamChatController::class, 'index'])->name('messages.index');
                     Route::post('messages', [TeamChatController::class, 'store'])->name('messages.store');
                 });
            ~~~
            *   **Note:** You might need a custom middleware `EnsureTeamContextIsTopLevel` that checks `$request->route('team')` and aborts if it's not a top-level team or user isn't a member. Authorization should also be checked (e.g., via policy within controller).
        *   **File (`app/Http/Controllers/Api/TeamChatController.php`):**
            ~~~php
             <?php
             namespace App\Http\Controllers\Api; // Ensure correct namespace

             use App\Http\Controllers\Controller;
             use App\Models\Team;
             use Illuminate\Http\Request;
             use App\Services\ChatMessageService; // Import service
             use App\Http\Resources\ChatMessageResource; // Import resource
             use Illuminate\Http\JsonResponse;
             use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
             use Illuminate\Support\Facades\Auth; // Use Auth facade

             class TeamChatController extends Controller
             {
                 public function __construct(protected ChatMessageService $chatMessageService)
                 {
                     // Apply authorization middleware or check policies here/in methods
                     // $this->authorizeResource(Team::class, 'team'); // Example policy check if applicable
                 }

                 /**
                  * Display paginated chat messages for the team.
                  */
                 public function index(Request $request, Team $team): AnonymousResourceCollection
                 {
                     // Authorization: Ensure user can view chat (done by service/middleware)
                     $this->authorize('viewChat', $team); // Assuming a 'viewChat' policy method

                     $messages = $this->chatMessageService->getMessages($team, $request->user());
                     return ChatMessageResource::collection($messages);
                 }

                 /**
                  * Store a newly created chat message.
                  */
                 public function store(Request $request, Team $team): JsonResponse
                 {
                      // Authorization: Ensure user can send chat (done by service/middleware)
                      $this->authorize('createChatMessage', $team); // Assuming a 'createChatMessage' policy method

                      $validated = $request->validate([
                          'content' => ['required', 'string', 'max:2000'],
                      ]);

                      try {
                           $message = $this->chatMessageService->sendMessage(
                               $request->user(), // Get authenticated user
                               $team,
                               $validated['content']
                           );

                           return ChatMessageResource::make($message->loadMissing('user')) // Ensure sender is loaded
                                  ->response()
                                  ->setStatusCode(201); // Return 201 Created

                      } catch (\InvalidArgumentException | \Illuminate\Auth\Access\AuthorizationException $e) {
                           return response()->json(['error' => $e->getMessage()], 403); // Forbidden
                      } catch (\Throwable $e) {
                           report($e); // Report unexpected errors
                           return response()->json(['error' => 'Failed to send message.'], 500);
                      }
                 }
             }
            ~~~
    *   **Step 6c: Frontend Chat UI Component (Conceptual):**
        *   **Location:** Displayed only when viewing a **top-level team**.
        *   **State:** Maintain list of messages, current input value, loading state.
        *   **On Load/Mount:**
            *   Fetch initial message history from `GET /api/teams/{team}/chat/messages` (use API token/Sanctum). Handle pagination (load more on scroll up).
            *   Join the private channel: `Echo.private(\`chat.team.${team.ulid}\`)`.
            *   Listen for new messages: `.listen('.chat.message.new', (message) => { ... })`. Append the received `message` (which is the `ChatMessageResource` data) to the local message list. Scroll to bottom.
        *   **Display:** Render the list of messages, showing sender avatar/name, content, timestamp.
        *   **Input:** Textarea and Send button. On submit:
            *   Call `POST /api/teams/{team}/chat/messages` with the content.
            *   Clear input on success. Handle errors. (The broadcast listener will add the message to the sender's own UI as well).
        *   **On Unmount/Leave:** `Echo.leaveChannel(\`chat.team.${team.ulid}\`)`.

    *   **Verification:** Navigate to a top-level team view. Verify chat history loads. Send a message, verify it appears instantly for you and other users viewing the same team chat (via WebSocket broadcast). Verify messages are saved to the database. Test pagination. Ensure chat is not accessible/functional in sub-teams.

7.  **Configure API Authentication (Passport & Sanctum):**
    *   **Action:** Install Passport, run migrations (done in Phase 0/1), configure Auth guards.
    *   **File (`config/auth.php` - Snippet):**
        ~~~php
        <?php
        return [
            'defaults' => [
                'guard' => 'web', // Default guard for web UI (uses sessions)
                'passwords' => 'users',
            ],
            'guards' => [
                'web' => [
                    'driver' => 'session',
                    'provider' => 'users',
                ],
                'sanctum' => [ // Guard for SPA authentication (first-party)
                     'driver' => 'sanctum',
                     'provider' => 'users', // Use the default user provider
                ],
                'api' => [ // Guard for external/token-based API access
                    'driver' => 'passport', // Use Passport driver
                    'provider' => 'users',
                     // 'hash' => false, // Set to true if using API tokens (Passport personal access) without hashing
                ],
            ],
            'providers' => [
                'users' => [
                    'driver' => 'eloquent',
                    'model' => App\Models\User::class,
                ],
            ],
            // ... password reset config ...
        ];
        ~~~
    *   **Action:** Add `Passport::routes()` to `AuthServiceProvider` (or create dedicated `PassportServiceProvider`).
    *   **File (`app/Providers/AuthServiceProvider.php` - Snippet in `boot()`):**
        ~~~php
         public function boot(): void
         {
             $this->registerPolicies();

             // Passport Routes (for issuing tokens etc.) - add appropriate middleware (web, auth?) if needed
             // Only register routes if not skipping migrations or routes aren't cached
             if (! $this->app->routesAreCached() && !$this->app->configurationIsCached()) {
                  Passport::routes(null, ['prefix' => 'api/oauth']); // Prefix Passport routes under /api/oauth
                  // Passport::tokensExpireIn(now()->addDays(15));
                  // Passport::refreshTokensExpireIn(now()->addDays(30));
                  // Passport::personalAccessTokensExpireIn(now()->addMonths(6));
             }

              // Define Passport Scopes (optional)
              // Passport::tokensCan([
              //     'read-profile' => 'Read user profile information',
              //     'manage-teams' => 'Create, update, and delete teams',
              // ]);
              // Passport::setDefaultScope(['read-profile']);
         }
        ~~~
    *   **Action:** Apply `auth:sanctum` middleware to routes used by your SPA (Inertia). Apply `auth:api` middleware to routes intended for external API consumers using Passport tokens. (`routes/api.php` example already uses `auth:sanctum`).
    *   **Concept:** Laravel allows multiple authentication guards. `web` uses sessions (for traditional web/Livewire). `sanctum` uses cookies for SPAs and simple API tokens. `passport` implements OAuth2 for robust API authentication.
    *   **Rationale:** Provides secure and appropriate authentication methods for both the first-party SPA (Inertia using Sanctum) and potential third-party API consumers (using Passport).
    *   **Verification:** SPA routes continue to work using session/cookie auth via Sanctum. Protect an API endpoint with `auth:api`. Try accessing it without a valid Passport token (expect 401). Issue a Passport token (e.g., Personal Access Token via UI or code) and use it in the `Authorization: Bearer <token>` header to successfully access the endpoint.

8.  **Commit Changes:**
    *   **Action:** Commit the completed Phase 5 work.
        ~~~bash
        git add .
        git commit -m "Phase 5: Implement Impersonation, Comments, Settings, Search (Scout/Typesense), Real-time Presence/Chat UI (3x), API Auth (Passport/Sanctum)"
        ~~~

---

### Phase 6: Polish, Testing & Deployment Prep (Est. 5-7 Days)

**Goal:** Implement Internationalization, Feature Flags, add comprehensive tests, perform basic performance tuning, documentation, and prepare for deployment.

**Steps:**

1.  **Implement Internationalization (i18n):**
    *   **Action:** Use `spatie/laravel-translation-loader` and Laravel's built-in features.
    *   **Concept:** Allows the application UI to be displayed in multiple languages. Uses translation files (`lang/en/messages.php`, `lang/es/messages.php`) or database storage (via `laravel-translation-loader`) and the `__()` helper function or `@lang()` Blade directive. `spatie/laravel-translatable` is used for translating *model attributes* if needed (e.g., Team description).
    *   **Rationale:** Supports a diverse user base as requested.
    *   **Implementation:**
        *   **Configure `laravel-translation-loader`:** Publish config/migrations if using database driver.
        *   **Create Language Files:** Create directories (`lang/en`, `lang/es`, etc.) and translation files (e.g., `lang/en/messages.php`).
            ~~~php
            // lang/en/messages.php
            return ['welcome' => 'Welcome, :name!', 'profile' => 'Profile'];
            // lang/es/messages.php
            return ['welcome' => '¡Bienvenido, :name!', 'profile' => 'Perfil'];
            ~~~
        *   **Use Translations in UI:**
            *   **Blade/Volt:** `{{ __('messages.welcome', ['name' => $userName]) }}` or `@lang('messages.profile')`
            *   **Inertia (React/Vue):** Pass translations object from backend controller (`'translations' => __('messages')`) or use a library like `ziggy` with translation support, or `inertia-laravel-translator`. Use the passed object/helper in components: `t('welcome', { name: user.name })`.
        *   **Locale Switching:** Add UI (e.g., dropdown in settings or header). Create a route/controller to update the user's locale preference (saved via `spatie/laravel-settings`). Create middleware (`SetLocale`) that checks the user's setting (or session/URL) and calls `App::setLocale($locale)`. Apply this middleware globally or to relevant route groups.
        *   **Model Attribute Translation:** If needed (e.g., Team description), add `HasTranslations` trait to `Team` model, cast the attribute (`'description' => 'array'`), and update forms to provide inputs for each supported language.

    *   **Verification:** Switch locale via UI. Verify text changes accordingly across different components/pages. Verify translated model attributes display correctly.

2.  **Implement Feature Flags (Laravel Pennant):**
    *   **Action:** Define features, add migration, use flags to control access to new features (e.g., Chat, new Profile section).
        ~~~bash
        php artisan pennant:install # Creates migration
        php artisan migrate
        ~~~
    *   **Action:** Define features in a service provider (e.g., `AppServiceProvider` or `PennantServiceProvider`).
    *   **File (`app/Providers/AppServiceProvider.php` - `boot()` method):**
        ~~~php
        use Laravel\Pennant\Feature;
        use App\Models\User; // If scoping to user attributes

        public function boot(): void {
            // ... other boot logic

            // Define Features
            Feature::define('realtime-chat', fn (User $user) => match (true) {
                 // Enable for specific users, teams, or based on subscription?
                 // $user->isOnTeam($specialTeam) => true,
                 // $user->isSubscribed('premium') => true,
                 $user->email === 'admin@example.com' => true, // Example: Only for admin
                 default => false // Disabled by default
            });

            Feature::define('new-profile-layout', fn (?User $user) => true); // Example: Enabled for everyone

            // Define using simple boolean or other lottery logic
            // Feature::define('beta-feature', true);
            // Feature::define('dark-mode', fn () => Lottery::odds(1, 10)); // 10% chance
        }
        ~~~
    *   **Concept:** Pennant allows defining flags whose state can depend on the current user, session, or other factors. Features can be checked in backend code or frontend components.
    *   **Rationale:** Enables gradual rollout and A/B testing of new UME features.
    *   **Implementation:**
        *   **Backend:** Use `Feature::active('realtime-chat')` in controllers, policies, or services to conditionally enable logic.
        *   **Frontend:** Pass active features to Inertia views or check within Livewire components: `if (Feature::active('realtime-chat')) { ... }`. Conditionally render UI elements (e.g., Chat sidebar item, Presence indicators).

    *   **Verification:** Check feature state for different users using `php artisan pennant:check <feature-name> --user=1`. Verify UI elements are shown/hidden correctly based on the active features for the logged-in user.

3.  **Write Comprehensive Tests (PestPHP / PHPUnit / Dusk):**
    *   **Action:** Create Feature tests for API endpoints, auth flows, team management, state transitions. Create Unit tests for Services, complex logic in Models/Traits, Enums, State classes. Create Dusk browser tests for critical UI flows (Login+2FA, Profile Update, Team Creation, Chat Send/Receive).
    *   **Concept:** Testing ensures code correctness, prevents regressions, and validates requirements. Feature tests simulate HTTP requests. Unit tests isolate and test specific classes/methods. Dusk tests interact with the application through a real browser.
    *   **Rationale:** Critical for maintaining a complex application and ensuring stability.
    *   **Examples:**
        *   **Feature Test (`tests/Feature/Auth/RegistrationTest.php`):** Assert user is created in `PendingValidation` state, event is dispatched.
        *   **Feature Test (`tests/Feature/Profile/AvatarUploadTest.php`):** Simulate file upload, assert media is added, assert redirect/response.
        *   **Feature Test (`tests/Feature/Teams/TeamManagementTest.php`):** Test creating team, adding member, checking permissions via API/web routes.
        *   **Unit Test (`tests/Unit/Services/TeamServiceTest.php`):** Mock models/events, call service methods, assert correct interactions and return values.
        *   **Unit Test (`tests/Unit/Models/UserTest.php`):** Test accessors (`fullName`, `initials`), state transitions (`$user->canTransitionTo(...)`), relationships.
        *   **Dusk Test (`tests/Browser/LoginTest.php`):** Automate browser login, including 2FA challenge if enabled.
        *   **Dusk Test (`tests/Browser/ChatTest.php`):** Log in two users, navigate to chat, send message as one, assert message appears for both.

    *   **Verification:** Run tests: `php artisan test`. Run Dusk tests: `php artisan dusk`. Ensure all tests pass. Aim for good code coverage.

4.  **Performance Considerations & Tuning:**
    *   **Action:** Review database queries (use Telescope, Debugbar), ensure proper indexing, implement caching where appropriate (e.g., permissions, settings, team member lists), optimize background jobs (Horizon dashboard). Consider Laravel Octane for production.
    *   **Concept:** Optimizing database queries, leveraging caching, and efficiently processing background jobs are key to application performance. Octane uses tools like Swoole or RoadRunner to keep the application booted in memory, significantly speeding up request handling.
    *   **Rationale:** Ensures the application remains responsive under load.
    *   **Implementation:**
        *   **Query Optimization:** Use `->with([...])` for eager loading relationships to prevent N+1 query problems. Analyze slow queries identified by Pulse/Telescope. Add database indexes for frequently queried columns (ULIDs, foreign keys, state fields, timestamps).
        *   **Caching:** Use `Cache::remember('key', $ttl, fn() => ...)` for expensive computations or frequently accessed data that doesn't change often (e.g., user permissions - Spatie Permission has built-in caching).
        *   **Queues:** Monitor Horizon dashboard (`/horizon`). Adjust number of workers, balance queues (`emails`, `broadcasts`, `logging`, `scout`, `default`) based on load. Ensure jobs are processed efficiently.
        *   **Octane:** Install (`composer require laravel/octane`) and configure if using Swoole/RoadRunner. Run via `php artisan octane:start`. Requires specific server setup.

    *   **Verification:** Monitor performance using Pulse, Telescope, Horizon, and browser developer tools. Perform load testing if possible.

5.  **Documentation:**
    *   **Action:** Update README with setup instructions, architecture overview, key features. Add PHPDoc blocks to classes/methods. Generate API documentation if needed (e.g., using Scribe). Document Service layer interactions, State Machine flows, and Event/Listener relationships. Document frontend component props and interactions.
    *   **Concept:** Good documentation is essential for maintainability and onboarding new developers.
    *   **Rationale:** Fulfills project requirements and improves long-term maintainability.

6.  **Data Backup Strategy:**
    *   **Action:** Configure and schedule `spatie/laravel-backup`.
    *   **Concept:** Regularly backs up application database and specified files to one or more storage disks (local, S3, etc.).
    *   **Rationale:** Critical for disaster recovery.
    *   **Implementation:** Publish config (`backup.php`). Configure source (database, files) and destination disks (e.g., `s3`). Schedule `php artisan backup:run` and `php artisan backup:clean` commands via Laravel's scheduler (`app/Console/Kernel.php`) or system cron. Test backup creation and restoration.

7.  **Deployment Preparation:**
    *   **Action:** Prepare deployment scripts/process (e.g., using Laravel Forge, Envoyer, Ploi, or manual scripts). Ensure environment variables are set correctly in production (`.env`). Optimize Laravel for production.
    *   **Concept:** Deploying involves transferring code, running migrations/seeds, installing dependencies, clearing caches, and configuring the web server.
    *   **Rationale:** Ensures a smooth transition to the live environment.
    *   **Implementation:**
        *   **Environment (`.env`):** Set `APP_ENV=production`, `APP_DEBUG=false`. Configure production database, Redis, Reverb, Mail, Typesense credentials. Generate a strong `APP_KEY`.
        *   **Optimization Commands:** Run these during deployment:
            ~~~bash
            composer install --optimize-autoloader --no-dev
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
            php artisan event:cache # If using event discovery (we aren't, but good practice)
            php artisan optimize # Combines config/route cache potentially
            npm ci # Use npm ci for consistent installs
            npm run build # Compile frontend assets for production
            php artisan migrate --force # Run migrations (use --force in production)
            php artisan pulse:check # Check pulse migrations
            php artisan storage:link # Create symbolic link
            # Restart queue workers (e.g., via Forge/Envoyer or supervisorctl restart horizon:*)
            # Restart Reverb server
            # Restart Octane server if used (php artisan octane:reload)
            ~~~
        *   **Web Server:** Configure Nginx/Apache document root to `/public`, add rewrite rules for Laravel.
        *   **Permissions:** Ensure `storage` and `bootstrap/cache` directories are writable by the web server/queue worker user.

8.  **Final Commit:**
    *   **Action:** Commit the completed Phase 6 work.
        ~~~bash
        git add .
        git commit -m "Phase 6: Implement i18n, Pennant, Tests (Unit/Feature/Dusk), Performance Tuning, Docs, Backup, Deployment Prep"
        ~~~

---

## Summary

This implementation plan provides a detailed roadmap for developing the User Model Enhancements based on the PRD. By following these phased steps, focusing on the core architectural patterns (Services, Events, State Machines, ULIDs, Enums), and leveraging the specified Laravel packages, the development team can build a robust, maintainable, and feature-rich user management system. The plan includes setup, backend implementation, frontend UI considerations for Livewire/Volt, Inertia/React, and Inertia/Vue, testing strategies, and deployment preparation, catering specifically to experienced developers new to the PHP/Laravel ecosystem. Remember to adapt UI component implementations based on the specific requirements and design system of the project. Regular testing and code reviews throughout the process are crucial for success.
