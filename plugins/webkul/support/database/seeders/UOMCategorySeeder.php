<?php

namespace Webkul\Support\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;

class UOMCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        DB::table('unit_of_measures')->delete();

        DB::table('unit_of_measure_categories')->delete();

        DB::table('unit_of_measure_categories')->insert([
            [
                'id'         => 1,
                'name'       => 'Unit',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 2,
                'name'       => 'Weight',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 3,
                'name'       => 'Working Time',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 4,
                'name'       => 'Length / Distance',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 5,
                'name'       => 'Surface',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id'         => 6,
                'name'       => 'Volume',
                'creator_id' => $user?->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
