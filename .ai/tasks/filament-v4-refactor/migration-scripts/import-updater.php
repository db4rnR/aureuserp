<?php

/**
 * FilamentPHP v4 Import Statement Migration Script
 *
 * This script automatically updates import statements from FilamentPHP v3 patterns to v4 patterns.
 *
 * Usage: php import-updater.php [path-to-scan]
 *
 * Changes made:
 * 1. Filament\Schemas\Schema -> Filament\Forms\Form (for forms)
 * 2. Filament\Schemas\Schema -> Filament\Infolists\Infolist (for infolists)
 * 3. Filament\Schemas\Components\* -> Filament\Forms\Components\*
 * 4. Filament\Schemas\Components\* -> Filament\Infolists\Components\* (for infolist components)
 */

class FilamentImportUpdater
{
    private array $stats = [
        'files_processed' => 0,
        'files_updated' => 0,
        'imports_updated' => 0,
        'errors' => []
    ];

    private array $formImportMappings = [
        'use Filament\Schemas\Schema;' => 'use Filament\Forms\Form;',
        'use Filament\Schemas\Components\Fieldset;' => 'use Filament\Forms\Components\Fieldset;',
        'use Filament\Schemas\Components\Grid;' => 'use Filament\Forms\Components\Grid;',
        'use Filament\Schemas\Components\Section;' => 'use Filament\Forms\Components\Section;',
        'use Filament\Schemas\Components\Select;' => 'use Filament\Forms\Components\Select;',
        'use Filament\Schemas\Components\TextInput;' => 'use Filament\Forms\Components\TextInput;',
        'use Filament\Schemas\Components\Textarea;' => 'use Filament\Forms\Components\Textarea;',
        'use Filament\Schemas\Components\Toggle;' => 'use Filament\Forms\Components\Toggle;',
        'use Filament\Schemas\Components\DatePicker;' => 'use Filament\Forms\Components\DatePicker;',
        'use Filament\Schemas\Components\DateTimePicker;' => 'use Filament\Forms\Components\DateTimePicker;',
        'use Filament\Schemas\Components\TimePicker;' => 'use Filament\Forms\Components\TimePicker;',
        'use Filament\Schemas\Components\FileUpload;' => 'use Filament\Forms\Components\FileUpload;',
        'use Filament\Schemas\Components\Hidden;' => 'use Filament\Forms\Components\Hidden;',
        'use Filament\Schemas\Components\Placeholder;' => 'use Filament\Forms\Components\Placeholder;',
        'use Filament\Schemas\Components\Repeater;' => 'use Filament\Forms\Components\Repeater;',
        'use Filament\Schemas\Components\RichEditor;' => 'use Filament\Forms\Components\RichEditor;',
        'use Filament\Schemas\Components\Wizard;' => 'use Filament\Forms\Components\Wizard;',
        'use Filament\Schemas\Components\Wizard\Step;' => 'use Filament\Forms\Components\Wizard\Step;',
        'use Filament\Schemas\Get;' => 'use Filament\Forms\Get;',
        'use Filament\Schemas\Set;' => 'use Filament\Forms\Set;',
    ];

    private array $infolistImportMappings = [
        'use Filament\Schemas\Components\Grid;' => 'use Filament\Infolists\Components\Grid;',
        'use Filament\Schemas\Components\Section;' => 'use Filament\Infolists\Components\Section;',
        'use Filament\Schemas\Components\TextEntry;' => 'use Filament\Infolists\Components\TextEntry;',
        'use Filament\Schemas\Components\IconEntry;' => 'use Filament\Infolists\Components\IconEntry;',
        'use Filament\Schemas\Components\ImageEntry;' => 'use Filament\Infolists\Components\ImageEntry;',
        'use Filament\Schemas\Components\Group;' => 'use Filament\Infolists\Components\Group;',
    ];

    public function updateDirectory(string $path): void
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("Directory does not exist: {$path}");
        }

        $this->scanDirectory($path);
        $this->printStats();
    }

    private function scanDirectory(string $directory): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $this->processFile($file->getPathname());
            }
        }
    }

    private function processFile(string $filePath): void
    {
        $this->stats['files_processed']++;

        try {
            $content = file_get_contents($filePath);
            $originalContent = $content;

            // Determine if this file contains form or infolist methods
            $hasFormMethod = strpos($content, 'function form(') !== false;
            $hasInfolistMethod = strpos($content, 'function infolist(') !== false;

            // Update imports based on context
            if ($hasFormMethod) {
                $content = $this->updateFormImports($content);
            }

            if ($hasInfolistMethod) {
                $content = $this->updateInfolistImports($content);
            }

            // Update generic schema imports
            $content = $this->updateSchemaImports($content, $hasFormMethod, $hasInfolistMethod);

            // Only write if content changed
            if ($content !== $originalContent) {
                file_put_contents($filePath, $content);
                $this->stats['files_updated']++;
                echo "Updated: {$filePath}\n";
            }

        } catch (Exception $e) {
            $this->stats['errors'][] = "Error processing {$filePath}: " . $e->getMessage();
            echo "Error: {$filePath} - " . $e->getMessage() . "\n";
        }
    }

    private function updateFormImports(string $content): string
    {
        foreach ($this->formImportMappings as $old => $new) {
            if (strpos($content, $old) !== false) {
                $content = str_replace($old, $new, $content);
                $this->stats['imports_updated']++;
            }
        }
        return $content;
    }

    private function updateInfolistImports(string $content): string
    {
        foreach ($this->infolistImportMappings as $old => $new) {
            if (strpos($content, $old) !== false) {
                $content = str_replace($old, $new, $content);
                $this->stats['imports_updated']++;
            }
        }
        return $content;
    }

    private function updateSchemaImports(string $content, bool $hasFormMethod, bool $hasInfolistMethod): string
    {
        // Handle Schema import based on context
        if (strpos($content, 'use Filament\Schemas\Schema;') !== false) {
            if ($hasFormMethod && !$hasInfolistMethod) {
                // Form context only
                $content = str_replace(
                    'use Filament\Schemas\Schema;',
                    'use Filament\Forms\Form;',
                    $content
                );
                $this->stats['imports_updated']++;
            } elseif ($hasInfolistMethod && !$hasFormMethod) {
                // Infolist context only
                $content = str_replace(
                    'use Filament\Schemas\Schema;',
                    'use Filament\Infolists\Infolist;',
                    $content
                );
                $this->stats['imports_updated']++;
            } elseif ($hasFormMethod && $hasInfolistMethod) {
                // Both contexts - add both imports
                $content = str_replace(
                    'use Filament\Schemas\Schema;',
                    "use Filament\Forms\Form;\nuse Filament\Infolists\Infolist;",
                    $content
                );
                $this->stats['imports_updated'] += 2;
            }
        }

        return $content;
    }

    private function printStats(): void
    {
        echo "\n=== Migration Statistics ===\n";
        echo "Files processed: {$this->stats['files_processed']}\n";
        echo "Files updated: {$this->stats['files_updated']}\n";
        echo "Import statements updated: {$this->stats['imports_updated']}\n";

        if (!empty($this->stats['errors'])) {
            echo "\nErrors encountered:\n";
            foreach ($this->stats['errors'] as $error) {
                echo "- {$error}\n";
            }
        }

        echo "\nMigration complete!\n";
    }
}

// CLI execution
if (php_sapi_name() === 'cli') {
    $path = $argv[1] ?? getcwd();

    echo "FilamentPHP v4 Import Statement Migration\n";
    echo "Scanning directory: {$path}\n\n";

    try {
        $updater = new FilamentImportUpdater();
        $updater->updateDirectory($path);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}
