<?php

namespace Tests\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DatabaseTestingTrait
{
    /**
     * Use an in-memory SQLite database for testing.
     *
     * @return void
     */
    protected function useInMemoryDatabase(): void
    {
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
    }

    /**
     * Begin a database transaction.
     *
     * @return void
     */
    protected function beginDatabaseTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * Rollback a database transaction.
     *
     * @return void
     */
    protected function rollbackDatabaseTransaction(): void
    {
        DB::rollBack();
    }

    /**
     * Commit a database transaction.
     *
     * @return void
     */
    protected function commitDatabaseTransaction(): void
    {
        DB::commit();
    }

    /**
     * Create a database record.
     *
     * @param string $model
     * @param array $attributes
     * @return Model
     */
    protected function createRecord(string $model, array $attributes = []): Model
    {
        return $model::factory()->create($attributes);
    }

    /**
     * Create multiple database records.
     *
     * @param string $model
     * @param int $count
     * @param array $attributes
     * @return Collection
     */
    protected function createRecords(string $model, int $count = 3, array $attributes = []): Collection
    {
        return $model::factory()->count($count)->create($attributes);
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

        $this->assertTrue(
            Schema::connection($connection)->hasTable($table),
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

        $this->assertFalse(
            Schema::connection($connection)->hasTable($table),
            "Table [{$table}] exists in the database."
        );
    }

    /**
     * Assert that a table has a given column.
     *
     * @param string $table
     * @param string $column
     * @param string|null $connection
     * @return void
     */
    protected function assertTableHasColumn(string $table, string $column, ?string $connection = null): void
    {
        $connection = $connection ?: config('database.default');

        $this->assertTrue(
            Schema::connection($connection)->hasColumn($table, $column),
            "Table [{$table}] does not have column [{$column}]."
        );
    }

    /**
     * Assert that a table does not have a given column.
     *
     * @param string $table
     * @param string $column
     * @param string|null $connection
     * @return void
     */
    protected function assertTableDoesNotHaveColumn(string $table, string $column, ?string $connection = null): void
    {
        $connection = $connection ?: config('database.default');

        $this->assertFalse(
            Schema::connection($connection)->hasColumn($table, $column),
            "Table [{$table}] has column [{$column}]."
        );
    }

    /**
     * Assert that a model has a given attribute.
     *
     * @param Model $model
     * @param string $attribute
     * @return void
     */
    protected function assertModelHasAttribute(Model $model, string $attribute): void
    {
        $this->assertTrue(
            array_key_exists($attribute, $model->getAttributes()),
            "Model does not have attribute [{$attribute}]."
        );
    }

    /**
     * Assert that a model does not have a given attribute.
     *
     * @param Model $model
     * @param string $attribute
     * @return void
     */
    protected function assertModelDoesNotHaveAttribute(Model $model, string $attribute): void
    {
        $this->assertFalse(
            array_key_exists($attribute, $model->getAttributes()),
            "Model has attribute [{$attribute}]."
        );
    }

    /**
     * Assert that a model has a given relationship.
     *
     * @param Model $model
     * @param string $relationship
     * @param string $type
     * @return void
     */
    protected function assertModelHasRelationship(Model $model, string $relationship, string $type): void
    {
        $this->assertTrue(
            method_exists($model, $relationship),
            "Model does not have relationship method [{$relationship}]."
        );

        $relationshipObject = $model->{$relationship}();

        $this->assertInstanceOf(
            $type,
            $relationshipObject,
            "Relationship [{$relationship}] is not of type [{$type}]."
        );
    }

    /**
     * Assert that a model has the expected attributes.
     *
     * @param Model $model
     * @param array $attributes
     * @return void
     */
    protected function assertModelAttributes(Model $model, array $attributes): void
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
     * @param Model $model
     * @param array $relationships
     * @return void
     */
    protected function assertModelRelationships(Model $model, array $relationships): void
    {
        foreach ($relationships as $relationship => $type) {
            $this->assertModelHasRelationship($model, $relationship, $type);
        }
    }

    /**
     * Assert that a model has soft deletes.
     *
     * @param Model $model
     * @return void
     */
    protected function assertModelHasSoftDeletes(Model $model): void
    {
        $this->assertTrue(
            method_exists($model, 'trashed'),
            "Model does not use soft deletes."
        );
    }

    /**
     * Assert that a model has timestamps.
     *
     * @param Model $model
     * @return void
     */
    protected function assertModelHasTimestamps(Model $model): void
    {
        $this->assertTrue(
            $model->timestamps,
            "Model does not use timestamps."
        );
    }
}
