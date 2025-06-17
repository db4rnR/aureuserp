# Building Enhanced User Management in Laravel: A Novice's Guide (Laravel 12 Edition)

--- START SECTION: Introduction ---

## 0. Introduction

### 0.1. Welcome!

Hello and welcome! If you're reading this, you're likely a developer with some basic PHP knowledge, ready to dive deeper into building web applications with the latest version of one of the most popular PHP frameworks: **Laravel 12**. This tutorial is designed specifically for you – the **lone novice developer** embarking on adding a significant set of features to a web application.

Our goal is ambitious but achievable: we'll take a set of requirements for enhancing user management features (we'll call this **User Model Enhancements** or **UME**) and build it step-by-step using modern Laravel practices. Think things like detailed user profiles, avatars, teams, roles & permissions within those teams, security features like Two-Factor Authentication (2FA), and even real-time features like online presence indicators and basic chat!

**Key Changes in this Edition:**
*   Targets **Laravel 12**.
*   Uses **Livewire/Volt** with Single File Components (SFCs) as the **primary** user-facing frontend stack.
*   Includes dedicated sections showing alternative implementations for **FilamentPHP** (for admin interfaces), **Inertia.js with React**, and **Inertia.js with Vue** where UI is involved.
*   Utilizes **Tailwind CSS v4** (the default for Laravel 12 starter kits).
*   Starts with a minimal Laravel 12 installation and adds **Laravel Breeze** for basic auth scaffolding.

### 0.2. What We'll Build

Based on a fictional Product Requirements Document (PRD) and an Implementation Plan, we will build the following features, integrating them into a standard Laravel 12 application:

1.  **Enhanced User Profiles:** Splitting names into components (given, family), managing avatars using a powerful media library package.
2.  **Teams & Hierarchy:** Creating teams, allowing users to belong to multiple teams, establishing parent-child relationships between teams (hierarchy).
3.  **Roles & Permissions:** Implementing fine-grained access control where users have specific roles (like 'Admin', 'Editor', 'Member') *within* specific teams, controlling what actions they can perform, managed via code and potentially a Filament admin panel.
4.  **Security:** Adding Two-Factor Authentication (2FA) for enhanced login security.
5.  **Account Lifecycle:** Managing user account status (e.g., Pending Validation, Active, Suspended) using a robust State Machine pattern.
6.  **Real-time Features (Restricted):**
    *   **Presence:** Showing which users are online/offline, but *only* to members within the same top-level team.
    *   **Chat:** A basic real-time chat system, also restricted *only* to members within the same top-level team.
7.  **Supporting Features:** User impersonation (for admins), user settings (like locale/timezone), commenting (basic setup), search integration (Scout/Typesense), feature flags (Pennant), internationalization (multi-language support), data backups, and more.
8.  **Admin Interface (Filament):** Setting up basic Filament resources for managing Users, Teams, Roles, and Permissions.

### 0.3. Learning Objectives

By the end of this tutorial, you will have:

*   Created a new Laravel 12 project.
*   Installed and configured Laravel Breeze for basic authentication.
*   Installed and configured FilamentPHP for admin interfaces.
*   Installed and configured numerous essential first-party (Laravel) and third-party (especially Spatie) packages.
*   Understood and implemented core Laravel concepts: Routing, Controllers, Models (Eloquent ORM), Migrations, Seeders, Factories, Middleware, Events, Listeners, Queues (Horizon), Service Container, Blade templating.
*   Applied important architectural patterns: Service Layer, State Machines, Enums.
*   Built database schemas using migrations.
*   Managed user authentication and authorization, including 2FA and team-based permissions.
*   Handled file uploads (avatars) effectively using `spatie/laravel-medialibrary`.
*   Implemented real-time features using WebSockets (Laravel Reverb & Echo).
*   Set up basic search functionality with Scout and Typesense.
*   Written basic tests (Unit, Feature, Browser) using PestPHP.
*   Gained experience building UI components with Livewire/Volt SFCs.
*   Created admin interfaces using FilamentPHP Resources.
*   Understood how to adapt UI implementations for Inertia/React and Inertia/Vue (provided as alternative sections).
*   Prepared your application for deployment.

Most importantly, you'll understand the **"why"** behind the code, not just the "how".

### 0.4. How to Use This Tutorial

This document is structured as a curriculum. Follow the steps sequentially within each Phase.

*   **Primary Path:** The main instructions assume you are building the user-facing UI with **Livewire/Volt**.
*   **Alternative UI Sections:** Where frontend implementation differs significantly, specific sections will be provided for:
    *   **FilamentPHP:** For building administrative interfaces to manage data.
    *   **Inertia/React:** For user-facing UI using React.
    *   **Inertia/Vue:** For user-facing UI using Vue.
    You can focus on the Livewire path and refer to the others, or attempt to implement one of the alternative stacks if you prefer.
*   **Read Carefully:** Pay attention to the explanations – they provide context and reasoning.
*   **Code Along:** Type the commands and code yourself rather than just copy-pasting (though full code is provided). This builds muscle memory.
*   **Verify Steps:** Use the "Verification" sections to ensure things are working as expected before moving on.
*   **Consult the Glossary:** If you encounter an unfamiliar term, check the Glossary at the end.
*   **Experiment:** Don't be afraid to tinker! Try changing things slightly (after committing your working code with Git!) to see what happens. That's a great way to learn.
*   **Patience:** Learning takes time. If you get stuck, reread the relevant section, check the verification steps, or consult the official Laravel documentation (linked in the Glossary).

Let's get started!

--- END SECTION: Introduction ---

--- START SECTION: Progress Tracker (Revised) ---

## 2. Prerequisites: Setting Up Your Development Environment

Before we write a single line of Laravel 12 code, we need to make sure your computer is ready. Think of this like gathering your ingredients before cooking. Each tool has a specific purpose in building and running a modern web application.

### 2.1. Understanding the Tools

Here's a quick rundown of *what* we need and *why* for Laravel 12 development:

1.  **PHP (>= 8.2 Recommended):**
    *   **What:** The programming language Laravel is written in. Laravel 12 requires PHP 8.2 or higher. Version 8.2+ includes features like Enums, Readonly Properties, etc., that Laravel leverages.
    *   **Why:** Laravel *is* PHP. You need PHP installed to execute the framework and your application code. Higher versions bring performance improvements and new language features.
2.  **Composer:**
    *   **What:** A dependency manager for PHP. It's like npm for Node.js or pip for Python.
    *   **Why:** Laravel itself and many of the features we'll add (like permission management, media handling, Filament) are external libraries called "packages". Composer downloads and manages these packages and their dependencies, ensuring everything works together. It reads instructions from a `composer.json` file.
3.  **Node.js and npm/yarn:**
    *   **What:** Node.js is a JavaScript runtime. npm and yarn are package managers for JavaScript.
    *   **Why:** Modern web applications use JavaScript for interactivity. Laravel uses Vite for compiling frontend assets (CSS, JS), which relies on Node.js. Even with Livewire (PHP-centric), you still need Node/npm/yarn for compiling Tailwind CSS and base JavaScript.
4.  **Database (PostgreSQL Recommended):**
    *   **What:** A system for storing your application's data persistently (users, teams, messages, etc.). PostgreSQL and MySQL are common choices compatible with Laravel.
    *   **Why:** Most web applications need to store data. We need a database server running so Laravel can connect to it and save/retrieve information using Eloquent. PostgreSQL is often favoured for its robustness and advanced features, but MySQL works fine too. SQLite can be used for local development/testing (`DB_CONNECTION=sqlite` and `DB_DATABASE=/path/to/database.sqlite` or `:memory:` for tests).
5.  **Git:**
    *   **What:** A version control system.
    *   **Why:** Absolutely essential for tracking changes to your code. Allows saving snapshots (commits), reverting, collaborating, and managing features (branches). We'll commit after each major milestone.
6.  **Code Editor (VS Code Recommended):**
    *   **What:** A text editor designed for writing code.
    *   **Why:** You need a tool to write PHP, JavaScript, CSS, etc. Provides syntax highlighting, code completion, error checking, and tool integration (Git, terminals). VS Code is very popular in the Laravel community with excellent extensions.
7.  **Optional: Redis:**
    *   **What:** An in-memory data structure store, often used as a cache, session driver, and queue broker.
    *   **Why:** For better performance, we'll configure Laravel to use Redis for caching, sessions, and background jobs (queues via Horizon). Laravel can fall back to other drivers (file, database), but Redis is generally faster. Reverb also benefits from Redis for horizontal scaling (though not strictly required for basic use).
8.  **Optional: Typesense:**
    *   **What:** An open-source search engine.
    *   **Why:** We'll implement fast, typo-tolerant search using Laravel Scout and Typesense. Requires a running Typesense server. If skipped, search features won't work.
9.  **Optional: Docker:**
    *   **What:** A platform for containerizing applications.
    *   **Why:** Simplifies setting up a consistent development environment (PHP, DB, Redis, etc.) regardless of your OS. Laravel Sail (`php artisan sail:install`) provides a simple Docker environment for Laravel. While not strictly required (we assume local installs), it's valuable.

### 2.2. Installation Steps

Installation varies by OS (Windows, macOS, Linux).

*   **Recommendation:** For the easiest start, especially on macOS or Windows, consider **Laravel Herd** ([https://herd.laravel.com/](https://herd.laravel.com/)). It bundles PHP, Nginx/Caddy, Node.js, Composer, and manages services locally with minimal fuss.
*   **Alternative (Docker):** Use **Laravel Sail**. After creating your project (Step 4.1), run `php artisan sail:install` and follow prompts. Then run commands via `./vendor/bin/sail <command>` (e.g., `./vendor/bin/sail up`, `./vendor/bin/sail artisan migrate`).
*   **Manual Installation:** Consult official documentation:
    *   PHP: [https://www.php.net/manual/en/install.php](https://www.php.net/manual/en/install.php) (Ensure required extensions like `pdo_pgsql` or `pdo_mysql`, `redis`, `gd`, `mbstring`, `xml`, `curl`, `bcmath` are enabled). Check Laravel 12 server requirements.
    *   Composer: [https://getcomposer.org/download/](https://getcomposer.org/download/)
    *   Node.js: [https://nodejs.org/](https://nodejs.org/) (LTS version recommended)
    *   PostgreSQL: [https://www.postgresql.org/download/](https://www.postgresql.org/download/)
    *   MySQL: [https://dev.mysql.com/downloads/](https://dev.mysql.com/downloads/)
    *   Git: [https://git-scm.com/downloads](https://git-scm.com/downloads)
    *   VS Code: [https://code.visualstudio.com/](https://code.visualstudio.com/)
    *   Redis: [https://redis.io/docs/getting-started/installation/](https://redis.io/docs/getting-started/installation/)
    *   Typesense: [https://typesense.org/docs/guide/install-typesense.html](https://typesense.org/docs/guide/install-typesense.html)

#### 2.2.1. PHP (>= 8.2 Recommended)

Follow OS-specific guides or use Herd/Sail. Verify version `php -v`. Ensure necessary extensions are enabled (check `php -m`). Laravel 12 requires PHP >= 8.2.

#### 2.2.2. Composer

Install globally following instructions on [getcomposer.org](https://getcomposer.org/). Verify with `composer -V`.

#### 2.2.3. Node.js and npm/yarn

Install LTS version from [nodejs.org](https://nodejs.org/). Includes npm. Verify `node -v` and `npm -v`.

#### 2.2.4. Database (PostgreSQL Recommended)

Install PostgreSQL server (or MySQL). Create a new, empty database (e.g., `ume_app_db`). Note the connection details (host, port, dbname, username, password). Ensure the server is running.

#### 2.2.5. Git

Install from [git-scm.com](https://git-scm.com/). Verify `git --version`. Configure your identity:
```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

#### 2.2.6. Code Editor (VS Code Recommended)

Download and install from [code.visualstudio.com](https://code.visualstudio.com/). Recommended extensions:
*   **PHP Intelephense** or **PHP IntelliSense (Crane)**
*   **Laravel Extension Pack** (includes Blade, Snippets, etc.)
*   **Tailwind CSS IntelliSense** (Essential for Tailwind v4)
*   **DotENV**
*   **EditorConfig for VS Code**
*   **Prettier - Code formatter** (Configure for JS/CSS)
*   **Material Icon Theme** (or similar)
*   **Pest Plugin** (if using Pest)
*   **Filament IDEA** (if using Filament - provides autocompletion)

#### 2.2.7. Optional: Redis

Install Redis server (`redis-server`) and CLI (`redis-cli`). Ensure it's running. Verify `redis-cli --version`.

#### 2.2.8. Optional: Typesense

Install Typesense server. Ensure it's running. Note the API key. Verify installation as per Typesense docs.

#### 2.2.9. Optional: Docker

Install Docker Desktop ([https://www.docker.com/products/docker-desktop/](https://www.docker.com/products/docker-desktop/)) if planning to use Sail.

#### 2.2.10. Verification

**Crucially, ensure these commands work in your terminal *before* proceeding:**

```bash
php -v # Should be 8.2 or higher
composer -V
node -v
npm -v # or yarn -v
git --version
# psql --version # (If using PostgreSQL)
# mysql --version # (If using MySQL)
# redis-cli --version # (If using Redis)
# typesense-server --version # (If using Typesense, command might vary)
```

With your tools ready, let's look at the roadmap.

--- END SECTION: Prerequisites ---

--- START SECTION: Progress Tracker (Revised) ---

## 3. Progress Tracker: Our Journey

We'll build the UME features in phases, broken down into smaller, manageable milestones. Mark these off as you complete them!

*(Note: [UI] indicates sections with specific framework implementations)*

**Phase 0: Laying the Foundation**
*   [✅] **0.1:** Create Laravel 12 Project (using interactive installer, select **Livewire Starter Kit** + Volt + Pest)
*   [✅] **0.2:** Configure Environment (`.env` Setup)
*   [❌] **0.3:** Install FilamentPHP
*   [ ] **0.4:** Install Core Backend Packages (`composer require ...`)
*   [ ] **0.5:** Publish Configurations & Run Initial Migrations (`vendor:publish`, `migrate`)
*   [ ] **0.6:** Configuring Laravel Pulse Access (`PulseServiceProvider`)
*   [ ] **0.7:** First Git Commit

**Phase 1: Building the Core Models & Architecture**
*   [ ] **1.1:** Understanding Traits & Model Events
*   [ ] **1.2:** Create `HasUlid` Trait
*   [ ] **1.3:** Create `HasUserTracking` Trait
*   [ ] **1.4:** Understanding Database Migrations
*   [ ] **1.5:** Enhance `users` Table Migration (Modify Starter Kit Migration)
*   [ ] **1.6:** Understanding Eloquent Models & Relationships
*   [ ] **1.7:** Create `Team` Model & Migration
*   [ ] **1.8:** Create `team_user` Pivot Table Migration
*   [ ] **1.9:** Update `User` Model (Traits, Casts, Relationships, Accessors - Name Refactor)
*   [ ] **1.10:** Understanding Factories & Seeders
*   [ ] **1.11:** Update `UserFactory` (Name Refactor)
*   [ ] **1.12:** Create `UserSeeder` & `TeamSeeder`
*   [ ] **1.13:** Update `DatabaseSeeder`
*   [ ] **1.14:** Understanding The Service Layer
*   [ ] **1.15:** Create `BaseService`
*   [ ] **1.16:** Initial Filament Resource Setup (User & Team)
*   [ ] **1.17:** Phase 1 Git Commit

**Phase 2: Authentication, Profile Basics & State Machine**
*   [ ] **2.1:** Understanding Fortify & Authentication (Provided by Starter Kit)
*   [ ] **2.2:** Configure Fortify Features (`config/fortify.php`)
*   [ ] **2.3:** Understanding Enums & State Machines
*   [ ] **2.4:** Define `AccountStatus` Enum
*   [ ] **2.5:** Create Account State Machine Classes
*   [ ] **2.6:** Understanding Email Verification Flow (Provided by Starter Kit)
*   [ ] **2.7:** Integrate State Machine with Email Verification (Modify Starter Kit Controller)
*   [ ] **2.8:** Understanding Two-Factor Authentication (2FA - Fortify Backend)
*   [ ] **2.9:** Implement 2FA UI [UI] (Modify/Use Livewire/Volt Kit Components, Add Filament UI, Conceptual React/Vue)
*   [ ] **2.10:** Implement Profile Information UI [UI] (Modify Backend Controller/Request, Modify Livewire/Volt Kit Component, Add Filament UI, Conceptual React/Vue)
*   [ ] **2.11:** Understanding File Uploads & `spatie/laravel-medialibrary`
*   [ ] **2.12:** Implement Avatar Upload Backend
*   [ ] **2.13:** Implement Avatar Upload UI [UI] (Livewire/Volt, Filament, Conceptual React/Vue)
*   [ ] **2.14:** Understanding Dependency Injection & Service Providers
*   [ ] **2.15:** Create `UserService` (Initial Version)
*   [ ] **2.16:** Customize Fortify's User Creation (Via Starter Kit's Action Binding)
*   [ ] **2.17:** Understanding Events & Listeners
*   [ ] **2.18:** Define Initial Events & Listeners
*   [ ] **2.19:** Register Events & Listeners
*   [ ] **2.20:** Phase 2 Git Commit

**Phase 3: Implementing Teams and Permissions**
*   [ ] **3.1:** Understanding `spatie/laravel-permission` & Team Scoping
*   [ ] **3.2:** Configure `spatie/laravel-permission` for Teams
*   [ ] **3.3:** Create `PermissionSeeder`
*   [ ] **3.4:** Create `TeamService`
*   [ ] **3.5:** Understanding Resource Controllers & Authorization (Policies)
*   [ ] **3.6:** Set up Team Management Backend (Routes, Controllers, Policy)
*   [ ] **3.7:** Implement Team Management UI [UI] (Livewire/Volt, Filament, Conceptual React/Vue)
*   [ ] **3.8:** Understanding Middleware
*   [ ] **3.9:** Create Optional `EnsureUserHasTeamRole` Middleware
*   [ ] **3.10:** Phase 3 Git Commit

**Phase 4: Real-time Foundation & Activity Logging**
*   [ ] **4.1:** Understanding WebSockets, Reverb & Echo
*   [ ] **4.2:** Set Up Laravel Reverb
*   [ ] **4.3:** Configure Laravel Echo (Backend & Frontend)
*   [ ] **4.4:** Implement Presence Status Backend (Enum, Migration, Cast)
*   [ ] **4.5:** Create `PresenceChanged` Broadcast Event
*   [ ] **4.6:** Create Login/Logout Presence Listeners
*   [ ] **4.7:** Understanding Contextual Activity Logging
*   [ ] **4.8:** Implement Activity Logging via Listeners
*   [ ] **4.9:** Phase 4 Git Commit

**Phase 5: Advanced Features & Real-time Implementation**
*   [ ] **5.1:** Implement Impersonation Feature [UI] (Backend, Livewire/Volt, Filament, Conceptual React/Vue)
*   [ ] **5.2:** Implement Comments Feature [UI] (Backend, Livewire/Volt, Filament, Conceptual React/Vue)
*   [ ] **5.3:** Implement User Settings Feature [UI] (Backend, Livewire/Volt, Filament, Conceptual React/Vue)
*   [ ] **5.4:** Understanding Full-Text Search (Scout & Typesense)
*   [ ] **5.5:** Implement Search Backend (Scout/Typesense Config, Model, Import)
*   [ ] **5.6:** Implement Search Frontend [UI] (Backend Endpoint, Livewire/Volt, Filament, Conceptual React/Vue)
*   [ ] **5.7:** Understanding Broadcasting Channels & Authorization
*   [ ] **5.8:** Define Broadcast Channel Authorizations (`channels.php`)
*   [ ] **5.9:** Implement Real-time Presence UI [UI] (Livewire/Volt, Filament-Info, Conceptual React/Vue)
*   [ ] **5.10:** Implement Real-time Chat Backend (Model, Service, API, Event)
*   [ ] **5.11:** Implement Real-time Chat UI [UI] (Livewire/Volt, Filament-Info, Conceptual React/Vue)
*   [ ] **5.12:** Understanding API Authentication (Passport & Sanctum)
*   [ ] **5.13:** Configure API Authentication Guards
*   [ ] **5.14:** Set Up Passport Routes
*   [ ] **5.15:** Phase 5 Git Commit

**Phase 6: Polishing, Testing & Deployment**
*   [ ] **6.1:** Understanding Internationalization (i18n)
*   [ ] **6.2:** Implement i18n (Backend)
*   [ ] **6.3:** Implement Locale Switching [UI] (Backend Middleware, Livewire/Volt, Filament, Conceptual React/Vue)
*   [ ] **6.4:** Understanding Feature Flags (Pennant)
*   [ ] **6.5:** Implement Feature Flags
*   [ ] **6.6:** Understanding Testing (Unit, Feature, Browser - PestPHP)
*   [ ] **6.7:** Writing Tests (Examples - PestPHP, Filament)
*   [ ] **6.8:** Understanding Performance Optimization
*   [ ] **6.9:** Apply Performance Considerations
*   [ ] **6.10:** Write Documentation (README, PHPDoc)
*   [ ] **6.11:** Set Up Data Backups (`spatie/laravel-backup`)
*   [ ] **6.12:** Understanding Deployment
*   [ ] **6.13:** Prepare for Deployment
*   [ ] **6.14:** Final Git Commit

--- END SECTION: Progress Tracker (Revised) ---

--- START SECTION: Phase 0: Laying the Foundation (Project Setup) ---

## 4. Phase 0: Laying the Foundation (Project Setup)

**Goal:** Create a new Laravel 12 application using the built-in **Livewire Starter Kit**, install FilamentPHP for the admin panel, install necessary third-party packages for UME features, and set up initial configurations and database migrations.

Think of this phase as establishing the core project, complete with basic authentication and admin capabilities, before adding our custom enhancements.

**(Conceptual Note: SPA vs. MPA and Our Choice)**

Before we begin, let's briefly touch on application architecture.

*   **MPA (Multi-Page Application):** The traditional web model. Each significant user action (like navigating to a new page or submitting a form) triggers a full page request to the server, which then renders and sends back a complete new HTML page. Standard Laravel with Blade views works this way. It's simple, robust, and SEO-friendly.
*   **SPA (Single-Page Application):** A modern approach where the initial page loads an application shell (HTML, CSS, JS). Subsequent navigation and interactions typically happen dynamically using JavaScript. JS frameworks (React, Vue, Angular) update parts of the page by fetching data from a backend API, providing a faster, app-like feel without full page reloads.
*   **Livewire:** Offers a middle ground. It allows building dynamic, interactive interfaces like SPAs but primarily using PHP and Blade templates. It cleverly updates only the necessary parts of the DOM via AJAX requests when interactions occur, providing SPA-like reactivity without requiring a full JS framework build process for the *entire* frontend. It feels closer to traditional Laravel development while enabling dynamic UIs.
*   **Inertia.js:** Facilitates building true SPAs with Laravel. You use familiar Laravel routing and controllers on the backend, but your controllers return Inertia responses that load specific JavaScript page components (React/Vue). Inertia acts as the glue, managing the frontend routing and data fetching.

**Our Approach:**

*   **Primary User Interface:** We will use **Livewire** (specifically with Volt SFCs) for the main user-facing parts of the application (profile, teams, chat, etc.). This leverages the power of Laravel's backend while providing dynamic UIs. It aligns well with developers comfortable primarily with PHP/Blade.
*   **Admin Interface:** We will use **FilamentPHP**, a powerful admin panel framework built on the TALL stack (Tailwind, Alpine.js, Livewire, Laravel). It allows rapidly building sophisticated data management interfaces.
*   **Alternative UI Sections:** For key user-facing features, we will provide *conceptual* implementation details or code snippets for **Inertia/React** and **Inertia/Vue** to demonstrate how these SPA approaches could be used for the same features.

### 4.1. Milestone 0.1: Creating the Laravel 12 Project (with Livewire Starter Kit)

We'll use the `laravel new` command and select the built-in Livewire starter kit during the interactive setup. This provides our base Laravel 12 application *including* authentication scaffolding powered by Fortify and Livewire/Volt.

*   **Action:** Open your terminal, navigate to your desired projects directory, and run:

```bash
    # Ensure your Laravel Installer is up-to-date
    # composer global update laravel/installer

    # Create the new project
    laravel new ume-app
```

*   **Interactive Prompts:** The installer will ask several questions. Choose the following:
    *   `Which starter kit would you like to install?` -> **`Livewire`**
    *   `Would you like any additional starter kit features?` -> Select **`Volt`** (Use arrow keys and spacebar to select, Enter to confirm). You can also select `Dark mode` if desired.
    *   `Which testing framework do you prefer?` -> **`Pest`**
    *   `Would you like to initialize a Git repository?` -> **`Yes`**
    *   `Which database will your application use?` -> Choose **`PostgreSQL`** (or `MySQL` if you prefer). Provide database name (e.g., `ume_app_db`).
    *   The installer will then create the project, install Composer dependencies, configure the starter kit, install NPM dependencies, build assets, and initialize Git.

*   **Action:** Navigate into the new directory:

```bash
    cd ume-app
```

*   **What it Does:**
    *   Creates a full Laravel 12 project structure.
    *   Installs and configures the Livewire starter kit, which includes:
        *   Laravel Fortify for backend authentication logic.
        *   Routes, Controllers, and Livewire/Volt components for login, registration, password reset, email verification, and basic profile management.
        *   Tailwind CSS v4 setup via Vite.
        *   Livewire and Volt packages.
    *   Configures PestPHP for testing.
    *   Initializes a Git repository.
    *   Installs Composer and NPM dependencies.
    *   Builds initial frontend assets.
    *   Sets basic `.env` variables (like DB connection based on your choice).

*   **Why:** This single command provides a complete, functional Laravel 12 application with a secure authentication system and our chosen primary UI stack (Livewire/Volt) ready to go. It leverages the official L12 starter kit workflow.

*   **Verification:**
    1.  `ume-app` directory exists and contains Laravel project files.
    2.  Run `git status` - should show a clean working directory after the initial commits made by the installer.
    3.  Check `composer.json` includes `laravel/fortify`, `livewire/livewire`, `livewire/volt`.
    4.  Check `package.json` includes Tailwind v4 (`@tailwindcss/vite` or similar), Alpine.js.
    5.  Check `routes/web.php` includes `auth.php`. Check `routes/auth.php` exists.
    6.  Check `resources/views/` contains `auth/`, `profile/`, `layouts/`, `livewire/` directories with Blade/Volt files.

### 4.2. Milestone 0.2: Configuring the Environment (`.env` Setup)

The installer sets up some `.env` variables, but we need to verify and add others.

*   **Action 1:** Open the `.env` file created by the installer.
*   **Action 2:** Verify/Update/Add variables.

*   **File (`.env` - Verify/Add/Update):**

```dotenv
    APP_NAME="UME App"
    APP_ENV=local
    APP_KEY=base64:... # Should already be generated
    APP_DEBUG=true
    APP_URL=http://ume-app.test # IMPORTANT: Set this correctly!

    LOG_CHANNEL=stack
    LOG_LEVEL=debug

    # --- Database ---
    # Verify these match your local setup AND the choices during `laravel new`
    DB_CONNECTION=pgsql # Or mysql
    DB_HOST=127.0.0.1
    DB_PORT=5432 # Or 3306 for MySQL
    DB_DATABASE=ume_app_db # Should match your choice/creation
    DB_USERNAME=sail # Your DB user
    DB_PASSWORD=password # Your DB password

    # --- Broadcasting, Cache, Queue, Session ---
    # Add these if missing, ensure values are correct
    BROADCAST_DRIVER=reverb # Use 'log' or 'null' initially if Reverb not setup
    CACHE_DRIVER=redis # Use 'file' if no Redis
    QUEUE_CONNECTION=redis # Use 'sync' if no Redis/Horizon setup yet
    SESSION_DRIVER=redis # Use 'file' if no Redis
    SESSION_LIFETIME=120

    # --- Redis (Add if using redis driver) ---
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    REDIS_CLIENT=phpredis # Default in L12

    # --- Reverb (Add these, generate random secrets) ---
    REVERB_APP_ID=your_reverb_app_id_placeholder
    REVERB_APP_KEY=your_reverb_app_key_placeholder
    REVERB_SECRET=your_reverb_app_secret_placeholder
    REVERB_HOST="localhost"
    REVERB_PORT=8080
    REVERB_SCHEME=http
    # Add corresponding VITE_ vars for frontend JS
    VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
    VITE_REVERB_HOST="${REVERB_HOST}"
    VITE_REVERB_PORT="${REVERB_PORT}"
    VITE_REVERB_SCHEME="${REVERB_SCHEME}"

    # --- Mail (Verify/Add) ---
    MAIL_MAILER=log # Or smtp, mailtrap, etc.
    MAIL_HOST=127.0.0.1 # Or smtp server
    MAIL_PORT=1025 # Or smtp port
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"

    # --- Third-Party Services (Add these) ---
    SCOUT_DRIVER=typesense # Use 'null' initially if not using search yet
    SCOUT_QUEUE=true
    TYPESENSE_HOST=localhost
    TYPESENSE_PORT=8108
    TYPESENSE_PROTOCOL=http
    TYPESENSE_API_KEY=your_typesense_api_key_placeholder
    TYPESENSE_COLLECTION_PREFIX=ume_
```

*   **Why:** Ensures the application connects correctly to the database, cache, queues, real-time server, mail driver, and search engine based on your local development setup. `APP_URL` is crucial for generating correct links (e.g., in emails).
*   **Verification:**
    1.  `.env` file is updated.
    2.  Crucially, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` match your actual database setup where you created the `ume_app_db` database.
    3.  `APP_URL` matches how you access the site locally (e.g., `http://ume-app.test` for Herd/Valet, `http://localhost:8000` for `artisan serve`).

### 4.3. Milestone 0.3: Installing FilamentPHP

Now, add the Filament admin panel framework.

*   **Action:** Install Filament via Composer and run its installer.

```bash
    # Install Filament package
    composer require filament/filament:"^3.3" -W

    # Run the installer
    php artisan filament:install --panels
```
    *   Follow prompts: Agree to star the repo (optional), create Filament admin user (say **Yes**), enter admin name, email (use a distinct one like `filament@example.com` or your main admin email), and password.

*   **What it Does:** Installs Filament packages, sets up config/provider, publishes assets, creates initial admin user in `users` table, potentially creates `app/Filament/Resources/UserResource.php`.
*   **Why:** Adds the admin panel foundation.
*   **Verification:**
    1.  Commands succeed.
    2.  Visit `/admin`. See Filament login.
    3.  Log in with the Filament user created. See dashboard.
    4.  Check `app/Filament/` directory and `app/Providers/Filament/AdminPanelProvider.php`.
    5.  Check `users` table for the newly created Filament admin user.

### 4.4. Milestone 0.4: Installing Core Backend Packages

Install the other essential backend packages needed for UME features.

*   **Action:** Run `composer require` commands.

```bash
    # --- Core Laravel & Utility Packages ---
    # Passport (OAuth2 Server)
    composer require laravel/passport
    # Reverb (WebSockets)
    composer require laravel/reverb
    # Pennant (Feature Flags)
    composer require laravel/pennant
    # Horizon (Queue Dashboard)
    composer require laravel/horizon
    # Pulse (Performance Monitoring)
    composer require laravel/pulse
    # Telescope (Local Debug Assistant)
    composer require laravel/telescope --dev
    # Scout (Search Abstraction)
    composer require laravel/scout
    # Typesense Driver for Scout
    composer require typesense/typesense-php

    # --- Spatie Packages ---
    # Permissions (Roles/Permissions)
    composer require spatie/laravel-permission
    # Media Library (File/Avatar Handling)
    composer require spatie/laravel-medialibrary
    # Activity Log
    composer require spatie/laravel-activitylog
    # Model States (State Machine)
    composer require spatie/laravel-model-states
    # Sluggable (URL Slugs)
    composer require spatie/laravel-sluggable
    # Tags (Tagging Functionality)
    composer require spatie/laravel-tags
    # Translatable (Model Attribute Translation)
    composer require spatie/laravel-translatable
    # Translation Loader (DB/JSON Translations)
    composer require spatie/laravel-translation-loader
    # Settings (App/User Settings)
    composer require spatie/laravel-settings
    # Comments
    composer require spatie/laravel-comments
    # Backup
    composer require spatie/laravel-backup

    # --- Other Utility Packages ---
    # Impersonate (User Impersonation)
    composer require lab404/laravel-impersonate
    # Socialite (OAuth Login - e.g., Google, GitHub)
    composer require laravel/socialite
    # Doctrine DBAL (Needed for modifying columns in migrations)
    composer require doctrine/dbal
```

*   **Verification:** Commands succeed. `composer.json` updated. `vendor/` directory populated.

### 4.5. Milestone 0.5: Publishing Configurations & Initial Migrations

Publish package assets and run all migrations (Laravel defaults, Starter Kit additions, Filament, Pulse, other packages).

*   **Action 1:** Publish necessary files.

```bash
    # Publish config/migration/other files for specific packages
    # (Same list as Milestone 4.6 in original plan, but Pulse is handled by pulse:install)
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
    php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
    php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
    php artisan vendor:publish --provider="Spatie\Sluggable\SluggableServiceProvider"
    php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider"
    php artisan vendor:publish --provider="Spatie\TranslationLoader\TranslationLoaderServiceProvider"
    php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider"
    php artisan vendor:publish --provider="Spatie\Comments\CommentsServiceProvider"
    php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
    php artisan vendor:publish --provider="Lab404\Impersonate\ImpersonateServiceProvider"
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" # Likely already done
    php artisan vendor:publish --provider="Laravel\Passport\PassportServiceProvider"
    php artisan vendor:publish --provider="Laravel\Pennant\PennantServiceProvider"
    php artisan vendor:publish --provider="Laravel\Telescope\TelescopeServiceProvider" # Publishes config
    # php artisan vendor:publish --tag=telescope-migrations # Not needed unless customizing telescope migrations
    php artisan vendor:publish --tag=horizon-assets
    php artisan vendor:publish --tag=horizon-config

    # --- Special Install Commands ---
    php artisan pulse:install # Installs Pulse migrations & assets
    php artisan passport:install --uuids # Creates keys & DB clients
    php artisan reverb:install # Installs config if not already present
```

*   **Action 2:** Run database migrations.

```bash
    php artisan migrate
```

*   **Verification:** Config files published to `config/`. Migration files added to `database/migrations/`. `migrate` command runs successfully, creating all necessary tables. Database schema contains tables for users, teams (later), roles, permissions, filament, pulse, telescope, etc.

### 4.6. Milestone 0.6: Configuring Laravel Pulse Access

Restrict access to the Pulse dashboard (`/pulse`).

*   **Action:** Modify `app/Providers/PulseServiceProvider.php`.

*   **File (`app/Providers/PulseServiceProvider.php`):** *(Implement `gate()` method as per Milestone 4.7 original plan - define 'viewPulse' gate checking for admin user)*.

*   **Verification:** Code added correctly.

### 4.7. Milestone 0.7: First Git Commit

Save the foundational setup.

*   **Action:** Commit changes.

```bash
    git status # Review changes
    git add .
    git commit -m "Phase 0: Setup L12 + Livewire Starter Kit, Filament, Core Packages, Configs, Migrations"
```

*   **Verification:** `git log`, `git status`.

**Phase 0 Complete!** You have a running Laravel 12 application using the Livewire starter kit, providing authentication. Filament is installed for admin tasks. All core backend packages for UME are installed and configured. The database schema is initialized.

--- END SECTION: Phase 0: Laying the Foundation (Project Setup) ---

--- START SECTION: Phase 1: Building the Core Models & Architecture ---

## 5. Phase 1: Building the Core Models & Architecture

**Goal:** Define the database structure and Eloquent models for `User` and `Team`, including enhancements like name components and hierarchy. Create reusable Traits (ULIDs, user tracking), set up Factories/Seeders, establish the Service Layer base, and configure initial Filament Resources.

**(Conceptual Note: Refactoring the User Name)**

A key requirement is to replace Laravel's default single `name` field on the `User` model with more granular `given_name`, `family_name`, and optional `other_names`.

*   **Why?** Allows for better sorting, personalized greetings, handling diverse naming conventions, and integration with systems requiring separate name parts.
*   **How?**
    1.  **Migration:** Modify the `users` table migration (created by the starter kit) to add `given_name`, `family_name`, `other_names` columns. We might keep the original `name` column nullable for compatibility or remove it if starting fresh (we'll keep it nullable initially).
    2.  **Model:**
        *   Add the new fields to the `$fillable` array.
        *   Remove `name` from `$fillable` (or ensure it's not the primary way to set names).
        *   **Crucially:** Add an **Accessor** for `name`. This computed property will concatenate `given_name` and `family_name` (and maybe `other_names`) when accessed via `$user->name`. This maintains compatibility with parts of Laravel or packages that might still expect a `name` attribute (like default display names in some contexts).
    3.  **Factory/Seeder:** Update the `UserFactory` and `UserSeeder` to populate the new name component fields instead of `name`.
    4.  **UI:** Update registration forms, profile forms (Livewire, Filament, etc.) to use separate input fields for the name components.

This approach provides the benefits of granular names while minimizing breaking changes by providing the composite `name` via an accessor.

### 5.1. Understanding Key Concepts: Traits & Model Events

*(Explanation remains the same as previous version)*

### 5.2. Milestone 1.1: Creating the `HasUlid` Trait

*(Implementation identical to previous version - create `app/Models/Traits/HasUlid.php`)*

### 5.3. Milestone 1.2: Creating the `HasUserTracking` Trait

*(Implementation identical to previous version - create `app/Models/Traits/HasUserTracking.php`)*

### 5.4. Understanding Key Concepts: Database Migrations

*(Explanation remains the same as previous version)*

### 5.5. Milestone 1.3: Enhancing the `users` Table Migration

Modify the *existing* `users` table migration (created by the Livewire starter kit) to add our UME fields.

*   **Action:** Find `database/migrations/..._create_users_table.php`. **Install `doctrine/dbal`:** `composer require doctrine/dbal`. Replace the `up()` method content.

*   **File (`database/migrations/..._create_users_table.php` - Modify Existing `up()`):** *(Code similar to previous version, but ensure it correctly modifies the table *created by the starter kit*, adding ulid, name components, state, slug, tracking, current team, soft deletes. Keep Fortify/Breeze columns like `two_factor_*` if they exist. Make original `name` column nullable.)*

```php
    // database/migrations/YYYY_MM_DD_HHMMSS_create_users_table.php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Str;

    return new class extends Migration {
        public function up(): void {
            // Modify existing users table if it exists (from starter kit)
            if (Schema::hasTable('users')) {
                Schema::table('users', function (Blueprint $table) {
                    // Add columns if they don't exist
                    if (!Schema::hasColumn('users', 'ulid')) {
                        $table->ulid('ulid')->nullable()->unique()->after('id');
                    }
                    if (!Schema::hasColumn('users', 'given_name')) {
                        $table->string('given_name')->nullable()->after('name'); // Place after original name
                    }
                    if (!Schema::hasColumn('users', 'family_name')) {
                        $table->string('family_name')->nullable()->after('given_name');
                    }
                     if (!Schema::hasColumn('users', 'other_names')) {
                        $table->string('other_names')->nullable()->after('family_name');
                    }
                    if (!Schema::hasColumn('users', 'account_state')) {
                        $table->string('account_state')->nullable()->index()->after('password');
                    }
                     if (!Schema::hasColumn('users', 'slug')) {
                        $table->string('slug')->nullable()->unique()->after('email');
                    }
                    // Add tracking columns
                     if (!Schema::hasColumn('users', 'created_by_id')) {
                        $table->foreignId('created_by_id')->nullable()->index();
                    }
                     if (!Schema::hasColumn('users', 'updated_by_id')) {
                        $table->foreignId('updated_by_id')->nullable()->index();
                    }
                     // Add current team column
                     if (!Schema::hasColumn('users', 'current_team_id')) {
                        $table->foreignId('current_team_id')->nullable()->index();
                    }
                    // Add soft deletes if not present
                    if (!Schema::hasColumn('users', 'deleted_at')) {
                         $table->softDeletes();
                    }

                    // Make original 'name' column nullable if it exists and isn't already
                    if (Schema::hasColumn('users', 'name')) {
                         try { // Need try/catch as DBAL might throw error if already nullable
                              $table->string('name')->nullable()->change();
                         } catch (\Exception $e) {}
                    }
                     // Ensure 2FA columns exist (Breeze adds them)
                     if (!Schema::hasColumn('users', 'two_factor_secret')) { $table->text('two_factor_secret')->nullable()->after('password'); }
                     if (!Schema::hasColumn('users', 'two_factor_recovery_codes')) { $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret'); }
                     if (!Schema::hasColumn('users', 'two_factor_confirmed_at')) { $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes'); }
                });

                 // Set defaults and make non-nullable AFTER adding columns
                 // Requires composer require doctrine/dbal
                 Schema::table('users', function (Blueprint $table) {
                      // Populate first if migrating data (example for ULID/Slug needed)
                      // DB::table('users')->whereNull('ulid')->cursor()->each(fn($user) => ... update ulid ...);
                      // DB::table('users')->whereNull('slug')->cursor()->each(fn($user) => ... update slug ...);
                      // DB::table('users')->whereNull('account_state')->update(['account_state' => 'pending_validation']);

                      if (Schema::hasColumn('users', 'ulid')) { $table->ulid('ulid')->nullable(false)->change(); }
                      if (Schema::hasColumn('users', 'slug')) { $table->string('slug')->nullable(false)->change(); }
                      if (Schema::hasColumn('users', 'account_state')) { $table->string('account_state')->nullable(false)->change(); }
                 });

            } else {
                 // Fallback: Create table if starter kit somehow didn't (unlikely)
                 // Copy schema definition from previous plan's create users migration here.
                 $this->command->error("Users table not found. Create table logic needs to be added here.");
                 // Schema::create('users', function (Blueprint $table) { ... });
                 // Schema::table('users', function (Blueprint $table) { ... make non-nullable ...});
            }
        }
        // down() method should ideally reverse these changes, but simple drop is fine for fresh start
        public function down(): void {
            // Reversing specific column additions/changes is complex.
            // For simplicity, if using migrate:fresh, just dropping is okay.
            // Otherwise, implement logic to drop added columns/indexes/constraints.
            Schema::dropIfExists('users'); // Or revert specific changes if needed
        }
    };
```

*   **Verification:**
    1.  Ensure `doctrine/dbal` installed.
    2.  Run `php artisan migrate` (or `migrate:fresh`).
    3.  Inspect `users` table. Verify new columns (`ulid`, `given_name`, `family_name`, `other_names`, `account_state`, `slug`, `created_by_id`, `updated_by_id`, `current_team_id`, `deleted_at`) exist. Verify `name` is nullable. Verify `ulid`, `slug`, `account_state` are non-nullable.

### 5.6. Understanding Key Concepts: Eloquent Models & Relationships

*(Explanation remains the same as previous version)*

### 5.7. Milestone 1.4: Creating the `Team` Model and Migration

*(Actions and code identical to previous version)*
1.  `php artisan make:model Team -mfs`
2.  Define schema in `..._create_teams_table.php` migration (id, ulid, slug, owner_id, parent_id, name, description, tracking, timestamps, soft deletes, add `users.current_team_id` FK).
3.  Set up `app/Models/Team.php` (use traits, define fillable, relationships, slug options, activity log options, helper).

### 5.8. Milestone 1.5: Creating the `team_user` Pivot Table

*(Actions and code identical to previous version)*
1.  `php artisan make:migration create_team_user_table`
2.  Define schema (id, user_id FK, team_id FK, nullable role, timestamps, unique constraint).

### 5.9. Milestone 1.6: Updating the `User` Model (Traits, Casts, Relationships, Accessors)

Update the `User` model generated by the starter kit.

*   **Action:** Open `app/Models/User.php` and replace/update content.

*   **File (`app/Models/User.php` - Update Existing):** *(Combine implementation from previous plan's Milestone 5.9 with Filament interfaces/methods from current plan's Milestone 5.9. **Crucially, add the `name` accessor.**)*

```php
    <?php
    namespace App\Models;
    // ... (All necessary use statements from previous plan + Filament) ...
    use Filament\Models\Contracts\FilamentUser;
    use Filament\Models\Contracts\HasAvatar;
    use Filament\Panel;

    class User extends Authenticatable implements MustVerifyEmail, HasMedia, FilamentUser, HasAvatar {
        // --- Apply ALL Traits ---
        use HasFactory, Notifiable, SoftDeletes, HasUlid, HasSanctumTokens, TwoFactorAuthenticatable,
            HasPassportTokens, HasRoles, InteractsWithMedia, HasSlug, LogsActivity, HasStates,
            InteractsWithComments, CanComment, HasSettings, Impersonate;

        protected $fillable = [ // Ensure 'name' is NOT here by default
            'given_name', 'family_name', 'other_names', 'email', 'password', 'current_team_id', 'account_state',
        ];
        protected $hidden = [ /* ... */ ];
        protected $casts = [ /* ... include all casts: state, settings, presence, dates */ ];
        protected $appends = [ 'full_name', 'initials', 'avatar_url', 'name' ]; // <-- ADD 'name' TO APPENDS

        // --- NEW/MODIFIED: Name Accessor for Compatibility ---
        protected function name(): Attribute {
             return Attribute::make(
                 get: fn ($value, $attributes) => trim(($attributes['given_name'] ?? '') . ' ' . ($attributes['family_name'] ?? '')),
                 // Optional: Define a setter if you want setting `name` to try and split into components
                 // set: fn ($value) => [ /* logic to split $value into given/family */ ],
             );
        }
        // --- END Name Accessor ---

        // Accessors: fullName(), initials(), avatarUrl(), defaultAvatarUrl() (Implement as before)
        protected function fullName(): Attribute { /* ... */ }
        protected function initials(): Attribute { /* ... */ }
        protected function avatarUrl(): Attribute { /* ... */ }
        public function defaultAvatarUrl(): string { /* ... */ }

        // Relationships: teams(), ownedTeams(), currentTeam(), chatMessages() (Implement as before)
        public function teams(): BelongsToMany { /* ... */ }
        // ... other relationships ...

        // Package Config: getSlugOptions() etc. (Implement as before)
        public function getSlugOptions(): SlugOptions {
             // Generate slug from name components now
             return SlugOptions::create()
                 ->generateSlugsFrom(['given_name', 'family_name']) // <-- CHANGE THIS
                 ->saveSlugsTo('slug')
                 ->doNotGenerateSlugsOnUpdate()
                 ->preventOverwrite();
        }
        public function registerMediaCollections(): void { /* ... */ }
        public function getActivitylogOptions(): LogOptions { /* ... */ }
        public string $settingsClass = UserSettings::class;

        // Team Helpers: belongsToTeam(), ownsTeam(), switchTeam() (Implement as before)
        // ...

        // Impersonation: canImpersonate(), canBeImpersonated() (Implement as before)
        // ...

        // Filament Integration: canAccessPanel(), getFilamentAvatarUrl() (Implement as before)
        public function canAccessPanel(Panel $panel): bool { /* ... */ }
        public function getFilamentAvatarUrl(): ?string { return $this->avatar_url; }

        // Model Boot Logic: Set default AccountState (Implement as before)
        protected static function booted(): void { /* ... */ }
    }
    // Remember to fill in method bodies based on previous plan details.
```

*   **Verification:** Code compiles. `name` accessor added and appended. `getSlugOptions` updated.

### 5.10. Understanding Key Concepts: Factories & Seeders

*(Explanation remains the same as previous version)*

### 5.11. Milestone 1.7: Updating the `UserFactory`

Update factory for name components.

*   **Action:** Open `database/factories/UserFactory.php`.

*   **File (`database/factories/UserFactory.php` - Update Existing):** *(Code almost identical to previous plan's Milestone 5.11 - ensure `given_name`, `family_name` are generated, `name` field is *not* set directly, default state `Active::class`)*.

```php
    public function definition(): array {
        $firstName = fake()->firstName(); $lastName = fake()->lastName();
        return [
            'given_name' => $firstName,
            'family_name' => $lastName,
            'other_names' => null,
            // 'name' => $firstName . ' ' . $lastName, // DO NOT set 'name' directly
            'email' => fake()->unique()->safeEmail(),
            // ... rest of fields as before (password, state, nulls for others) ...
        ];
    }
    // ... unverified() and 2FA states ...
```

*   **Verification:** Tinker `User::factory()->create()`. Verify `given_name`, `family_name` populated, `name` might be null in DB (but accessor `$user->name` works).

### 5.12. Milestone 1.8: Creating `UserSeeder` and `TeamSeeder`

Seed initial data.

*   **Action:** Create/Update Seeders.
*   **File (`database/seeders/UserSeeder.php`):** *(Modify to use `given_name`, `family_name` for Admin user, rely on factory for others)*.
*   **File (`database/seeders/TeamSeeder.php`):** *(No changes needed from previous plan)*.

*   **Verification:** Run seeders in next step.

### 5.13. Milestone 1.9: Updating `DatabaseSeeder`

Call specific seeders.

*   **Action:** Update `database/seeders/DatabaseSeeder.php`. *(Ensure `UserSeeder`, `TeamSeeder`, `PermissionSeeder` (later) are called)*.
*   **Verification:** Run `php artisan migrate:fresh --seed`. Check database tables.

### 5.14. Understanding Key Concepts: The Service Layer

*(Explanation remains the same as previous version)*

### 5.15. Milestone 1.10: Creating a `BaseService`

*(Action and code identical to previous version - create `app/Services/BaseService.php`)*.

### 5.16. Milestone 1.11: Initial Filament Resource Setup (User & Team)

Configure basic Filament admin views.

*   **Action 1:** Generate Resources (`php artisan make:filament-resource User --generate`, `... Team --generate`) if they don't exist or need regenerating.
*   **Action 2:** Customize `UserResource`.

    *   **File (`app/Filament/Resources/UserResource.php`):** *(Implement `form()` and `table()` as per previous plan's Milestone 5.16. Ensure form uses `given_name`, `family_name`, `other_names`. Table displays these and uses `account_state` badge.)*
*   **Action 3:** Customize `TeamResource`.

    *   **File (`app/Filament/Resources/TeamResource.php`):** *(Implement `form()` (name, desc, owner Select, parent Select) and `table()` (name, owner, parent) as per previous plan's Milestone 5.16)*.

*   **Verification:** Check `/admin/users` and `/admin/teams`. View/Edit/Create work. Name components are used.

### 5.17. Milestone 1.12: Phase 1 Git Commit

Save core models, architecture, and initial admin setup.

*   **Action:** Commit changes.

```bash
    git add .
    git commit -m "Phase 1: Implement core User(name refactor)/Team models, Traits, Migrations, Factory, Seeders, BaseService, Initial Filament Resources"

```

*   **Verification:** `git log`, `git status`.

**Phase 1 Complete!** Database structure, models (including name refactor), core traits, seed data, service base, and basic Filament admin interfaces are established.

--- END SECTION: Phase 1: Building the Core Models & Architecture ---

--- START SECTION: Phase 2: Authentication, Profile Basics & State Machine ---

## 6. Phase 2: Authentication, Profile Basics & State Machine

**Goal:** Implement core user authentication features (Login, Register, Email Verification, 2FA) leveraging Fortify and the Livewire Starter Kit. Build basic profile management (name components, email, avatar) for both the user-facing Livewire/Volt stack and the Filament admin panel. Implement the User Account State Machine using `spatie/laravel-model-states`.

This phase focuses on establishing the core user account functionality and security layer.

### 6.1. Understanding Key Concepts: Laravel Fortify & Authentication

*(Explanation remains the same - Fortify provides backend logic, the Livewire Starter Kit provides the UI interacting with Fortify.)*

### 6.2. Milestone 2.1: Configuring Fortify Features

Ensure Fortify (installed via the starter kit) has the necessary features enabled.

*   **Action:** Verify `config/fortify.php` (created by starter kit).

*   **File (`config/fortify.php` - Verify `features` section):** *(Ensure registration, resetPasswords, emailVerification, updateProfileInformation, updatePasswords, twoFactorAuthentication are enabled, same as previous plan)*.

*   **Verification:** `php artisan route:list | grep -E 'login|register|password|verify|two-factor|user-'` shows expected routes.

### 6.3. Understanding Key Concepts: Enums & State Machines

*(Explanation remains the same - Enums for type-safe states, State Machines for managing transitions via `spatie/laravel-model-states`)*.

### 6.4. Milestone 2.2: Defining the `AccountStatus` Enum

Define possible account statuses using a PHP Enum, including Filament integration.

*   **Action:** Create `app/Enums/AccountStatus.php`.

*   **File (`app/Enums/AccountStatus.php`):** *(Code identical to previous plan's Milestone 6.4 - implements `HasLabel`, `HasColor`, `HasIcon` for Filament, keeps original helper methods)*. Remember to add translations to `lang/xx/messages.php`.

*   **Verification:** File exists, implements interfaces. Translations added.

### 6.5. Milestone 2.3: Creating the Account State Machine Classes

Implement the state machine using `spatie/laravel-model-states`.

*   **Action 1:** Create directory `app/States/User`.
*   **Action 2:** Create base state `app/States/User/AccountState.php`. *(Code identical to previous plan's Milestone 6.5)*.
*   **Action 3:** Create concrete states `PendingValidation.php`, `Active.php`, `Suspended.php`, `Deactivated.php` in `app/States/User/`. *(Code identical to previous plan's Milestone 6.5)*.
*   **Action 4:** Create transition action `app/Actions/Users/ValidateEmailTransition.php`. *(Code identical to previous plan's Milestone 6.5)*.
*   **Action 5:** Ensure `User` model uses `HasStates` trait and has `account_state` in `$casts`. (Done in Phase 1).

*   **Verification:** Files exist, code correct. User model configured.

### 6.6. Understanding Key Concepts: Email Verification Flow

*(Explanation remains the same - Registration -> Notification (Signed URL) -> Click -> Verify Route -> Controller (`markEmailAsVerified`) -> State Transition -> Redirect)*.

### 6.7. Milestone 2.4: Integrating State Machine with Email Verification

Modify the starter kit's `VerifyEmailController` to transition the user state.

*   **Action:** Modify `app/Http/Controllers/Auth/VerifyEmailController.php` (created by starter kit).

*   **File (`app/Http/Controllers/Auth/VerifyEmailController.php` - Modify `__invoke`):** *(Code identical to previous plan's Milestone 6.7 - adds the state transition logic after `markEmailAsVerified()` succeeds)*.

*   **Verification:** Test registration/verification flow. Check DB for `email_verified_at` timestamp and `account_state` changing from `pending_validation` to `active`.

### 6.8. Understanding Key Concepts: Two-Factor Authentication (2FA)

*(Explanation remains the same - Fortify handles backend logic for enable, QR/secret display, confirmation, login challenge, disable. We build the UI.)*

### 6.9. Milestone 2.5: Implementing the 2FA UI [UI]

Create/modify the frontend components for managing and using 2FA.

#### 6.9.1. 2FA Management Form (Profile - Livewire/Volt)

*   **Component:** The Livewire Starter Kit typically provides a component for this at `resources/views/profile/partials/update-two-factor-authentication-form.blade.php` (likely a Volt SFC).
*   **Action:** Review and ensure this component correctly implements the logic described in the previous plan's Milestone 6.9.1:
    *   Uses Livewire state properties (`enabling`, `confirming`, etc.).
    *   Calls Fortify endpoints (`/user/two-factor-authentication`, `/user/confirmed-two-factor-authentication`, `/user/two-factor-recovery-codes`) via methods like `enableTwoFactorAuthentication`, `confirmTwoFactorAuthentication`, etc.
    *   Displays QR code, setup key, recovery codes, and confirmation input conditionally.
    *   Handles password confirmation via `x-password-confirm` if required by Fortify config.
*   **Verification:** Ensure the component provided by the starter kit functions correctly for enabling, confirming, viewing codes, regenerating codes, and disabling 2FA. Test the entire flow.

#### 6.9.2. 2FA Challenge Form (Login - Livewire/Volt)

*   **Component:** The Livewire Starter Kit provides the view at `resources/views/auth/two-factor-challenge.blade.php`.
*   **Action:** Verify this component (likely a Volt SFC) correctly implements the logic from the previous plan's Milestone 6.9.2:
    *   Provides input for `code` or `recovery_code`.
    *   Submits `POST` request to `/two-factor-challenge`.
    *   Displays errors on failure.
*   **Verification:** After enabling 2FA, log out and log back in. Verify you are redirected to this challenge page and can log in successfully using either an app code or a recovery code.

#### 6.9.3. 2FA Management (Admin - Filament)

*   **Component:** Modify `app/Filament/Resources/UserResource.php`.
*   **Action:** Add informational display and a disable action (handle with care).
    *   **Form (`form()` method):** Add read-only placeholders to show 2FA status.
    ```php
        Forms\Components\Section::make('Two-Factor Authentication')
            ->columns(2)
            ->schema([
                Forms\Components\Placeholder::make('two_factor_status')
                    ->label('Status')
                    ->content(fn(?User $record): string => $record?->two_factor_secret ? 'Enabled' : 'Disabled'),
                Forms\Components\Placeholder::make('two_factor_confirmed_at')
                    ->label('Confirmed At')
                    ->content(fn(?User $record): ?string => $record?->two_factor_confirmed_at?->diffForHumans() ?? '-'),
            ])->collapsible()->collapsed(),
    ```
    *   **Table (`table()` method):** Add columns to show status.
    ```php
        Tables\Columns\IconColumn::make('two_factor_confirmed_at')
             ->label('2FA Enabled')
             ->boolean()
             ->trueIcon('heroicon-o-lock-closed')
             ->falseIcon('heroicon-o-lock-open'),
    ```
    *   **Actions (`table()` method `->actions([...])`):** Add a custom action to disable 2FA for a user (requires careful authorization).
    ```php
        Tables\Actions\Action::make('disable2fa')
            ->label('Disable 2FA')
            ->icon('heroicon-o-lock-open')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (User $record) {
                // Directly clear 2FA fields (or call a dedicated service method)
                $record->forceFill([
                    'two_factor_secret' => null,
                    'two_factor_recovery_codes' => null,
                    'two_factor_confirmed_at' => null,
                ])->save();
                 \Filament\Notifications\Notification::make()
                    ->title('2FA Disabled successfully')
                    ->success()
                    ->send();
                 // Log this admin action!
                 activity()->performedOn($record)->causedBy(auth()->user())->log('Admin disabled 2FA');
            })
            // Only visible if 2FA is actually enabled for the target user AND the admin has permission
            ->visible(fn (User $record): bool => !is_null($record->two_factor_secret) && auth()->user()->can('disableUser2FA', $record)) // Assumes a policy check
            ->modalHeading('Disable Two-Factor Authentication')
            ->modalDescription('Are you sure? This will allow the user to log in without a 2FA code.')
            ->modalSubmitActionLabel('Yes, Disable 2FA'),
    ```
    *   **Policy:** Add a `disableUser2FA(User $admin, User $targetUser)` method to `UserPolicy` (or a relevant policy) to control who can perform this action.

*   **Verification:** View users in Filament. Verify 2FA status display. If authorized, test the "Disable 2FA" action. Check user's 2FA fields in DB are cleared. Check activity log.

#### 6.9.4. 2FA UI (Conceptual - Inertia/React)

*   Adapt the components provided by the **React Starter Kit** (which also uses Fortify). Use `useForm` and Axios/fetch to interact with Fortify endpoints. Manage UI state based on responses.

#### 6.9.5. 2FA UI (Conceptual - Inertia/Vue)

*   Adapt the components provided by the **Vue Starter Kit**. Use `useForm` and Axios/fetch to interact with Fortify endpoints. Manage UI state.

### 6.10. Milestone 2.6: Implementing Profile Information UI [UI]

Adapt profile forms for name components.

#### 6.10.1. Profile Info Backend (Controller & Request)

*   **Action:** Ensure `ProfileController` (`update` method) and `ProfileUpdateRequest` (`rules` method) are correctly handling `given_name`, `family_name`, `other_names`, and email validation/update logic. *(Code identical to previous plan's Milestone 6.10.1)*.

#### 6.10.2. Profile Info UI (Livewire/Volt)

*   **Component:** Modify `resources/views/profile/partials/update-profile-information-form.blade.php` (provided by starter kit).
*   **Action:** *(Adapt code from previous plan's Milestone 6.10.2)*
    *   Change form inputs from `name` to `given_name`, `family_name`, `other_names`.
    *   Update `wire:model` bindings.
    *   Update validation rules within the component's `updateProfileInformation` method to match the `ProfileUpdateRequest`.
    *   Keep email verification logic.

*   **Verification:** Test updating name components and email via the `/profile` page. Check database and email verification flow if email changed.

#### 6.10.3. Profile Info UI (Filament - User Resource Form)

*   **Component:** `app/Filament/Resources/UserResource.php`.
*   **Action:** We already configured the form in Phase 1 (Milestone 5.16) to use separate inputs for name components, email, and handle password updates. No further changes needed here for basic profile info.

*   **Verification:** Test editing user details (names, email) via the Filament User resource form.

#### 6.10.4. Profile Info UI (Conceptual - Inertia/React)

*   Adapt the `UpdateProfileInformationForm.jsx` component provided by the React Starter Kit. Change the `name` input to separate inputs for `given_name`, `family_name`, `other_names`, updating the `useForm` hook accordingly. Ensure it `PATCH`es to the correct `profile.update` route.

#### 6.10.5. Profile Info UI (Conceptual - Inertia/Vue)

*   Adapt the `UpdateProfileInformationForm.vue` component provided by the Vue Starter Kit. Change `name` input to separate inputs (`v-model`) for `given_name`, `family_name`, `other_names`. Update `useForm` and ensure it `PATCH`es correctly.

### 6.11. Understanding Key Concepts: File Uploads & `spatie/laravel-medialibrary`

*(Explanation remains the same - Media Library handles storage, DB records, conversions for model file attachments.)*

### 6.12. Milestone 2.7: Implementing Avatar Upload (Backend)

Create route and controller using `spatie/laravel-medialibrary`.

*   **Action 1:** Create `Profile/AvatarController`. *(Identical to previous plan's Milestone 6.12)*.
*   **Action 2:** Define route `POST /user/avatar` -> `AvatarController`. *(Identical to previous plan's Milestone 6.12)*.
*   **Action 3:** Implement `AvatarController::__invoke` method (validate file, use `addMediaFromRequest`). *(Identical to previous plan's Milestone 6.12)*.
*   **Action 4:** Ensure `User` model has `registerMediaCollections`. (Done in Phase 1).

*   **Verification:** Backend route and logic exist.

### 6.13. Milestone 2.8: Implementing Avatar Upload UI [UI]

Build frontend components for avatar upload.

#### 6.13.1. Avatar Upload UI (Livewire/Volt)

*   **Component:** Create Volt SFC `resources/views/profile/partials/update-avatar-form.blade.php`. Include in `profile/edit.blade.php`.
*   **Logic & Template:** *(Implement as per previous plan's Milestone 6.13.1)*
    *   `use WithFileUploads`.
    *   Properties `$avatar`, `$currentAvatarUrl`.
    *   `mount()` sets initial URL.
    *   `updateAvatar()` validates, calls `addMedia()`, refreshes URL, dispatches event.
    *   Template shows preview, file input (`wire:model`), save button.

*   **Verification:** Test uploading via `/profile` page. Check `media` table and storage. Verify preview/display updates.

#### 6.13.2. Avatar Upload UI (Filament - User Resource Form)

*   **Component:** Modify `app/Filament/Resources/UserResource.php`.
*   **Action:** Add `SpatieMediaLibraryFileUpload` to form and `SpatieMediaLibraryImageColumn` to table. *(Code identical to previous plan's Milestone 6.13.2)*.

*   **Verification:** Test uploading/displaying avatars via the Filament User resource.

#### 6.13.3. Avatar Upload UI (Conceptual - Inertia/React)

*   Create `Pages/Profile/Partials/UpdateAvatarForm.jsx`. Use `useForm`, hidden file input, `useState` for preview. `POST` to `/user/avatar`. Inertia handles multipart. Update display (might require page reload/event).

#### 6.13.4. Avatar Upload UI (Conceptual - Inertia/Vue)

*   Create `Pages/Profile/Partials/UpdateAvatarForm.vue`. Use `useForm`, hidden file input, `ref` for preview. `POST` to `/user/avatar`. Update display.

### 6.14. Understanding Key Concepts: Dependency Injection & Service Providers

*(Explanation remains the same - Service Container manages DI, Service Providers configure bindings)*.

### 6.15. Milestone 2.9: Creating `UserService` (Initial Version)

Create service for user creation logic.

*   **Action:** Create `app/Services/UserService.php`.
*   **File (`app/Services/UserService.php`):** *(Code identical to previous plan's Milestone 6.15)*.

*   **Verification:** File exists.

### 6.16. Milestone 2.10: Customizing Fortify's User Creation

Tell Fortify (via the starter kit) to use our `UserService`.

*   **Action 1:** Create custom Fortify Action `app/Actions/Fortify/CreateNewUser.php`. *(Code identical to previous plan's Milestone 6.16 - implements contract, injects service, validates, calls service)*.
*   **Action 2:** Bind action in `AppServiceProvider`. *(Code identical to previous plan's Milestone 6.16)*.

*   **Verification:** Test registration via UI (`/register`). Check user created correctly (state, name components), event dispatched.

### 6.17. Understanding Key Concepts: Events & Listeners

*(Explanation remains the same - Decoupling via Events (announcements) and Listeners (handlers), optional queueing)*.

### 6.18. Milestone 2.11: Defining Initial Events & Listeners

Generate and implement Event/Listener classes for user lifecycle.

*   **Action:** Generate Events (`UserRegistered`, `AccountActivated`, `AccountSuspended`) and queued Listeners (`SendWelcomeEmail`, `LogRegistrationActivity`, `NotifyAdminOnActivation`, `LogAccountSuspensionActivity`). *(Commands identical to previous plan's Milestone 6.18)*.
*   **Action:** Implement Event classes (add public properties). *(Identical to previous plan's Milestone 6.18)*.
*   **Action:** Implement Listener `handle()` methods (send mail, log activity, send notification). Create associated Mailables/Notifications. *(Identical to previous plan's Milestone 6.18)*.

*   **Verification:** Files exist, code implemented. Mailables/Notifications created.

### 6.19. Milestone 2.12: Registering Events & Listeners

Map Events to Listeners in `EventServiceProvider`.

*   **Action:** Verify/update `$listen` array in `app/Providers/EventServiceProvider.php`. *(Ensure mappings exist as per previous plan's Milestone 6.19)*.

*   **Verification:** Event system configured.

### 6.20. Milestone 2.13: Phase 2 Git Commit

Core authentication, profile, state machine, events/listeners complete.

*   **Action:** Commit changes.

```bash
    git add .
    git commit -m "Phase 2: Implement Auth (Fortify/LivewireKit), 2FA, State Machine, Profile/Avatar (Livewire/Filament), UserService, Events/Listeners"
```

*   **Verification:** `git log`, `git status`.

**Phase 2 Complete!** User accounts are functional with core security features, profile management (via Livewire and Filament), and a state machine integrated with email verification. Foundational event system is in place.

--- END SECTION: Phase 2: Authentication, Profile Basics & State Machine ---

--- START SECTION: Phase 3: Implementing Teams and Permissions ---

## 7. Phase 3: Implementing Teams and Permissions

**Goal:** Introduce Teams, allow users to belong to multiple teams with specific roles within each, establish team hierarchy, and implement fine-grained access control using `spatie/laravel-permission`. Provide UIs for team management in Livewire/Volt and Filament.

This phase builds the core collaboration structure and integrates team-based authorization.

### 7.1. Understanding Key Concepts: `spatie/laravel-permission` & Team Scoping

*(Explanation remains the same - `spatie/laravel-permission` handles Roles/Permissions. Enabling `teams` feature scopes these assignments to specific Team model instances via a `team_id` foreign key.)*

### 7.2. Milestone 3.1: Configuring `spatie/laravel-permission` for Teams

Enable team features and add the `team_id` column to pivot tables.

*   **Action 1:** Configure `config/permission.php` (published Phase 0).

    *   **File (`config/permission.php` - Modify):** *(Set `'teams' => true`, `'team_model' => Team::class`, ensure `'column_names.team_foreign_key' => 'team_id'`. Code identical to previous plan's Milestone 7.2)*.

*   **Action 2:** Create migration to add `team_id` column.

```bash
    php artisan make:migration add_team_foreign_key_to_permission_tables --table=model_has_roles
```
    *   **File (`database/migrations/..._add_team_foreign_key_to_permission_tables.php`):** *(Code identical to previous plan's Milestone 7.2 - adds nullable `team_id` column to `model_has_permissions` & `model_has_roles`, includes optional primary key update notes)*.

*   **Action 3:** Run migration.

```bash
    php artisan migrate
```

*   **Verification:** Config updated. Migration runs. `team_id` column exists in `model_has_roles`, `model_has_permissions`.

### 7.3. Milestone 3.2: Creating the `PermissionSeeder`

Define Roles, Permissions, and seed initial team-scoped assignments.

*   **Action 1:** Create seeder.

```bash
    php artisan make:seeder PermissionSeeder
```

*   **File (`database/seeders/PermissionSeeder.php`):** *(Code identical to previous plan's Milestone 7.3 - resets cache, creates Permissions (`view team dashboard`, `manage team members`, etc.), creates Roles (`Team Owner`, `Editor`, `Member`), assigns Permissions to Roles globally, assigns Roles to users *within specific teams* using `$user->assignRole($role, $team)`)*.

*   **Action 2:** Add `PermissionSeeder` to `DatabaseSeeder`.

    *   **File (`database/seeders/DatabaseSeeder.php` - Update `run()`):**
    ```php
        public function run(): void {
            $this->call([ UserSeeder::class, TeamSeeder::class, PermissionSeeder::class, ]);
        }
    ```

*   **Verification:** Run `php artisan migrate:fresh --seed`. Check `permissions`, `roles`, `role_has_permissions`. Check `model_has_roles` has records with correct `user_id`, `role_id`, and populated `team_id`.

### 7.4. Milestone 3.3: Creating the `TeamService`

Create the service class for team management logic.

*   **Action:** Create `app/Services/TeamService.php`.

*   **File (`app/Services/TeamService.php`):** *(Code identical to previous plan's Milestone 7.4 - implements `createTeam` (assigns owner role), `addUserToTeam`, `removeUserFromTeam`, `switchUserTeam`. Placeholder events created: `TeamCreated`, `UserAddedToTeam`, `UserRemovedFromTeam`, `CurrentTeamSwitched`)*.
    *   *(Remember to create events: `php artisan make:event Team/EventName`)*

*   **Verification:** File exists, code compiles. Placeholder events created.

### 7.5. Understanding Key Concepts: Resource Controllers & Authorization (Policies)

*(Explanation remains the same - Resource Controllers for CRUD conventions, Policies for model authorization)*.

### 7.6. Milestone 3.4: Setting up Team Management Backend (Routes, Controllers, Policy)

Create routes, controllers, and policy for managing teams.

*   **Action 1:** Generate Controllers and Policy. *(Identical to previous plan)*.

```bash
    php artisan make:controller TeamController --model=Team --resource
    php artisan make:controller TeamMemberController --model=Team
    php artisan make:controller CurrentTeamController --invokable
    php artisan make:policy TeamPolicy --model=Team
```

*   **Action 2:** Define routes in `routes/web.php` (inside `auth`, `verified` group). *(Code identical to previous plan's Milestone 7.6)*.

*   **Action 3:** Implement `TeamPolicy`.

    *   **File (`app/Policies/TeamPolicy.php`):** *(Code identical to previous plan's Milestone 7.6 - implements `before`, `viewAny`, `view`, `create`, `update`, `delete`, `addMember`, `updateMemberRole`, `removeMember`, plus placeholders `viewChat`, `createChatMessage`. Uses `$user->belongsToTeam()`, `ownsTeam()`, `hasTeamPermission()`)*.

*   **Action 4:** Register `TeamPolicy` in `AuthServiceProvider`. *(Identical to previous plan)*.

*   **Action 5:** Implement Controller method skeletons.
    *   **Modify `TeamController`:** Ensure methods return appropriate responses for Livewire/Volt. Instead of Inertia responses, return Blade views or handle logic within Livewire page components directly. Use Form Requests (`StoreTeamRequest`, `UpdateTeamRequest`).
    ```php
        // Example TeamController@index (if NOT using Livewire Page component)
        // public function index() {
        //     $this->authorize('viewAny', Team::class); // Handled by authorizeResource
        //     $teams = Auth::user()->teams()->with('owner')->paginate(15);
        //     return view('teams.index', ['teams' => $teams]); // Return Blade view
        // }
        // Example TeamController@show (if NOT using Livewire Page component)
        // public function show(Team $team) {
        //     $this->authorize('view', $team); // Handled by authorizeResource
        //     $team->load(['owner', 'users', 'parent', 'children']);
        //     return view('teams.show', ['team' => $team]);
        // }
        // Store/Update/Destroy methods will use TeamService and redirect.
    ```
    *   `TeamMemberController`: Methods validate, call `TeamService`, and return `RedirectResponse` with success/error messages. *(Skeleton identical to previous plan)*.
    *   `CurrentTeamController`: Method validates, calls `TeamService`, returns `RedirectResponse`. *(Skeleton identical to previous plan)*.
    *   *(Create Form Requests: `php artisan make:request StoreTeamRequest`, `UpdateTeamRequest`)*.

*   **Verification:** Routes exist. Policy registered. Controllers/Policy have skeletons. Form Requests created.

### 7.7. Milestone 3.5: Implementing Team Management UI [UI]

Build frontend interfaces for team management.

#### 7.7.1. Team List & Creation UI (Livewire/Volt)

*   **Components:**
    *   Livewire Page Component: `php artisan make:livewire Teams/ListTeams` -> `app/Livewire/Teams/ListTeams.php` & `resources/views/livewire/teams/list-teams.blade.php`.
    *   Livewire Page Component: `php artisan make:livewire Teams/CreateTeam` -> `app/Livewire/Teams/CreateTeam.php` & `resources/views/livewire/teams/create-team.blade.php`.
*   **Route (`routes/web.php`):** Point `/teams` and `/teams/create` routes to these Livewire components.
    ```php
        use App\Livewire\Teams\ListTeams;
        use App\Livewire\Teams\CreateTeam;
        // Replace Route::resource('teams', ...) parts if handling fully in Livewire pages
        Route::get('/teams', ListTeams::class)->name('teams.index');
        Route::get('/teams/create', CreateTeam::class)->name('teams.create');
        // Keep POST /teams route pointing to TeamController@store
        Route::post('/teams', [App\Http\Controllers\TeamController::class, 'store'])->name('teams.store');
        // Keep other team routes pointing to TeamController for now
        Route::get('/teams/{team}', [App\Http\Controllers\TeamController::class, 'show'])->name('teams.show'); // Or use Livewire page
        Route::get('/teams/{team}/edit', [App\Http\Controllers\TeamController::class, 'edit'])->name('teams.edit'); // Or use Livewire page
        Route::put('/teams/{team}', [App\Http\Controllers\TeamController::class, 'update'])->name('teams.update');
        Route::delete('/teams/{team}', [App\Http\Controllers\TeamController::class, 'destroy'])->name('teams.destroy');
        // Member routes remain as before
    ```
*   **Logic (`ListTeams.php`):**
    ```php
    // app/Livewire/Teams/ListTeams.php
    use Livewire\Component;
    use Illuminate\Support\Facades\Auth;
    use Livewire\WithPagination; // Use pagination trait

    class ListTeams extends Component {
        use WithPagination;
        public function render() {
            $teams = Auth::user()->teams()->with('owner')->paginate(15);
            return view('livewire.teams.list-teams', ['teams' => $teams])
                   ->layout('layouts.app'); // Use app layout
        }
    }
    ```
*   **Template (`list-teams.blade.php`):** Display paginated teams, link to show/create routes.
*   **Logic (`CreateTeam.php`):** Includes a form (can be inline or separate Volt component).
    ```php
    // app/Livewire/Teams/CreateTeam.php
    use Livewire\Component;
    class CreateTeam extends Component {
        public function render() {
             return view('livewire.teams.create-team')->layout('layouts.app');
        }
    }
    ```*   **Template (`create-team.blade.php`):** Include the form.
*   **Form Component (Volt SFC: `livewire/teams/create-team-form.blade.php`):**
    ```php
    // resources/views/livewire/teams/create-team-form.blade.php
    <?php
    use Livewire\Volt\Component;
    use App\Services\TeamService; // Inject service
    use Illuminate\Support\Facades\Auth;

    new class extends Component {
        public string $name = '';
        public ?string $description = null; // Nullable

        // Inject service via function (Volt specific)
        public function with(TeamService $teamService): array { return []; }

        public function saveTeam(TeamService $teamService): void {
             $validated = $this->validate([
                 'name' => ['required', 'string', 'max:255'],
                 'description' => ['nullable', 'string', 'max:65535'],
             ]);
             try {
                  $team = $teamService->createTeam(Auth::user(), $validated);
                  // Redirect after successful creation
                  $this->redirectRoute('teams.show', $team, navigate: true); // Use Volt navigate helper
             } catch (\Exception $e) {
                  // Add error handling, maybe dispatch browser event
                  session()->flash('error', 'Failed to create team.');
             }
        }
    }; ?>
    <form wire:submit="saveTeam">
        {{-- Name Input --}}
        <div>... <input type="text" wire:model="name"> ... @error('name') ... @enderror</div>
        {{-- Description Textarea --}}
        <div>... <textarea wire:model="description"></textarea> ... @error('description') ... @enderror</div>
        <button type="submit">Create Team</button>
    </form>
    ```

#### 7.7.2. Team Settings & Member Management UI (Livewire/Volt)

*   **Components:**
    *   Livewire Page Component: `php artisan make:livewire Teams/ShowTeam`
    *   Volt SFC: `resources/views/livewire/teams/update-team-form.blade.php`
    *   Volt SFC: `resources/views/livewire/teams/team-member-manager.blade.php`
*   **Route (`routes/web.php`):** Point `/teams/{team}` to `ShowTeam` component.
    ```php
        use App\Livewire\Teams\ShowTeam;
        Route::get('/teams/{team:ulid}', ShowTeam::class)->name('teams.show'); // Use ULID binding
    ```
*   **Logic (`ShowTeam.php`):**
    ```php
    // app/Livewire/Teams/ShowTeam.php
    use Livewire\Component;
    use App\Models\Team;
    use Illuminate\Support\Facades\Auth;
    use App\Services\TeamService; // Inject if needed for delete

    class ShowTeam extends Component {
        public Team $team; // Route model binding injects the Team

        public function mount(Team $team) {
             $this->authorize('view', $team); // Authorize access
             $this->team = $team->load(['owner', 'parent', 'children']); // Eager load
        }
        public function deleteTeam(TeamService $teamService) {
             $this->authorize('delete', $this->team);
             try {
                  // teamService->deleteTeam(...) // Implement delete in service
                  $this->team->delete(); // Simple soft delete for now
                  session()->flash('status', 'Team deleted.');
                  $this->redirectRoute('teams.index', navigate: true);
             } catch (\Exception $e) {
                 session()->flash('error', 'Failed to delete team.');
             }
        }
        public function render() {
             return view('livewire.teams.show-team')->layout('layouts.app');
        }
    }
    ```
*   **Template (`show-team.blade.php`):** Display team details. Conditionally include components based on permissions (`@can`).
    ```blade
    <div>
        <h1>{{ $team->name }}</h1>
        {{-- ... other details ... --}}

        @can('update', $team)
            <livewire:teams.update-team-form :team="$team" />
        @endcan

        <livewire:teams.team-member-manager :team="$team" />

        @can('delete', $team)
             <button wire:click="deleteTeam" wire:confirm="Are you sure you want to delete this team?">Delete Team</button>
        @endcan
    </div>
    ```*   **Component (`update-team-form.blade.php`):** Volt SFC similar to `create-team-form`, handles updates via PUT to `TeamController@update` or dedicated Livewire action.
*   **Component (`team-member-manager.blade.php`):** Volt SFC to list members, add (calls `TeamMemberController@store` or Livewire action calling `TeamService`), update role (calls `TeamMemberController@update` or LW action), remove (calls `TeamMemberController@destroy` or LW action). Uses `$team->users()->withPivot('role')...` to display roles. Needs list of available roles (`Role::pluck...`).

#### 7.7.3. Team Switcher UI (Livewire/Volt - Layout)

*   **Component:** Modify `resources/views/layouts/navigation.blade.php` (provided by starter kit).
*   **Logic:** *(Largely same as starter kit)*. Dropdown lists `Auth::user()->teams`. Each item triggers a form `submit()` that `PUT`s to `CurrentTeamController` route, sending the `team_id`. Page reloads to reflect change.

#### 7.7.4. Team & Member Management (Filament Resources)

*   **Component:** `app/Filament/Resources/TeamResource.php`.
    *   **Form:** Ensure owner/parent selects are configured.
    *   **Relation Manager:** Create/Configure `UsersRelationManager` as described in previous plan (Milestone 7.7.4) to attach/detach/edit roles of users within the team.
*   **Component:** `app/Filament/Resources/UserResource.php`.
    *   **Form:** Ensure `current_team_id` Select exists.
    *   **Relation Manager:** Create/Configure `TeamsRelationManager` as described in previous plan (Milestone 7.7.4) to show teams user belongs to and their role.

#### 7.7.5. Team Management UI (Conceptual - Inertia/React)

*   Use components (`Index`, `Create`, `Show`, `Edit`, `TeamMemberManager`). Fetch data via Axios from `TeamController`, `TeamMemberController`. Use `useForm` for forms. Send requests via Inertia/Axios. Team switcher requires `PUT` and likely page reload/context update.

#### 7.7.6. Team Management UI (Conceptual - Inertia/Vue)

*   Use components (`Index`, `Create`, `Show`, `Edit`, `TeamMemberManager`). Fetch data. Use `useForm`. Send requests. Team switcher requires `PUT` and reload/context update.

*   **Verification:** Test team creation, viewing, member add/edit/remove, team switching via Livewire/Volt UI. Test managing teams and member roles via Filament UI. Verify authorization rules are enforced.

### 7.8. Understanding Key Concepts: Middleware

*(Explanation remains the same)*

### 7.9. Milestone 3.6: Creating Optional Team Role Middleware

*(Actions and code identical to previous plan - create `EnsureUserHasTeamRole`, register alias `team_role`)*.

*   **Verification:** Test middleware protects routes based on user's role in their `currentTeam`.

### 7.10. Milestone 3.7: Phase 3 Git Commit

Save Teams and Permissions implementation.

*   **Action:** Commit changes.

```bash
    git add .
    git commit -m "Phase 3: Implement Teams, Spatie Permissions (team-scoped), TeamService, Team Mgmt Backend & UI (Livewire/Filament), Team Role Middleware"
```

*   **Verification:** `git log`, `git status`.

**Phase 3 Complete!** Team functionality with scoped roles and management UIs (Livewire/Filament) is now integrated.

--- END SECTION: Phase 3: Implementing Teams and Permissions ---

--- START SECTION: Phase 4: Real-time Foundation & Activity Logging ---

## 8. Phase 4: Real-time Foundation & Activity Logging

**Goal:** Establish the infrastructure for real-time communication using Laravel Reverb and Echo. Implement the backend logic for tracking user online/offline presence based on login/logout events and broadcast these changes. Refine activity logging to leverage listeners for better context.

This phase sets the stage for the interactive presence and chat UIs in Phase 5 by building the underlying real-time system.

### 8.1. Understanding Key Concepts: WebSockets, Reverb & Echo

*(Explanation remains the same as previous plan - WebSockets for persistent connections, Reverb as Laravel's WebSocket server, Broadcasting for sending server events, Echo as the frontend JS library for receiving events)*.

### 8.2. Milestone 4.1: Setting Up Laravel Reverb

Install, configure, and run the Reverb WebSocket server.

*   **Action 1:** Ensure Reverb `.env` variables ( `REVERB_APP_ID`, `_KEY`, `_SECRET`, `_HOST`, `_PORT`, `_SCHEME`) are set correctly (Done in Phase 0). Remember to generate secure random values for ID, Key, and Secret.
*   **Action 2:** Run Reverb install command (if not done during initial setup, though less common now).

```bash
    # This command installs config/reverb.php and potentially pusher-js/laravel-echo
    php artisan reverb:install
    # Run npm install again just in case
    npm install
```

*   **Action 3:** Start Reverb server in a **dedicated terminal**.

```bash
    # In a NEW terminal, navigate to your project root (ume-app)
    php artisan reverb:start --host=0.0.0.0 --port=8080 --debug
```

*   **Verification:** Reverb server starts without errors in its dedicated terminal, logging connection attempts. `config/reverb.php` exists.

### 8.3. Milestone 4.2: Configuring Laravel Echo (Backend & Frontend)

Configure Laravel broadcasting and the frontend Echo client.

*   **Action 1:** Configure backend `config/broadcasting.php`.

    *   **File (`config/broadcasting.php`):** *(Ensure `default` is 'reverb', `connections.reverb` block is correct, reading from `.env`. Code identical to previous plan's Milestone 8.3)*.

*   **Action 2:** Configure frontend `resources/js/bootstrap.js` (setup by starter kit, verify).

    *   **File (`resources/js/bootstrap.js`):** *(Ensure Echo is initialized with `broadcaster: 'reverb'`, correct host/port/key/scheme reading from `VITE_REVERB_*` env vars. Code identical to previous plan's Milestone 8.3)*.

*   **Action 3:** Ensure corresponding `VITE_REVERB_*` variables exist in `.env` (Done in Phase 0).
*   **Action 4:** Ensure JS libraries are installed (`laravel-echo`, `pusher-js`). Should be handled by `reverb:install` or starter kit setup. Run `npm install` if unsure.
*   **Action 5:** Compile assets / ensure Vite dev server is running (`npm run dev` or `build`).

*   **Verification:** With Reverb and Vite running, load the app. Check browser Dev Tools (Network > WS) for successful WebSocket connection. Check Reverb terminal for connection logs. Check browser console for Echo connection messages/errors.

### 8.4. Milestone 4.3: Implementing Presence Status Backend (Enum, Migration, Cast)

Add database fields and Enum to store user presence.

*   **Action 1:** Create `app/Enums/PresenceStatus.php`.

    *   **File (`app/Enums/PresenceStatus.php`):** *(Code identical to previous plan's Milestone 8.4 - defines Online, Offline, Away cases, helper methods `label`, `color`, `indicatorClass`)*.

*   **Action 2:** Create migration `add_presence_status_to_users_table`.

```bash
    php artisan make:migration add_presence_status_to_users_table --table=users
```
    *   **File (`database/migrations/..._add_presence_status_to_users_table.php`):** *(Code identical to previous plan's Milestone 8.4 - adds nullable string `presence_status` defaulting to 'offline', nullable timestamp `last_seen_at`)*.

*   **Action 3:** Run migration (`php artisan migrate`).
*   **Action 4:** Add casts to `User` model.

    *   **File (`app/Models/User.php` - Add to `$casts`):**
    ```php
         'presence_status' => \App\Enums\PresenceStatus::class,
         'last_seen_at' => 'datetime',
    ```

*   **Verification:** Migration runs. Columns exist. Casts added. Tinker shows `User::first()->presence_status` returns Enum instance.

### 8.5. Milestone 4.4: Creating the `PresenceChanged` Broadcast Event

Create the event that broadcasts presence updates.

*   **Action:** Create the event class.

```bash
    php artisan make:event User/PresenceChanged
```

*   **File (`app/Events/User/PresenceChanged.php` - Modify Generated):** *(Code identical to previous plan's Milestone 8.5 - implements `ShouldBroadcast`, sets `queue='broadcasts'`, defines `broadcastOn()` returning `PresenceChannel` array for user's top-level teams, `broadcastAs()`, `broadcastWith()` payload)*.

*   **Verification:** File exists, implements `ShouldBroadcast`, defines channels/payload correctly.

### 8.6. Milestone 4.5: Creating Login/Logout Presence Listeners

Create listeners for built-in auth events to update presence status and dispatch `PresenceChanged`.

*   **Action:** Create listener classes.

```bash
    php artisan make:listener Listeners/User/UpdatePresenceOnLogin --event=\Illuminate\Auth\Events\Login
    php artisan make:listener Listeners/User/UpdatePresenceOnLogout --event=\Illuminate\Auth\Events\Logout
```

*   **File (`app/Listeners/User/UpdatePresenceOnLogin.php`):** *(Code identical to previous plan's Milestone 8.6 - updates status to Online, `last_seen_at`, saves quietly, dispatches `PresenceChanged`)*.
*   **File (`app/Listeners/User/UpdatePresenceOnLogout.php`):** *(Code identical to previous plan's Milestone 8.6 - updates status to Offline, saves quietly, dispatches `PresenceChanged`)*.
*   **Action:** Ensure listeners registered for `Login` and `Logout` events in `EventServiceProvider`. *(Verify mappings from previous plan's Milestone 8.6)*.

*   **Verification:** Log in/out with Reverb and queue worker (`broadcasts` queue) running. Check `users` table updates. Check Reverb logs for broadcasting activity on presence channels.

### 8.7. Understanding Key Concepts: Contextual Activity Logging

*(Explanation remains the same - logging from Listeners provides better context than model traits alone)*.

### 8.8. Milestone 4.6: Implementing Activity Logging via Listeners

Ensure key domain events trigger listeners that log meaningful activity.

*   **Action 1:** Review/Refine `LogRegistrationActivity` listener (Milestone 6.18).
*   **Action 2:** Review/Refine `LogAccountSuspensionActivity` listener (Milestone 6.18).
*   **Action 3:** Create/Implement `LogTeamCreationActivity` listener (Milestone 8.8).
    *   Ensure `TeamCreated` event exists (created in Phase 3).
    ```bash
        php artisan make:listener Listeners/Team/LogTeamCreationActivity --queued --event=Team/TeamCreated
    ```
    *   Implement `handle()` using `activity()->performedOn($event->team)...`. Register listener in `EventServiceProvider`.
*   **Action 4:** Create/Implement listeners for `UserAddedToTeam`, `UserRemovedFromTeam`, etc., logging relevant context (actor, target user, team). Register them.

*   **Verification:** Trigger events (register, suspend, create team, add/remove member). Ensure queue worker (`logging` queue) runs. Check `activity_log` table for detailed log entries.

### 8.9. Milestone 4.7: Phase 4 Git Commit

Real-time foundation and contextual logging are set up.

*   **Action:** Commit changes.

```bash
    git add .
    git commit -m "Phase 4: Setup Reverb/Echo, Implement Presence Backend & Broadcast, Contextual Activity Logging via Listeners"
```

*   **Verification:** `git log`, `git status`.

**Phase 4 Complete!** The real-time infrastructure is operational, user presence is tracked and broadcast on the backend, and activity logging provides richer context.

--- END SECTION: Phase 4: Real-time Foundation & Activity Logging ---

--- START SECTION: Phase 5: Advanced Features & Real-time Implementation ---

## 9. Phase 5: Advanced Features & Real-time Implementation

**Goal:** Implement Impersonation, Comments, User Settings, and Search, including necessary UI elements (Livewire/Volt, Filament). Build the **frontend UI** for real-time Presence and Chat features, connecting to the Phase 4 backend. Finalize API authentication setup.

This phase integrates the remaining features and brings the real-time components to life for the user.

### 9.1. Milestone 5.1: Implementing Impersonation Feature [UI]

*(Implementation steps are identical to previous plan's Milestone 9.1, covering backend controller/routes and frontend UI for Livewire/Volt, Filament, and conceptual Inertia.)*

*   **9.1.1. Backend Setup:** Create `UserImpersonationController` (`start`, `leave` methods using `ImpersonateManager`), define routes (`impersonate.start`, `impersonate.leave` with middleware).
*   **9.1.2. Frontend Button (Livewire/Volt):** Add conditional "Impersonate" button/link in user lists pointing to `impersonate.start`.
*   **9.1.3. Frontend Banner (Livewire/Volt - Layout):** Use `@impersonating` Blade directive in `layouts/app.blade.php` to show banner and "Leave" link.
*   **9.1.4. Frontend UI (Filament):** Add custom "Impersonate" `Action` to `UserResource` table. Register view hook in `AdminPanelProvider` to render `@impersonating` banner.
*   **9.1.5. Frontend UI (Conceptual - Inertia/React):** Button links to route. Banner checks shared `is_impersonating` prop.
*   **9.1.6. Frontend UI (Conceptual - Inertia/Vue):** Button links to route. Banner checks shared `is_impersonating` prop (`v-if`).

*   **Verification:** Test impersonating/leaving via Livewire/Volt and Filament interfaces. Check banner display.

### 9.2. Milestone 5.2: Implementing Comments Feature [UI]

*(Implementation steps are identical to previous plan's Milestone 9.2, using `spatie/laravel-comments`)*.

*   **9.2.1. Backend Setup:** Prepare commentable model (e.g., `Post` implementing `Commentable`, using `InteractsWithComments` trait). Create routes (`GET /posts/{post}`, `POST /posts/{post}/comments`). Implement `PostController@show` (load comments) and `PostCommentController@store` (use `Auth::user()->comment(...)`).
*   **9.2.2. Frontend UI (Livewire/Volt):** Create page component for `posts.show`. Include nested components for comments list (refreshes on event) and comment form (`wire:submit` calls action using `Auth::user()->comment(...)`).
*   **9.2.3. Frontend UI (Filament):** Create `CommentsRelationManager` for the `PostResource` (or other commentable resource). Configure form/table for managing comments.
*   **9.2.4. Frontend UI (Conceptual - Inertia/React):** Display comments. Form uses `useForm` to `POST` to comment store route.
*   **9.2.5. Frontend UI (Conceptual - Inertia/Vue):** Display comments. Form uses `useForm` (`v-model`) to `POST`.

*   **Verification:** Add/view comments via Livewire/Volt. Manage comments via Filament Relation Manager.

### 9.3. Milestone 5.3: Implementing User Settings Feature [UI]

*(Implementation steps are identical to previous plan's Milestone 9.3, using `spatie/laravel-settings`)*.

*   **9.3.1. Backend Setup:** Define `app/Settings/UserSettings.php` (with `locale`, `timezone`, etc.). Create route (`PUT /user/settings`) and `UserSettingsController` (`__invoke` validates, updates `Auth::user()->settings->property`, saves).
*   **9.3.2. Frontend UI (Livewire/Volt):** Create Volt SFC `update-settings-form.blade.php`. `mount()` loads settings. `saveSettings()` validates, sends `PUT` request (or calls controller action). Inputs use `wire:model`.
*   **9.3.3. Frontend UI (Filament):** Create custom `Settings` page (`php artisan make:filament-page Settings`). Use `HasForms`, define form schema matching settings props. `mount()` loads settings, `save()` updates and saves `Auth::user()->settings`.
*   **9.3.4. Frontend UI (Conceptual - Inertia/React):** Form uses `useForm` initialized with settings. `onSubmit` sends `PUT` request.
*   **9.3.5. Frontend UI (Conceptual - Inertia/Vue):** Form uses `useForm`. Inputs use `v-model`. `@submit` sends `PUT` request.

*   **Verification:** Update settings via Livewire/Volt profile and Filament settings page. Verify changes persist. Check `settings` table.

### 9.4. Understanding Key Concepts: Full-Text Search with Scout & Typesense

*(Explanation remains the same)*.

### 9.5. Milestone 5.4: Implementing Search Backend (Scout Config, Model Setup, Indexing)

*(Implementation steps are identical to previous plan's Milestone 9.5)*.

*   Configure `.env` / `config/scout.php`.
*   Add `Searchable` trait and methods to `User`/`Team`.
*   **Define schema in Typesense server (external step).**
*   Run `php artisan scout:import ...`.
*   Ensure queue worker (`scout` queue) is running.

*   **Verification:** Schema created in Typesense. Import successful. Data appears in Typesense. Index syncs on create/update/delete.

### 9.6. Milestone 5.5: Implementing Search Frontend [UI]

Add UI for searching users.

#### 9.6.1. Backend Search Endpoint

*   **Action:** Create route (`GET /search/users`) and `SearchController@users` action. Uses `User::search($query)->paginate()`. Returns JSON (e.g., via `UserSearchResource`). *(Identical to previous plan's Milestone 9.6.1)*.

#### 9.6.2. Frontend Search UI (Livewire/Volt - Header)

*   **Component:** Volt SFC `livewire/search/global-search.blade.php`. Include in layout.
*   **Logic & Template:** *(Implement as per previous plan's Milestone 9.6.2)*.
    *   Properties `$query`, `$results`, `$showResults`.
    *   `updatedQuery()` (debounced) fetches results from API endpoint.
    *   Template: Input (`wire:model.live.debounce...`), results dropdown (`@if/@foreach`).

#### 9.6.3. Frontend Search UI (Filament - Global Search)

*   **Action:** Configure Filament's built-in global search (uses DB by default).
*   **Logic (`UserResource.php`, `TeamResource.php`):** *(Implement as per previous plan's Milestone 9.6.3)*.
    *   Set `$recordTitleAttribute`.
    *   Set `$globalSearchAttributes` (DB columns).
    *   *(Note: True Scout integration requires more advanced Filament customization or plugins)*.

#### 9.6.4. Frontend Search UI (Conceptual - Inertia/React)

*   Component fetches from API on debounced query change. Displays results dropdown.

#### 9.6.5. Frontend Search UI (Conceptual - Inertia/Vue)

*   Component fetches from API via `watch` (debounced). Displays results dropdown.

*   **Verification:** Search works via Livewire/Volt header and Filament global search (DB based). Results are relevant.

### 9.7. Understanding Key Concepts: Broadcasting Channels & Authorization

*(Explanation remains the same - Public/Private/Presence channels, Naming, Authorization via `routes/channels.php`)*.

### 9.8. Milestone 5.6: Defining Broadcast Channel Authorizations (`channels.php`)

Define authorization for presence and chat channels.

*   **Action:** Open `routes/channels.php`.

*   **File (`routes/channels.php`):** *(Code identical to previous plan's Milestone 9.8 - defines auth for `presence-team.{teamUlid}`, `chat.team.{teamUlid}`, `user.{userUlid}`)*.

*   **Verification:** Test subscriptions using browser console `Echo.join/private`. Check `/broadcasting/auth` requests succeed/fail appropriately.

### 9.9. Milestone 5.7: Implementing Real-time Presence UI [UI]

Display real-time online status indicators. **Render only when viewing a top-level team.**

#### 9.9.1. Presence UI: Livewire/Volt Implementation

*   **Component:** Modify component displaying team members (e.g., `livewire/teams/team-member-manager.blade.php`).
*   **Logic:** *(Implement as per previous plan's Milestone 9.9.1)*.
    *   Property `$memberPresence = []`. `public Team $team`.
    *   `getListeners()` subscribes to `echo-presence:presence-team.{teamUlid},here/joining/leaving` *only if* `$this->team->isTopLevel()`.
    *   Handlers update `$memberPresence` based on presence events.
    *   Template: Conditionally (`@if($team->isTopLevel())`) render indicators based on `$memberPresence`.

#### 9.9.2. Presence UI: Filament Implementation (Info Only)

*(Same as previous plan - difficult to implement real-time in standard Filament tables. Show non-real-time status badge instead)*.

#### 9.9.3. Presence UI: Inertia/React Implementation

*   **Component:** Modify component displaying team members.
*   **Logic:** *(Implement as per previous plan's Milestone 9.9.2)*.
    *   `useState` for `memberPresence`.
    *   `useEffect`: *Only if* `team.is_top_level` is true, `Echo.join(...)`. Listen for `here/joining/leaving`. Update state. Leave channel on unmount.
    *   Render: Conditionally render indicators based on state *only if* `team.is_top_level`.

#### 9.9.4. Presence UI: Inertia/Vue Implementation

*   **Component:** Modify component displaying team members.
*   **Logic:** *(Implement as per previous plan's Milestone 9.9.3)*.
    *   `reactive/ref` for `memberPresence`.
    *   `onMounted`: *Only if* `team.is_top_level`, `Echo.join(...)`. Listen, update state. `onUnmounted`: Leave channel.
    *   Template: Conditionally (`v-if="team.is_top_level"`) render indicators.

*   **Verification (Livewire/Volt):** Test presence indicators appear and update correctly *only* when viewing members of a top-level team. Test with multiple users logging in/out.

### 9.10. Milestone 5.8: Implementing Real-time Chat Backend (Model, Service, API, Event)

Set up backend components for restricted, real-time chat.

*   **Action 1:** Create `ChatMessage` Model & Migration. *(Identical to previous plan)*. Run `migrate`.
*   **Action 2:** Create `ChatMessageService` (implement `sendMessage`, `getMessages`, enforce top-level team check). *(Identical to previous plan)*.
*   **Action 3:** Create API Controller `Api/TeamChatController`, `ChatMessageResource`, `Chat/ChatMessageSent` Event. *(Identical to previous plan - Resource formats data, Event implements `ShouldBroadcast` on private team channel only if top-level, Controller uses Service/Resource, authorizes)*.
*   **Action 4:** Define API routes in `routes/api.php` (`GET/POST /teams/{team:ulid}/chat/messages`, apply `auth:sanctum`, enforce top-level team/membership). *(Identical to previous plan)*.
*   **Action 5:** Ensure `TeamPolicy` has `viewChat`, `createChatMessage` methods. (Done Phase 3).

*   **Verification:** Test API endpoints (`/api/teams/.../chat/messages`) via API client. Verify messages saved/retrieved, broadcasts trigger on correct channel (check Reverb logs), top-level team restriction enforced.

### 9.11. Milestone 5.9: Implementing Real-time Chat UI [UI]

Build the frontend chat interface. **Render only when viewing a top-level team.**

#### 9.11.1. Chat UI: Livewire/Volt Implementation

*   **Component:** Create Volt SFC `resources/views/livewire/chat/team-chat-box.blade.php`. Include conditionally (`@if($team->isTopLevel() && auth()->user()->can('viewChat', $team))`) in `livewire/teams/show-team.blade.php`.
*   **Logic & Template:** *(Implement as per previous plan's Milestone 9.11.1)*.
    *   Properties `$team`, `$messages` (load initial via service/API), `$newMessage`.
    *   `getListeners()` subscribes `echo-private:chat.team.{teamUlid},.chat.message.new` => `handleNewMessage`.
    *   `handleNewMessage()` prepends/appends to `$messages`. Scrolls.
    *   `sendMessage()` validates, calls service/API, clears input. Needs `@can('createChatMessage', $team)` check.
    *   Template: Scrollable list, input form (`wire:model`, `wire:submit`). Style own messages.

#### 9.11.2. Chat UI: Filament Implementation (Info Only)

*(Same as previous plan - real-time chat within standard Filament is complex. Not implemented here.)*

#### 9.11.3. Chat UI: Inertia/React Implementation

*   **Component:** Create `Chat/TeamChatBox.jsx`. Include conditionally (`if (team.is_top_level && canViewChat)`) in `Pages/Teams/Show.jsx`. (Pass `canViewChat` permission from controller).
*   **Logic:** *(Implement as per previous plan's Milestone 9.11.3)*.
    *   State: `messages`, `newMessage`, `pagination`, `isLoading`.
    *   `useEffect`: Fetch initial messages. `Echo.private(...)`. Listen for `.chat.message.new`, update state. Scroll. Leave channel on unmount.
    *   `sendMessage()`: Check permission (`canCreateChatMessage` prop). `axios.post`. Clear input.
    *   Render: List, input form.

#### 9.11.4. Chat UI: Inertia/Vue Implementation

*   **Component:** Create `Chat/TeamChatBox.vue`. Include conditionally (`v-if="team.is_top_level && canViewChat"`) in `Pages/Teams/Show.vue`.
*   **Logic:** *(Implement as per previous plan's Milestone 9.11.4)*.
    *   State: `messages`, etc.
    *   `onMounted`: Fetch initial. `Echo.private(...)`. Listen, update state. Scroll. `onUnmounted`: Leave channel.
    *   `sendMessage()`: Check permission. `axios.post`. Clear input.
    *   Template: List (`v-for`), input form (`v-model`, `@submit`).

*   **Verification (Livewire/Volt):** Test chat functions only in top-level teams. Send/receive messages instantly between users. Check permissions prevent unauthorized viewing/sending.

### 9.12. Understanding Key Concepts: API Authentication (Passport & Sanctum)

*(Explanation remains the same)*.

### 9.13. Milestone 5.10: Configuring API Authentication Guards

*(Action and verification identical to previous plan - ensure `config/auth.php` defines `web`, `sanctum`, `api` guards correctly)*.

### 9.14. Milestone 5.11: Setting Up Passport Routes

*(Action and verification identical to previous plan - run `passport:install --uuids` if needed, ensure `Passport::routes()` called in `AuthServiceProvider`)*.

### 9.15. Milestone 5.12: Phase 5 Git Commit

Advanced features and real-time UI complete.

*   **Action:** Commit changes.

```bash
    git add .
    git commit -m "Phase 5: Implement Impersonation, Comments, Settings, Search, Real-time Presence/Chat UI (Livewire/Filament/Conceptual), API Auth"
```

*   **Verification:** `git log`, `git status`.

**Phase 5 Complete!** The application now includes advanced features like impersonation, comments, settings, search, and functional real-time presence and chat UIs, along with configured API authentication.

--- END SECTION: Phase 5: Advanced Features & Real-time Implementation ---

--- START SECTION: Phase 6: Polishing, Testing & Deployment ---

## 10. Phase 6: Polishing, Testing & Deployment

**Goal:** Add final touches (i18n, feature flags), write comprehensive tests (Unit, Feature, Browser, Filament), perform performance checks, write documentation, set up backups, and prepare for deployment.

This phase ensures the application is robust, maintainable, well-documented, and ready for production.

### 10.1. Understanding Key Concepts: Internationalization (i18n)

*(Explanation remains the same)*.

### 10.2. Milestone 6.1: Implementing i18n (Backend)

*(Actions and verification identical to previous plan's Milestone 10.2 - configure locales in `config/app.php`, create language files `lang/en/`, `lang/es/`, replace hardcoded strings with `__()` in Blade/Volt/Filament, handle translations for Inertia)*.

### 10.3. Milestone 6.2: Implementing Locale Switching [UI]

Allow users to select their language.

#### 10.3.1. Backend Middleware Setup

*(Actions identical to previous plan's Milestone 10.3.1 - Add `locale` to `UserSettings`, create/register `SetLocale` middleware)*.

#### 10.3.2. Locale Switcher UI (Livewire/Volt)

*(Implementation identical to previous plan's Milestone 10.3.2 - Dropdown triggers action/route to update user setting and session)*.

#### 10.3.3. Locale Switching (Filament)

*(Explanation identical to previous plan's Milestone 10.3.3 - Filament uses app locale set by middleware; publish translations for Filament UI text)*.

#### 10.3.4. Locale Switcher UI (Conceptual - Inertia/React)

*(Conceptual implementation identical to previous plan's Milestone 10.3.4)*.

#### 10.3.5. Locale Switcher UI (Conceptual - Inertia/Vue)

*(Conceptual implementation identical to previous plan's Milestone 10.3.5)*.

*   **Verification:** Test switching locales via UI. Verify preference saved, app (Livewire/Filament) displays in selected language.

### 10.4. Understanding Key Concepts: Feature Flags (Pennant)

*(Explanation remains the same)*.

### 10.5. Milestone 6.3: Implementing Feature Flags

*(Actions identical to previous plan's Milestone 10.5 - install migration, define flag in `AppServiceProvider`, check flag (`Feature::active()`) in backend/frontend, manage state via Artisan commands)*.

*   **Verification:** Test toggling feature flag via Artisan commands. Verify controlled UI elements appear/disappear correctly.

### 10.6. Understanding Key Concepts: Testing in Laravel (Unit, Feature, Browser)

*(Explanation remains the same)*.

### 10.7. Milestone 6.4: Writing Tests (Examples - PestPHP, Filament)

*(Actions identical to previous plan's Milestone 10.7 - set up `.env.testing`, write example Feature, Unit, Dusk tests using Pest, write example Filament Resource test using `Livewire::test()`).*

*   **Focus:** Ensure tests cover:
    *   Auth flows (registration, login, 2FA challenge)
    *   State machine transitions (email verification)
    *   Team creation, member management, role assignment
    *   Core service logic (unit tests)
    *   Policy authorization checks
    *   Filament resource rendering and actions
    *   Basic Dusk browser interactions (login, profile update)

*   **Verification:** Run `php artisan test` and `php artisan dusk`. Ensure tests pass. Aim for reasonable coverage of critical paths.

### 10.8. Understanding Key Concepts: Performance Optimization

*(Explanation remains the same)*.

### 10.9. Milestone 6.5: Applying Performance Considerations

*(Actions identical to previous plan's Milestone 10.9 - review eager loading, add DB indexes, implement caching strategically, monitor queues/Horizon, use Telescope/Pulse locally)*.

*   **Verification:** Improved query counts, responsive local feel.

### 10.10. Milestone 6.6: Writing Documentation (README, PHPDoc)

*(Actions identical to previous plan's Milestone 10.10 - update `README.md`, add PHPDoc blocks, optional API docs)*.

*   **Verification:** Documentation is clear, up-to-date, and explains setup/architecture.

### 10.11. Milestone 6.7: Setting Up Data Backups

*(Actions identical to previous plan's Milestone 10.11 - configure `config/backup.php`, schedule commands in `Kernel.php`, add cron job to server)*.

*   **Verification:** Test `backup:run`, `backup:clean`, `backup:list`. Ensure production cron job is set.

### 10.12. Understanding Key Concepts: Deployment

*(Explanation remains the same - server setup, code transfer, prod `.env`, dependencies, migrations, optimizations, storage link, permissions, web server, Supervisor, scheduler)*.

### 10.13. Milestone 6.8: Preparing for Deployment

*(Actions identical to previous plan's Milestone 10.13 - create `.env.production.example`, ensure deployment script includes optimization commands, prepare Supervisor config)*.

*   **Verification:** Production `.env` template complete. Deployment checklist/script includes all necessary steps.

### 10.14. Milestone 6.9: Final Git Commit

Final commit for the tutorial.

*   **Action:** Commit changes.

```bash
    git add .
    git commit -m "Phase 6: Implement i18n, Pennant, Tests (Unit/Feature/Dusk/Filament), Perf Checks, Docs, Backup, Deploy Prep"
```

*   **Verification:** `git log`, `git status`.

**Phase 6 Complete!** The application has been polished with i18n and feature flags, tested across different layers, documented, and prepared for a production deployment environment with backups configured.

--- END SECTION: Phase 6: Polishing, Testing & Deployment ---

--- START SECTION: Conclusion & Next Steps ---

## 11. Conclusion & Next Steps

Congratulations! You've successfully built a comprehensive set of User Model Enhancements within a modern Laravel 12 application. Starting from a clean installation, you've incrementally added complex features, gaining practical experience with:

*   **Laravel 12 Fundamentals:** Routing, Controllers, Eloquent Models (Relationships, Traits, Accessors), Migrations, Seeders, Factories, Middleware, Events, Listeners, Queues (Horizon), Service Container, Blade templating.
*   **Authentication & Authorization:** Leveraging Fortify and Breeze, implementing 2FA, managing permissions with `spatie/laravel-permission` (including team scoping), and using Policies.
*   **Architecture:** Implementing the Service Layer pattern, using State Machines (`spatie/laravel-model-states`) for account lifecycles, and designing a decoupled system with Events/Listeners.
*   **Advanced Features:** Handling file uploads (`spatie/laravel-medialibrary`), user impersonation (`lab404/laravel-impersonate`), user settings (`spatie/laravel-settings`), commenting (`spatie/laravel-comments`), full-text search (Scout/Typesense), feature flags (Pennant), and internationalization.
*   **Real-time Capabilities:** Setting up WebSockets with Reverb, using Echo on the frontend, building presence and chat features restricted by team context.
*   **UI Implementation:** Building user-facing features primarily with **Livewire/Volt SFCs**, creating administrative interfaces with **FilamentPHP**, and understanding how similar features could be approached conceptually with **Inertia/React** and **Inertia/Vue**.
*   **Development Best Practices:** Utilizing version control (Git), writing automated tests (PestPHP for Unit/Feature, Dusk for Browser, Filament tests), considering performance, documenting code, setting up backups, and preparing for deployment.

This project covered a wide range of tools and techniques essential for building robust, feature-rich web applications with Laravel.

**Next Steps for Your Learning:**

1.  **Deepen Testing:** Expand test coverage significantly. Explore advanced mocking, different assertion types, and testing edge cases for all implemented features and UI interactions. Aim for robust Feature tests covering user flows involving teams, permissions, and real-time updates.
2.  **Refine UI/UX:** Polish the Livewire/Volt frontend. Improve user experience, add loading states, enhance accessibility, and perhaps explore more advanced Livewire features. If interested, fully implement one of the Inertia stacks based on the conceptual guides.
3.  **Expand Filament:** Build out more Filament resources and potentially custom pages for deeper administration (e.g., viewing activity logs, managing settings, visualizing team hierarchies). Explore Filament plugins.
4.  **Real-time Enhancements:** Make the comments section real-time. Add typing indicators to the chat. Explore broadcasting more types of events (e.g., team setting changes). Implement more sophisticated presence logic (e.g., 'away' status based on inactivity).
5.  **Explore Further:** Consider features from the original PRD's "Future Roadmap" (Direct Messaging, Notification Preferences, Webhooks) or add your own ideas.
6.  **Deployment Practice:** Deploy the application to a staging or production environment using tools like Laravel Forge, Ploi, or manual server configuration. Gain experience managing queues, Reverb, backups, and HTTPS in a live setting.
7.  **Study Documentation:** Revisit the official documentation for Laravel, Livewire, Filament, and key Spatie packages to solidify your understanding of the tools used.
8.  **Community & Learning:** Engage with the Laravel community (Laracasts, forums, Discord). Contribute to open source. Keep building projects to apply and expand your knowledge.

This tutorial provided a strong foundation. The best way to learn is by building and experimenting. Take these skills and create something amazing! Thank you for following along.

--- END SECTION: Conclusion & Next Steps ---

--- START SECTION: Glossary ---

## 12. Glossary

*(Alphabetical Order - Updated for Laravel 12 and new concepts)*

*   **`.env` File:** Environment configuration file (DB credentials, API keys). Not version controlled. Copied from `.env.example`.
*   **Abstract Class:** Base class that cannot be instantiated directly; meant to be extended.
*   **Accessor (Eloquent):** Method defining a computed property or modifying attribute retrieval (e.g., `fullName`). `Attribute::make(get: fn() => ...)` syntax.
*   **Action (Class):** Class performing one specific task (e.g., `CreateNewUser`). Promotes single responsibility.
*   **Activity Log:** Record of significant application events. Uses `spatie/laravel-activitylog`.
*   **API (Application Programming Interface):** Rules for software interaction, often HTTP endpoints returning JSON.
*   **Artisan:** Laravel's command-line tool (`php artisan`).
*   **Asset Bundling:** Compiling/combining JS/CSS assets (e.g., using Vite).
*   **Authentication:** Verifying user identity (login). Handled by Fortify.
*   **Authorization:** Determining user permissions for actions. Handled by Gates/Policies/`spatie/laravel-permission`.
*   **Blade:** Laravel's templating engine (`.blade.php` files). Used by Livewire/Volt.
*   **Broadcasting:** Sending server-side events over WebSockets (via Reverb) for real-time updates.
*   **Breeze (Laravel):** Minimal starter kit for authentication scaffolding (views, routes, controllers). We used the Livewire/Volt stack.
*   **Cache:** Temporary storage for computed data/queries to improve performance.
*   **Callback:** Function passed as an argument to another function.
*   **Channel (Broadcasting):** Named conduit for real-time messages (public, private, presence). Authorized in `routes/channels.php`.
*   **Closure:** Anonymous PHP function.
*   **Collection (Laravel):** Powerful wrapper class for arrays (`Illuminate\Support\Collection`).
*   **Composer:** PHP dependency manager. Manages packages via `composer.json`.
*   **Controller:** Class handling HTTP requests, orchestrating Services/Models, returning Responses.
*   **Cookie:** Small data stored by the browser, used for sessions etc.
*   **CRUD:** Create, Read, Update, Delete operations.
*   **CSRF (Cross-Site Request Forgery):** Web vulnerability. Laravel provides protection via tokens.
*   **Database:** Organized data storage (e.g., PostgreSQL).
*   **Database Seeding:** Populating DB tables with initial data (`php artisan db:seed`).
*   **DBAL (Doctrine):** PHP library used by Laravel for some schema modifications (`->change()`).
*   **Debouncing:** Limiting how often a function fires (e.g., on search input type).
*   **Decoupling:** Reducing dependencies between code components.
*   **Dependency:** External code needed by a class.
*   **Dependency Injection (DI):** Pattern where dependencies are provided externally (via Service Container).
*   **Dusk (Laravel):** Browser testing tool using ChromeDriver. Tests in `tests/Browser`.
*   **Eager Loading (Eloquent):** Loading related models efficiently (`->with(...)`) to prevent N+1 problems.
*   **Echo (Laravel):** Frontend JavaScript library for interacting with WebSocket broadcasts (Reverb/Pusher).
*   **Eloquent ORM:** Laravel's Object-Relational Mapper for database interaction via Models.
*   **Enum (Enumeration):** PHP 8.1+ type with a fixed set of named constant values (e.g., `AccountStatus::Active`).
*   **Event:** Object representing a significant application occurrence (e.g., `UserRegistered`). Dispatched via `event()`.
*   **Event Listener:** Class handling a specific event. Registered in `EventServiceProvider`. Can be queued.
*   **Facade (Laravel):** Static-like interface to services in the container (e.g., `Log::info()`).
*   **Factory (Model Factory):** Class defining blueprint for fake model data (`database/factories/`). Uses Faker.
*   **Faker:** PHP library for generating fake data.
*   **Feature Flag:** Toggle for enabling/disabling features without code deployment. Managed by `laravel/pennant`.
*   **Feature Test:** Test verifying functionality via simulated HTTP/console requests (`tests/Feature/`). Boots framework.
*   **FilamentPHP:** A TALL stack admin panel framework for Laravel. Builds admin UIs quickly using Livewire, Alpine.js, Tailwind CSS.
*   **Foreign Key:** Database column linking related tables.
*   **Form Request (Laravel):** Class handling validation/authorization for HTTP requests (`app/Http/Requests/`).
*   **Fortify (Laravel):** Backend authentication scaffolding (logic only). Used by Breeze.
*   **Full-Text Search:** Advanced text searching. Implemented via Scout + Typesense.
*   **Gate (Laravel Authorization):** Simple closure-based authorization check, often not tied to a model.
*   **Git:** Distributed version control system.
*   **Guard (Laravel Auth):** Named authentication mechanism (e.g., 'web', 'api', 'sanctum').
*   **Helper Function (Laravel):** Global convenience functions (`__()`, `event()`, `route()`, etc.).
*   **Herd (Laravel):** Native macOS/Windows local development environment (PHP, Nginx, Node, etc.).
*   **Horizon (Laravel):** Dashboard and configuration for Redis queues.
*   **HTTP:** Protocol for web communication (GET, POST, etc.).
*   **i18n (Internationalization):** Designing software for multiple languages/regions.
*   **Idempotent:** Operation yielding same result if performed multiple times.
*   **Impersonation:** Admin acting as another user. Uses `lab404/laravel-impersonate`.
*   **Index (Database):** Structure speeding up database lookups.
*   **Inertia.js:** Framework for building SPAs using server-side routing (Laravel) with JS frontends (React/Vue). Alternative UI stack.
*   **IoC Container:** See Service Container.
*   **JSON:** Lightweight data format, common for APIs.
*   **L10n (Localization):** Adapting software for a *specific* locale (translation, formatting).
*   **Laravel:** The PHP web framework used. Version 12 targeted here.
*   **Livewire:** Full-stack Laravel framework for dynamic UIs using mostly PHP/Blade. Our primary UI stack.
*   **Locale:** Code representing language/region (e.g., 'en', 'es_MX').
*   **Logging:** Recording application events/errors. Uses `Log` facade.
*   **Mailable:** Class representing an email (`app/Mail/`).
*   **Mass Assignment:** Setting multiple model attributes via array. Protected by `$fillable`/`$guarded`.
*   **Media Conversion (Spatie):** Generating different file versions (e.g., thumbnails).
*   **Media Library (Spatie):** Package (`spatie/laravel-medialibrary`) for managing model file attachments.
*   **Middleware:** Classes filtering HTTP requests/responses (`app/Http/Middleware/`).
*   **Migration:** Version-controlled database schema changes (`database/migrations/`).
*   **Mocking (Testing):** Creating fake objects to simulate dependencies in tests.
*   **Model (Eloquent):** Class representing a database table (`app/Models/`).
*   **Model Events (Eloquent):** Events fired during model lifecycle (`creating`, `updated`, etc.).
*   **MVC:** Model-View-Controller architectural pattern.
*   **Mutator (Eloquent):** Modifies attribute value *before* saving (less common now). `Attribute::make(set: fn...)`.
*   **N+1 Query Problem:** Inefficient DB querying solved by Eager Loading.
*   **Namespace (PHP):** Organizes code, prevents naming conflicts.
*   **Node.js:** JavaScript runtime for server-side/tooling.
*   **Notification (Laravel):** System for sending messages (email, Slack, DB) (`app/Notifications/`).
*   **npm:** Node Package Manager for JS libraries.
*   **OAuth2:** Standard for delegated access (API auth, social login). Implemented by Passport.
*   **Observer (Eloquent):** Class listening for Eloquent model events. Alternative to `booted()` listeners.
*   **ORM:** Object-Relational Mapper (Eloquent).
*   **Package:** Reusable code library (Composer for PHP, npm for JS).
*   **Pagination:** Dividing large data sets into pages. Laravel provides `->paginate()`.
*   **Passport (Laravel):** Full OAuth2 server implementation package.
*   **Pennant (Laravel):** Feature flag management package.
*   **PestPHP:** Elegant testing framework built on PHPUnit. Used for our tests.
*   **PHP:** Server-side scripting language used by Laravel.
*   **PHPDoc:** Standard for commenting PHP code (`/** ... */`).
*   **Pivot Table:** Intermediate table for many-to-many relationships (e.g., `team_user`).
*   **Policy (Laravel Authorization):** Class containing authorization logic for a specific model (`app/Policies/`).
*   **PostgreSQL:** Open-source relational database.
*   **Presence Channel:** Special WebSocket channel for tracking subscribed users (`presence-...`). Requires auth, returns user data.
*   **Primary Key:** Unique identifier column(s) in a DB table.
*   **Private Channel:** WebSocket channel requiring authorization (`private-...`).
*   **Provider:** See Service Provider.
*   **Pulse (Laravel):** Real-time application performance monitoring dashboard.
*   **Publishing (Vendor):** Copying package assets (config, migrations) into app (`vendor:publish`).
*   **Queue:** System for background job processing. Uses drivers like Redis. Managed by Horizon/workers.
*   **Queue Worker:** Process executing jobs from a queue (`queue:work` or `horizon`).
*   **RBAC:** Role-Based Access Control. Implemented via `spatie/laravel-permission`.
*   **React:** JavaScript library for building UIs. Alternative Inertia stack.
*   **Real-time:** Features updating automatically via WebSockets.
*   **Redis:** In-memory data store (cache, sessions, queues).
*   **Redirect:** HTTP response telling browser to go to a different URL.
*   **Refactoring:** Improving code structure without changing functionality.
*   **Regression:** Bug where previously working feature breaks.
*   **Relationship (Eloquent):** Definition connecting models (`belongsTo`, `hasMany`, etc.).
*   **Resource (API Resource):** Class transforming models to JSON for APIs (`app/Http/Resources/`).
*   **Resource Controller:** Controller with standard CRUD methods (`make:controller --resource`).
*   **Reverb (Laravel):** First-party WebSocket server for Laravel Broadcasting.
*   **Role:** Named group of permissions (`spatie/laravel-permission`).
*   **Route Model Binding:** Automatically injecting models into controller methods based on route parameters. Uses ULID via `HasUlid` trait.
*   **Routing:** Mapping URLs/methods to controller actions (`routes/web.php`, `api.php`).
*   **Sail (Laravel):** Docker-based local development environment.
*   **Sanctum (Laravel):** Lightweight authentication for SPAs (cookies) and simple API tokens.
*   **Scaffolding:** Auto-generated boilerplate code (e.g., via Breeze).
*   **Scheduler (Laravel):** System for scheduling recurring tasks (`app/Console/Kernel.php`). Requires cron job.
*   **Schema:** Database structure definition.
*   **Scout (Laravel):** Abstraction layer for full-text search engine integration (Typesense, Algolia).
*   **Seeder:** Class populating database with initial data (`database/seeders/`).
*   **Serialization:** Converting objects to storable/transmittable format (e.g., for queues).
*   **Service (Class):** Class encapsulating specific business logic (`app/Services/`).
*   **Service Container:** Laravel's IoC container managing dependencies and DI.
*   **Service Provider:** Class bootstrapping services, registering bindings (`app/Providers/`).
*   **Session:** Storing user data across multiple requests.
*   **SFC (Single File Component):** Component structure combining template, script, style (Vue, Volt).
*   **Signed URL:** Tamper-proof URL using cryptographic signature.
*   **Slug:** URL-friendly string (`spatie/laravel-sluggable`).
*   **Socialite (Laravel):** Package for OAuth social logins.
*   **Soft Deletes:** Marking records as deleted (`deleted_at`) instead of removing them. Uses `SoftDeletes` trait.
*   **SPA (Single Page Application):** UI managed by JS in browser, interacts with backend API. Built with Inertia+React/Vue.
*   **Spatie:** Company creating many useful PHP/Laravel packages.
*   **State Machine:** Pattern managing object state transitions. Uses `spatie/laravel-model-states`.
*   **Supervisor:** Linux process manager for keeping queue workers/Reverb running.
*   **Tailwind CSS:** Utility-first CSS framework. Default in Laravel 12 starter kits (v4). Used by Livewire/Volt, Inertia stacks. Filament uses v3 internally.
*   **TALL Stack:** Tech stack: Tailwind CSS, Alpine.js, Laravel, Livewire. Filament is built on this.
*   **Telescope (Laravel):** Local development debug assistant UI (`/telescope`).
*   **Testing:** Verifying code correctness via automated tests.
*   **Tinker (Artisan):** Interactive command-line shell for Laravel (`php artisan tinker`).
*   **Token (API):** String credential for API authentication (Sanctum/Passport).
*   **Trait (PHP):** Code reuse mechanism.
*   **Transaction (Database):** Atomic unit of database work (`DB::transaction(...)`).
*   **Transition (State Machine):** Allowed change between states.
*   **Translation:** Converting text between languages (see i18n/L10n). Uses `__()`.
*   **Typesense:** Open-source search engine used with Scout.
*   **2FA (Two-Factor Authentication):** Security layer requiring password + second factor (code). Handled by Fortify.
*   **UI (User Interface):** Visual part users interact with.
*   **ULID:** Unique, sortable identifier used for public IDs.
*   **Unit Test:** Test for isolated code unit (`tests/Unit/`). Fast.
*   **UUID:** Universally Unique Identifier (alternative to ULID).
*   **Validation:** Checking user input against rules.
*   **Vendor Directory:** Stores Composer packages (`vendor/`). Not committed.
*   **Version Control:** Tracking code changes (Git).
*   **View:** Presentation layer (Blade templates, JS components).
*   **Vite:** Frontend build tool used by Laravel. Compiles assets (`vite.config.js`, `npm run dev/build`).
*   **Volt (Livewire):** Functional API for Livewire allowing SFCs in Blade files.
*   **Vue.js:** JavaScript framework for UIs. Alternative Inertia stack.
*   **Web Server:** Handles HTTP requests (Nginx, Apache).
*   **WebSocket:** Protocol for persistent, bi-directional client-server communication (real-time).

--- END SECTION: Glossary ---
