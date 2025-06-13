<?php
/**
 * Script to generate SVG thumbnails for all Mermaid and PlantUML diagrams
 * and update the HTML file to use these thumbnails.
 *
 * This script:
 * 1. Creates subdirectories in the thumbnails directory for Mermaid and PlantUML with light and dark variants
 * 2. Processes all Mermaid diagrams in mermaid/light and mermaid/dark directories
 * 3. Processes all PlantUML diagrams in plantuml/light and plantuml/dark directories
 * 4. Creates SVG thumbnails for each diagram, maintaining the light/dark structure
 * 5. Updates the HTML file to use these thumbnails
 */

// Define directories
$thumbnailsDir = "thumbnails";

// Source directories
$mermaidLightDir = "mermaid/light";
$mermaidDarkDir = "mermaid/dark";
$plantumlLightDir = "plantuml/light";
$plantumlDarkDir = "plantuml/dark";

// Define subdirectories for thumbnails
$mermaidThumbsDir = "$thumbnailsDir/mermaid";
$plantumlThumbsDir = "$thumbnailsDir/plantuml";

// Light and dark subdirectories for thumbnails
$mermaidLightThumbsDir = "$mermaidThumbsDir/light";
$mermaidDarkThumbsDir = "$mermaidThumbsDir/dark";
$plantumlLightThumbsDir = "$plantumlThumbsDir/light";
$plantumlDarkThumbsDir = "$plantumlThumbsDir/dark";

// Ensure the thumbnails directory and subdirectories exist
if (!is_dir($thumbnailsDir)) {
    mkdir($thumbnailsDir, 0755, true);
    echo "Created thumbnails directory\n";
}

// Create Mermaid thumbnails subdirectories
if (!is_dir($mermaidThumbsDir)) {
    mkdir($mermaidThumbsDir, 0755, true);
    echo "Created Mermaid thumbnails directory\n";
}

if (!is_dir($mermaidLightThumbsDir)) {
    mkdir($mermaidLightThumbsDir, 0755, true);
    echo "Created Mermaid light thumbnails subdirectory\n";
}

if (!is_dir($mermaidDarkThumbsDir)) {
    mkdir($mermaidDarkThumbsDir, 0755, true);
    echo "Created Mermaid dark thumbnails subdirectory\n";
}

// Create PlantUML thumbnails subdirectories
if (!is_dir($plantumlThumbsDir)) {
    mkdir($plantumlThumbsDir, 0755, true);
    echo "Created PlantUML thumbnails directory\n";
}

if (!is_dir($plantumlLightThumbsDir)) {
    mkdir($plantumlLightThumbsDir, 0755, true);
    echo "Created PlantUML light thumbnails subdirectory\n";
}

if (!is_dir($plantumlDarkThumbsDir)) {
    mkdir($plantumlDarkThumbsDir, 0755, true);
    echo "Created PlantUML dark thumbnails subdirectory\n";
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
 * Function to extract PlantUML content from a file
 */
function extractPlantUmlContent($filePath) {
    if (!file_exists($filePath)) {
        echo "Warning: File not found: $filePath\n";
        return null;
    }

    $content = file_get_contents($filePath);

    // Look for PlantUML content between @startuml and @enduml tags
    if (preg_match('/@startuml\s*(.*?)\s*@enduml/s', $content, $matches)) {
        return trim($matches[1]);
    }

    // If not found, assume the entire file is PlantUML content
    return trim($content);
}

/**
 * Function to determine Mermaid diagram type from content
 */
function determineMermaidDiagramType($content) {
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
 * Function to determine PlantUML diagram type from content
 */
function determinePlantUmlDiagramType($content) {
    if (strpos($content, 'actor') !== false && strpos($content, '->') !== false) {
        return 'Sequence';
    } elseif (strpos($content, 'class') !== false || strpos($content, 'interface') !== false) {
        return 'Class';
    } elseif (strpos($content, 'entity') !== false || strpos($content, 'enum') !== false) {
        return 'ERD';
    } elseif (strpos($content, 'state') !== false) {
        return 'State';
    } elseif (strpos($content, 'component') !== false) {
        return 'Component';
    } elseif (strpos($content, 'usecase') !== false) {
        return 'UseCase';
    } elseif (strpos($content, 'activity') !== false) {
        return 'Activity';
    } elseif (strpos($content, 'object') !== false) {
        return 'Object';
    } elseif (strpos($content, 'deployment') !== false) {
        return 'Deployment';
    } else {
        return 'Class'; // Default
    }
}

/**
 * Function to create a Mermaid SVG thumbnail
 */
function createMermaidSvgThumbnail($name, $type, $content, $outputDir, $variant = 'light') {
    // Create the filename with variant suffix
    $filename = strtolower(str_replace(" ", "-", $name)) . "-{$variant}-thumb.svg";
    $filepath = $outputDir . "/" . $filename;

    // Set background and text colors based on variant
    $bgColor = ($variant === 'light') ? '#f8f9fa' : '#2d333b';
    $textColor = ($variant === 'light') ? '#333' : '#adbac7';
    $secondaryTextColor = ($variant === 'light') ? '#666' : '#768390';

    // Check if we have the mermaid-cli installed
    exec('which mmdc 2>/dev/null', $output, $returnCode);
    $hasMermaidCli = ($returnCode === 0);

    if ($hasMermaidCli) {
        // Create a temporary file with the Mermaid content
        $tempFile = tempnam(sys_get_temp_dir(), 'mermaid_');
        file_put_contents($tempFile, $content);

        // Export the diagram using Mermaid CLI
        // Add background color parameter for dark mode
        $bgParam = ($variant === 'dark') ? " -b $bgColor" : "";
        $command = "mmdc -i $tempFile -o $filepath -w 300 -H 200$bgParam";
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

    // Create a simple SVG thumbnail based on the diagram type
    $svgContent = '';

    switch ($type) {
        case 'Sequence':
            $svgContent = createSequenceSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'ERD':
            $svgContent = createErdSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'Gantt':
            $svgContent = createGanttSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'Flowchart':
            $svgContent = createFlowchartSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'Class':
            $svgContent = createClassSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'State':
            $svgContent = createStateSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        default:
            // Generic template for other diagram types
            $svgContent = createGenericSvgThumbnail($name, $type, $color, $bgColor, $textColor, $secondaryTextColor);
    }

    // Write the SVG file
    file_put_contents($filepath, $svgContent);

    echo "Created SVG thumbnail: $filepath\n";
    return true;
}

/**
 * Create a generic SVG thumbnail
 */
function createGenericSvgThumbnail($name, $type, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- Diagram Type Indicator -->
  <rect x="20" y="50" width="260" height="120" fill="{$color}" fill-opacity="0.2" rx="5" ry="5" />

  <!-- Diagram Type Label -->
  <text x="150" y="100" font-family="Arial, sans-serif" font-size="14" text-anchor="middle" fill="{$textColor}">{$type}</text>

  <!-- Description -->
  <text x="150" y="180" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="{$secondaryTextColor}">Diagram for {$name}</text>
</svg>
SVG;
}

/**
 * Create a Sequence SVG thumbnail
 */
function createSequenceSvgThumbnail($name, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- Sequence Diagram Elements -->
  <!-- Actor: User -->
  <circle cx="75" cy="60" r="10" fill="{$color}" />
  <line x1="75" y1="70" x2="75" y2="100" stroke="{$color}" stroke-width="2" />
  <line x1="60" y1="85" x2="90" y2="85" stroke="{$color}" stroke-width="2" />
  <line x1="75" y1="100" x2="60" y2="120" stroke="{$color}" stroke-width="2" />
  <line x1="75" y1="100" x2="90" y2="120" stroke="{$color}" stroke-width="2" />
  <text x="75" y="140" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="{$textColor}">User</text>

  <!-- Actor: System -->
  <rect x="210" y="50" width="30" height="20" fill="{$color}" />
  <line x1="225" y1="70" x2="225" y2="120" stroke="{$color}" stroke-width="2" />
  <text x="225" y="140" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="{$textColor}">System</text>

  <!-- Sequence Arrows -->
  <line x1="75" y1="85" x2="215" y2="85" stroke="{$textColor}" stroke-width="1.5" />
  <polygon points="215,85 205,80 205,90" fill="{$textColor}" />
  <text x="145" y="80" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="{$textColor}">Request</text>

  <line x1="225" y1="110" x2="85" y2="110" stroke="{$textColor}" stroke-width="1.5" stroke-dasharray="5,3" />
  <polygon points="85,110 95,105 95,115" fill="{$textColor}" />
  <text x="155" y="105" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="{$textColor}">Response</text>

  <!-- Lifelines -->
  <line x1="75" y1="150" x2="75" y2="180" stroke="{$textColor}" stroke-width="1" stroke-dasharray="4,2" />
  <line x1="225" y1="150" x2="225" y2="180" stroke="{$textColor}" stroke-width="1" stroke-dasharray="4,2" />
</svg>
SVG;
}

/**
 * Create an ERD SVG thumbnail
 */
function createErdSvgThumbnail($name, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- ERD Elements -->
  <rect x="60" y="70" width="80" height="40" fill="{$color}" stroke="{$textColor}" stroke-width="1" rx="3" ry="3" />
  <text x="100" y="95" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Entity 1</text>

  <rect x="160" y="70" width="80" height="40" fill="{$color}" stroke="{$textColor}" stroke-width="1" rx="3" ry="3" />
  <text x="200" y="95" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Entity 2</text>

  <line x1="140" y1="90" x2="160" y2="90" stroke="{$textColor}" stroke-width="1.5" />
  <text x="150" y="85" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="{$textColor}">1:N</text>

  <rect x="60" y="140" width="80" height="40" fill="{$color}" stroke="{$textColor}" stroke-width="1" rx="3" ry="3" />
  <text x="100" y="165" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Entity 3</text>

  <rect x="160" y="140" width="80" height="40" fill="{$color}" stroke="{$textColor}" stroke-width="1" rx="3" ry="3" />
  <text x="200" y="165" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Entity 4</text>

  <line x1="100" y1="110" x2="100" y2="140" stroke="{$textColor}" stroke-width="1.5" />
  <text x="110" y="125" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="{$textColor}">1:1</text>

  <line x1="200" y1="110" x2="200" y2="140" stroke="{$textColor}" stroke-width="1.5" />
  <text x="210" y="125" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="{$textColor}">N:M</text>
</svg>
SVG;
}

/**
 * Create a Flowchart SVG thumbnail
 */
function createFlowchartSvgThumbnail($name, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- Flowchart Elements -->
  <rect x="120" y="50" width="60" height="30" fill="{$color}" rx="3" ry="3" />
  <text x="150" y="70" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Start</text>

  <line x1="150" y1="80" x2="150" y2="100" stroke="{$textColor}" stroke-width="1.5" />
  <polygon points="150,100 145,95 155,95" fill="{$textColor}" />

  <rect x="100" y="100" width="100" height="40" fill="{$color}" fill-opacity="0.8" rx="3" ry="3" />
  <text x="150" y="125" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Process</text>

  <line x1="150" y1="140" x2="150" y2="160" stroke="{$textColor}" stroke-width="1.5" />
  <polygon points="150,160 145,155 155,155" fill="{$textColor}" />

  <ellipse cx="150" cy="170" rx="30" ry="15" fill="{$color}" />
  <text x="150" y="175" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">End</text>
</svg>
SVG;
}

/**
 * Create a Gantt SVG thumbnail
 */
function createGanttSvgThumbnail($name, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- Gantt Chart Elements -->
  <!-- Timeline Header -->
  <line x1="40" y1="50" x2="260" y2="50" stroke="{$textColor}" stroke-width="1" />
  <text x="40" y="45" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="{$textColor}">Q1</text>
  <text x="100" y="45" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="{$textColor}">Q2</text>
  <text x="160" y="45" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="{$textColor}">Q3</text>
  <text x="220" y="45" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="{$textColor}">Q4</text>

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
  <text x="30" y="74" font-family="Arial, sans-serif" font-size="10" text-anchor="end" fill="{$textColor}">Planning</text>
  <text x="30" y="104" font-family="Arial, sans-serif" font-size="10" text-anchor="end" fill="{$textColor}">Development</text>
  <text x="30" y="134" font-family="Arial, sans-serif" font-size="10" text-anchor="end" fill="{$textColor}">Testing</text>
  <text x="30" y="164" font-family="Arial, sans-serif" font-size="10" text-anchor="end" fill="{$textColor}">Deployment</text>
</svg>
SVG;
}

/**
 * Create a Class SVG thumbnail
 */
function createClassSvgThumbnail($name, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- Class Diagram Elements -->
  <rect x="60" y="60" width="80" height="100" fill="{$color}" fill-opacity="0.2" stroke="{$color}" stroke-width="2" />
  <rect x="60" y="60" width="80" height="25" fill="{$color}" stroke="{$color}" stroke-width="2" />
  <text x="100" y="77" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Class A</text>

  <line x1="60" y1="110" x2="140" y2="110" stroke="{$color}" stroke-width="1" />
  <text x="65" y="95" font-family="Arial, sans-serif" font-size="10" fill="{$textColor}">- property1</text>
  <text x="65" y="125" font-family="Arial, sans-serif" font-size="10" fill="{$textColor}">+ method1()</text>
  <text x="65" y="140" font-family="Arial, sans-serif" font-size="10" fill="{$textColor}">+ method2()</text>

  <rect x="160" y="60" width="80" height="100" fill="{$color}" fill-opacity="0.2" stroke="{$color}" stroke-width="2" />
  <rect x="160" y="60" width="80" height="25" fill="{$color}" stroke="{$color}" stroke-width="2" />
  <text x="200" y="77" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Class B</text>

  <line x1="160" y1="110" x2="240" y2="110" stroke="{$color}" stroke-width="1" />
  <text x="165" y="95" font-family="Arial, sans-serif" font-size="10" fill="{$textColor}">- property1</text>
  <text x="165" y="125" font-family="Arial, sans-serif" font-size="10" fill="{$textColor}">+ method1()</text>
  <text x="165" y="140" font-family="Arial, sans-serif" font-size="10" fill="{$textColor}">+ method2()</text>

  <line x1="140" y1="90" x2="160" y2="90" stroke="{$textColor}" stroke-width="1.5" />
  <polygon points="160,90 150,85 150,95" fill="{$textColor}" />
</svg>
SVG;
}

/**
 * Create a State SVG thumbnail
 */
function createStateSvgThumbnail($name, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- State Diagram Elements -->
  <circle cx="75" cy="80" r="15" fill="#000" />
  <circle cx="75" cy="80" r="12" fill="#fff" />

  <ellipse cx="150" cy="80" rx="40" ry="25" fill="{$color}" />
  <text x="150" y="85" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">State 1</text>

  <ellipse cx="150" cy="150" rx="40" ry="25" fill="{$color}" />
  <text x="150" y="155" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">State 2</text>

  <ellipse cx="225" cy="80" rx="40" ry="25" fill="{$color}" />
  <text x="225" y="85" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">State 3</text>

  <line x1="90" y1="80" x2="110" y2="80" stroke="{$textColor}" stroke-width="1.5" />
  <polygon points="110,80 100,75 100,85" fill="{$textColor}" />

  <line x1="150" y1="105" x2="150" y2="125" stroke="{$textColor}" stroke-width="1.5" />
  <polygon points="150,125 145,115 155,115" fill="{$textColor}" />

  <line x1="190" y1="80" x2="185" y2="80" stroke="{$textColor}" stroke-width="1.5" />
  <polygon points="185,80 195,75 195,85" fill="{$textColor}" />
</svg>
SVG;
}

/**
 * Function to create a PlantUML SVG thumbnail
 */
function createPlantUmlSvgThumbnail($name, $type, $content, $outputDir, $variant = 'light') {
    // Create the filename with variant suffix
    $filename = strtolower(str_replace(" ", "-", $name)) . "-{$variant}-thumb.svg";
    $filepath = $outputDir . "/" . $filename;

    // Set background and text colors based on variant
    $bgColor = ($variant === 'light') ? '#f8f9fa' : '#2d333b';
    $textColor = ($variant === 'light') ? '#333' : '#adbac7';
    $secondaryTextColor = ($variant === 'light') ? '#666' : '#768390';

    // Check if we have the plantuml command installed
    exec('which plantuml 2>/dev/null', $output, $returnCode);
    $hasPlantUml = ($returnCode === 0);

    if ($hasPlantUml) {
        // Create a temporary file with the PlantUML content
        $tempFile = tempnam(sys_get_temp_dir(), 'plantuml_');
        file_put_contents($tempFile, "@startuml\n" . $content . "\n@enduml");

        // Export the diagram using PlantUML
        $command = "plantuml -tsvg -o " . dirname($filepath) . " $tempFile";
        exec($command, $output, $returnCode);

        // Rename the output file to our desired filename
        $generatedFile = dirname($filepath) . "generate_all_thumbnails.php/" . basename($tempFile) . ".svg";
        if (file_exists($generatedFile)) {
            rename($generatedFile, $filepath);

            // Clean up the temporary file
            unlink($tempFile);

            echo "Created PlantUML SVG thumbnail for '$name' using PlantUML\n";
            return true;
        }

        // Clean up the temporary file
        unlink($tempFile);
    }

    // If PlantUML is not available or export failed, create a simple SVG thumbnail
    echo "Creating simple SVG thumbnail for '$name'\n";

    // Define colors for different diagram types
    $diagramColors = [
        "Class" => "#9c36b5",
        "Sequence" => "#e67700",
        "ERD" => "#087f5b",
        "Component" => "#0077cc",
        "UseCase" => "#5f3dc4",
        "Activity" => "#c92a2a",
        "State" => "#c92a2a",
        "Object" => "#1864ab",
        "Deployment" => "#1864ab",
        "Overview" => "#0077cc", // Default for overview diagrams
    ];

    // Determine the color based on the diagram type
    $color = isset($diagramColors[$type]) ? $diagramColors[$type] : "#0077cc";

    // Create a simple SVG thumbnail based on the diagram type
    $svgContent = '';

    switch ($type) {
        case 'Sequence':
            $svgContent = createSequenceSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'ERD':
            $svgContent = createErdSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'Class':
            $svgContent = createClassSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'State':
            $svgContent = createStateSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'Component':
            $svgContent = createComponentSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'UseCase':
            $svgContent = createUseCaseSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'Activity':
            $svgContent = createActivitySvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        case 'Deployment':
            $svgContent = createDeploymentSvgThumbnail($name, $color, $bgColor, $textColor, $secondaryTextColor);
            break;
        default:
            // Generic template for other diagram types
            $svgContent = createGenericSvgThumbnail($name, $type, $color, $bgColor, $textColor, $secondaryTextColor);
    }

    // Write the SVG file
    file_put_contents($filepath, $svgContent);

    echo "Created SVG thumbnail: $filepath\n";
    return true;
}

/**
 * Create a Component SVG thumbnail
 */
function createComponentSvgThumbnail($name, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- Component Diagram Elements -->
  <rect x="60" y="60" width="80" height="50" fill="{$color}" fill-opacity="0.8" rx="3" ry="3" />
  <text x="100" y="90" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Component 1</text>

  <rect x="160" y="60" width="80" height="50" fill="{$color}" fill-opacity="0.8" rx="3" ry="3" />
  <text x="200" y="90" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Component 2</text>

  <rect x="60" y="130" width="80" height="50" fill="{$color}" fill-opacity="0.8" rx="3" ry="3" />
  <text x="100" y="160" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Component 3</text>

  <rect x="160" y="130" width="80" height="50" fill="{$color}" fill-opacity="0.8" rx="3" ry="3" />
  <text x="200" y="160" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Component 4</text>

  <line x1="140" y1="85" x2="160" y2="85" stroke="{$textColor}" stroke-width="1.5" />
  <line x1="100" y1="110" x2="100" y2="130" stroke="{$textColor}" stroke-width="1.5" />
  <line x1="200" y1="110" x2="200" y2="130" stroke="{$textColor}" stroke-width="1.5" />
  <line x1="140" y1="155" x2="160" y2="155" stroke="{$textColor}" stroke-width="1.5" />
</svg>
SVG;
}

/**
 * Create a UseCase SVG thumbnail
 */
function createUseCaseSvgThumbnail($name, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- Use Case Diagram Elements -->
  <!-- Actor -->
  <circle cx="50" cy="100" r="10" fill="{$color}" />
  <line x1="50" y1="110" x2="50" y2="140" stroke="{$color}" stroke-width="2" />
  <line x1="30" y1="125" x2="70" y2="125" stroke="{$color}" stroke-width="2" />
  <line x1="50" y1="140" x2="30" y2="160" stroke="{$color}" stroke-width="2" />
  <line x1="50" y1="140" x2="70" y2="160" stroke="{$color}" stroke-width="2" />
  <text x="50" y="180" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="{$textColor}">Actor</text>

  <!-- System Boundary -->
  <rect x="100" y="50" width="180" height="120" fill="none" stroke="{$textColor}" stroke-width="1" rx="10" ry="10" stroke-dasharray="5,3" />
  <text x="190" y="70" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="{$textColor}">System</text>

  <!-- Use Cases -->
  <ellipse cx="150" cy="100" rx="40" ry="20" fill="{$color}" fill-opacity="0.8" />
  <text x="150" y="105" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Use Case 1</text>

  <ellipse cx="230" cy="100" rx="40" ry="20" fill="{$color}" fill-opacity="0.8" />
  <text x="230" y="105" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Use Case 2</text>

  <ellipse cx="190" cy="140" rx="40" ry="20" fill="{$color}" fill-opacity="0.8" />
  <text x="190" y="145" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Use Case 3</text>

  <!-- Connections -->
  <line x1="70" y1="100" x2="110" y2="100" stroke="{$textColor}" stroke-width="1" />
</svg>
SVG;
}

/**
 * Create an Activity SVG thumbnail
 */
function createActivitySvgThumbnail($name, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- Activity Diagram Elements -->
  <circle cx="150" cy="50" r="10" fill="#000" />
  <circle cx="150" cy="50" r="8" fill="#fff" />

  <rect x="120" y="70" width="60" height="30" fill="{$color}" rx="3" ry="3" />
  <text x="150" y="90" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Activity 1</text>

  <polygon points="150,110 140,120 160,120" fill="{$color}" />
  <text x="150" y="118" font-family="Arial, sans-serif" font-size="8" text-anchor="middle" fill="#fff">?</text>

  <rect x="80" y="130" width="60" height="30" fill="{$color}" rx="3" ry="3" />
  <text x="110" y="150" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Activity 2</text>

  <rect x="160" y="130" width="60" height="30" fill="{$color}" rx="3" ry="3" />
  <text x="190" y="150" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Activity 3</text>

  <circle cx="150" cy="180" r="12" fill="#000" />
  <circle cx="150" cy="180" r="8" fill="#000" />

  <!-- Connections -->
  <line x1="150" y1="60" x2="150" y2="70" stroke="{$textColor}" stroke-width="1.5" />
  <line x1="150" y1="100" x2="150" y2="110" stroke="{$textColor}" stroke-width="1.5" />
  <line x1="140" y1="120" x2="110" y2="130" stroke="{$textColor}" stroke-width="1.5" />
  <line x1="160" y1="120" x2="190" y2="130" stroke="{$textColor}" stroke-width="1.5" />
  <line x1="110" y1="160" x2="140" y2="180" stroke="{$textColor}" stroke-width="1.5" />
  <line x1="190" y1="160" x2="160" y2="180" stroke="{$textColor}" stroke-width="1.5" />
</svg>
SVG;
}

/**
 * Create a Deployment SVG thumbnail
 */
function createDeploymentSvgThumbnail($name, $color, $bgColor = '#f8f9fa', $textColor = '#333', $secondaryTextColor = '#666') {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="{$bgColor}" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="{$textColor}">{$name}</text>

  <!-- Deployment Diagram Elements -->
  <!-- Node 1 -->
  <path d="M60,60 L80,40 L180,40 L180,120 L160,140 L60,140 Z" fill="{$color}" fill-opacity="0.2" stroke="{$color}" stroke-width="2" />
  <path d="M160,140 L180,120 L180,40 L160,60 Z" fill="{$color}" fill-opacity="0.3" stroke="{$color}" stroke-width="2" />
  <path d="M60,60 L160,60 L180,40" fill="none" stroke="{$color}" stroke-width="2" />
  <text x="120" y="100" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="{$textColor}">Server</text>

  <!-- Component inside Node 1 -->
  <rect x="80" y="70" width="80" height="40" fill="{$color}" fill-opacity="0.8" rx="3" ry="3" />
  <text x="120" y="95" font-family="Arial, sans-serif" font-size="10" text-anchor="middle" fill="#fff">Component</text>

  <!-- Node 2 -->
  <path d="M200,100 L220,80 L260,80 L260,160 L240,180 L200,180 Z" fill="{$color}" fill-opacity="0.2" stroke="{$color}" stroke-width="2" />
  <path d="M240,180 L260,160 L260,80 L240,100 Z" fill="{$color}" fill-opacity="0.3" stroke="{$color}" stroke-width="2" />
  <path d="M200,100 L240,100 L260,80" fill="none" stroke="{$color}" stroke-width="2" />
  <text x="230" y="140" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="{$textColor}">Database</text>

  <!-- Connection -->
  <line x1="160" y1="90" x2="200" y2="120" stroke="{$textColor}" stroke-width="1.5" stroke-dasharray="5,3" />
</svg>
SVG;
}

/**
 * Function to update old format references in the HTML file
 */
function updateOldFormatReferences($htmlFile) {
    // Read the HTML file
    $content = file_get_contents($htmlFile);
    $replacements = 0;

    // Regular expression to find old format thumbnail references
    $pattern = '/<img src="thumbnails\/([^\/>]+)-thumb\.svg" alt="([^"]+)" width="([^"]+)"[^>]*>/i';

    // Find all matches
    preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

    // Process each match
    foreach ($matches as $match) {
        $oldPath = $match[0];
        $baseName = $match[1];
        $altText = $match[2];
        $width = $match[3];

        // Create light and dark versions
        $lightPath = "<img src=\"thumbnails/mermaid/light/{$baseName}-light-thumb.svg\" alt=\"{$altText}\" width=\"{$width}\" />";

        // Replace in the content
        $content = str_replace($oldPath, $lightPath, $content);
        $replacements++;

        echo "Updated old format reference to '{$baseName}' thumbnail\n";
    }

    // Write the updated content back to the file
    file_put_contents($htmlFile, $content);

    echo "Updated $replacements old format thumbnail references in the HTML file\n";

    // Clean up old format thumbnails
    cleanupOldFormatThumbnails();
}

/**
 * Function to clean up old format thumbnails
 */
function cleanupOldFormatThumbnails() {
    global $thumbnailsDir, $mermaidThumbsDir, $plantumlThumbsDir;
    global $mermaidLightThumbsDir, $mermaidDarkThumbsDir, $plantumlLightThumbsDir, $plantumlDarkThumbsDir;

    $dirs = [$thumbnailsDir, $mermaidThumbsDir, $plantumlThumbsDir, $mermaidLightThumbsDir, $mermaidDarkThumbsDir, $plantumlLightThumbsDir, $plantumlDarkThumbsDir];
    $removed = 0;

    foreach ($dirs as $dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                // Check if it's an old format thumbnail (without light/dark in the name)
                if (preg_match('/^(.+)-thumb\.svg$/', $file, $matches) && !preg_match('/-(light|dark)-thumb\.svg$/', $file)) {
                    $oldPath = $dir . "/" . $file;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                        $removed++;
                        echo "Removed old format thumbnail: $oldPath\n";
                    }
                }
            }
        }
    }

    echo "Removed $removed old format thumbnails\n";
}

/**
 * Function to update the HTML file to use the new thumbnails
 */
function updateHtmlToUseNewThumbnails($htmlFile, $thumbnailsDir, $diagramFiles) {
    global $mermaidThumbsDir, $plantumlThumbsDir;
    global $mermaidLightThumbsDir, $mermaidDarkThumbsDir, $plantumlLightThumbsDir, $plantumlDarkThumbsDir;

    // First, let's update any remaining old format references in the HTML file
    updateOldFormatReferences($htmlFile);

    // Read the HTML file
    $content = file_get_contents($htmlFile);
    $replacements = 0;

    // Get all existing thumbnails from all directories
    $existingThumbnails = [];

    // Check main thumbnails directory
    $files = scandir($thumbnailsDir);
    foreach ($files as $file) {
        if (preg_match('/^(.+)-thumb\.svg$/', $file, $matches)) {
            $existingThumbnails[] = $matches[1];
        }
    }

    // Check all Mermaid thumbnails subdirectories
    $mermaidDirs = [$mermaidThumbsDir, $mermaidLightThumbsDir, $mermaidDarkThumbsDir];
    foreach ($mermaidDirs as $dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if (preg_match('/^(.+)-thumb\.svg$/', $file, $matches)) {
                    $existingThumbnails[] = $matches[1];
                }
            }
        }
    }

    // Check all PlantUML thumbnails subdirectories
    $plantumlDirs = [$plantumlThumbsDir, $plantumlLightThumbsDir, $plantumlDarkThumbsDir];
    foreach ($plantumlDirs as $dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if (preg_match('/^(.+)-thumb\.svg$/', $file, $matches)) {
                    $existingThumbnails[] = $matches[1];
                }
            }
        }
    }

    // For each diagram file, extract the diagram name and update the HTML
    foreach ($diagramFiles as $file) {
        // Extract the diagram name from the file name (handle both Mermaid and PlantUML)
        if (preg_match('/^(.+)-(light|dark)\.(md|puml)$/', $file, $matches)) {
            $baseName = $matches[1];
            $variant = $matches[2]; // light or dark
            $extension = $matches[3]; // md or puml
            $diagramName = str_replace('-', ' ', $baseName);
            $diagramName = ucwords($diagramName);

            // Create the thumbnail filename with variant suffix
            $thumbnailFilename = strtolower(str_replace(" ", "-", $diagramName)) . "-{$variant}-thumb.svg";

            // Determine the subdirectory based on the file extension and variant
            $subDir = ($extension === 'md') ? 'mermaid' : 'plantuml';
            $thumbnailPath = "thumbnails/$subDir/$variant/$thumbnailFilename";

            // Check if the thumbnail already exists in the HTML
            if (strpos($content, $thumbnailPath) !== false) {
                echo "Thumbnail for '$diagramName' ($variant) already exists in the HTML\n";
                continue;
            }

            // Look for img tags with placeholder thumbnails
            $pattern = '/<img src="thumbnails\/[^"]*" alt="' . preg_quote($diagramName, '/') . ' thumbnail" width="[^"]*"[^>]*>/';
            $replacement = '<img src="' . $thumbnailPath . '" alt="' . $diagramName . ' thumbnail" width="80" />';

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
    global $thumbnailsDir, $mermaidLightDir, $mermaidDarkDir, $plantumlLightDir, $plantumlDarkDir;
    global $mermaidThumbsDir, $plantumlThumbsDir, $mermaidLightThumbsDir, $mermaidDarkThumbsDir, $plantumlLightThumbsDir, $plantumlDarkThumbsDir;

    // Process Mermaid diagrams
    processMermaidDiagrams();

    // Process PlantUML diagrams
    processPlantUmlDiagrams();

    // Update the HTML file to use the new thumbnails
    echo "\nUpdating HTML to use new thumbnails:\n";
    $allDiagramFiles = [];

    // Get all Mermaid light diagram files
    $mermaidLightFiles = scandir($mermaidLightDir);
    $mermaidLightFiles = array_filter($mermaidLightFiles, function($file) {
        return preg_match('/\.md$/', $file);
    });
    $allDiagramFiles = array_merge($allDiagramFiles, $mermaidLightFiles);

    // Get all Mermaid dark diagram files
    $mermaidDarkFiles = scandir($mermaidDarkDir);
    $mermaidDarkFiles = array_filter($mermaidDarkFiles, function($file) {
        return preg_match('/\.md$/', $file);
    });
    $allDiagramFiles = array_merge($allDiagramFiles, $mermaidDarkFiles);

    // Get all PlantUML light diagram files
    $plantumlLightFiles = scandir($plantumlLightDir);
    $plantumlLightFiles = array_filter($plantumlLightFiles, function($file) {
        return preg_match('/\.puml$/', $file);
    });
    $allDiagramFiles = array_merge($allDiagramFiles, $plantumlLightFiles);

    // Get all PlantUML dark diagram files
    $plantumlDarkFiles = scandir($plantumlDarkDir);
    $plantumlDarkFiles = array_filter($plantumlDarkFiles, function($file) {
        return preg_match('/\.puml$/', $file);
    });
    $allDiagramFiles = array_merge($allDiagramFiles, $plantumlDarkFiles);

    $replacements = updateHtmlToUseNewThumbnails("index.html", $thumbnailsDir, $allDiagramFiles);

    echo "\nCompleted processing diagrams and updated $replacements thumbnail references\n";
}

/**
 * Function to process Mermaid diagrams
 */
function processMermaidDiagrams() {
    global $mermaidLightDir, $mermaidDarkDir, $mermaidLightThumbsDir, $mermaidDarkThumbsDir;

    // Process light theme diagrams
    $lightProcessed = processMermaidVariant($mermaidLightDir, $mermaidLightThumbsDir, 'light');

    // Process dark theme diagrams
    $darkProcessed = processMermaidVariant($mermaidDarkDir, $mermaidDarkThumbsDir, 'dark');

    echo "Processed $lightProcessed light and $darkProcessed dark Mermaid diagrams\n";
}

/**
 * Function to process a specific variant (light/dark) of Mermaid diagrams
 */
function processMermaidVariant($sourceDir, $outputDir, $variant) {
    // Get all Mermaid diagram files
    $diagramFiles = scandir($sourceDir);
    $diagramFiles = array_filter($diagramFiles, function($file) {
        return preg_match('/\.md$/', $file);
    });

    echo "Found " . count($diagramFiles) . " Mermaid $variant diagram files\n";

    // Process each diagram file
    $processedDiagrams = 0;
    foreach ($diagramFiles as $file) {
        // Extract the diagram name from the file name
        if (preg_match('/^(.+)-' . $variant . '\.md$/', $file, $matches)) {
            $baseName = $matches[1];
            $diagramName = str_replace('-', ' ', $baseName);
            $diagramName = ucwords($diagramName);

            // Create the thumbnail filename with variant suffix
            $thumbnailFilename = strtolower(str_replace(" ", "-", $diagramName)) . "-{$variant}-thumb.svg";
            $thumbnailPath = $outputDir . "/" . $thumbnailFilename;

            // Check if the thumbnail already exists and is a real diagram (not a placeholder)
            if (file_exists($thumbnailPath) && filesize($thumbnailPath) > 5000) {
                echo "Mermaid $variant thumbnail for '$diagramName' already exists and is not a placeholder\n";
                continue;
            }

            // Also check for old format filename for backward compatibility
            $oldFormatFilename = strtolower(str_replace(" ", "-", $diagramName)) . "-thumb.svg";
            $oldFormatPath = $outputDir . "/" . $oldFormatFilename;
            if (file_exists($oldFormatPath) && filesize($oldFormatPath) > 5000) {
                // Rename the old format file to the new format
                rename($oldFormatPath, $thumbnailPath);
                echo "Renamed old format thumbnail to new format for '$diagramName' ($variant)\n";
                continue;
            }

            // Extract the Mermaid content
            $filePath = $sourceDir . "/" . $file;
            $content = extractMermaidContent($filePath);

            if ($content) {
                // Determine the diagram type
                $diagramType = determineMermaidDiagramType($content);

                // Create the SVG thumbnail
                createMermaidSvgThumbnail($diagramName, $diagramType, $content, $outputDir, $variant);
                $processedDiagrams++;
            }
        }
    }

    return $processedDiagrams;
}

/**
 * Function to process PlantUML diagrams
 */
function processPlantUmlDiagrams() {
    global $plantumlLightDir, $plantumlDarkDir, $plantumlLightThumbsDir, $plantumlDarkThumbsDir;

    // Process light theme diagrams
    $lightProcessed = processPlantUmlVariant($plantumlLightDir, $plantumlLightThumbsDir, 'light');

    // Process dark theme diagrams
    $darkProcessed = processPlantUmlVariant($plantumlDarkDir, $plantumlDarkThumbsDir, 'dark');

    echo "Processed $lightProcessed light and $darkProcessed dark PlantUML diagrams\n";
}

/**
 * Function to process a specific variant (light/dark) of PlantUML diagrams
 */
function processPlantUmlVariant($sourceDir, $outputDir, $variant) {
    // Get all PlantUML diagram files
    $diagramFiles = scandir($sourceDir);
    $diagramFiles = array_filter($diagramFiles, function($file) {
        return preg_match('/\.puml$/', $file);
    });

    echo "Found " . count($diagramFiles) . " PlantUML $variant diagram files\n";

    // Process each diagram file
    $processedDiagrams = 0;
    foreach ($diagramFiles as $file) {
        // Extract the diagram name from the file name
        if (preg_match('/^(.+)-' . $variant . '\.puml$/', $file, $matches)) {
            $baseName = $matches[1];
            $diagramName = str_replace('-', ' ', $baseName);
            $diagramName = ucwords($diagramName);

            // Create the thumbnail filename with variant suffix
            $thumbnailFilename = strtolower(str_replace(" ", "-", $diagramName)) . "-{$variant}-thumb.svg";
            $thumbnailPath = $outputDir . "/" . $thumbnailFilename;

            // Check if the thumbnail already exists and is a real diagram (not a placeholder)
            if (file_exists($thumbnailPath) && filesize($thumbnailPath) > 5000) {
                echo "PlantUML $variant thumbnail for '$diagramName' already exists and is not a placeholder\n";
                continue;
            }

            // Also check for old format filename for backward compatibility
            $oldFormatFilename = strtolower(str_replace(" ", "-", $diagramName)) . "-thumb.svg";
            $oldFormatPath = $outputDir . "/" . $oldFormatFilename;
            if (file_exists($oldFormatPath) && filesize($oldFormatPath) > 5000) {
                // Rename the old format file to the new format
                rename($oldFormatPath, $thumbnailPath);
                echo "Renamed old format thumbnail to new format for '$diagramName' ($variant)\n";
                continue;
            }

            // Extract the PlantUML content
            $filePath = $sourceDir . "/" . $file;
            $content = extractPlantUmlContent($filePath);

            if ($content) {
                // Determine the diagram type
                $diagramType = determinePlantUmlDiagramType($content);

                // Create the SVG thumbnail
                createPlantUmlSvgThumbnail($diagramName, $diagramType, $content, $outputDir, $variant);
                $processedDiagrams++;
            }
        }
    }

    return $processedDiagrams;
}

// Run the main function
main();
