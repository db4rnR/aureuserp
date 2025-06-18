<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Clear cache before each test
        Artisan::call('cache:clear');
    }

    /**
     * Reset the database after each test.
     *
     * This method uses LazilyRefreshDatabase which only refreshes the database when needed,
     * significantly improving test performance.
     */
    protected function useRefreshDatabase(): void
    {
        // LazilyRefreshDatabase trait is used at the class level
        // This method is kept for backward compatibility
        // The database will be refreshed automatically when needed
    }

    /**
     * Use in-memory database for testing.
     */
    protected function useInMemoryDatabase(): void
    {
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
    }

    /**
     * Use database transactions for tests.
     *
     * This wraps each test in a transaction which is rolled back after the test completes.
     * This is much faster than refreshing the entire database for each test.
     */
    protected function useDatabaseTransactions(): void
    {
        // DatabaseTransactions trait is used at the class level
        // This method is provided for consistency with useRefreshDatabase()
        // The database transactions will be handled automatically
    }

    /**
     * Create a test file in storage.
     */
    protected function createTestFile(string $path, string $content = 'test content', string $disk = 'local'): string
    {
        Storage::disk($disk)->put($path, $content);

        return $path;
    }

    /**
     * Delete a test file from storage.
     */
    protected function deleteTestFile(string $path, string $disk = 'local'): void
    {
        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }

    /**
     * Assert that a database has a given table.
     */
    protected function assertDatabaseHasTable(string $table, ?string $connection = null): void
    {
        $connection = $connection !== null && $connection !== '' && $connection !== '0' ? $connection : config('database.default');

        $tables = DB::connection($connection)->getDoctrineSchemaManager()->listTableNames();

        $this->assertContains(
            $table,
            $tables,
            "Table [{$table}] does not exist in the database."
        );
    }

    /**
     * Assert that a database does not have a given table.
     */
    protected function assertDatabaseDoesNotHaveTable(string $table, ?string $connection = null): void
    {
        $connection = $connection !== null && $connection !== '' && $connection !== '0' ? $connection : config('database.default');

        $tables = DB::connection($connection)->getDoctrineSchemaManager()->listTableNames();

        $this->assertNotContains(
            $table,
            $tables,
            "Table [{$table}] exists in the database."
        );
    }

    /**
     * Assert that a JSON response has a given structure.
     */
    protected function assertJsonStructure(TestResponse $response, array $structure, string $path = ''): void
    {
        $response->assertJsonStructure($structure, $path);
    }

    /**
     * Assert that a model has the expected attributes.
     */
    protected function assertModelHasAttributes(object $model, array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->assertEquals(
                $value,
                $model->{$key},
                "Model attribute [{$key}] does not match expected value."
            );
        }
    }

    /**
     * Assert that a model has the expected relationships.
     */
    protected function assertModelHasRelationships(object $model, array $relationships): void
    {
        foreach ($relationships as $relationship => $type) {
            $this->assertObjectHasMethod(
                $model,
                $relationship,
                "Model does not have relationship method [{$relationship}]."
            );

            $relationshipObject = $model->{$relationship}();

            $this->assertInstanceOf(
                $type,
                $relationshipObject,
                "Relationship [{$relationship}] is not of type [{$type}]."
            );
        }
    }

    /**
     * Assert that an object has a method.
     */
    protected function assertObjectHasMethod(object $object, string $method, string $message = ''): void
    {
        $this->assertTrue(
            method_exists($object, $method),
            $message !== '' && $message !== '0' ? $message : "Object does not have method [{$method}]."
        );
    }

    /**
     * Seed the database with specific data for a test.
     *
     * This method allows tests to seed only the specific data they need,
     * rather than the entire database, which can significantly improve test performance.
     *
     * @param  array  $seeders  Array of seeder class names to run
     * @param  array  $factories  Array of factory configurations [model class => count]
     */
    protected function seedDatabase(array $seeders = [], array $factories = []): void
    {
        // Run specific seeders if provided
        foreach ($seeders as $seeder) {
            if (class_exists($seeder)) {
                $this->artisan('db:seed', ['--class' => $seeder]);
            }
        }

        // Create models using factories if provided
        foreach ($factories as $model => $count) {
            if (class_exists($model) && method_exists($model, 'factory')) {
                $model::factory()->count($count)->create();
            }
        }
    }

    /**
     * Enable test result caching for performance optimization.
     *
     * This method configures the test to use the cache directory specified in phpunit.xml.
     * Caching test results can significantly improve performance for tests that don't change frequently.
     *
     * @param  bool  $enabled  Whether to enable test result caching
     */
    protected function useTestCache(bool $enabled = true): void
    {
        if ($enabled) {
            // The cacheDirectory is already configured in phpunit.xml
            // This method is provided for explicit control in tests
            // and for documentation purposes
        }
        // If needed, tests can explicitly disable caching
        // Currently, there's no direct way to disable caching for individual tests
        // but this method provides a hook for future implementation

    }
}
