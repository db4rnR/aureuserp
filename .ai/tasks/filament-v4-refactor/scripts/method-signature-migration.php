<?php

/**
 * FilamentPHP v4 Method Signature Migration Script
 *
 * This script automatically updates method signatures and method calls from FilamentPHP v3 Schema patterns
 * to FilamentPHP v4 Form and Infolist patterns.
 */

class MethodSignatureMigrationScript
{
    private array $methodSignaturePatterns = [
        // Form method signature patterns
        'form' => [
            'old_signature' => '/public\s+static\s+function\s+form\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i',
            'new_signature' => 'public static function form(Form $form): Form',
            'old_call_pattern' => '/\$schema\s*->\s*components\s*\(/i',
            'new_call_pattern' => '$form->schema(',
        ],

        // Infolist method signature patterns
        'infolist' => [
            'old_signature' => '/public\s+static\s+function\s+infolist\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i',
            'new_signature' => 'public static function infolist(Infolist $infolist): Infolist',
            'old_call_pattern' => '/\$schema\s*->\s*components\s*\(/i',
            'new_call_pattern' => '$infolist->schema(',
        ],
    ];

    private array $additionalPatterns = [
        // Variable name patterns within methods
        'form_variable_patterns' => [
            '/\$schema\s*=\s*\$schema\s*->\s*components\s*\(/i' => '$form = $form->schema(',
            '/return\s+\$schema\s*;/i' => 'return $form;',
            '/\$schema\s*->\s*when\s*\(/i' => '$form->when(',
            '/\$schema\s*->\s*disabled\s*\(/i' => '$form->disabled(',
            '/\$schema\s*->\s*hidden\s*\(/i' => '$form->hidden(',
        ],

        'infolist_variable_patterns' => [
            '/\$schema\s*=\s*\$schema\s*->\s*components\s*\(/i' => '$infolist = $infolist->schema(',
            '/return\s+\$schema\s*;/i' => 'return $infolist;',
            '/\$schema\s*->\s*when\s*\(/i' => '$infolist->when(',
            '/\$schema\s*->\s*disabled\s*\(/i' => '$infolist->disabled(',
            '/\$schema\s*->\s*hidden\s*\(/i' => '$infolist->hidden(',
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

        // Check if file contains Schema-based methods
        if (strpos($content, 'Filament\Schemas\Schema') === false &&
            strpos($content, 'function form(Schema') === false &&
            strpos($content, 'function infolist(Schema') === false) {
            return [
                'success' => true,
                'changes' => [],
                'file' => $filePath,
                'message' => 'No method signature changes needed'
            ];
        }

        // Process form methods
        if (preg_match($this->methodSignaturePatterns['form']['old_signature'], $content)) {
            // Update method signature
            $content = preg_replace(
                $this->methodSignaturePatterns['form']['old_signature'],
                $this->methodSignaturePatterns['form']['new_signature'],
                $content
            );
            $changes[] = 'Updated form method signature from Schema to Form';

            // Update method calls within form method
            $content = $this->updateMethodCalls($content, 'form', $changes);
        }

        // Process infolist methods
        if (preg_match($this->methodSignaturePatterns['infolist']['old_signature'], $content)) {
            // Update method signature
            $content = preg_replace(
                $this->methodSignaturePatterns['infolist']['old_signature'],
                $this->methodSignaturePatterns['infolist']['new_signature'],
                $content
            );
            $changes[] = 'Updated infolist method signature from Schema to Infolist';

            // Update method calls within infolist method
            $content = $this->updateMethodCalls($content, 'infolist', $changes);
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
            'message' => 'No method signature changes needed'
        ];
    }

    private function updateMethodCalls(string $content, string $methodType, array &$changes): string
    {
        // Update primary method call pattern
        $oldCallPattern = $this->methodSignaturePatterns[$methodType]['old_call_pattern'];
        $newCallPattern = $this->methodSignaturePatterns[$methodType]['new_call_pattern'];

        if (preg_match($oldCallPattern, $content)) {
            $content = preg_replace($oldCallPattern, $newCallPattern, $content);
            $changes[] = "Updated $methodType method calls from \$schema->components() to appropriate pattern";
        }

        // Update additional variable patterns
        $variablePatterns = $this->additionalPatterns[$methodType . '_variable_patterns'];
        foreach ($variablePatterns as $oldPattern => $newPattern) {
            if (preg_match($oldPattern, $content)) {
                $content = preg_replace($oldPattern, $newPattern, $content);
                $changes[] = "Updated $methodType variable usage pattern";
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

                // Skip if file doesn't contain relevant patterns
                $content = file_get_contents($filePath);
                if (strpos($content, 'function form(') === false &&
                    strpos($content, 'function infolist(') === false) {
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

    public function validateMethodSignatures(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['valid' => false, 'error' => "File not found: $filePath"];
        }

        $content = file_get_contents($filePath);
        $issues = [];

        // Check for remaining old patterns
        if (preg_match($this->methodSignaturePatterns['form']['old_signature'], $content)) {
            $issues[] = 'Found old form method signature using Schema';
        }

        if (preg_match($this->methodSignaturePatterns['infolist']['old_signature'], $content)) {
            $issues[] = 'Found old infolist method signature using Schema';
        }

        // Check for old method call patterns
        if (preg_match('/\$schema\s*->\s*components\s*\(/i', $content)) {
            $issues[] = 'Found old $schema->components() method calls';
        }

        // Check for mixed patterns (potential issues)
        if (strpos($content, 'Form $form') !== false && strpos($content, '$schema->') !== false) {
            $issues[] = 'Found mixed Form/Schema patterns - may need manual review';
        }

        if (strpos($content, 'Infolist $infolist') !== false && strpos($content, '$schema->') !== false) {
            $issues[] = 'Found mixed Infolist/Schema patterns - may need manual review';
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
            'file' => $filePath
        ];
    }

    public function generateReport(array $results): string
    {
        $report = "# Method Signature Migration Report\n\n";
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
        $report = "# Method Signature Validation Report\n\n";
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
    $script = new MethodSignatureMigrationScript();

    if ($argc < 2) {
        echo "Usage: php method-signature-migration.php <command> <directory_path> [options]\n";
        echo "Commands:\n";
        echo "  migrate <directory>     - Migrate method signatures in directory\n";
        echo "  validate <directory>    - Validate method signatures in directory\n";
        echo "Examples:\n";
        echo "  php method-signature-migration.php migrate plugins/webkul/accounts\n";
        echo "  php method-signature-migration.php validate plugins/webkul/accounts\n";
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
            echo "Starting method signature migration for directory: $directory\n";
            $results = $script->migrateDirectory($directory);

            $report = $script->generateReport($results);
            $reportPath = 'method-signature-migration-report.md';
            file_put_contents($reportPath, $report);

            echo "Migration completed!\n";
            echo "Files processed: " . count($results) . "\n";
            echo "Report saved to: $reportPath\n";
            break;

        case 'validate':
            echo "Starting method signature validation for directory: $directory\n";
            $results = [];

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory)
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $results[] = $script->validateMethodSignatures($file->getPathname());
                }
            }

            $report = $script->generateValidationReport($results);
            $reportPath = 'method-signature-validation-report.md';
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
