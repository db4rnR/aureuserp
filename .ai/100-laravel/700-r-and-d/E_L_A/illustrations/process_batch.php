<?php
/**
 * Script to process a small batch of diagrams
 */

// Define directories
$thumbnailsDir = "thumbnails";
$mermaidLightDir = "mermaid/light";

// Ensure the thumbnails directory exists
if (!is_dir($thumbnailsDir)) {
    mkdir($thumbnailsDir, 0755, true);
    echo "Created thumbnails directory\n";
}

/**
 * Function to extract Mermaid content from a file
 */
function extractMermaidContent($filePath) {
    if (!file_exists($filePath)) {
        echo "Warning: File not found: $filePath\n";
        return null;
    }

    $content = file_get_contents($filePath);

    // Look for Mermaid content between ```mermaid and ``` tags
    if (preg_match('/```mermaid\s*(.*?)\s*```/s', $content, $matches)) {
        return trim($matches[1]);
    }

    // If not found, assume the entire file is Mermaid content
    return trim($content);
}

/**
 * Function to determine diagram type from content
 */
function determineDiagramType($content) {
    if (strpos($content, 'sequenceDiagram') !== false) {
        return 'Sequence';
    } elseif (strpos($content, 'classDiagram') !== false) {
        return 'Class';
    } elseif (strpos($content, 'erDiagram') !== false) {
        return 'ERD';
    } elseif (strpos($content, 'gantt') !== false) {
        return 'Gantt';
    } elseif (strpos($content, 'stateDiagram') !== false) {
        return 'State';
    } elseif (strpos($content, 'flowchart') !== false || strpos($content, 'graph') !== false) {
        return 'Flowchart';
    } elseif (strpos($content, 'journey') !== false) {
        return 'Journey';
    } elseif (strpos($content, 'pie') !== false) {
        return 'Pie';
    } else {
        return 'Flowchart'; // Default
    }
}

/**
 * Function to create a Mermaid SVG thumbnail
 */
function createMermaidSvgThumbnail($name, $type, $content, $thumbnailsDir) {
    // Create the filename
    $filename = strtolower(str_replace(" ", "-", $name)) . "-thumb.svg";
    $filepath = $thumbnailsDir . "/" . $filename;

    // Check if we have the mermaid-cli installed
    exec('which mmdc 2>/dev/null', $output, $returnCode);
    $hasMermaidCli = ($returnCode === 0);

    if ($hasMermaidCli) {
        // Create a temporary file with the Mermaid content
        $tempFile = tempnam(sys_get_temp_dir(), 'mermaid_');
        file_put_contents($tempFile, $content);

        // Export the diagram using Mermaid CLI
        $command = "mmdc -i $tempFile -o $filepath -w 300 -H 200";
        exec($command, $output, $returnCode);

        // Clean up the temporary file
        unlink($tempFile);

        if ($returnCode === 0) {
            echo "Created Mermaid SVG thumbnail for '$name' using Mermaid CLI\n";
            return true;
        }
    }

    // If Mermaid CLI is not available or export failed, create a simple SVG thumbnail
    echo "Creating simple SVG thumbnail for '$name'\n";

    // Define colors for different diagram types
    $diagramColors = [
        "Flowchart" => "#0077cc",
        "ERD" => "#087f5b",
        "Sequence" => "#e67700",
        "Class" => "#9c36b5",
        "State" => "#c92a2a",
        "Gantt" => "#5f3dc4",
        "Deployment" => "#1864ab",
        "Journey" => "#5c940d",
        "Pie" => "#862e9c",
        "Overview" => "#0077cc", // Default for overview diagrams
    ];

    // Determine the color based on the diagram type
    $color = isset($diagramColors[$type]) ? $diagramColors[$type] : "#0077cc";

    // Create a simple SVG thumbnail
    $svgContent = <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="#f8f9fa" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#333">{$name}</text>

  <!-- Diagram Type Indicator -->
  <rect x="20" y="50" width="260" height="120" fill="{$color}" fill-opacity="0.2" rx="5" ry="5" />

  <!-- Diagram Type Label -->
  <text x="150" y="100" font-family="Arial, sans-serif" font-size="14" text-anchor="middle" fill="#333">{$type}</text>

  <!-- Description -->
  <text x="150" y="180" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#666">Diagram for {$name}</text>
</svg>
SVG;

    // Write the SVG file
    file_put_contents($filepath, $svgContent);

    echo "Created SVG thumbnail: $filepath\n";
    return true;
}

// Process a small batch of diagrams
function processBatch($batchFiles) {
    global $thumbnailsDir, $mermaidLightDir;

    echo "Processing batch of " . count($batchFiles) . " diagrams\n";

    foreach ($batchFiles as $file) {
        // Extract the diagram name from the file name
        if (preg_match('/^(.+)-light\.md$/', $file, $matches)) {
            $baseName = $matches[1];
            $diagramName = str_replace('-', ' ', $baseName);
            $diagramName = ucwords($diagramName);

            // Create the thumbnail filename
            $thumbnailFilename = strtolower(str_replace(" ", "-", $diagramName)) . "-thumb.svg";
            $thumbnailPath = $thumbnailsDir . "/" . $thumbnailFilename;

            // Check if the thumbnail already exists and is a real diagram (not a placeholder)
            if (file_exists($thumbnailPath) && filesize($thumbnailPath) > 5000) {
                echo "Thumbnail for '$diagramName' already exists and is not a placeholder\n";
                continue;
            }

            // Extract the Mermaid content
            $filePath = $mermaidLightDir . "/" . $file;
            $content = extractMermaidContent($filePath);

            if ($content) {
                // Determine the diagram type
                $diagramType = determineDiagramType($content);

                // Create the SVG thumbnail
                createMermaidSvgThumbnail($diagramName, $diagramType, $content, $thumbnailsDir);
            }
        }
    }
}

// Get a list of diagrams that need processing
function getDiagramsNeedingProcessing() {
    global $thumbnailsDir, $mermaidLightDir;

    // Get all Mermaid diagram files
    $diagramFiles = scandir($mermaidLightDir);
    $diagramFiles = array_filter($diagramFiles, function($file) {
        return preg_match('/\.md$/', $file);
    });

    // Filter to only include diagrams that need processing
    $needProcessing = [];
    foreach ($diagramFiles as $file) {
        // Extract the diagram name from the file name
        if (preg_match('/^(.+)-light\.md$/', $file, $matches)) {
            $baseName = $matches[1];
            $diagramName = str_replace('-', ' ', $baseName);
            $diagramName = ucwords($diagramName);

            // Create the thumbnail filename
            $thumbnailFilename = strtolower(str_replace(" ", "-", $diagramName)) . "-thumb.svg";
            $thumbnailPath = $thumbnailsDir . "/" . $thumbnailFilename;

            // Check if the thumbnail already exists and is a real diagram (not a placeholder)
            if (!file_exists($thumbnailPath) || filesize($thumbnailPath) < 5000) {
                $needProcessing[] = $file;
            }
        }
    }

    return $needProcessing;
}

/**
 * Function to update the HTML file to use the new thumbnails
 */
function updateHtmlReferences($htmlFile, $processedDiagrams) {
    // Read the HTML file
    $content = file_get_contents($htmlFile);
    $replacements = 0;

    // For each processed diagram, update the HTML to use the new thumbnail
    foreach ($processedDiagrams as $file) {
        // Extract the diagram name from the file name
        if (preg_match('/^(.+)-light\.md$/', $file, $matches)) {
            $baseName = $matches[1];
            $diagramName = str_replace('-', ' ', $baseName);
            $diagramName = ucwords($diagramName);

            // Create the thumbnail filename
            $thumbnailFilename = strtolower(str_replace(" ", "-", $diagramName)) . "-thumb.svg";

            // Look for img tags with placeholder thumbnails
            $pattern = '/<img src="thumbnails\/[^"]*" alt="' . preg_quote($diagramName, '/') . ' thumbnail" width="[^"]*"[^>]*>/';
            $replacement = '<img src="thumbnails/' . $thumbnailFilename . '" alt="' . $diagramName . ' thumbnail" width="80" />';

            // Replace in the HTML content
            $newContent = preg_replace($pattern, $replacement, $content, -1, $count);

            if ($count > 0) {
                $content = $newContent;
                $replacements += $count;
                echo "Updated $count references to '$diagramName' thumbnail\n";
            }
        }
    }

    // Write the updated HTML
    file_put_contents($htmlFile, $content);

    echo "Updated $replacements thumbnail references in the HTML file\n";
    return $replacements;
}

// Main function
function main() {
    // Get diagrams that need processing
    $diagramsNeedingProcessing = getDiagramsNeedingProcessing();

    echo "Found " . count($diagramsNeedingProcessing) . " diagrams that need processing\n";

    // Process in small batches of 5
    $batchSize = 5;
    $batches = array_chunk($diagramsNeedingProcessing, $batchSize);

    // Get the batch number from command line arguments
    $batchNumber = 1; // Default to batch 1
    if (isset($argv[1]) && is_numeric($argv[1])) {
        $batchNumber = (int)$argv[1];
    }

    // Make sure the batch number is valid
    if ($batchNumber < 1 || $batchNumber > count($batches)) {
        echo "Invalid batch number. Valid range is 1 to " . count($batches) . "\n";
        return;
    }

    // Process the specified batch
    if (count($batches) > 0) {
        $batch = $batches[$batchNumber - 1];
        echo "Processing batch $batchNumber of " . count($batches) . "\n";
        processBatch($batch);
        echo "Processed batch $batchNumber of diagrams\n";

        // Update the HTML file to use the new thumbnails
        echo "\nUpdating HTML to use new thumbnails:\n";
        updateHtmlReferences("index.html", $batch);
    } else {
        echo "No diagrams need processing\n";
    }
}

// Run the main function
main();
