# PHPStan Commands Reference

This document serves as a comprehensive reference for all PHPStan-related commands available in our project. We provide composer scripts for convenience and direct vendor bin commands for advanced usage.

## Composer Script Commands

| Command | Description |
|---------|-------------|
| `composer analyze` | Run PHPStan analysis on the codebase |
| `composer analyze:baseline` | Generate PHPStan baseline using default configuration |
| `composer analyze:fresh-baseline` | Generate a clean PHPStan baseline without dependency on existing configuration |
| `composer analyze:clear-cache` | Clear PHPStan cache directory |
| `composer analyze:fix` | Run Rector to automatically fix code issues |
| `composer analyze:show-errors` | Display PHPStan errors in detailed table format |

## Standard Analysis

Run regular PHPStan analysis:

```bash
composer analyze
```

This executes the following command:

```bash
./vendor/bin/phpstan analyse --memory-limit=1G --ansi
```

## Baseline Management

Generate a fresh, clean baseline (recommended approach):

```bash
composer analyze:fresh-baseline
```

This runs our custom baseline generator script that:
1. Removes any existing baseline
2. Creates a minimal PHPStan configuration
3. Generates a clean baseline
4. Cleans up temporary files

Alternatively, use the standard baseline generation:

```bash
composer analyze:baseline
```

Which executes:

```bash
./vendor/bin/phpstan analyse --generate-baseline --memory-limit=1G --ansi
```

## Cache Management

Clear the PHPStan cache:

```bash
composer analyze:clear-cache
```

This executes:
# PHPStan Command Reference

This guide provides a comprehensive reference for all PHPStan commands available in our Laravel 12 project.

## 1. Composer Scripts

We've defined convenient Composer scripts for common PHPStan operations:

| Script | Description |
|--------|-------------|
| `composer analyze` | Run PHPStan analysis on the entire project |
| `composer analyze:changed` | Analyze only files changed since last commit |
| `composer analyze:baseline` | Generate a new baseline file |
| `composer analyze:fresh-baseline` | Generate a completely new baseline file |
| `composer analyze:show-errors` | Show detailed error table |

## 2. Core PHPStan Commands

### 2.1. Basic Analysis

Run full static analysis:
```bash
rm -rf var/cache/phpstan/* && echo 'PHPStan cache cleared'
```

## Advanced Analysis Commands

### Detailed Error Display

Show errors in a detailed table format:

```bash
composer analyze:show-errors
```

This executes:

```bash
./vendor/bin/phpstan analyse --memory-limit=1G --error-format=table
```

### Automatic Error Fixing

Run Rector to automatically fix some PHPStan errors:

```bash
composer analyze:fix
```

This executes:

```bash
./vendor/bin/rector process
```

## Direct Vendor Commands

For advanced use cases, you can run PHPStan directly:

### Custom Memory Limit

```bash
vendor/bin/phpstan analyse --memory-limit=2G
```

### Analyze Specific Files

```bash
vendor/bin/phpstan analyse app/Models/User.php app/Services
```

### Custom Configuration

```bash
vendor/bin/phpstan analyse --configuration=my-custom-phpstan.neon
```

### Debug Mode

```bash
vendor/bin/phpstan analyse --debug
```

### Custom Error Format

```bash
vendor/bin/phpstan analyse --error-format=json > phpstan-results.json
```

## Integration with IDEs

### PhpStorm

For PhpStorm integration, configure an External Tool:
1. Go to Settings → Tools → External Tools
2. Add a new tool with:
   - Program: path to vendor/bin/phpstan
   - Arguments: analyse --error-format=table $FilePathRelativeToProjectRoot$
   - Working directory: $ProjectFileDir$
