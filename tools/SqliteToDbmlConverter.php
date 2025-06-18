#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * SQLite to DBML Converter - PHP Version
 * Fixed version that handles older SQLite databases
 */
final class SqliteToDbmlConverter
{
    private bool $verbose = false;

    private array $keywords = [
        'table', 'ref', 'enum', 'project', 'note', 'indexes', 'index',
        'pk', 'unique', 'not', 'null', 'increment', 'default',
    ];

    private array $typeMappings = [
        'INTEGER' => 'integer',
        'INT' => 'integer',
        'TINYINT' => 'integer',
        'SMALLINT' => 'integer',
        'MEDIUMINT' => 'integer',
        'BIGINT' => 'integer',
        'UNSIGNED BIG INT' => 'integer',
        'INT2' => 'integer',
        'INT8' => 'integer',
        'REAL' => 'real',
        'DOUBLE' => 'real',
        'DOUBLE PRECISION' => 'real',
        'FLOAT' => 'real',
        'NUMERIC' => 'decimal',
        'DECIMAL' => 'decimal',
        'BOOLEAN' => 'boolean',
        'DATE' => 'date',
        'DATETIME' => 'datetime',
        'TIMESTAMP' => 'timestamp',
        'TIME' => 'time',
        'TEXT' => 'text',
        'CLOB' => 'text',
        'VARCHAR' => 'varchar',
        'VARYING CHARACTER' => 'varchar',
        'NCHAR' => 'varchar',
        'NATIVE CHARACTER' => 'varchar',
        'NVARCHAR' => 'varchar',
        'CHAR' => 'varchar',
        'CHARACTER' => 'varchar',
        'BLOB' => 'text',
    ];

    private ?bool $hasUniqueColumn = null;

    public function __construct(bool $verbose = false)
    {
        $this->verbose = $verbose;
    }

    public function exportSchema(string $dbPath, string $outputFile): bool
    {
        try {
            if (! file_exists($dbPath)) {
                $this->logError("Database file not found: {$dbPath}");

                return false;
            }

            $sqlite = new SQLite3($dbPath);
            $schema = '';

            // Get all tables and their creation statements
            $result = $sqlite->query("SELECT sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                if ($row['sql']) {
                    $schema .= $row['sql'].";\n\n";
                }
            }

            // Get all indexes
            $result = $sqlite->query("SELECT sql FROM sqlite_master WHERE type='index' AND name NOT LIKE 'sqlite_%'");
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                if ($row['sql']) {
                    $schema .= $row['sql'].";\n\n";
                }
            }

            $sqlite->close();

            file_put_contents($outputFile, $schema);
            $this->log("Schema exported successfully to {$outputFile}");

            return true;

        } catch (Exception $e) {
            $this->logError('Error exporting schema: '.$e->getMessage());

            return false;
        }
    }

    public function convertToDbml(string $dbPath, string $dbmlOutput): bool
    {
        try {
            if (! file_exists($dbPath)) {
                $this->logError("Database file not found: {$dbPath}");

                return false;
            }

            $pdo = new PDO("sqlite:{$dbPath}");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create output directory if it doesn't exist
            $outputDir = dirname($dbmlOutput);
            if ($outputDir && ! is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
                $this->log("Created output directory: {$outputDir}");
            }

            // Get all user-defined tables, excluding SQLite internal tables
            $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($tables)) {
                $this->logWarning('No user tables found in the database.');
            }

            $dbmlLines = [
                '// DBML file generated from SQLite database',
                '// Learn more about DBML: https://dbml.dbdiagram.io/docs/',
                '',
            ];

            // Process tables
            foreach ($tables as $tableName) {
                $this->logDebug("Processing table: {$tableName}");

                $tableDisplay = $this->escapeIdentifier($tableName);
                $dbmlLines[] = "Table {$tableDisplay} {";

                try {
                    // Get column information - escape table name in PRAGMA
                    $stmt = $pdo->prepare("PRAGMA table_info([{$tableName}])");
                    $stmt->execute();
                    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($columns)) {
                        $this->logWarning("No columns found for table {$tableName}");
                        $dbmlLines[] = '  // No columns found';
                    } else {
                        // Process columns
                        foreach ($columns as $column) {
                            $colName = $column['name'];
                            $colType = $column['type'];
                            $colNotNull = (bool) $column['notnull'];
                            $colDefault = $column['dflt_value'];
                            $colPk = (bool) $column['pk'];

                            // Build column definition
                            $colDisplay = $this->escapeIdentifier($colName);
                            $dbmlType = $this->sqliteToDbmlType($colType);

                            // Build column attributes
                            $attributes = [];
                            if ($colPk) {
                                $attributes[] = 'pk';
                            }
                            if ($colNotNull && ! $colPk) { // PK implies not null
                                $attributes[] = 'not null';
                            }
                            if ($colDefault !== null) {
                                $formattedDefault = $this->formatDefaultValue($colDefault);
                                if ($formattedDefault) {
                                    $attributes[] = "default: {$formattedDefault}";
                                }
                            }

                            // Construct column line
                            if (! empty($attributes)) {
                                $colDef = "  {$colDisplay} {$dbmlType} [".implode(', ', $attributes).']';
                            } else {
                                $colDef = "  {$colDisplay} {$dbmlType}";
                            }

                            $dbmlLines[] = $colDef;
                        }

                        // Add indexes if any
                        $indexes = $this->getIndexInfo($pdo, $tableName);
                        if (! empty($indexes)) {
                            $dbmlLines[] = '';
                            $dbmlLines[] = '  Indexes {';
                            foreach ($indexes as $index) {
                                $indexCols = array_map([$this, 'escapeIdentifier'], $index['columns']);
                                $indexLine = '    ('.implode(', ', $indexCols).')';
                                if ($index['unique']) {
                                    $indexLine .= ' [unique]';
                                }
                                $dbmlLines[] = $indexLine;
                            }
                            $dbmlLines[] = '  }';
                        }
                    }
                } catch (PDOException $e) {
                    $this->logError("Error getting columns for table {$tableName}: ".$e->getMessage());
                    $dbmlLines[] = '  // Error getting columns: '.$e->getMessage();
                }

                $dbmlLines[] = '}';
                $dbmlLines[] = ''; // Add empty line between tables
            }

            // Add relationships
            $relationshipsAdded = false;
            foreach ($tables as $tableName) {
                try {
                    // Escape table name in PRAGMA
                    $stmt = $pdo->prepare("PRAGMA foreign_key_list([{$tableName}])");
                    $stmt->execute();
                    $foreignKeys = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($foreignKeys as $fk) {
                        if (! $relationshipsAdded) {
                            $dbmlLines[] = '// Relationships';
                            $relationshipsAdded = true;
                        }

                        $fkTable = $fk['table'];
                        $fkFrom = $fk['from'];
                        $fkTo = $fk['to'];

                        // Escape names for DBML
                        $tableDisplay = $this->escapeIdentifier($tableName);
                        $fkTableDisplay = $this->escapeIdentifier($fkTable);
                        $fkFromDisplay = $this->escapeIdentifier($fkFrom);
                        $fkToDisplay = $this->escapeIdentifier($fkTo);

                        // Use proper DBML relationship syntax
                        $dbmlLines[] = "Ref: {$tableDisplay}.{$fkFromDisplay} > {$fkTableDisplay}.{$fkToDisplay}";
                    }
                } catch (PDOException $e) {
                    $this->logError("Error getting foreign keys for table {$tableName}: ".$e->getMessage());
                }
            }

            // Write DBML file
            file_put_contents($dbmlOutput, implode("\n", $dbmlLines));

            $this->log("DBML schema exported successfully to {$dbmlOutput}");

            return true;

        } catch (Exception $e) {
            $this->logError('Error converting to DBML: '.$e->getMessage());

            return false;
        }
    }

    public function run(array $args): int
    {
        // Parse arguments
        $options = getopt('i:o:s:vh', ['input:', 'output:', 'sql:', 'verbose', 'help']);

        if (isset($options['h']) || isset($options['help']) || count($args) <= 1) {
            $this->printUsageExample();

            return empty($options) ? 1 : 0;
        }

        $input = $options['i'] ?? $options['input'] ?? null;
        $output = $options['o'] ?? $options['output'] ?? 'output.dbml';
        $sql = $options['s'] ?? $options['sql'] ?? null;
        $this->verbose = isset($options['v']) || isset($options['verbose']);

        if (! $input) {
            $this->logError('Missing required argument: -i/--input');
            $this->printUsageExample();

            return 1;
        }

        $this->log("Processing database: {$input}");

        // Validate input file
        if (! file_exists($input)) {
            $this->logError("Input database file not found: {$input}");

            return 1;
        }

        // Export schema as SQL if requested
        if ($sql) {
            if (! $this->exportSchema($input, $sql)) {
                $this->logError('Failed to export SQL schema');

                return 1;
            }
        }

        // Convert to DBML
        if (! $this->convertToDbml($input, $output)) {
            $this->logError('Failed to convert to DBML');

            return 1;
        }

        $this->log('Conversion completed successfully');

        return 0;
    }

    private function log(string $message, string $level = 'INFO'): void
    {
        $timestamp = date('Y-m-d H:i:s');
        echo "{$timestamp} - {$level} - {$message}\n";
    }

    private function logDebug(string $message): void
    {
        if ($this->verbose) {
            $this->log($message, 'DEBUG');
        }
    }

    private function logWarning(string $message): void
    {
        $this->log($message, 'WARNING');
    }

    private function logError(string $message): void
    {
        $this->log($message, 'ERROR');
    }

    private function escapeIdentifier(string $identifier): string
    {
        $needsEscape = (
            in_array(mb_strtolower($identifier), $this->keywords, true) ||
            str_contains($identifier, ' ') ||
            str_contains($identifier, '-') ||
            str_starts_with($identifier, '"') ||
            ! ctype_alnum(str_replace(['_', '-'], '', $identifier))
        );

        if ($needsEscape && ! (str_starts_with($identifier, '"') && str_ends_with($identifier, '"'))) {
            return '"'.$identifier.'"';
        }

        return $identifier;
    }

    private function sqliteToDbmlType(string $sqliteType): string
    {
        $sqliteType = mb_strtoupper($sqliteType);
        $baseType = preg_split('/[(\s]/', $sqliteType)[0];

        return $this->typeMappings[$baseType] ?? mb_strtolower($sqliteType);
    }

    private function formatDefaultValue(?string $defaultValue): string
    {
        if ($defaultValue === null) {
            return '';
        }

        // Handle string defaults
        if (str_starts_with($defaultValue, "'") && str_ends_with($defaultValue, "'")) {
            return "'".mb_substr($defaultValue, 1, -1)."'";
        }

        // Handle numeric defaults
        if (is_numeric($defaultValue)) {
            return $defaultValue;
        }

        // Handle boolean defaults
        if (in_array(mb_strtolower($defaultValue), ['true', 'false', '1', '0'], true)) {
            return mb_strtolower($defaultValue);
        }

        // Handle SQL functions and expressions
        if (in_array(mb_strtoupper($defaultValue), ['CURRENT_TIMESTAMP', 'CURRENT_DATE', 'CURRENT_TIME'], true)) {
            return "`{$defaultValue}`";
        }

        // For other cases, quote the value
        return "'{$defaultValue}'";
    }

    private function checkUniqueColumnExists(PDO $pdo): bool
    {
        if ($this->hasUniqueColumn !== null) {
            return $this->hasUniqueColumn;
        }

        try {
            // Check if the unique column exists in sqlite_master
            $stmt = $pdo->query('PRAGMA table_info(sqlite_master)');
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN, 1); // Get column names
            $this->hasUniqueColumn = in_array('unique', $columns, true);

            $this->logDebug('SQLite unique column '.($this->hasUniqueColumn ? 'exists' : 'does not exist'));

        } catch (PDOException $e) {
            $this->logWarning('Could not check for unique column: '.$e->getMessage());
            $this->hasUniqueColumn = false;
        }

        return $this->hasUniqueColumn;
    }

    private function getIndexInfo(PDO $pdo, string $tableName): array
    {
        $indexes = [];

        try {
            // Check if the unique column exists
            $hasUniqueColumn = $this->checkUniqueColumnExists($pdo);

            if ($hasUniqueColumn) {
                // Use the unique column if available
                $stmt = $pdo->prepare("SELECT name, [unique] FROM sqlite_master WHERE type='index' AND tbl_name=? AND name NOT LIKE 'sqlite_%'");
                $stmt->execute([$tableName]);
                $indexList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Fallback for older SQLite versions - we'll determine uniqueness differently
                $stmt = $pdo->prepare("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name=? AND name NOT LIKE 'sqlite_%'");
                $stmt->execute([$tableName]);
                $indexNames = $stmt->fetchAll(PDO::FETCH_COLUMN);

                // Convert to the expected format
                $indexList = array_map(function ($name) {
                    return ['name' => $name, 'unique' => null]; // We'll determine this later
                }, $indexNames);
            }

            foreach ($indexList as $indexRow) {
                $indexName = $indexRow['name'];
                $isUnique = $indexRow['unique'] ?? null;

                try {
                    // Get index details
                    $stmt = $pdo->prepare("PRAGMA index_info([{$indexName}])");
                    $stmt->execute();
                    $indexInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (! empty($indexInfo)) {
                        $columns = array_column($indexInfo, 'name');

                        // If we don't have unique info, try to determine it from the index name or creation SQL
                        if ($isUnique === null) {
                            $isUnique = $this->determineIndexUniqueness($pdo, $indexName, $tableName);
                        }

                        $indexes[] = [
                            'name' => $indexName,
                            'unique' => (bool) $isUnique,
                            'columns' => $columns,
                        ];
                    }
                } catch (PDOException $e) {
                    $this->logWarning("Error getting info for index {$indexName}: ".$e->getMessage());
                }
            }
        } catch (PDOException $e) {
            $this->logWarning("Error getting indexes for table {$tableName}: ".$e->getMessage());
        }

        return $indexes;
    }

    private function determineIndexUniqueness(PDO $pdo, string $indexName, string $tableName): bool
    {
        try {
            // Try to get the SQL creation statement for the index
            $stmt = $pdo->prepare("SELECT sql FROM sqlite_master WHERE type='index' AND name=? AND tbl_name=?");
            $stmt->execute([$indexName, $tableName]);
            $sql = $stmt->fetchColumn();

            if ($sql) {
                // Check if the SQL contains UNIQUE keyword
                return mb_stripos($sql, 'UNIQUE') !== false;
            }

            // Alternative method: try to check if the index name suggests uniqueness
            // Many ORMs create unique indexes with specific naming patterns
            $uniquePatterns = [
                '/unique/i',
                '/_unique_/i',
                '/^unique_/i',
                '/_uniq_/i',
                '/^uniq_/i',
            ];

            foreach ($uniquePatterns as $pattern) {
                if (preg_match($pattern, $indexName)) {
                    return true;
                }
            }

        } catch (PDOException $e) {
            $this->logDebug("Could not determine uniqueness for index {$indexName}: ".$e->getMessage());
        }

        // Default to false if we can't determine
        return false;
    }

    private function printUsageExample(): void
    {
        $script = basename($_SERVER['argv'][0] ?? 'sqlite_schema_to_dbml.php');

        echo "\n\033[36mSQLite to DBML Converter\033[0m\n";
        echo "------------------------\n\n";
        echo "Basic usage:\n";
        echo "  \033[32mphp {$script} -i database.db -o schema.dbml\033[0m\n\n";
        echo "All options:\n";
        echo "  \033[32mphp {$script} -i database.db -o schema.dbml -s schema.sql -v\033[0m\n\n";
        echo "Required arguments:\n";
        echo "  \033[33m-i, --input\033[0m    Path to the SQLite database file\n\n";
        echo "Optional arguments:\n";
        echo "  \033[33m-o, --output\033[0m   Path to the output DBML file (default: output.dbml)\n";
        echo "  \033[33m-s, --sql\033[0m      Additionally export schema as SQL to the specified file\n";
        echo "  \033[33m-v, --verbose\033[0m  Enable verbose output\n";
        echo "  \033[33m-h, --help\033[0m     Show this help message and exit\n\n";
    }
}

// Main execution
if (php_sapi_name() === 'cli') {
    try {
        $converter = new SqliteToDbmlConverter();
        $exitCode = $converter->run($_SERVER['argv']);
        exit($exitCode);
    } catch (Exception $e) {
        echo 'Unexpected error: '.$e->getMessage()."\n";
        exit(1);
    }
}
