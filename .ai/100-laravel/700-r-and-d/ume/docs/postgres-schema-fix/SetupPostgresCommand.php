<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema as SchemaFacade; // Import Schema facade if needed for other checks
use PDOException;
use Exception; // Import base Exception

class SetupPostgresCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:setup-postgres
                            {--schema= : The schema name to create (defaults to env DB_SCHEMA or DB_SEARCH_PATH)}
                            {--force : Force schema setup even if not using PostgreSQL}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up a PostgreSQL schema for the application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('🚀 PostgreSQL Schema Setup');
        $this->line('--------------------------');

        // 1. Check if we're using PostgreSQL
        $dbConnection = config('database.default');
        if ($dbConnection !== 'pgsql' && !$this->option('force')) {
            $this->warn("⚠️ Current default database connection is '$dbConnection', not 'pgsql'.");
            if (!$this->confirm('The command is intended for PostgreSQL. Continue anyway?', false)) {
                $this->comment('Schema setup aborted.');
                return Command::FAILURE; // Use standard command failure code
            }
            $this->warn("Proceeding with connection '$dbConnection' due to --force flag or confirmation.");
        }

        // 2. Determine the schema name
        $schema = $this->option('schema')
            ?? config('database.connections.pgsql.schema') // Check config first
            ?? env('DB_SCHEMA')                          // Then .env DB_SCHEMA
            ?? env('DB_SEARCH_PATH')                     // Then .env DB_SEARCH_PATH
            ?? null;                                     // Default to null if none found

        if (empty($schema)) {
            // If still empty, prompt the user
            $schema = $this->ask('❓ Enter the schema name to create/use', 'public');
            if (empty($schema)) {
                $this->error('❌ Schema name cannot be empty.');
                return Command::FAILURE;
            }
        }

        $this->info("ℹ️ Target schema: \"$schema\"");

        try {
            // 3. Connect and verify PostgreSQL connection
            $this->line('🔄 Connecting to database...');
            DB::connection()->getPdo(); // Attempt to get PDO instance to force connection
            $this->info('✅ Database connection successful.');

            $version = DB::select('SELECT version()')[0]->version ?? 'unknown';
            $this->line("🐘 PostgreSQL Version: $version");

            // 4. Check if schema exists
            $this->line("🔍 Checking if schema \"$schema\" exists...");
            $schemaExists = DB::selectOne(
                "SELECT EXISTS (SELECT 1 FROM information_schema.schemata WHERE schema_name = ?)",
                [$schema]
            )->exists;

            // 5. Create schema if it doesn't exist
            if (!$schemaExists) {
                $this->line("✨ Schema \"$schema\" does not exist. Creating...");
                DB::statement("CREATE SCHEMA \"$schema\"");
                $this->info("✅ Schema \"$schema\" created successfully.");
            } else {
                $this->info("👍 Schema \"$schema\" already exists.");
            }

            // 6. Set the search path for the current connection *session*
            // Note: This only affects the current script execution unless persisted in config/env.
            $this->line("🧭 Setting session search_path to '\"$schema\", public'...");
            DB::statement("SET search_path TO \"$schema\", public");

            // 7. Verify the search path was set correctly for the session
            $currentPath = DB::selectOne("SHOW search_path")->search_path;
            $this->info("✅ Current session search_path: $currentPath");
            if (!str_contains($currentPath, $schema)) {
                 $this->warn("⚠️ Schema \"$schema\" was not successfully set in the session search_path.");
                 // Don't fail here, as the primary goal (schema existence) might be met.
            }

            // 8. Update runtime config (affects subsequent DB calls *within this command execution*)
            $this->line("⚙️ Updating runtime configuration for 'search_path'...");
            config(['database.connections.pgsql.search_path' => $schema]);
            $this->info("✅ Runtime config 'database.connections.pgsql.search_path' set to '$schema'.");

            // 9. Recommend updating .env if needed
             $envSchema = env('DB_SCHEMA') ?? env('DB_SEARCH_PATH');
            if ($envSchema !== $schema) {
                $this->line("\n💡 Recommendation:");
                $this->line("   To make this schema persistent for your application, ensure your");
                $this->line("   `.env` file has `DB_SCHEMA=$schema` or `DB_SEARCH_PATH=$schema`");
                $this->line("   and that your `config/database.php` reads this variable for the");
                $this->line("   'search_path' option in the 'pgsql' connection details.");
                $this->line("   Example `config/database.php` entry:");
                $this->line("   'pgsql' => [ ..., 'search_path' => env('DB_SCHEMA', 'public'), ... ]");
            }


            $this->info("\n🎉 PostgreSQL schema setup command finished successfully!");
            return Command::SUCCESS; // Use standard command success code

        } catch (PDOException $e) {
            $this->error("❌ Database Connection Error: " . $e->getMessage());
            $this->line("   Please check your database credentials and connection settings in `.env`");
            $this->line("   and `config/database.php`.");
            return Command::FAILURE;
        } catch (Exception $e) {
            $this->error("❌ An unexpected error occurred: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
