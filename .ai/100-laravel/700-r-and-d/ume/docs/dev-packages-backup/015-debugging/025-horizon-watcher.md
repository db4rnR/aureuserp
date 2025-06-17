# Laravel Horizon Watcher

## 1. Overview

Laravel Horizon Watcher is a package by Spatie that monitors Laravel Horizon stats and notifies you when something goes wrong, such as when queue processing is paused or when jobs are taking too long to process.

### 1.1. Package Information

- **Package Name**: spatie/laravel-horizon-watcher
- **Version**: ^1.1
- **GitHub**: [https://github.com/spatie/laravel-horizon-watcher](https://github.com/spatie/laravel-horizon-watcher)
- **Documentation**: [https://github.com/spatie/laravel-horizon-watcher#readme](https://github.com/spatie/laravel-horizon-watcher#readme)

## 2. Key Features

- Monitors Horizon's status (paused/active)
- Tracks job processing time
- Alerts when jobs are taking too long
- Notifies when queues are backed up
- Multiple notification channels (Slack, email, etc.)
- Customizable thresholds

## 3. Usage Examples

### 3.1. Basic Setup

Once installed and configured, Horizon Watcher runs automatically.

```php
<?php

declare(strict_types=1);

// The package will use your defined notification channels
// No manual integration required in your code
```

### 3.2. Custom Notifications

```php
<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Spatie\HorizonWatcher\Notifications\HorizonWatcherNotification;

class CustomHorizonNotification extends HorizonWatcherNotification
{
    public function toSlack($notifiable): SlackMessage
    {
        return (new SlackMessage())
            ->error()
            ->content("⚠️ Horizon alert: {$this->message}");
    }
}
```

## 4. Configuration

The configuration file is published at `config/horizon-watcher.php`:

```php
<?php

declare(strict_types=1);

return [
    // Whether the watcher is enabled
    'enabled' => env('HORIZON_WATCHER_ENABLED', true),

    // The channels that will be used to send notifications
    'notifications' => [
        'channels' => ['slack'],
        
        // The notification class that will be used
        'notification_class' => \Spatie\HorizonWatcher\Notifications\HorizonWatcherNotification::class,
    ],
    
    // Check whether Horizon is active
    'check_horizon_status' => [
        'enabled' => true,
        'check_interval_in_seconds' => 60,
    ],
    
    // Check if jobs are taking too long
    'check_long_wait_times' => [
        'enabled' => true,
        'check_interval_in_seconds' => 30,
        'wait_time_threshold_in_seconds' => 30,
    ],
    
    // Check if there are too many jobs in the queue
    'check_long_queues' => [
        'enabled' => true,
        'check_interval_in_seconds' => 30,
        'queue_length_threshold' => 100,
    ],
];
```

## 5. Best Practices

### 5.1. Production Usage

In production environments, send notifications to appropriate channels:

```php
<?php

declare(strict_types=1);

// In config/horizon-watcher.php
'notifications' => [
    'channels' => ['slack', 'email'],
],
```

### 5.2. Customizing Thresholds

Adjust thresholds based on your application's characteristics:

```php
<?php

declare(strict_types=1);

// For high-traffic applications
'check_long_queues' => [
    'enabled' => true,
    'check_interval_in_seconds' => 30,
    'queue_length_threshold' => 500, // Higher threshold
],

// For time-sensitive jobs
'check_long_wait_times' => [
    'enabled' => true,
    'check_interval_in_seconds' => 15, // More frequent checks
    'wait_time_threshold_in_seconds' => 15, // Lower threshold
],
```

### 5.3. Integration with Monitoring

Pair with Laravel Pulse for comprehensive monitoring:

```php
<?php

declare(strict_types=1);

// In app/Providers/AppServiceProvider.php
public function boot()
{
    if (app()->environment('production')) {
        // Ensure both monitoring tools are enabled
        config(['horizon-watcher.enabled' => true]);
        config(['pulse.enabled' => true]);
    }
}
```
