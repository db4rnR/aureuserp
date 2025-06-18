<?php

declare(strict_types=1);

/**
 * Test Coverage Dashboard Generator
 *
 * This script generates a simple dashboard for visualizing test coverage metrics.
 * It runs the tests with coverage, processes the coverage reports, and displays
 * a summary of coverage metrics for each plugin.
 *
 * Usage: php scripts/coverage-dashboard.php
 */

// Define colors for console output
define('COLOR_GREEN', "\033[32m");
define('COLOR_YELLOW', "\033[33m");
define('COLOR_RED', "\033[31m");
define('COLOR_RESET', "\033[0m");
define('COLOR_BOLD', "\033[1m");

// Create reports directory if it doesn't exist
$reportsDir = 'reports/coverage';
if (! is_dir($reportsDir)) {
    if (! mkdir($reportsDir, 0755, true) && ! is_dir($reportsDir)) {
        echo COLOR_RED."Error: Could not create reports directory.\n".COLOR_RESET;
        exit(1);
    }
}

// Run tests with coverage
echo "Running tests with coverage...\n";
passthru('composer test:coverage');

// Check if coverage report was generated
if (! file_exists('reports/coverage/clover.xml')) {
    echo COLOR_RED."Error: Coverage report not generated. Make sure Xdebug is enabled.\n".COLOR_RESET;
    exit(1);
}

// Parse the clover.xml file
$cloverFile = 'reports/coverage/clover.xml';
$xmlContent = file_get_contents($cloverFile);
if ($xmlContent === false) {
    echo COLOR_RED."Error: Could not read coverage report file.\n".COLOR_RESET;
    exit(1);
}

$xml = simplexml_load_string($xmlContent);
if (! $xml) {
    echo COLOR_RED."Error: Could not parse coverage report XML.\n".COLOR_RESET;
    exit(1);
}

// Extract coverage data
$plugins = [];
$appCoverage = [
    'lines' => 0,
    'covered' => 0,
    'methods' => 0,
    'coveredMethods' => 0,
    'statements' => 0,
    'coveredStatements' => 0,
];

foreach ($xml->xpath('//package') as $package) {
    $path = (string) $package['name'];

    // Skip vendor files
    if (mb_strpos($path, 'vendor/') === 0) {
        continue;
    }

    // Determine if this is a plugin or app file
    $isPlugin = mb_strpos($path, 'plugins/') === 0;
    $pluginName = $isPlugin ? explode('/', $path)[1] : null;

    // Extract metrics
    $metrics = $package->xpath('.//metrics');
    foreach ($metrics as $metric) {
        $lines = (int) $metric['elements'];
        $coveredLines = (int) $metric['coveredelements'];
        $methods = (int) $metric['methods'];
        $coveredMethods = (int) $metric['coveredmethods'];
        $statements = (int) $metric['statements'];
        $coveredStatements = (int) $metric['coveredstatements'];

        if ($isPlugin) {
            if (! isset($plugins[$pluginName])) {
                $plugins[$pluginName] = [
                    'lines' => 0,
                    'covered' => 0,
                    'methods' => 0,
                    'coveredMethods' => 0,
                    'statements' => 0,
                    'coveredStatements' => 0,
                ];
            }

            $plugins[$pluginName]['lines'] += $lines;
            $plugins[$pluginName]['covered'] += $coveredLines;
            $plugins[$pluginName]['methods'] += $methods;
            $plugins[$pluginName]['coveredMethods'] += $coveredMethods;
            $plugins[$pluginName]['statements'] += $statements;
            $plugins[$pluginName]['coveredStatements'] += $coveredStatements;
        } else {
            $appCoverage['lines'] += $lines;
            $appCoverage['covered'] += $coveredLines;
            $appCoverage['methods'] += $methods;
            $appCoverage['coveredMethods'] += $coveredMethods;
            $appCoverage['statements'] += $statements;
            $appCoverage['coveredStatements'] += $coveredStatements;
        }
    }
}

// Calculate overall coverage
$totalLines = $appCoverage['lines'];
$totalCovered = $appCoverage['covered'];
$totalMethods = $appCoverage['methods'];
$totalCoveredMethods = $appCoverage['coveredMethods'];
$totalStatements = $appCoverage['statements'];
$totalCoveredStatements = $appCoverage['coveredStatements'];

foreach ($plugins as $plugin) {
    $totalLines += $plugin['lines'];
    $totalCovered += $plugin['covered'];
    $totalMethods += $plugin['methods'];
    $totalCoveredMethods += $plugin['coveredMethods'];
    $totalStatements += $plugin['statements'];
    $totalCoveredStatements += $plugin['coveredStatements'];
}

$overallCoverage = $totalLines > 0 ? ($totalCovered / $totalLines) * 100 : 0;
$methodCoverage = $totalMethods > 0 ? ($totalCoveredMethods / $totalMethods) * 100 : 0;
$statementCoverage = $totalStatements > 0 ? ($totalCoveredStatements / $totalStatements) * 100 : 0;

// Display dashboard
echo "\n".COLOR_BOLD."TEST COVERAGE DASHBOARD\n".COLOR_RESET;
echo str_repeat('-', 80)."\n";
echo COLOR_BOLD.'Overall Coverage: '.formatCoverage($overallCoverage)."\n";
echo 'Method Coverage: '.formatCoverage($methodCoverage)."\n";
echo 'Statement Coverage: '.formatCoverage($statementCoverage)."\n".COLOR_RESET;
echo str_repeat('-', 80)."\n";

// Display app coverage
$appLineCoverage = $appCoverage['lines'] > 0 ? ($appCoverage['covered'] / $appCoverage['lines']) * 100 : 0;
echo COLOR_BOLD.'App Coverage: '.formatCoverage($appLineCoverage).COLOR_RESET."\n";

// Display plugin coverage
echo COLOR_BOLD."\nPlugin Coverage:\n".COLOR_RESET;
echo mb_str_pad('Plugin', 30).' | '.mb_str_pad('Coverage', 10).' | '.mb_str_pad('Methods', 10).' | '.mb_str_pad('Statements', 10)."\n";
echo str_repeat('-', 80)."\n";

// Sort plugins by coverage (descending)
uasort($plugins, function ($a, $b) {
    $aCoverage = $a['lines'] > 0 ? ($a['covered'] / $a['lines']) * 100 : 0;
    $bCoverage = $b['lines'] > 0 ? ($b['covered'] / $b['lines']) * 100 : 0;

    return $bCoverage <=> $aCoverage;
});

foreach ($plugins as $name => $plugin) {
    $lineCoverage = $plugin['lines'] > 0 ? ($plugin['covered'] / $plugin['lines']) * 100 : 0;
    $methodCoverage = $plugin['methods'] > 0 ? ($plugin['coveredMethods'] / $plugin['methods']) * 100 : 0;
    $statementCoverage = $plugin['statements'] > 0 ? ($plugin['coveredStatements'] / $plugin['statements']) * 100 : 0;

    echo mb_str_pad($name, 30).' | '.
         mb_str_pad(formatCoverage($lineCoverage, false), 10).' | '.
         mb_str_pad(formatCoverage($methodCoverage, false), 10).' | '.
         mb_str_pad(formatCoverage($statementCoverage, false), 10)."\n";
}

echo "\n".COLOR_BOLD.'HTML Report: '.COLOR_RESET.'file://'.realpath('reports/coverage')."/index.html\n";
echo "Open this URL in your browser to view the detailed HTML coverage report.\n\n";

/**
 * Format coverage percentage with color coding
 *
 * @param  float  $coverage  Coverage percentage
 * @param  bool  $withColor  Whether to include color codes
 * @return string Formatted coverage string
 */
function formatCoverage($coverage, $withColor = true): string
{
    $formatted = number_format($coverage, 2).'%';

    if (! $withColor) {
        return $formatted;
    }

    if ($coverage >= 70) {
        return COLOR_GREEN.$formatted.COLOR_RESET;
    }
    if ($coverage >= 50) {
        return COLOR_YELLOW.$formatted.COLOR_RESET;
    }

    return COLOR_RED.$formatted.COLOR_RESET;

}
