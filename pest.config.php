<?php

declare(strict_types=1);

use Pest\Plugins\Parallel;
use Pest\Plugins\TypeCoverage;

return [
    // Configure parallel testing
    Parallel::class => [
        'processes' => 4,
        'timeout' => 120,
    ],

    // Configure type coverage
    TypeCoverage::class => [
        'ignoreFiles' => [
            'app/Console/Kernel.php',
            'app/Exceptions/Handler.php',
            'app/Http/Middleware/Authenticate.php',
            'app/Http/Middleware/RedirectIfAuthenticated.php',
            'app/Providers/BroadcastServiceProvider.php',
            'packages/**/*.php',
            'vendor/**/*.php',
        ],
        'ignoreUntyped' => false,
        'level' => 95,
    ],
];
