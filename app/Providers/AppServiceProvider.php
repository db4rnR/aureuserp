<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureCarbon();
        $this->configureCommands();
        $this->configureModels();
        $this->configurePasswordDefaults();
        $this->configureUrl();
        $this->configureVite();
    }

    /**
     * Configure the application's carbon.
     *
     * @return void
     */
    private function configureCarbon(): void
    {

        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure the application's commands.
     *
     * @return void
     */
    private function configureCommands(): void
    {

        DB::prohibitDestructiveCommands(
            $this->app->environment('production')
            && !$this->app->runningInConsole()
            && !$this->app->runningUnitTests()
            && !$this->app->isDownForMaintenance(),
        );
    }

    /**
     * Configure the application's models.
     *
     * @return void
     */
    private function configureModels(): void
    {

        Model::shouldBeStrict(! $this->app->environment('production'));
        Model::unguard(! $this->app->environment('production'));
    }

    /**
     * Summary of configurePasswordDefaults
     * Configure the application's password defaults.
     * This method sets the default password requirements based on the environment.
     * If the application is in production, it enforces stricter password requirements.
     * In non-production environments, it relaxes the requirements.
     * @see https://laravel.com/docs/10.x/passwords#password-defaults
     *
     * @return void
     */
    private function configurePasswordDefaults(): void
    {
        Password::defaults(function () {
            return $this->app->environment('production')
                ? Password::defaults()
                    ->min(12)
                    ->letters()
                    ->mixedcase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                : Password::defaults()
                    ->min(8);
        });

    }

    /**
     * Configure the application's url.
     *
     * @return void
     */
    private function configureUrl(): void
    {

        URL::forceScheme('https');
    }

    /**
     * Configure the application's vite.
     *
     * @return void
     */
    private function configureVite(): void
    {

        Vite::useBuildDirectory('build')
            ->withEntryPoints([
                'resources/js/app.js',
            ]);
    }
}
