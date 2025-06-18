<?php

declare(strict_types=1);

namespace Webkul\Project\Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProjectStageSeeder::class,
        ]);
    }
}
