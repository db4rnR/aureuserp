<?php

declare(strict_types=1);

namespace Webkul\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LocationSeeder::class,
            RouteSeeder::class,
            OperationTypeSeeder::class,
            RuleSeeder::class,
            WarehouseSeeder::class,
        ]);
    }
}
