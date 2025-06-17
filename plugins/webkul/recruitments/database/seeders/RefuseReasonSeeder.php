<?php

namespace Webkul\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;

class RefuseReasonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('recruitments_refuse_reasons')->delete();

        $user = User::first();

        $degrees = [
            [
                'sort'       => 1,
                'name'       => 'Does not fit the job requirements',
                'creator_id' => $user?->id,
                'template'   => 'applicant-refuse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 2,
                'name'       => 'Refused by applicant: job fit',
                'creator_id' => $user?->id,
                'template'   => 'applicant-not-interested',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 3,
                'name'       => 'Job already fulfilled',
                'creator_id' => $user?->id,
                'template'   => 'applicant-refuse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 4,
                'name'       => 'Duplicate',
                'creator_id' => $user?->id,
                'template'   => 'applicant-refuse',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 4,
                'name'       => 'Spam',
                'creator_id' => $user?->id,
                'template'   => 'applicant-not-interested',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort'       => 4,
                'name'       => 'Refused by applicant: salary',
                'template'   => 'applicant-not-interested',
                'creator_id' => $user?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('recruitments_refuse_reasons')->insert($degrees);
    }
}
