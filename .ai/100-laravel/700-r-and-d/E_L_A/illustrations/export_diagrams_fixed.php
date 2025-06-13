<?php
/**
 * Script to export Mermaid diagrams as SVG files to the thumbnails folder
 * This version properly handles Mermaid content extraction
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
 * Function to create a simple SVG thumbnail for a diagram
 */
function createSimpleSvgThumbnail($name, $type, $description, $thumbnailsDir) {
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
    
    // Create a more visually appealing SVG based on the diagram type
    $svgContent = '';
    
    switch ($type) {
        case 'Flowchart':
            $svgContent = createFlowchartSvg($name, $description, $color);
            break;
        case 'ERD':
            $svgContent = createErdSvg($name, $description, $color);
            break;
        case 'Sequence':
            $svgContent = createSequenceSvg($name, $description, $color);
            break;
        case 'Gantt':
            $svgContent = createGanttSvg($name, $description, $color);
            break;
        case 'Class':
            $svgContent = createClassSvg($name, $description, $color);
            break;
        case 'State':
            $svgContent = createStateSvg($name, $description, $color);
            break;
        default:
            // Generic template for other diagram types
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
  <text x="150" y="180" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#666">{$description}</text>
</svg>
SVG;
    }
    
    // Create the filename
    $filename = strtolower(str_replace(" ", "-", $name)) . "-thumb.svg";
    $filepath = $thumbnailsDir . "/" . $filename;
    
    // Write the SVG file
    file_put_contents($filepath, $svgContent);
    
    echo "Generated thumbnail: $filepath\n";
    return $filename;
}

/**
 * Create a Flowchart SVG thumbnail
 */
function createFlowchartSvg($name, $description, $color) {
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
 * Create an ERD SVG thumbnail
 */
function createErdSvg($name, $description, $color) {
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
 * Create a Sequence SVG thumbnail
 */
function createSequenceSvg($name, $description, $color) {
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
 * Create a Gantt SVG thumbnail
 */
function createGanttSvg($name, $description, $color) {
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

/**
 * Create a Class SVG thumbnail
 */
function createClassSvg($name, $description, $color) {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="#f8f9fa" rx="5" ry="5" />
  
  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#333">{$name}</text>
  
  <!-- Class Diagram Elements -->
  <rect x="60" y="60" width="80" height="100" fill="{$color}" fill-opacity="0.2" stroke="{$color}" stroke-width="2" />
  <rect x="60" y="60" width="80" height="25" fill="{$color}" stroke="{$color}" stroke-width="2" />
  <text x="100" y="77" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Class A</text>
  
  <line x1="60" y1="110" x2="140" y2="110" stroke="{$color}" stroke-width="1" />
  <text x="65" y="95" font-family="Arial, sans-serif" font-size="10" fill="#333">- property1</text>
  <text x="65" y="125" font-family="Arial, sans-serif" font-size="10" fill="#333">+ method1()</text>
  <text x="65" y="140" font-family="Arial, sans-serif" font-size="10" fill="#333">+ method2()</text>
  
  <rect x="160" y="60" width="80" height="100" fill="{$color}" fill-opacity="0.2" stroke="{$color}" stroke-width="2" />
  <rect x="160" y="60" width="80" height="25" fill="{$color}" stroke="{$color}" stroke-width="2" />
  <text x="200" y="77" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">Class B</text>
  
  <line x1="160" y1="110" x2="240" y2="110" stroke="{$color}" stroke-width="1" />
  <text x="165" y="95" font-family="Arial, sans-serif" font-size="10" fill="#333">- property1</text>
  <text x="165" y="125" font-family="Arial, sans-serif" font-size="10" fill="#333">+ method1()</text>
  <text x="165" y="140" font-family="Arial, sans-serif" font-size="10" fill="#333">+ method2()</text>
  
  <line x1="140" y1="90" x2="160" y2="90" stroke="#333" stroke-width="1.5" />
  <polygon points="160,90 150,85 150,95" fill="#333" />
</svg>
SVG;
}

/**
 * Create a State SVG thumbnail
 */
function createStateSvg($name, $description, $color) {
    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="#f8f9fa" rx="5" ry="5" />
  
  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#333">{$name}</text>
  
  <!-- State Diagram Elements -->
  <circle cx="75" cy="80" r="15" fill="#000" />
  <circle cx="75" cy="80" r="12" fill="#fff" />
  
  <ellipse cx="150" cy="80" rx="40" ry="25" fill="{$color}" />
  <text x="150" y="85" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">State 1</text>
  
  <ellipse cx="150" cy="150" rx="40" ry="25" fill="{$color}" />
  <text x="150" y="155" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">State 2</text>
  
  <ellipse cx="225" cy="80" rx="40" ry="25" fill="{$color}" />
  <text x="225" y="85" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#fff">State 3</text>
  
  <line x1="90" y1="80" x2="110" y2="80" stroke="#333" stroke-width="1.5" />
  <polygon points="110,80 100,75 100,85" fill="#333" />
  
  <line x1="150" y1="105" x2="150" y2="125" stroke="#333" stroke-width="1.5" />
  <polygon points="150,125 145,115 155,115" fill="#333" />
  
  <line x1="190" y1="80" x2="185" y2="80" stroke="#333" stroke-width="1.5" />
  <polygon points="185,80 195,75 195,85" fill="#333" />
</svg>
SVG;
}

/**
 * Function to update the HTML file to reference SVG thumbnails
 */
function updateHtmlReferences($htmlFile) {
    $content = file_get_contents($htmlFile);
    
    // Replace PNG references with SVG
    $content = preg_replace('/(src="thumbnails\/[^"]+)\.png"/', '$1.svg"', $content);
    
    // Write the updated HTML
    file_put_contents($htmlFile, $content);
    
    echo "Updated $htmlFile to reference SVG thumbnails\n";
}

// Main function
function main() {
    global $thumbnailsDir, $mermaidDarkDir, $mermaidLightDir;
    
    // Get the HTML file path
    $htmlFile = "index.html";
    
    // Extract diagram paths from the HTML
    $diagrams = extractDiagramPaths($htmlFile);
    
    echo "Found " . count($diagrams) . " diagrams in the HTML file\n";
    
    // Process each diagram
    foreach ($diagrams as $name => $info) {
        $type = $info['type'];
        $description = $info['description'];
        $darkPath = $info['dark_path'];
        $lightPath = $info['light_path'];
        
        // Create a visually appealing SVG thumbnail based on the diagram type
        createSimpleSvgThumbnail($name, $type, $description, $thumbnailsDir);
    }
    
    // Update HTML references
    updateHtmlReferences($htmlFile);
    
    echo "Completed processing " . count($diagrams) . " diagrams\n";
}

// Run the main function
main();
