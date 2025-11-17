<?php

namespace Database\Seeders;

use App\Models\SysRole;
use App\Models\SysUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRole extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developer = SysRole::firstOrCreate(['name' => 'developer']);
        $admin = SysRole::firstOrCreate(['name' => 'admin']);

        $userDeveloper = SysUser::firstOrCreate(
            ['email' => 'developer@example.com'],
            [
                'name' => fake()->name(),
                'password' => 'password',
                'username' => 'developer',
                'birthplace' => fake()->city(),
                'birthdate' => fake()->date(),
                'gender' => fake()->randomElement(['l', 'p']),
                'phone' => fake()->numerify('08##########'),
                'address' => fake()->address(),
            ]
        );

        $userAdmin = SysUser::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => fake()->name(),
                'password' => 'password',
                'username' => 'admin',
                'birthplace' => fake()->city(),
                'birthdate' => fake()->date(),
                'gender' => fake()->randomElement(['l', 'p']),
                'phone' => fake()->numerify('08##########'),
                'address' => fake()->address(),
            ]
        );

        $userDeveloper->assignRole($developer);
        $userAdmin->assignRole($admin);
    }
}
