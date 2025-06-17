# PHPStan Troubleshooting

## 1. Overview

This guide addresses common issues you might encounter when using PHPStan with our Laravel 12 project and provides solutions to resolve them.

## 2. Common Issues and Solutions

### 2.1. Memory Limit Errors

**Issue**: PHPStan terminates with "Allowed memory size exhausted"

**Solutions**:

1. Increase memory limit in your PHPStan command:
   ```bash
   php -d memory_limit=1G vendor/bin/phpstan analyse
   ```

2. Update your composer script:
   ```json
   "analyze": [
       "php -d memory_limit=1G vendor/bin/phpstan analyse"
   ]
   ```

3. Analyze smaller parts of your codebase at a time:
   ```bash
   vendor/bin/phpstan analyse app/Models
   ```

### 2.2. Slow Analysis

**Issue**: PHPStan takes too long to run

**Solutions**:

1. Enable parallel processing:
   ```yaml
   # phpstan.neon
   parameters:
       parallel:
           maximumNumberOfProcesses: 4
   ```

2. Use incremental analysis (analyze only changed files):
   ```yaml
   # phpstan.neon
   parameters:
       incremental: true
   ```

3. Exclude unnecessary directories:
   ```yaml
   # phpstan.neon
   parameters:
       excludePaths:
           - ./storage/*
           - ./bootstrap/cache/*
   ```

### 2.3. False Positives with Laravel

**Issue**: PHPStan reports errors for valid Laravel code

**Solutions**:

1. Ensure you're using Larastan:
   ```yaml
   # phpstan.neon
   includes:
       - ./vendor/larastan/larastan/extension.neon
   ```

2. Add specific ignores for Laravel patterns:
   ```yaml
   # phpstan.neon
   parameters:
       ignoreErrors:
           - '#Call to an undefined method Illuminate\\Support\\HigherOrder#'
           - '#Access to an undefined property App\\Models#'
   ```

3. Use proper PHPDoc annotations for models:
   ```php
   /**
    * @property string $name
    * @property Carbon $created_at
    */
   class User extends Model
   {
   }
   ```

### 2.4. Issues with Dynamic Properties

**Issue**: PHPStan complains about dynamic properties

**Solutions**:

1. Configure model properties checking:
   ```yaml
   # phpstan.neon
   parameters:
       checkModelProperties: false
   ```

2. Define model properties explicitly:
   ```yaml
   # phpstan.neon
   parameters:
       modelProperties:
           App\Models\User:
               settings: array
   ```

3. Use PHPDoc annotations:
   ```php
   /**
    * @property array $settings
    */
   class User extends Model
   {
   }
   ```

### 2.5. Baseline Issues

**Issue**: Problems with baseline generation or usage

**Solutions**:

1. Regenerate a fresh baseline:
   ```bash
   composer analyze:fresh-baseline
   ```

2. Check for syntax errors in baseline file:
   ```bash
   vendor/bin/phpstan analyse --debug
   ```

3. Ensure baseline path is correct:
   ```yaml
   # phpstan.neon
   parameters:
       baseline: phpstan-baseline.neon
   ```

### 2.6. Conflicts with Other Tools

**Issue**: PHPStan conflicts with other development tools

**Solutions**:

1. Ensure compatible versions:
   ```bash
   composer why-not phpstan/phpstan
   ```

2. Check for conflicting extensions:
   ```bash
   composer show | grep phpstan
   ```

3. Isolate PHPStan configuration:
   ```yaml
   # phpstan.neon
   includes: []  # Empty to avoid conflicts
   ```

## 3. Advanced Troubleshooting

### 3.1. Debugging PHPStan

Run PHPStan with debug output:

```bash
vendor/bin/phpstan analyse --debug
```

### 3.2. Analyzing Specific Files

Analyze only specific files to isolate issues:

```bash
vendor/bin/phpstan analyse app/Models/User.php
```

### 3.3. Checking Rule Configuration

List all active rules:

```bash
vendor/bin/phpstan analyse --debug --configuration=phpstan.neon | grep "Registered rules:"
```

## 4. Project-Specific Issues

### 4.1. Livewire Components

**Issue**: PHPStan reports errors for Livewire properties

**Solution**:

```yaml
# phpstan.neon
parameters:
    ignoreErrors:
        - '#Property [a-zA-Z0-9\\_]+::\$listeners is never written, only read.#'
```

### 4.2. Custom Collections

**Issue**: Issues with custom collection types

**Solution**:

```yaml
# phpstan.neon
parameters:
    checkGenericClassInNonGenericObjectType: false
```

## 5. Getting Help

If you encounter issues not covered in this guide:

1. Check the [PHPStan documentation](https://phpstan.org/user-guide/getting-started)
2. Search the [Larastan issues](https://github.com/larastan/larastan/issues)
3. Ask in the #dev-tools channel on Slack
4. Document your solution here after resolving it
