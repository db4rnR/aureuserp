<?php

declare(strict_types=1);

namespace Webkul\Sale\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SalesTeamSeeder::class,
        ]);
    }
}
