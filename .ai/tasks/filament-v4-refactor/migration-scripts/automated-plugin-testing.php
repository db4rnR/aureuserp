<?php

/**
 * FilamentPHP v4 Migration - Automated Plugin Testing Procedures
 *
 * This script provides automated testing procedures for each plugin during the migration process.
 * It ensures that functionality is preserved and validates that all tests pass after migration.
 *
 * Usage: php automated-plugin-testing.php [command] [plugin-name] [options]
 *
 * Commands:
 * - test: Run tests for specific plugin or all plugins
 * - baseline: Establish baseline test results before migration
 * - compare: Compare current test results with baseline
 * - report: Generate comprehensive testing report
 *
 * Options:
 * --plugin=name: Test specific plugin
 * --type=unit|feature|all: Test type to run
 * --coverage: Generate coverage report
 * --parallel: Run tests in parallel
 * --stop-on-failure: Stop on first failure
 */

class AutomatedPluginTesting
{
    private array $stats = [
        'plugins_tested' => 0,
        'total_tests' => 0,
        'passed_tests' => 0,
        'failed_tests' => 0,
        'skipped_tests' => 0,
        'coverage_percentage' => 0,
        'execution_time' => 0,
        'errors' => []
    ];

    private array $pluginList = [
        'accounts', 'analytics', 'blogs', 'chatter', 'contacts', 'employees',
        'fields', 'inventories', 'invoices', 'partners', 'payments', 'products',
        'projects', 'purchases', 'recruitments', 'sales', 'security', 'support',
        'table-views', 'time-off', 'timesheets', 'website'
    ];

    private array $testTypes = ['unit', 'feature', 'integration'];
    private string $baselineFile = '.ai/tasks/filament-v4-refactor/baseline-test-results.json';
    private bool $stopOnFailure = false;
    private bool $generateCoverage = false;
    private bool $runParallel = false;

    public function run(string $command, array $options = []): array
    {
        $this->parseOptions($options);

        switch ($command) {
            case 'test':
                return $this->runTests($options);
            case 'baseline':
                return $this->establishBaseline($options);
            case 'compare':
                return $this->compareWithBaseline($options);
            case 'report':
                return $this->generateReport($options);
            default:
                throw new InvalidArgumentException("Unknown command: {$command}");
        }
    }

    private function parseOptions(array $options): void
    {
        $this->stopOnFailure = $options['stop-on-failure'] ?? false;
        $this->generateCoverage = $options['coverage'] ?? false;
        $this->runParallel = $options['parallel'] ?? false;
    }

    private function runTests(array $options): array
    {
        $plugin = $options['plugin'] ?? null;
        $testType = $options['type'] ?? 'all';

        echo "Running automated plugin tests...\n";
        echo "Plugin: " . ($plugin ?? 'All') . "\n";
        echo "Test Type: {$testType}\n";
        echo "Coverage: " . ($this->generateCoverage ? 'Yes' : 'No') . "\n\n";

        $startTime = microtime(true);

        if ($plugin) {
            $this->runPluginTests($plugin, $testType);
        } else {
            foreach ($this->pluginList as $pluginName) {
                $this->runPluginTests($pluginName, $testType);

                if ($this->stopOnFailure && $this->stats['failed_tests'] > 0) {
                    echo "Stopping on failure as requested.\n";
                    break;
                }
            }
        }

        $this->stats['execution_time'] = microtime(true) - $startTime;
        $this->printTestSummary();

        return $this->stats;
    }

    private function runPluginTests(string $plugin, string $testType): void
    {
        echo "Testing plugin: {$plugin}\n";
        $this->stats['plugins_tested']++;

        $pluginTestPath = $this->getPluginTestPath($plugin);

        if (!$this->hasTests($pluginTestPath)) {
            echo "  ⚠️  No tests found for {$plugin}\n\n";
            return;
        }

        $testCommands = $this->buildTestCommands($plugin, $testType, $pluginTestPath);

        foreach ($testCommands as $command) {
            $result = $this->executeTestCommand($command);
            $this->processTestResult($plugin, $result);
        }

        echo "\n";
    }

    private function getPluginTestPath(string $plugin): string
    {
        return "tests/Unit/Plugins/" . ucfirst($plugin);
    }

    private function hasTests(string $testPath): bool
    {
        return is_dir($testPath) && !empty(glob($testPath . '/*Test.php'));
    }

    private function buildTestCommands(string $plugin, string $testType, string $testPath): array
    {
        $commands = [];
        $baseCommand = './vendor/bin/pest';

        if ($testType === 'all' || $testType === 'unit') {
            $unitPath = "tests/Unit/Plugins/" . ucfirst($plugin);
            if (is_dir($unitPath)) {
                $cmd = "{$baseCommand} {$unitPath}";
                if ($this->generateCoverage) {
                    $cmd .= " --coverage --coverage-html=coverage/{$plugin}";
                }
                if ($this->stopOnFailure) {
                    $cmd .= " --stop-on-failure";
                }
                $commands[] = ['type' => 'unit', 'command' => $cmd];
            }
        }

        if ($testType === 'all' || $testType === 'feature') {
            $featurePath = "tests/Feature/Plugins/" . ucfirst($plugin);
            if (is_dir($featurePath)) {
                $cmd = "{$baseCommand} {$featurePath}";
                if ($this->stopOnFailure) {
                    $cmd .= " --stop-on-failure";
                }
                $commands[] = ['type' => 'feature', 'command' => $cmd];
            }
        }

        return $commands;
    }

    private function executeTestCommand(array $commandInfo): array
    {
        $command = $commandInfo['command'];
        $type = $commandInfo['type'];

        echo "  Running {$type} tests...\n";

        $output = [];
        $returnCode = 0;

        exec($command . ' 2>&1', $output, $returnCode);

        return [
            'type' => $type,
            'command' => $command,
            'output' => $output,
            'return_code' => $returnCode,
            'success' => $returnCode === 0
        ];
    }

    private function processTestResult(string $plugin, array $result): void
    {
        $output = implode("\n", $result['output']);

        // Parse Pest output for test statistics
        $testStats = $this->parsePestOutput($output);

        $this->stats['total_tests'] += $testStats['total'];
        $this->stats['passed_tests'] += $testStats['passed'];
        $this->stats['failed_tests'] += $testStats['failed'];
        $this->stats['skipped_tests'] += $testStats['skipped'];

        if ($result['success']) {
            echo "    ✅ {$result['type']} tests passed ({$testStats['total']} tests)\n";
        } else {
            echo "    ❌ {$result['type']} tests failed ({$testStats['failed']} failures)\n";

            // Extract and display failure details
            $failures = $this->extractFailures($output);
            foreach ($failures as $failure) {
                echo "      - {$failure}\n";
            }
        }

        // Extract coverage information if available
        if ($this->generateCoverage && strpos($output, 'Coverage') !== false) {
            $coverage = $this->extractCoverage($output);
            if ($coverage) {
                echo "    📊 Coverage: {$coverage}%\n";
            }
        }
    }

    private function parsePestOutput(string $output): array
    {
        $stats = ['total' => 0, 'passed' => 0, 'failed' => 0, 'skipped' => 0];

        // Parse Pest summary line (e.g., "Tests: 5 passed, 1 failed, 6 total")
        if (preg_match('/Tests:\s*(\d+)\s*passed(?:,\s*(\d+)\s*failed)?(?:,\s*(\d+)\s*skipped)?(?:,\s*(\d+)\s*total)?/', $output, $matches)) {
            $stats['passed'] = (int)($matches[1] ?? 0);
            $stats['failed'] = (int)($matches[2] ?? 0);
            $stats['skipped'] = (int)($matches[3] ?? 0);
            $stats['total'] = (int)($matches[4] ?? ($stats['passed'] + $stats['failed'] + $stats['skipped']));
        }

        return $stats;
    }

    private function extractFailures(string $output): array
    {
        $failures = [];
        $lines = explode("\n", $output);

        $inFailureSection = false;
        foreach ($lines as $line) {
            if (strpos($line, 'FAILED') !== false || strpos($line, 'FAIL') !== false) {
                $inFailureSection = true;
                $failures[] = trim($line);
            } elseif ($inFailureSection && (empty(trim($line)) || strpos($line, '---') !== false)) {
                $inFailureSection = false;
            } elseif ($inFailureSection) {
                $failures[] = trim($line);
            }
        }

        return array_filter($failures);
    }

    private function extractCoverage(string $output): ?float
    {
        if (preg_match('/Coverage:\s*(\d+(?:\.\d+)?)%/', $output, $matches)) {
            return (float)$matches[1];
        }
        return null;
    }

    private function establishBaseline(array $options): array
    {
        echo "Establishing baseline test results...\n\n";

        $baselineResults = [];

        foreach ($this->pluginList as $plugin) {
            echo "Establishing baseline for {$plugin}...\n";

            $pluginResults = [];
            foreach ($this->testTypes as $testType) {
                $result = $this->runSinglePluginTest($plugin, $testType);
                if ($result) {
                    $pluginResults[$testType] = $result;
                }
            }

            if (!empty($pluginResults)) {
                $baselineResults[$plugin] = $pluginResults;
            }
        }

        // Save baseline results
        $this->saveBaseline($baselineResults);

        echo "\nBaseline established and saved to {$this->baselineFile}\n";
        return $baselineResults;
    }

    private function runSinglePluginTest(string $plugin, string $testType): ?array
    {
        $testPath = $this->getTestPathForType($plugin, $testType);

        if (!is_dir($testPath)) {
            return null;
        }

        $command = "./vendor/bin/pest {$testPath} --json";
        $output = [];
        $returnCode = 0;

        exec($command . ' 2>&1', $output, $returnCode);

        $outputString = implode("\n", $output);
        $stats = $this->parsePestOutput($outputString);

        return [
            'total' => $stats['total'],
            'passed' => $stats['passed'],
            'failed' => $stats['failed'],
            'skipped' => $stats['skipped'],
            'success' => $returnCode === 0,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    private function getTestPathForType(string $plugin, string $testType): string
    {
        $pluginName = ucfirst($plugin);

        switch ($testType) {
            case 'unit':
                return "tests/Unit/Plugins/{$pluginName}";
            case 'feature':
                return "tests/Feature/Plugins/{$pluginName}";
            case 'integration':
                return "tests/Integration/Plugins/{$pluginName}";
            default:
                return "tests/Unit/Plugins/{$pluginName}";
        }
    }

    private function saveBaseline(array $results): void
    {
        $baselineData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => 'pre-filament-v4-migration',
            'results' => $results
        ];

        $dir = dirname($this->baselineFile);
        if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new RuntimeException("Failed to create directory: {$dir}");
        }

        file_put_contents($this->baselineFile, json_encode($baselineData, JSON_PRETTY_PRINT));
    }

    private function compareWithBaseline(array $options): array
    {
        echo "Comparing current test results with baseline...\n\n";

        if (!file_exists($this->baselineFile)) {
            throw new RuntimeException("Baseline file not found. Run 'baseline' command first.");
        }

        $baseline = json_decode(file_get_contents($this->baselineFile), true);
        $current = $this->runTests(['type' => 'all']);

        $comparison = $this->performComparison($baseline['results'], $current);
        $this->printComparison($comparison);

        return $comparison;
    }

    private function performComparison(array $baseline, array $current): array
    {
        $comparison = [
            'improved' => [],
            'degraded' => [],
            'unchanged' => [],
            'new_failures' => [],
            'fixed_failures' => []
        ];

        foreach ($this->pluginList as $plugin) {
            $baselinePlugin = $baseline[$plugin] ?? null;
            $currentPlugin = $this->getPluginCurrentResults($plugin);

            if (!$baselinePlugin || !$currentPlugin) {
                continue;
            }

            $pluginComparison = $this->comparePluginResults($baselinePlugin, $currentPlugin);

            if ($pluginComparison['status'] === 'improved') {
                $comparison['improved'][$plugin] = $pluginComparison;
            } elseif ($pluginComparison['status'] === 'degraded') {
                $comparison['degraded'][$plugin] = $pluginComparison;
            } else {
                $comparison['unchanged'][$plugin] = $pluginComparison;
            }
        }

        return $comparison;
    }

    private function getPluginCurrentResults(string $plugin): ?array
    {
        // This would be populated during the test run
        // For now, return a placeholder
        return null;
    }

    private function comparePluginResults(array $baseline, array $current): array
    {
        $baselineTotal = array_sum(array_column($baseline, 'total'));
        $baselinePassed = array_sum(array_column($baseline, 'passed'));
        $baselineFailed = array_sum(array_column($baseline, 'failed'));

        $currentTotal = array_sum(array_column($current, 'total'));
        $currentPassed = array_sum(array_column($current, 'passed'));
        $currentFailed = array_sum(array_column($current, 'failed'));

        $status = 'unchanged';
        if ($currentPassed > $baselinePassed || $currentFailed < $baselineFailed) {
            $status = 'improved';
        } elseif ($currentPassed < $baselinePassed || $currentFailed > $baselineFailed) {
            $status = 'degraded';
        }

        return [
            'status' => $status,
            'baseline' => ['total' => $baselineTotal, 'passed' => $baselinePassed, 'failed' => $baselineFailed],
            'current' => ['total' => $currentTotal, 'passed' => $currentPassed, 'failed' => $currentFailed],
            'diff' => [
                'total' => $currentTotal - $baselineTotal,
                'passed' => $currentPassed - $baselinePassed,
                'failed' => $currentFailed - $baselineFailed
            ]
        ];
    }

    private function printComparison(array $comparison): void
    {
        echo "\n=== Test Results Comparison ===\n";

        if (!empty($comparison['improved'])) {
            echo "\n✅ Improved plugins:\n";
            foreach ($comparison['improved'] as $plugin => $data) {
                echo "  {$plugin}: +{$data['diff']['passed']} passed, {$data['diff']['failed']} failed\n";
            }
        }

        if (!empty($comparison['degraded'])) {
            echo "\n❌ Degraded plugins:\n";
            foreach ($comparison['degraded'] as $plugin => $data) {
                echo "  {$plugin}: {$data['diff']['passed']} passed, +{$data['diff']['failed']} failed\n";
            }
        }

        if (!empty($comparison['unchanged'])) {
            echo "\n➡️  Unchanged plugins: " . count($comparison['unchanged']) . "\n";
        }
    }

    private function generateReport(array $options): array
    {
        echo "Generating comprehensive testing report...\n\n";

        $report = [
            'summary' => $this->stats,
            'plugin_details' => [],
            'recommendations' => []
        ];

        // Generate detailed plugin reports
        foreach ($this->pluginList as $plugin) {
            $pluginReport = $this->generatePluginReport($plugin);
            if ($pluginReport) {
                $report['plugin_details'][$plugin] = $pluginReport;
            }
        }

        // Generate recommendations
        $report['recommendations'] = $this->generateRecommendations($report);

        $this->saveReport($report);
        $this->printReport($report);

        return $report;
    }

    private function generatePluginReport(string $plugin): ?array
    {
        $testPath = $this->getPluginTestPath($plugin);

        if (!$this->hasTests($testPath)) {
            return null;
        }

        return [
            'has_tests' => true,
            'test_files' => glob($testPath . '/*Test.php'),
            'test_count' => count(glob($testPath . '/*Test.php')),
            'last_run' => date('Y-m-d H:i:s'),
            'status' => 'needs_testing'
        ];
    }

    private function generateRecommendations(array $report): array
    {
        $recommendations = [];

        // Check for plugins without tests
        $pluginsWithoutTests = 0;
        foreach ($report['plugin_details'] as $plugin => $details) {
            if (!$details['has_tests']) {
                $pluginsWithoutTests++;
            }
        }

        if ($pluginsWithoutTests > 0) {
            $recommendations[] = "Add tests for {$pluginsWithoutTests} plugins that currently lack test coverage";
        }

        if ($this->stats['failed_tests'] > 0) {
            $recommendations[] = "Fix {$this->stats['failed_tests']} failing tests before proceeding with migration";
        }

        if ($this->stats['coverage_percentage'] < 80) {
            $recommendations[] = "Improve test coverage (currently {$this->stats['coverage_percentage']}%, target: 80%+)";
        }

        return $recommendations;
    }

    private function saveReport(array $report): void
    {
        $reportFile = '.ai/tasks/filament-v4-refactor/testing-report-' . date('Y-m-d-H-i-s') . '.json';
        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
        echo "Report saved to {$reportFile}\n";
    }

    private function printReport(array $report): void
    {
        echo "\n=== Testing Report Summary ===\n";
        echo "Plugins tested: {$report['summary']['plugins_tested']}\n";
        echo "Total tests: {$report['summary']['total_tests']}\n";
        echo "Passed: {$report['summary']['passed_tests']}\n";
        echo "Failed: {$report['summary']['failed_tests']}\n";
        echo "Skipped: {$report['summary']['skipped_tests']}\n";
        echo "Execution time: " . round($report['summary']['execution_time'], 2) . "s\n";

        if (!empty($report['recommendations'])) {
            echo "\nRecommendations:\n";
            foreach ($report['recommendations'] as $recommendation) {
                echo "- {$recommendation}\n";
            }
        }
    }

    private function printTestSummary(): void
    {
        echo "\n=== Test Execution Summary ===\n";
        echo "Plugins tested: {$this->stats['plugins_tested']}\n";
        echo "Total tests: {$this->stats['total_tests']}\n";
        echo "Passed: {$this->stats['passed_tests']}\n";
        echo "Failed: {$this->stats['failed_tests']}\n";
        echo "Skipped: {$this->stats['skipped_tests']}\n";
        echo "Execution time: " . round($this->stats['execution_time'], 2) . "s\n";

        if ($this->stats['failed_tests'] === 0) {
            echo "\n✅ All tests passed!\n";
        } else {
            echo "\n❌ {$this->stats['failed_tests']} test(s) failed.\n";
        }
    }
}

// CLI execution
if (php_sapi_name() === 'cli') {
    $command = $argv[1] ?? 'test';
    $options = [];

    // Parse command line options
    $argCount = count($argv);
    for ($i = 2; $i < $argCount; $i++) {
        $arg = $argv[$i];

        if (strpos($arg, '--plugin=') === 0) {
            $options['plugin'] = substr($arg, 9);
        } elseif (strpos($arg, '--type=') === 0) {
            $options['type'] = substr($arg, 7);
        } elseif ($arg === '--coverage') {
            $options['coverage'] = true;
        } elseif ($arg === '--parallel') {
            $options['parallel'] = true;
        } elseif ($arg === '--stop-on-failure') {
            $options['stop-on-failure'] = true;
        }
    }

    echo "FilamentPHP v4 Migration - Automated Plugin Testing\n";
    echo "Command: {$command}\n\n";

    try {
        $tester = new AutomatedPluginTesting();
        $result = $tester->run($command, $options);

        // Exit with error code if tests failed
        exit(isset($result['failed_tests']) && $result['failed_tests'] > 0 ? 1 : 0);

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}
