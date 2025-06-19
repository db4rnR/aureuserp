<?php

/**
 * FilamentPHP v4 Performance Comparison Tools
 *
 * This script provides performance benchmarking and comparison tools to ensure
 * the migration to FilamentPHP v4 doesn't introduce performance regressions.
 */

class PerformanceComparisonTools
{
    private string $projectRoot;
    private array $benchmarkResults = [];
    private array $defaultMetrics = [
        'memory_usage',
        'execution_time',
        'database_queries',
        'response_time',
        'cpu_usage'
    ];

    public function __construct(string $projectRoot = '.')
    {
        $this->projectRoot = realpath($projectRoot);
    }

    public function runResourceBenchmark(string $resourceClass, array $options = []): array
    {
        $iterations = $options['iterations'] ?? 10;
        $warmupRuns = $options['warmup'] ?? 3;

        $results = [
            'resource_class' => $resourceClass,
            'iterations' => $iterations,
            'metrics' => [],
            'raw_data' => []
        ];

        // Warmup runs
        for ($i = 0; $i < $warmupRuns; $i++) {
            $this->runSingleBenchmark($resourceClass, $options);
        }

        // Actual benchmark runs
        for ($i = 0; $i < $iterations; $i++) {
            $runResult = $this->runSingleBenchmark($resourceClass, $options);
            $results['raw_data'][] = $runResult;
        }

        // Calculate statistics
        $results['metrics'] = $this->calculateMetrics($results['raw_data']);

        return $results;
    }

    private function runSingleBenchmark(string $resourceClass, array $options): array
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        $startPeakMemory = memory_get_peak_usage(true);

        // Enable query logging if available
        $queryCount = $this->getQueryCount();

        try {
            // Simulate resource operations
            $result = $this->simulateResourceOperations($resourceClass, $options);

            $endTime = microtime(true);
            $endMemory = memory_get_usage(true);
            $endPeakMemory = memory_get_peak_usage(true);
            $endQueryCount = $this->getQueryCount();

            return [
                'execution_time' => ($endTime - $startTime) * 1000, // Convert to milliseconds
                'memory_usage' => $endMemory - $startMemory,
                'peak_memory_usage' => $endPeakMemory - $startPeakMemory,
                'query_count' => $endQueryCount - $queryCount,
                'success' => true,
                'result' => $result
            ];

        } catch (Exception $e) {
            $endTime = microtime(true);
            $endMemory = memory_get_usage(true);
            $endQueryCount = $this->getQueryCount();

            return [
                'execution_time' => ($endTime - $startTime) * 1000,
                'memory_usage' => $endMemory - $startMemory,
                'peak_memory_usage' => 0,
                'query_count' => $endQueryCount - $queryCount,
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function simulateResourceOperations(string $resourceClass, array $options): array
    {
        $operations = $options['operations'] ?? ['form', 'table', 'infolist'];
        $results = [];

        foreach ($operations as $operation) {
            switch ($operation) {
                case 'form':
                    $results['form'] = $this->benchmarkFormOperation($resourceClass);
                    break;
                case 'table':
                    $results['table'] = $this->benchmarkTableOperation($resourceClass);
                    break;
                case 'infolist':
                    $results['infolist'] = $this->benchmarkInfolistOperation($resourceClass);
                    break;
                case 'actions':
                    $results['actions'] = $this->benchmarkActionsOperation($resourceClass);
                    break;
            }
        }

        return $results;
    }

    private function benchmarkFormOperation(string $resourceClass): array
    {
        $startTime = microtime(true);

        try {
            // Simulate form creation and rendering
            if (class_exists($resourceClass) && method_exists($resourceClass, 'form')) {
                // Create a mock form instance
                $form = $this->createMockForm();
                $resourceClass::form($form);

                $endTime = microtime(true);
                return [
                    'operation' => 'form',
                    'duration' => ($endTime - $startTime) * 1000,
                    'success' => true
                ];
            }
        } catch (Exception $e) {
            $endTime = microtime(true);
            return [
                'operation' => 'form',
                'duration' => ($endTime - $startTime) * 1000,
                'success' => false,
                'error' => $e->getMessage()
            ];
        }

        return [
            'operation' => 'form',
            'duration' => 0,
            'success' => false,
            'error' => 'Form method not found'
        ];
    }

    private function benchmarkTableOperation(string $resourceClass): array
    {
        $startTime = microtime(true);

        try {
            if (class_exists($resourceClass) && method_exists($resourceClass, 'table')) {
                // Create a mock table instance
                $table = $this->createMockTable();
                $resourceClass::table($table);

                $endTime = microtime(true);
                return [
                    'operation' => 'table',
                    'duration' => ($endTime - $startTime) * 1000,
                    'success' => true
                ];
            }
        } catch (Exception $e) {
            $endTime = microtime(true);
            return [
                'operation' => 'table',
                'duration' => ($endTime - $startTime) * 1000,
                'success' => false,
                'error' => $e->getMessage()
            ];
        }

        return [
            'operation' => 'table',
            'duration' => 0,
            'success' => false,
            'error' => 'Table method not found'
        ];
    }

    private function benchmarkInfolistOperation(string $resourceClass): array
    {
        $startTime = microtime(true);

        try {
            if (class_exists($resourceClass) && method_exists($resourceClass, 'infolist')) {
                // Create a mock infolist instance
                $infolist = $this->createMockInfolist();
                $resourceClass::infolist($infolist);

                $endTime = microtime(true);
                return [
                    'operation' => 'infolist',
                    'duration' => ($endTime - $startTime) * 1000,
                    'success' => true
                ];
            }
        } catch (Exception $e) {
            $endTime = microtime(true);
            return [
                'operation' => 'infolist',
                'duration' => ($endTime - $startTime) * 1000,
                'success' => false,
                'error' => $e->getMessage()
            ];
        }

        return [
            'operation' => 'infolist',
            'duration' => 0,
            'success' => false,
            'error' => 'Infolist method not found'
        ];
    }

    private function benchmarkActionsOperation(string $resourceClass): array
    {
        $startTime = microtime(true);

        try {
            // Benchmark various action methods if they exist
            $actions = [];

            if (method_exists($resourceClass, 'getActions')) {
                $actions['getActions'] = $resourceClass::getActions();
            }

            if (method_exists($resourceClass, 'getHeaderActions')) {
                $actions['getHeaderActions'] = $resourceClass::getHeaderActions();
            }

            $endTime = microtime(true);
            return [
                'operation' => 'actions',
                'duration' => ($endTime - $startTime) * 1000,
                'success' => true,
                'actions_found' => count($actions)
            ];
        } catch (Exception $e) {
            $endTime = microtime(true);
            return [
                'operation' => 'actions',
                'duration' => ($endTime - $startTime) * 1000,
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function createMockForm(): object
    {
        // Create a simple mock form object
        return new class {
            private array $schema = [];

            public function schema(array $components): self
            {
                $this->schema = $components;
                return $this;
            }

            public function getSchema(): array
            {
                return $this->schema;
            }
        };
    }

    private function createMockTable(): object
    {
        // Create a simple mock table object
        return new class {
            private array $columns = [];
            private array $actions = [];

            public function columns(array $columns): self
            {
                $this->columns = $columns;
                return $this;
            }

            public function actions(array $actions): self
            {
                $this->actions = $actions;
                return $this;
            }
        };
    }

    private function createMockInfolist(): object
    {
        // Create a simple mock infolist object
        return new class {
            private array $schema = [];

            public function schema(array $components): self
            {
                $this->schema = $components;
                return $this;
            }
        };
    }

    private function getQueryCount(): int
    {
        // Try to get query count from Laravel's query log
        try {
            if (function_exists('app') && app()->bound('db')) {
                return count(app('db')->getQueryLog());
            }
        } catch (Exception $e) {
            // Ignore errors
        }

        return 0;
    }

    private function calculateMetrics(array $rawData): array
    {
        if (empty($rawData)) {
            return [];
        }

        $metrics = [];
        $successfulRuns = array_filter($rawData, fn($run) => $run['success']);

        if (empty($successfulRuns)) {
            return [
                'success_rate' => 0,
                'total_runs' => count($rawData),
                'successful_runs' => 0
            ];
        }

        $metricKeys = ['execution_time', 'memory_usage', 'peak_memory_usage', 'query_count'];

        foreach ($metricKeys as $key) {
            $values = array_column($successfulRuns, $key);

            $metrics[$key] = [
                'min' => min($values),
                'max' => max($values),
                'avg' => array_sum($values) / count($values),
                'median' => $this->calculateMedian($values),
                'std_dev' => $this->calculateStandardDeviation($values)
            ];
        }

        $metrics['success_rate'] = (count($successfulRuns) / count($rawData)) * 100;
        $metrics['total_runs'] = count($rawData);
        $metrics['successful_runs'] = count($successfulRuns);

        return $metrics;
    }

    private function calculateMedian(array $values): float
    {
        sort($values);
        $count = count($values);

        if ($count % 2 === 0) {
            return ($values[$count / 2 - 1] + $values[$count / 2]) / 2;
        } else {
            return $values[floor($count / 2)];
        }
    }

    private function calculateStandardDeviation(array $values): float
    {
        $mean = array_sum($values) / count($values);
        $squaredDifferences = array_map(fn($value) => pow($value - $mean, 2), $values);
        $variance = array_sum($squaredDifferences) / count($values);

        return sqrt($variance);
    }

    public function compareResults(array $beforeResults, array $afterResults): array
    {
        $comparison = [
            'before' => $beforeResults,
            'after' => $afterResults,
            'improvements' => [],
            'regressions' => [],
            'summary' => []
        ];

        $metricKeys = ['execution_time', 'memory_usage', 'peak_memory_usage', 'query_count'];

        foreach ($metricKeys as $key) {
            if (isset($beforeResults['metrics'][$key]) && isset($afterResults['metrics'][$key])) {
                $beforeAvg = $beforeResults['metrics'][$key]['avg'];
                $afterAvg = $afterResults['metrics'][$key]['avg'];

                $percentChange = (($afterAvg - $beforeAvg) / $beforeAvg) * 100;

                $comparison['summary'][$key] = [
                    'before_avg' => $beforeAvg,
                    'after_avg' => $afterAvg,
                    'change' => $afterAvg - $beforeAvg,
                    'percent_change' => $percentChange
                ];

                if ($percentChange < -5) { // Improvement threshold
                    $comparison['improvements'][] = [
                        'metric' => $key,
                        'improvement' => abs($percentChange)
                    ];
                } elseif ($percentChange > 5) { // Regression threshold
                    $comparison['regressions'][] = [
                        'metric' => $key,
                        'regression' => $percentChange
                    ];
                }
            }
        }

        return $comparison;
    }

    public function benchmarkAllResources(string $pluginPath, array $options = []): array
    {
        $resources = $this->findResourceClasses($pluginPath);
        $results = [];

        foreach ($resources as $resourceClass) {
            echo "Benchmarking: $resourceClass\n";
            $results[$resourceClass] = $this->runResourceBenchmark($resourceClass, $options);
        }

        return $results;
    }

    private function findResourceClasses(string $pluginPath): array
    {
        $resources = [];
        $resourcesPath = $pluginPath . '/src/Filament/Resources';

        if (!is_dir($resourcesPath)) {
            return $resources;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($resourcesPath)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());

                // Extract class name and namespace
                if (preg_match('/namespace\s+([^;]+);/', $content, $namespaceMatch) &&
                    preg_match('/class\s+(\w+)/', $content, $classMatch)) {

                    $className = $namespaceMatch[1] . '\\' . $classMatch[1];

                    // Check if it's a Resource class
                    if (strpos($content, 'extends Resource') !== false ||
                        strpos($content, 'extends BaseResource') !== false) {
                        $resources[] = $className;
                    }
                }
            }
        }

        return $resources;
    }

    public function generatePerformanceReport(array $results, string $type = 'detailed'): string
    {
        if ($type === 'comparison' && isset($results['before']) && isset($results['after'])) {
            return $this->generateComparisonReport($results);
        } else {
            return $this->generateBenchmarkReport($results);
        }
    }

    private function generateBenchmarkReport(array $results): string
    {
        $report = "# Performance Benchmark Report\n\n";
        $report .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($results as $resourceClass => $result) {
            $report .= "## Resource: " . basename($resourceClass) . "\n\n";
            $report .= "**Class:** `$resourceClass`\n\n";

            if (isset($result['metrics'])) {
                $metrics = $result['metrics'];

                $report .= "### Performance Metrics\n\n";
                $report .= "- **Success Rate:** " . round($metrics['success_rate'], 2) . "%\n";
                $report .= "- **Total Runs:** {$metrics['total_runs']}\n";
                $report .= "- **Successful Runs:** {$metrics['successful_runs']}\n\n";

                $report .= "#### Execution Time (ms)\n";
                $report .= "- Min: " . round($metrics['execution_time']['min'], 2) . "\n";
                $report .= "- Max: " . round($metrics['execution_time']['max'], 2) . "\n";
                $report .= "- Average: " . round($metrics['execution_time']['avg'], 2) . "\n";
                $report .= "- Median: " . round($metrics['execution_time']['median'], 2) . "\n\n";

                $report .= "#### Memory Usage (bytes)\n";
                $report .= "- Min: " . number_format($metrics['memory_usage']['min']) . "\n";
                $report .= "- Max: " . number_format($metrics['memory_usage']['max']) . "\n";
                $report .= "- Average: " . number_format($metrics['memory_usage']['avg']) . "\n\n";

                $report .= "#### Database Queries\n";
                $report .= "- Min: " . $metrics['query_count']['min'] . "\n";
                $report .= "- Max: " . $metrics['query_count']['max'] . "\n";
                $report .= "- Average: " . round($metrics['query_count']['avg'], 2) . "\n\n";
            }
        }

        return $report;
    }

    private function generateComparisonReport(array $comparison): string
    {
        $report = "# Performance Comparison Report\n\n";
        $report .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";

        $report .= "## Summary\n\n";

        if (!empty($comparison['improvements'])) {
            $report .= "### ✅ Improvements\n\n";
            foreach ($comparison['improvements'] as $improvement) {
                $report .= "- **{$improvement['metric']}:** " . round($improvement['improvement'], 2) . "% improvement\n";
            }
            $report .= "\n";
        }

        if (!empty($comparison['regressions'])) {
            $report .= "### ⚠️ Regressions\n\n";
            foreach ($comparison['regressions'] as $regression) {
                $report .= "- **{$regression['metric']}:** " . round($regression['regression'], 2) . "% regression\n";
            }
            $report .= "\n";
        }

        if (empty($comparison['improvements']) && empty($comparison['regressions'])) {
            $report .= "### ✅ No Significant Changes\n\n";
            $report .= "Performance metrics remained stable after migration.\n\n";
        }

        $report .= "## Detailed Metrics\n\n";

        foreach ($comparison['summary'] as $metric => $data) {
            $report .= "### " . ucfirst(str_replace('_', ' ', $metric)) . "\n\n";
            $report .= "- **Before:** " . round($data['before_avg'], 2) . "\n";
            $report .= "- **After:** " . round($data['after_avg'], 2) . "\n";
            $report .= "- **Change:** " . round($data['change'], 2) . "\n";
            $report .= "- **Percent Change:** " . round($data['percent_change'], 2) . "%\n\n";
        }

        return $report;
    }

    public function saveResults(array $results, string $filename): void
    {
        $data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'results' => $results
        ];

        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function loadResults(string $filename): array
    {
        if (!file_exists($filename)) {
            throw new Exception("Results file not found: $filename");
        }

        $data = json_decode(file_get_contents($filename), true);
        return $data['results'] ?? [];
    }
}

// CLI usage
if (php_sapi_name() === 'cli') {
    $tools = new PerformanceComparisonTools();

    if ($argc < 2) {
        echo "Usage: php performance-comparison-tools.php <command> [options]\n";
        echo "Commands:\n";
        echo "  benchmark <plugin_path>     - Benchmark all resources in plugin\n";
        echo "  compare <before_file> <after_file> - Compare two benchmark results\n";
        echo "Options:\n";
        echo "  --iterations=<number>       - Number of benchmark iterations (default: 10)\n";
        echo "  --warmup=<number>          - Number of warmup runs (default: 3)\n";
        echo "  --output=<filename>        - Save results to file\n";
        echo "  --report=<filename>        - Generate report file\n";
        exit(1);
    }

    $command = $argv[1];
    $options = [];

    // Parse options
    for ($i = 3; $i < $argc; $i++) {
        $arg = $argv[$i];
        if (strpos($arg, '--') === 0 && strpos($arg, '=') !== false) {
            list($key, $value) = explode('=', substr($arg, 2), 2);
            $options[$key] = is_numeric($value) ? (int)$value : $value;
        }
    }

    switch ($command) {
        case 'benchmark':
            if ($argc < 3) {
                echo "Error: Plugin path required\n";
                exit(1);
            }

            $pluginPath = $argv[2];
            if (!is_dir($pluginPath)) {
                echo "Error: Plugin path not found: $pluginPath\n";
                exit(1);
            }

            echo "Starting performance benchmark for: $pluginPath\n";
            $results = $tools->benchmarkAllResources($pluginPath, $options);

            if (isset($options['output'])) {
                $tools->saveResults($results, $options['output']);
                echo "Results saved to: {$options['output']}\n";
            }

            $report = $tools->generatePerformanceReport($results);
            $reportFile = $options['report'] ?? 'performance-benchmark-report.md';
            file_put_contents($reportFile, $report);

            echo "Benchmark completed!\n";
            echo "Report saved to: $reportFile\n";
            break;

        case 'compare':
            if ($argc < 4) {
                echo "Error: Before and after result files required\n";
                exit(1);
            }

            $beforeFile = $argv[2];
            $afterFile = $argv[3];

            try {
                $beforeResults = $tools->loadResults($beforeFile);
                $afterResults = $tools->loadResults($afterFile);

                echo "Comparing performance results...\n";

                // For simplicity, compare first resource in each file
                $beforeResource = array_values($beforeResults)[0] ?? null;
                $afterResource = array_values($afterResults)[0] ?? null;

                if ($beforeResource && $afterResource) {
                    $comparison = $tools->compareResults($beforeResource, $afterResource);
                    $report = $tools->generatePerformanceReport($comparison, 'comparison');

                    $reportFile = $options['report'] ?? 'performance-comparison-report.md';
                    file_put_contents($reportFile, $report);

                    echo "Comparison completed!\n";
                    echo "Report saved to: $reportFile\n";
                } else {
                    echo "Error: Could not find comparable results in files\n";
                    exit(1);
                }

            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . "\n";
                exit(1);
            }
            break;

        default:
            echo "Error: Unknown command '$command'\n";
            exit(1);
    }
}
