<?php

namespace Database\Seeders;

use App\Models\Employee;
use Faker\Factory;
use Illuminate\Database\Seeder;

class EmployeesSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            Employee::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'email' => $faker->unique()->safeEmail,
                'mobile_number' => $faker->unique()->numerify('##########'),
                'birthday' => $faker->date(),
                'nationality' => $faker->country,
                'address' => $faker->address,
                'employee_id' => $faker->unique()->numerify('EMP####'),
                'employment_status' => $faker->randomElement(['regular', 'part_time', 'job_order', 'volunteer', 'consultant', 'contract_service']),
                'start_date' => $faker->date(),
                'position' => $faker->jobTitle,
                'department' => $faker->word,
                'emergency_contact_name' => $faker->name,
                'emergency_contact_number' => $faker->numerify('##########'),
                'relation_to_employee' => $faker->randomElement(['parent', 'sibling', 'spouse', 'friend']),
                'emergency_contact_address' => $faker->address,
                'is_active' => $faker->boolean,
                'created_by' => $faker->name,
                'updated_by' => $faker->name,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }
}
