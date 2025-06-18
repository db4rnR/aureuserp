<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Preset
    |--------------------------------------------------------------------------
    |
    | This option controls the default preset that will be used by PHP Insights
    | to make your code reliable, simple, and clean. However, you can always
    | adjust the `Metrics` and `Insights` below in this configuration file.
    |
    | Supported: "default", "laravel", "symfony", "magento2", "drupal"
    |
    */

    'preset' => 'laravel',

    /*
    |--------------------------------------------------------------------------
    | IDE
    |--------------------------------------------------------------------------
    |
    | This options allow to add hyperlinks in your terminal to quickly open
    | files in your favorite IDE while browsing your PhpInsights report.
    |
    | Supported: "phpstorm", "vscode", "sublime", "atom"
    |
    */

    'ide' => 'phpstorm',

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may adjust all the various `Insights` that will be used by PHP
    | Insights. You can either add, remove or configure `Insights`. Keep in
    | mind, that all added `Insights` must belong to a specific `Metric`.
    |
    */

    'exclude' => [
        'app/Console/Kernel.php',
        'app/Exceptions/Handler.php',
        'app/Http/Middleware/Authenticate.php',
        'app/Http/Middleware/RedirectIfAuthenticated.php',
        'app/Providers/BroadcastServiceProvider.php',
        'bootstrap/cache',
        'database/migrations',
        'database/seeders',
        'tests/TestCase.php',
    ],

    'add' => [
        //  ExampleMetric::class => [
        //      ExampleInsight::class,
        //  ]
    ],

    'remove' => [
        SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff::class,
        SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff::class,
        PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer::class,
        SlevomatCodingStandard\Sniffs\Classes\ClassConstantVisibilitySniff::class,
        SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff::class,
        NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses::class,
        PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class,
        PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterNotSniff::class,
        SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff::class,
        SlevomatCodingStandard\Sniffs\Commenting\DocCommentSpacingSniff::class,
        PhpCsFixer\Fixer\Import\OrderedImportsFixer::class,
        PHP_CodeSniffer\Standards\PSR12\Sniffs\Files\FileHeaderSniff::class,
    ],

    'config' => [
        NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 10,
        ],
        PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 160,
            'ignoreComments' => true,
        ],
        PhpCsFixer\Fixer\Import\OrderedImportsFixer::class => [
            'imports_order' => ['class', 'function', 'const'],
            'sort_algorithm' => 'alpha',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Requirements
    |--------------------------------------------------------------------------
    |
    | Here you may define a level you want to reach per `Insights` category.
    | When a score is lower than the minimum level defined, then an error
    | code will be returned. This is optional and individually defined.
    |
    */

    'requirements' => [
        'min-quality' => 90,
        'min-complexity' => 90,
        'min-architecture' => 90,
        'min-style' => 90,
        'disable-security-check' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Threads
    |--------------------------------------------------------------------------
    |
    | Here you may adjust how many threads (core) PHPInsights can use to perform
    | the analysis. This is optional, don't provide it and the tool will guess
    | the max core number available. This accept null value or integer > 0.
    |
    */

    'threads' => null,
];
