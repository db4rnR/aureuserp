# 5. Plugin Architecture

## 5.1. Plugin System Overview

AureusERP uses a modular plugin architecture to organize business logic by domain. This approach allows for:

- Clear separation of concerns
- Independent development and testing
- Flexible deployment options
- Customization for different business needs
- Easier maintenance and updates

Each plugin represents a specific business domain or functionality and follows a consistent structure.

## 5.2. Plugin Structure

Plugins are organized under `/plugins/webkul/` with each plugin representing a business domain:

```
plugins/webkul/{module}/
├── composer.json          ← Composer package definition
├── src/
│   ├── {Module}Plugin.php ← FilamentPHP plugin class
│   ├── Models/           ← Domain models
│   ├── Resources/        ← FilamentPHP resources
│   └── Providers/        ← Service providers
├── database/
│   ├── migrations/       ← Database schema
│   └── seeders/         ← Sample data
└── tests/               ← Plugin-specific tests
```

### 5.2.1. Key Components

- **{Module}Plugin.php**: The main plugin class that registers with FilamentPHP
- **Service Providers**: Register the plugin's services with Laravel
- **Models**: Eloquent models representing the domain entities
- **Resources**: FilamentPHP resources for admin interfaces
- **Migrations**: Database schema definitions
- **Seeders**: Sample data for development and testing
- **Tests**: Plugin-specific test cases

## 5.3. Working with Plugins

### 5.3.1. Installing Plugins

To install a plugin:

```bash
php artisan <plugin-name>:install
```

This command:
- Registers the plugin with the application
- Runs database migrations
- Seeds initial data if needed
- Publishes any required assets
- Updates configuration files

### 5.3.2. Uninstalling Plugins

To remove a plugin:

```bash
php artisan <plugin-name>:uninstall
```

This command:
- Removes plugin registration
- Rolls back database migrations
- Removes published assets
- Restores configuration files

### 5.3.3. Enabling/Disabling Plugins

Plugins can be temporarily disabled without uninstalling:

```bash
php artisan plugin:disable <plugin-name>
php artisan plugin:enable <plugin-name>
```

## 5.4. Creating New Plugins

### 5.4.1. Plugin Generator

Use the plugin generator to create a new plugin scaffold:

```bash
php artisan make:plugin <plugin-name>
```

This creates the basic structure following AureusERP conventions.

### 5.4.2. Required Components

When creating a new plugin, ensure:

- Proper namespace under `Webkul\{PluginName}`
- Service provider registration
- FilamentPHP resource integration
- Database migrations and seeders
- Comprehensive README documentation

### 5.4.3. Plugin Registration

Register your plugin in the application by adding it to the `config/plugins.php` file:

```php
return [
    'enabled' => [
        // Other plugins...
        Webkul\YourPlugin\YourPluginServiceProvider::class,
    ],
];
```

### 5.4.4. Plugin Configuration

Each plugin should have its own configuration file:

```php
// config/yourplugin.php
return [
    'name' => 'Your Plugin',
    'prefix' => 'your-plugin',
    // Plugin-specific configuration...
];
```

## 5.5. Plugin Development Best Practices

### 5.5.1. Dependency Management

- Clearly define dependencies in composer.json
- Use interfaces for cross-plugin communication
- Avoid direct dependencies between plugins when possible
- Use Laravel's service container for dependency injection

### 5.5.2. Event-Driven Communication

- Use Laravel events for cross-plugin communication
- Define clear event contracts
- Document all events and their payloads
- Use event listeners for plugin integration

### 5.5.3. Database Considerations

- Use migrations for all database changes
- Follow naming conventions for tables: `{plugin_prefix}_{entity_name}`
- Use foreign keys for relationships
- Document database schema in README

### 5.5.4. Testing Requirements

- Write unit tests for all plugin functionality
- Create feature tests for plugin integration
- Test plugin installation and uninstallation
- Ensure test coverage for critical business logic

## 5.6. Plugin Documentation

### 5.6.1. README Requirements

Each plugin should include a comprehensive README.md with:

- Plugin description and purpose
- Installation instructions
- Configuration options
- Usage examples
- API documentation
- Database schema
- Testing instructions
- Troubleshooting guide

### 5.6.2. Code Documentation

- Document all public methods and classes
- Use PHP attributes for metadata
- Include usage examples for complex functionality
- Document database schema changes

## 5.7. Plugin Deployment

### 5.7.1. Versioning

- Follow semantic versioning (MAJOR.MINOR.PATCH)
- Document breaking changes in CHANGELOG.md
- Tag releases in git

### 5.7.2. Distribution

- Register plugin with Composer
- Provide clear installation instructions
- Document system requirements
- Include upgrade guides for major versions

### 5.7.3. Updates and Maintenance

- Provide migration paths for updates
- Test updates thoroughly
- Document deprecated features
- Maintain backward compatibility when possible
