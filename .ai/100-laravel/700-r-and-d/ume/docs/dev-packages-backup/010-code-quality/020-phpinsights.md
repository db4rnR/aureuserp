# PHP Insights

## 1. Overview

PHP Insights is a powerful static analysis tool created by Nuno Maduro that provides metrics and insights about the quality of PHP code. It combines multiple static analysis tools into a single, elegant interface designed specifically for Laravel applications.

### 1.1. Package Information

- **Package Name**: nunomaduro/phpinsights
- **Version**: ^2.13.1
- **GitHub**: [https://github.com/nunomaduro/phpinsights](https://github.com/nunomaduro/phpinsights)
- **Documentation**: [https://phpinsights.com/](https://phpinsights.com/)

## 2. Key Features

- Code quality analysis
- Architecture analysis
- Style checks
- Complexity metrics
- Security vulnerability detection
- Customizable ruleset
- Beautiful console output
- Integration with CI/CD pipelines

## 3. Usage Examples

### 3.1. Basic Usage

```sh
## Run PHP Insights
./vendor/bin/phpinsights

## Run with specific configuration
./vendor/bin/phpinsights analyse --config-path=config/insights.php

## Analyze specific files or directories
./vendor/bin/phpinsights analyse app/Models

## Fix issues automatically (where possible)
./vendor/bin/phpinsights analyse --fix
```

### 3.2. Using with Composer Scripts

The project has PHP Insights configured in composer scripts:

```sh
## Run analysis
composer analyze

## Fix issues
composer analyze:fix
```

## 4. Configuration

PHP Insights is configured in `config/insights.php`:

```php
<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Preset
    |--------------------------------------------------------------------------
    */
    'preset' => 'laravel',

    /*
    |--------------------------------------------------------------------------
    | IDE
    |--------------------------------------------------------------------------
    */
    'ide' => 'phpstorm',

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    */
    'exclude' => [
        'app/Providers',
        'storage',
        'resources/views',
    ],

    'add' => [
        // Custom rules
    ],

    'remove' => [
        // Rules to remove
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff::class,
        \SlevomatCodingStandard\Sniffs\TypeHints\DisallowArrayTypeHintSyntaxSniff::class,
    ],

    'config' => [
        \PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 160,
            'ignoreComments' => true,
        ],
        \NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexity::class => [
            'maxComplexity' => 15,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Requirements
    |--------------------------------------------------------------------------
    */
    'requirements' => [
        'min-quality' => 85,
        'min-complexity' => 85,
        'min-architecture' => 85,
        'min-style' => 85,
        'disable-security-check' => false,
    ],
];
```

## 5. Best Practices

### 5.1. Gradual Improvement

Start with lower threshold values and incrementally increase them:

```php
<?php

declare(strict_types=1);

// Initial thresholds
'requirements' => [
    'min-quality' => 75,
    'min-complexity' => 75,
    'min-architecture' => 75,
    'min-style' => 75,
],

// Gradually increase over time
// 'requirements' => [
//     'min-quality' => 85,
//     'min-complexity' => 85,
//     'min-architecture' => 85,
//     'min-style' => 85,
// ],
```

### 5.2. CI/CD Integration

Integrate with CI/CD for automated checks:

```yaml
# In GitHub workflow
php-insights:
  runs-on: ubuntu-latest
  steps:
    - uses: actions/checkout@v3
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
    - name: Install dependencies
      run: composer install --prefer-dist
    - name: Run PHP Insights
      run: vendor/bin/phpinsights analyse --no-interaction --min-quality=85 --min-complexity=85 --min-architecture=85 --min-style=85
```

### 5.3. Custom Rules

Create custom rules for project-specific standards:

```php
<?php

declare(strict_types=1);

namespace App\Insights;

use NunoMaduro\PhpInsights\Domain\Insights\Insight;
use NunoMaduro\PhpInsights\Domain\Metrics\Architecture\Classes;

final class NoRepositoryWithoutInterface extends Insight
{
    public function hasIssue(): bool
    {
        $filename = $this->getFiles()[0];
        
        if (strpos($filename, 'Repository.php') !== false 
            && strpos($filename, 'Repositories/Interfaces') === false) {
            
            $interfaceFile = str_replace('Repository.php', 'RepositoryInterface.php', $filename);
            $interfaceFile = str_replace('Repositories/', 'Repositories/Interfaces/', $interfaceFile);
            
            return !file_exists($interfaceFile);
        }
        
        return false;
    }

    public function getTitle(): string
    {
        return 'Repository without interface detected';
    }
}
```
