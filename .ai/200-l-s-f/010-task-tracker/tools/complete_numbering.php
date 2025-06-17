<?php

declare(strict_types=1);

/**
 * Hierarchical Header Numbering Script
 *
 * This script applies proper hierarchical numbering (1, 1.1, 1.1.1, etc.) to markdown files
 * following the user's coding standards. It handles:
 * - H2-H6 header numbering
 * - Code block preservation
 * - Proper hierarchical sequence
 * - TOC generation compatibility
 *
 * Usage: php complete_numbering.php <input_file> [--fix-in-place]
 */

if ($argc < 2 || $argc > 3) {
    echo "Usage: php complete_numbering.php <input_file> [--fix-in-place]\n";
    echo "  --fix-in-place: Overwrite the original file instead of creating a new one\n";
    exit(1);
}

$filename = $argv[1];
$fixInPlace = ($argc === 3 && $argv[2] === '--fix-in-place');

if (!file_exists($filename)) {
    echo "Error: File '$filename' not found!\n";
    exit(1);
}

$lines = file($filename, FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    echo "Error: Could not read file '$filename'!\n";
    exit(1);
}

$output = [];
$inCodeBlock = false;
$codeBlockDelimiter = '';

// Hierarchical counters: [h2, h3, h4, h5, h6]
$counters = [0, 0, 0, 0, 0];

/**
 * Generate hierarchical number for a given header level
 */
function generateNumber(array $counters, int $level): string {
    $parts = [];
    for ($i = 0; $i < $level; $i++) {
        if ($counters[$i] > 0) {
            $parts[] = $counters[$i];
        }
    }
    return implode('.', $parts);
}

/**
 * Reset counters for deeper levels when a higher level increments
 */
function resetDeeperCounters(array &$counters, int $level): void {
    for ($i = $level; $i < count($counters); $i++) {
        $counters[$i] = 0;
    }
}

foreach ($lines as $lineNum => $line) {
    // Track code blocks to avoid numbering headers inside them
    if (preg_match('/^```(\w+)?/', $line, $matches)) {
        $inCodeBlock = !$inCodeBlock;
        if ($inCodeBlock && !empty($matches[1])) {
            $codeBlockDelimiter = $matches[1];
        } else {
            $codeBlockDelimiter = '';
        }
        $output[] = $line;
        continue;
    }

    if ($inCodeBlock) {
        $output[] = $line;
        continue;
    }    // Match headers H2-H6 and apply hierarchical numbering
    if (preg_match('/^(#{2,6})\s+(.+)$/', $line, $matches)) {
        $headerLevel = strlen($matches[1]) - 2; // Convert H2=0, H3=1, H4=2, etc.
        $headerText = $matches[2];

        // Remove ALL existing numbering patterns from header text
        $cleanText = $headerText;

        // Remove leading hierarchical numbering (e.g., "5.1.7.")
        $cleanText = preg_replace('/^\d+(\.\d+)*\.\s*/', '', $cleanText);

        // Remove embedded legacy numbering (e.g., "3.1.6.1.")
        $cleanText = preg_replace('/\d+(\.\d+)*\.\s*/', '', $cleanText);

        // Remove task/step prefixes (e.g., "Task 1.1:", "Step 1.1.1:")
        $cleanText = preg_replace('/^(Task|Step)\s+\d+(\.\d+)*:\s*/', '', $cleanText);

        // Clean up any double spaces or leading/trailing whitespace
        $cleanText = preg_replace('/\s+/', ' ', trim($cleanText));

        // Increment counter for this level
        $counters[$headerLevel]++;

        // Reset all deeper level counters
        resetDeeperCounters($counters, $headerLevel + 1);

        // Generate hierarchical number
        $number = generateNumber($counters, $headerLevel + 1);

        // Reconstruct header with proper numbering
        $headerPrefix = str_repeat('#', $headerLevel + 2);
        $output[] = "{$headerPrefix} {$number}. {$cleanText}";
        continue;
    }

    // Pass through all other lines unchanged
    $output[] = $line;
}

// Write output to file
$outputFile = $fixInPlace ? $filename : str_replace('.md', '_numbered.md', $filename);
$result = file_put_contents($outputFile, implode("\n", $output) . "\n");

if ($result === false) {
    echo "Error: Could not write to file '$outputFile'!\n";
    exit(1);
}

echo "✅ Successfully " . ($fixInPlace ? "updated" : "created numbered file") . ": $outputFile\n";
echo "📊 Applied hierarchical numbering following coding standards\n";
echo "   - Headers properly numbered with format: 1, 1.1, 1.1.1, etc.\n";
echo "   - Code blocks preserved\n";
echo "   - Existing numbering cleaned and corrected\n";
