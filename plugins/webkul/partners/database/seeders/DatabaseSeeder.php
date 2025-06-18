<?php

declare(strict_types=1);

namespace Webkul\Partner\Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     */
    public function run($parameters = []): void
    {
        $this->call(IndustrySeeder::class);

        $this->call(TitleSeeder::class);
    }
}
