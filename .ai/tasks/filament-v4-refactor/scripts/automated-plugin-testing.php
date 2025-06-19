<?php

/**
 * FilamentPHP v4 Automated Plugin Testing Script
 *
 * This script provides automated testing procedures for each plugin to ensure
 * functionality remains intact after migration to FilamentPHP v4.
 */

class AutomatedPluginTesting
{
    private string $projectRoot;
    private array $testCommands = [
        'pest' => './vendor/bin/pest',
        'phpunit' => './vendor/bin/phpunit',
        'artisan' => 'php artisan test'
    ];

    public function __construct(string $projectRoot = '.')
    {
        $this->projectRoot = realpath($projectRoot);
    }

    public function getAvailablePlugins(): array
    {
        $pluginsDir = $this->projectRoot . '/plugins/webkul';
        if (!is_dir($pluginsDir)) {
            return [];
        }

        $plugins = [];
        $iterator = new DirectoryIterator($pluginsDir);

        foreach ($iterator as $dir) {
            if ($dir->isDot() || !$dir->isDir()) {
                continue;
            }

            $pluginName = $dir->getFilename();
            $pluginPath = $dir->getPathname();

            // Check if plugin has tests
            $hasTests = $this->pluginHasTests($pluginPath);

            $plugins[] = [
                'name' => $pluginName,
                'path' => $pluginPath,
                'has_tests' => $hasTests,
                'test_paths' => $this->getPluginTestPaths($pluginName)
            ];
        }

        return $plugins;
    }

    private function pluginHasTests(string $pluginPath): bool
    {
        $testDirs = [
            $pluginPath . '/tests',
            $this->projectRoot . '/tests/Unit/Plugins/' . basename($pluginPath),
            $this->projectRoot . '/tests/Feature/Plugins/' . basename($pluginPath)
        ];

        foreach ($testDirs as $testDir) {
            if (is_dir($testDir) && $this->hasTestFiles($testDir)) {
                return true;
            }
        }

        return false;
    }

    private function hasTestFiles(string $directory): bool
    {
        if (!is_dir($directory)) {
            return false;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() &&
                ($file->getExtension() === 'php') &&
                (strpos($file->getFilename(), 'Test.php') !== false)) {
                return true;
            }
        }

        return false;
    }

    private function getPluginTestPaths(string $pluginName): array
    {
        $paths = [];

        $possiblePaths = [
            "plugins/webkul/{$pluginName}/tests",
            "tests/Unit/Plugins/{$pluginName}",
            "tests/Feature/Plugins/{$pluginName}"
        ];

        foreach ($possiblePaths as $path) {
            $fullPath = $this->projectRoot . '/' . $path;
            if (is_dir($fullPath) && $this->hasTestFiles($fullPath)) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    public function runPluginTests(string $pluginName, array $options = []): array
    {
        $plugin = $this->getPluginInfo($pluginName);
        if (!$plugin) {
            return [
                'success' => false,
                'error' => "Plugin not found: $pluginName",
                'plugin' => $pluginName
            ];
        }

        if (!$plugin['has_tests']) {
            return [
                'success' => true,
                'warning' => "No tests found for plugin: $pluginName",
                'plugin' => $pluginName,
                'results' => []
            ];
        }

        $results = [];
        $overallSuccess = true;

        foreach ($plugin['test_paths'] as $testPath) {
            $result = $this->runTestsForPath($testPath, $options);
            $results[] = $result;

            if (!$result['success']) {
                $overallSuccess = false;
            }
        }

        return [
            'success' => $overallSuccess,
            'plugin' => $pluginName,
            'results' => $results,
            'summary' => $this->generateTestSummary($results)
        ];
    }

    private function getPluginInfo(string $pluginName): ?array
    {
        $plugins = $this->getAvailablePlugins();
        foreach ($plugins as $plugin) {
            if ($plugin['name'] === $pluginName) {
                return $plugin;
            }
        }
        return null;
    }

    private function runTestsForPath(string $testPath, array $options = []): array
    {
        $fullPath = $this->projectRoot . '/' . $testPath;

        // Determine which test runner to use
        $testRunner = $this->determineTestRunner($options);
        $command = $this->buildTestCommand($testRunner, $testPath, $options);

        $startTime = microtime(true);
        $output = [];
        $returnCode = 0;

        // Change to project root directory
        $originalDir = getcwd();
        chdir($this->projectRoot);

        // Execute the test command
        exec($command . ' 2>&1', $output, $returnCode);

        // Restore original directory
        chdir($originalDir);

        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);

        return [
            'success' => $returnCode === 0,
            'test_path' => $testPath,
            'command' => $command,
            'output' => implode("\n", $output),
            'return_code' => $returnCode,
            'duration' => $duration,
            'test_results' => $this->parseTestOutput($output, $testRunner)
        ];
    }

    private function determineTestRunner(array $options): string
    {
        if (isset($options['runner'])) {
            return $options['runner'];
        }

        // Auto-detect based on available tools
        if (file_exists($this->projectRoot . '/vendor/bin/pest')) {
            return 'pest';
        } elseif (file_exists($this->projectRoot . '/vendor/bin/phpunit')) {
            return 'phpunit';
        } else {
            return 'artisan';
        }
    }

    private function buildTestCommand(string $testRunner, string $testPath, array $options): string
    {
        $baseCommand = $this->testCommands[$testRunner];

        switch ($testRunner) {
            case 'pest':
                $command = $baseCommand . ' ' . escapeshellarg($testPath);
                if (isset($options['coverage']) && $options['coverage']) {
                    $command .= ' --coverage';
                }
                if (isset($options['filter'])) {
                    $command .= ' --filter=' . escapeshellarg($options['filter']);
                }
                break;

            case 'phpunit':
                $command = $baseCommand . ' ' . escapeshellarg($testPath);
                if (isset($options['coverage']) && $options['coverage']) {
                    $command .= ' --coverage-text';
                }
                if (isset($options['filter'])) {
                    $command .= ' --filter=' . escapeshellarg($options['filter']);
                }
                break;

            case 'artisan':
                $command = $baseCommand;
                if (isset($options['filter'])) {
                    $command .= ' --filter=' . escapeshellarg($options['filter']);
                }
                // Note: Laravel's test command doesn't support path filtering directly
                break;

            default:
                $command = $baseCommand . ' ' . escapeshellarg($testPath);
        }

        return $command;
    }

    private function parseTestOutput(array $output, string $testRunner): array
    {
        $results = [
            'total_tests' => 0,
            'passed' => 0,
            'failed' => 0,
            'skipped' => 0,
            'errors' => [],
            'failures' => []
        ];

        $outputText = implode("\n", $output);

        switch ($testRunner) {
            case 'pest':
                $this->parsePestOutput($outputText, $results);
                break;

            case 'phpunit':
                $this->parsePhpUnitOutput($outputText, $results);
                break;

            case 'artisan':
                $this->parseArtisanTestOutput($outputText, $results);
                break;
        }

        return $results;
    }

    private function parsePestOutput(string $output, array &$results): void
    {
        // Parse Pest output format
        if (preg_match('/Tests:\s+(\d+)\s+passed/', $output, $matches)) {
            $results['passed'] = (int)$matches[1];
            $results['total_tests'] = $results['passed'];
        }

        if (preg_match('/(\d+)\s+failed/', $output, $matches)) {
            $results['failed'] = (int)$matches[1];
            $results['total_tests'] += $results['failed'];
        }

        if (preg_match('/(\d+)\s+skipped/', $output, $matches)) {
            $results['skipped'] = (int)$matches[1];
            $results['total_tests'] += $results['skipped'];
        }

        // Extract failure details
        if (preg_match_all('/FAILED\s+(.+?)(?=\n\n|\nTests:|$)/s', $output, $matches)) {
            $results['failures'] = $matches[1];
        }
    }

    private function parsePhpUnitOutput(string $output, array &$results): void
    {
        // Parse PHPUnit output format
        if (preg_match('/OK \((\d+) tests?/', $output, $matches)) {
            $results['passed'] = (int)$matches[1];
            $results['total_tests'] = $results['passed'];
        }

        if (preg_match('/Tests: (\d+), Assertions: \d+(?:, Failures: (\d+))?(?:, Errors: (\d+))?(?:, Skipped: (\d+))?/', $output, $matches)) {
            $results['total_tests'] = (int)$matches[1];
            $results['failed'] = isset($matches[2]) ? (int)$matches[2] : 0;
            $results['errors'] = isset($matches[3]) ? (int)$matches[3] : 0;
            $results['skipped'] = isset($matches[4]) ? (int)$matches[4] : 0;
            $results['passed'] = $results['total_tests'] - $results['failed'] - $results['errors'] - $results['skipped'];
        }
    }

    private function parseArtisanTestOutput(string $output, array &$results): void
    {
        // Parse Laravel artisan test output
        if (preg_match('/PASS.+?(\d+) passed/', $output, $matches)) {
            $results['passed'] = (int)$matches[1];
            $results['total_tests'] = $results['passed'];
        }

        if (preg_match('/FAIL.+?(\d+) failed/', $output, $matches)) {
            $results['failed'] = (int)$matches[1];
            $results['total_tests'] += $results['failed'];
        }
    }

    private function generateTestSummary(array $results): array
    {
        $summary = [
            'total_test_suites' => count($results),
            'successful_suites' => 0,
            'failed_suites' => 0,
            'total_tests' => 0,
            'total_passed' => 0,
            'total_failed' => 0,
            'total_skipped' => 0,
            'total_duration' => 0
        ];

        foreach ($results as $result) {
            if ($result['success']) {
                $summary['successful_suites']++;
            } else {
                $summary['failed_suites']++;
            }

            $summary['total_duration'] += $result['duration'];

            if (isset($result['test_results'])) {
                $summary['total_tests'] += $result['test_results']['total_tests'];
                $summary['total_passed'] += $result['test_results']['passed'];
                $summary['total_failed'] += $result['test_results']['failed'];
                $summary['total_skipped'] += $result['test_results']['skipped'];
            }
        }

        return $summary;
    }

    public function runAllPluginTests(array $options = []): array
    {
        $plugins = $this->getAvailablePlugins();
        $results = [];
        $overallSuccess = true;

        foreach ($plugins as $plugin) {
            $result = $this->runPluginTests($plugin['name'], $options);
            $results[] = $result;

            if (!$result['success']) {
                $overallSuccess = false;
            }
        }

        return [
            'success' => $overallSuccess,
            'results' => $results,
            'summary' => $this->generateOverallSummary($results)
        ];
    }

    private function generateOverallSummary(array $results): array
    {
        $summary = [
            'total_plugins' => count($results),
            'successful_plugins' => 0,
            'failed_plugins' => 0,
            'plugins_without_tests' => 0,
            'total_test_suites' => 0,
            'total_tests' => 0,
            'total_passed' => 0,
            'total_failed' => 0,
            'total_skipped' => 0,
            'total_duration' => 0
        ];

        foreach ($results as $result) {
            if (isset($result['warning'])) {
                $summary['plugins_without_tests']++;
            } elseif ($result['success']) {
                $summary['successful_plugins']++;
            } else {
                $summary['failed_plugins']++;
            }

            if (isset($result['summary'])) {
                $pluginSummary = $result['summary'];
                $summary['total_test_suites'] += $pluginSummary['total_test_suites'];
                $summary['total_tests'] += $pluginSummary['total_tests'];
                $summary['total_passed'] += $pluginSummary['total_passed'];
                $summary['total_failed'] += $pluginSummary['total_failed'];
                $summary['total_skipped'] += $pluginSummary['total_skipped'];
                $summary['total_duration'] += $pluginSummary['total_duration'];
            }
        }

        return $summary;
    }

    public function generateTestReport(array $results, string $type = 'detailed'): string
    {
        if ($type === 'summary') {
            return $this->generateSummaryReport($results);
        } else {
            return $this->generateDetailedReport($results);
        }
    }

    private function generateSummaryReport(array $results): string
    {
        $summary = isset($results['summary']) ? $results['summary'] : $this->generateOverallSummary($results['results'] ?? [$results]);

        $report = "# Plugin Testing Summary Report\n\n";
        $report .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";

        $report .= "## Overall Results\n\n";
        $report .= "- **Total Plugins:** {$summary['total_plugins']}\n";
        $report .= "- **Successful Plugins:** {$summary['successful_plugins']}\n";
        $report .= "- **Failed Plugins:** {$summary['failed_plugins']}\n";
        $report .= "- **Plugins Without Tests:** {$summary['plugins_without_tests']}\n\n";

        $report .= "## Test Statistics\n\n";
        $report .= "- **Total Test Suites:** {$summary['total_test_suites']}\n";
        $report .= "- **Total Tests:** {$summary['total_tests']}\n";
        $report .= "- **Passed:** {$summary['total_passed']}\n";
        $report .= "- **Failed:** {$summary['total_failed']}\n";
        $report .= "- **Skipped:** {$summary['total_skipped']}\n";
        $report .= "- **Total Duration:** " . round($summary['total_duration'], 2) . " seconds\n\n";

        if ($summary['total_tests'] > 0) {
            $successRate = round(($summary['total_passed'] / $summary['total_tests']) * 100, 2);
            $report .= "**Success Rate:** {$successRate}%\n\n";
        }

        return $report;
    }

    private function generateDetailedReport(array $results): string
    {
        $report = $this->generateSummaryReport($results);

        $testResults = isset($results['results']) ? $results['results'] : [$results];

        $report .= "## Detailed Results\n\n";

        foreach ($testResults as $result) {
            $pluginName = $result['plugin'];
            $report .= "### Plugin: {$pluginName}\n\n";

            if (isset($result['warning'])) {
                $report .= "⚠️ **Warning:** {$result['warning']}\n\n";
                continue;
            }

            $status = $result['success'] ? '✅ **PASSED**' : '❌ **FAILED**';
            $report .= "**Status:** {$status}\n\n";

            if (isset($result['summary'])) {
                $summary = $result['summary'];
                $report .= "**Test Summary:**\n";
                $report .= "- Test Suites: {$summary['total_test_suites']}\n";
                $report .= "- Total Tests: {$summary['total_tests']}\n";
                $report .= "- Passed: {$summary['total_passed']}\n";
                $report .= "- Failed: {$summary['total_failed']}\n";
                $report .= "- Duration: " . round($summary['total_duration'], 2) . " seconds\n\n";
            }

            if (isset($result['results']) && !empty($result['results'])) {
                $report .= "**Test Suite Details:**\n\n";
                foreach ($result['results'] as $suiteResult) {
                    $report .= "- **Path:** `{$suiteResult['test_path']}`\n";
                    $report .= "  - Status: " . ($suiteResult['success'] ? 'PASSED' : 'FAILED') . "\n";
                    $report .= "  - Duration: {$suiteResult['duration']} seconds\n";

                    if (!$suiteResult['success'] && !empty($suiteResult['output'])) {
                        $report .= "  - Output:\n```\n" . substr($suiteResult['output'], 0, 500) . "\n```\n";
                    }
                    $report .= "\n";
                }
            }
        }

        return $report;
    }
}

// CLI usage
if (php_sapi_name() === 'cli') {
    $tester = new AutomatedPluginTesting();

    if ($argc < 2) {
        echo "Usage: php automated-plugin-testing.php <command> [options]\n";
        echo "Commands:\n";
        echo "  list                    - List all available plugins\n";
        echo "  test <plugin_name>      - Test specific plugin\n";
        echo "  test-all               - Test all plugins\n";
        echo "Options:\n";
        echo "  --coverage             - Include coverage report\n";
        echo "  --filter=<pattern>     - Filter tests by pattern\n";
        echo "  --runner=<pest|phpunit|artisan> - Specify test runner\n";
        echo "  --report=<summary|detailed> - Report type (default: detailed)\n";
        exit(1);
    }

    $command = $argv[1];
    $options = [];

    // Parse options
    for ($i = 2; $i < $argc; $i++) {
        $arg = $argv[$i];
        if (strpos($arg, '--') === 0) {
            if (strpos($arg, '=') !== false) {
                list($key, $value) = explode('=', substr($arg, 2), 2);
                $options[$key] = $value;
            } else {
                $options[substr($arg, 2)] = true;
            }
        }
    }

    switch ($command) {
        case 'list':
            $plugins = $tester->getAvailablePlugins();
            echo "Available Plugins:\n";
            foreach ($plugins as $plugin) {
                $testStatus = $plugin['has_tests'] ? 'HAS TESTS' : 'NO TESTS';
                echo "- {$plugin['name']} ({$testStatus})\n";
            }
            break;

        case 'test':
            if ($argc < 3) {
                echo "Error: Plugin name required\n";
                exit(1);
            }
            $pluginName = $argv[2];
            echo "Testing plugin: {$pluginName}\n";
            $result = $tester->runPluginTests($pluginName, $options);

            $reportType = $options['report'] ?? 'detailed';
            $report = $tester->generateTestReport($result, $reportType);

            $reportPath = "plugin-test-report-{$pluginName}.md";
            file_put_contents($reportPath, $report);

            echo "Test completed!\n";
            echo "Status: " . ($result['success'] ? 'PASSED' : 'FAILED') . "\n";
            echo "Report saved to: {$reportPath}\n";
            break;

        case 'test-all':
            echo "Testing all plugins...\n";
            $results = $tester->runAllPluginTests($options);

            $reportType = $options['report'] ?? 'detailed';
            $report = $tester->generateTestReport($results, $reportType);

            $reportPath = 'all-plugins-test-report.md';
            file_put_contents($reportPath, $report);

            echo "All tests completed!\n";
            echo "Overall status: " . ($results['success'] ? 'PASSED' : 'FAILED') . "\n";
            echo "Report saved to: {$reportPath}\n";
            break;

        default:
            echo "Error: Unknown command '{$command}'\n";
            exit(1);
    }
}
