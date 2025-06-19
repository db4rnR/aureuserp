<?php

/**
 * FilamentPHP v4 Method Signature Migration Script
 *
 * This script automatically updates method signatures and method calls from FilamentPHP v3 patterns to v4 patterns.
 *
 * Usage: php method-signature-updater.php [path-to-scan]
 *
 * Changes made:
 * 1. form(Schema $schema): Schema -> form(Form $form): Form
 * 2. infolist(Schema $schema): Schema -> infolist(Infolist $infolist): Infolist
 * 3. $schema->components([]) -> $form->schema([])
 * 4. $schema->components([]) -> $infolist->schema([]) (in infolist context)
 */

class FilamentMethodSignatureUpdater
{
    private array $stats = [
        'files_processed' => 0,
        'files_updated' => 0,
        'signatures_updated' => 0,
        'method_calls_updated' => 0,
        'errors' => []
    ];

    private array $methodSignaturePatterns = [
        // Form method signatures
        '/public\s+static\s+function\s+form\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/' => 'public static function form(Form $form): Form',
        '/public\s+function\s+form\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/' => 'public function form(Form $form): Form',

        // Infolist method signatures
        '/public\s+static\s+function\s+infolist\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/' => 'public static function infolist(Infolist $infolist): Infolist',
        '/public\s+function\s+infolist\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/' => 'public function infolist(Infolist $infolist): Infolist',
    ];

    private array $methodCallPatterns = [
        // Form method calls
        '/\$schema->components\s*\(/' => '$form->schema(',

        // Infolist method calls (will be handled contextually)
        // '/\$schema->components\s*\(/' => '$infolist->schema(',
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

            // Update method signatures
            $content = $this->updateMethodSignatures($content);

            // Update method calls based on context
            $content = $this->updateMethodCalls($content);

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

    private function updateMethodSignatures(string $content): string
    {
        foreach ($this->methodSignaturePatterns as $pattern => $replacement) {
            $newContent = preg_replace($pattern, $replacement, $content);
            if ($newContent !== $content) {
                $this->stats['signatures_updated']++;
                $content = $newContent;
            }
        }
        return $content;
    }

    private function updateMethodCalls(string $content): string
    {
        // Split content into methods to handle context-aware replacements
        $methods = $this->extractMethods($content);

        foreach ($methods as $method) {
            $methodName = $method['name'];
            $methodContent = $method['content'];
            $originalMethodContent = $methodContent;

            if ($methodName === 'form') {
                // In form context, replace $schema with $form
                $methodContent = preg_replace('/\$schema->components\s*\(/', '$form->schema(', $methodContent);
                $methodContent = str_replace('$schema', '$form', $methodContent);
            } elseif ($methodName === 'infolist') {
                // In infolist context, replace $schema with $infolist
                $methodContent = preg_replace('/\$schema->components\s*\(/', '$infolist->schema(', $methodContent);
                $methodContent = str_replace('$schema', '$infolist', $methodContent);
            }

            if ($methodContent !== $originalMethodContent) {
                $content = str_replace($method['content'], $methodContent, $content);
                $this->stats['method_calls_updated']++;
            }
        }

        return $content;
    }

    private function extractMethods(string $content): array
    {
        $methods = [];

        // Pattern to match form and infolist methods
        $pattern = '/(?:public\s+(?:static\s+)?function\s+(form|infolist)\s*\([^)]*\)[^{]*\{)/';

        if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $index => $match) {
                $methodName = $matches[1][$index][0];
                $startPos = $match[1];

                // Find the end of the method by counting braces
                $braceCount = 0;
                $inMethod = false;
                $contentLength = strlen($content);
                $endPos = $contentLength;

                for ($i = $startPos; $i < $contentLength; $i++) {
                    $char = $content[$i];

                    if ($char === '{') {
                        $braceCount++;
                        $inMethod = true;
                    } elseif ($char === '}') {
                        $braceCount--;
                        if ($inMethod && $braceCount === 0) {
                            $endPos = $i + 1;
                            break;
                        }
                    }
                }

                $methodContent = substr($content, $startPos, $endPos - $startPos);

                $methods[] = [
                    'name' => $methodName,
                    'content' => $methodContent,
                    'start' => $startPos,
                    'end' => $endPos
                ];
            }
        }

        return $methods;
    }

    private function printStats(): void
    {
        echo "\n=== Method Signature Migration Statistics ===\n";
        echo "Files processed: {$this->stats['files_processed']}\n";
        echo "Files updated: {$this->stats['files_updated']}\n";
        echo "Method signatures updated: {$this->stats['signatures_updated']}\n";
        echo "Method calls updated: {$this->stats['method_calls_updated']}\n";

        if (!empty($this->stats['errors'])) {
            echo "\nErrors encountered:\n";
            foreach ($this->stats['errors'] as $error) {
                echo "- {$error}\n";
            }
        }

        echo "\nMethod signature migration complete!\n";
    }
}

// CLI execution
if (php_sapi_name() === 'cli') {
    $path = $argv[1] ?? getcwd();

    echo "FilamentPHP v4 Method Signature Migration\n";
    echo "Scanning directory: {$path}\n\n";

    try {
        $updater = new FilamentMethodSignatureUpdater();
        $updater->updateDirectory($path);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}
