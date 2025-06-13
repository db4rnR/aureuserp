<?php

namespace Webkul\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('recruitments_degrees')->delete();

        $user = User::first();

        $degrees = [
            [
                'sort'       => 1,
                'name'       => 'Graduate',
                'creator_id' => $user?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 2,
                'name'       => 'Master',
                'creator_id' => $user?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 3,
                'name'       => 'Bachelor',
                'creator_id' => $user?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 4,
                'name'       => 'Doctoral Degree',
                'creator_id' => $user?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('recruitments_degrees')->insert($degrees);
    }
}
