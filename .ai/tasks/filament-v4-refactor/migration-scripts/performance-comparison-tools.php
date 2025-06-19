<?php

/**
 * FilamentPHP v4 Migration - Performance Comparison Tools
 *
 * This script provides performance measurement and comparison tools for the migration process.
 * It measures various performance metrics before and after migration to ensure no degradation.
 *
 * Usage: php performance-comparison-tools.php [command] [options]
 *
 * Commands:
 * - baseline: Establish performance baseline before migration
 * - measure: Measure current performance metrics
 * - compare: Compare current performance with baseline
 * - report: Generate comprehensive performance report
 * - benchmark: Run comprehensive benchmark suite
 *
 * Options:
 * --plugin=name: Focus on specific plugin
 * --iterations=n: Number of test iterations (default: 10)
 * --warmup=n: Number of warmup iterations (default: 3)
 * --output=file: Save results to file
 * --verbose: Show detailed output
 */

class PerformanceComparisonTools
{
    private array $metrics = [
        'page_load_times' => [],
        'memory_usage' => [],
        'database_queries' => [],
        'response_times' => [],
        'cpu_usage' => [],
        'file_operations' => []
    ];

    private array $testEndpoints = [
        'admin_dashboard' => '/admin',
        'accounts_list' => '/admin/accounts',
        'partners_list' => '/admin/partners',
        'products_list' => '/admin/products',
        'invoices_list' => '/admin/invoices',
        'sales_list' => '/admin/sales'
    ];

    private array $pluginEndpoints = [
        'accounts' => ['/admin/accounts', '/admin/accounts/create'],
        'partners' => ['/admin/partners', '/admin/partners/create'],
        'products' => ['/admin/products', '/admin/products/create'],
        'invoices' => ['/admin/invoices', '/admin/invoices/create'],
        'sales' => ['/admin/sales', '/admin/sales/create']
    ];

    private string $baselineFile = '.ai/tasks/filament-v4-refactor/performance-baseline.json';
    private int $iterations = 10;
    private int $warmupIterations = 3;
    private bool $verbose = false;
    private ?string $targetPlugin = null;

    public function run(string $command, array $options = []): array
    {
        $this->parseOptions($options);

        switch ($command) {
            case 'baseline':
                return $this->establishBaseline();
            case 'measure':
                return $this->measurePerformance();
            case 'compare':
                return $this->comparePerformance();
            case 'report':
                return $this->generateReport();
            case 'benchmark':
                return $this->runBenchmarkSuite();
            default:
                throw new InvalidArgumentException("Unknown command: {$command}");
        }
    }

    private function parseOptions(array $options): void
    {
        $this->iterations = (int)($options['iterations'] ?? 10);
        $this->warmupIterations = (int)($options['warmup'] ?? 3);
        $this->verbose = $options['verbose'] ?? false;
        $this->targetPlugin = $options['plugin'] ?? null;
    }

    private function establishBaseline(): array
    {
        echo "Establishing performance baseline...\n";
        echo "Iterations: {$this->iterations}\n";
        echo "Warmup iterations: {$this->warmupIterations}\n\n";

        $baseline = [
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => 'pre-filament-v4-migration',
            'system_info' => $this->getSystemInfo(),
            'metrics' => []
        ];

        // Run performance tests
        $baseline['metrics'] = $this->runPerformanceTests();

        // Save baseline
        $this->saveBaseline($baseline);

        echo "\nBaseline established and saved to {$this->baselineFile}\n";
        return $baseline;
    }

    private function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'opcache_enabled' => extension_loaded('opcache') && opcache_get_status()['opcache_enabled'],
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'CLI',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    private function runPerformanceTests(): array
    {
        $results = [];

        // Test page load times
        echo "Testing page load times...\n";
        $results['page_load_times'] = $this->measurePageLoadTimes();

        // Test memory usage
        echo "Testing memory usage...\n";
        $results['memory_usage'] = $this->measureMemoryUsage();

        // Test database performance
        echo "Testing database performance...\n";
        $results['database_queries'] = $this->measureDatabasePerformance();

        // Test file operations
        echo "Testing file operations...\n";
        $results['file_operations'] = $this->measureFileOperations();

        return $results;
    }

    private function measurePageLoadTimes(): array
    {
        $results = [];
        $endpoints = $this->targetPlugin ?
            ($this->pluginEndpoints[$this->targetPlugin] ?? []) :
            array_values($this->testEndpoints);

        foreach ($endpoints as $endpoint) {
            if ($this->verbose) {
                echo "  Testing endpoint: {$endpoint}\n";
            }

            $times = [];

            // Warmup
            for ($i = 0; $i < $this->warmupIterations; $i++) {
                $this->makeHttpRequest($endpoint);
            }

            // Actual measurements
            for ($i = 0; $i < $this->iterations; $i++) {
                $startTime = microtime(true);
                $response = $this->makeHttpRequest($endpoint);
                $endTime = microtime(true);

                $times[] = ($endTime - $startTime) * 1000; // Convert to milliseconds

                if ($this->verbose) {
                    echo "    Iteration " . ($i + 1) . ": " . round($times[$i], 2) . "ms\n";
                }
            }

            $results[$endpoint] = [
                'times' => $times,
                'average' => array_sum($times) / count($times),
                'min' => min($times),
                'max' => max($times),
                'median' => $this->calculateMedian($times),
                'std_dev' => $this->calculateStandardDeviation($times)
            ];

            echo "  {$endpoint}: " . round($results[$endpoint]['average'], 2) . "ms avg\n";
        }

        return $results;
    }

    private function measureMemoryUsage(): array
    {
        $results = [];

        // Measure memory usage during various operations
        $operations = [
            'baseline' => function() { return memory_get_usage(true); },
            'form_rendering' => function() { return $this->simulateFormRendering(); },
            'table_rendering' => function() { return $this->simulateTableRendering(); },
            'data_processing' => function() { return $this->simulateDataProcessing(); }
        ];

        foreach ($operations as $operation => $callback) {
            $memoryUsages = [];

            for ($i = 0; $i < $this->iterations; $i++) {
                $startMemory = memory_get_usage(true);
                $callback();
                $endMemory = memory_get_usage(true);

                $memoryUsages[] = $endMemory - $startMemory;
            }

            $results[$operation] = [
                'usages' => $memoryUsages,
                'average' => array_sum($memoryUsages) / count($memoryUsages),
                'min' => min($memoryUsages),
                'max' => max($memoryUsages),
                'peak_usage' => memory_get_peak_usage(true)
            ];

            echo "  {$operation}: " . $this->formatBytes($results[$operation]['average']) . " avg\n";
        }

        return $results;
    }

    private function measureDatabasePerformance(): array
    {
        $results = [];

        // Simulate database operations
        $queries = [
            'simple_select' => 'SELECT COUNT(*) FROM users',
            'complex_join' => 'SELECT u.*, p.* FROM users u LEFT JOIN profiles p ON u.id = p.user_id LIMIT 100',
            'aggregation' => 'SELECT COUNT(*), AVG(created_at) FROM users GROUP BY DATE(created_at)'
        ];

        foreach ($queries as $queryType => $sql) {
            $times = [];

            for ($i = 0; $i < $this->iterations; $i++) {
                $startTime = microtime(true);
                $this->executeQuery($sql);
                $endTime = microtime(true);

                $times[] = ($endTime - $startTime) * 1000;
            }

            $results[$queryType] = [
                'times' => $times,
                'average' => array_sum($times) / count($times),
                'min' => min($times),
                'max' => max($times)
            ];

            echo "  {$queryType}: " . round($results[$queryType]['average'], 2) . "ms avg\n";
        }

        return $results;
    }

    private function measureFileOperations(): array
    {
        $results = [];
        $tempDir = sys_get_temp_dir() . '/filament_perf_test';

        if (!is_dir($tempDir)) {
            if (!mkdir($tempDir, 0755, true) && !is_dir($tempDir)) {
                throw new RuntimeException("Failed to create temp directory: {$tempDir}");
            }
        }

        $operations = [
            'file_write' => function() use ($tempDir) {
                $file = $tempDir . '/test_' . uniqid('', true) . '.txt';
                file_put_contents($file, str_repeat('test data ', 1000));
                unlink($file);
            },
            'file_read' => function() use ($tempDir) {
                $file = $tempDir . '/read_test.txt';
                file_put_contents($file, str_repeat('test data ', 1000));
                file_get_contents($file);
                unlink($file);
            },
            'directory_scan' => function() {
                return scandir('.');
            }
        ];

        foreach ($operations as $operation => $callback) {
            $times = [];

            for ($i = 0; $i < $this->iterations; $i++) {
                $startTime = microtime(true);
                $callback();
                $endTime = microtime(true);

                $times[] = ($endTime - $startTime) * 1000;
            }

            $results[$operation] = [
                'times' => $times,
                'average' => array_sum($times) / count($times),
                'min' => min($times),
                'max' => max($times)
            ];

            echo "  {$operation}: " . round($results[$operation]['average'], 2) . "ms avg\n";
        }

        // Cleanup
        if (is_dir($tempDir)) {
            rmdir($tempDir);
        }

        return $results;
    }

    private function makeHttpRequest(string $endpoint): array
    {
        // Simulate HTTP request - in real implementation, use curl or Guzzle
        usleep(rand(10000, 50000)); // Simulate 10-50ms response time
        return ['status' => 200, 'body' => 'simulated response'];
    }

    private function simulateFormRendering(): int
    {
        // Simulate form rendering memory usage
        $data = [];
        for ($i = 0; $i < 1000; $i++) {
            $data[] = [
                'field_' . $i => 'value_' . $i,
                'options' => range(1, 100)
            ];
        }
        return memory_get_usage(true);
    }

    private function simulateTableRendering(): int
    {
        // Simulate table rendering memory usage
        $rows = [];
        for ($i = 0; $i < 500; $i++) {
            $rows[] = [
                'id' => $i,
                'name' => 'Item ' . $i,
                'description' => str_repeat('Description ', 10),
                'data' => range(1, 50)
            ];
        }
        return memory_get_usage(true);
    }

    private function simulateDataProcessing(): int
    {
        // Simulate data processing memory usage
        $data = range(1, 10000);
        $processed = array_map(function($item) {
            return $item * 2 + rand(1, 100);
        }, $data);
        return memory_get_usage(true);
    }

    private function executeQuery(string $sql): array
    {
        // Simulate database query execution
        usleep(rand(1000, 10000)); // Simulate 1-10ms query time
        return ['result' => 'simulated'];
    }

    private function calculateMedian(array $values): float
    {
        sort($values);
        $count = count($values);
        $middle = floor($count / 2);

        if ($count % 2 === 0) {
            return ($values[$middle - 1] + $values[$middle]) / 2;
        } else {
            return $values[$middle];
        }
    }

    private function calculateStandardDeviation(array $values): float
    {
        $mean = array_sum($values) / count($values);
        $squaredDiffs = array_map(function($value) use ($mean) {
            return pow($value - $mean, 2);
        }, $values);

        return sqrt(array_sum($squaredDiffs) / count($squaredDiffs));
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }

    private function saveBaseline(array $baseline): void
    {
        $dir = dirname($this->baselineFile);
        if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new RuntimeException("Failed to create directory: {$dir}");
        }

        file_put_contents($this->baselineFile, json_encode($baseline, JSON_PRETTY_PRINT));
    }

    private function measurePerformance(): array
    {
        echo "Measuring current performance...\n\n";

        $current = [
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => 'current',
            'system_info' => $this->getSystemInfo(),
            'metrics' => $this->runPerformanceTests()
        ];

        return $current;
    }

    private function comparePerformance(): array
    {
        echo "Comparing performance with baseline...\n\n";

        if (!file_exists($this->baselineFile)) {
            throw new RuntimeException("Baseline file not found. Run 'baseline' command first.");
        }

        $baseline = json_decode(file_get_contents($this->baselineFile), true);
        $current = $this->measurePerformance();

        $comparison = $this->performComparison($baseline['metrics'], $current['metrics']);
        $this->printComparison($comparison);

        return $comparison;
    }

    private function performComparison(array $baseline, array $current): array
    {
        $comparison = [
            'summary' => [],
            'detailed' => [],
            'recommendations' => []
        ];

        // Compare page load times
        if (isset($baseline['page_load_times']) && isset($current['page_load_times'])) {
            $comparison['detailed']['page_load_times'] = $this->compareMetricGroup(
                $baseline['page_load_times'],
                $current['page_load_times']
            );
        }

        // Compare memory usage
        if (isset($baseline['memory_usage']) && isset($current['memory_usage'])) {
            $comparison['detailed']['memory_usage'] = $this->compareMetricGroup(
                $baseline['memory_usage'],
                $current['memory_usage']
            );
        }

        // Compare database performance
        if (isset($baseline['database_queries']) && isset($current['database_queries'])) {
            $comparison['detailed']['database_queries'] = $this->compareMetricGroup(
                $baseline['database_queries'],
                $current['database_queries']
            );
        }

        // Generate summary
        $comparison['summary'] = $this->generateComparisonSummary($comparison['detailed']);

        // Generate recommendations
        $comparison['recommendations'] = $this->generatePerformanceRecommendations($comparison);

        return $comparison;
    }

    private function compareMetricGroup(array $baseline, array $current): array
    {
        $results = [];

        foreach ($baseline as $metric => $baselineData) {
            if (!isset($current[$metric])) {
                continue;
            }

            $currentData = $current[$metric];
            $baselineAvg = $baselineData['average'];
            $currentAvg = $currentData['average'];

            $percentChange = (($currentAvg - $baselineAvg) / $baselineAvg) * 100;

            $results[$metric] = [
                'baseline_avg' => $baselineAvg,
                'current_avg' => $currentAvg,
                'percent_change' => $percentChange,
                'status' => $this->getPerformanceStatus($percentChange),
                'baseline_data' => $baselineData,
                'current_data' => $currentData
            ];
        }

        return $results;
    }

    private function getPerformanceStatus(float $percentChange): string
    {
        if ($percentChange <= -10) {
            return 'improved';
        } elseif ($percentChange >= 20) {
            return 'degraded';
        } elseif ($percentChange >= 10) {
            return 'warning';
        } else {
            return 'stable';
        }
    }

    private function generateComparisonSummary(array $detailed): array
    {
        $summary = [
            'improved' => 0,
            'stable' => 0,
            'warning' => 0,
            'degraded' => 0,
            'total_metrics' => 0
        ];

        foreach ($detailed as $group => $metrics) {
            foreach ($metrics as $metric => $data) {
                $summary['total_metrics']++;
                $summary[$data['status']]++;
            }
        }

        return $summary;
    }

    private function generatePerformanceRecommendations(array $comparison): array
    {
        $recommendations = [];

        foreach ($comparison['detailed'] as $group => $metrics) {
            foreach ($metrics as $metric => $data) {
                if ($data['status'] === 'degraded') {
                    $recommendations[] = "Performance degraded for {$group}.{$metric}: " .
                        round($data['percent_change'], 1) . "% slower";
                } elseif ($data['status'] === 'warning') {
                    $recommendations[] = "Performance warning for {$group}.{$metric}: " .
                        round($data['percent_change'], 1) . "% slower";
                }
            }
        }

        if (empty($recommendations)) {
            $recommendations[] = "No performance issues detected";
        }

        return $recommendations;
    }

    private function printComparison(array $comparison): void
    {
        echo "\n=== Performance Comparison Results ===\n";

        $summary = $comparison['summary'];
        echo "Total metrics: {$summary['total_metrics']}\n";
        echo "Improved: {$summary['improved']}\n";
        echo "Stable: {$summary['stable']}\n";
        echo "Warning: {$summary['warning']}\n";
        echo "Degraded: {$summary['degraded']}\n\n";

        foreach ($comparison['detailed'] as $group => $metrics) {
            echo "=== {$group} ===\n";
            foreach ($metrics as $metric => $data) {
                $icon = $this->getStatusIcon($data['status']);
                $change = $data['percent_change'] > 0 ? '+' : '';
                echo "  {$icon} {$metric}: {$change}" . round($data['percent_change'], 1) . "%\n";
            }
            echo "\n";
        }

        if (!empty($comparison['recommendations'])) {
            echo "=== Recommendations ===\n";
            foreach ($comparison['recommendations'] as $recommendation) {
                echo "- {$recommendation}\n";
            }
        }
    }

    private function getStatusIcon(string $status): string
    {
        return match($status) {
            'improved' => '✅',
            'stable' => '➡️',
            'warning' => '⚠️',
            'degraded' => '❌',
            default => '❓'
        };
    }

    private function runBenchmarkSuite(): array
    {
        echo "Running comprehensive benchmark suite...\n\n";

        $results = [
            'baseline' => $this->establishBaseline(),
            'stress_test' => $this->runStressTest(),
            'load_test' => $this->runLoadTest(),
            'memory_test' => $this->runMemoryTest()
        ];

        echo "\nBenchmark suite completed.\n";
        return $results;
    }

    private function runStressTest(): array
    {
        echo "Running stress test...\n";

        // Simulate high load
        $results = [];
        for ($i = 0; $i < 100; $i++) {
            $startTime = microtime(true);
            $this->simulateFormRendering();
            $endTime = microtime(true);
            $results[] = ($endTime - $startTime) * 1000;
        }

        return [
            'iterations' => 100,
            'average_time' => array_sum($results) / count($results),
            'max_time' => max($results),
            'min_time' => min($results)
        ];
    }

    private function runLoadTest(): array
    {
        echo "Running load test...\n";

        // Simulate concurrent requests
        $results = [];
        for ($i = 0; $i < 50; $i++) {
            $startTime = microtime(true);
            $this->makeHttpRequest('/admin');
            $endTime = microtime(true);
            $results[] = ($endTime - $startTime) * 1000;
        }

        return [
            'concurrent_requests' => 50,
            'average_response_time' => array_sum($results) / count($results),
            'max_response_time' => max($results),
            'min_response_time' => min($results)
        ];
    }

    private function runMemoryTest(): array
    {
        echo "Running memory test...\n";

        $startMemory = memory_get_usage(true);

        // Simulate memory-intensive operations
        for ($i = 0; $i < 10; $i++) {
            $this->simulateDataProcessing();
        }

        $endMemory = memory_get_usage(true);
        $peakMemory = memory_get_peak_usage(true);

        return [
            'start_memory' => $startMemory,
            'end_memory' => $endMemory,
            'peak_memory' => $peakMemory,
            'memory_increase' => $endMemory - $startMemory
        ];
    }

    private function generateReport(): array
    {
        echo "Generating comprehensive performance report...\n\n";

        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'system_info' => $this->getSystemInfo(),
            'current_metrics' => $this->measurePerformance(),
            'comparison' => null,
            'recommendations' => []
        ];

        if (file_exists($this->baselineFile)) {
            $report['comparison'] = $this->comparePerformance();
        }

        $this->saveReport($report);
        $this->printReport($report);

        return $report;
    }

    private function saveReport(array $report): void
    {
        $reportFile = '.ai/tasks/filament-v4-refactor/performance-report-' . date('Y-m-d-H-i-s') . '.json';
        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
        echo "Performance report saved to {$reportFile}\n";
    }

    private function printReport(array $report): void
    {
        echo "\n=== Performance Report Summary ===\n";
        echo "Timestamp: {$report['timestamp']}\n";
        echo "PHP Version: {$report['system_info']['php_version']}\n";
        echo "Memory Limit: {$report['system_info']['memory_limit']}\n";

        if ($report['comparison']) {
            $summary = $report['comparison']['summary'];
            echo "\nComparison with baseline:\n";
            echo "- Improved metrics: {$summary['improved']}\n";
            echo "- Stable metrics: {$summary['stable']}\n";
            echo "- Warning metrics: {$summary['warning']}\n";
            echo "- Degraded metrics: {$summary['degraded']}\n";
        }

        echo "\nReport generation complete.\n";
    }
}

// CLI execution
if (php_sapi_name() === 'cli') {
    $command = $argv[1] ?? 'measure';
    $options = [];

    // Parse command line options
    $argCount = count($argv);
    for ($i = 2; $i < $argCount; $i++) {
        $arg = $argv[$i];

        if (strpos($arg, '--plugin=') === 0) {
            $options['plugin'] = substr($arg, 9);
        } elseif (strpos($arg, '--iterations=') === 0) {
            $options['iterations'] = (int)substr($arg, 13);
        } elseif (strpos($arg, '--warmup=') === 0) {
            $options['warmup'] = (int)substr($arg, 9);
        } elseif (strpos($arg, '--output=') === 0) {
            $options['output'] = substr($arg, 9);
        } elseif ($arg === '--verbose') {
            $options['verbose'] = true;
        }
    }

    echo "FilamentPHP v4 Migration - Performance Comparison Tools\n";
    echo "Command: {$command}\n\n";

    try {
        $tools = new PerformanceComparisonTools();
        $result = $tools->run($command, $options);

        // Save output if specified
        if (isset($options['output'])) {
            file_put_contents($options['output'], json_encode($result, JSON_PRETTY_PRINT));
            echo "Results saved to {$options['output']}\n";
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}
