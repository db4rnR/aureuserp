<?php

declare(strict_types=1);

namespace Webkul\Payment\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            PaymentSeeder::class,
        ]);
    }
}
