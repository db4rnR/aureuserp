# Phase 1.4: SoftDeletes and User Tracking Implementation

**Version:** 1.0.2
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Updated
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [1. Introduction](#1-introduction)
  - [1.1. Purpose](#11-purpose)
  - [1.2. Scope](#12-scope)
- [Prerequisites](#prerequisites)
  - [Required Prior Steps](#required-prior-steps)
  - [Required Packages](#required-packages)
  - [Required Knowledge](#required-knowledge)
  - [Required Environment](#required-environment)
- [Estimated Time Requirements](#estimated-time-requirements)
- [2. Implementation Timeline](#2-implementation-timeline)
  - [2.1. Package Installation (Phase 0)](#21-package-installation-phase-0)
  - [2.2. Core Implementation (Phase 1)](#22-core-implementation-phase-1)
- [3. SoftDeletes Implementation](#3-softdeletes-implementation)
  - [3.1. Overview](#31-overview)
  - [3.2. Implementation Steps](#32-implementation-steps)
  - [3.3. Example Implementation](#33-example-implementation)
- [4. User Tracking Implementation](#4-user-tracking-implementation)
  - [4.1. Overview](#41-overview)
  - [4.2. Implementation Steps](#42-implementation-steps)
  - [4.3. Example Implementation](#43-example-implementation)
- [5. Base Migration Implementation](#5-base-migration-implementation)
  - [5.1. Creating the BaseMigration Class](#51-creating-the-basemigration-class)
  - [5.2. Using the BaseMigration Class](#52-using-the-basemigration-class)
- [6. Testing and Verification](#6-testing-and-verification)
  - [6.1. Testing SoftDeletes](#61-testing-softdeletes)
  - [6.2. Testing User Tracking](#62-testing-user-tracking)
- [7. References](#7-references)
- [8. Troubleshooting](#8-troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)

</details>

## 1. Introduction

### 1.1. Purpose

This document provides detailed guidance on implementing SoftDeletes and user tracking (userstamps) in the Enhanced Laravel Application (ELA). It outlines when and how these features should be implemented according to the project's implementation plan.

### 1.2. Scope

This document covers:
- The timeline for implementing SoftDeletes and user tracking
- Detailed implementation steps for both features
- Creation of a BaseMigration class for consistent implementation
- Testing and verification procedures

## Prerequisites

Before implementing SoftDeletes and user tracking, ensure you have:

### Required Prior Steps
- [Phase 0 Summary](070-phase-summaries/010-phase0-summary.md) reviewed
- [GitHub Workflows](080-infrastructure/010-github-workflows.md) configured
- All Phase 0 implementation steps completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed
- Wildside Userstamps (`wildside/userstamps`) installed

### Required Knowledge
- Basic understanding of Laravel Eloquent models
- Familiarity with Laravel migrations
- Understanding of soft deletion concepts
- Knowledge of user tracking (userstamps) concepts

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x
- Database connection configured

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Implement SoftDeletes | 30 minutes |
| Implement User Tracking | 30 minutes |
| Create BaseMigration Class | 20 minutes |
| Test and Verify Implementation | 30 minutes |
| **Total** | **110 minutes** |

> **Note:** These time estimates assume familiarity with Laravel Eloquent and migrations. Actual time may vary based on experience level and the complexity of your models.

## 2. Implementation Timeline

### 2.1. Package Installation (Phase 0)

During Phase 0 (Development Environment & Laravel Setup), the necessary packages for SoftDeletes and user tracking are installed:

1. **SoftDeletes**: This is a built-in feature of Laravel, so no additional package installation is required.

2. **User Tracking**: The `wildside/userstamps` package is installed during Phase 0:
   ```bash
   composer require wildside/userstamps:^2.2
   ```

### 2.2. Core Implementation (Phase 1)

The actual implementation of SoftDeletes and user tracking occurs during Phase 1 (Core Infrastructure) when:

1. The database schema is implemented
2. Base models are created
3. Core traits are defined

This is the phase where:
- A `BaseMigration` class is created with methods for adding timestamps, userstamps, softDeletes, and softUserstamps to tables
- Models are configured to use the `SoftDeletes` trait and user tracking functionality

## 3. SoftDeletes Implementation

### 3.1. Overview

SoftDeletes is a Laravel feature that allows records to be "soft deleted" by setting a `deleted_at` timestamp rather than actually removing the record from the database. This enables the application to:

- Filter out "deleted" records from normal queries
- Restore "deleted" records if needed
- Permanently delete records when appropriate

### 3.2. Implementation Steps

1. **Create Migration Files**:
   - Use the `BaseMigration` class (see Section 5) to add the `deleted_at` column to all core model tables

2. **Update Model Classes**:
   - Add the `SoftDeletes` trait to all core model classes:
     ```php
     use Illuminate\Database\Eloquent\SoftDeletes;

     class YourModel extends Model
     {
         use SoftDeletes;

         // Rest of the model code
     }
     ```markdown
3. **Configure Global Scopes**:
   - The `SoftDeletes` trait automatically adds a global scope to exclude soft-deleted records from queries
   - No additional configuration is needed for basic functionality

### 3.3. Example Implementation

```php
// Example model implementation with SoftDeletes
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Rest of the model code
}
```

## 4. User Tracking Implementation

### 4.1. Overview

User tracking (userstamps) allows the application to track which user created, updated, or deleted a record. This is implemented using the `wildside/userstamps` package, which adds:

- `created_by` - The ID of the user who created the record
- `updated_by` - The ID of the user who last updated the record
- `deleted_by` - The ID of the user who deleted the record (when used with SoftDeletes)

### 4.2. Implementation Steps

1. **Create Migration Files**:
   - Use the `BaseMigration` class (see Section 5) to add the `created_by`, `updated_by`, and `deleted_by` columns to all core model tables

2. **Update Model Classes**:
- Add the `Userstamps` trait to all core model classes:
  ```php
  use Wildside\Userstamps\Userstamps;

  class YourModel extends Model
  {
      use Userstamps;

      // Rest of the model code
  }
  ```
3. **Configure User Provider**:
   - The `Userstamps` trait automatically uses the authenticated user for tracking
   - No additional configuration is needed for basic functionality

### 4.3. Example Implementation

```php
// Example model implementation with Userstamps
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Userstamps;

    // Rest of the model code
}
```

## 5. Base Migration Implementation

### 5.1. Creating the BaseMigration Class

Create a `BaseMigration` class in `database/migrations` to ensure consistent implementation of timestamps, userstamps, softDeletes, and softUserstamps across all migrations:

```php
<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

abstract class BaseMigration extends Migration
{
    /**
     * Add timestamps, userstamps, softDeletes, and softUserstamps to a table.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     * @return void
     */
    protected function timestampsWithUserstamps(Blueprint $table)
    {
        $table->timestamps();
        $table->userstamps();
        $table->softDeletes();
        $table->softUserstamps();
    }
}
```

### 5.2. Using the BaseMigration Class

Use the `BaseMigration` class in all core model migrations:

```php
<?php

use Database\Migrations\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            // Other columns...

            // Add timestamps, userstamps, softDeletes, and softUserstamps
            $this->timestampsWithUserstamps($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
```

## 6. Testing and Verification

### 6.1. Testing SoftDeletes

Create tests to verify that SoftDeletes is working correctly:

```php
// Example test for SoftDeletes
public function test_soft_deletes_works_correctly()
{
    // Create a model instance
    $post = Post::factory()->create();

    // Soft delete the model
    $post->delete();

    // Verify the model is not returned in normal queries
    $this->assertNull(Post::find($post->id));

    // Verify the model is returned when including trashed models
    $this->assertNotNull(Post::withTrashed()->find($post->id));

    // Verify the deleted_at timestamp is set
    $this->assertNotNull($post->fresh()->deleted_at);
}
```

### 6.2. Testing User Tracking

Create tests to verify that user tracking is working correctly:

```php
// Example test for user tracking
public function test_user_tracking_works_correctly()
{
    // Create a user and authenticate
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create a model instance
    $post = Post::factory()->create();

    // Verify created_by is set correctly
    $this->assertEquals($user->id, $post->created_by);

    // Update the model
    $post->update(['title' => 'Updated Title']);

    // Verify updated_by is set correctly
    $this->assertEquals($user->id, $post->fresh()->updated_by);

    // Soft delete the model
    $post->delete();

    // Verify deleted_by is set correctly
    $this->assertEquals($user->id, Post::withTrashed()->find($post->id)->deleted_by);
}
```

## 7. References

- [Laravel SoftDeletes Documentation](https:/laravel.com/docs/12.x/eloquent#soft-deleting)
- [Wildside Userstamps Documentation](https:/github.com/wildside/userstamps)
- [ELA Product Requirements Document (PRD)](../010-000-ela-prd.md)
- [ELA Technical Architecture Document (TAD)](../030-ela-tad.md)
- [ELA Implementation Plan](010-overview/010-implementation-plan-overview.md)

## 8. Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: SoftDeletes not working correctly

**Symptoms:**
- Records are permanently deleted instead of soft deleted
- Soft deleted records are not excluded from queries

**Possible Causes:**
- Missing `SoftDeletes` trait in the model
- Missing `deleted_at` column in the database table
- Incorrect query methods being used

**Solutions:**
1. Ensure the model uses the `SoftDeletes` trait
2. Verify the database table has a `deleted_at` column
3. Use `withTrashed()` or `onlyTrashed()` methods when needed

### Issue: User tracking not recording correctly

**Symptoms:**
- User IDs not being recorded in created_by, updated_by, or deleted_by fields
- Wrong user IDs being recorded

**Possible Causes:**
- Missing `Userstamps` trait in the model
- Missing user tracking columns in the database table
- Authentication issues (user not properly authenticated)

**Solutions:**
1. Ensure the model uses the `Userstamps` trait
2. Verify the database table has the required columns
3. Check that the user is properly authenticated when performing operations

### Issue: BaseMigration class not working

**Symptoms:**
- Migrations fail when using the BaseMigration class
- SoftDeletes or user tracking columns not added correctly

**Possible Causes:**
- BaseMigration class not properly implemented
- Incorrect method calls in migrations
- Conflicts with existing columns

**Solutions:**
1. Verify the BaseMigration class implementation
2. Check the method calls in migrations
3. Ensure there are no column name conflicts

### Issue: Queries returning unexpected results

**Symptoms:**
- Queries return soft deleted records unexpectedly
- Queries don't return records that should be included

**Possible Causes:**
- Global scopes interfering with queries
- Incorrect use of withTrashed() or onlyTrashed()
- Relationships not properly configured for soft deletes

**Solutions:**
1. Use `withoutGlobalScopes()` when needed
2. Verify the correct use of withTrashed() and onlyTrashed()
3. Configure relationships to respect soft deletes

</details>

## Related Documents

- [GitHub Workflows](080-infrastructure/010-github-workflows.md) - For CI/CD workflow configuration
- [Event Sourcing Implementation](100-event-sourcing/050-implementation.md) - For next implementation step
- [Model Status Implementation](090-model-features/020-model-status-implementation.md) - For model status implementation

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [GitHub Workflows](080-infrastructure/010-github-workflows.md) | **Next Step:** [Event Sourcing Implementation](100-event-sourcing/050-implementation.md)
