# Laravel Horizon Watcher

## 1. Overview

Laravel Horizon Watcher is a package by Spatie that monitors Laravel Horizon stats and notifies you when something goes wrong, such as when queue processing is paused or when jobs are taking too long to process.

### 1.1. Package Information

- **Package Name**: spatie/laravel-horizon-watcher
- **Version**: ^1.1
- **GitHub**: [https://github.com/spatie/laravel-horizon-watcher](https://github.com/spatie/laravel-horizon-watcher)
- **Documentation**: [https://github.com/spatie/laravel-horizon-watcher#readme](https://github.com/spatie/laravel-horizon-watcher#readme)

## 2. Key Features

- Monitors Laravel Horizon status
- Sends notifications when Horizon is paused
- Alerts when jobs are taking too long
- Customizable notification channels
- Configurable thresholds
- Low overhead
- Easy integration with existing Laravel Horizon setup
- Support for multiple notification channels

## 3. Installation

```bash
composer require --dev spatie/laravel-horizon-watcher
```

### 3.1. Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Spatie\HorizonWatcher\HorizonWatcherServiceProvider" --tag="horizon-watcher-config"
```

This creates a `config/horizon-watcher.php` file.

## 4. Configuration

### 4.1. Basic Configuration

The main configuration options in `config/horizon-watcher.php`:

```php
<?php

return [
    // Enable or disable the watcher
    'enabled' => env('HORIZON_WATCHER_ENABLED', true),
    
    // Notification channels
    'notifications' => [
        'channels' => ['mail', 'slack'],
        
        // Who should receive notifications
        'mail' => [
            'to' => ['admin@example.com'],
        ],
        
        'slack' => [
            'webhook_url' => env('HORIZON_WATCHER_SLACK_WEBHOOK_URL'),
        ],
    ],
    
    // Checks to perform
    'checks' => [
        // Check if Horizon is paused
        Spatie\HorizonWatcher\Checks\HorizonPausedCheck::class => [
            'enabled' => true,
        ],
        
        // Check if jobs are taking too long
        Spatie\HorizonWatcher\Checks\LongWaitTimesCheck::class => [
            'enabled' => true,
            'threshold_in_seconds' => 30,
        ],
        
        // Check if jobs are failing
        Spatie\HorizonWatcher\Checks\FailedJobCheck::class => [
            'enabled' => true,
        ],
    ],
];
```

### 4.2. Environment Configuration

In your `.env` file:

```
# Enable or disable the watcher
HORIZON_WATCHER_ENABLED=true

# Slack webhook URL for notifications
HORIZON_WATCHER_SLACK_WEBHOOK_URL=https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX
```

## 5. Usage

### 5.1. Basic Usage

Once installed and configured, Horizon Watcher will automatically monitor your Laravel Horizon instance and send notifications when issues are detected.

### 5.2. Running the Checks

Horizon Watcher runs checks automatically, but you can also run them manually:

```bash
php artisan horizon:check
```

### 5.3. Scheduling the Checks

Schedule the checks to run regularly:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('horizon:check')->everyFiveMinutes();
}
```

## 6. Integration with Laravel 12 and PHP 8.4

Laravel Horizon Watcher is fully compatible with Laravel 12 and PHP 8.4. It works seamlessly with Laravel Horizon and supports all notification channels available in Laravel.

## 7. Advanced Usage

### 7.1. Custom Checks

Create custom checks by implementing the `Check` interface:

```php
<?php

namespace App\HorizonChecks;

use Spatie\HorizonWatcher\Checks\Check;
use Spatie\HorizonWatcher\CheckResult;

class CustomCheck implements Check
{
    public function run(): CheckResult
    {
        // Perform your check logic here
        $hasIssue = false;
        
        // Example: Check if there are too many pending jobs
        $pendingJobs = \Laravel\Horizon\WaitTime::get('default');
        if ($pendingJobs > 100) {
            $hasIssue = true;
        }
        
        return new CheckResult(
            check: static::class,
            hasIssue: $hasIssue,
            message: $hasIssue
                ? "There are {$pendingJobs} pending jobs"
                : "Pending jobs are within acceptable limits"
        );
    }
}
```

Register the custom check in `config/horizon-watcher.php`:

```php
'checks' => [
    // Other checks...
    App\HorizonChecks\CustomCheck::class => [
        'enabled' => true,
    ],
],
```

### 7.2. Custom Notification Channels

Configure custom notification channels:

```php
'notifications' => [
    'channels' => ['mail', 'slack', 'discord', 'telegram'],
    
    'mail' => [
        'to' => ['admin@example.com', 'devops@example.com'],
    ],
    
    'slack' => [
        'webhook_url' => env('HORIZON_WATCHER_SLACK_WEBHOOK_URL'),
    ],
    
    'discord' => [
        'webhook_url' => env('HORIZON_WATCHER_DISCORD_WEBHOOK_URL'),
    ],
    
    'telegram' => [
        'bot_token' => env('HORIZON_WATCHER_TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('HORIZON_WATCHER_TELEGRAM_CHAT_ID'),
    ],
],
```

### 7.3. Custom Notification Classes

Create custom notification classes:

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Spatie\HorizonWatcher\CheckResult;

class CustomHorizonNotification extends Notification
{
    public function __construct(
        protected CheckResult $checkResult
    ) {
    }

    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->error()
            ->content("Horizon Issue Detected: {$this->checkResult->message}")
            ->attachment(function ($attachment) {
                $attachment->title('Check Details')
                    ->fields([
                        'Check' => class_basename($this->checkResult->check),
                        'Message' => $this->checkResult->message,
                        'Time' => now()->toDateTimeString(),
                    ]);
            });
    }
}
```

Configure the custom notification class:

```php
// config/horizon-watcher.php
'notification_class' => App\Notifications\CustomHorizonNotification::class,
```

## 8. Best Practices

1. **Set Appropriate Thresholds**: Configure thresholds based on your application's normal behavior
2. **Use Multiple Notification Channels**: Configure multiple channels for redundancy
3. **Schedule Regular Checks**: Run checks frequently enough to catch issues early
4. **Monitor in Production**: Horizon Watcher is especially valuable in production environments
5. **Combine with Other Monitoring**: Use alongside other monitoring tools for comprehensive coverage

## 9. Troubleshooting

### 9.1. Notifications Not Being Sent

If notifications aren't being sent:

1. Check that the watcher is enabled
2. Verify notification channel configurations
3. Ensure the notification channels are properly set up
4. Check that the checks are enabled
5. Run the checks manually to see if issues are detected

### 9.2. False Positives

If you're getting false positives:

1. Adjust thresholds to match your application's normal behavior
2. Disable checks that aren't relevant to your setup
3. Create custom checks with more specific logic
