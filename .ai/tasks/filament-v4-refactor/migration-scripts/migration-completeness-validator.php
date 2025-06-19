<?php

/**
 * FilamentPHP v4 Migration Completeness Validator
 *
 * This script validates whether the FilamentPHP v4 migration has been completed correctly.
 * It checks for remaining v3 patterns, missing imports, and incomplete migrations.
 *
 * Usage: php migration-completeness-validator.php [path-to-scan] [--plugin=plugin-name] [--strict]
 *
 * Features:
 * 1. Validates complete migration from v3 to v4 patterns
 * 2. Checks for remaining deprecated imports and method signatures
 * 3. Validates component usage consistency
 * 4. Generates detailed migration completeness report
 * 5. Plugin-specific validation
 * 6. Strict mode for comprehensive validation
 */

class FilamentMigrationCompletenessValidator
{
    private array $stats = [
        'files_processed' => 0,
        'plugins_processed' => 0,
        'validation_errors' => 0,
        'validation_warnings' => 0,
        'migration_complete' => 0,
        'migration_incomplete' => 0,
        'errors' => []
    ];

    private array $validationRules = [
        'deprecated_imports' => [
            'severity' => 'error',
            'description' => 'Deprecated v3 import statements found',
            'patterns' => [
                '/use Filament\\Schemas\\Schema;/',
                '/use Filament\\Schemas\\Components\\/',
                '/use Filament\\Schemas\\Get;/',
                '/use Filament\\Schemas\\Set;/',
            ]
        ],
        'deprecated_method_signatures' => [
            'severity' => 'error',
            'description' => 'Deprecated v3 method signatures found',
            'patterns' => [
                '/function\s+form\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/',
                '/function\s+infolist\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/',
            ]
        ],
        'deprecated_method_calls' => [
            'severity' => 'error',
            'description' => 'Deprecated v3 method calls found',
            'patterns' => [
                '/\$schema->components\s*\(/',
            ]
        ],
        'missing_v4_imports' => [
            'severity' => 'error',
            'description' => 'Missing required v4 imports',
            'check' => 'checkMissingV4Imports'
        ],
        'inconsistent_component_usage' => [
            'severity' => 'warning',
            'description' => 'Inconsistent component usage patterns',
            'check' => 'checkInconsistentComponentUsage'
        ],
        'mixed_v3_v4_patterns' => [
            'severity' => 'error',
            'description' => 'Mixed v3 and v4 patterns in same file',
            'check' => 'checkMixedPatterns'
        ]
    ];

    private array $requiredV4Patterns = [
        'form_method' => [
            'signature' => '/function\s+form\s*\(\s*Form\s+\$form\s*\)\s*:\s*Form/',
            'import' => 'use Filament\Forms\Form;',
            'method_call' => '/\$form->schema\s*\(/',
        ],
        'infolist_method' => [
            'signature' => '/function\s+infolist\s*\(\s*Infolist\s+\$infolist\s*\)\s*:\s*Infolist/',
            'import' => 'use Filament\Infolists\Infolist;',
            'method_call' => '/\$infolist->schema\s*\(/',
        ]
    ];

    private bool $strictMode = false;
    private ?string $targetPlugin = null;

    public function validate(string $path, array $options = []): array
    {
        $this->strictMode = $options['strict'] ?? false;
        $this->targetPlugin = $options['plugin'] ?? null;

        if (!is_dir($path)) {
            throw new InvalidArgumentException("Directory does not exist: {$path}");
        }

        echo "FilamentPHP v4 Migration Completeness Validation\n";
        echo "Path: {$path}\n";
        echo "Strict Mode: " . ($this->strictMode ? 'Yes' : 'No') . "\n";
        echo "Target Plugin: " . ($this->targetPlugin ?? 'All') . "\n\n";

        $this->scanDirectory($path);
        return $this->generateReport();
    }

    private function scanDirectory(string $directory): void
    {
        if ($this->targetPlugin) {
            // Scan specific plugin
            $pluginPath = $directory . '/plugins/webkul/' . $this->targetPlugin;
            if (is_dir($pluginPath)) {
                $this->scanPluginDirectory($pluginPath, $this->targetPlugin);
            } else {
                throw new InvalidArgumentException("Plugin directory does not exist: {$pluginPath}");
            }
        } else {
            // Scan all plugins
            $pluginsPath = $directory . '/plugins/webkul';
            if (is_dir($pluginsPath)) {
                $plugins = array_diff(scandir($pluginsPath), ['.', '..']);
                foreach ($plugins as $plugin) {
                    $pluginPath = $pluginsPath . '/' . $plugin;
                    if (is_dir($pluginPath)) {
                        $this->scanPluginDirectory($pluginPath, $plugin);
                    }
                }
            }
        }
    }

    private function scanPluginDirectory(string $pluginPath, string $pluginName): void
    {
        echo "Validating plugin: {$pluginName}\n";
        $this->stats['plugins_processed']++;

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($pluginPath, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $pluginStats = [
            'files' => 0,
            'errors' => 0,
            'warnings' => 0,
            'complete' => true
        ];

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $result = $this->validateFile($file->getPathname(), $pluginName);
                $pluginStats['files']++;
                $pluginStats['errors'] += $result['errors'];
                $pluginStats['warnings'] += $result['warnings'];

                if ($result['errors'] > 0) {
                    $pluginStats['complete'] = false;
                }
            }
        }

        if ($pluginStats['complete']) {
            $this->stats['migration_complete']++;
            echo "  ✅ Migration complete ({$pluginStats['files']} files)\n";
        } else {
            $this->stats['migration_incomplete']++;
            echo "  ❌ Migration incomplete ({$pluginStats['errors']} errors, {$pluginStats['warnings']} warnings)\n";
        }

        echo "\n";
    }

    private function validateFile(string $filePath, string $pluginName): array
    {
        $this->stats['files_processed']++;
        $result = ['errors' => 0, 'warnings' => 0, 'issues' => []];

        try {
            $content = file_get_contents($filePath);

            // Skip non-Filament files
            if (!$this->isFilamentFile($content)) {
                return $result;
            }

            // Run validation rules
            foreach ($this->validationRules as $ruleName => $rule) {
                $issues = $this->runValidationRule($content, $filePath, $ruleName, $rule);

                foreach ($issues as $issue) {
                    $result['issues'][] = $issue;

                    if ($rule['severity'] === 'error') {
                        $result['errors']++;
                        $this->stats['validation_errors']++;
                    } else {
                        $result['warnings']++;
                        $this->stats['validation_warnings']++;
                    }
                }
            }

            // Report issues if found
            if (!empty($result['issues'])) {
                $relativePath = str_replace(getcwd() . '/', '', $filePath);
                echo "  Issues in {$relativePath}:\n";

                foreach ($result['issues'] as $issue) {
                    $icon = $issue['severity'] === 'error' ? '❌' : '⚠️';
                    echo "    {$icon} {$issue['description']}\n";

                    if (isset($issue['line'])) {
                        echo "       Line {$issue['line']}: {$issue['context']}\n";
                    }
                }
                echo "\n";
            }

        } catch (Exception $e) {
            $this->stats['errors'][] = "Error validating {$filePath}: " . $e->getMessage();
            $result['errors']++;
        }

        return $result;
    }

    private function isFilamentFile(string $content): bool
    {
        return strpos($content, 'Filament\\') !== false ||
               strpos($content, 'function form(') !== false ||
               strpos($content, 'function infolist(') !== false ||
               strpos($content, 'extends Resource') !== false;
    }

    private function runValidationRule(string $content, string $filePath, string $ruleName, array $rule): array
    {
        $issues = [];

        if (isset($rule['patterns'])) {
            foreach ($rule['patterns'] as $pattern) {
                if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                    foreach ($matches[0] as $match) {
                        $line = substr_count(substr($content, 0, $match[1]), "\n") + 1;
                        $issues[] = [
                            'severity' => $rule['severity'],
                            'description' => $rule['description'],
                            'line' => $line,
                            'context' => trim($match[0])
                        ];
                    }
                }
            }
        }

        if (isset($rule['check'])) {
            $checkMethod = $rule['check'];
            if (method_exists($this, $checkMethod)) {
                $checkIssues = $this->$checkMethod($content, $filePath);
                foreach ($checkIssues as $issue) {
                    $issue['severity'] = $rule['severity'];
                    $issue['description'] = $rule['description'];
                    $issues[] = $issue;
                }
            }
        }

        return $issues;
    }

    private function checkMissingV4Imports(string $content, string $filePath): array
    {
        $issues = [];

        // Check for form method without proper v4 imports
        if (preg_match('/function\s+form\s*\(/', $content)) {
            if (!preg_match($this->requiredV4Patterns['form_method']['signature'], $content)) {
                $issues[] = ['description' => 'Form method found but missing v4 signature'];
            }

            if (strpos($content, $this->requiredV4Patterns['form_method']['import']) === false) {
                $issues[] = ['description' => 'Form method found but missing Form import'];
            }
        }

        // Check for infolist method without proper v4 imports
        if (preg_match('/function\s+infolist\s*\(/', $content)) {
            if (!preg_match($this->requiredV4Patterns['infolist_method']['signature'], $content)) {
                $issues[] = ['description' => 'Infolist method found but missing v4 signature'];
            }

            if (strpos($content, $this->requiredV4Patterns['infolist_method']['import']) === false) {
                $issues[] = ['description' => 'Infolist method found but missing Infolist import'];
            }
        }

        return $issues;
    }

    private function checkInconsistentComponentUsage(string $content, string $filePath): array
    {
        $issues = [];

        // Check for mixed component namespaces
        $hasFormsComponents = strpos($content, 'use Filament\Forms\Components\\') !== false;
        $hasInfolistsComponents = strpos($content, 'use Filament\Infolists\Components\\') !== false;
        $hasSchemasComponents = strpos($content, 'use Filament\Schemas\Components\\') !== false;

        if ($hasSchemasComponents && ($hasFormsComponents || $hasInfolistsComponents)) {
            $issues[] = ['description' => 'Mixed v3 and v4 component imports found'];
        }

        return $issues;
    }

    private function checkMixedPatterns(string $content, string $filePath): array
    {
        $issues = [];

        $hasV3Patterns = preg_match('/\$schema->components\s*\(/', $content) ||
                        strpos($content, 'use Filament\Schemas\\') !== false;

        $hasV4Patterns = preg_match('/\$form->schema\s*\(/', $content) ||
                        preg_match('/\$infolist->schema\s*\(/', $content) ||
                        strpos($content, 'use Filament\Forms\Form;') !== false ||
                        strpos($content, 'use Filament\Infolists\Infolist;') !== false;

        if ($hasV3Patterns && $hasV4Patterns) {
            $issues[] = ['description' => 'File contains both v3 and v4 patterns'];
        }

        return $issues;
    }

    private function generateReport(): array
    {
        echo "\n=== Migration Completeness Validation Report ===\n";
        echo "Files processed: {$this->stats['files_processed']}\n";
        echo "Plugins processed: {$this->stats['plugins_processed']}\n";
        echo "Migration complete: {$this->stats['migration_complete']}\n";
        echo "Migration incomplete: {$this->stats['migration_incomplete']}\n";
        echo "Validation errors: {$this->stats['validation_errors']}\n";
        echo "Validation warnings: {$this->stats['validation_warnings']}\n";

        $completionRate = $this->stats['plugins_processed'] > 0
            ? round(($this->stats['migration_complete'] / $this->stats['plugins_processed']) * 100, 2)
            : 0;

        echo "Completion rate: {$completionRate}%\n";

        if ($this->stats['validation_errors'] === 0) {
            echo "\n✅ All validations passed! Migration appears complete.\n";
        } else {
            echo "\n❌ Validation errors found. Migration is incomplete.\n";
        }

        if (!empty($this->stats['errors'])) {
            echo "\nProcessing errors:\n";
            foreach ($this->stats['errors'] as $error) {
                echo "- {$error}\n";
            }
        }

        return $this->stats;
    }
}

// CLI execution
if (php_sapi_name() === 'cli') {
    $path = $argv[1] ?? getcwd();
    $options = [];

    // Parse command line options
    $argCount = count($argv);
    for ($i = 2; $i < $argCount; $i++) {
        if (strpos($argv[$i], '--plugin=') === 0) {
            $options['plugin'] = substr($argv[$i], 9);
        } elseif ($argv[$i] === '--strict') {
            $options['strict'] = true;
        }
    }

    try {
        $validator = new FilamentMigrationCompletenessValidator();
        $result = $validator->validate($path, $options);

        // Exit with error code if validation failed
        exit($result['validation_errors'] > 0 ? 1 : 0);

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}
