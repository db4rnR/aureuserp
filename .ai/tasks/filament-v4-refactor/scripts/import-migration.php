<?php

/**
 * FilamentPHP v4 Import Statement Migration Script
 *
 * This script automatically updates import statements from FilamentPHP v3 Schema patterns
 * to FilamentPHP v4 Form and Infolist patterns.
 */

class ImportMigrationScript
{
    private array $importMappings = [
        // Core schema to form/infolist mappings
        'use Filament\Schemas\Schema;' => [
            'form' => 'use Filament\Forms\Form;',
            'infolist' => 'use Filament\Infolists\Infolist;'
        ],

        // Component mappings for forms
        'use Filament\Schemas\Components\Section;' => 'use Filament\Forms\Components\Section;',
        'use Filament\Schemas\Components\Fieldset;' => 'use Filament\Forms\Components\Fieldset;',
        'use Filament\Schemas\Components\Grid;' => 'use Filament\Forms\Components\Grid;',
        'use Filament\Schemas\Components\Group;' => 'use Filament\Forms\Components\Group;',
        'use Filament\Schemas\Components\Tabs;' => 'use Filament\Forms\Components\Tabs;',
        'use Filament\Schemas\Components\Tabs\Tab;' => 'use Filament\Forms\Components\Tabs\Tab;',
        'use Filament\Schemas\Components\Wizard;' => 'use Filament\Forms\Components\Wizard;',
        'use Filament\Schemas\Components\Wizard\Step;' => 'use Filament\Forms\Components\Wizard\Step;',

        // Form input components
        'use Filament\Schemas\Components\TextInput;' => 'use Filament\Forms\Components\TextInput;',
        'use Filament\Schemas\Components\Textarea;' => 'use Filament\Forms\Components\Textarea;',
        'use Filament\Schemas\Components\Select;' => 'use Filament\Forms\Components\Select;',
        'use Filament\Schemas\Components\CheckboxList;' => 'use Filament\Forms\Components\CheckboxList;',
        'use Filament\Schemas\Components\Radio;' => 'use Filament\Forms\Components\Radio;',
        'use Filament\Schemas\Components\Toggle;' => 'use Filament\Forms\Components\Toggle;',
        'use Filament\Schemas\Components\Checkbox;' => 'use Filament\Forms\Components\Checkbox;',
        'use Filament\Schemas\Components\DatePicker;' => 'use Filament\Forms\Components\DatePicker;',
        'use Filament\Schemas\Components\DateTimePicker;' => 'use Filament\Forms\Components\DateTimePicker;',
        'use Filament\Schemas\Components\TimePicker;' => 'use Filament\Forms\Components\TimePicker;',
        'use Filament\Schemas\Components\FileUpload;' => 'use Filament\Forms\Components\FileUpload;',
        'use Filament\Schemas\Components\RichEditor;' => 'use Filament\Forms\Components\RichEditor;',
        'use Filament\Schemas\Components\MarkdownEditor;' => 'use Filament\Forms\Components\MarkdownEditor;',
        'use Filament\Schemas\Components\Repeater;' => 'use Filament\Forms\Components\Repeater;',
        'use Filament\Schemas\Components\Builder;' => 'use Filament\Forms\Components\Builder;',
        'use Filament\Schemas\Components\KeyValue;' => 'use Filament\Forms\Components\KeyValue;',
        'use Filament\Schemas\Components\TagsInput;' => 'use Filament\Forms\Components\TagsInput;',
        'use Filament\Schemas\Components\ColorPicker;' => 'use Filament\Forms\Components\ColorPicker;',
        'use Filament\Schemas\Components\Hidden;' => 'use Filament\Forms\Components\Hidden;',
        'use Filament\Schemas\Components\Placeholder;' => 'use Filament\Forms\Components\Placeholder;',

        // Utility imports
        'use Filament\Schemas\Components\Utilities\Get;' => 'use Filament\Forms\Get;',
        'use Filament\Schemas\Components\Utilities\Set;' => 'use Filament\Forms\Set;',
    ];

    private array $infolistMappings = [
        // Infolist-specific component mappings
        'use Filament\Schemas\Components\Section;' => 'use Filament\Infolists\Components\Section;',
        'use Filament\Schemas\Components\Grid;' => 'use Filament\Infolists\Components\Grid;',
        'use Filament\Schemas\Components\Group;' => 'use Filament\Infolists\Components\Group;',
        'use Filament\Schemas\Components\Fieldset;' => 'use Filament\Infolists\Components\Fieldset;',
        'use Filament\Schemas\Components\Split;' => 'use Filament\Infolists\Components\Split;',
        'use Filament\Schemas\Components\Tabs;' => 'use Filament\Infolists\Components\Tabs;',
        'use Filament\Schemas\Components\Tabs\Tab;' => 'use Filament\Infolists\Components\Tabs\Tab;',

        // Infolist entry components
        'use Filament\Schemas\Components\TextEntry;' => 'use Filament\Infolists\Components\TextEntry;',
        'use Filament\Schemas\Components\IconEntry;' => 'use Filament\Infolists\Components\IconEntry;',
        'use Filament\Schemas\Components\ColorEntry;' => 'use Filament\Infolists\Components\ColorEntry;',
        'use Filament\Schemas\Components\ImageEntry;' => 'use Filament\Infolists\Components\ImageEntry;',
        'use Filament\Schemas\Components\KeyValueEntry;' => 'use Filament\Infolists\Components\KeyValueEntry;',
        'use Filament\Schemas\Components\RepeatableEntry;' => 'use Filament\Infolists\Components\RepeatableEntry;',
    ];

    public function migrateFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['success' => false, 'error' => "File not found: $filePath"];
        }

        $content = file_get_contents($filePath);
        $originalContent = $content;
        $changes = [];

        // Determine if this file has form or infolist methods
        $hasFormMethod = strpos($content, 'function form(') !== false;
        $hasInfolistMethod = strpos($content, 'function infolist(') !== false;

        // Apply core Schema import replacements
        if (isset($this->importMappings['use Filament\Schemas\Schema;'])) {
            if ($hasFormMethod && strpos($content, 'use Filament\Schemas\Schema;') !== false) {
                $content = str_replace(
                    'use Filament\Schemas\Schema;',
                    $this->importMappings['use Filament\Schemas\Schema;']['form'],
                    $content
                );
                $changes[] = 'Replaced Schema import with Form import';
            }

            if ($hasInfolistMethod && strpos($content, 'use Filament\Schemas\Schema;') !== false) {
                $content = str_replace(
                    'use Filament\Schemas\Schema;',
                    $this->importMappings['use Filament\Schemas\Schema;']['infolist'],
                    $content
                );
                $changes[] = 'Replaced Schema import with Infolist import';
            }
        }

        // Apply component import replacements
        foreach ($this->importMappings as $oldImport => $newImport) {
            if ($oldImport === 'use Filament\Schemas\Schema;') {
                continue; // Already handled above
            }

            if (strpos($content, $oldImport) !== false) {
                if ($hasInfolistMethod && isset($this->infolistMappings[$oldImport])) {
                    // Use infolist-specific mapping if available and file has infolist method
                    $content = str_replace($oldImport, $this->infolistMappings[$oldImport], $content);
                    $changes[] = "Replaced $oldImport with infolist equivalent";
                } else {
                    // Use form mapping (default)
                    $content = str_replace($oldImport, $newImport, $content);
                    $changes[] = "Replaced $oldImport with $newImport";
                }
            }
        }

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
            'message' => 'No import changes needed'
        ];
    }

    public function migrateDirectory(string $directory, string $pattern = '*.php'): array
    {
        $results = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $filePath = $file->getPathname();

                // Skip if file doesn't contain Schema imports
                $content = file_get_contents($filePath);
                if (strpos($content, 'Filament\Schemas') === false) {
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

    public function generateReport(array $results): string
    {
        $report = "# Import Migration Report\n\n";
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
}

// CLI usage
if (php_sapi_name() === 'cli') {
    $script = new ImportMigrationScript();

    if ($argc < 2) {
        echo "Usage: php import-migration.php <directory_path> [output_report_path]\n";
        echo "Example: php import-migration.php plugins/webkul/accounts migration-report.md\n";
        exit(1);
    }

    $directory = $argv[1];
    $reportPath = $argv[2] ?? 'import-migration-report.md';

    if (!is_dir($directory)) {
        echo "Error: Directory not found: $directory\n";
        exit(1);
    }

    echo "Starting import migration for directory: $directory\n";
    $results = $script->migrateDirectory($directory);

    $report = $script->generateReport($results);
    file_put_contents($reportPath, $report);

    echo "Migration completed!\n";
    echo "Files processed: " . count($results) . "\n";
    echo "Report saved to: $reportPath\n";
}
