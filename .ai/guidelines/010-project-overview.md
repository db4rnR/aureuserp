# 1. Project Overview

## 1.1. Introduction

AureusERP is a comprehensive, open-source Enterprise Resource Planning (ERP) solution built on Laravel 12.x and FilamentPHP 3.x. It's designed for Small and Medium Enterprises (SMEs) and large-scale enterprises, offering a modular plugin architecture for managing various business operations.

## 1.2. Core Technologies

- **Laravel 12.x**: Modern PHP framework providing the foundation
- **FilamentPHP 3.x**: Admin panel framework for building the user interface
- **PHP 8.2+**: Taking advantage of modern PHP features
- **MySQL/PostgreSQL**: Database backend options

## 1.3. Project Structure

### 1.3.1. Core Directories

- `/app` - Core application code
- `/plugins` - Modular business logic organized by domain
- `/packages` - Custom packages and third-party integrations
- `/config` - Application configuration
- `/database` - Schema and data migrations
- `/resources` - Frontend assets and views
- `/routes` - URL routing definitions
- `/tests` - Testing infrastructure

### 1.3.2. Plugin Architecture

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

### 1.3.3. Key Plugins

#### 1.3.3.1. Core Plugins

- **Analytics**: Business intelligence and reporting
- **Chatter**: Internal communication
- **Fields**: Custom field definitions
- **Security**: Authentication and authorization
- **Support**: Customer support management
- **Table View**: Enhanced data visualization

#### 1.3.3.2. Business Plugins

- **Accounts**: Financial accounting
- **Contacts**: Customer and contact management
- **Employees**: Human resources
- **Inventories**: Stock management
- **Invoices**: Billing and invoicing
- **Partners**: Partner relationship management
- **Payments**: Payment processing
- **Products**: Product catalog management
- **Projects**: Project management
- **Purchases**: Procurement management
- **Recruitments**: Hiring and recruitment
- **Sales**: Sales management
- **Time-off**: Leave management
- **Timesheets**: Time tracking
- **Website**: Public-facing website management

## 1.4. FilamentPHP Integration

AureusERP uses FilamentPHP extensively for admin interfaces:

- Resources follow FilamentPHP conventions
- FilamentShield for permission management
- FilamentPHP form and table builders
- Dual-panel architecture (Admin/Customer)

## 1.5. Database Architecture

- Laravel migrations for schema changes
- Eloquent ORM for data access
- Proper relationships between entities
- Database seeders for test data

## 1.6. Security Framework

- Laravel security best practices
- FilamentShield for permission management
- Input validation
- Data encryption
- Laravel Sanctum for API authentication
