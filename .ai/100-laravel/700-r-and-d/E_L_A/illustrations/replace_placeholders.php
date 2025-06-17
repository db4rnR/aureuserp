<?php
/**
 * Script to replace placeholder thumbnails with actual diagram content
 * Parts 1 & 2: Identify diagrams with content and extract Mermaid content
 */

// Define directories
$thumbnailsDir = "thumbnails";
$mermaidDarkDir = "mermaid/dark";
$mermaidLightDir = "mermaid/light";

// Ensure the thumbnails directory exists
if (!is_dir($thumbnailsDir)) {
    mkdir($thumbnailsDir, 0755, true);
    echo "Created thumbnails directory\n";
}

/**
 * Function to extract diagram paths from the HTML file
 */
function extractDiagramPaths($htmlFile) {
    $content = file_get_contents($htmlFile);
    $diagrams = [];

    // Look for data attributes with diagram paths
    preg_match_all('/data-diagram-name="([^"]+)" data-diagram-type="([^"]+)" data-diagram-desc="([^"]+)" data-diagram-dark="([^"]+)" data-diagram-light="([^"]+)"/', $content, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $diagramName = $match[1];
        $diagramType = $match[2];
        $diagramDesc = $match[3];
        $darkPath = $match[4];
        $lightPath = $match[5];

        $diagrams[$diagramName] = [
            'type' => $diagramType,
            'description' => $diagramDesc,
            'dark_path' => $darkPath,
            'light_path' => $lightPath
        ];
    }

    return $diagrams;
}

/**
 * Function to check if a diagram file exists and has content
 */
function checkDiagramFile($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }

    $content = file_get_contents($filePath);
    return !empty($content);
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

// Main function for Part 1
function identifyDiagramsWithContent() {
    global $mermaidDarkDir, $mermaidLightDir;

    // Get the HTML file path
    $htmlFile = "index.html";

    // Extract diagram paths from the HTML
    $diagrams = extractDiagramPaths($htmlFile);

    echo "Found " . count($diagrams) . " diagrams in the HTML file\n";

    // Check which diagrams have actual content files
    $diagramsWithContent = [];
    $diagramsWithoutContent = [];

    foreach ($diagrams as $name => $info) {
        $darkPath = $info['dark_path'];
        $lightPath = $info['light_path'];

        $hasDarkContent = checkDiagramFile($darkPath);
        $hasLightContent = checkDiagramFile($lightPath);

        if ($hasDarkContent || $hasLightContent) {
            $diagramsWithContent[$name] = [
                'info' => $info,
                'has_dark' => $hasDarkContent,
                'has_light' => $hasLightContent
            ];
        } else {
            $diagramsWithoutContent[$name] = $info;
        }
    }

    echo "Found " . count($diagramsWithContent) . " diagrams with content files\n";
    echo "Found " . count($diagramsWithoutContent) . " diagrams without content files\n";

    return $diagramsWithContent;
}

// Main function for Part 2
function extractMermaidContentFromDiagrams($diagramsWithContent) {
    $extractedContent = [];

    foreach ($diagramsWithContent as $name => $info) {
        $diagramInfo = $info['info'];
        $hasDark = $info['has_dark'];
        $hasLight = $info['has_light'];

        // Prefer light version for thumbnails
        if ($hasLight) {
            $content = extractMermaidContent($diagramInfo['light_path']);
            if ($content) {
                $extractedContent[$name] = [
                    'type' => $diagramInfo['type'],
                    'content' => $content
                ];
                echo "Extracted Mermaid content from light version of '$name'\n";
            }
        } elseif ($hasDark) {
            $content = extractMermaidContent($diagramInfo['dark_path']);
            if ($content) {
                $extractedContent[$name] = [
                    'type' => $diagramInfo['type'],
                    'content' => $content
                ];
                echo "Extracted Mermaid content from dark version of '$name'\n";
            }
        }
    }

    echo "Extracted Mermaid content from " . count($extractedContent) . " diagrams\n";
    return $extractedContent;
}

// Run the identification function
$diagramsWithContent = identifyDiagramsWithContent();

// Output the results
echo "\nDiagrams with content files:\n";
foreach ($diagramsWithContent as $name => $info) {
    echo "- $name (" . $info['info']['type'] . ")\n";
    echo "  Dark: " . ($info['has_dark'] ? "Yes" : "No") . ", Light: " . ($info['has_light'] ? "Yes" : "No") . "\n";
}

// Extract Mermaid content from diagrams
$extractedContent = extractMermaidContentFromDiagrams($diagramsWithContent);

// Output the extracted content
echo "\nExtracted Mermaid content:\n";
foreach ($extractedContent as $name => $info) {
    echo "- $name (" . $info['type'] . ")\n";
    echo "  Content length: " . strlen($info['content']) . " characters\n";
    // Show a preview of the content (first 50 characters)
    $preview = substr($info['content'], 0, 50);
    echo "  Preview: " . str_replace("\n", " ", $preview) . "...\n";
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
        "Overview" => "#0077cc", // Default for overview diagrams
    ];

    // Determine the color based on the diagram type
    $color = isset($diagramColors[$type]) ? $diagramColors[$type] : "#0077cc";

    // Create a simple SVG thumbnail based on the diagram type
    $svgContent = '';

    switch ($type) {
        case 'Sequence':
            $svgContent = createSequenceSvgThumbnail($name, $color);
            break;
        case 'ERD':
            $svgContent = createErdSvgThumbnail($name, $color);
            break;
        case 'Gantt':
            $svgContent = createGanttSvgThumbnail($name, $color);
            break;
        case 'Flowchart':
            $svgContent = createFlowchartSvgThumbnail($name, $color);
            break;
        default:
            // Generic template for other diagram types
            $svgContent = createGenericSvgThumbnail($name, $type, $color);
    }

    // Write the SVG file
    file_put_contents($filepath, $svgContent);

    echo "Created SVG thumbnail: $filepath\n";
    return true;
}

/**
 * Create a generic SVG thumbnail
 */
function createGenericSvgThumbnail($name, $type, $color) {
    return <<<SVG
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
}

/**
 * Create a Sequence SVG thumbnail
 */
function createSequenceSvgThumbnail($name, $color) {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="#f8f9fa" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#333">{$name}</text>

  <!-- Sequence Diagram Elements -->
  <!-- Actor: User -->
  <circle cx="75" cy="60" r="10" fill="{$color}" />
  <line x1="75" y1="70" x2="75" y2="100" stroke="{$color}" stroke-width="2" />
  <line x1="60" y1="85" x2="90" y2="85" stroke="{$color}" stroke-width="2" />
  <line x1="75" y1="100" x2="60" y2="120" stroke="{$color}" stroke-width="2" />
  <line x1="75" y1="100" x2="90" y2="120" stroke="{$color}" stroke-width="2" />
  <text x="75" y="140" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#333">User</text>

  <!-- Actor: System -->
  <rect x="210" y="50" width="30" height="20" fill="{$color}" />
  <line x1="225" y1="70" x2="225" y2="120" stroke="{$color}" stroke-width="2" />
  <text x="225" y="140" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#333">System</text>

  <!-- Sequence Arrows -->
  <line x1="75" y1="85" x2="215" y2="85" stroke="#333" stroke-width="1.5" />
  <polygon points="215,85 205,80 205,90" fill="#333" />
  <text x="145" y="80" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#333">Request</text>

  <line x1="225" y1="110" x2="85" y2="110" stroke="#333" stroke-width="1.5" stroke-dasharray="5,3" />
  <polygon points="85,110 95,105 95,115" fill="#333" />
  <text x="155" y="105" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#333">Response</text>

  <!-- Lifelines -->
  <line x1="75" y1="150" x2="75" y2="180" stroke="#333" stroke-width="1" stroke-dasharray="4,2" />
  <line x1="225" y1="150" x2="225" y2="180" stroke="#333" stroke-width="1" stroke-dasharray="4,2" />
</svg>
SVG;
}

/**
 * Create an ERD SVG thumbnail
 */
function createErdSvgThumbnail($name, $color) {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="#f8f9fa" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#333">{$name}</text>

  <!-- ERD Elements -->
  <rect x="60" y="70" width="80" height="40" fill="{$color}" stroke="#333" stroke-width="1" rx="3" ry="3" />
  <text x="100" y="95" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Entity 1</text>

  <rect x="160" y="70" width="80" height="40" fill="{$color}" stroke="#333" stroke-width="1" rx="3" ry="3" />
  <text x="200" y="95" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Entity 2</text>

  <line x1="140" y1="90" x2="160" y2="90" stroke="#333" stroke-width="1.5" />
  <text x="150" y="85" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#333">1:N</text>

  <rect x="60" y="140" width="80" height="40" fill="{$color}" stroke="#333" stroke-width="1" rx="3" ry="3" />
  <text x="100" y="165" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Entity 3</text>

  <rect x="160" y="140" width="80" height="40" fill="{$color}" stroke="#333" stroke-width="1" rx="3" ry="3" />
  <text x="200" y="165" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Entity 4</text>

  <line x1="100" y1="110" x2="100" y2="140" stroke="#333" stroke-width="1.5" />
  <text x="110" y="125" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#333">1:1</text>

  <line x1="200" y1="110" x2="200" y2="140" stroke="#333" stroke-width="1.5" />
  <text x="210" y="125" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#333">N:M</text>
</svg>
SVG;
}

/**
 * Create a Flowchart SVG thumbnail
 */
function createFlowchartSvgThumbnail($name, $color) {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="#f8f9fa" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#333">{$name}</text>

  <!-- Flowchart Elements -->
  <rect x="120" y="50" width="60" height="30" fill="{$color}" rx="3" ry="3" />
  <text x="150" y="70" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Start</text>

  <line x1="150" y1="80" x2="150" y2="100" stroke="#333" stroke-width="1.5" />
  <polygon points="150,100 145,95 155,95" fill="#333" />

  <rect x="100" y="100" width="100" height="40" fill="{$color}" fill-opacity="0.8" rx="3" ry="3" />
  <text x="150" y="125" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Process</text>

  <line x1="150" y1="140" x2="150" y2="160" stroke="#333" stroke-width="1.5" />
  <polygon points="150,160 145,155 155,155" fill="#333" />

  <ellipse cx="150" cy="170" rx="30" ry="15" fill="{$color}" />
  <text x="150" y="175" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">End</text>
</svg>
SVG;
}

/**
 * Create a Gantt SVG thumbnail
 */
function createGanttSvgThumbnail($name, $color) {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="#f8f9fa" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#333">{$name}</text>

  <!-- Gantt Chart Elements -->
  <!-- Timeline Header -->
  <line x1="40" y1="50" x2="260" y2="50" stroke="#333" stroke-width="1" />
  <text x="40" y="45" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#333">Q1</text>
  <text x="100" y="45" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#333">Q2</text>
  <text x="160" y="45" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#333">Q3</text>
  <text x="220" y="45" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#333">Q4</text>

  <!-- Gantt Bars -->
  <rect x="40" y="60" width="60" height="20" fill="{$color}" rx="3" ry="3" />
  <text x="70" y="74" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Task 1</text>

  <rect x="100" y="90" width="120" height="20" fill="{$color}" rx="3" ry="3" />
  <text x="160" y="104" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Task 2</text>

  <rect x="160" y="120" width="60" height="20" fill="{$color}" rx="3" ry="3" />
  <text x="190" y="134" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Task 3</text>

  <rect x="220" y="150" width="40" height="20" fill="{$color}" rx="3" ry="3" />
  <text x="240" y="164" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Task 4</text>

  <!-- Task Labels -->
  <text x="30" y="74" font-family="Arial, sans-serif" font-size="10" text-anchor="end" fill="#333">Planning</text>
  <text x="30" y="104" font-family="Arial, sans-serif" font-size="10" text-anchor="end" fill="#333">Development</text>
  <text x="30" y="134" font-family="Arial, sans-serif" font-size="10" text-anchor="end" fill="#333">Testing</text>
  <text x="30" y="164" font-family="Arial, sans-serif" font-size="10" text-anchor="end" fill="#333">Deployment</text>
</svg>
SVG;
}

// Create SVG thumbnails from the extracted Mermaid content
echo "\nCreating SVG thumbnails from Mermaid content:\n";
foreach ($extractedContent as $name => $info) {
    createMermaidSvgThumbnail($name, $info['type'], $info['content'], $thumbnailsDir);
}

/**
 * Function to update the HTML file to use the new thumbnails
 */
function updateHtmlToUseNewThumbnails($htmlFile, $diagramsWithContent) {
    // Read the HTML file
    $content = file_get_contents($htmlFile);
    $replacements = 0;

    // For each diagram with content, update the HTML to use the new thumbnail
    foreach ($diagramsWithContent as $name => $info) {
        $thumbnailFilename = strtolower(str_replace(" ", "-", $name)) . "-thumb.svg";

        // Check if the thumbnail already exists in the HTML
        if (strpos($content, $thumbnailFilename) !== false) {
            echo "Thumbnail for '$name' already exists in the HTML\n";
            continue;
        }

        // Look for img tags with the old thumbnail (both PNG and SVG)
        $pattern = '/<img src="thumbnails\/([^"]*?)(?:-thumb\.(?:png|svg))" alt="' . preg_quote($name, '/') . ' thumbnail" width="[^"]*"[^>]*>/';
        $replacement = '<img src="thumbnails/' . $thumbnailFilename . '" alt="' . $name . ' thumbnail" width="80" />';

        // Replace in the HTML content
        $newContent = preg_replace($pattern, $replacement, $content, -1, $count);

        if ($count > 0) {
            $content = $newContent;
            $replacements += $count;
            echo "Updated $count references to '$name' thumbnail\n";
        }
    }

    // Write the updated HTML
    file_put_contents($htmlFile, $content);

    echo "Updated $replacements thumbnail references in the HTML file\n";
    return $replacements;
}

// Update the HTML file to use the new thumbnails
echo "\nUpdating HTML to use new thumbnails:\n";
$replacements = updateHtmlToUseNewThumbnails("index.html", $diagramsWithContent);
