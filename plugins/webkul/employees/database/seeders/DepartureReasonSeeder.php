<?php

namespace Webkul\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;

class DepartureReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees_departure_reasons')->delete();

        $user = User::first();

        $employeesDepartureReasons = [
            ['creator_id' => $user?->id, 'name' => 'Fired', 'sort' => 1, 'reason_code' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['creator_id' => $user?->id, 'name' => 'Resigned', 'sort' => 2, 'reason_code' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['creator_id' => $user?->id, 'name' => 'Retired', 'sort' => 3, 'reason_code' => 3, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('employees_departure_reasons')->insert($employeesDepartureReasons);
    }
}
