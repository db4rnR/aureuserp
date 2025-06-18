<?php

declare(strict_types=1);

namespace Webkul\Support\Database\Seeders;

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
        $this->call([
            CurrencySeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CompanySeeder::class,
            ActivityTypeSeeder::class,
            ActivityPlanSeeder::class,
            UOMCategorySeeder::class,
            UOMSeeder::class,
            UtmStageSeeder::class,
            UtmCampaignSeeder::class,
            UTMMediumSeeder::class,
            UTMSourceSeeder::class,
        ]);
    }
}
