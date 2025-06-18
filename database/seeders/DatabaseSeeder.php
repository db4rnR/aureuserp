<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Security\Database\Seeders\DatabaseSeeder as SecurityDatabaseSeeder;
use Webkul\Support\Database\Seeders\DatabaseSeeder as SupportDatabaseSeeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SecurityDatabaseSeeder::class,
            SupportDatabaseSeeder::class,
        ]);
    }
}
