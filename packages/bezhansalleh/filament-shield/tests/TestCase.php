<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentShield\Tests;

use BezhanSalleh\FilamentShield\FilamentShieldServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

final class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'BezhanSalleh\\FilamentShield\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        // $migration = include __DIR__.'/../database/migrations/create_filament_shield_settings_table.php.stub';
        // $migration->up();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            FilamentShieldServiceProvider::class,
        ];
    }
}
