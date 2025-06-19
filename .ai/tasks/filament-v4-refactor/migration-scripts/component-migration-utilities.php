<?php

/**
 * FilamentPHP v4 Component Migration Utilities
 *
 * This script provides utilities for complex component migrations that require more than simple find-and-replace.
 *
 * Usage: php component-migration-utilities.php [command] [path-to-scan]
 *
 * Commands:
 * - validate: Validate component usage patterns
 * - migrate: Perform complex component migrations
 * - report: Generate migration report
 *
 * Features:
 * 1. Component usage pattern validation
 * 2. Complex component migration logic
 * 3. Migration completeness reporting
 * 4. Edge case handling
 */

class FilamentComponentMigrationUtilities
{
    private array $stats = [
        'files_processed' => 0,
        'files_updated' => 0,
        'components_migrated' => 0,
        'validation_issues' => 0,
        'errors' => []
    ];

    private array $componentMigrationRules = [
        // Form component specific migrations
        'form' => [
            'Get' => [
                'old_import' => 'use Filament\Schemas\Get;',
                'new_import' => 'use Filament\Forms\Get;',
                'usage_pattern' => '/Get::/',
            ],
            'Set' => [
                'old_import' => 'use Filament\Schemas\Set;',
                'new_import' => 'use Filament\Forms\Set;',
                'usage_pattern' => '/Set::/',
            ],
        ],

        // Infolist component specific migrations
        'infolist' => [
            'TextEntry' => [
                'old_import' => 'use Filament\Schemas\Components\TextEntry;',
                'new_import' => 'use Filament\Infolists\Components\TextEntry;',
                'usage_pattern' => '/TextEntry::/',
            ],
            'IconEntry' => [
                'old_import' => 'use Filament\Schemas\Components\IconEntry;',
                'new_import' => 'use Filament\Infolists\Components\IconEntry;',
                'usage_pattern' => '/IconEntry::/',
            ],
        ],
    ];

    private array $validationRules = [
        'mixed_schema_usage' => [
            'description' => 'Files using both form and infolist patterns with Schema',
            'pattern' => '/use Filament\\Schemas\\Schema;.*function (form|infolist)/s',
        ],
        'missing_imports' => [
            'description' => 'Component usage without proper imports',
            'check' => 'checkMissingImports',
        ],
        'deprecated_patterns' => [
            'description' => 'Usage of deprecated v3 patterns',
            'patterns' => [
                '/\$schema->components\s*\(/',
                '/Schema\s+\$schema/',
            ],
        ],
    ];

    public function run(string $command, string $path): void
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("Directory does not exist: {$path}");
        }

        switch ($command) {
            case 'validate':
                $this->validateDirectory($path);
                break;
            case 'migrate':
                $this->migrateDirectory($path);
                break;
            case 'report':
                $this->generateReport($path);
                break;
            default:
                throw new InvalidArgumentException("Unknown command: {$command}");
        }

        $this->printStats();
    }

    private function validateDirectory(string $path): void
    {
        echo "Validating component usage patterns...\n\n";
        $this->scanDirectory($path, 'validate');
    }

    private function migrateDirectory(string $path): void
    {
        echo "Performing complex component migrations...\n\n";
        $this->scanDirectory($path, 'migrate');
    }

    private function generateReport(string $path): void
    {
        echo "Generating migration report...\n\n";
        $this->scanDirectory($path, 'report');
    }

    private function scanDirectory(string $directory, string $mode): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $this->processFile($file->getPathname(), $mode);
            }
        }
    }

    private function processFile(string $filePath, string $mode): void
    {
        $this->stats['files_processed']++;

        try {
            $content = file_get_contents($filePath);
            $originalContent = $content;

            switch ($mode) {
                case 'validate':
                    $this->validateFile($filePath, $content);
                    break;
                case 'migrate':
                    $content = $this->migrateFile($filePath, $content);
                    break;
                case 'report':
                    $this->reportFile($filePath, $content);
                    break;
            }

            // Only write if content changed and in migrate mode
            if ($mode === 'migrate' && $content !== $originalContent) {
                file_put_contents($filePath, $content);
                $this->stats['files_updated']++;
                echo "Updated: {$filePath}\n";
            }

        } catch (Exception $e) {
            $this->stats['errors'][] = "Error processing {$filePath}: " . $e->getMessage();
            echo "Error: {$filePath} - " . $e->getMessage() . "\n";
        }
    }

    private function validateFile(string $filePath, string $content): void
    {
        $issues = [];

        // Check for mixed schema usage
        if (preg_match($this->validationRules['mixed_schema_usage']['pattern'], $content)) {
            $issues[] = $this->validationRules['mixed_schema_usage']['description'];
            $this->stats['validation_issues']++;
        }

        // Check for deprecated patterns
        foreach ($this->validationRules['deprecated_patterns']['patterns'] as $pattern) {
            if (preg_match($pattern, $content)) {
                $issues[] = "Deprecated pattern found: {$pattern}";
                $this->stats['validation_issues']++;
            }
        }

        // Check for missing imports
        $missingImports = $this->checkMissingImports($content);
        if (!empty($missingImports)) {
            $issues = array_merge($issues, $missingImports);
            $this->stats['validation_issues'] += count($missingImports);
        }

        if (!empty($issues)) {
            echo "Issues in {$filePath}:\n";
            foreach ($issues as $issue) {
                echo "  - {$issue}\n";
            }
            echo "\n";
        }
    }

    private function migrateFile(string $filePath, string $content): string
    {
        // Determine context (form vs infolist)
        $hasFormMethod = strpos($content, 'function form(') !== false;
        $hasInfolistMethod = strpos($content, 'function infolist(') !== false;

        // Apply context-specific migrations
        if ($hasFormMethod) {
            $content = $this->applyFormComponentMigrations($content);
        }

        if ($hasInfolistMethod) {
            $content = $this->applyInfolistComponentMigrations($content);
        }

        // Apply general component migrations
        $content = $this->applyGeneralComponentMigrations($content);

        return $content;
    }

    private function applyFormComponentMigrations(string $content): string
    {
        foreach ($this->componentMigrationRules['form'] as $component => $rule) {
            if (strpos($content, $rule['old_import']) !== false) {
                $content = str_replace($rule['old_import'], $rule['new_import'], $content);
                $this->stats['components_migrated']++;
            }
        }
        return $content;
    }

    private function applyInfolistComponentMigrations(string $content): string
    {
        foreach ($this->componentMigrationRules['infolist'] as $component => $rule) {
            if (strpos($content, $rule['old_import']) !== false) {
                $content = str_replace($rule['old_import'], $rule['new_import'], $content);
                $this->stats['components_migrated']++;
            }
        }
        return $content;
    }

    private function applyGeneralComponentMigrations(string $content): string
    {
        // Handle edge cases and complex migrations

        // Fix component method chaining patterns
        $content = preg_replace(
            '/(\w+)::make\(\s*([^)]*)\s*\)\s*->(\w+)\s*\(/m',
            '$1::make($2)->$3(',
            $content
        );

        // Ensure proper spacing in component configurations
        $content = preg_replace('/->(\w+)\(/', '->$1(', $content);

        return $content;
    }

    private function checkMissingImports(string $content): array
    {
        $issues = [];

        // Check for component usage without imports
        $componentPatterns = [
            'TextInput::' => 'use Filament\Forms\Components\TextInput;',
            'Select::' => 'use Filament\Forms\Components\Select;',
            'Toggle::' => 'use Filament\Forms\Components\Toggle;',
            'TextEntry::' => 'use Filament\Infolists\Components\TextEntry;',
            'IconEntry::' => 'use Filament\Infolists\Components\IconEntry;',
        ];

        foreach ($componentPatterns as $usage => $requiredImport) {
            if (strpos($content, $usage) !== false && strpos($content, $requiredImport) === false) {
                $issues[] = "Missing import: {$requiredImport} (found usage: {$usage})";
            }
        }

        return $issues;
    }

    private function reportFile(string $filePath, string $content): void
    {
        $report = [
            'file' => $filePath,
            'has_form_method' => strpos($content, 'function form(') !== false,
            'has_infolist_method' => strpos($content, 'function infolist(') !== false,
            'schema_imports' => substr_count($content, 'use Filament\Schemas\\'),
            'form_imports' => substr_count($content, 'use Filament\Forms\\'),
            'infolist_imports' => substr_count($content, 'use Filament\Infolists\\'),
            'deprecated_patterns' => 0,
        ];

        // Count deprecated patterns
        foreach ($this->validationRules['deprecated_patterns']['patterns'] as $pattern) {
            $report['deprecated_patterns'] += preg_match_all($pattern, $content);
        }

        if ($report['schema_imports'] > 0 || $report['deprecated_patterns'] > 0) {
            echo "File: {$filePath}\n";
            echo "  Form method: " . ($report['has_form_method'] ? 'Yes' : 'No') . "\n";
            echo "  Infolist method: " . ($report['has_infolist_method'] ? 'Yes' : 'No') . "\n";
            echo "  Schema imports: {$report['schema_imports']}\n";
            echo "  Form imports: {$report['form_imports']}\n";
            echo "  Infolist imports: {$report['infolist_imports']}\n";
            echo "  Deprecated patterns: {$report['deprecated_patterns']}\n";
            echo "\n";
        }
    }

    private function printStats(): void
    {
        echo "\n=== Component Migration Utilities Statistics ===\n";
        echo "Files processed: {$this->stats['files_processed']}\n";
        echo "Files updated: {$this->stats['files_updated']}\n";
        echo "Components migrated: {$this->stats['components_migrated']}\n";
        echo "Validation issues: {$this->stats['validation_issues']}\n";

        if (!empty($this->stats['errors'])) {
            echo "\nErrors encountered:\n";
            foreach ($this->stats['errors'] as $error) {
                echo "- {$error}\n";
            }
        }

        echo "\nComponent migration utilities complete!\n";
    }
}

// CLI execution
if (php_sapi_name() === 'cli') {
    $command = $argv[1] ?? 'validate';
    $path = $argv[2] ?? getcwd();

    echo "FilamentPHP v4 Component Migration Utilities\n";
    echo "Command: {$command}\n";
    echo "Scanning directory: {$path}\n\n";

    try {
        $utilities = new FilamentComponentMigrationUtilities();
        $utilities->run($command, $path);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}
