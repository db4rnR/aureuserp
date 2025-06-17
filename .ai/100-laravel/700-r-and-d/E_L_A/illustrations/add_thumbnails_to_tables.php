<?php
/**
 * Script to add thumbnail columns to all tables in the index.html file
 */

// Define the thumbnails directory
$thumbnailsDir = "thumbnails";

/**
 * Function to add thumbnail columns to all tables in the HTML file
 */
function addThumbnailsToTables($htmlFile) {
    // Load the HTML file
    $html = file_get_contents($htmlFile);

    // Create a new DOMDocument
    $dom = new DOMDocument();

    // Preserve whitespace and format
    $dom->preserveWhiteSpace = true;
    $dom->formatOutput = true;

    // Load the HTML, suppressing warnings about HTML5 tags
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    libxml_clear_errors();

    // Find all tables
    $tables = $dom->getElementsByTagName('table');
    $tablesModified = 0;

    // Process each table
    foreach ($tables as $table) {
        // Check if this is a diagram table by looking for "Diagram Name" in the headers
        $hasDiagramColumn = false;
        $hasThumbnailColumn = false;
        $diagramNameColumnIndex = -1;
        $typeColumnIndex = -1;

        // Get the table headers
        $headers = $table->getElementsByTagName('th');

        // Check if this table has headers
        if ($headers->length === 0) {
            continue;
        }

        // Check the headers to see if this is a diagram table
        for ($i = 0; $i < $headers->length; $i++) {
            $headerText = trim($headers->item($i)->textContent);

            if ($headerText === 'Diagram Name') {
                $hasDiagramColumn = true;
                $diagramNameColumnIndex = $i;
            } elseif ($headerText === 'Type') {
                $typeColumnIndex = $i;
            } elseif ($headerText === 'Thumbnail') {
                $hasThumbnailColumn = true;
            }
        }

        // Skip tables that don't have a "Diagram Name" column
        if (!$hasDiagramColumn) {
            continue;
        }

        // Skip tables that already have a "Thumbnail" column
        if ($hasThumbnailColumn) {
            continue;
        }

        echo "Found a table with Diagram Name column but no Thumbnail column\n";

        // Add a "Thumbnail" column to the header row
        $headerRow = $table->getElementsByTagName('tr')->item(0);
        $thumbnailHeader = $dom->createElement('th', 'Thumbnail');

        // Insert the thumbnail header as the first column
        if ($headerRow->firstChild) {
            $headerRow->insertBefore($thumbnailHeader, $headerRow->firstChild);
        } else {
            $headerRow->appendChild($thumbnailHeader);
        }

        // Process each row in the table (skip the header row)
        $rows = $table->getElementsByTagName('tr');
        for ($i = 1; $i < $rows->length; $i++) {
            $row = $rows->item($i);
            $cells = $row->getElementsByTagName('td');

            // Skip rows that don't have enough cells
            if ($cells->length <= $diagramNameColumnIndex) {
                continue;
            }

            // Get the diagram name
            $diagramName = trim($cells->item($diagramNameColumnIndex)->textContent);

            // Skip rows with empty diagram names
            if (empty($diagramName)) {
                continue;
            }

            // Get the diagram type if available
            $diagramType = ($typeColumnIndex >= 0 && $cells->length > $typeColumnIndex)
                ? trim($cells->item($typeColumnIndex)->textContent)
                : '';

            // Create a thumbnail cell
            $thumbnailCell = $dom->createElement('td');

            // Create the thumbnail container
            $thumbnailContainer = $dom->createElement('div');
            $thumbnailContainer->setAttribute('class', 'thumbnail-container');
            $thumbnailContainer->setAttribute('data-diagram-name', $diagramName);
            $thumbnailContainer->setAttribute('data-diagram-type', $diagramType);
            $thumbnailContainer->setAttribute('data-diagram-desc', $diagramName);

            // Create the thumbnail image
            $thumbnailFilename = strtolower(str_replace(' ', '-', $diagramName)) . '-thumb.svg';
            $thumbnailImg = $dom->createElement('img');
            $thumbnailImg->setAttribute('src', "thumbnails/{$thumbnailFilename}");
            $thumbnailImg->setAttribute('alt', "{$diagramName} thumbnail");
            $thumbnailImg->setAttribute('width', "80");

            // Add the image to the container
            $thumbnailContainer->appendChild($thumbnailImg);

            // Add the container to the cell
            $thumbnailCell->appendChild($thumbnailContainer);

            // Insert the thumbnail cell as the first column
            if ($row->firstChild) {
                $row->insertBefore($thumbnailCell, $row->firstChild);
            } else {
                $row->appendChild($thumbnailCell);
            }
        }

        $tablesModified++;
    }

    // Save the modified HTML
    $html = $dom->saveHTML();
    file_put_contents($htmlFile, $html);

    echo "Modified {$tablesModified} tables to add thumbnail columns\n";
    return $tablesModified;
}

/**
 * Function to ensure all diagrams have thumbnails
 */
function ensureAllDiagramsHaveThumbnails($htmlFile, $thumbnailsDir) {
    // Load the HTML file
    $html = file_get_contents($htmlFile);

    // Extract all diagram names from the HTML
    preg_match_all('/data-diagram-name="([^"]+)"/', $html, $matches);
    $diagramNames = array_unique($matches[1]);

    // Define diagram types and their colors
    $diagramTypes = [
        "Flowchart" => "#0077cc",
        "ERD" => "#087f5b",
        "Sequence" => "#e67700",
        "Class" => "#9c36b5",
        "State" => "#c92a2a",
        "Gantt" => "#5f3dc4",
        "Deployment" => "#1864ab",
        "Overview" => "#0077cc", // Default for overview diagrams
    ];

    // Extract all diagram names and types from table rows
    preg_match_all('/<tr>\s*<td>.*?<\/td>\s*<td>([^<]+)<\/td>\s*<td>([^<]+)<\/td>/s', $html, $tableMatches, PREG_SET_ORDER);

    foreach ($tableMatches as $match) {
        if (count($match) >= 3) {
            $diagramName = trim($match[1]);
            $diagramType = trim($match[2]);

            // Skip if not a valid diagram name or type
            if (empty($diagramName) || empty($diagramType)) {
                continue;
            }

            // Add to the list of diagrams
            $diagramNames[] = $diagramName;
        }
    }

    // Remove duplicates
    $diagramNames = array_unique($diagramNames);

    // Count of thumbnails created
    $thumbnailsCreated = 0;

    // Create thumbnails for each diagram
    foreach ($diagramNames as $diagramName) {
        // Create the thumbnail filename
        $thumbnailFilename = strtolower(str_replace(' ', '-', $diagramName)) . '-thumb.svg';
        $thumbnailPath = $thumbnailsDir . '/' . $thumbnailFilename;

        // Skip if the thumbnail already exists
        if (file_exists($thumbnailPath)) {
            continue;
        }

        // Determine the diagram type
        $diagramType = "Overview"; // Default
        foreach ($diagramTypes as $type => $color) {
            if (stripos($diagramName, $type) !== false) {
                $diagramType = $type;
                break;
            }
        }

        // Create a simple SVG thumbnail
        $color = $diagramTypes[$diagramType];
        $description = "Diagram for " . $diagramName;

        // Create a simple SVG thumbnail
        $svgContent = <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
  <!-- Background -->
  <rect width="300" height="200" fill="#f8f9fa" rx="5" ry="5" />

  <!-- Title -->
  <text x="150" y="30" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#333">{$diagramName}</text>

  <!-- Diagram Type Indicator -->
  <rect x="20" y="50" width="260" height="120" fill="{$color}" fill-opacity="0.2" rx="5" ry="5" />

  <!-- Diagram Type Label -->
  <text x="150" y="100" font-family="Arial, sans-serif" font-size="14" text-anchor="middle" fill="#333">{$diagramType}</text>

  <!-- Description -->
  <text x="150" y="180" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#666">{$description}</text>
</svg>
SVG;

        // Write the SVG file
        file_put_contents($thumbnailPath, $svgContent);

        echo "Created thumbnail for {$diagramName}: {$thumbnailPath}\n";
        $thumbnailsCreated++;
    }

    echo "Created {$thumbnailsCreated} new thumbnails\n";
    return $thumbnailsCreated;
}

// Main function
function main() {
    global $thumbnailsDir;

    // Get the HTML file path
    $htmlFile = "index.html";

    // Ensure the thumbnails directory exists
    if (!is_dir($thumbnailsDir)) {
        mkdir($thumbnailsDir, 0755, true);
        echo "Created thumbnails directory\n";
    }

    // Ensure all diagrams have thumbnails
    $thumbnailsCreated = ensureAllDiagramsHaveThumbnails($htmlFile, $thumbnailsDir);

    // Add thumbnail columns to tables
    $tablesModified = addThumbnailsToTables($htmlFile);

    echo "Completed processing: Created {$thumbnailsCreated} thumbnails and modified {$tablesModified} tables\n";
}

// Run the 010-ddl function
main();
