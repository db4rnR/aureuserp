<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class CheckEnvSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:check {variable? : The environment variable to check} {--all : Check all database-related variables}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check where environment variables are set from';

    /**
     * Database-related env variables to check if --all is provided
     */
    protected array $dbVars = [
        'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE',
        'DB_USERNAME', 'DB_PASSWORD', 'DB_SCHEMA', 'DB_URL'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $variable = $this->argument('variable');
        $checkAll = $this->option('all');

        if (!$variable && !$checkAll) {
            $variable = $this->ask('Which environment variable would you like to check?');
        }

        if ($checkAll) {
            $this->info('Checking all database-related environment variables:');
            foreach ($this->dbVars as $var) {
                $this->checkVariable($var);
            }
            return Command::SUCCESS;
        }

        if (!$variable) {
            $this->error('No variable specified.');
            return Command::FAILURE;
        }

        $this->checkVariable($variable);
        return Command::SUCCESS;
    }

    /**
     * Check the source of a specific environment variable
     */
    protected function checkVariable(string $variable): void
    {
        $this->info("---------- Checking: $variable ----------");

        // 1. Check if it's in $_ENV or $_SERVER (real environment)
        $fromEnv = $this->checkSystemEnvironment($variable);

        // 2. Check if it's in .env file
        $fromDotEnv = $this->checkDotEnvFile($variable);

        // 3. Check if it appears in config files as a default
        $configUsage = $this->findConfigUsage($variable);

        // 4. Check the actual resolved value
        $this->checkResolvedValue($variable, $fromEnv, $fromDotEnv, $configUsage);
    }

    /**
     * Check if variable exists in system environment
     */
    protected function checkSystemEnvironment(string $variable): bool
    {
        $inEnv = isset($_ENV[$variable]);
        $inServer = isset($_SERVER[$variable]);

        if ($inEnv) {
            $this->line("<fg=green>√</> Present in \$_ENV superglobal");
            $this->line("   Value: " . $_ENV[$variable]);
            return true;
        }

        if ($inServer) {
            $this->line("<fg=green>√</> Present in \$_SERVER superglobal");
            $this->line("   Value: " . $_SERVER[$variable]);
            return true;
        }

        $this->line("<fg=yellow>×</> Not found in system environment variables");
        return false;
    }

    /**
     * Check if variable exists in .env file
     */
    protected function checkDotEnvFile(string $variable): bool
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            $this->line("<fg=yellow>×</> .env file not found");
            return false;
        }

        $contents = File::get($envPath);
        $lines = explode("\n", $contents);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            if (strpos($line, $variable.'=') === 0) {
                $parts = explode('=', $line, 2);
                $value = $parts[1] ?? '';
                $this->line("<fg=green>√</> Found in .env file");
                $this->line("   Line: $line");
                return true;
            }
        }

        $this->line("<fg=yellow>×</> Not found in .env file");
        return false;
    }

    /**
     * Find usage in config files (focus on default values)
     */
    protected function findConfigUsage(string $variable): array
    {
        $results = [];
        $pattern = '/env\\([\'"]' . preg_quote($variable, '/') . '[\'"]\\s*,\\s*[^)]+\\)/';

        $configPath = config_path();
        $configFiles = File::allFiles($configPath);

        foreach ($configFiles as $file) {
            $content = File::get($file->getPathname());

            if (preg_match_all($pattern, $content, $matches)) {
                foreach ($matches[0] as $match) {
                    $results[] = [
                        'file' => $file->getRelativePathname(),
                        'usage' => trim($match)
                    ];
                }
            }
        }

        if (empty($results)) {
            $this->line("<fg=yellow>×</> No default values found in config files");
            return [];
        }

        $this->line("<fg=blue>i</> Found as config defaults in:");
        foreach ($results as $result) {
            $this->line("   • {$result['file']}: {$result['usage']}");
        }

        return $results;
    }

    /**
     * Check the actual resolved value through Laravel
     */
    protected function checkResolvedValue(string $variable, bool $fromEnv, bool $fromDotEnv, array $configUsage): void
    {
        $value = env($variable);

        $this->newLine();
        $this->line("<fg=white;bg=blue> RESULT </>");

        if ($value === null) {
            $this->warn("Variable '$variable' does not have a value (is null)");
            $this->line("This means it wasn't set in environment or .env, and no default is being used.");
            return;
        }

        $this->info("Resolved value: " . (is_bool($value) ? ($value ? 'true' : 'false') : $value));

        // Determine the most likely source
        if ($fromEnv) {
            $this->line("Likely source: System environment variable");
        } elseif ($fromDotEnv) {
            $this->line("Likely source: .env file");
        } elseif (!empty($configUsage)) {
            $this->line("Likely source: Default value from config files");
            $this->line("The value comes from a default in one of your config files, not from an actual environment variable.");
        } else {
            $this->line("Source: Unknown");
            $this->line("The value exists but wasn't found in searched locations. It might come from:");
            $this->line(" - A file we didn't check");
            $this->line(" - Environment processing outside Laravel (e.g., Docker, hosting platform)");
            $this->line(" - A service provider that sets environment variables");
        }

        // For DB_SCHEMA specifically, check PostgreSQL config
        if ($variable === 'DB_SCHEMA') {
            $this->newLine();
            $this->line("<fg=white;bg=blue> POSTGRESQL INFO </>");
            $configSchema = Config::get('database.connections.pgsql.search_path');
            $this->line("Current search_path value in config: " . ($configSchema ?: 'null'));

            if ($configSchema === 'public' && !$fromEnv && !$fromDotEnv) {
                $this->info("NOTE: 'public' is the default PostgreSQL schema.");
                $this->line("Since DB_SCHEMA is not explicitly set, the default 'public' schema is being used.");
                $this->line("This is expected behavior if you haven't set a custom schema.");
            }
        }
    }
}
