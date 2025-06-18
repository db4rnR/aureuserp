<?php

declare(strict_types=1);

namespace Webkul\Employee\Database\Seeders;

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
            EmploymentTypeSeeder::class,
            EmployeeJobPositionSeeder::class,
            SkillTypeSeeder::class,
            SkillSeeder::class,
            SkillLevelSeeder::class,
            WorkLocationSeeder::class,
            EmployeeCategorySeeder::class,
            DepartureReasonSeeder::class,
            CalendarSeeder::class,
            CalendarAttendanceSeeder::class,
            ActivityPlanTemplateSeeder::class,
            EmployeeSeeder::class,
            DepartmentSeeder::class,
        ]);
    }
}
