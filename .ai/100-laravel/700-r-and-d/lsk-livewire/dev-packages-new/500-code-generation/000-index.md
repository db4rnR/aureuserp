# Code Generation Packages

This directory contains documentation for all code generation related packages used in the project.

## 1. Overview

Code generation tools help automate the creation of boilerplate code, improving productivity and ensuring consistency. This project uses several code generation tools to streamline development.

## 2. Code Generation Packages

| Package | Description | Documentation |
|---------|-------------|---------------|
| [Eloquent Model Generator](010-eloquent-model-generator.md) | Generate Eloquent models from database | [010-eloquent-model-generator.md](010-eloquent-model-generator.md) |
| [Laravel IDE Helper](020-ide-helper.md) | Generate IDE helper files | [020-ide-helper.md](020-ide-helper.md) |

## 3. Code Generation Workflow

The typical code generation workflow in this project includes:

1. Generating Eloquent models from the database schema
2. Generating IDE helper files for better IDE integration
3. Customizing generated code as needed

## 4. Composer Commands

This project includes several Composer scripts related to code generation:

```bash
# Generate IDE helper files
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta

# Generate Eloquent models
php artisan generate:model TableName
```

## 5. Configuration

Code generation tools are configured through:

- `.php-cs-fixer.php` - Code style for generated code
- `config/ide-helper.php` - IDE Helper configuration
- Custom templates for generated code

## 6. Best Practices

- Review and customize generated code
- Keep generated code under version control
- Regenerate code when the database schema changes
- Use consistent naming conventions
- Add proper documentation to generated code
