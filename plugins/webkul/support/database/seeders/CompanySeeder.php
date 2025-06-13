<?php

namespace Webkul\Support\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Currency;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        DB::table('companies')->delete();
        DB::table('partners_partners')->delete();

        $user = User::first();

        $partnerId = DB::table('partners_partners')->insertGetId([
            'sub_type'         => 'company',
            'company_registry' => 'DUMREG780',
            'name'             => 'DummyCorp LLC',
            'email'            => 'dummy@dummycorp.local',
            'website'          => 'http://dummycorp.local',
            'tax_id'           => 'DUM123456',
            'phone'            => '1234567890',
            'mobile'           => '1234567890',
            'creator_id'       => $user?->id,
            'color'            => '#AAAAAA',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        DB::table('companies')->insertGetId([
            'sort'                => 1,
            'name'                => 'DummyCorp LLC',
            'tax_id'              => 'DUM123456',
            'registration_number' => 'DUMREG789',
            'company_id'          => 'DUMCOMP001',
            'creator_id'          => $user?->id,
            'email'               => 'dummy@dummycorp.local',
            'phone'               => '1234567890',
            'mobile'              => '1234567890',
            'color'               => '#AAAAAA',
            'is_active'           => true,
            'founded_date'        => '2000-01-01',
            'currency_id'         => Currency::find(1)->id,
            'website'             => 'http://dummycorp.local',
            'partner_id'          => $partnerId,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }
}
