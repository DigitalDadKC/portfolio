<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Raleigh',
                'email' => 'raleighgroesbeck@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('KSUwildcat#5'),
                'role' => ['admin', 'manager', 'guest', 'estimator']
            ],
            [
                'name' => 'Michael Scott',
                'email' => 'michaelscott@dundermifflin.com',
                'email_verified_at' => now(),
                'password' => bcrypt('KSUwildcat#5'),
                'role' => 'manager'
            ],
            [
                'name' => 'Dwight Schrute',
                'email' => 'dwightschrute@dundermifflin.com',
                'email_verified_at' => now(),
                'password' => bcrypt('KSUwildcat#5'),
                'role' => 'guest'
            ],
            [
                'name' => 'Jim Halpert',
                'email' => 'jimhalpert@dundermifflin.com',
                'email_verified_at' => now(),
                'password' => bcrypt('KSUwildcat#5'),
                'role' => 'estimator'
            ],
            [
                'name' => 'Pam Beesly',
                'email' => 'pambeesly@dundermifflin.com',
                'email_verified_at' => now(),
                'password' => bcrypt('KSUwildcat#5'),
                'role' => 'estimator'
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'email_verified_at' => $user['email_verified_at'],
                'password' => $user['password'],
            ])->assignRole($user['role']);
        }
    }
}
