<?php

/**
 * FilamentPHP v4 Migration Completeness Validation Script
 *
 * This script validates that the migration from FilamentPHP v3 Schema patterns
 * to FilamentPHP v4 Form and Infolist patterns has been completed properly.
 */

class MigrationCompletenessValidator
{
    private array $oldPatterns = [
        // Import patterns that should be migrated
        'imports' => [
            'use Filament\Schemas\Schema;',
            'use Filament\Schemas\Components\Section;',
            'use Filament\Schemas\Components\Fieldset;',
            'use Filament\Schemas\Components\Grid;',
            'use Filament\Schemas\Components\Group;',
            'use Filament\Schemas\Components\Tabs;',
            'use Filament\Schemas\Components\Tabs\Tab;',
            'use Filament\Schemas\Components\TextInput;',
            'use Filament\Schemas\Components\Textarea;',
            'use Filament\Schemas\Components\Select;',
            'use Filament\Schemas\Components\Toggle;',
            'use Filament\Schemas\Components\Checkbox;',
            'use Filament\Schemas\Components\DatePicker;',
            'use Filament\Schemas\Components\DateTimePicker;',
            'use Filament\Schemas\Components\FileUpload;',
            'use Filament\Schemas\Components\RichEditor;',
            'use Filament\Schemas\Components\Repeater;',
            'use Filament\Schemas\Components\Hidden;',
            'use Filament\Schemas\Components\Placeholder;',
            'use Filament\Schemas\Components\TextEntry;',
            'use Filament\Schemas\Components\IconEntry;',
            'use Filament\Schemas\Components\ImageEntry;',
            'use Filament\Schemas\Components\ColorEntry;',
            'use Filament\Schemas\Components\Utilities\Get;',
            'use Filament\Schemas\Components\Utilities\Set;',
        ],

        // Method signature patterns that should be migrated
        'method_signatures' => [
            '/public\s+static\s+function\s+form\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i',
            '/public\s+static\s+function\s+infolist\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i',
        ],

        // Method call patterns that should be migrated
        'method_calls' => [
            '/\$schema\s*->\s*components\s*\(/i',
            '/return\s+\$schema\s*;/i',
        ],

        // Namespace patterns that should be migrated
        'namespaces' => [
            'Filament\\Schemas\\Components\\',
            'Filament\\Schemas\\Schema',
        ],
    ];

    private array $expectedPatterns = [
        // New patterns that should be present after migration
        'form_imports' => [
            'use Filament\Forms\Form;',
            'use Filament\Forms\Components\Section;',
            'use Filament\Forms\Components\TextInput;',
            'use Filament\Forms\Get;',
            'use Filament\Forms\Set;',
        ],

        'infolist_imports' => [
            'use Filament\Infolists\Infolist;',
            'use Filament\Infolists\Components\Section;',
            'use Filament\Infolists\Components\TextEntry;',
        ],

        'method_signatures' => [
            '/public\s+static\s+function\s+form\s*\(\s*Form\s+\$form\s*\)\s*:\s*Form/i',
            '/public\s+static\s+function\s+infolist\s*\(\s*Infolist\s+\$infolist\s*\)\s*:\s*Infolist/i',
        ],

        'method_calls' => [
            '/\$form\s*->\s*schema\s*\(/i',
            '/\$infolist\s*->\s*schema\s*\(/i',
            '/return\s+\$form\s*;/i',
            '/return\s+\$infolist\s*;/i',
        ],
    ];

    public function validateFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [
                'valid' => false,
                'error' => "File not found: $filePath",
                'file' => $filePath
            ];
        }

        $content = file_get_contents($filePath);
        $issues = [];
        $warnings = [];
        $info = [];

        // Skip files that don't contain Filament components
        if (strpos($content, 'Filament\\') === false) {
            return [
                'valid' => true,
                'issues' => [],
                'warnings' => [],
                'info' => ['File does not contain Filament components'],
                'file' => $filePath
            ];
        }

        // Check for remaining old patterns
        $this->checkOldPatterns($content, $issues);

        // Check for proper new patterns
        $this->checkNewPatterns($content, $warnings, $info);

        // Check for consistency issues
        $this->checkConsistency($content, $warnings);

        // Check for mixed patterns
        $this->checkMixedPatterns($content, $warnings);

        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'warnings' => $warnings,
            'info' => $info,
            'file' => $filePath
        ];
    }

    private function checkOldPatterns(string $content, array &$issues): void
    {
        // Check for old import statements
        foreach ($this->oldPatterns['imports'] as $pattern) {
            if (strpos($content, $pattern) !== false) {
                $issues[] = "Found old import statement: $pattern";
            }
        }

        // Check for old method signatures
        foreach ($this->oldPatterns['method_signatures'] as $pattern) {
            if (preg_match($pattern, $content)) {
                $issues[] = "Found old method signature pattern: $pattern";
            }
        }

        // Check for old method calls
        foreach ($this->oldPatterns['method_calls'] as $pattern) {
            if (preg_match($pattern, $content)) {
                $issues[] = "Found old method call pattern: $pattern";
            }
        }

        // Check for old namespace usage
        foreach ($this->oldPatterns['namespaces'] as $pattern) {
            if (strpos($content, $pattern) !== false) {
                $issues[] = "Found old namespace pattern: $pattern";
            }
        }
    }

    private function checkNewPatterns(string $content, array &$warnings, array &$info): void
    {
        $hasFormMethod = strpos($content, 'function form(') !== false;
        $hasInfolistMethod = strpos($content, 'function infolist(') !== false;

        if ($hasFormMethod) {
            $hasFormImport = strpos($content, 'use Filament\Forms\Form;') !== false;
            if (!$hasFormImport) {
                $warnings[] = 'File has form method but missing Form import';
            } else {
                $info[] = 'Form method properly uses Form import';
            }

            $hasFormSignature = preg_match($this->expectedPatterns['method_signatures'][0], $content);
            if (!$hasFormSignature) {
                $warnings[] = 'Form method may not have correct v4 signature';
            } else {
                $info[] = 'Form method has correct v4 signature';
            }
        }

        if ($hasInfolistMethod) {
            $hasInfolistImport = strpos($content, 'use Filament\Infolists\Infolist;') !== false;
            if (!$hasInfolistImport) {
                $warnings[] = 'File has infolist method but missing Infolist import';
            } else {
                $info[] = 'Infolist method properly uses Infolist import';
            }

            $hasInfolistSignature = preg_match($this->expectedPatterns['method_signatures'][1], $content);
            if (!$hasInfolistSignature) {
                $warnings[] = 'Infolist method may not have correct v4 signature';
            } else {
                $info[] = 'Infolist method has correct v4 signature';
            }
        }
    }

    private function checkConsistency(string $content, array &$warnings): void
    {
        // Check for consistent component namespace usage
        $hasFormComponents = strpos($content, 'Filament\\Forms\\Components\\') !== false;
        $hasInfolistComponents = strpos($content, 'Filament\\Infolists\\Components\\') !== false;
        $hasFormMethod = strpos($content, 'function form(') !== false;
        $hasInfolistMethod = strpos($content, 'function infolist(') !== false;

        if ($hasFormComponents && !$hasFormMethod) {
            $warnings[] = 'File uses Form components but has no form method';
        }

        if ($hasInfolistComponents && !$hasInfolistMethod) {
            $warnings[] = 'File uses Infolist components but has no infolist method';
        }

        if ($hasFormMethod && $hasInfolistComponents && !$hasInfolistMethod) {
            $warnings[] = 'File has form method but uses infolist components without infolist method';
        }

        if ($hasInfolistMethod && $hasFormComponents && !$hasFormMethod) {
            $warnings[] = 'File has infolist method but uses form components without form method';
        }
    }

    private function checkMixedPatterns(string $content, array &$warnings): void
    {
        $hasOldImports = false;
        $hasNewImports = false;

        // Check for mixed import patterns
        foreach ($this->oldPatterns['imports'] as $pattern) {
            if (strpos($content, $pattern) !== false) {
                $hasOldImports = true;
                break;
            }
        }

        if (strpos($content, 'use Filament\\Forms\\') !== false ||
            strpos($content, 'use Filament\\Infolists\\') !== false) {
            $hasNewImports = true;
        }

        if ($hasOldImports && $hasNewImports) {
            $warnings[] = 'File contains mixed old and new import patterns';
        }
    }

    public function validateDirectory(string $directory): array
    {
        $results = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $filePath = $file->getPathname();
                $result = $this->validateFile($filePath);
                $results[] = $result;
            }
        }

        return $results;
    }

    public function generateSummaryReport(array $results): array
    {
        $summary = [
            'total_files' => count($results),
            'valid_files' => 0,
            'files_with_issues' => 0,
            'files_with_warnings' => 0,
            'total_issues' => 0,
            'total_warnings' => 0,
            'issue_types' => [],
            'warning_types' => [],
        ];

        foreach ($results as $result) {
            if ($result['valid']) {
                $summary['valid_files']++;
            } else {
                $summary['files_with_issues']++;
            }

            if (!empty($result['warnings'])) {
                $summary['files_with_warnings']++;
            }

            $summary['total_issues'] += count($result['issues']);
            $summary['total_warnings'] += count($result['warnings']);

            // Categorize issues and warnings
            foreach ($result['issues'] as $issue) {
                $type = $this->categorizeIssue($issue);
                $summary['issue_types'][$type] = ($summary['issue_types'][$type] ?? 0) + 1;
            }

            foreach ($result['warnings'] as $warning) {
                $type = $this->categorizeWarning($warning);
                $summary['warning_types'][$type] = ($summary['warning_types'][$type] ?? 0) + 1;
            }
        }

        return $summary;
    }

    private function categorizeIssue(string $issue): string
    {
        if (strpos($issue, 'import statement') !== false) {
            return 'Import Issues';
        } elseif (strpos($issue, 'method signature') !== false) {
            return 'Method Signature Issues';
        } elseif (strpos($issue, 'method call') !== false) {
            return 'Method Call Issues';
        } elseif (strpos($issue, 'namespace') !== false) {
            return 'Namespace Issues';
        } else {
            return 'Other Issues';
        }
    }

    private function categorizeWarning(string $warning): string
    {
        if (strpos($warning, 'import') !== false) {
            return 'Import Warnings';
        } elseif (strpos($warning, 'signature') !== false) {
            return 'Signature Warnings';
        } elseif (strpos($warning, 'component') !== false) {
            return 'Component Warnings';
        } elseif (strpos($warning, 'mixed') !== false) {
            return 'Mixed Pattern Warnings';
        } else {
            return 'Other Warnings';
        }
    }

    public function generateDetailedReport(array $results): string
    {
        $summary = $this->generateSummaryReport($results);

        $report = "# Migration Completeness Validation Report\n\n";
        $report .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";

        // Summary section
        $report .= "## Summary\n\n";
        $report .= "- **Total files validated:** {$summary['total_files']}\n";
        $report .= "- **Valid files:** {$summary['valid_files']}\n";
        $report .= "- **Files with issues:** {$summary['files_with_issues']}\n";
        $report .= "- **Files with warnings:** {$summary['files_with_warnings']}\n";
        $report .= "- **Total issues:** {$summary['total_issues']}\n";
        $report .= "- **Total warnings:** {$summary['total_warnings']}\n\n";

        // Migration completeness percentage
        $completeness = $summary['total_files'] > 0 ?
            round(($summary['valid_files'] / $summary['total_files']) * 100, 2) : 100;
        $report .= "**Migration Completeness:** {$completeness}%\n\n";

        // Issue breakdown
        if (!empty($summary['issue_types'])) {
            $report .= "## Issue Breakdown\n\n";
            foreach ($summary['issue_types'] as $type => $count) {
                $report .= "- **{$type}:** {$count}\n";
            }
            $report .= "\n";
        }

        // Warning breakdown
        if (!empty($summary['warning_types'])) {
            $report .= "## Warning Breakdown\n\n";
            foreach ($summary['warning_types'] as $type => $count) {
                $report .= "- **{$type}:** {$count}\n";
            }
            $report .= "\n";
        }

        // Files with issues
        $filesWithIssues = array_filter($results, fn($result) => !$result['valid']);
        if (!empty($filesWithIssues)) {
            $report .= "## Files with Issues\n\n";
            foreach ($filesWithIssues as $result) {
                $report .= "### " . basename($result['file']) . "\n";
                $report .= "Path: `{$result['file']}`\n\n";
                $report .= "**Issues:**\n";
                foreach ($result['issues'] as $issue) {
                    $report .= "- $issue\n";
                }
                if (!empty($result['warnings'])) {
                    $report .= "\n**Warnings:**\n";
                    foreach ($result['warnings'] as $warning) {
                        $report .= "- $warning\n";
                    }
                }
                $report .= "\n";
            }
        }

        // Files with warnings only
        $filesWithWarnings = array_filter($results, fn($result) => $result['valid'] && !empty($result['warnings']));
        if (!empty($filesWithWarnings)) {
            $report .= "## Files with Warnings Only\n\n";
            foreach ($filesWithWarnings as $result) {
                $report .= "### " . basename($result['file']) . "\n";
                $report .= "Path: `{$result['file']}`\n\n";
                $report .= "**Warnings:**\n";
                foreach ($result['warnings'] as $warning) {
                    $report .= "- $warning\n";
                }
                $report .= "\n";
            }
        }

        return $report;
    }
}

// CLI usage
if (php_sapi_name() === 'cli') {
    $validator = new MigrationCompletenessValidator();

    if ($argc < 2) {
        echo "Usage: php migration-completeness-validator.php <directory_path> [output_report_path]\n";
        echo "Example: php migration-completeness-validator.php plugins/webkul/accounts validation-report.md\n";
        exit(1);
    }

    $directory = $argv[1];
    $reportPath = $argv[2] ?? 'migration-completeness-report.md';

    if (!is_dir($directory)) {
        echo "Error: Directory not found: $directory\n";
        exit(1);
    }

    echo "Starting migration completeness validation for directory: $directory\n";
    $results = $validator->validateDirectory($directory);

    $report = $validator->generateDetailedReport($results);
    file_put_contents($reportPath, $report);

    $summary = $validator->generateSummaryReport($results);

    echo "Validation completed!\n";
    echo "Files validated: {$summary['total_files']}\n";
    echo "Valid files: {$summary['valid_files']}\n";
    echo "Files with issues: {$summary['files_with_issues']}\n";
    echo "Migration completeness: " . round(($summary['valid_files'] / $summary['total_files']) * 100, 2) . "%\n";
    echo "Report saved to: $reportPath\n";
}
