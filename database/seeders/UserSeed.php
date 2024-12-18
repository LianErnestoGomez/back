<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $options = ['manager', 'revisor', 'comprador']; 
        for ($i = 0; $i < 100; $i++) {
            User::create([
                'name'  => $faker->name,
                'email' => $faker->email,
                'role'  => $faker->randomElement($options),                
            ]);
        }
    }
}
