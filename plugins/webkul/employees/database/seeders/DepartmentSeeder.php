<?php

namespace Webkul\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Employee\Models\Employee;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees_departments')->delete();

        $company = Company::first();

        $user = User::first();

        $manager = Employee::first();

        $now = now();

        $departments = [
            [
                'company_id'    => $company?->id,
                'creator_id'    => $user?->id,
                'name'          => 'Administration',
                'complete_name' => 'Administration',
                'color'         => '#4e0554',
                'deleted_at'    => null,
                'manager_id'    => $manager?->id,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'company_id'    => $company?->id,
                'creator_id'    => $user?->id,
                'name'          => 'Long Term Projects',
                'complete_name' => 'Long Term Projects',
                'color'         => '#5d0a6e',
                'deleted_at'    => null,
                'manager_id'    => $manager?->id,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'company_id'    => $company?->id,
                'creator_id'    => $user?->id,
                'name'          => 'Management',
                'complete_name' => 'Management',
                'color'         => '#4e095c',
                'deleted_at'    => null,
                'manager_id'    => $manager?->id,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'company_id'    => $company?->id,
                'creator_id'    => $user?->id,
                'name'          => 'Professional Services',
                'complete_name' => 'Professional Services',
                'color'         => '#5e0870',
                'deleted_at'    => null,
                'manager_id'    => $manager?->id,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'company_id'    => $company?->id,
                'creator_id'    => $user?->id,
                'name'          => 'R&D USA',
                'complete_name' => 'R&D USA',
                'color'         => '#420957',
                'deleted_at'    => null,
                'manager_id'    => $manager?->id,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'company_id'    => $company?->id,
                'creator_id'    => $user?->id,
                'name'          => 'Research & Development',
                'complete_name' => 'Research & Development',
                'color'         => '#570919',
                'deleted_at'    => null,
                'manager_id'    => $manager?->id,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'company_id'    => $company?->id,
                'creator_id'    => $user?->id,
                'name'          => 'Sales',
                'complete_name' => 'Sales',
                'color'         => '#590819',
                'deleted_at'    => null,
                'manager_id'    => $manager?->id,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        DB::table('employees_departments')->insert($departments);
    }
}
