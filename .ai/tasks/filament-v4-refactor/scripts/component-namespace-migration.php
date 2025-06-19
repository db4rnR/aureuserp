<?php

/**
 * FilamentPHP v4 Component Namespace Migration Script
 *
 * This script handles component namespace migrations and usage patterns within code,
 * ensuring proper component references and namespace usage throughout the codebase.
 */

class ComponentNamespaceMigrationScript
{
    private array $componentMappings = [
        // Layout components
        'Section' => [
            'form' => 'Filament\\Forms\\Components\\Section',
            'infolist' => 'Filament\\Infolists\\Components\\Section',
            'old' => 'Filament\\Schemas\\Components\\Section'
        ],
        'Grid' => [
            'form' => 'Filament\\Forms\\Components\\Grid',
            'infolist' => 'Filament\\Infolists\\Components\\Grid',
            'old' => 'Filament\\Schemas\\Components\\Grid'
        ],
        'Group' => [
            'form' => 'Filament\\Forms\\Components\\Group',
            'infolist' => 'Filament\\Infolists\\Components\\Group',
            'old' => 'Filament\\Schemas\\Components\\Group'
        ],
        'Fieldset' => [
            'form' => 'Filament\\Forms\\Components\\Fieldset',
            'infolist' => 'Filament\\Infolists\\Components\\Fieldset',
            'old' => 'Filament\\Schemas\\Components\\Fieldset'
        ],
        'Tabs' => [
            'form' => 'Filament\\Forms\\Components\\Tabs',
            'infolist' => 'Filament\\Infolists\\Components\\Tabs',
            'old' => 'Filament\\Schemas\\Components\\Tabs'
        ],
        'Tab' => [
            'form' => 'Filament\\Forms\\Components\\Tabs\\Tab',
            'infolist' => 'Filament\\Infolists\\Components\\Tabs\\Tab',
            'old' => 'Filament\\Schemas\\Components\\Tabs\\Tab'
        ],

        // Form-specific components
        'TextInput' => [
            'form' => 'Filament\\Forms\\Components\\TextInput',
            'old' => 'Filament\\Schemas\\Components\\TextInput'
        ],
        'Textarea' => [
            'form' => 'Filament\\Forms\\Components\\Textarea',
            'old' => 'Filament\\Schemas\\Components\\Textarea'
        ],
        'Select' => [
            'form' => 'Filament\\Forms\\Components\\Select',
            'old' => 'Filament\\Schemas\\Components\\Select'
        ],
        'Toggle' => [
            'form' => 'Filament\\Forms\\Components\\Toggle',
            'old' => 'Filament\\Schemas\\Components\\Toggle'
        ],
        'Checkbox' => [
            'form' => 'Filament\\Forms\\Components\\Checkbox',
            'old' => 'Filament\\Schemas\\Components\\Checkbox'
        ],
        'DatePicker' => [
            'form' => 'Filament\\Forms\\Components\\DatePicker',
            'old' => 'Filament\\Schemas\\Components\\DatePicker'
        ],
        'DateTimePicker' => [
            'form' => 'Filament\\Forms\\Components\\DateTimePicker',
            'old' => 'Filament\\Schemas\\Components\\DateTimePicker'
        ],
        'FileUpload' => [
            'form' => 'Filament\\Forms\\Components\\FileUpload',
            'old' => 'Filament\\Schemas\\Components\\FileUpload'
        ],
        'RichEditor' => [
            'form' => 'Filament\\Forms\\Components\\RichEditor',
            'old' => 'Filament\\Schemas\\Components\\RichEditor'
        ],
        'Repeater' => [
            'form' => 'Filament\\Forms\\Components\\Repeater',
            'old' => 'Filament\\Schemas\\Components\\Repeater'
        ],
        'Hidden' => [
            'form' => 'Filament\\Forms\\Components\\Hidden',
            'old' => 'Filament\\Schemas\\Components\\Hidden'
        ],
        'Placeholder' => [
            'form' => 'Filament\\Forms\\Components\\Placeholder',
            'old' => 'Filament\\Schemas\\Components\\Placeholder'
        ],

        // Infolist-specific components
        'TextEntry' => [
            'infolist' => 'Filament\\Infolists\\Components\\TextEntry',
            'old' => 'Filament\\Schemas\\Components\\TextEntry'
        ],
        'IconEntry' => [
            'infolist' => 'Filament\\Infolists\\Components\\IconEntry',
            'old' => 'Filament\\Schemas\\Components\\IconEntry'
        ],
        'ImageEntry' => [
            'infolist' => 'Filament\\Infolists\\Components\\ImageEntry',
            'old' => 'Filament\\Schemas\\Components\\ImageEntry'
        ],
        'ColorEntry' => [
            'infolist' => 'Filament\\Infolists\\Components\\ColorEntry',
            'old' => 'Filament\\Schemas\\Components\\ColorEntry'
        ],
    ];

    private array $utilityMappings = [
        'Get' => [
            'form' => 'Filament\\Forms\\Get',
            'old' => 'Filament\\Schemas\\Components\\Utilities\\Get'
        ],
        'Set' => [
            'form' => 'Filament\\Forms\\Set',
            'old' => 'Filament\\Schemas\\Components\\Utilities\\Set'
        ],
    ];

    public function migrateFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['success' => false, 'error' => "File not found: $filePath"];
        }

        $content = file_get_contents($filePath);
        $originalContent = $content;
        $changes = [];

        // Skip if file doesn't contain Schema components
        if (strpos($content, 'Filament\\Schemas\\Components') === false) {
            return [
                'success' => true,
                'changes' => [],
                'file' => $filePath,
                'message' => 'No component namespace changes needed'
            ];
        }

        // Determine context (form vs infolist)
        $hasFormMethod = strpos($content, 'function form(') !== false;
        $hasInfolistMethod = strpos($content, 'function infolist(') !== false;

        // Migrate component usage patterns
        $content = $this->migrateComponentUsage($content, $hasFormMethod, $hasInfolistMethod, $changes);

        // Migrate utility usage patterns
        $content = $this->migrateUtilityUsage($content, $changes);

        // Clean up any remaining old namespace references
        $content = $this->cleanupOldNamespaces($content, $changes);

        // Only write if changes were made
        if ($content !== $originalContent) {
            file_put_contents($filePath, $content);
            return [
                'success' => true,
                'changes' => $changes,
                'file' => $filePath
            ];
        }

        return [
            'success' => true,
            'changes' => [],
            'file' => $filePath,
            'message' => 'No component namespace changes needed'
        ];
    }

    private function migrateComponentUsage(string $content, bool $hasFormMethod, bool $hasInfolistMethod, array &$changes): string
    {
        foreach ($this->componentMappings as $componentName => $mappings) {
            $oldNamespace = $mappings['old'];

            // Skip if this component isn't used in the file
            if (strpos($content, $oldNamespace) === false) {
                continue;
            }

            // Determine which namespace to use
            $newNamespace = null;
            if ($hasInfolistMethod && isset($mappings['infolist'])) {
                $newNamespace = $mappings['infolist'];
                $context = 'infolist';
            } elseif ($hasFormMethod && isset($mappings['form'])) {
                $newNamespace = $mappings['form'];
                $context = 'form';
            } elseif (isset($mappings['form'])) {
                // Default to form if available
                $newNamespace = $mappings['form'];
                $context = 'form';
            }

            if ($newNamespace) {
                // Replace full namespace references
                $content = str_replace($oldNamespace, $newNamespace, $content);
                $changes[] = "Updated $componentName component namespace for $context context";

                // Handle static method calls
                $oldStaticPattern = "\\$oldNamespace::";
                $newStaticPattern = "\\$newNamespace::";
                if (strpos($content, $oldStaticPattern) !== false) {
                    $content = str_replace($oldStaticPattern, $newStaticPattern, $content);
                    $changes[] = "Updated $componentName static method calls";
                }
            }
        }

        return $content;
    }

    private function migrateUtilityUsage(string $content, array &$changes): string
    {
        foreach ($this->utilityMappings as $utilityName => $mappings) {
            $oldNamespace = $mappings['old'];
            $newNamespace = $mappings['form']; // Utilities are form-specific

            if (strpos($content, $oldNamespace) !== false) {
                $content = str_replace($oldNamespace, $newNamespace, $content);
                $changes[] = "Updated $utilityName utility namespace";

                // Handle static method calls
                $oldStaticPattern = "\\$oldNamespace::";
                $newStaticPattern = "\\$newNamespace::";
                if (strpos($content, $oldStaticPattern) !== false) {
                    $content = str_replace($oldStaticPattern, $newStaticPattern, $content);
                    $changes[] = "Updated $utilityName static method calls";
                }
            }
        }

        return $content;
    }

    private function cleanupOldNamespaces(string $content, array &$changes): string
    {
        // Remove any remaining old Schema component references
        $oldPatterns = [
            'Filament\\Schemas\\Components\\',
            'Filament\\Schemas\\Schema',
        ];

        foreach ($oldPatterns as $pattern) {
            if (strpos($content, $pattern) !== false) {
                // Log remaining patterns for manual review
                $changes[] = "WARNING: Found remaining old namespace pattern: $pattern - may need manual review";
            }
        }

        return $content;
    }

    public function migrateDirectory(string $directory): array
    {
        $results = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $filePath = $file->getPathname();

                // Skip if file doesn't contain Schema components
                $content = file_get_contents($filePath);
                if (strpos($content, 'Filament\\Schemas\\Components') === false) {
                    continue;
                }

                $result = $this->migrateFile($filePath);
                if (!empty($result['changes'])) {
                    $results[] = $result;
                }
            }
        }

        return $results;
    }

    public function validateComponentNamespaces(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['valid' => false, 'error' => "File not found: $filePath"];
        }

        $content = file_get_contents($filePath);
        $issues = [];

        // Check for remaining old namespace patterns
        if (strpos($content, 'Filament\\Schemas\\Components\\') !== false) {
            $issues[] = 'Found remaining old Schema component namespaces';
        }

        if (strpos($content, 'Filament\\Schemas\\Components\\Utilities\\') !== false) {
            $issues[] = 'Found remaining old Schema utility namespaces';
        }

        // Check for mixed namespace usage (potential issues)
        $hasFormComponents = strpos($content, 'Filament\\Forms\\Components\\') !== false;
        $hasInfolistComponents = strpos($content, 'Filament\\Infolists\\Components\\') !== false;
        $hasOldComponents = strpos($content, 'Filament\\Schemas\\Components\\') !== false;

        if ($hasOldComponents && ($hasFormComponents || $hasInfolistComponents)) {
            $issues[] = 'Found mixed old and new component namespaces - may need manual review';
        }

        // Check for proper context usage
        $hasFormMethod = strpos($content, 'function form(') !== false;
        $hasInfolistMethod = strpos($content, 'function infolist(') !== false;

        if ($hasFormMethod && $hasInfolistComponents && !$hasInfolistMethod) {
            $issues[] = 'Found infolist components in file with only form method - may be incorrect';
        }

        if ($hasInfolistMethod && $hasFormComponents && !$hasFormMethod) {
            $issues[] = 'Found form components in file with only infolist method - may be incorrect';
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'file' => $filePath
        ];
    }

    public function generateReport(array $results): string
    {
        $report = "# Component Namespace Migration Report\n\n";
        $report .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";

        $totalFiles = count($results);
        $report .= "## Summary\n";
        $report .= "- Total files processed: $totalFiles\n\n";

        if ($totalFiles > 0) {
            $report .= "## Files Modified\n\n";
            foreach ($results as $result) {
                $report .= "### " . basename($result['file']) . "\n";
                $report .= "Path: `{$result['file']}`\n\n";
                $report .= "Changes:\n";
                foreach ($result['changes'] as $change) {
                    $report .= "- $change\n";
                }
                $report .= "\n";
            }
        }

        return $report;
    }

    public function generateValidationReport(array $validationResults): string
    {
        $report = "# Component Namespace Validation Report\n\n";
        $report .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";

        $totalFiles = count($validationResults);
        $validFiles = array_filter($validationResults, fn($result) => $result['valid']);
        $invalidFiles = array_filter($validationResults, fn($result) => !$result['valid']);

        $report .= "## Summary\n";
        $report .= "- Total files validated: $totalFiles\n";
        $report .= "- Valid files: " . count($validFiles) . "\n";
        $report .= "- Files with issues: " . count($invalidFiles) . "\n\n";

        if (!empty($invalidFiles)) {
            $report .= "## Files with Issues\n\n";
            foreach ($invalidFiles as $result) {
                $report .= "### " . basename($result['file']) . "\n";
                $report .= "Path: `{$result['file']}`\n\n";
                $report .= "Issues:\n";
                foreach ($result['issues'] as $issue) {
                    $report .= "- $issue\n";
                }
                $report .= "\n";
            }
        }

        return $report;
    }
}

// CLI usage
if (php_sapi_name() === 'cli') {
    $script = new ComponentNamespaceMigrationScript();

    if ($argc < 2) {
        echo "Usage: php component-namespace-migration.php <command> <directory_path> [options]\n";
        echo "Commands:\n";
        echo "  migrate <directory>     - Migrate component namespaces in directory\n";
        echo "  validate <directory>    - Validate component namespaces in directory\n";
        echo "Examples:\n";
        echo "  php component-namespace-migration.php migrate plugins/webkul/accounts\n";
        echo "  php component-namespace-migration.php validate plugins/webkul/accounts\n";
        exit(1);
    }

    $command = $argv[1];
    $directory = $argv[2] ?? '';

    if (!is_dir($directory)) {
        echo "Error: Directory not found: $directory\n";
        exit(1);
    }

    switch ($command) {
        case 'migrate':
            echo "Starting component namespace migration for directory: $directory\n";
            $results = $script->migrateDirectory($directory);

            $report = $script->generateReport($results);
            $reportPath = 'component-namespace-migration-report.md';
            file_put_contents($reportPath, $report);

            echo "Migration completed!\n";
            echo "Files processed: " . count($results) . "\n";
            echo "Report saved to: $reportPath\n";
            break;

        case 'validate':
            echo "Starting component namespace validation for directory: $directory\n";
            $results = [];

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory)
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $results[] = $script->validateComponentNamespaces($file->getPathname());
                }
            }

            $report = $script->generateValidationReport($results);
            $reportPath = 'component-namespace-validation-report.md';
            file_put_contents($reportPath, $report);

            echo "Validation completed!\n";
            echo "Files validated: " . count($results) . "\n";
            echo "Report saved to: $reportPath\n";
            break;

        default:
            echo "Error: Unknown command '$command'\n";
            echo "Use 'migrate' or 'validate'\n";
            exit(1);
    }
}
