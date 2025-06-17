<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
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
     * @return void
     */
    protected function useRefreshDatabase(): void
    {
        $this->refreshDatabase();
    }

    /**
     * Use in-memory database for testing.
     *
     * @return void
     */
    protected function useInMemoryDatabase(): void
    {
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
    }

    /**
     * Create a test file in storage.
     *
     * @param string $path
     * @param string $content
     * @param string $disk
     * @return string
     */
    protected function createTestFile(string $path, string $content = 'test content', string $disk = 'local'): string
    {
        Storage::disk($disk)->put($path, $content);
        return $path;
    }

    /**
     * Delete a test file from storage.
     *
     * @param string $path
     * @param string $disk
     * @return void
     */
    protected function deleteTestFile(string $path, string $disk = 'local'): void
    {
        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }

    /**
     * Assert that a database has a given table.
     *
     * @param string $table
     * @param string|null $connection
     * @return void
     */
    protected function assertDatabaseHasTable(string $table, ?string $connection = null): void
    {
        $connection = $connection ?: config('database.default');

        $tables = DB::connection($connection)->getDoctrineSchemaManager()->listTableNames();

        $this->assertContains(
            $table,
            $tables,
            "Table [{$table}] does not exist in the database."
        );
    }

    /**
     * Assert that a database does not have a given table.
     *
     * @param string $table
     * @param string|null $connection
     * @return void
     */
    protected function assertDatabaseDoesNotHaveTable(string $table, ?string $connection = null): void
    {
        $connection = $connection ?: config('database.default');

        $tables = DB::connection($connection)->getDoctrineSchemaManager()->listTableNames();

        $this->assertNotContains(
            $table,
            $tables,
            "Table [{$table}] exists in the database."
        );
    }

    /**
     * Assert that a JSON response has a given structure.
     *
     * @param TestResponse $response
     * @param array $structure
     * @param string $path
     * @return void
     */
    protected function assertJsonStructure(TestResponse $response, array $structure, string $path = ''): void
    {
        $response->assertJsonStructure($structure, $path);
    }

    /**
     * Assert that a model has the expected attributes.
     *
     * @param object $model
     * @param array $attributes
     * @return void
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
     *
     * @param object $model
     * @param array $relationships
     * @return void
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
     *
     * @param object $object
     * @param string $method
     * @param string $message
     * @return void
     */
    protected function assertObjectHasMethod(object $object, string $method, string $message = ''): void
    {
        $this->assertTrue(
            method_exists($object, $method),
            $message ?: "Object does not have method [{$method}]."
        );
    }
}
