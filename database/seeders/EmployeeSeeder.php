<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Generator;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Generator::class);
        for($i = 0; $i<19; $i++) {
            DB::table('employees')->insert([
                'first_name' => $faker->firstname(),
                'middle_name' => $faker->name(),
                'last_name' => $faker->lastname(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'birthday' => $faker->date('Y-m-d'),
                'ssn' => $faker->randomNumber(9),
                'gender' => $faker->randomElement(['M', 'F']),
                'position' => $faker->jobTitle(),
                'salary' => $faker->numberBetween(10000, 999999),
                'address' => $faker->streetAddress,
                'address2' => $faker->secondaryAddress,
                'city' => $faker->city(),
                'state' => $faker->state(),
                'zip' => $faker->randomNumber(5),
                'start_date' => $faker->date('Y-m-d'),
                'end_date' => $faker->date('Y-m-d')
            ]);
        }

    }
}
