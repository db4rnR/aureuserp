<?php

declare(strict_types=1);

/**
 * Test Performance Measurement Script
 *
 * This script measures the execution time of tests and compares them before and after optimizations.
 * It can be used to demonstrate the performance improvements achieved by the optimizations.
 *
 * Usage:
 * php scripts/measure-test-performance.php [--suite=<suite>] [--group=<group>] [--filter=<filter>]
 *
 * Options:
 * --suite=<suite>    Run tests from the specified suite (unit, feature, integration)
 * --group=<group>    Run tests with the specified group
 * --filter=<filter>  Run tests matching the specified filter
 * --parallel         Run tests in parallel
 * --baseline         Save the results as a baseline for comparison
 * --compare          Compare the results with the baseline
 */

// Parse command line arguments
$options = getopt('', ['suite::', 'group::', 'filter::', 'parallel', 'baseline', 'compare']);

// Default values
$suite = $options['suite'] ?? 'unit';
$group = $options['group'] ?? null;
$filter = $options['filter'] ?? null;
$parallel = isset($options['parallel']);
$baseline = isset($options['baseline']);
$compare = isset($options['compare']);

// Build the command
$command = './vendor/bin/pest';

if ($suite) {
    $command .= " --testsuite={$suite}";
}

if ($group) {
    $command .= " --group={$group}";
}

if ($filter) {
    $command .= " --filter={$filter}";
}

if ($parallel) {
    $command .= ' --parallel';
}

// Add timing flag
$command .= ' --debug';

echo "Running command: {$command}\n";

// Measure execution time
$startTime = microtime(true);

// Execute the command and capture output
$output = [];
$returnVar = 0;
exec($command, $output, $returnVar);

// Calculate execution time
$endTime = microtime(true);
$executionTime = $endTime - $startTime;

// Display results
echo "\nTest Execution Time: ".number_format($executionTime, 2)." seconds\n";

// Parse the output to extract more detailed timing information
$testCount = 0;
$slowestTests = [];

foreach ($output as $line) {
    if (preg_match('/Time: (\d+\.\d+)s, Memory: (\d+\.\d+)MB/', $line, $matches)) {
        echo "Total Time: {$matches[1]}s, Memory: {$matches[2]}MB\n";
    }

    if (preg_match('/Tests: (\d+)/', $line, $matches)) {
        $testCount = $matches[1];
        echo "Tests Run: {$testCount}\n";
    }

    // Extract individual test times if available
    if (preg_match('/(\d+\.\d+)s (\S+)/', $line, $matches)) {
        $time = (float) ($matches[1]);
        $test = $matches[2];
        $slowestTests[$test] = $time;
    }
}

// Sort and display the slowest tests
if (! empty($slowestTests)) {
    arsort($slowestTests);
    echo "\nSlowest Tests:\n";
    $count = 0;
    foreach ($slowestTests as $test => $time) {
        echo "{$test}: ".number_format($time, 2)."s\n";
        $count++;
        if ($count >= 5) {
            break;
        } // Show only the 5 slowest tests
    }
}

// Save or compare results
$resultsFile = __DIR__.'/test-performance-baseline.json';

if ($baseline) {
    $results = [
        'timestamp' => time(),
        'command' => $command,
        'executionTime' => $executionTime,
        'testCount' => $testCount,
        'slowestTests' => $slowestTests,
    ];

    file_put_contents($resultsFile, json_encode($results, JSON_PRETTY_PRINT));
    echo "\nBaseline saved to {$resultsFile}\n";
} elseif ($compare && file_exists($resultsFile)) {
    $baseline = json_decode(file_get_contents($resultsFile), true);

    $timeDiff = $executionTime - $baseline['executionTime'];
    $percentChange = ($timeDiff / $baseline['executionTime']) * 100;

    echo "\nComparison with Baseline:\n";
    echo 'Baseline Time: '.number_format($baseline['executionTime'], 2)."s\n";
    echo 'Current Time: '.number_format($executionTime, 2)."s\n";
    echo 'Difference: '.number_format($timeDiff, 2).'s ('.number_format($percentChange, 2)."%)\n";

    if ($percentChange < 0) {
        echo 'Performance Improvement: '.number_format(abs($percentChange), 2)."%\n";
    } else {
        echo 'Performance Regression: '.number_format($percentChange, 2)."%\n";
    }
}

exit($returnVar);
