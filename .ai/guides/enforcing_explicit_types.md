# Enforcing Explicit Types: A Comprehensive Guide

## Introduction

Type safety is a critical aspect of modern software development that helps catch errors at compile time rather than runtime. In the AureusERP project, enforcing explicit types across both PHP and TypeScript codebases is essential for maintaining code quality, improving developer experience, and reducing bugs. This guide explains from first principles how to configure the project's code quality tools to enforce explicit typing.

**Important:** AureusERP strongly prefers PHP 8.x native type declarations (attributes) over PHPDoc annotations. Native type declarations are enforced by the PHP engine itself and provide superior type safety compared to documentation-only approaches.

## Understanding Type Systems

### What Are Types?

At their core, types are labels that tell the compiler or interpreter what kind of data a variable contains and what operations can be performed on it. Types can be:

1. **Primitive** (strings, numbers, booleans)
2. **Composite** (arrays, objects)
3. **Function** (including parameter and return types)
4. **Custom** (user-defined types and interfaces)

### Why Explicit Typing Matters

1. **Error Prevention**: Catches type-related errors before runtime
2. **Self-Documentation**: Code becomes more readable and self-documenting
3. **IDE Support**: Enables better autocompletion and refactoring tools
4. **Maintenance**: Makes large codebases more maintainable
5. **Scalability**: Helps teams collaborate more effectively as the codebase grows

## Configuring PHP Type Safety

### PHP Attributes vs. PHPDoc

AureusERP uses PHP 8.4, which has robust typing capabilities. The project has adopted a clear policy favoring native PHP type declarations over PHPDoc comments:

| Feature | Preferred Approach | Discouraged Approach |
|---------|-------------------|----------------------|
| Method return types | `function getUser(): User` | `/** @return User */` |
| Parameter types | `function update(int $id, array $data)` | `/** @param int $id @param array $data */` |
| Property types | `protected string $name;` | `/** @var string */` |
| Union types | `public function find(): User\|null` | `/** @return User\|null */` |
| Intersection types | `public function process(Renderable&Htmlable $view)` | `/** @param Renderable&Htmlable $view */` |

**Why Attributes Over PHPDoc:**
1. **Runtime Enforcement**: PHP attributes are enforced by the runtime engine
2. **Performance**: No need to parse docblocks
3. **IDE Support**: Better IDE integration and refactoring support
4. **Clarity**: More concise and standardized syntax
5. **Reduced Redundancy**: Avoids duplication between code and documentation

PHPDoc should only be used for additional context that cannot be expressed through PHP's type system, such as describing what a method does or providing examples.

Here's how to enforce these standards:

### 1. PHPStan Configuration

PHPStan is a static analysis tool that can enforce type rules. Here's a complete configuration for `phpstan.neon` that enforces maximum type safety and prioritizes native PHP types over PHPDoc:

```yaml
parameters:
    level: 8
    paths:
        - app
        - bin
        - bootstrap
        - config
        - database
        - routes
        - tests
        - packages/**/src
        - packages/**/tests
        - plugins
    excludePaths:
        - vendor
        - vendor/*
        - vendor/**/*
        - node_modules
        - storage
        - bootstrap/cache
        - public
        - database/migrations
        - plugins/**/database/migrations
        - reports/rector/cache
    tmpDir: reports/phpstan
    checkMissingIterableValueType: true
    checkGenericClassInNonGenericObjectType: true
    checkExplicitMixed: true
    checkImplicitMixed: true
    checkMissingCallableSignature: true
    reportUnmatchedIgnoredErrors: false
    treatPhpDocTypesAsCertain: false
    # Discourage PHPDoc over native types
    reportMissingPhpDocTypeInParameter: false
    reportMissingPhpDocTypeInReturn: false
    preferNativeTypes: true
    # PHP 8.4 specific settings
    phpVersion: 80400
```

This configuration uses the same standardized paths as `rector-type-safety.php` and explicitly tells PHPStan to prefer native type declarations and not report missing PHPDoc types when native types are present. It also sets the PHP version to 8.4 to enable all the latest type checking features.

### 2. Rector for Automated Type Upgrades

The project already uses Rector (via `rector-type-safety.php`). This tool can automatically add missing type declarations to your code and even convert PHPDoc types to native PHP attributes. Here's the complete configuration to enforce strict typing:

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return static function (RectorConfig $rectorConfig): void {
    // Standardized paths across all tools
    $rectorConfig->paths([
        __DIR__.'/app',
        __DIR__.'/bin',
        __DIR__.'/bootstrap',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/routes',
        __DIR__.'/tests',
        __DIR__.'/packages/**/src',
        __DIR__.'/packages/**/tests',
        __DIR__.'/plugins',
    ]);

    // Standardized exclude paths across all tools
    $rectorConfig->skip([
        __DIR__.'/vendor',
        __DIR__.'/vendor/*',
        __DIR__.'/vendor/**/*',
        __DIR__.'/node_modules',
        __DIR__.'/storage',
        __DIR__.'/bootstrap/cache',
        __DIR__.'/public',
        // Skip migration files to avoid breaking changes to schema definitions
        __DIR__.'/database/migrations',
        __DIR__.'/plugins/**/database/migrations',
        // Skip rector cache files to avoid processing them
        __DIR__.'/reports/rector/cache',
    ]);

    // Output directory for reports
    $rectorConfig->cacheDirectory('reports/rector/cache');

    // Performance settings
    // Parallel processing - standardized across tools
    $rectorConfig->parallel(8); // Aligned with other tools
    $rectorConfig->memoryLimit('1G'); // Standardized memory limit
    // Increase timeout for parallel processing
    $rectorConfig->fileExtensions(['php']);

    // PHP version features - update to PHP 8.4
    $rectorConfig->phpVersion(80400); // Explicitly set PHP version to 8.4

    // Type Declaration rules
    $rectorConfig->rules([
        // Add return types to methods
        AddReturnTypeDeclarationRector::class,
        AddVoidReturnTypeWhereNoReturnRector::class,
        ReturnTypeFromStrictNativeCallRector::class,
        AddParamTypeDeclarationRector::class,
        ReturnNeverTypeRector::class,

        // Add property types
        AddPropertyTypeDeclarationRector::class,
        TypedPropertyFromStrictConstructorRector::class,
    ]);
};
```

This configuration matches the existing `rector-type-safety.php` file in the project and ensures consistent paths and rules for enforcing type safety across the codebase.

### 3. PHP CS Fixer Rules (via Laravel Pint)

Laravel Pint (which uses PHP CS Fixer) can enforce coding standards including type declarations. Here's a complete configuration for `pint.json` that enforces type safety:

```json
{
    "preset": "laravel",
    "rules": {
        "declare_strict_types": true,
        "fully_qualified_strict_types": true,
        "native_function_type_declaration_casing": true,
        "no_unreachable_default_argument_value": true,
        "phpdoc_to_return_type": true,
        "return_type_declaration": true,
        "strict_comparison": true,
        "strict_param": true,
        "void_return": true
    },
    "include": [
        "app/**/*.php",
        "bin/**/*.php",
        "bootstrap/**/*.php",
        "config/**/*.php",
        "database/**/*.php",
        "routes/**/*.php",
        "tests/**/*.php",
        "packages/**/src/**/*.php",
        "packages/**/tests/**/*.php",
        "plugins/**/*.php"
    ],
    "exclude": [
        "vendor",
        "node_modules",
        "storage",
        "bootstrap/cache",
        "public",
        "database/migrations",
        "plugins/**/database/migrations",
        "reports/rector/cache"
    ]
}
```

This configuration ensures that Laravel Pint will check the same files and enforce the same paths as both Rector and PHPStan.

### 4. IDE Integration with PHP_CodeSniffer

Create a `.phpcs.xml.dist` file to enforce native type declarations and discourage redundant PHPDoc:

```xml
<?xml version="1.0"?>
<ruleset name="AureusERP">
    <description>AureusERP coding standard with preference for native types over PHPDoc</description>

    <!-- Define the files to analyze -->
    <file>app</file>
    <file>bin</file>
    <file>bootstrap</file>
    <file>config</file>
    <file>database</file>
    <file>routes</file>
    <file>tests</file>
    <file>packages</file>
    <file>plugins</file>

    <!-- Exclude paths that match other tools -->
    <exclude-pattern>*/vendor/*</exclude-pattern>
    <exclude-pattern>*/node_modules/*</exclude-pattern>
    <exclude-pattern>*/storage/*</exclude-pattern>
    <exclude-pattern>*/bootstrap/cache/*</exclude-pattern>
    <exclude-pattern>*/public/*</exclude-pattern>
    <exclude-pattern>*/database/migrations/*</exclude-pattern>
    <exclude-pattern>*/plugins/**/database/migrations/*</exclude-pattern>
    <exclude-pattern>*/reports/rector/cache/*</exclude-pattern>

    <!-- PHP 8.4 compatibility -->
    <config name="php_version" value="80400"/>

    <!-- Enforce native type hints -->
    <rule ref="SlevomatCodingStandard.TypeHints.ParameterTypeHint">
        <properties>
            <property name="enableObjectTypeHint" value="true"/>
            <property name="traversableTypeHints" type="array">
                <element value="array"/>
                <element value="\ArrayAccess"/>
                <element value="\Countable"/>
                <element value="\Illuminate\Support\Collection"/>
                <element value="\Illuminate\Database\Eloquent\Collection"/>
            </property>
        </properties>
    </rule>
    <rule ref="SlevomatCodingStandard.TypeHints.PropertyTypeHint">
        <properties>
            <property name="enableNativeTypeHint" value="true"/>
        </properties>
    </rule>
    <rule ref="SlevomatCodingStandard.TypeHints.ReturnTypeHint">
        <properties>
            <property name="enableStaticTypeHint" value="true"/>
            <property name="traversableTypeHints" type="array">
                <element value="array"/>
                <element value="\Traversable"/>
                <element value="\Illuminate\Support\Collection"/>
                <element value="\Illuminate\Database\Eloquent\Collection"/>
            </property>
        </properties>
    </rule>
    <rule ref="SlevomatCodingStandard.TypeHints.UselessConstantTypeHint"/>

    <!-- Discourage redundant PHPDoc when native types are used -->
    <rule ref="SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint"/>
    <rule ref="SlevomatCodingStandard.TypeHints.DisallowArrayTypeHintSyntax"/>
    <rule ref="SlevomatCodingStandard.TypeHints.TypeHintDeclaration">
        <properties>
            <property name="enableEachParameterAndReturnInspection" value="true"/>
            <property name="usefulAnnotations" type="array">
                <element value="@api"/>
                <element value="@deprecated"/>
                <element value="@example"/>
                <element value="@see"/>
                <element value="@throws"/>
            </property>
        </properties>
    </rule>
</ruleset>
```

This enhanced configuration not only enforces native type hints but also discourages redundant PHPDoc when native types are already present, allowing PHPDoc only for additional context that cannot be expressed through PHP's type system. The file paths and exclusions are kept consistent with the other tools in the project.

## Configuring TypeScript Type Safety

### 1. TSConfig Settings

Create or modify your `tsconfig.json` file to enforce strict type checking with TypeScript 5.8.3:

```json
{
  "compilerOptions": {
    "target": "es2022",
    "module": "NodeNext",
    "moduleResolution": "NodeNext",
    "lib": ["es2022", "dom"],
    "strict": true,
    "noImplicitAny": true,
    "strictNullChecks": true,
    "strictFunctionTypes": true,
    "strictBindCallApply": true,
    "strictPropertyInitialization": true,
    "noImplicitThis": true,
    "alwaysStrict": true,
    "noUncheckedIndexedAccess": true,
    "noImplicitReturns": true,
    "noFallthroughCasesInSwitch": true,
    "noImplicitOverride": true,
    "forceConsistentCasingInFileNames": true,
    "useUnknownInCatchVariables": true,
    "esModuleInterop": true,
    "skipLibCheck": true,
    "outDir": "./dist",
    "declaration": true,
    "sourceMap": true
  },
  "include": ["resources/js/**/*.ts", "resources/js/**/*.tsx", "resources/js/**/*.d.ts"],
  "exclude": ["node_modules", "vendor", "public", "storage"]
}
```

### 2. ESLint Rules for TypeScript

Update your ESLint configuration (in `.eslintrc.js`) to enforce TypeScript type rules, using the installed TypeScript v5.8.3 and ESLint plugins:

```javascript
module.exports = {
  root: true,
  env: {
    browser: true,
    node: true,
    es2022: true,
  },
  parser: '@typescript-eslint/parser',
  parserOptions: {
    ecmaVersion: 2022,
    sourceType: 'module',
    project: './tsconfig.json',
    ecmaFeatures: {
      jsx: true,
    },
  },
  plugins: [
    '@typescript-eslint',
    'import',
    'simple-import-sort',
    'prefer-arrow-functions',
  ],
  extends: [
    'eslint:recommended',
    'plugin:@typescript-eslint/recommended',
    'plugin:@typescript-eslint/recommended-requiring-type-checking',
    'plugin:import/errors',
    'plugin:import/warnings',
    'plugin:import/typescript',
    'plugin:prettier/recommended',
  ],
  rules: {
    // Strict TypeScript rules
    '@typescript-eslint/explicit-function-return-type': 'error',
    '@typescript-eslint/explicit-module-boundary-types': 'error',
    '@typescript-eslint/no-explicit-any': 'error',
    '@typescript-eslint/no-unsafe-assignment': 'error',
    '@typescript-eslint/no-unsafe-call': 'error',
    '@typescript-eslint/no-unsafe-member-access': 'error',
    '@typescript-eslint/no-unsafe-return': 'error',
    '@typescript-eslint/prefer-as-const': 'error',
    '@typescript-eslint/restrict-template-expressions': 'error',
    '@typescript-eslint/strict-boolean-expressions': 'error',

    // Import sorting and organization
    'simple-import-sort/imports': 'error',
    'simple-import-sort/exports': 'error',
    'import/first': 'error',
    'import/newline-after-import': 'error',
    'import/no-duplicates': 'error',

    // Prefer arrow functions
    'prefer-arrow-functions/prefer-arrow-functions': [
      'error',
      {
        'classPropertiesAllowed': false,
        'disallowPrototype': true,
        'returnStyle': 'implicit',
      }
    ],
  },
  settings: {
    'import/resolver': {
      typescript: {},
    },
  },
  ignorePatterns: ['node_modules', 'vendor', 'public', 'storage', 'dist', 'build']
};
```

### 3. Integration with Prettier

If using Prettier, ensure it doesn't conflict with TypeScript type rules:

```javascript
// .prettierrc.js
module.exports = {
  // ... other options
  parser: 'typescript',
};
```

## Git Hooks for Enforcement

Use Git hooks via the `.pre-commit-config.yaml` file to prevent code without proper types from being committed:

```yaml
repos:
  - repo: local
    hooks:
      - id: phpstan
        name: PHPStan
        description: 'Runs PHPStan static analysis on PHP files'
        entry: vendor/bin/phpstan analyze
        language: system
        pass_filenames: false
        files: '^(app|bin|bootstrap|config|database|routes|tests|packages|plugins)/.+\.php$'
        exclude: '^(vendor|node_modules|storage|bootstrap/cache|public|database/migrations|plugins/.*/database/migrations|reports/rector/cache)/.*$'

      - id: rector
        name: Rector Type Safety
        description: 'Checks for type safety issues with Rector'
        entry: vendor/bin/rector process --dry-run --config=rector-type-safety.php
        language: system
        pass_filenames: false
        files: '^(app|bin|bootstrap|config|database|routes|tests|packages|plugins)/.+\.php$'
        exclude: '^(vendor|node_modules|storage|bootstrap/cache|public|database/migrations|plugins/.*/database/migrations|reports/rector/cache)/.*$'

      - id: pint
        name: Laravel Pint
        description: 'Runs Laravel Pint PHP code style fixer'
        entry: vendor/bin/pint --test
        language: system
        pass_filenames: false
        files: '^(app|bin|bootstrap|config|database|routes|tests|packages|plugins)/.+\.php$'
        exclude: '^(vendor|node_modules|storage|bootstrap/cache|public|database/migrations|plugins/.*/database/migrations|reports/rector/cache)/.*$'

      - id: typescript-check
        name: TypeScript Check
        description: 'Runs TypeScript compiler for type checking'
        entry: pnpm tsc --noEmit
        language: system
        pass_filenames: false
        files: '\.(ts|tsx)$'
        exclude: '^(node_modules|vendor|public|storage|dist|build)/.*$'

      - id: eslint
        name: ESLint
        description: 'Runs ESLint for TypeScript files'
        entry: pnpm eslint
        language: system
        args: ['--ext', '.ts,.tsx', 'resources/js']
        pass_filenames: false
        files: '\.(ts|tsx)$'
        exclude: '^(node_modules|vendor|public|storage|dist|build)/.*$'
```

## CI/CD Pipeline Integration

Enforce type checking in your CI/CD pipeline to prevent merging code that doesn't meet type requirements. Add these checks to your GitHub Actions workflows:

```yaml
# .github/workflows/type-checks.yml
name: Type Safety Checks

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main, develop]

jobs:
  php-type-check:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
          extensions: mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite, gd
          coverage: none

      - name: Install Composer Dependencies
        run: composer install --prefer-dist --no-interaction --no-progress

      - name: Run PHPStan
        run: vendor/bin/phpstan analyze

      - name: Run Rector Type Safety Check
        run: vendor/bin/rector process --dry-run --config=rector-type-safety.php

      - name: Run Laravel Pint
        run: vendor/bin/pint --test

  ts-type-check:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3

      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: '20'

      - name: Install pnpm
        uses: pnpm/action-setup@v2
        with:
          version: 8

      - name: Install Dependencies
        run: pnpm install --frozen-lockfile

      - name: TypeScript Type Check
        run: pnpm tsc --noEmit

      - name: ESLint Check
        run: pnpm eslint --ext .ts,.tsx resources/js
```

The paths and configurations in both the Git hooks and CI/CD pipeline match the standardized paths used in `rector-type-safety.php` and other configuration files across the project.

## Developer Documentation

Create clear documentation for the team on how to work with the type system. This should include:

1. **Best Practices**: Guidelines for writing well-typed code
2. **Common Patterns**: Type patterns specific to your application
3. **Troubleshooting**: How to fix common type-related errors

### PHP Type Declaration Best Practices

#### Always Use Native Type Declarations

```php
// ✅ DO THIS
public function createUser(string $name, int $age): User
{
    // implementation
}

// ❌ NOT THIS
/**
 * @param string $name
 * @param int $age
 * @return User
 */
public function createUser($name, $age)
{
    // implementation
}
```

#### Use Union Types for Multiple Possible Types

```php
// ✅ DO THIS
public function findRecord(int|string $id): Model|null
{
    // implementation
}

// ❌ NOT THIS
/**
 * @param int|string $id
 * @return Model|null
 */
public function findRecord($id)
{
    // implementation
}
```

#### Use Intersection Types for Complex Requirements

```php
// ✅ DO THIS
public function renderView(Renderable&Htmlable $view): string
{
    // implementation
}
```

#### Use PHPDoc Only for Additional Context

```php
// ✅ DO THIS
/**
 * Generates a secure token for user authentication.
 *
 * @throws SecurityException If the random generator fails
 * @example generateToken(32) // Returns a 32-character token
 */
public function generateToken(int $length): string
{
    // implementation
}
```

## Phased Implementation Strategy

Implementing strict typing in an existing codebase can be challenging. Consider this phased approach:

1. **Audit**: Run analysis tools to identify areas lacking types
2. **Prioritize**: Focus on core functionality and public APIs first
3. **Automate**: Use tools like Rector to add missing types where possible
4. **Educate**: Train the team on type-safety principles
5. **Enforce**: Gradually increase strictness in CI/CD checks

## Conclusion

Enforcing explicit types through native PHP attributes is not just about catching errors—it's about creating a more maintainable, scalable, and developer-friendly codebase. By configuring the tools described above and prioritizing PHP 8.x native type declarations over PHPDoc annotations, you'll establish a robust type system that will serve as a foundation for code quality in the AureusERP project.

Remember that the goal is not to make development more difficult, but to catch issues earlier in the development process, improve code readability, and enhance the overall developer experience.

The project's strong preference for native type declarations reflects modern PHP best practices and leverages the full power of PHP 8.4's type system. This approach ensures that types are not just documentation but are enforced by the PHP engine itself, providing real runtime protection against type-related errors.

By following these guidelines, you'll create a codebase that is more resistant to bugs, easier to understand, and better suited for long-term maintenance and evolution.
