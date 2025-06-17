# PHPStan Troubleshooting

This guide addresses common issues when using PHPStan with our Laravel 12 project running on PHP 8.4.

## 1. Memory Issues

### Problem: PHPStan runs out of memory

**Symptoms:**
- Fatal error: Allowed memory size exhausted
- Analysis terminates unexpectedly

**Solutions:**

1. Increase PHP memory limit:
   ```bash
   # Via command line
   php -d memory_limit=1G vendor/bin/phpstan analyse
   
   # Via memory-safe wrapper script
   ./bin/phpstan.sh analyse
   ```

2. Analyze only specific directories:
   ```bash
   # Only analyze app directory
   composer analyze app/
   
   # Only analyze specific files or directories
   php -d memory_limit=1G vendor/bin/phpstan analyse app/Models app/Services
   ```

3. Use incremental analysis:
   ```bash
   # Only analyze changed files
   composer analyze:changed
   ```

## 2. Performance Issues

### Problem: Analysis is too slow

**Symptoms:**
- Complete analysis takes several minutes
- CI/CD pipeline bottlenecked by PHPStan

**Solutions:**

1. Enable result caching:
   ```bash
   # Make sure result caching is enabled in phpstan.neon
   parameters:
       resultCachePath: var/cache/phpstan
   ```

2. Use parallel processing:
   ```bash
   # Use all available CPU cores
   php -d memory_limit=1G vendor/bin/phpstan analyse --parallel
   
   # Specify number of processes
   php -d memory_limit=1G vendor/bin/phpstan analyse --parallel 4
   ```

3. Exclude large vendor directories in configuration:
   ```yaml
   # In phpstan.neon
   parameters:
       excludePaths:
           - vendor/some-large-package/*
   ```

## 3. False Positives

### Problem: PHPStan reports errors for code that works correctly

**Symptoms:**
- Errors reported for Laravel magic methods
- Errors reported for dynamic properties

**Solutions:**

1. Update Larastan:
   ```bash
   composer update --dev nunomaduro/larastan
   ```

2. Add PHPDoc blocks with proper type hints:
   ```php
   /**
    * @var string
    */
   private $dynamicProperty;
   
   /**
    * @return \Illuminate\Database\Eloquent\Collection<\App\Models\User>
    */
   public function getUsers()
   {
       return User::all();
   }
   ```

3. Add method calls to PHPStan's ignored errors:
   ```yaml
   # In phpstan.neon
   parameters:
       ignoreErrors:
           - '#Call to an undefined method [a-zA-Z0-9\\_]+::[a-zA-Z0-9_]+\(\)#'
   ```

4. Use baseline file to ignore specific errors:
   ```bash
   # Generate a baseline file
   composer analyze:baseline
   ```

## 4. Configuration Issues

### Problem: PHPStan configuration not applied correctly

**Symptoms:**
- Unexpected rules being enforced
- Rules from phpstan.neon not taking effect

**Solutions:**

1. Verify configuration file path:
   ```bash
   # Explicitly specify config file
   vendor/bin/phpstan analyse --configuration=phpstan.neon
   ```

2. Check for syntax errors in configuration:
   ```bash
   # Validate NEON syntax
   composer exec -- neon-lint phpstan.neon
   ```

3. Ensure you're not overriding configurations:
   ```bash
   # Check if multiple configuration files are being used
   vendor/bin/phpstan analyse --debug
   ```

## 5. Integration Issues

### Problem: PHPStan doesn't work with specific Laravel features

**Symptoms:**
- Errors for Laravel-specific patterns
- False positives in model relations

**Solutions:**

1. Use Larastan extensions:
   ```yaml
   # In phpstan.neon
   includes:
       - ./vendor/nunomaduro/larastan/extension.neon
   ```

2. Add specific model return type extensions:
   ```yaml
   # In phpstan.neon
   services:
       -
           class: App\PHPStan\ModelRelationReturnTypeExtension
           tags:
               - phpstan.broker.methodReturnTypeExtension
   ```

3. Create custom PHPStan extensions for project-specific patterns:
   See the [Custom Rules](050-custom-rules.md) documentation.

## 6. CI/CD Issues

### Problem: PHPStan fails in CI but passes locally

**Symptoms:**
- GitHub Actions workflow fails only in CI environment
- Different error counts between environments

**Solutions:**

1. Ensure identical PHP versions:
   ```yaml
   # In GitHub workflow
   - name: Setup PHP
     uses: shivammathur/setup-php@v2
     with:
       php-version: '8.4'
   ```

2. Check for environment-specific configurations:
   ```bash
   # Use same memory limits in CI as locally
   run: ./vendor/bin/phpstan analyse --memory-limit=1G
   ```

3. Cache dependencies and PHPStan results in CI:
   ```yaml
   # In GitHub workflow
   - name: Cache PHPStan results
     uses: actions/cache@v3
     with:
       path: var/cache/phpstan
       key: ${{ runner.os }}-phpstan-${{ github.sha }}
   ```

## 7. Getting Help

If you've tried the solutions above and still have issues:

1. Check the [official PHPStan documentation](https://phpstan.org/user-guide/getting-started)
2. Review the [Larastan issues on GitHub](https://github.com/nunomaduro/larastan/issues)
3. Consult with the team's PHPStan experts
4. Create a minimal reproduction case and request help in the #dev-support channel