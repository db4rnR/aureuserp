<?php

namespace Webkul\Sale\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class SalesTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sales_teams')->delete();

        $company = Company::first();

        $user = User::first();

        $degrees = [
            [
                'id'              => 1,
                'sort'            => 0,
                'company_id'      => $company?->id,
                'user_id'         => $user?->id,
                'creator_id'      => $user?->id,
                'color'           => '#FF0000',
                'name'            => 'Sales',
                'is_active'       => true,
                'invoiced_target' => 25000,
            ],
            [
                'id'              => 2,
                'sort'            => 1,
                'company_id'      => $company?->id,
                'user_id'         => $user?->id,
                'creator_id'      => $user?->id,
                'color'           => '#00FF00',
                'name'            => 'Website',
                'is_active'       => false,
                'invoiced_target' => 5000,
            ],
            [
                'id'              => 3,
                'sort'            => 2,
                'company_id'      => $company?->id,
                'user_id'         => $user?->id,
                'creator_id'      => $user?->id,
                'color'           => '#0000FF',
                'name'            => 'Point of Sale',
                'is_active'       => true,
                'invoiced_target' => 55000,
            ],
            [
                'id'              => 4,
                'sort'            => 3,
                'company_id'      => $company?->id,
                'user_id'         => $user?->id,
                'creator_id'      => $user?->id,
                'color'           => '#FFFF00',
                'name'            => 'Pre-Sales',
                'is_active'       => true,
                'invoiced_target' => 55000,
            ],
        ];

        DB::table('sales_teams')->insert($degrees);
    }
}
