# Code Generation Packages

This documentation covers tools used for generating code in the development workflow.

## 1. Package List

The following code generation packages are used in this project:

| Package | Version | Description |
|---------|---------|-------------|
| magentron/eloquent-model-generator | ^12.0.6 | Generate Eloquent models from database |
| barryvdh/laravel-ide-helper | ^3.5.5 | Generate IDE helper files |

## 2. Common Usage Patterns

### 2.1. Generating Models
# Code Generation Packages Documentation

## 1. Overview

This section documents all code generation packages available in the project's require-dev dependencies. These tools help automate repetitive tasks, create boilerplate code, and generate documentation.

## 2. Laravel Code Generation

### 2.1. Model Generation
- [Laravel IDE Helper](010-ide-helper.md) - PhpStorm helper for Laravel
- [Model Factory Generator](015-factory-generator.md) - Generating model factories

### 2.2. Command Generation
- [Artisan Commands](020-artisan-commands.md) - Built-in Laravel generators
- [Custom Generators](025-custom-generators.md) - Project-specific generators

## 3. API Documentation Generation

### 3.1. OpenAPI/Swagger
- [Laravel OpenAPI](030-openapi.md) - Generate OpenAPI documentation
- [Swagger UI](035-swagger-ui.md) - Interactive API documentation

### 3.2. API Client Generation
- [API Client Generator](040-api-client.md) - Generate client libraries
- [Postman Collection](045-postman.md) - Generate Postman collections

## 4. Database Generation

### 4.1. Schema Management
- [Schema Generation](050-schema-generation.md) - Generate database schemas
- [Migration Generation](055-migration-generation.md) - Generate migrations from models

### 4.2. Seeder Generation
- [Seeder Generation](060-seeder-generation.md) - Generate database seeders
- [Test Data Generation](065-test-data.md) - Generate test data

## 5. Frontend Integration

### 5.1. TypeScript Integration
- [TypeScript Definition Generator](070-typescript-generator.md) - Generate TypeScript types
- [API Types](075-api-types.md) - Generate types for API responses

### 5.2. Form Generation
- [Form Generator](080-form-generator.md) - Generate forms from models
- [Validation Schema Generator](085-validation-generator.md) - Generate validation schemas

## 6. Code Transformation

### 6.1. Code Refactoring
- [Laravel Shift](090-laravel-shift.md) - Automated Laravel upgrades
- [Rector](095-rector.md) - Automated code refactoring

### 6.2. Code Formatting
- [Laravel Pint](100-pint.md) - Format code automatically
- [Code Styling](105-code-styling.md) - Automated code styling

## 7. Best Practices

- [Code Generation Strategy](110-strategy.md) - When to use generation
- [Customizing Generators](115-customization.md) - Adapting to project needs
- [Maintaining Generated Code](120-maintenance.md) - Long-term management
Use Eloquent Model Generator to create models from existing database tables.

### 2.2. IDE Integration

Laravel IDE Helper provides better autocompletion and type hinting for IDEs.

## 3. Configuration

Each tool has specific configuration options detailed in their respective documentation pages.
