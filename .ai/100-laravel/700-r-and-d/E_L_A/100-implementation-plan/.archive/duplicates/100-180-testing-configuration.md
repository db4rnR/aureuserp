# Phase 1: Phase 0.18: Testing Configuration Details

**Version:** 1.0.2 **Date:** 2023-11-13 **Author:** AI Assistant **Status:** Updated **Progress:** Complete

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
- [Step 1: Configure PHPUnit](#step-1-configure-phpunit)
- [Step 2: Configure Pest PHP](#step-2-configure-pest-php)
- [Step 3: Configure Laravel Dusk](#step-3-configure-laravel-dusk)
- [Step 4: Configure Playwright](#step-4-configure-playwright)
- [Step 5: Set Up CI/CD Testing](#step-5-set-up-cicd-testing)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides detailed instructions for configuring testing tools for the Enhanced Laravel Application (ELA).
It covers PHPUnit, Pest PHP, Laravel Dusk, Playwright, and CI/CD testing setup.

> **Reference:** [Laravel 12.x Testing Documentation](https:/laravel.com/docs/12.x/testing)

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps

- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Testing Environment Setup](050-security-testing/020-testing-setup.md) completed
- [Final Configuration](060-configuration/020-final-configuration.md) completed

### Required Packages

- Laravel Framework (`laravel/framework`) installed
- PHPUnit (`phpunit/phpunit`) installed
- Pest PHP (`pestphp/pest`) installed
- Laravel Dusk (`laravel/dusk`) installed
- Playwright PHP (`pestphp/pest-plugin-playwright`) installed

### Required Knowledge

- Basic understanding of testing concepts
- Familiarity with PHPUnit and Pest PHP
- Understanding of browser automation testing

### Required Environment

- PHP 8.2 or higher with Xdebug extension
- Composer 2.x
- Node.js 22.x or higher
- npm 10.x or higher

## Estimated Time Requirements

| Task                   | Estimated Time  |
| ---------------------- | --------------- |
| Configure PHPUnit      | 15 minutes      |
| Configure Pest PHP     | 15 minutes      |
| Configure Laravel Dusk | 20 minutes      |
| Configure Playwright   | 20 minutes      |
| Set Up CI/CD Testing   | 30 minutes      |
| **Total**              | **100 minutes** |

> **Note:** These time estimates assume familiarity with Laravel testing tools. Actual time may vary based on experience
> level and the complexity of your testing requirements.

## Step 1: Configure PHPUnit

1. Update the PHPUnit configuration in `phpunit.xml`:

   ```xml
   <?xml version="1.0" encoding="UTF-8"?>
   <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
            bootstrap="vendor/autoload.php"
            colors="true"
   >
       <testsuites>
           <testsuite name="Unit">
               <directory>tests/Unit</directory>
           </testsuite>
           <testsuite name="Feature">
               <directory>tests/Feature</directory>
           </testsuite>
       </testsuites>
       <source>
           <include>
               <directory>app</directory>
           </include>
       </source>
       <php>
           <env name="APP_ENV" value="testing"/>
           <env name="BCRYPT_ROUNDS" value="4"/>
           <env name="CACHE_DRIVER" value="array"/>
           <env name="DB_CONNECTION" value="sqlite"/>
           <env name="DB_DATABASE" value=":memory:"/>
           <env name="MAIL_MAILER" value="array"/>
           <env name="QUEUE_CONNECTION" value="sync"/>
           <env name="SESSION_DRIVER" value="array"/>
           <env name="TELESCOPE_ENABLED" value="false"/>
       </php>
   </phpunit>
   ```

2. Create a base test case class in `tests/TestCase.php`:

   ```php
   <?php

   namespace Tests;

   use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
   use Illuminate\Foundation\Testing\RefreshDatabase;

   abstract class TestCase extends BaseTestCase
   {
       use CreatesApplication;

       /**
        * Indicates whether the default seeder should run before each test.
        *
        * @var bool
        */
       protected $seed = true;

       /**
        * Setup the test environment.
        */
       protected function setUp(): void
       {
           parent::setUp();

           // Additional setup code
       }
   }
   ```

3. Create a database test case class in `tests/DatabaseTestCase.php`:

   ```php
   <?php

   namespace Tests;

   use Illuminate\Foundation\Testing\RefreshDatabase;

   abstract class DatabaseTestCase extends TestCase
   {
       use RefreshDatabase;

       /**
        * Setup the test environment.
        */
       protected function setUp(): void
       {
           parent::setUp();

           // Additional database setup code
       }
   }
   ```

   > **Reference:** >
   > [Laravel 12.x PHPUnit Configuration Documentation](https:/laravel.com/docs/12.x/testing#environment)

## Step 2: Configure Pest PHP

1. Install Pest PHP:

   ```bash
   # First, remove PHPUnit if it's installed
   composer remove phpunit/phpunit --dev

   # Install Pest with all dependencies
   composer require pestphp/pest:"^3.8.2" --dev --with-all-dependencies

   # Install Laravel plugin for Pest
   composer require pestphp/pest-plugin-laravel:"^3.2.0" --dev

   # Initialize Pest in your project
   ./vendor/bin/pest --init
   ```

   > **Reference:** [Pest PHP Installation Documentation](https:/pestphp.com/docs/installation)

2. Configure Pest PHP in `tests/Pest.php`:

   ```php
   <?php

   use Illuminate\Foundation\Testing\RefreshDatabase;
   use Tests\TestCase;

   /*
   |--------------------------------------------------------------------------
   | Test Case
   |--------------------------------------------------------------------------
   |
   | The closure you provide to your test functions is always bound to a specific PHPUnit test
   | case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
   | need to change it using the "uses()" function to bind a different classes or traits.
   |
   */

   uses(TestCase::class)->in('Feature');
   uses(TestCase::class)->in('Unit');
   uses(RefreshDatabase::class)->in('Feature/Database');

   /*
   |--------------------------------------------------------------------------
   | Expectations
   |--------------------------------------------------------------------------
   |
   | When you're writing tests, you often need to check that values meet certain conditions. The
   | "expect()" function gives you access to a set of "expectations" methods that you can use
   | to assert different things. Of course, you may extend the Expectation API at any time.
   |
   */

   expect()->extend('toBeOne', function () {
       return $this->toBe(1);
   });

   /*
   |--------------------------------------------------------------------------
   | Functions
   |--------------------------------------------------------------------------
   |
   | While Pest is very powerful out-of-the-box, you may have some testing code specific to your
   | project that you don't want to repeat in every file. Here you can also expose helpers as
   | global functions to help you to reduce the number of lines of code in your test files.
   |
   */

   function something()
   {
       // ..
   }
   ```

3. Create a sample Pest test in `tests/Feature/ExampleTest.php`:

   ```php
   <?php

   it('has welcome page', function () {
       $response = $this->get('/');

       $response->assertStatus(200);
   });

   it('has application name', function () {
       $this->assertEquals('Enhanced Laravel Application', config('app.name'));
   });
   ```

   > **Reference:** [Pest PHP Documentation](https:/pestphp.com/docs/installation)

## Step 3: Configure Laravel Dusk

1. Install Laravel Dusk:

   ```bash
   composer require laravel/dusk:"^8.0" --dev
   php artisan dusk:install
   ```

2. Configure Dusk in `tests/DuskTestCase.php`:

   ```php
   <?php

   namespace Tests;

   use Facebook\WebDriver\Chrome\ChromeOptions;
   use Facebook\WebDriver\Remote\DesiredCapabilities;
   use Facebook\WebDriver\Remote\RemoteWebDriver;
   use Laravel\Dusk\TestCase as BaseTestCase;

   abstract class DuskTestCase extends BaseTestCase
   {
       use CreatesApplication;

       /**
        * Prepare for Dusk test execution.
        */
       public static function prepare(): void
       {
           if (! static::runningInSail()) {
               static::startChromeDriver();
           }
       }

       /**
        * Create the RemoteWebDriver instance.
        */
       protected function driver(): RemoteWebDriver
       {
           $options = (new ChromeOptions)->addArguments(collect([
               $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
           ])->unless($this->hasHeadlessDisabled(), function ($items) {
               return $items->merge([
                   '--headless=new',
               ]);
           })->all());

           return RemoteWebDriver::create(
               $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
               DesiredCapabilities::chrome()->setCapability(
                   ChromeOptions::CAPABILITY, $options
               )
           );
       }

       /**
        * Determine whether the Dusk command has disabled headless mode.
        */
       protected function hasHeadlessDisabled(): bool
       {
           return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
                  isset($_ENV['DUSK_HEADLESS_DISABLED']);
       }

       /**
        * Determine if the browser window should start maximized.
        */
       protected function shouldStartMaximized(): bool
       {
           return isset($_SERVER['DUSK_START_MAXIMIZED']) ||
                  isset($_ENV['DUSK_START_MAXIMIZED']);
       }
   }
   ```

3. Create a sample Dusk test in `tests/Browser/ExampleTest.php`:

   ```php
   <?php

   namespace Tests\Browser;

   use Laravel\Dusk\Browser;
   use Tests\DuskTestCase;

   class ExampleTest extends DuskTestCase
   {
       /**
        * A basic browser test example.
        */
       public function test_basic_example(): void
       {
           $this->browse(function (Browser $browser) {
               $browser->visit('/')
                       ->assertSee('Laravel');
           });
       }
   }
   ```

4. Update the `.env.dusk` file:

   ```
   APP_URL=http://localhost:8000
   DUSK_DRIVER_URL=http://localhost:9515
   ```

   > **Reference:** [Laravel Dusk Documentation](https:/laravel.com/docs/12.x/dusk)

## Step 4: Configure Playwright

1. Install Playwright:

   ```bash
   npm install --save-dev @playwright/test
   npx playwright install
   ```

2. Create a Playwright configuration file in `playwright.config.js`:

   ```javascript
   // @ts-check
   const { defineConfig, devices } = require('@playwright/test');

   /**
    * @see https://playwright.dev/docs/test-configuration
    */
   module.exports = defineConfig({
     testDir: './tests/e2e',
     timeout: 30 * 1000,
     expect: {
       timeout: 5000,
     },
     fullyParallel: true,
     forbidOnly: !!process.env.CI,
     retries: process.env.CI ? 2 : 0,
     workers: process.env.CI ? 1 : undefined,
     reporter: 'html',
     use: {
       baseURL: 'http://localhost:8000',
       trace: 'on-first-retry',
     },
     projects: [
       {
         name: 'chromium',
         use: { ...devices['Desktop Chrome'] },
       },
       {
         name: 'firefox',
         use: { ...devices['Desktop Firefox'] },
       },
       {
         name: 'webkit',
         use: { ...devices['Desktop Safari'] },
       },
     ],
     webServer: {
       command: 'php artisan serve',
       port: 8000,
       reuseExistingServer: !process.env.CI,
     },
   });
   ```

3. Create a sample Playwright test in `tests/e2e/example.spec.js`:

   ```javascript
   const { test, expect } = require('@playwright/test');

   test('has title', async ({ page }) => {
     await page.goto('/');
     await expect(page).toHaveTitle(/Laravel/);
   });

   test('has welcome text', async ({ page }) => {
     await page.goto('/');
     await expect(page.locator('h1')).toContainText('Laravel');
   });
   ```

4. Add Playwright scripts to `package.json`:

   ```json
   "scripts": {
       "e2e": "playwright test",
       "e2e:ui": "playwright test --ui",
       "e2e:debug": "playwright test --debug"
   }
   ```

   > **Reference:** [Playwright Documentation](https:/playwright.dev/docs/intro)

## Step 5: Set Up CI/CD Testing

1. Create a GitHub Actions workflow file in `.github/workflows/tests.yml`:

   ```yaml
   name: Tests

   on:
     push:
       branches: [010-ddl, develop]
     pull_request:
       branches: [010-ddl, develop]

   jobs:
     laravel-tests:
       runs-on: ubuntu-latest

       services:
         postgres:
           image: postgres:14
           env:
             POSTGRES_DB: ela_test
             POSTGRES_USER: postgres
             POSTGRES_PASSWORD: postgres
           ports:
             - 5432:5432
           options: >-
             --health-cmd pg_isready --health-interval 10s --health-timeout 5s --health-retries 5

       steps:
         - uses: shivammathur/setup-php@v2
           with:
             php-version: '8.2'
             extensions: mbstring, pgsql, pcov
             coverage: pcov

         - uses: actions/checkout@v3

         - name: Copy .env
           run: cp .env.example .env

         - name: Install Dependencies
           run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

         - name: Generate key
           run: php artisan key:generate

         - name: Directory Permissions
           run: chmod -R 777 storage bootstrap/cache

         - name: Execute tests (Unit and Feature tests) via PHPUnit
           env:
             DB_CONNECTION: pgsql
             DB_HOST: localhost
             DB_PORT: 5432
             DB_DATABASE: ela_test
             DB_USERNAME: postgres
             DB_PASSWORD: postgres
           run: vendor/bin/pest --coverage

     playwright-tests:
       runs-on: ubuntu-latest
       needs: laravel-tests

       steps:
         - uses: actions/checkout@v3

         - uses: actions/setup-node@v3
           with:
             node-version: 18

         - name: Install dependencies
           run: npm ci

         - name: Install Playwright Browsers
           run: npx playwright install --with-deps

         - name: Copy .env
           run: cp .env.example .env

         - name: Install PHP dependencies
           run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist

         - name: Generate key
           run: php artisan key:generate

         - name: Directory Permissions
           run: chmod -R 777 storage bootstrap/cache

         - name: Run Playwright tests
           run: npx playwright test

         - uses: actions/upload-artifact@v3
           if: always()
           with:
             name: playwright-report
             path: playwright-report/
             retention-days: 30
   ```

   > **Reference:** [GitHub Actions Documentation](https:/docs.github.com/en/actions) and
   > [Laravel Testing Documentation](https:/laravel.com/docs/12.x/testing)

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: PHPUnit tests failing

**Symptoms:**

- PHPUnit tests fail with errors
- Tests pass locally but fail in CI

**Possible Causes:**

- Missing environment variables
- Database configuration issues
- Incorrect test database setup

**Solutions:**

1. Check the `.env.testing` file for missing variables
2. Ensure the test database is properly configured
3. Run `php artisan config:clear` before running tests

### Issue: Pest PHP tests not running

**Symptoms:**

- Pest PHP tests are not discovered
- Tests fail with syntax errors

**Possible Causes:**

- Pest PHP not properly installed
- Incorrect test file naming
- Missing Pest configuration

**Solutions:**

1. Verify Pest PHP is installed with `composer show pestphp/pest`
2. Ensure test files follow the naming convention `*Test.php` or `*_test.php`
3. Check the `Pest.php` file for proper configuration

### Issue: Laravel Dusk browser tests failing

**Symptoms:**

- Dusk tests fail with browser errors
- Screenshots show incorrect UI state

**Possible Causes:**

- Chrome driver version mismatch
- Missing JavaScript dependencies
- Timing issues with browser interactions

**Solutions:**

1. Update Chrome driver with `php artisan dusk:chrome-driver`
2. Ensure all JavaScript dependencies are installed
3. Add wait statements to handle timing issues

### Issue: Playwright tests failing

**Symptoms:**

- Playwright tests fail with browser errors
- Tests pass locally but fail in CI

**Possible Causes:**

- Missing browser dependencies in CI
- Incorrect Playwright configuration
- Timing issues with browser interactions

**Solutions:**

1. Install browser dependencies in CI with `npx playwright install --with-deps`
2. Check the Playwright configuration in `playwright.config.js`
3. Add wait statements to handle timing issues

### Issue: CI/CD pipeline failing

**Symptoms:**

- GitHub Actions workflow fails
- Tests pass locally but fail in CI

**Possible Causes:**

- Missing environment variables in CI
- Different PHP or Node.js versions
- Cache issues in CI

**Solutions:**

1. Add required environment variables to GitHub Actions secrets
2. Ensure CI uses the same PHP and Node.js versions as local development
3. Clear caches in CI with appropriate steps

</details>

## Related Documents

- [Testing Environment Setup](050-security-testing/020-testing-setup.md) - For basic testing setup
- [Final Configuration](060-configuration/020-final-configuration.md) - For final configuration steps
- [Code Quality Tools](060-configuration/040-code-quality-tools.md) - For code quality tools configuration

## Version History

| Version | Date       | Changes                                                                                             | Author       |
| ------- | ---------- | --------------------------------------------------------------------------------------------------- | ------------ |
| 1.0.0   | 2025-05-15 | Initial version                                                                                     | AI Assistant |
| 1.0.1   | 2025-05-17 | Updated file references and links                                                                   | AI Assistant |
| 1.0.2   | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [Final Configuration and Verification](060-configuration/020-final-configuration.md) | **Next Step:**
[Code Quality Tools Configuration](060-configuration/040-code-quality-tools.md)
