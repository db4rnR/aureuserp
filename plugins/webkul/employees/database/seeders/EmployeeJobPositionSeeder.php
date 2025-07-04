<?php

declare(strict_types=1);

namespace Webkul\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;

class EmployeeJobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees_job_positions')->delete();

        $user = User::first();

        $jobPositions = [
            [
                'sort' => 1,
                'name' => 'Software Engineer',
                'description' => 'Develop and maintain software solutions.',
                'requirements' => 'Proficiency in programming languages like PHP, JavaScript, and Python.',
                'is_active' => true,
                'expected_employees' => 10,
                'no_of_employee' => 8,
                'no_of_recruitment' => 2,
                'department_id' => null,
                'company_id' => null,
                'creator_id' => $user?->id,
                'employment_type_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort' => 2,
                'name' => 'HR Manager',
                'description' => 'Manage HR activities, including recruitment and employee relations.',
                'requirements' => 'Experience in HR management and excellent interpersonal skills.',
                'is_active' => true,
                'expected_employees' => 2,
                'no_of_employee' => 1,
                'no_of_recruitment' => 1,
                'department_id' => null,
                'company_id' => null,
                'creator_id' => $user?->id,
                'employment_type_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort' => 3,
                'name' => 'Marketing Specialist',
                'description' => 'Plan and execute marketing campaigns.',
                'requirements' => 'Knowledge of digital marketing, content creation, and analytics tools.',
                'is_active' => true,
                'expected_employees' => 5,
                'no_of_employee' => 3,
                'no_of_recruitment' => 2,
                'department_id' => null,
                'company_id' => null,
                'creator_id' => $user?->id,
                'employment_type_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort' => 4,
                'name' => 'Sales Manager',
                'description' => 'Oversee the sales team and develop strategies to increase revenue.',
                'requirements' => 'Strong background in sales and leadership experience.',
                'is_active' => true,
                'expected_employees' => 4,
                'no_of_employee' => 4,
                'no_of_recruitment' => 0,
                'department_id' => null,
                'company_id' => null,
                'creator_id' => $user?->id,
                'employment_type_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort' => 5,
                'name' => 'Product Manager',
                'description' => 'Oversee the development and lifecycle of products from start to finish.',
                'requirements' => 'Experience in product management and market research.',
                'is_active' => true,
                'expected_employees' => 3,
                'no_of_employee' => 2,
                'no_of_recruitment' => 1,
                'department_id' => null,
                'company_id' => null,
                'creator_id' => $user?->id,
                'employment_type_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort' => 6,
                'name' => 'UX/UI Designer',
                'description' => 'Design intuitive user interfaces and improve user experience.',
                'requirements' => 'Experience with design tools like Sketch, Figma, Adobe XD.',
                'is_active' => false,
                'expected_employees' => 2,
                'no_of_employee' => 1,
                'no_of_recruitment' => 1,
                'department_id' => null,
                'company_id' => null,
                'creator_id' => $user?->id,
                'employment_type_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort' => 7,
                'name' => 'Customer Support Specialist',
                'description' => 'Provide assistance to customers and solve their issues.',
                'requirements' => 'Excellent communication skills and patience.',
                'is_active' => true,
                'expected_employees' => 5,
                'no_of_employee' => 3,
                'no_of_recruitment' => 2,
                'department_id' => null,
                'company_id' => null,
                'creator_id' => $user?->id,
                'employment_type_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort' => 8,
                'name' => 'Data Scientist',
                'description' => 'Analyze and interpret complex data to help make strategic decisions.',
                'requirements' => 'Strong background in statistics, programming, and machine learning.',
                'is_active' => true,
                'expected_employees' => 6,
                'no_of_employee' => 5,
                'no_of_recruitment' => 1,
                'department_id' => null,
                'company_id' => null,
                'creator_id' => $user?->id,
                'employment_type_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort' => 9,
                'name' => 'Finance Analyst',
                'description' => 'Analyze financial data and prepare reports to guide company decisions.',
                'requirements' => 'Experience in financial analysis and accounting.',
                'is_active' => true,
                'expected_employees' => 4,
                'no_of_employee' => 2,
                'no_of_recruitment' => 2,
                'department_id' => null,
                'company_id' => null,
                'creator_id' => $user?->id,
                'employment_type_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sort' => 10,
                'name' => 'Legal Advisor',
                'description' => 'Provide legal guidance to the company and handle legal matters.',
                'requirements' => 'Law degree and experience in corporate law.',
                'is_active' => true,
                'expected_employees' => 1,
                'no_of_employee' => 1,
                'no_of_recruitment' => 0,
                'department_id' => null,
                'company_id' => null,
                'creator_id' => $user?->id,
                'employment_type_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('employees_job_positions')->insert($jobPositions);
    }
}
