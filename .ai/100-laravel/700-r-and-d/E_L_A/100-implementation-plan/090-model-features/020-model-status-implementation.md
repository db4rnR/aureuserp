# Phase 1.6: Model Status Implementation

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
- [Comparing Model Status and Model States](#comparing-model-status-and-model-states)
- [Implementation Strategy](#implementation-strategy)
  - [Installation and Configuration](#1-installation-and-configuration)
  - [Using Model Status with Models](#2-using-model-status-with-models)
  - [Integration with Model States](#3-integration-with-model-states)
  - [Integration with Event Sourcing](#4-integration-with-event-sourcing)
  - [Displaying Status History in Filament](#5-displaying-status-history-in-filament)
- [Example Implementations](#example-implementations)
- [Benefits and Use Cases](#benefits-and-use-cases)
- [Conclusion](#conclusion)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document explores the implementation of `spatie/laravel-model-status` in the Enhanced Laravel Application (ELA) and how it can complement our existing use of `spatie/laravel-model-states` for state management.

## Prerequisites

Before implementing model status, ensure you have:

### Required Prior Steps
- [Event Sourcing Implementation](100-event-sourcing/050-implementation.md) completed
- [SoftDeletes and UserTracking Implementation](090-model-features/010-softdeletes-usertracking.md) completed
- All Phase 0 implementation steps completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed
- Spatie Laravel Model Status (`spatie/laravel-model-status`) installed
- Spatie Laravel Model States (`spatie/laravel-model-states`) installed
- Spatie Laravel Event Sourcing (`spatie/laravel-event-sourcing`) installed

### Required Knowledge
- Basic understanding of state management concepts
- Familiarity with Laravel Eloquent models
- Understanding of polymorphic relationships
- Knowledge of event sourcing principles

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x
- Database connection configured

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Installation and Configuration | 15 minutes |
| Using Model Status with Models | 30 minutes |
| Integration with Model States | 45 minutes |
| Integration with Event Sourcing | 45 minutes |
| Displaying Status History in Filament | 30 minutes |
| Testing Implementation | 30 minutes |
| **Total** | **195 minutes** |

> **Note:** These time estimates assume familiarity with Laravel and Spatie packages. Actual time may vary based on experience level and the complexity of your models.

## Comparing Model Status and Model States

### spatie/laravel-model-status

- **Purpose**: Track multiple statuses with history for a model
- **Storage**: Uses a separate `statuses` table
- **Features**:
  - Multiple status types per model
  - Complete history of status changes
  - Reasons and descriptions for changes
  - Simple API

### spatie/laravel-model-states

- **Purpose**: Implement the State pattern for models
- **Storage**: Uses a field in the model's table
- **Features**:
  - State-specific behavior
  - Explicit state transitions
  - Validation for transitions
  - Single state per field

## Implementation Strategy

### 1. Installation and Configuration

1. Install the package:
   ```bash
   composer require spatie/laravel-model-status:"^1.15"
   ```php
2. Publish and run the migrations:
   ```bash
   php artisan vendor:publish --provider="Spatie\ModelStatus\ModelStatusServiceProvider" --tag="migrations"
   php artisan migrate
   ```markdown
3. The migration will create a `statuses` table with the following structure:
   ```php
   Schema::create('statuses', function (Blueprint $table) {
       $table->increments('id');
       $table->string('name');
       $table->text('reason')->nullable();
       $table->morphs('model');
       $table->timestamps();
   });
   ```markdown
### 2. Using Model Status with Models

1. Add the `HasStatuses` trait to models:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStatus\HasStatuses;

class Todo extends Model
{
    use HasStatuses;

    // Define valid statuses (optional)
    public function getValidStatuses(): array
    {
        return ['review_requested', 'approved', 'rejected'];
    }
}
```text

2. Set and retrieve statuses:

```php
// Set a status
$todo->setStatus('review_requested', 'Needs technical review');

// Get the current status
$currentStatus = $todo->status; // returns 'review_requested'

// Get the reason
$reason = $todo->latestStatus()->reason; // returns 'Needs technical review'

// Get all statuses
$allStatuses = $todo->statuses;
```php
3. Using custom status models:

```php
<?php

namespace App\Models;

use Spatie\ModelStatus\Status as BaseStatus;

class Status extends BaseStatus
{
    // Add custom methods or relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```text

Then configure the model to use this custom status:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStatus\HasStatuses;

class Todo extends Model
{
    use HasStatuses;

    // Use custom status model
    protected $statusModel = \App\Models\Status::class;
}
```php
### 3. Integration with Model States

We can use both packages together, with `laravel-model-states` handling the primary state machine and `laravel-model-status` tracking additional statuses or providing a history:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStatus\HasStatuses;
use Spatie\ModelStates\HasStates;
use App\States\Todo\TodoState;

class Todo extends Model
{
    use HasStates, HasStatuses;

    protected $casts = [
        'status' => TodoState::class,
    ];

    // When the state changes, also record it in the statuses table
    public function setStatusAttribute($value)
    {
        $oldState = $this->status ? $this->status->getValue() : null;
        $newState = $value instanceof TodoState ? $value->getValue() : $value;

        // Set the state using model-states
        $this->attributes['status'] = $newState;

        // Also record in model-status if the state changed
        if ($oldState !== $newState) {
            $this->setStatus("state_changed_to_{$newState}", "State changed from {$oldState} to {$newState}");
        }
    }
}
```text

### 4. Integration with Event Sourcing

We can integrate `laravel-model-status` with our event sourcing implementation:

```php
<?php

namespace App\Projectors;

use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Events\TodoStatusChanged;
use App\Models\Todo;

class TodoProjector extends Projector
{
    public function onTodoStatusChanged(TodoStatusChanged $event, string $aggregateUuid)
    {
        $todo = Todo::findOrFail($aggregateUuid);

        // Update the state machine state
        $todo->status->transitionTo($event->to);

        // Also record in the statuses table with the reason
        $todo->setStatus("state_changed_to_{$event->to}", $event->reason);

        $todo->save();
    }
}
```php
### 5. Displaying Status History in Filament

We can create a Filament resource to display the status history:

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TodoResource\Pages;
use App\Models\Todo;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class TodoResource extends Resource
{
    // ... other resource configuration

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ... other columns

                // Add a column to show the current status
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => $state->getLabel())
                    ->color(fn ($state) => $state->getColor()),

                // Add a column to show the latest status from model-status
                Tables\Columns\TextColumn::make('latestStatus.name')
                    ->label('Latest Status')
                    ->searchable(),
            ])
            ->actions([
                // ... other actions

                // Add an action to view status history
                Tables\Actions\Action::make('viewStatusHistory')
                    ->label('Status History')
                    ->icon('heroicon-o-clock')
                    ->action(function (Todo $record) {
                        // This will be handled by the modal
                    })
                    ->modalHeading(fn (Todo $record) => "Status History for {$record->title}")
                    ->modalContent(function (Todo $record) {
                        $statuses = $record->statuses()->latest()->get()->map(function ($status) {
                            return [
                                'name' => $status->name,
                                'reason' => $status->reason,
                                'created_at' => $status->created_at->format('Y-m-d H:i:s'),
                                'user' => $status->user ? $status->user->name : 'System',
                            ];
                        });

                        return view('filament.modals.status-history', [
                            'statuses' => $statuses,
                        ]);
                    }),
            ]);
    }
}
```text

## Example Implementations

### 1. Todo Review Process

```php
// Request a review
$todo->setStatus('review_requested', 'Needs technical review by senior developer');

// Approve the todo
$todo->setStatus('approved', 'Code meets standards');

// Reject the todo
$todo->setStatus('rejected', 'Needs more test coverage');
```php
### 2. Post Publication Workflow

```php
// Submit for editorial review
$post->setStatus('editorial_review', 'Ready for editor review');

// Request revisions
$post->setStatus('revisions_requested', 'Needs grammar improvements');

// Mark as ready for publishing
$post->setStatus('ready_for_publishing', 'Approved by editorial team');
```text

### 3. User Verification Process

```php
// Email verification sent
$user->setStatus('email_verification_sent', 'Verification email sent');

// Email verified
$user->setStatus('email_verified', 'User verified their email');

// Identity verification requested
$user->setStatus('identity_verification_requested', 'User submitted ID documents');

// Identity verified
$user->setStatus('identity_verified', 'ID documents verified');
```

## Benefits and Use Cases

1. **Audit Trail**: Complete history of status changes with timestamps
2. **Multiple Status Types**: Track different aspects of a model's lifecycle
3. **Metadata**: Store reasons and descriptions for each status change
4. **User Attribution**: Record which user made each status change
5. **Complementary to State Machines**: Works alongside `laravel-model-states` for different purposes

## Conclusion

`spatie/laravel-model-status` provides a valuable complement to our existing state management with `spatie/laravel-model-states`. By using both packages together, we can maintain a strict state machine for core business logic while also tracking a complete history of status changes with additional metadata.

This approach gives us the best of both worlds: the structured state transitions and behavior of the State pattern, combined with the historical tracking and flexibility of the model status package.

> **Reference:**
> - [Spatie Laravel Model Status Documentation](https:/github.com/spatie/laravel-model-status)
> - [Spatie Laravel Model States Documentation](https:/spatie.be/docs/laravel-model-states/v2/introduction)

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: Status not being saved

**Symptoms:**
- Status changes are not being recorded
- No entries in the statuses table

**Possible Causes:**
- Missing `HasStatuses` trait in the model
- Migration not run for the statuses table
- Incorrect model relationship

**Solutions:**
1. Ensure the model uses the `HasStatuses` trait
2. Run `php artisan migrate` to create the statuses table
3. Check that the model relationship is correctly defined

### Issue: Cannot retrieve status history

**Symptoms:**
- Unable to retrieve status history
- Errors when calling status-related methods

**Possible Causes:**
- Incorrect method calls
- Missing status entries
- Database query issues

**Solutions:**
1. Verify the correct method calls (e.g., `statuses()`, `status()`, `latestStatus()`)
2. Check if status entries exist in the database
3. Debug database queries to identify issues

### Issue: Integration with Model States not working

**Symptoms:**
- Status changes not synchronized with state transitions
- Inconsistent state and status values

**Possible Causes:**
- Missing event listeners for state transitions
- Incorrect implementation of state transition events
- Circular dependencies between states and statuses

**Solutions:**
1. Ensure event listeners are registered for state transitions
2. Verify the implementation of state transition events
3. Review the architecture to avoid circular dependencies

### Issue: Integration with Event Sourcing not working

**Symptoms:**
- Status changes not recorded as events
- Events not triggering status changes

**Possible Causes:**
- Missing event dispatching in status changes
- Incorrect event handling
- Projector issues

**Solutions:**
1. Ensure status changes dispatch events
2. Verify event handlers are correctly implemented
3. Check projector implementation for status-related events

### Issue: Status display in Filament not working

**Symptoms:**
- Status history not displayed in Filament
- Errors in Filament UI

**Possible Causes:**
- Incorrect Filament resource configuration
- Missing relationship definitions
- Permissions issues

**Solutions:**
1. Check Filament resource configuration for status display
2. Verify relationship definitions in the Filament resource
3. Ensure proper permissions for viewing status history

</details>

## Related Documents

- [Event Sourcing Implementation](100-event-sourcing/050-implementation.md) - For previous implementation step
- [SoftDeletes and UserTracking Implementation](090-model-features/010-softdeletes-usertracking.md) - For SoftDeletes and UserTracking implementation
- [Status Implementation for Models](090-model-features/030-status-implementation.md) - For next implementation step

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [Event Sourcing Implementation](100-event-sourcing/050-implementation.md) | **Next Step:** [Status Implementation for Models](090-model-features/030-status-implementation.md)
