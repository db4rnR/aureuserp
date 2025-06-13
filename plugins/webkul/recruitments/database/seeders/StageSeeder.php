<?php

namespace Webkul\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Employee\Models\EmployeeJobPosition;
use Webkul\Recruitment\Models\Stage;
use Webkul\Security\Models\User;

class StageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('recruitments_stages')->delete();

        $user = User::first();

        $recruitmentStages = [
            [
                'sort'           => 0,
                'is_default'     => 1,
                'creator_id'     => $user?->id,
                'name'           => 'New',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [

                'sort'           => 1,
                'is_default'     => 0,
                'creator_id'     => $user?->id,
                'name'           => 'First Interview',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [

                'sort'           => 2,
                'is_default'     => 0,
                'creator_id'     => $user?->id,
                'name'           => 'Initial Qualification',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [

                'sort'           => 3,
                'is_default'     => 0,
                'creator_id'     => $user?->id,
                'name'           => 'Second Interview',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [

                'sort'           => 4,
                'is_default'     => 0,
                'creator_id'     => $user?->id,
                'name'           => 'Contract Proposal',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => false,
                'fold'           => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [

                'sort'           => 5,
                'is_default'     => 0,
                'creator_id'     => $user?->id,
                'name'           => 'Contract Signed',
                'legend_blocked' => 'Blocked',
                'legend_done'    => 'Ready for Next Stage',
                'legend_normal'  => 'In Progress	',
                'hired_stage'    => true,
                'fold'           => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        DB::table('recruitments_stages')->insert($recruitmentStages);

        $jobs = EmployeeJobPosition::all();
        $stage = Stage::first();

        $recruitmentStagesJobs = [];

        foreach ($jobs as $job) {
            $recruitmentStagesJobs[] = [
                'stage_id' => $stage->id,
                'job_id'   => $job->id,
            ];
        }

        DB::table('recruitments_stages_jobs')->insert($recruitmentStagesJobs);
    }
}
