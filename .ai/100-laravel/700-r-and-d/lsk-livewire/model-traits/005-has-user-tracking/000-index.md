# HasUserTracking Trait

## 1. Overview

The `HasUserTracking` trait automatically tracks and maintains `created_by`, `updated_by`, and `deleted_by` attributes for Eloquent models. This documentation provides comprehensive information about the trait, its implementation, configuration, and usage.

## 2. Features

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

## 3. Documentation Structure

| Section | Description |
|---------|-------------|
| [Overview](000-index.md) | Introduction to the HasUserTracking trait |
| [Installation](005-installation.md) | How to install and set up the trait |
| [Configuration](010-configuration.md) | Configuration options and customization |
| [Usage](015-usage.md) | How to use the trait in your models |
| [Advanced Features](020-advanced-features.md) | Advanced features and techniques |
| [Examples](025-examples.md) | Example implementations |

## 4. Getting Started

To get started with the `HasUserTracking` trait, see the [Installation](005-installation.md) guide.
