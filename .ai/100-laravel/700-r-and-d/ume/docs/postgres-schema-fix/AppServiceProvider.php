<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*Gate::before(function (User $user, string $ability) {
            return $user->id === 1;});*/

        Number::useLocale('en');
        URL::defaults(['domain' => '']);
        if (! $this->app->isLocal()) {
            URL::forceScheme('https');
        }

        Model::unguard();

        $this->configureCarbon();
        $this->configureCommands();
        $this->configureDatabase();
        $this->configureModels();
        $this->configureUrl();
        $this->configureVite();

        /**
         * Force correct Typesense API key very early
         */
        // config(['scout.typesense.client-settings.api_key' => 'LARAVEL_HERD']);

        /**
         * Force the search_path for PostgreSQL connections
         * COMMENTED OUT: Although DB::whenReady exists in L10+, this block is causing a
         * BadMethodCallException related to PostgresConnection during boot.
         * Functionality (schema creation, search_path config) is handled elsewhere
         * (SetupPostgresCommand, config/database.php + .env).
         */
        /*
        if (config('database.default') === 'pgsql') {
            // Try DB_SCHEMA first, then fall back to DB_SEARCH_PATH
            $searchPath = env('DB_SCHEMA', env('DB_SEARCH_PATH'));
            if ($searchPath) {
                // Set the search_path in the database configuration
                config(['database.connections.pgsql.search_path' => $searchPath]);

                // Ensure schema exists and is set correctly for all new connections
                DB::whenReady(function ($connection) use ($searchPath) {
                    try {
                        if ($connection->getDriverName() === 'pgsql') {
                            // Create schema if needed
                            $connection->statement("CREATE SCHEMA IF NOT EXISTS \"$searchPath\"");
                            // Set search path
                            $connection->statement("SET search_path TO \"$searchPath\", public");
                        }
                    } catch (\Exception $e) {
                        // Just log errors, don't break bootstrap
                        logger()->error("Error setting PostgreSQL search path: " . $e->getMessage());
                    }
                });
            }
        }
        */
    }

    /**
     * Configure the application's carbon.
     */
    private function configureCarbon(): void
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        Artisan::command('inspire', function (): void {
            $this->comment(Inspiring::quote());
        })->purpose('Display an inspiring quote');
    }

    /**
     * Configure the application's database.
     */
    private function configureDatabase(): void
    {
        DB::prohibitDestructiveCommands(
            $this->app->isProduction()
            && ! $this->app->runningInConsole()
            && ! $this->app->runningUnitTests()
            && ! $this->app->isDownForMaintenance(),
        );
    }

    /**
     * Configure the application's models.
     */
    private function configureModels(): void
    {
        Model::automaticallyEagerLoadRelationships();
        Model::preventAccessingMissingAttributes(! $this->app->isProduction());
        Model::preventLazyLoading(! $this->app->isProduction());
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());
        Model::shouldBeStrict(! $this->app->isProduction());
        Model::unguard(! $this->app->isProduction());
    }

    /**
     * Configure the application's url.
     */
    private function configureUrl(): void
    {

        URL::forceScheme('https');
    }

    /**
     * Configure the application's vite.
     */
    private function configureVite(): void
    {

        Vite::useBuildDirectory('build')
            ->withEntryPoints([
                'resources/js/app.js',
            ]);
    }
}
