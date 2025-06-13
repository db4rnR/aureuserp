# Phase 0: Phase 0.2: Development Environment Setup

**Version:** 1.0.6
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Updated
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Step 1: Install Laravel Herd](#step-1-install-laravel-herd)
- [Step 2: Configure Laravel Herd](#step-2-configure-laravel-herd)
- [Step 3: Install Additional Development Tools](#step-3-install-additional-development-tools)
- [Step 4: Configure Git](#step-4-configure-git)
- [Step 5: Configure IDE](#step-5-configure-ide)
- [Step 6: Set Up Database Tools](#step-6-set-up-database-tools)
- [Step 7: Install Node.js and NPM](#step-7-install-nodejs-and-npm)
- [Step 8: Verify Development Environment](#step-8-verify-development-environment)
- [Troubleshooting](#troubleshooting)
</details>

## Overview

This document provides detailed instructions for setting up the development environment for the Enhanced Laravel Application (ELA). The development environment is based on Laravel Herd, which provides a complete local development environment for Laravel applications.

## Prerequisites

Before starting, ensure you have the following:

- macOS 11.0 or later (for Laravel Herd)
- Administrator access to your machine
- Internet connection
- At least 10GB of free disk space
- 8GB RAM or more recommended

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Install Laravel Herd | 15 minutes |
| Configure Laravel Herd | 10 minutes |
| Install Node.js and NPM | 10 minutes |
| Install Git | 10 minutes |
| Configure Git | 5 minutes |
| Verify Installation | 10 minutes |
| **Total** | **60 minutes** |

> **Note:** These time estimates assume a high-speed internet connection. Download times may vary based on your connection speed. Additional time may be needed if troubleshooting is required.

## Step 1: Install Laravel Herd

Laravel Herd is an all-in-one development environment for macOS that includes PHP, Nginx, MySQL, and other tools needed for Laravel development.

1. Download Laravel Herd from the official website: [https://herd.laravel.com/](https:/herd.laravel.com)
2. Open the downloaded DMG file and drag the Herd application to your Applications folder
3. Launch Herd from your Applications folder
4. When prompted, allow Herd to install its components

## Step 2: Configure Laravel Herd

Once Laravel Herd is installed, configure it for optimal development:

1. Open Laravel Herd preferences
2. Configure PHP version:
   - Select PHP 8.4 as the default PHP version
   - Ensure the following PHP extensions are enabled:
     - BCMath
     - Ctype
     - Fileinfo
     - JSON
     - Mbstring
     - OpenSSL
     - PDO
     - Tokenizer
     - XML
     - SQLite
     - Redis
     - Imagick
     - Intl
     - Zip
     - GD

3. Configure database settings:
   - Ensure PostgreSQL is installed and running
   - Create a new database for the project:
   ```bash
   createdb ela_development
   ```
   - Create a new database for testing:
   ```bash
   createdb ela_testing
   ```

4. Configure Redis:
   - Ensure Redis is installed and running
   - Verify Redis is working with:
   ```bash
   redis-cli ping
   ```
   You should receive a response of "PONG"

## Step 3: Install Additional Development Tools

Install the following additional tools to enhance your development workflow:

1. Install Composer (if not already installed with Herd):
   ```bash
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

2. Install Laravel Installer:
   ```bash
   composer global require laravel/installer
   ```

3. Install Laravel Sail (for Docker-based development if needed):
   ```bash
   composer global require laravel/sail
   ```

4. Install Laravel Pint (for code styling):
   ```bash
   composer global require laravel/pint
   ```

## Step 4: Configure Git

Set up Git for version control:

1. Install Git (if not already installed):
   ```bash
   brew install git
   ```

2. Configure Git with your credentials:
   ```bash
   git config --global user.name "Your Name"
   git config --global user.email "your.email@example.com"
   ```

3. Set up SSH keys for GitHub (if not already done):
   ```bash
   ssh-keygen -t ed25519 -C "your.email@example.com"
   ```

4. Add the SSH key to your GitHub account:
   - Copy the public key:
     ```bash
     cat ~/.ssh/id_ed25519.pub
     ```
   - Add it to your GitHub account in Settings > SSH and GPG keys

## Step 5: Configure IDE

Configure your preferred IDE for Laravel development:

### Visual Studio Code

1. Install Visual Studio Code from [https://code.visualstudio.com/](https:/code.visualstudio.com)
2. Install the following extensions:
   - PHP Intelephense
   - Laravel Blade Snippets
   - Laravel Snippets
   - Laravel Artisan
   - Laravel Extra Intellisense
   - DotENV
   - EditorConfig for VS Code
   - GitLens
   - PHP Debug
   - PHP DocBlocker
   - PHP Namespace Resolver
   - Tailwind CSS IntelliSense

### PhpStorm

1. Install PhpStorm from [https://www.jetbrains.com/phpstorm/](https:/www.jetbrains.com/phpstorm)
2. Install the Laravel plugin
3. Configure the PHP interpreter to use the PHP version from Laravel Herd
4. Configure the database connection to use the PostgreSQL database created earlier
5. Install the Tailwind CSS plugin

## Step 6: Set Up Database Tools

Install and configure database management tools:

1. Install TablePlus:
   - Download from [https://tableplus.com/](https:/tableplus.com)
   - Install and configure connections to your PostgreSQL databases

2. Alternatively, install pgAdmin:
   - Download from [https://www.pgadmin.org/](https:/www.pgadmin.org)
   - Configure connections to your PostgreSQL databases

## Step 7: Install Node.js and NPM

Install Node.js and NPM for frontend development:

1. Install Node.js using NVM (Node Version Manager):
   ```bash
   # Download and run the NVM installation script
   # This script will clone the NVM repository to ~/.nvm and add the source line to your profile
   curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash

   # Load NVM without restarting your terminal
   export NVM_DIR="$HOME/.nvm"
   [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
   ```

2. Install the latest LTS version of Node.js:
   ```bash
   # Install the latest Long-Term Support version of Node.js
   # This is recommended for production applications
   nvm install --lts

   # You can also install a specific version if needed
   # nvm install 16.20.0
   ```

3. Verify the installation:
   ```bash
   # Check the installed Node.js version
   node --version

   # Check the installed npm version
   npm --version

   # List all installed Node.js versions
   nvm ls
   ```

## Step 8: Verify Development Environment

Verify that your development environment is set up correctly:

1. Create a test Laravel project:
   ```bash
   cd ~/Herd
   laravel new test-project
   cd test-project
   ```

2. Start the development server:
   ```bash
   php artisan serve
   ```

3. Open your browser and navigate to `http://localhost:8000`
   - You should see the Laravel welcome page

4. Test database connection:
   ```bash
   php artisan migrate:status
   ```
   - You should see a message indicating the migration table does not exist

5. Clean up the test project:
   ```bash
   cd ..
   rm -rf test-project
   ```

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: PHP Extensions Missing

**Symptoms:**
- Laravel returns errors about missing PHP extensions
- Specific functionality doesn't work as expected

**Possible Causes:**
- Required PHP extensions are not enabled in Laravel Herd
- PHP configuration is incorrect

**Solutions:**
1. Open Laravel Herd preferences
2. Go to the PHP tab
3. Enable the required extensions (typically: pdo_pgsql, redis, zip, gd, intl)
4. Restart Laravel Herd

### Issue: Database Connection Issues

**Symptoms:**
- Laravel returns database connection errors
- Migrations fail to run
- Error messages about "could not connect to server"

**Possible Causes:**
- PostgreSQL is not running
- Database credentials are incorrect
- Database does not exist
- PostgreSQL configuration issues

**Solutions:**
1. Ensure PostgreSQL is running (check Laravel Herd dashboard)
2. Verify database credentials in `.env` file
3. Create the database if it doesn't exist
4. Check PostgreSQL logs for specific errors

### Issue: Composer Memory Limit

**Symptoms:**
- Composer fails with "Allowed memory size exhausted" error
- Package installation terminates unexpectedly

**Possible Causes:**
- PHP memory limit is too low
- Large packages require more memory than allocated

**Solutions:**
1. Increase PHP memory limit in php.ini
2. Use the `COMPOSER_MEMORY_LIMIT=-1` environment variable:
   ```bash
   COMPOSER_MEMORY_LIMIT=-1 composer require package/name
   ```
3. Update Composer to the latest version

### Issue: Redis Connection Issues

**Symptoms:**
- Laravel cache or queue operations fail
- Error messages about Redis connection

**Possible Causes:**
- Redis is not running
- Redis configuration is incorrect
- Redis port conflicts

**Solutions:**
1. Ensure Redis is running (check Laravel Herd dashboard)
2. Verify Redis configuration in `.env` file
3. Check if another process is using the Redis port
4. Restart Redis service

### Issue: Node.js Version Conflicts

**Symptoms:**
- npm or yarn commands fail
- Error messages about incompatible Node.js version

**Possible Causes:**
- Installed Node.js version is incompatible with project requirements
- Multiple Node.js versions causing conflicts

**Solutions:**
1. Use NVM to install and switch to the required Node.js version:
   ```bash
   nvm install 16
   nvm use 16
   ```
2. Specify Node.js version in package.json
3. Use .nvmrc file to automatically switch versions

</details>

## Related Documents

- [Documentation Updates](010-overview/030-documentation-updates.md) - For updating project documentation
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) - For installing Laravel framework
- [Package Installation](030-core-components/010-package-installation.md) - For installing required packages
- [Technical Architecture Document](../030-ela-tad.md) - For technical architecture details
- [System Requirements](../050-ela-system-requirements.md) - For detailed system requirements

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added version history section and navigation links | AI Assistant |
| 1.0.3 | 2025-05-17 | Added estimated time requirements section | AI Assistant |
| 1.0.4 | 2025-05-17 | Enhanced troubleshooting section with collapsible format and detailed information | AI Assistant |
| 1.0.5 | 2025-05-17 | Added related documents section | AI Assistant |
| 1.0.6 | 2025-05-17 | Enhanced code examples with detailed comments | AI Assistant |

---

**Previous Step:** [Documentation Updates](010-overview/030-documentation-updates.md) | **Next Step:** [Laravel Installation](020-environment-setup/020-laravel-installation.md)
