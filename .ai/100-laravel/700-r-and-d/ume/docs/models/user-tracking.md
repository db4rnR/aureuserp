# User Tracking for Models

This document explains how to use the `HasUserTracking` trait to automatically track which users create, update, and delete records in your database.

## Overview

The `HasUserTracking` trait automatically maintains the following attributes:

- `created_by_id`: The ID of the user who created the record
- `updated_by_id`: The ID of the user who last updated the record
- `deleted_by_id`: The ID of the user who deleted the record (for soft deletes)

## Features

- Automatically tracks user actions (create, update, delete, restore, force delete)
- Works with both web and API authentication
- Supports custom column naming via configuration
- Provides query scopes for filtering by user
- Includes helper methods for checking user actions
- Compatible with soft deletes and hard deletes
- Supports custom user models
- Tracks pivot tables in many-to-many relationships
- Provides detailed action history
- Integrates with Spatie's Activity Log package
- Allows temporarily disabling tracking
- Configurable via global configuration file

## Requirements

1. Your model's database table must have columns for tracking user actions. By default, these are:
   - `created_by_id` (foreign key to users table)
   - `updated_by_id` (foreign key to users table)
   - `deleted_by_id` (foreign key to users table)

2. For `deleted_by_id` tracking to work optimally, your model should use the `SoftDeletes` trait, though the trait will work without it.

## Adding User Tracking to a Model

### Step 1: Add the required columns to your table

#### Option 1: Use the provided Artisan command

The easiest way to add user tracking columns is to use the provided Artisan command:

```bash
php artisan make:user-tracking-migration your_table_name
```

This will generate a migration file with the necessary columns. You can then run the migration:

```bash
php artisan migrate
```

#### Option 2: Create a migration manually

Alternatively, you can create a migration manually:

```php
Schema::table('your_table_name', function (Blueprint $table) {
    $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('deleted_by_id')->nullable()->constrained('users')->nullOnDelete();
});
```

#### Using custom column names

If you want to use custom column names, you can specify them with the `--custom-columns` option:

```bash
php artisan make:user-tracking-migration your_table_name --custom-columns=author_id,editor_id,remover_id
```

### Step 2: Add the trait to your model

```php
use App\Models\Traits\HasUserTracking;

class YourModel extends Model
{
    use HasUserTracking;

    // If you want deleted_by tracking, also add:
    use SoftDeletes;

    // Rest of your model...
}
```

## Accessing User Information

### Relationships

The trait provides relationships to access the users who created, updated, or deleted a record:

```php
// Get the user who created the record
$model->createdBy;

// Get the user who last updated the record
$model->updatedBy;

// Get the user who deleted the record (for soft deletes)
$model->deletedBy;
```

### Query Scopes

The trait provides query scopes to filter records by user:

```php
// Get all records created by a specific user
$records = YourModel::createdBy($userId)->get();

// Get all records updated by a specific user
$records = YourModel::updatedBy($userId)->get();

// Get all records deleted by a specific user
$records = YourModel::deletedBy($userId)->get();
```

### Helper Methods

The trait provides helper methods to check if a specific user created, updated, or deleted a record:

```php
// Check if a specific user created the record
if ($model->wasCreatedBy($userId)) {
    // ...
}

// Check if a specific user updated the record
if ($model->wasUpdatedBy($userId)) {
    // ...
}

// Check if a specific user deleted the record
if ($model->wasDeletedBy($userId)) {
    // ...
}
```

## Customization

### Custom Column Names

You can customize the column names used for tracking by overriding the `getUserTrackingColumnName` method in your model:

```php
protected function getUserTrackingColumnName(string $type): string
{
    return match($type) {
        'created' => 'author_id',
        'updated' => 'editor_id',
        'deleted' => 'remover_id',
        default => $type . '_by_id',
    };
}
```

### Custom User Model

By default, the trait uses the user model specified in your auth configuration. You can customize this by overriding the `getUserClass` method:

```php
protected function getUserClass(): string
{
    return CustomUser::class;
}
```

## Advanced Features

### Temporarily Disabling Tracking

You can temporarily disable user tracking for specific operations:

```php
YourModel::withoutUserTracking(function () {
    // User tracking is disabled within this callback
    $model = YourModel::create([
        'title' => 'No tracking for this creation',
    ]);

    $model->update([
        'title' => 'No tracking for this update either',
    ]);

    return $model;
});

// User tracking is re-enabled outside the callback
```

### Tracking in Pivot Tables

The trait automatically tracks user actions in pivot tables for many-to-many relationships. Just make sure your pivot table has the tracking columns:

```php
Schema::create('model_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('model_id')->constrained();
    $table->foreignId('user_id')->constrained();
    $table->timestamps();

    // Add tracking columns
    $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
});
```

### Getting User Action History

You can get a complete history of user actions on a model:

```php
$history = $model->getUserActionHistory();

// Access specific actions
$creatorId = $history['created']['user_id'];
$creatorName = $history['created']['user']->name;
$creationDate = $history['created']['timestamp'];

// If using Spatie's Activity Log, you also get detailed history
foreach ($history['activity_log'] as $activity) {
    echo $activity['action'] . ' by ' . $activity['user']->name . ' at ' . $activity['timestamp'];
}
```

### Force Delete Tracking

When a model is force deleted, the information is logged either to Spatie's Activity Log (if available) or to the application log:

```php
// Force delete a model
$model->forceDelete();

// The action is logged with the user who performed it
```

### Restore Tracking

When a soft-deleted model is restored, the trait automatically:

1. Sets the `updated_by_id` to the current user
2. Clears the `deleted_by_id` field
3. Logs the restore action (if using Activity Log)

```php
// Restore a soft-deleted model
$model->restore();

// The model now has updated_by_id set to the current user
// and deleted_by_id set to null
```

## Global Configuration

You can configure user tracking globally via the `config/user-tracking.php` file:

```php
return [
    'enabled' => env('USER_TRACKING_ENABLED', true),
    'default_columns' => [
        'created' => 'created_by_id',
        'updated' => 'updated_by_id',
        'deleted' => 'deleted_by_id',
    ],
    'user_model' => \App\Models\User::class,
    'log_force_deletes' => true,
    'activity_log_enabled' => true,
];
```

## How It Works

The trait uses Laravel's model events to automatically set the user IDs:

- `creating`: Sets both `created_by_id` and `updated_by_id` to the current authenticated user
- `updating`: Sets `updated_by_id` to the current authenticated user
- `deleting`: Sets `deleted_by_id` to the current authenticated user (for soft deletes)
- `forceDeleting`: Logs the force delete action with the current user
- `restoring`: Updates `updated_by_id` and clears `deleted_by_id`

The trait attempts to get the current user from various authentication contexts:

1. Standard web authentication (`Auth::check()`)
2. API token authentication (Sanctum)
3. Custom contexts (can be extended for queue jobs, etc.)

If no user is authenticated in any context, the attributes remain null.
