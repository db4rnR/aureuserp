# Phase 1: Phase 0.14: Logging and Monitoring Setup

**Version:** 1.0.2
**Date:** 2023-11-13
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
- [Step 1: Configure Laravel Logging](#step-1-configure-laravel-logging)
- [Step 2: Set Up Activity Logging](#step-2-set-up-activity-logging)
- [Step 3: Configure Error Tracking](#step-3-configure-error-tracking)
- [Step 4: Set Up Monitoring Tools](#step-4-set-up-monitoring-tools)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides instructions for setting up logging and monitoring for the Enhanced Laravel Application (ELA). It covers configuring Laravel logging, activity logging, and error tracking.

> **Reference:** [Laravel 12.x Logging Documentation](https:/laravel.com/docs/12.x/logging) and [Laravel 12.x Error Handling Documentation](https:/laravel.com/docs/12.x/errors)

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Package Installation](030-core-components/010-package-installation.md) completed
- [Testing Environment Setup](050-security-testing/020-testing-setup.md) completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed
- Spatie Laravel Activity Log (`spatie/laravel-activitylog`) installed
- Laravel Telescope (`laravel/telescope`) installed
- Laravel Health (`spatie/laravel-health`) installed

### Required Knowledge
- Basic understanding of logging concepts
- Familiarity with Laravel's logging system
- Understanding of monitoring and error tracking

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x
- Redis for queue processing (optional)

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Configure Laravel Logging | 15 minutes |
| Set Up Activity Logging | 20 minutes |
| Configure Error Tracking | 15 minutes |
| Set Up Monitoring Tools | 30 minutes |
| Configure Telescope | 20 minutes |
| Set Up Health Checks | 20 minutes |
| **Total** | **120 minutes** |

> **Note:** These time estimates assume familiarity with Laravel logging and monitoring. Actual time may vary based on experience level and the complexity of your application.

## Step 1: Configure Laravel Logging

1. Configure logging in `config/logging.php`:
   ```php
   return [
       'default' => env('LOG_CHANNEL', 'stack'),

       'deprecations' => [
           'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
           'trace' => env('LOG_DEPRECATIONS_TRACE', false),
       ],

       'channels' => [
           'stack' => [
               'driver' => 'stack',
               'channels' => ['single', 'daily', 'slack'],
               'ignore_exceptions' => false,
           ],

           'single' => [
               'driver' => 'single',
               'path' => storage_path('logs/laravel.log'),
               'level' => env('LOG_LEVEL', 'debug'),
               'replace_placeholders' => true,
           ],

           'daily' => [
               'driver' => 'daily',
               'path' => storage_path('logs/laravel.log'),
               'level' => env('LOG_LEVEL', 'debug'),
               'days' => env('LOG_DAILY_DAYS', 14),
               'replace_placeholders' => true,
           ],

           'slack' => [
               'driver' => 'slack',
               'url' => env('LOG_SLACK_WEBHOOK_URL'),
               'username' => env('LOG_SLACK_USERNAME', 'ELA Log'),
               'emoji' => env('LOG_SLACK_EMOJI', ':boom:'),
               'level' => env('LOG_SLACK_LEVEL', 'critical'),
               'replace_placeholders' => true,
           ],

           'papertrail' => [
               'driver' => 'monolog',
               'level' => env('LOG_LEVEL', 'debug'),
               'handler' => env('LOG_PAPERTRAIL_HANDLER', \Monolog\Handler\SyslogUdpHandler::class),
               'handler_with' => [
                   'host' => env('PAPERTRAIL_URL'),
                   'port' => env('PAPERTRAIL_PORT'),
                   'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
               ],
               'processors' => [
                   \Monolog\Processor\PsrLogMessageProcessor::class,
               ],
           ],

           'stderr' => [
               'driver' => 'monolog',
               'level' => env('LOG_LEVEL', 'debug'),
               'handler' => \Monolog\Handler\StreamHandler::class,
               'formatter' => env('LOG_STDERR_FORMATTER'),
               'with' => [
                   'stream' => 'php://stderr',
               ],
               'processors' => [
                   \Monolog\Processor\PsrLogMessageProcessor::class,
               ],
           ],

           'syslog' => [
               'driver' => 'syslog',
               'level' => env('LOG_LEVEL', 'debug'),
               'facility' => env('LOG_SYSLOG_FACILITY', LOG_USER),
               'replace_placeholders' => true,
           ],

           'errorlog' => [
               'driver' => 'errorlog',
               'level' => env('LOG_LEVEL', 'debug'),
               'replace_placeholders' => true,
           ],

           'null' => [
               'driver' => 'monolog',
               'handler' => \Monolog\Handler\NullHandler::class,
           ],

           'emergency' => [
               'path' => storage_path('logs/emergency.log'),
           ],
       ],
   ];
   ```

   > **Reference:** [Laravel 12.x Logging Configuration Documentation](https:/laravel.com/docs/12.x/logging#configuration)

2. Update the `.env` file with logging configuration:
   ```
   LOG_CHANNEL=stack
   LOG_DEPRECATIONS_CHANNEL=null
   LOG_DEPRECATIONS_TRACE=false
   LOG_LEVEL=debug
   LOG_DAILY_DAYS=14
   LOG_SLACK_WEBHOOK_URL=
   LOG_SLACK_USERNAME="ELA Log"
   LOG_SLACK_EMOJI=":boom:"
   LOG_SLACK_LEVEL=critical
   ```

   > **Reference:** [Laravel 12.x Environment Configuration Documentation](https:/laravel.com/docs/12.x/configuration#environment-configuration)

3. Create a custom logger service:
   ```bash
   php artisan make:service LoggerService
   ```

4. Configure the logger service in `app/Services/LoggerService.php` with contextual information support:
   ```php
   <?php

   namespace App\Services;

   use Illuminate\Support\Facades\Log;

   class LoggerService
   {
       /**
        * Log an emergency message.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function emergency(string $message, array $context = []): void
       {
           Log::emergency($message, $context);
       }

       /**
        * Log an alert message.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function alert(string $message, array $context = []): void
       {
           Log::alert($message, $context);
       }

       /**
        * Log a critical message.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function critical(string $message, array $context = []): void
       {
           Log::critical($message, $context);
       }

       /**
        * Log an error message.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function error(string $message, array $context = []): void
       {
           Log::error($message, $context);
       }

       /**
        * Log a warning message.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function warning(string $message, array $context = []): void
       {
           Log::warning($message, $context);
       }

       /**
        * Log a notice message.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function notice(string $message, array $context = []): void
       {
           Log::notice($message, $context);
       }

       /**
        * Log an info message.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function info(string $message, array $context = []): void
       {
           Log::info($message, $context);
       }

       /**
        * Log a debug message.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function debug(string $message, array $context = []): void
       {
           Log::debug($message, $context);
       }
   }
   ```

## Step 2: Set Up Activity Logging

1. Configure the Spatie Laravel Activity Log package in `config/activitylog.php`:
   ```php
   return [
       /*
       |--------------------------------------------------------------------------
       | Activity Log Database Connection
       |--------------------------------------------------------------------------
       |
       | This value determines the database connection that will be used to
       | write activity logs. In case you want to use a different connection
       | you can specify it here.
       |
       */
       'database_connection' => env('ACTIVITY_LOGGER_DB_CONNECTION', env('DB_CONNECTION', 'pgsql')),

       /*
       |--------------------------------------------------------------------------
       | Activity Log Enabled
       |--------------------------------------------------------------------------
       |
       | This value determines if the activity logger will be enabled. If you
       | want to disable activity logging, set this value to false.
       |
       */
       'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

       /*
       |--------------------------------------------------------------------------
       | Activity Log Table Name
       |--------------------------------------------------------------------------
       |
       | This value determines the name of the table that will be used to store
       | activity logs. If you want to use a different table name, you can
       | specify it here.
       |
       */
       'table_name' => env('ACTIVITY_LOGGER_TABLE_NAME', 'activity_log'),

       /*
       |--------------------------------------------------------------------------
       | Activity Log Default Log Name
       |--------------------------------------------------------------------------
       |
       | This value determines the default log name that will be used to store
       | activity logs. If you want to use a different log name, you can
       | specify it here.
       |
       */
       'default_log_name' => env('ACTIVITY_LOGGER_DEFAULT_LOG_NAME', 'default'),

       /*
       |--------------------------------------------------------------------------
       | Activity Log Default Auth Driver
       |--------------------------------------------------------------------------
       |
       | This value determines the default auth driver that will be used to
       | retrieve the user model. If you want to use a different auth driver,
       | you can specify it here.
       |
       */
       'default_auth_driver' => env('ACTIVITY_LOGGER_DEFAULT_AUTH_DRIVER', null),

       /*
       |--------------------------------------------------------------------------
       | Activity Log Subject Returns
       |--------------------------------------------------------------------------
       |
       | This value determines if the subject returns soft deleted models. If you
       | want to return soft deleted models, set this value to true.
       |
       */
       'subject_returns_soft_deleted_models' => env('ACTIVITY_LOGGER_SUBJECT_RETURNS_SOFT_DELETED_MODELS', false),

       /*
       |--------------------------------------------------------------------------
       | Activity Log Queue
       |--------------------------------------------------------------------------
       |
       | This value determines if the activity logger should be queued. If you
       | want to queue activity logging, set this value to true.
       |
       */
       'queue' => env('ACTIVITY_LOGGER_QUEUE', false),

       /*
       |--------------------------------------------------------------------------
       | Activity Log Queue Name
       |--------------------------------------------------------------------------
       |
       | This value determines the name of the queue that will be used to queue
       | activity logs. If you want to use a different queue name, you can
       | specify it here.
       |
       */
       'queue_name' => env('ACTIVITY_LOGGER_QUEUE_NAME', 'default'),
   ];
   ```

2. Create a trait for activity logging in `app/Traits/LogsActivity.php`:
   ```php
   <?php

   namespace App\Traits;

   use Illuminate\Database\Eloquent\Model;
   use Spatie\Activitylog\LogOptions;
   use Spatie\Activitylog\Traits\LogsActivity as SpatieLogsActivity;

   trait LogsActivity
   {
       use SpatieLogsActivity;

       /**
        * Get the activity log options for the model.
        *
        * @return LogOptions
        */
       public function getActivitylogOptions(): LogOptions
       {
           return LogOptions::defaults()
               ->logFillable()
               ->logOnlyDirty()
               ->dontSubmitEmptyLogs();
       }

       /**
        * Get the description for the activity log.
        *
        * @param string $eventName
        * @return string
        */
       public function getDescriptionForEvent(string $eventName): string
       {
           return "This model has been {$eventName}";
       }
   }
   ```

## Step 3: Configure Error Tracking

1. Create an error handler service:
   ```bash
   php artisan make:service ErrorHandlerService
   ```

2. Configure the error handler service in `app/Services/ErrorHandlerService.php`:
   ```php
   <?php

   namespace App\Services;

   use Exception;
   use Illuminate\Support\Facades\Log;

   class ErrorHandlerService
   {
       /**
        * Handle an exception.
        *
        * @param Exception $exception
        * @param array $context
        * @return void
        */
       public function handleException(Exception $exception, array $context = []): void
       {
           $context = array_merge($context, [
               'file' => $exception->getFile(),
               'line' => $exception->getLine(),
               'trace' => $exception->getTraceAsString(),
           ]);

           Log::error($exception->getMessage(), $context);
       }

       /**
        * Report an error.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function reportError(string $message, array $context = []): void
       {
           Log::error($message, $context);
       }

       /**
        * Report a warning.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function reportWarning(string $message, array $context = []): void
       {
           Log::warning($message, $context);
       }

       /**
        * Report an info message.
        *
        * @param string $message
        * @param array $context
        * @return void
        */
       public function reportInfo(string $message, array $context = []): void
       {
           Log::info($message, $context);
       }
   }
   ```

## Step 4: Set Up Monitoring Tools

1. Install Laravel Pail for log tailing:
   ```bash
   composer require laravel/pail:"^1.0" --dev
   ```

   > **Reference:** [Laravel Pail Documentation](https:/laravel.com/docs/12.x/logging#tailing-log-messages-using-pail)

2. Install Laravel Telescope for debugging and monitoring:
   ```bash
   composer require laravel/telescope:"^5.0" --dev
   php artisan telescope:install
   php artisan migrate
   ```

3. Configure Telescope in `config/telescope.php`:
   ```php
   'enabled' => env('TELESCOPE_ENABLED', app()->environment('local')),

   'middleware' => [
       'web',
       \Laravel\Telescope\Http\Middleware\Authorize::class,
   ],

   'storage' => [
       'database' => [
           'connection' => env('DB_CONNECTION', 'mysql'),
           'chunk' => 1000,
       ],
   ],
   ```

4. Update the `.env` file with Telescope configuration:
   ```
   TELESCOPE_ENABLED=true
   ```

5. Configure Telescope authorization in `app/Providers/TelescopeServiceProvider.php`:
   ```php
   /**
    * Register the Telescope gate.
    *
    * This gate determines who can access Telescope in non-local environments.
    */
   protected function gate(): void
   {
       Gate::define('viewTelescope', function (User $user) {
           return in_array($user->email, [
               'admin@example.com',
               // Add authorized emails here
           ]);
       });
   }
   ```

   > **Reference:** [Laravel Telescope Documentation](https:/laravel.com/docs/12.x/telescope)

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: Logs not being written

**Symptoms:**
- No log files are being created
- Log files exist but are empty

**Possible Causes:**
- Incorrect log channel configuration
- Permission issues with log directory
- Log driver not properly configured

**Solutions:**
1. Check the log channel configuration in `config/logging.php`
2. Ensure the log directory is writable by the web server
3. Verify that the selected log driver is properly configured

### Issue: Activity logs not recording

**Symptoms:**
- No activity logs in the database
- Missing activity for specific models

**Possible Causes:**
- Models not using the `LogsActivity` trait
- Incorrect configuration of logged attributes
- Database migration not run

**Solutions:**
1. Ensure models use the `LogsActivity` trait
2. Check the `getActivitylogOptions()` method in models
3. Run `php artisan migrate` to create the activity_log table

### Issue: Telescope not showing data

**Symptoms:**
- Telescope dashboard is empty
- No requests or logs are being recorded

**Possible Causes:**
- Telescope not enabled in the current environment
- Authorization gate preventing access
- Pruning happening too frequently

**Solutions:**
1. Check the `enabled` method in `TelescopeServiceProvider`
2. Verify the authorization gate in `TelescopeServiceProvider`
3. Adjust pruning settings in the Telescope configuration

### Issue: Health checks failing

**Symptoms:**
- Health checks show failures
- Email notifications about failed checks

**Possible Causes:**
- Resource constraints (disk space, memory)
- Services not running (Redis, database)
- Incorrect thresholds for checks

**Solutions:**
1. Address resource constraints
2. Ensure all required services are running
3. Adjust thresholds in health check configuration

</details>

## Related Documents

- [Testing Environment Setup](050-security-testing/020-testing-setup.md) - For testing configuration
- [Custom AppServiceProvider](060-configuration/010-app-service-provider.md) - For service provider configuration
- [Final Configuration](060-configuration/020-final-configuration.md) - For final configuration steps

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [Testing Environment Setup](050-security-testing/020-testing-setup.md) | **Next Step:** [Custom AppServiceProvider Configuration](060-configuration/010-app-service-provider.md)
